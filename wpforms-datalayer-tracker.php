<?php
/**
 * Plugin Name: WPForms DataLayer Tracker
 * Plugin URI: https://github.com/nxssie/wpforms-datalayer-tracker
 * Description: Automatically sends successful WPForms submissions to Google Tag Manager via dataLayer for tracking and analytics.
 * Version: 1.0.0
 * Author: nxssie
 * Author URI: https://profiles.wordpress.org/nxssie/
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wpforms-datalayer-tracker
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WPFormsDataLayerTracker {
    
    public function __construct() {
        add_action('wpforms_process_complete', array($this, 'handle_form_submission'), 10, 4);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_jquery'));
        add_action('wp_footer', array($this, 'output_frontend_script'), 20);
        add_action('wp_ajax_get_wpforms_submission_data', array($this, 'handle_ajax_get_submission_data'));
        add_action('wp_ajax_nopriv_get_wpforms_submission_data', array($this, 'handle_ajax_get_submission_data'));
    }
    
    public function enqueue_jquery() {
        wp_enqueue_script('jquery');
    }
    
    public function handle_form_submission($fields, $entry, $form_data, $entry_id) {
        // Default excluded fields
        $default_excluded_fields = array(
            'email', 
            'telefono', 
            'correo_electrónico', 
            'phone_number', 
            'email_address'
        );
        
        // Apply filter to allow customization
        $exclude_fields = apply_filters('wpforms_datalayer_excluded_fields', $default_excluded_fields);
        
        $processed_fields = array();
        
        foreach ($fields as $field_id => $field) {
            $field_name = isset($field['name']) ? $field['name'] : 'field_' . $field_id;
            $normalized_name = $this->normalize_field_name($field_name);
            
            if (!in_array($normalized_name, $exclude_fields)) {
                $processed_fields[$normalized_name] = $field['value'];
            }
        }
        
        $data = array(
            'event' => 'wpforms_submission',
            'form_id' => $form_data['id'],
            'form_name' => $form_data['settings']['form_title'],
            'form_data' => $processed_fields,
            'entry_id' => $entry_id
        );
        
        // Apply filter to allow complete data modification
        $data = apply_filters('wpforms_datalayer_data', $data, $form_data, $entry_id);
        
        set_transient('wpfdt_last_submission', $data, 60);
    }
    
    private function normalize_field_name($name) {
        return strtolower(trim(preg_replace('/\s+/', '_', $name)));
    }
    
    public function get_excluded_fields_for_js() {
        $default_excluded_fields = array(
            'email', 
            'telefono', 
            'correo_electrónico', 
            'phone_number', 
            'email_address'
        );
        
        return apply_filters('wpforms_datalayer_excluded_fields', $default_excluded_fields);
    }
    
    public function output_frontend_script() {
        // Solo ejecutar en el frontend
        if (is_admin()) {
            return;
        }
        
        $excluded_fields = $this->get_excluded_fields_for_js();
        ?>
        <script type="text/javascript">
        // Esperar a que jQuery esté disponible
        (function() {
            function initWPFormsTracker() {
                if (typeof jQuery === 'undefined') {
                    setTimeout(initWPFormsTracker, 100);
                    return;
                }
                
                var $ = jQuery;
                
                $(document).ready(function() {
                    $(document).on('wpformsAjaxSubmitSuccess', function(event, formId, fields, form) {
                        console.log('WPForms submission detected:', {formId: formId, fields: fields, form: form});
                        
                        $.ajax({
                            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                            type: 'POST',
                            data: {
                                action: 'get_wpforms_submission_data',
                                nonce: '<?php echo esc_attr(wp_create_nonce('wpfdt_nonce')); ?>'
                            },
                            success: function(response) {
                                if (response.success && response.data) {
                                    window.dataLayer = window.dataLayer || [];
                                    window.dataLayer.push(response.data);
                                    console.log('DataLayer push successful:', response.data);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error retrieving submission data, using fallback:', error);
                                pushFormDataToDataLayer(formId, fields);
                            }
                        });
                    });
                    
                    function pushFormDataToDataLayer(formId, fields) {
                        var formData = {};
                        var excludeFields = <?php echo json_encode($excluded_fields); ?>;
                        
                        if (fields && typeof fields === 'object') {
                            $.each(fields, function(fieldId, field) {
                                var fieldName = field.name || 'field_' + fieldId;
                                var normalizedName = fieldName.trim().replace(/\s+/g, '_').toLowerCase();
                                
                                if (excludeFields.indexOf(normalizedName) === -1) {
                                    formData[normalizedName] = field.value || '';
                                }
                            });
                        }
                        
                        var dataLayerData = {
                            event: 'wpforms_submission',
                            form_id: formId,
                            form_data: formData
                        };
                        
                        // Apply any JavaScript filters if they exist
                        if (typeof wpforms_datalayer_modify_data === 'function') {
                            dataLayerData = wpforms_datalayer_modify_data(dataLayerData);
                        }
                        
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push(dataLayerData);
                        console.log('DataLayer fallback push successful:', dataLayerData);
                    }
                });
            }
            
            // Iniciar el tracker
            initWPFormsTracker();
        })();
        </script>
        <?php
    }
    
    public function handle_ajax_get_submission_data() {
        check_ajax_referer('wpfdt_nonce', 'nonce');
        
        $data = get_transient('wpfdt_last_submission');
        
        if ($data) {
            delete_transient('wpfdt_last_submission');
            wp_send_json_success($data);
        } else {
            wp_send_json_error('No submission data found');
        }
    }
}

// Initialize the plugin
new WPFormsDataLayerTracker();