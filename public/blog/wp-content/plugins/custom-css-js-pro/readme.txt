=== Simple Custom CSS and JS PRO ===
Created: 06/12/2015
Contributors: Diana Burduja
Tags: CSS, JS, javascript, custom CSS, custom JS, custom style, site css, add style, customize theme, custom code, external css, css3, style, styles, stylesheet, theme, editor, design, admin
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 3.10
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easily add Custom CSS or JS to your website with an awesome editor.

== Description ==

Customize your WordPress site's appearance by easily adding custom CSS and JS code without even having to modify your theme or plugin files. This is perfect for adding custom CSS tweaks to your site.

= Features =
* **Text editor** with syntax highlighting
* Print the code **inline** or included into an **external file**
* Print the code in the **header** or the **footer**
* Add CSS or JS to the **frontend** or the **admin side**
* Add as many codes as you want
* Keep your changes also when you change the theme

= Frequently Asked Questions =
* **Can I recover the codes if I previous uninstalled the plugin?**
No, on the `Custom CSS and JS` plugin's uninstall all the added code will be removed. Before uninstalling make sure you don't need the codes anymore.

* **What if I want to add multiple external CSS codes?**
If you write multiple codes of the same type (for example: two external CSS codes), then all of them will be printed one after another

* **Will this plugin affect the loading time?**
When you click the `Save` button the codes will be cached in files, so there are no tedious database queries.

* **Does the plugin modify the code I write in the editor?**
No, the code is printed exactly as in the editor. It is not modified/checked/validated in any way. You take the full responsability for what is written in there.

* **My code doesn't show on the website**
Try one of the following:
1. If you are using any caching plugin (like "W3 Total Cache" or "WP Fastest Cache"), then don't forget to delete the cache before seing the code printed on the website.
2. Make sure the code is in **Published** state (not **Draft** or **in Trash**).
3. Check if the `wp-content/uploads/custom-css-js` folder exists and is writable

* **Does it work with a Multisite Network?**
Yes.

* **What if I change the theme?**
The CSS and JS are independent of the theme and they will persist through a theme change. This is particularly useful if you apply CSS and JS for modifying a plugin's output.

* **Can I use a CSS preprocesor like LESS or Sass?**
No, for the moment only plain CSS is supported.

* **Can I upload images for use with my CSS?**
Yes. You can upload an image to your Media Library, then refer to it by its direct URL from within the CSS stylesheet. For example:
`div#content {
    background-image: url('http://example.com/wp-content/uploads/2015/12/image.jpg');
}`

* **Can I use CSS rules like @import and @font-face?**
Yes.

