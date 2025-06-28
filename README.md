# WPForms DataLayer Tracker

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-5.0+-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3+-green.svg)](https://www.gnu.org/licenses/gpl-3.0.html)

A lightweight WordPress plugin that automatically sends successful WPForms submissions to Google Tag Manager via dataLayer for enhanced tracking and analytics.

## 🚀 Features

- **Automatic Integration** - Works seamlessly with WPForms without any configuration
- **Google Tag Manager Ready** - Sends data directly to GTM's dataLayer
- **Privacy Focused** - Automatically excludes sensitive fields (customizable via filters)
- **AJAX Compatible** - Works with both AJAX and traditional form submissions
- **Field Normalization** - Automatically normalizes field names for consistency
- **Developer Friendly** - Includes filter hooks for customization
- **Lightweight** - Minimal performance impact on your site

## 📊 Data Structure

When a form is submitted, the following data is pushed to the dataLayer:
```
javascript
{
event: 'wpforms_submission',
form_id: 123,
form_name: 'Contact Form',
form_data: {
name: 'John Doe',
message: 'Hello world',
// ... other non-sensitive fields
},
entry_id: 456
}
```
### Excluded Fields (for privacy)
By default, these fields are excluded:
- `email`
- `telefono` 
- `correo_electrónico`
- `phone_number`
- `email_address`

## 🔧 Customization

### Exclude Additional Fields

You can customize which fields are excluded using the filter hook:
```
php
add_filter('wpforms_datalayer_excluded_fields', function($fields) {
// Add more sensitive fields
$fields[] = 'password';
$fields[] = 'credit_card';
$fields[] = 'ssn';

    return $fields;
});
```
### Modify DataLayer Data

You can modify the complete data before it's sent to the dataLayer:
```
php
add_filter('wpforms_datalayer_data', function($data, $form_data, $entry_id) {
// Add custom data
$data['custom_field'] = 'custom_value';

    // Modify existing data
    $data['form_name'] = 'Custom: ' . $data['form_name'];
    
    return $data;
}, 10, 3);
```
## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- WPForms plugin (free or pro version)
- Google Tag Manager configured on your site

## 🛠️ Installation

### Method 1: WordPress Admin (Recommended)
1. Download the plugin zip file
2. Go to `Plugins > Add New` in your WordPress admin
3. Click `Upload Plugin` and select the zip file
4. Install and activate the plugin

### Method 2: Manual Installation
1. Download and extract the plugin files
2. Upload the `wpforms-datalayer-tracker` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress

### Method 3: Development Setup
```
bash
git clone https://github.com/nxssie/wpforms-datalayer-tracker.git
cd wpforms-datalayer-tracker
# Copy to your WordPress plugins directory
```
## ⚙️ Configuration

No configuration needed! The plugin works automatically once activated. Just ensure you have:

1. ✅ WPForms installed and active
2. ✅ Google Tag Manager properly configured on your site
3. ✅ Forms created in WPForms

## 🎣 Available Hooks

### Filters

| Hook | Description | Parameters |
|------|-------------|------------|
| `wpforms_datalayer_excluded_fields` | Modify excluded field names | `$fields` (array) |
| `wpforms_datalayer_data` | Modify complete dataLayer data | `$data`, `$form_data`, `$entry_id` |

## 🔧 Development

### File Structure
```

wpforms-datalayer-tracker/
├── wpforms-datalayer-tracker.php  # Main plugin file
├── readme.txt                     # WordPress plugin readme
├── README.md                      # This file
└── LICENSE                        # GPL v3+ license
```
### Local Development
1. Clone the repository
2. Copy to your local WordPress plugins directory
3. Activate in WordPress admin
4. Test with WPForms and Google Tag Manager

## 🐛 Debugging

The plugin includes console logging for debugging. Open your browser's developer tools to see:

- Form submission detection
- DataLayer push confirmations
- Error messages if any issues occur

## ❓ FAQ

**Q: Do I need to configure anything after installation?**
A: No, the plugin works automatically once activated.

**Q: Is it compatible with AJAX forms?**
A: Yes, it's optimized for WPForms AJAX submissions and also works with traditional submissions.

**Q: Can I customize which fields are excluded?**
A: Yes! Use the `wpforms_datalayer_excluded_fields` filter to customize the exclusion list.

**Q: How do I add custom excluded fields?**
A: Add the filter hook to your theme's functions.php or a custom plugin as shown in the Customization section.

**Q: Does it affect site performance?**
A: No, the plugin is very lightweight and only executes when forms are submitted.

## 🔒 Privacy

This plugin:
- ❌ Does not store any personal data
- ❌ Does not send data to external servers
- ✅ Only pushes data to the browser's dataLayer
- ✅ Excludes sensitive fields by default
- ✅ Provides hooks for additional privacy customization
- ✅ Respects user privacy

**Note**: It's your responsibility to configure Google Tag Manager in accordance with applicable privacy regulations (GDPR, CCPA, etc.).

## 📝 Changelog

### 1.0.0 (Current)
- Initial release
- Basic WPForms integration
- Automatic dataLayer sending
- Sensitive field exclusion with filter hooks
- Support for AJAX and traditional submissions
- Developer-friendly customization options

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the GPL v3+ License - see the [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) file for details.

## 👨‍💻 Author

**nxssie**
- WordPress Profile: [@nxssie](https://profiles.wordpress.org/nxssie/)
- GitHub: [@nxssie](https://github.com/nxssie)

## ⭐ Support

If you find this plugin helpful, please consider:
- ⭐ Starring this repository
- 🐛 Reporting issues
- 💡 Suggesting new features
- 📝 Contributing to the code

---

Made with ❤️ for the WordPress community