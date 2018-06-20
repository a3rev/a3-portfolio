=== a3 Portfolio ===

Contributors: a3rev, nguyencongtuan
Tags: a3 Portfolio, Portfolio, Post Portfolio, Showcase, Image Showcase, Image Portfolio, Gallery, Photo Gallery, Image Gallery
Requires at least: 4.5
Tested up to: 4.9.6
Stable tag: 2.6.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

a3 Portfolio is an extendable post based plugin that makes creating beautiful content a breeze.

== Description ==

Inspired by Google Images UI a3 Portfolio is an image based creation and management extension for every blogger, artist, photographer, and web developer to showcase their own and clients work.


= SIMPLE TO USE =

* If you know how to use WordPress posts, categories and tags then you know how to use a3 Portfolios.
* Portfolio Items are custom post types.
* Portfolio Categories (custom WordPress taxonomy)
* Portfolio Tags (custom WordPress taxonomy)
* Portfolio Custom feature data
* Each Portfolio item has its own image gallery (add multiple images to each item).


= ITEM CARDS WITH BEAUTIFUL EXPANDER =

* Portfolio page, category Pages and Tag pages show Portfolio items as Cards.
* Item details show on click in Google Images inspired Expander item.
* Each Item has its own image Gallery
* Option to set the number of item cards to show per row.
* Option to show Item title on mouse over item card or under the card
* Option to show first part of description on Item cards
* Option to set the number of lines of description text to show on the cards


= ITEM POSTS =

* Each Item is created as a post
* WordPress Sticky post feature
* Set default posts to show as 1 column or 2 columns (Gallery to left and content to the right)
* On each post edit page use the Item Meta to customize the layout of that post (over rides global settings)
* Portfolio Item gallery manager in post meta
* Option to set width of the Gallery main image on item post.
* Option to show thumbnails beside main image or under it on item post


= PORTFOLIO ATTRIBUTES =

a3 Portfolio Attributes allows admins to create Attribute Name example 'Colours' and then create terms for it - example Blue, Black, Green. These terms are assigned to the item post and are used as additional data on the post and to Filter and Sort the Portfolio items from a widget.

* Create Attributes and terms for an attribute that can be applied to item posts.
* Option to show the Attributes and terms in a table on the Item Expander and the Item Post
* Portfolio Attributes Widget enables admin to set widget as additional Filters for users to view Portfolio Items


= PORTFOLIO WIDGETS =

* a3 Portfolio Attribute widget for advanced portfolio filter and sort
* a3 Portfolio Category widget for easy navigation
* a3 Portfolio Tag Cloud widget
* a3 Portfolio Recently viewed items widget.


= FREE ISOTOPE PLUGIN =

After installing a3 Portfolio please install and activate the a3 Portfolio Isotope Add-On. It is a FREE Add-On plugin that greatly enhances the Portfolio, Category and Tags page Items main images sort and filtering. It creates a real wow factor with the Portfolios for your site visitors.

* Isotope is a js. that is subject to a Commercial License which we have purchased.
* WordPress could not allow us to include the script into the a3 Portfolio core because of the Isotope commercial license.
* From the a3 Portfolio plugin admin dashboard go the Add-Ons Menu
* Click on the Get This Extension button on the Isotope plugin card.
* The link will take you to the Free Downloads section of the a3rev site.
* Register an account or if you have an existing a3rev account - use it to log in.
* Once logged in you will see the License Key and a Download Plugin button.
* Click the button and you will be asked to acknowledge that we supply the Plugin to you at no charge and that you are aware of and agree to be bound by the Isotope developers Commercial License terms and conditions.
* Copy the License Key and download the plugins zip file to your computer.
* Use the WordPress plugins installer to upload the zip from your computer.
* WordPress will unpack the zip and install the plugin.
* Click the activate link and on the page that opens and enter the Key you had copied and validate the License.
* If you have any existing Portfolio items clear any caching on your site and in your browser so you can see the beautiful Isotope sort and filter of the portfolio, category and tags pages.
* Notice that when you click on an item the expander now opens over the rows below and does not push them down below the expander window.
* Note the Isotope Add-On Key is a lifetime key for an unlimited number of site activations.


= EXTEND YOUR PORTFOLIO =

There is an emerging ecosystem of Premium plugins that extend the Portfolio - you will see these on the plugins admin panel

* a3 Portfolio Dynamic Style Sheets - Customize the style and layout of your portfolio item cards and item expander without touching the code with this plugin.
* a3 Dynamic Gallery - Converts the Item page and Item Expander static galleries into dynamic scrolling galleries with transition effects
* a3 Portfolio Item Switcher - Enables visitors to scroll through the entire portfolio without closing the item expander.
* a3 Portfolio Shortcodes - Add Portfolio items either individually, by recent, Sticky, Categories or Tags


= MORE FEATURES =

* HTML5 card Architecture - responsive mobile and tablet display.
* Fast loading with built in Lazy Load for all images.
* Lightweight - Portfolio resources only load on Portfolio Post items, Portfolio page, Category and Tags pages.
* WordPress Multi site ready.
* Full WMPL compatibility
* Backend and frontend support for RTL display.
* Translation ready