* **CSS Help.**
If you are just starting with CSS, then here you'll find some resources:
* [codecademy.com - Learn HTML & CSS](https://www.codecademy.com/learn/web)
* [Wordpress.org - Finding Your CSS Styles](https://codex.wordpress.org/Finding_Your_CSS_Styles)

== Installation ==

* From the WP admin panel, click "Plugins" -> "Add new".
* In the browser input box, type "Simple Custom CSS and JS".
* Select the "Simple Custom CSS and JS" plugin and click "Install".
* Activate the plugin.

OR...

* Download the plugin from this page.
* Save the .zip file to a location on your computer.
* Open the WP admin panel, and click "Plugins" -> "Add new".
* Click "upload".. then browse to the .zip file downloaded from this page.
* Click "Install".. and then "Activate plugin".

OR...

* Download the plugin from this page.
* Extract the .zip file to a location on your computer.
* Use either FTP or your hosts cPanel to gain access to your website file directories.
* Browse to the `wp-content/plugins` directory.
* Upload the extracted `custom-css-js` folder to this directory location.
* Open the WP admin panel.. click the "Plugins" page.. and click "Activate" under the newly added "Simple Custom CSS and JS" plugin.

== Frequently Asked Questions ==

= Requirements =
PHP >= 5.3

= Browser requirements =
* Firefox - version 4 and up
* Chrome - any version
* Safari - version 5.2 and up
* Internet Explorer - version 8 and up
* Opera - version 9 and up

= Small incompatibilities =
* If the [qTranslate X](https://wordpress.org/plugins/qtranslate-x/) plugin is adding some `[:]` or `[:en]` characters to your code, then you need to remove the `custom-css-js` post type from the qTranslate settings. Check out [this screenshot](https://www.silkypress.com/wp-content/uploads/2016/08/ccj_qtranslate_compatibility.png) on how to do that.

* The [HTML Editor Syntax Highlighter](https://wordpress.org/plugins/html-editor-syntax-highlighter/) plugin will make the Beautify and Fullscreen editor buttons not work properly.


== Changelog ==

= 3.10 =
* 11/14/2017
* Fix: change the ids of the loaded assets in admin in order to avoid conflicts
* Fix: remove the iframe footer hook in order to avoid conflict with the `HTML Editor Syntax Highlighter` plugin
* Fix: for revisions set the modal with `top` instead of `margin-top`

= 3.9 =
* 10/19/2017
* Declare compatibility with WooCommerce 3.2 (https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)
* Fix: avoid conflicts with other plugins that implement the CodeMirror editor
* Update the CodeMirror library to the 5.28 version

= 3.8 =
* 09/29/2017
* Fix: "Apply only on these Pages: First Page" needs to use home_url() instead of get_option('home') for multi-site installations
* Fix: allow the "Please activate the license" meta box

= 3.7 =
* 09/07/2017
* Fix: compatibility with the CSS Plus plugin

= 3.6 =
* 08/06/2017
* Fix: show the preview also when there are no other codes defined
* Tweak: comment about linking an external JS

= 3.5 =
* 07/27/2017
* Feature: prepare the plugin for translation
* Tweak: show date according to the get_option('date_format')
* Fix: the Custom Codes table is responsive for narrower screens
* Fix: initialize the codes with `wp` on frontend and `init` on backend.

= 3.4 =
* 07/15/2017
* Fix: rename the EDD_SL_Plugin_Updater class to avoid conflicts with other plugins that update with this class
* Security fix according to VN: JVN#31459091 / TN: JPCERT#91837758
* Add activate/deactivate link to row actions and in Publish box
* Make the activate/deactivate links work with AJAX
* Feature: option for adding Codes to the Login Page

= 3.3 =
* 06/13/2017
* Fix: compatibility issue with the HTML Editor Syntax Highlighter plugin
* Fix: remove htmlentities in the editor

= 3.2 =
* 04/04/2017
* Fix: allow codes in the backend

= 3.1 =
* 12/22/2016
* Feature: use WP Conditional Tags to restrict code
* Feature: wrap the lines in the editor
* Feature: Beautify Code editor button
* Feature: Fullscreen editor button
* Fix: cURL error because the activation license was done on silkypress.com:80

= 3.0 =
* 11/07/2016
* Feature: shortcodes functionality for the HTML codes
* Check: compatibility with WordPress 4.7

= 2.9 =
* 11/03/2016
* Fix: when Toolset Types plugin was enabled, the editor lost the colors

= 2.8 =
* 10/16/2016
* Fix: add stripslashes before preprocessors or minifiers, not after
* Fix: on activation along the free version there was a warning because both plugins used the same CCJ_VERSION constant

= 2.7 =
* 10/14/2016
* Feature: keep the cursor position after saving
* Fix: for empty allowed_codes() the search_tree() was still printed

= 2.6 =
* 08/24/2016
* Feature: add HTML code
* Feature: choose priority for the codes

= 2.5 =
* 08/03/2016
* Feature: Search functionality for the editor
* Feature: add "Add CSS Code" and "Add JS Code" buttons in order to be consequent with the WP admin interface
* Fix: make the editor wrapper adapt as width to the editor's line numbers gutter
* Fix: adapt the editor wrapper theme to the editor's theme
* Fix: incompatibility with other plugins that load CodeMirror (https://wordpress.org/support/topic/almost-everything-disabled)
* Fix: show warning when qTranslate is activated (https://wordpress.org/support/topic/conflict-with-qtranslate-x-2)
* Compatibility with WP 4.6: the "Modified" column in the Codes listing was empty

= 2.4 =
* 07/01/2016
* Fix: PHP warning for no codes in the $allowed_codes array
* Feature: change the editor's scrollbar so it can be caught with the mouse

= 2.3 =
* 06/24/2016
* Feature: "Debug Info" tab to the settings in order to help debugging
* Fix: when the Allowed Codes is empty, the Search Tree was not filtered

= 2.2 =
* 06/12/2016
* Initial commit

== Upgrade Notice ==

Nothing at the moment
