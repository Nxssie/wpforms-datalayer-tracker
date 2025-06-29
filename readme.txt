=== WPForms DataLayer Tracker ===
Contributors: nxssie
Tags: wpforms, google tag manager, datalayer, tracking, analytics
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically sends successful WPForms submissions to Google Tag Manager via dataLayer.

== Description ==

WPForms DataLayer Tracker is a lightweight plugin that integrates WPForms with Google Tag Manager. When a user successfully submits a WPForms form, the plugin automatically sends the form data to GTM's dataLayer for tracking and analysis.

**Key Features:**

* Automatic integration with WPForms
* Sends data to Google Tag Manager dataLayer
* Configurable exclusion of sensitive fields (email, phone)
* Compatible with AJAX and traditional submissions
* Automatic field name normalization
* Clean and lightweight code

**Data sent to dataLayer:**

* Event: `wpforms_submission`
* Form ID
* Form name
* Form data (excluding sensitive fields)
* Entry ID

**Fields excluded by default:**
* email
* telefono
* correo_electrónico

== Installation ==

1. Upload the `wpforms-datalayer-tracker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure you have WPForms installed and active
4. Configure Google Tag Manager on your website
5. The plugin will work automatically without additional configuration

== Frequently Asked Questions ==

= Do I need to configure anything after installation? =

No, the plugin works automatically once activated. You just need to have WPForms and Google Tag Manager configured on your site.

= What data is sent to the dataLayer? =

All form fields are sent except email and phone fields for privacy reasons. The data includes form ID, form name, and all allowed fields.

= Is it compatible with AJAX forms? =

Yes, the plugin is optimized to work with WPForms AJAX submissions and also works with traditional submissions.

= Can I customize which fields are excluded? =

Currently, excluded fields are defined in the code. If you need to customize this list, you can modify the `$exclude_fields` array in the plugin file.

= Does it affect my site's performance? =

No, the plugin is very lightweight and only executes when a form is submitted. It doesn't add any additional weight to your site pages.

== Screenshots ==

1. Data sent to dataLayer visible in Google Tag Manager Preview
2. Data structure in the dataLayer

== Changelog ==

= 1.0 =
* Initial release
* Basic WPForms integration
* Automatic dataLayer sending
* Sensitive field exclusion
* Support for AJAX and traditional submissions

== Upgrade Notice ==

= 1.0 =
Initial plugin release.

== Requirements ==

* WordPress 5.0 or higher
* PHP 7.4 or higher
* WPForms plugin (free or pro version)
* Google Tag Manager configured on the site

== Support ==

For technical support or to report bugs, please contact the developer.

== Privacy Policy ==

This plugin does not store personal data. Form data is only sent to the browser's dataLayer for processing by Google Tag Manager. It is the user's responsibility to configure GTM in accordance with applicable privacy regulations (GDPR, etc.).