= DEVELOPERS =

a3 Portfolio works with any theme, including the default WordPress themes. Built in hooks and filters allow developers to create extensions with a robust template structure for easy customization.


= CONTRIBUTE =

When you download a3 Portfolio, you join our the a3rev Software community. Regardless of if you are a WordPress beginner or experienced developer if you are interested in contributing to the future development of this plugin head over to the a3 Portfolio [GitHub Repository](https://github.com/a3rev/a3-portfolio) to find out how you can contribute.

Want to add a new language to a3 Portfolio! You can contribute via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/a3-portfolio)


== Installation ==

= Minimum Requirements =

* WordPress 4.5
* PHP version 5.5 or greater
* MySQL version 5.5 or greater


= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of a3 Portfolio, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New. Search a3 Portfolio, select and install.


== Screenshots ==

1. Screenshot Portfolio-items-front-end
2. Screenshot Portfolio-items-front-end-open
3. Screenshot Portfolio-items-post
4. Portfolio-item


== Usage ==

1. Install and activate the plugin
2. On wp-admin click on a3 Portfolio
3. Click Add New and create items.
4. Go to WordPress menu Appearance > Menus
5. From Pages select the Portfolio page and add to main menu. Save menu
6. Enjoy


== Changelog ==

= 2.6.5 - 2018/06/20 =
* Maintenance update to fix a display issue in Chrome and Categories display issue on item posts plus a fix for Item Extender URL
* Tweak - Change priority value from 2 to 9 for apply single Portfolio template so that content shows correct
* Fix - Update JavaScript for trigger correct so that it opens popup from the item extender URL

= 2.6.4 - 2018/06/19 =
* Maintenance update has 1 bug fix and 1 tweak when adding shortcodes to posts to show in item expander.
* Tweak - Change priority value from 12 to 2 for apply single Portfolio template so that shortcode content can work correctly 
* Framework - Fix for Framework Global Box open and close settings options
* Framework - Update a3rev Plugin Framework to version 2.0.5
* Fix - Called wpautop before do_shortcode for the content show on Expander so that it does not add more space to content loaded by shortcode

= 2.6.3 - 2018/06/16 =
* Tweak - Test for compatibility with WordPress 4.9.6
* Fix - Add a3-notlazy class to Expander main gallery image to resolve conflict with a3 Lazy Load plugin

= 2.6.2 - 2018/05/26 =
* This maintenance update is for compatibility with WordPress 4.9.6 and the new GDPR compliance requirements for users in the EU 
* Tweak - Test for compatibility with WordPress 4.9.6
* Tweak - Check for any issues with GDPR compliance. None Found
* Framework - Update a3rev Plugin Framework to version 2.0.3

= 2.6.1 - 2018/03/07 =
* This is a big Maintenance Update. IMPORTANT clear all caches after this update. 3 Bug Fixes plus 2 Mobile UI and UX tweaks. Big fix for conflict with themes that filter Content in wp head.  
* Tweak - Mobile break point to display items one column in portrait on new Apple and Android wider screen phones.
* Tweak - Update script to remove auto close expander when scroll on mobile for improved UX
* Fix - Changed from filtering the content at wp head to filter the title and call wp content from there to resolve issues with themes that filter the content at wp head. 
* Fix - Item Cards alignment in PC and tablet for portfolio items without description. 
* Fix - Image Scale and Crop options which had stopped working

= 2.6.0 - 2018/03/05 =
* Feature Upgrade. This version adds full compatibility with the popular Divi premium theme by adding Category and Tags templates when that theme is used. 
* Feature -  Added taxonomy templates that are used when the Divi theme is activated.

= 2.5.2 - 2018/02/14 =
* Fix - Set correct name A3_PORTFOLIO_VERSION for update version

= 2.5.1 - 2018/02/13 =
* Maintenance Update. Under the bonnet tweaks to keep your plugin running smoothly and is the foundation for new features to be developed this year 
* Framework - Update a3rev Plugin Framework to version 2.0.2
* Framework - Add Framework version for all style and script files
* Tweak - Update for full compatibility with a3rev Dashboard plugin
* Tweak - Test for compatibility with WordPress 4.9.4

= 2.5.0 - 2017/06/10 =
* Feature - Launched a3 Portfolio public Github Repository
* Feature - WordPress Translation activation. Add text domain declaration in file header.
* Tweak - Tested for compatibility with WordPress major version 4.8.0
* Tweak - Include bootstrap modal script into plugin framework
* Tweak - Update a3rev plugin framework to latest version

= 2.4.6 - 2017/05/03 =
* Tweak - Tested for full compatibility with WordPress version 4.7.4
* Dev - Define new a3_portfolio_get_permalink_structure function to get new permalink structure of Portfolio on WP 4.7.4
* Fix - Set rewrite rules just for sub pages of main Portfolio pages to resolve conflicts with other plugins that have custom rewrite rules

= 2.4.5 - 2017/03/31 =
* Tweak - Changed facebook social share image size from thumbnail to medium size for better display when share from the Item Expander
* Tweak - Tested for full compatibility with WordPress version 4.7.3
* Fix - Update facebook social share parameters to rectify not being able to get the featured image in the facebook share pop up 

