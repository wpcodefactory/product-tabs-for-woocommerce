=== Additional Custom Product Tabs for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, product tabs, product, tab, tabs, customize, custom, woo, commerce
Requires at least: 4.4
Tested up to: 6.5
Stable tag: 1.6.2
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage product tabs in WooCommerce. Beautifully.

== Description ==

**Additional Custom Product Tabs for WooCommerce** plugin lets you:

* Customize WooCommerce **standard product tabs**.
* Add **custom product tabs** globally for all products, or on per product basis.
* And more...

### &#9989; Standard WooCommerce Product Tabs ###

For standard WooCommerce product tabs (i.e., "Description", "Additional information" and "Reviews") you can:

* **Remove** tab completely for all products.
* Change tab's **position**.
* Change tab's **title**.

### &#9989; Custom Product Tabs ###

For each custom product tab you can set:

* Tab's **title**.
* Tab's **ID** (i.e., link).
* Tab's **position**.
* Tab's **content**.

For each global (i.e., all products) product tab you can also set **products/categories to show/hide** it for.

### &#9989; Shortcodes ###

In product tab's title and content you can use plugin's shortcodes: `[alg_wc_pt_product_meta]` and `[alg_wc_pt_product_function]`. This is especially useful in global (i.e., all products) tabs. For example, you can output some custom field value in your custom tabs for all products at once:

`
[alg_wc_pt_product_meta key="my_custom_field"]
`

Or, for example, you could add stock quantity info:

`
Stock: [alg_wc_pt_product_function name="get_stock_quantity"]
`

### &#127942; Premium Version ###

While free version allows you to add one global custom tab and one "per product" tab for each product, with [Product Tabs for WooCommerce Pro](https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/) you can add an **unlimited** number of custom product tabs.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to "WooCommerce > Settings > Product Tabs".

== Screenshots ==

1. Plugin admin area.
2. Setting product tabs on per product basis.

== Changelog ==

= 1.6.2 - 30/07/2024 =
* WC tested up to: 9.1.

= 1.6.1 - 20/05/2024 =
* WC tested up to: 8.8.
* Tested up to: 6.5.

= 1.6.0 - 06/01/2024 =
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Code refactoring.
* WC tested up to: 8.4.

= 1.5.6 - 20/11/2023 =
* Plugin name updated.

= 1.5.5 - 20/11/2023 =
* WC tested up to: 8.3.
* Tested up to: 6.4.

= 1.5.4 - 25/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.
* Plugin icon, banner updated.

= 1.5.3 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.5.2 - 09/11/2022 =
* WC tested up to: 7.1.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 1.5.1 - 02/03/2022 =
* Dev - Minor code refactoring.
* WC tested up to: 6.2.
* Tested up to: 5.9.

= 1.5.0 - 22/09/2021 =
* Dev - Admin settings restyled ("General", "Standard Tabs", etc. sections).
* Dev - Properly sanitizing all input in admin settings now.
* Dev - Plugin is initialized on the `plugins_loaded` action now.
* Dev - Code refactoring.
* WC tested up to: 5.7.
* Tested up to: 5.8.

= 1.4.1 - 02/03/2021 =
* Fix - JS file minification bug fixed.
* WC tested up to: 5.0.

= 1.4.0 - 19/01/2021 =
* Dev - "Variations Tabs" section added.
* Dev - Standard Tabs - Shortcodes are applied in "Title" options now.
* Dev - Standard Tabs - "Enable section" option added (defaults to `yes`).
* Dev - Custom Tabs: All Products - "Enable section" option added (defaults to `yes`).
* Dev - Shortcodes - `[alg_wc_pt_product_function]` shortcode added.
* Dev - Shortcodes - `[alg_wc_pt_product_meta]` shortcode added.
* Dev - Shortcodes - `[alg_wc_pt_translate]` shortcode alias added (i.e., same as `[alg_wc_cpt_translate]`).
* Dev - Localization - `load_plugin_textdomain()` moved to the `init` action.
* Dev - JS file minified.
* Dev - Code refactoring.
* Dev - Admin settings restyled and descriptions updated.
* Tested up to: 5.6.
* WC tested up to: 4.9.

= 1.3.0 - 03/01/2020 =
* Dev - Tab "ID" options added.
* Dev - Triggering tab open with JavaScript now.
* Dev - Multi-language (i.e., WPML/Polylang) shortcode `[alg_wc_cpt_translate]` added.
* Dev - Code refactoring.
* Dev - Admin settings restyled and descriptions updated.
* WC tested up to: 3.8.
* Tested up to: 5.3.

= 1.2.0 - 06/06/2019 =
* Dev - Admin Settings - "Reset Settings" option added.
* Dev - Admin settings restyled.
* Dev - Code refactoring.
* Tags updated.
* Plugin URI updated.
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.1.3 - 10/04/2018 =
* Dev - All Products Tabs - "Products Show/Hide Options Type" option added (Multiselect / IDs / SKUs).
* Dev - Shortcodes are now processed in all custom tabs titles.

= 1.1.2 - 05/04/2018 =
* Dev - All Products Tabs - "Add SKU to Products Lists" option added.
* Dev - All Products Tabs - "Content" now accepts all HTML tags.
* Dev - All Products Tabs - Settings minor restyling.
* Dev - "WC tested up" added to the plugin header.
* Dev - Settings array is saved as main class property.

= 1.1.1 - 24/07/2017 =
* Dev - WooCommerce v3 compatibility - Product ID.
* Dev - `WP_Query` optimized to return `ids` only.
* Plugin URI updated.
* Plugin header ("Text Domain" etc.) updated.

= 1.1.0 - 17/12/2016 =
* Fix - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Fix - General - Tab active link fixed.
* Dev - Version system added.
* Dev - Multisite compatibility added.
* Dev - Language (POT) file added.
* Dev - Custom Product Tabs: per Product - "Enable WP Editor" option added.
* Dev - Code refactoring.
* Tweak - Language domain name changed.
* Tweak - Donate link added.

= 1.0.0 - 02/10/2015 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
