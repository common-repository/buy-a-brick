=== Plugin Name ===
Contributors: lagally
Donate link: http://www.ghtech.org/tools/plugins.html
Tags: charities, charity, donate, donate goals, donate meter, donations, fundraise, fundraising, fundraising goal, goal, nonprofit
Requires at least: 2.7.0
Tested up to: 3.3
Stable tag: 1.1

Plugin used to dynamically create CSS image maps for use in fundraising.  Admin panel includes XML editor for updating fundraising information.  

== Description ==

This plugin loads an XML file specifying different "bricks" used to make up a fundraising image map and generates a dynamic CSS image map based on the XML file for use as a fundraising meter within pages, posts, and more.

Features:

* Loads XML file and displays it as a form within the Administrator panel for editing
* Saves edited XML file 
* Parses XML file to generate dynamic image map, with opaque squares representing "bricks" that are not yet purchased and transparent squares representing "bricks" that have been purchased.
* Each brick has a hover button popup that directs the user to a donation page, shopping cart, etc.
* Completely customizable background image, XML file, popup image, and more

For support and further information about the Buy-a-Brick plugin see the plugins homepage at [http://www.ghtech.org/tools/plugins.html](http://www.gthech.org/tools/plugins.html).


== Installation ==

1.  Create and upload the sprite image, popup image, and XML file for use with the plugin.  See the [official plugin site](http://www.ghtech.org/tools/plugins.html) for details on how to do this, especially if you will be linking to Paypal from your image map.

2.  Edit the `brickstatic.css` file to match your Wordpress theme or fundraising page.  Replace the existing CSS file in the .zip file with the file you edited.

3.  Install and activate the Buy-a-brick plugin as normal.  

4.  Select the Buy-a-Brick Settings under the Options admin menu and fill out the form with the correct information.  

5.  Install, activate, and configure [exec-PHP](http://wordpress.org/extend/plugins/exec-php/) or another plugin that allows the execution of PHP code in Wordpress posts and pages.  

6.  Edit the header.php file for the Wordpress theme you are using.  Add the following lines somewhere above the `</head>` tag:

`<link type="text/css" href="<?php echo home_url().'/wp-content/plugins/buy-a-brick/brickstatic.css'; ?>" rel="stylesheet" media="screen">`

`<?php 
	if (is_page('[PAGE_SLUG]')) {
		require_once(ABSPATH.'wp-content/plugins/buy-a-brick/buyabrick-header.php');
} 
?>`

where `[PAGE_SLUG]` is the slug of the page you wish the image map to appear on.  You can also use any of the other conditional tags in Wordpress if you want the image map to appear elsewhere in the site (with a specific page template, in a post, etc.).  See the [official plugin page](http://www.gthech.org/tools/plugins.html) for more details.

7.  In the page, post, etc. where you want the image map to appear, add the following code:

`<?php
	require_once(ABSPATH.'/wp-content/plugins/buy-a-brick/buyabrick-page.php'); 
?>`

== Frequently Asked Questions ==

= How do I create the image sprite to use in the plugin? =

The sprite has two parts:  the bottom half should be the image you want to appear when bricks are purchased, with an identical version of the image directly above it with opacity applied to darken it for bricks that are not yet purchased.

= What is the format of the XML file? =

`<?xml version="1.0" encoding="utf-8"?> 

 <bricks> 

<item id="item-id-1" product_name="message-that-appears-in-popup-1" link_brick="url-that-popup-links-to" width="width-of-this-brick-in-px" height="height-of-this-brick-in-px" top=
upper-bound-of-this-brick-in-px" left="left-location-of-this-brick-in-px" type="2-if-purchased-1-otherwise" raised="total-raised-so-far-first-item-only" />

</bricks>`

An example XML file:

`<?xml version="1.0" encoding="utf-8"?> 

 <bricks> 

<item id="mc1" product_name="Purchase your Brick now $5000" link_brick="http://www.somesite.org/store/products-page/bricks/bricks4" width="99" height="49" top="0" left="0" type="1" raised="$3.700.00"/>

<item id="mc2" product_name="Mr. Testing Bricks" link_brick="http://www.somesite.org/store/products-page/bricks/bricks4" width="99" height="49" top="0" left="100" type="2" />

<item id="mc3" product_name="Purchase your Brick now $5000" link_brick="http://www.somesite.org/store/products-page/bricks/bricks4" width="99" height="49" top="50" left="0" type="1" /> 

<item id="mc4" product_name="Mrs. Testing Bricks" link_brick="http://www.somesite.org/store/products-page/bricks/bricks4" width="99" height="49" top="0" left="200" type="2" />

</bricks>`

== Screenshots ==

1. Plugin settings page

2. XML Editor screen

3. Fundraising image map showing purchased and unpurchased bricks

== Changelog ==

= 1.1 =
* Revised settings to allow for five fundraising levels and specification of completely different URLs to link to from each level.  This is for compatibility with Paypal buttons.

= 1.0.5 =
* Updated plugin to handle Paypal donation or purchase link within image map.

= 1.0 =
* First stable version

== Upgrade Notice ==

= 1.1 = 
This version allows specification of different Paypal button URLs for each of five fundraising levels in the image map while retaining the original functionality for users not linking to Paypal.

= 1.0.5 =
This version allows a single Paypal link to be used in the image map.  See main plugin site for details.

= 1.0 =
* First stable version

== Other Notes ==

**Plugin Official Site**
If you have questions about installation or use of this plugin, please visit the [official plugin site](http://www.ghtech.org/tools/plugins.html).