= 2.4.4 - 2017/02/09 =
* Tweak - Change global $$variable to global ${$variable} for compatibility with PHP 7.0
* Tweak - Removed auto redirect to plugins admin panel on activation
* Tweak - Update a3 Revolution to a3rev Software on plugins description
* Tweak - Added Settings link to plugins description on plugins menu
* Tweak - Tested for full compatibility with WordPress version 4.7.2
* Fix - Show $number as string with use str_replace instead of preg_replace. $ value amounts not showing in item expander description
* Fix - Update Portfolio script so that portfolio item cards display correctly without overlapping in mobile.

= 2.4.3 - 2017/01/03 =
* Tweak - Remove auto redirect to settings page when first install and activate the plugin
* Tweak - Add Settings link to the plugins place maker on Plugins menu
* Tweak - Update By A3 Revolution to a3rev Software on plugins place marker
* Fix - Remove space from gallery image ids value when add or remove images

= 2.4.2 - 2016/12/12 =
* Tweak - Tested for full compatibility with WordPress version 4.7
* Fix - Validate empty file that are parsed to a3_portfolio_get_template

= 2.4.1 - 2016/06/24 =
* Tweak - Update Portfolio Gallery box to enable a3 Portfolio Dynamic Gallery plugin to hook to it
* Tweak - Update 'a3.portfolio.js' script to support a3 Portfolio Dynamic Gallery plugin
* Tweak - Tested for full compatibility with WordPress version 4.5.3
* Fix - Check $post variable is defined
* Fix - Called 'wp_reset_query' at end of custom portfolio query to fix pagination can show items
* Fix - Hooked 'a3_portfolio_term_description' to 'a3_portfolio_before_category_content' instead of 'a3_portfolio_before_template_part' to resolve category description duplication on Portfolio Category pages

= 2.4.0 - 2016/05/19 =
* Feature - Created new 'Settings' panel page for plugin
* Feature - Add 'Portfolio Item Images' settings box for 'Settings' panel. Set Gallery and Thumbnail dimension
* Feature - Add Hard Crop option for Gallery images and Thumbnails
* Feature - Add lightbox image pop up on Portfolio Item post gallery image option. Image shows in the pop up at full size
* Tweak - Move 'Plugin Framework Global Settings' box from 'Item Cards' panel to 'Settings' panel.
* Tweak - Changed all image size from default WP to custom image dimensions set from the plugin. 
* Tweak - Updated 'a3.portfolio.css' style file for support new features
* Tweak - Updated 'a3.portfolio.single.js' script file for support new features
* Tweak - Change thumbnail image show on frontend from hard code 80 x 80 px to Gallery Thumbnail Size set from plugin settings
* Tweak - Updated 'content-single-portfolio.php' template file
* Tweak - Updated 'expander/gallery-thumbs.php' template file
* Tweak - Tested for full compatibility with WordPress version 4.5.2

= 2.3.0 - 2016/05/04 =
* Feature - Add new 'Image Links Opens' ITEM POST or ITEM EXPANDER option for when click on Card Image when Card Title is set Under Image
* Feature - Add new 'Title Links Opens' ITEM POST or ITEM EXPANDER option for when click on Card Title
* Feature - Add new 'View More Link Opens' ITEM POST or ITEM EXPANDER option for when click on View More button 
* Feature - Add new 'View More Button Text' option into Button tab of Portfolio Data box on individual Portfolio Item for support change the button text on each Portfolio item
* Tweak - Make condition logic for show or hide options that have relationship
* Tweak - Update 'a3.portfolio.css' and 'a3.portfolio.less' style file for support new features
* Tweak - Update 'a3.portfolio.js' and 'a3.portfolio.min.js' script file for support new features
* Tweak - Change layout structure of card item for support new features
* Tweak - Make On Hover Background screen just show on the Image Container instead of the whole Item Card
* Tweak - Filter for change View More Button Text on each card item.
* Tweak - Update Portfolio Permalinks for when Permalinks are set as pretty or plain
* Fix - Auto flush rewrite rules when first time plugin is activated for solved the issue return 404 page when go to Portfolio item detail page

= 2.2.0 - 2016/04/14 =
* Feature - Expander - Added option to set 'Thumbnail Position' on expander. Options are show on right or below main gallery
* Tweak - Update 'a3.portfolio.css' for new feature
* Tweak - Apply style and script for new term.php page on WordPress 4.5
* Tweak - Change hook tag from 'portfolio_cat_edit_form_fields' to 'portfolio_cat_edit_form' to fix settings box show on Portfolio Category Edit page
* Tweak - Apply settings box for Portfolio Category Add and Edit page
* Tweak - Update 'content-portfolio.php' template
* Tweak - Update 'gallery-thumbs.php' template
* Tweak - Tested for full compatibility with WordPress major version 4.5

= 2.1.0 - 2016/04/01 =
* Feature - Build new Portfolio Attribute feature as an upgrade of Feature Data
* Feature - Create Portfolio Attribute page and Terms page as custom taxonomy
* Feature - Portfolio Attribute with 2 types Select | Text .
* Feature - Attribute with 'Select' type support terms and for using to make filter from widget. 
* Feature - Attribute with 'Text' type to give the same feature as old Feature Data option
* Feature - Create new Attribute menu on the Portfolio Item Data box on Portfolio Edit page.
* Feature - Attribute meta box with select attributes and terms to apply to this item. 
* Feature - Attribute meta box with expand attribute and sort by drag and drop.
* Feature - Define Portfolio Attribute Filter Widget. This new widget is used to filter multiple Attribute based terms.  
* Feature - Portfolio Attribute Filter Widget can add to Sidebar but just show on main Portfolio page or Portfolio Category page or Portfolio Tag page, don't show on another page.
* Feature - Define new settings for select position to show Attribute Table on Expander and Item Post.
* Feature - Upgrade all current icons to fontawesome icons
* Tweak - Update admin meta box style and script from plugin framework
* Tweak - Register fontawesome in plugin framework with style name is 'font-awesome-styles'
* Tweak - Register jquery blockUI script to support new features
* Tweak - Update 'a3.portfolio.css' file to support new features
* Tweak - Update 'a3.portfolio.metabox.admin.css' file to support new features
* Tweak - Update 'a3.portfolio.js' file to support new features
* Tweak - Update 'a3.portfolio.metabox.admin.js' file to support new features
* Tweak - Include new a3-portfolio-ajax.php file to support AJAX from plugin
* Tweak - Remove Feature Data page and Feature Data tab from Portfolio Item Data box
* Tweak - Make upgrade function for auto convert data from Feature Data to Attribute data as 'Text' type
* Tweak - Update 'content-portfolio.php' template
* Tweak - Update 'content-single-portfolio.php' template
* Tweak - Update 'entry-metas.php' template
* Tweak - Update 'category-navbar.php' template
* Tweak - Update 'main-navbar.php' template
* Tweak - Update 'tag-navbar.php' template
* Tweak - Define new 'attribute-table.php' template
* Tweak - Define new 'attribute-filter-widget.php' template

= 2.0.0 - 2016/03/22 =
* Feature - Plugin Framework Mobile First focus upgrade
* Feature - Massive improvement in admin UI and UX in PC, tablet and mobile browsers
* Feature - Introducing opening and closing Setting Boxes on admin panels.
* Feature - Added Plugin Framework Customization settings box. Control how the admin panel settings show when editing.
* Feature - Added Option to set Google Fonts API key to directly access latest fonts and font updates from Google
* Feature - Added Housekeeping function to Plugin Framework Global Settings. Clean up on Deletion.  Option - Choose if you ever delete this plugin it will completely remove all tables and data it created.
* Tweak - Update plugin framework to latest version
* Tweak - Register fontawesome in plugin framework with style name 'font-awesome-styles'
* Tweak - Separate Settings tab to 2 Item Cards settings tabs and Item Expander settings tab for better UI
* Tweak - Remove Add-on page, put available Add-ons on right sidebar
* Tweak - Move Watermark help text onto the a3 Plugin Framework settings box
* Tweak - Change Title switch name from ON MOUSE OVER to SHOW ON HOVER for better clarity
* Tweak - Upgraded various setting box titles, option names and help text
* Tweak - Make upgrade function for auto convert old structure to new structure of settings

= 1.9.0 - 2016/03/18 =
* Feature - Added full support for WordPress 'Sticky' posts feature to apply to Portfolio Items for themes that support the Sticky feature to show posts on the home page
* Feature - Created new Sticky options menu on the Portfolio Item meta box with ON | OFF switch to apply the Sticky feature to that Portfolio Item
* Tweak - Update 'a3_portfolio_get_main_page' function to parse 3 new arguments - Type, Number of Items, Show Navbar. They support Recent and Sticky now
* Tweak - Update 'a3_portfolio_get_categories_page' function for parse 2 new arguments - Number of Items, Show Navbar.
* Tweak - Update 'a3_portfolio_get_tags_page' function for parse 2 new arguments - Number of Items, Show Navbar.
* Tweak - Update 'a3.portfolio.metabox.admin.css' for new Sticky feature

= 1.8.0 - 2016/03/15 =
* Feature - Added new option to enter Item Card Custom Description instead of the first few lines of the item description
* Feature - New Card Description tab shows on Portfolio Item meta box when Item Card Description is switched ON in General Settings
* Feature - If enter a Custom Description it is used - if nothing is entered the fall back is get text for item card from the item description
* Tweak - Check if Item Card Description feature is OFF then don't show Card Description tab on Portfolio Item meta box
* Tweak - Sort include PHP file to fix PHP warning when add shortcode to page in the Dashboard
* Tweak - Update 'a3_portfolio_get_main_page' function to support Shortcode when called from 3rd party plugins

= 1.7.0 - 2016/03/05 =
* Feature - Add new Item Card 'View More' button. Button shows at bottom of the Item Card. When clicked it opens the item expander
* Feature - Add new Item Card 'View More' button display ON | OFF option
* Feature - Add new Item Expander 'Post Meta Cats' display ON | OFF option
* Feature - Add new Item Expander 'Post Meta Tags' display ON | OFF option
* Feature - Post title under image on item card now links to the post instead of opening the item expander
* Feature - Refactor item card CSS to support Image, Dynamic Title, Description and View More button layout 
* Tweak - Update a3 Portfolio styles and scripts for new behavior on open Expander
* Tweak - Updated 'archive-portfolio.php' template file
* Tweak - Updated 'content-portfolio.php' template file
* Tweak - Updated 'content-single-portfolio.php' template file
* Dev - Defined 'a3_portfolio_get_main_page' function. 3rd party plugin can use function to get main portfolio cards on any page
* Dev - Defined 'a3_portfolio_get_item_ids_page' function. 3rd party plugin can use function to get portfolios list cards from portfolio ids list on any page
* Dev - Defined 'a3_portfolio_get_categories_page' function. 3rd party plugin can use function to get portfolios list card from portfolio category ids list on any page
* Dev - Defined 'a3_portfolio_get_tags_page' function. 3rd party plugin can use function to get portfolios list card from portfolio tag ids list on any page
* Dev - Added new 'portfolio-categories.php' template file to support new functions
* Dev - Added new 'portfolio-tags.php' template file to support new functions
* Dev - Added new 'portfolio-item-cards.php' template file to support new functions

= 1.6.0 - 2016/03/03 =
* Feature - Add more Item Expander setting options
* Feature - Added Item Expander Post Meta ON | OFF options for Post Author and Post Date meta
* Feature - Added Item Expander Social Share ON | OFF options
* Feature - Add new settings 'Expander Top Alignment' to set value of alignment from top of screen when Expander open.
* Feature - Added apply Top Alignment to Mobile or custom value in px
* Tweak - Saved the time number into database for one time customize style and Save change on the Plugin Settings
* Tweak - Replace version number by time number for dynamic style file are generated by Sass to solve the issue get cache file on CDN server
* Tweak - Update a3 Portfolio script to move Expander container to top of screen when it opens
* Tweak - Update a3 Portfolio script to get Top Alignment value to change position of Expander container when open
* Tweak - Update 'entry-metas.php' template from path '/templates/expander/' to check to show or hide Author, Date, Social Share meta

= 1.5.1 - 2016/02/25 =
* Tweak - Update script for Auto scale the height of card image when is resizing the window for when set Fixed Height with % of Wide
* Tweak - Update script for show image on the gallery faster than when click on thumbnail inside the expander with decrease the time of effect show and hide the Processing bar
* Tweak - Update script for auto scroll to border bottom of activated card image when the expander is open for see full content from expander
* Tweak - Update script for auto scroll to border top of activated card image when the expander is close
* Tweak - Update Portfolio style for auto scale thumbnail image when viewing on Tablet

= 1.5.0 - 2016/02/22 =
* Feature - Major refactor of portfolio item cards fist load. Loads much faster with far less resources used
* Feature - Update portfolio scripts for support 'scrset' and 'sizes' for new WordPress v4.4 responsive images feature
* Feature - Support the Responsive Image with 2 new attribute 'scrset' and 'sizes' are put on card image, main image and thumbnail image for decrease the total size of images are load on frontend for small screen
* Feature - Added Item card 'Image Display Height' and 'Image Height as a % of Width' options.
* Feature - Set Height of Item Card Image Container as FIXED or DYNAMIC based % Width of Image Container.
* Feature - Added new loading UI when Expander is opened and loading the expander content
* Feature - Added new loading UI when Gallery image is loaded by clicking a thumbnail in the expander
* Feature - Define new 'Background Color' type on plugin framework with ON | OFF switch to disable background or enable it
* Feature - Define new function - hextorgb() - for convert hex color to rgb color on plugin framework
* Feature - Define new function - generate_background_color_css() - for export background style code on plugin framework that is used to make custom style
* Feature - Define new 'strip_methods' argument for Uploader type, allow strip http/https or no
* Tweak - Updated a3 Plugin Framework to the latest version
* Tweak - Defined 'A3_PORTFOLIO_VERSION' to parse into all portfolio scripts and styles for ask CDN get new scripts or styles when plugin is updated
* Tweak - Remove 'a3-portfolio' image size, use 'large' size from WordPress
* Tweak - Remove Item Card Image Thumbnails settings
* Tweak - Update style for thumbnails inside expander to remove excessive space between thumbnails
* Tweak - Remove lazyloaxt script and all addon of that script from a3 Portfolio plugin
* Tweak - Update expander/gallery-thumbs.php template
* Tweak - Tested for full compatibility with WordPress version 4.4.2
* Fix - Remove built in lazyload of all Portfolio images. Was causing load delays and browser freezing on first load when combine with a3 Portfolio Isotope plugin

= 1.4.0 - 2015/12/08 =
* Feature - Added Option to set Google Fonts API key to directly access latest fonts and font updates from Google
* Feature - Added full support for Right to Left RTL layout on plugins admin dashboard.
* Feature - Change media uploader to New UI of WordPress media uploader with WordPress Backbone and Underscore
* Tweak - Update the uploader script to save the Attachment ID and work with New Uploader
* Tweak - Change the heading on Plugin Settings from h2 to h1 with new WordPress v4.4 UI
* Tweak - Update the style for meta box at Portfolio Item Edit page
* Tweak - Updated a3 Plugin Framework to the latest version
* Tweak - Tested for full compatibility with WordPress major version 4.4

= 1.3.2 - 2015/08/19 =
* Feature - Added support for show content in the item expander and item post that is created by shortcode from other plugins
* Tweak - Tested for full compatibility with WordPress major version 4.3.0
* Tweak - include new CSSMin lib from https://github.com/tubalmartin/YUI-CSS-compressor-PHP-port into plugin framework instead of old CSSMin lib from http://code.google.com/p/cssmin/ , to avoid conflict with plugins or themes that have CSSMin lib
* Tweak - Make __construct() function for 'Compile_Less_Sass' class instead of using a method with the same name as the class for compatibility on WP 4.3 and is deprecated on PHP4
* Tweak - Change class name from 'lessc' to 'a3_lessc' so that it does not conflict with plugins or themes that have another Lessc lib
* Fix - Check 'request_filesystem_credentials' function, if it does not exists then require the core php lib file from WP where it is defined
* Fix - Make __construct() function for 'A3_Portfolio_Categories_Widget' class instead of using a method with the same name as the class for compatibility on WP 4.3 and is deprecated on PHP4
* Fix - Make __construct() function for 'A3_Portfolio_Recently_Viewed_Widget' class instead of using a method with the same name as the class for compatibility on WP 4.3 and is deprecated on PHP4

= 1.3.1 - 2015/06/03 =
* Tweak - Security Hardening. Removed all php file_put_contents functions in the plugin framework and replace with the WP_Filesystem API
* Tweak - Security Hardening. Removed all php file_get_contents functions in the plugin framework and replace with the WP_Filesystem API
* Fix - Update dynamic stylesheet url in uploads folder to the format //domain.com/ so it's always is correct when loaded as http or https

= 1.3.0 - 2015/05/11 =
* Feature - Added support for setting a custom permalink structure for Portfolio page
* Feature - Added support change the permalink structure for Portfolio Category page
* Feature - Added support change the permalink structure for Portfolio Tag page
* Tweak - Added new options into Settings -> Permalinks page on Dashboard
* Tweak - Tested and Tweaked for full compatibility with WordPress Version 4.2.2
* Tweak - Put <code>%[portfoliopage]%</code> as arguments for $wpdb-prepare than parse it direct to mysql query
* Tweak - Don't auto added #hash on url bar of browser when first reload the page has portfolios

= 1.2.1 - 2015/04/21 =
* Tweak - Tested and Tweaked for full compatibility with WordPress Version 4.2.0
* Tweak - Changed <code>dbDelta()</code> function to <code>$wpdb->query()</code> for creating plugin table database.
* Tweak - Added style for social icon to a3.portfolio
* Tweak - Update style of plugin framework. Removed the [data-icon] selector to prevent conflict with other plugins that have font awesome icons
* Fix - Thumb images are not showing on single portfolio page. Added <code>a3-portfolio-lazy-hidden</code> into <code>a3.portfolio.single.js</code> file

= 1.2.0 - 2015/04/17 =
* Feature - Added option to show the first part of the Item description text on item cards. Description shows on footer of the card.
* Feature - Added 'Description Height' feature to set number lines of description text that shows on item cards. Option is 1 - 6 lines
* Tweak - Numerous a3.portfolio.js script file tweaks to add support for newly released a3 Portfolio shortcode add-on.
* Dev - Defined <code>@desc_background_color</code> variable inside a3.portfolio.base.less so dev can change the background color for new Description feature. Default color is <code>#555555</code>
* Dev - Added .a3-portfolio-card-description class into a3.portfolio.less so dev can change the default style for the new Item Description feature and compile own style for theme
* Dev - Defined 'a3_portfolio_card_item_description' function to get the description container of portfolio
* Dev - Defined 'a3_portfolio_main_page_scripts' function that enqueues all portfolio scripts and supports load portfolio container. This adds support for 3rd party plugins including the new a3 Portfolio Shortcodes add-on to call portfolio script from another page

= 1.1.1 - 2015/04/01 =
* Tweak - Added New Release a3 Portfolio Item Switcher Add-On plugin Card to the Add-Ons menu
* Tweak - Mobile UX improvement - increased the size of the item expander close X icon in mobile browsers so that it is easier to tap
* Tweak - Mobile UI improvement - added border to the bottom the Item expander gallery main image container to better define the image
* Tweak - Updated the plugins wordpress.org description.
* Dev - Convert function inside a3.portfolio.js file to global function so that the function can be called from another plugin
* Dev - Defined the trigger 'a3_portfolio_open_expander' when the expander is opened
* Dev - Changed WP_CONTENT_DIR to WP_PLUGIN_DIR. Is set a custom WordPress file structure then it can get the correct path of plugin

= 1.1.0 - 2015/03/27 =
* Feature - Added new global option - Post Display tab - Single Portfolio Template option of 1 or 2 column layout
* Feature - Added new post meta for Portfolio Item - Post Display - admin can override the global 1 or 2 Column layout option for post item.
* Dev - Defined new function 'a3_portfolio_single_get_layout_column' to get layout column for each single post item
* Dev - Updated header comment in portfolio template files from '/portfolio/' to '/portfolios/' for template over-rides from theme.
* Dev - Note! Updated single-portfolio.php template file for new Single Layout Column. Custom template over-rides will need updating
* Dev - Note! Updated content-single-portfolio.php template file for new feature Single Layout Column. Custom template over rides will need updating.
* Tweak - Tweak the Meta Box UI with checkbox switcher to toggle 1 Column | 2 Column layout

= 1.0.5 - 2015/03/18 =
* Tweak - Tested and tweaked for 100% compatibility with WordPress Version 4.1.1
* Tweak - Added jQuery.lazyLoadXT.onshow.addClass='lazy-hidden a3-portfolio-lazy-hidden'; into Portfolio script so that it still show the image for portfolio when site is using a3 lazy load
* Fix - Called wp_enqueue_script( 'jquery-masonry' ); when Portfolio page is showing to fix the javascript error that masonry script is not function

= 1.0.4 - 2015/01/28 =
* Tweak - Add New Release a3 Portfolio Dynamic Stylesheet Add-On plugin Card to the Add-Ons menu
* Tweak - Added an image to the a3 Portfolio Isotope Add-On Card on the Add-Ons menu

= 1.0.3 - 2015/01/26 =
* Tweak - Update background of portfolio mobile control to be the same background color of item expander.
* Dev - Defined 'a3_portfolio_cards_title_class' filter tag. Developers can filter to add new class name for Card Title on Card Layout
* Dev - Defined 'a3_portfolio_backend_launch_button_text' filter tag. Developers can filter to change Launch button text that shows inside the item Expander
* Fix - Change <code>parameter == undefined</code> to <code>typeof parameter === 'undefined'</code> inside portfolio script to fix JavaScript undefined parameter error

= 1.0.2 - 2015/01/12 =
* Fix - Fatal Error on first install on some installs. Hook the 'set_default_settings' function into 'init' action instead hook into 'register_activation_hook'
* Fix - Item main image caption not showing. Updated if <code>( ! is_array( $gallery ) )</code> to <code>if ( is_array( $gallery ) )</code>
* Credit - Thanks to wordpress.org memeber [jmdev](https://wordpress.org/support/profile/jmdev) for reporting the issue.

= 1.0.1 - 2015/01/05 =
* Feature - Add a3 Portfolio Isotope Add-On Free plugin download from the Add-On's menu
* Tweak - Updated the plugins description with details about the Isotope Add-On plugin
* Tweak - Link from the Isotope Add-On plugin to http://a3rev.com/my-account/free-downloads/
* Tweak - Add Registration and Login features to the /free-downloads/ page
* Tweak - Add License agreement for first download of a3 Portfolio Isotope plugin
* Tweak - Added effect mouse over on the card for Add-On page
* Tweak - Remove custom colour for Add-On submenu title
* Dev - Hooked framework code into 'plugins_loaded' action. Allows developers to add their child plugin admin menu as a a3 Portfolio sub menu
* Dev - Defined 'a3_portfolio_before_portfolio_enqueue_styles' action tag
* Dev - Defined 'a3_portfolio_after_portfolio_enqueue_styles' action tag
* Dev - Defined 'a3_portfolio_before_portfolio_enqueue_styles_rtl' action tag
* Dev - Defined 'a3_portfolio_after_portfolio_enqueue_styles_rtl' action tag
* Dev - Defined 'a3_portfolio_before_single_enqueue_styles' action tag
* Dev - Defined 'a3_portfolio_after_single_enqueue_styles' action tag
* Dev - Defined 'a3_portfolio_before_single_enqueue_styles_rtl' action tag
* Dev - Defined 'a3_portfolio_after_single_enqueue_styles_rtl' action tag

= 1.0.0 - 2014/12/16 =
* First working release


== Upgrade Notice ==

= 2.6.5 =
Maintenance update to fix a display issue in Chrome and Categories display issue on item posts plus a fix for Item Extender URL

= 2.6.4 =
Maintenance update has 1 bug fix and 1 tweak when adding shortcodes to posts to show in item expander.

= 2.6.3 =
Maintenance Update. Resolves a conflict with a3 Lazy Load and compatibility with WordPress v 4.9.6

= 2.6.2 =
Maintenance Update. Compatibility WordPress 4.9.6 and the new GDPR compliance requirements for users in the EU

= 2.6.1 =
Big Maintenance Update. Important! - Clear all caches after this update, including your browser cache. 3 Bug Fixes plus 2 Mobile UI and UX tweaks. Big fix for conflict with themes that filter Content in wp head.

= 2.6.0 =
Feature Upgrade. This version adds full compatibility with the popular Divi premium theme by adding Category and Tags templates when that theme is used.

= 2.5.2 =
Maintenance Update. 1 bug fix appear on a3 Portfolio 2.5.1

= 2.5.1 =
Maintenance Update. This version updates the Plugin Framework to v 2.0.2, adds full compatibility with a3rev dashboard and WordPress v 4.9.4

= 2.5.0 =
Feature Upgrade. 2 code tweaks for compatibility with WordPress 4.8 plus launch of plugins public Github Repo

= 2.4.6 =
Maintenance Update. 1 bug fix and major permalink tweak for compatibility with WordPress 4.7.4

= 2.4.5 =
Maintenance Update. 1 bug fix and 1 code tweak and full compatibility with WordPress version 4.7.3

= 2.4.4 =
Maintenance update. 2 bug fixes and 4 code tweaks for full compatibility with WordPress v 4.7.2 and PHP 7.0

= 2.4.3 =
Maintenance Update. 1 bug fix and 3 tweaks with full compatibility with WordPress version 4.7.0

= 2.4.2 =
Maintenance Update. 1 bug fix and full compatibility with WordPress version 4.7

= 2.4.1 =
Maintenance Update. 2 tweaks for Portfolio Dynamic Gallery plugin plus 3 bug fixes for full compatibility with WordPress version 4.5.3

= 2.4.0 =
Feature Upgrade. New General Settings - Settings tab with Gallery and Thumbnail Dimension settings. NOTE! After upgrade please check the settings and change if necessary. Image sizes are changed from your WordPress settings to custom settings.

= 2.3.0 =
Feature Upgrade. 4 new features, 7 code Tweaks, 1 bug fix and full compatibility with WordPress version 4.5.1

= 2.2.0 =
Feature Upgrade. Added option to set Gallery Thumbnails position on item expander plus 6 code tweaks for full compatibility with WordPress major version 4.5

= 2.1.0 =
Major Feature Upgrade. Introducing Portfolio Attributes and Portfolio Filter by Attributes widget. 12 new features and 18 code tweaks.

= 2.0.0 =
Major Feature Upgrade. Version 2.0.0 is the a3 plugin framework mobile first upgrade. 6 new features and 7 Tweaks - see it all on the General Settings menu of the plugin.

= 1.9.0 =
Feature Upgrade. Added full support for WordPress 'Sticky' post feature. If your theme supports Sticky, you can now auto show Portfolio Items by Sticky on your home page. Also fully supported in a3 Portfolio shortcodes plugin

= 1.8.0 =
Feature Upgrade. 3 new features that add the option to enter a custom item card description instead of using the first few lines of item description. Also 3 code tweaks.

= 1.7.0 =
Feature Upgrade. 6 new features and a complete refactor of the item card CSS. Card now supports View More button option. 3 code tweaks plus 4 new developer functions and 3 new template files

= 1.6.0 =
Feature Upgrade. 5 new features and 5 code tweak that add more settings options for the item expander. New settings are on General Settings > Settings tab

= 1.5.1 =
Maintenance Upgrade. 5 portfolio item card and item expander code tweaks for greatly improved item card / expander UI

= 1.5.0 =
Major Feature Upgrade. IMPORTANT! Be sure to upgrade a3 Portfolio Isotope plugin to v 1.2.0 after running this update. 11 new features, 8 major code tweaks and 1 bug fix plus full compatibility with WordPress version 4.4.2

= 1.4.0 =
Feature Upgrade. 3 new features plus 4 tweaks for full compatibility with WordPress major version 4.4

= 1.3.2 =
Major Maintenance Upgrade. 3 Code Tweaks plus 3 bug fixes for full compatibility with WordPress major version 4.3.0 plus 1 new feature.

= 1.3.1 =
Important Maintenance Upgrade. 2 x major a3rev Plugin Framework Security Hardening Tweaks plus 1 https bug fix

= 1.3.0 =
Feature Upgrade. Added support for set custom portfolio permalinks on WordPress admin panel Settings > Permalinks menu, plus 2 Code Tweaks for full compatibility with WP v 4.2.2

= 1.2.1 =
Maintenance upgrade. Code tweaks for full compatibility with WordPress 4.2.0 plus 1 bug fix.

= 1.2.0 =
Major Feature upgrade - 2 new Show description font on Item Card features plus many code Tweaks and dev functions added for support new a3 Portfolios Shortcode plugin

= 1.1.1 =
Mobile user experience and interface Tweaks plus additional Dev functionality - Important! Be sure to Clear your site cache after updating and check the new Item Switcher Add-On.

= 1.1.0 =
Major feature ugrade - update now for 2 new features, 1 Tweak and 4 Developer related changes code changes.

= 1.0.5 =
Upgrade now for full compatibility with WordPress v 4.1.1 plus an a3 Lazy Load compatibility tweak and 1 important bug fix.

= 1.0.4 =
Upgrade now and check the plugins Add-on menu for new release a3 Portfolio Dynamic Stylesheets Add-on plugin listing.

= 1.0.3 =
Upgrade now for 1 Mobile display code tweak, 2 new dev filters and 1 bug fix

= 1.0.2 =
Upgrade now for 2 bug fixes. Fatal Error on fist activation on some installs and item main image caption not showing.

= 1.0.1 =
Upgrade now to gain access to the FREE Isotope Add-On plugin from the Add-On menu to greatly improve a3 Portfolio sort and filter.
