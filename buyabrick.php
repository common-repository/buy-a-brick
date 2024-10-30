<?php
/*
Plugin Name: Buy a Brick
Plugin URI: http://www.ghtech.org/tools/plugins.html
Description: This plugin loads an XML file specifying different "bricks" used to make up a fundraising image map.  
The plugin parses this file and displays the data in the admin tools menu as a form for editing as bricks are purchased.  
Once edited, the plugin saves the new file to the original location, overwriting the original file.  Compatible with Wordpress 2.7+
Version: 1.1
Author: Eric Lagally
Author URI: http://eric.lagallyconsulting.com
License: GPL2
*/





/*  Copyright 2012  Eric Lagally  (email : eric.lagally@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along     with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




// Add Options page under Settings menu
function buybrick_menu() {
	add_plugins_page('Buy a Brick Options', 'Buy a Brick Options', 'manage_options', 'buybrickplugin', 'buybrick_options_page');
}
// Add Management page under Tools menu - this is where the magic happens 
function buybrick_edit() {
	add_management_page('Buy a Brick Editor', 'Buy a Brick Editor', 'manage_options', 'buymybrick', 'buybrick_now');
}

function buybrick_options_page() {
	?>
	<div id="wrap">
		<form action="options.php" method="post">
		<?php settings_fields('buybrick_options'); ?>
		<?php do_settings_sections('buybrickplugin'); ?>
			<input name="Submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
	</div>
<?php
}

function buybrick_now() {
	include('buybrickparser.php');
}

 // add the admin settings and such


function buybrick_admin_init(){
	register_setting( 'buybrick_options', 'buybrick_options', 'buybrick_options_validate' );
	
	add_settings_section('buybrick_main', 'Buy a Brick Settings', 'buybrick_section_text', 'buybrickplugin');
	add_settings_field('buybrick_xmlpath', '<strong>Path to XML file</strong><br />Enter the name of the XML file to edit as a path from your website\'s home directory.', 'buybrick_xmlpath_string', 'buybrickplugin', 'buybrick_main');	
	add_settings_section('buybrick_main', 'Brick Levels and Links', 'buybrick_links_text', 'buybrickplugin');
	add_settings_field('buybrick_numfields', '<strong>Fundraising Levels</strong><br />Enter the various fundraising levels represented in the bricks, separated by commas (5 levels maximum).', 'buybrick_numfields_string', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_bricklinka', '<strong>Individual Brick Links</strong><br />For the first brick fundraising level, enter the link that bricks of that level will direct to.', 'buybrick_link_a', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_bricklinkb', '<strong>Individual Brick Links</strong><br />For the second brick fundraising level, enter the link that bricks of that level will direct to.', 'buybrick_link_b', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_bricklinkc', '<strong>Individual Brick Links</strong><br />For the third brick fundraising level, enter the link that bricks of that level will direct to.', 'buybrick_link_c', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_bricklinkd', '<strong>Individual Brick Links</strong><br />For the fourth brick fundraising level, enter the link that bricks of that level will direct to.', 'buybrick_link_d', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_bricklinke', '<strong>Individual Brick Links</strong><br />For the fifth brick fundraising level, enter the link that bricks of that level will direct to.', 'buybrick_link_e', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_imagepath', '<strong>Image Path</strong><br />Enter the full path (including http://) to the fundraising image.  See help for image requirements.', 'buybrick_imgpath_string', 'buybrickplugin', 'buybrick_main');
   add_settings_field('buybrick_imghigh', '<strong>Image Height</strong><br />Enter the height (in pixels) of the uncovered fundraising image.', 'buybrick_imghigh_string', 'buybrickplugin', 'buybrick_main');
   add_settings_field('buybrick_imgwide', '<strong>Image Width</strong><br />Enter the width (in pixels) of the uncovered fundraising image.', 'buybrick_imgwide_string', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_pbrickimg', '<strong>Purchased Brick Grout</strong><br />Enter the hex color code (no "#") for the purchased brick grout.', 'buybrick_pimage_string', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_upbrickimg', '<strong>Unpurchased Brick Grout</strong><br />Enter the hex color code (no "#") for the unpurchased brick grout.', 'buybrick_upimage_string', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_butimg', '<strong>Button Image</strong><br />Enter the full path (including http://) to the image for the popup button that appears over the bricks.', 'buybrick_butcol_string', 'buybrickplugin', 'buybrick_main');
	add_settings_field('buybrick_totposx','<strong>X Position of Total Raised</strong><br />Enter the horizontal position in the image (in pixels) to display the total raised so far.','buybrick_totposx_string','buybrickplugin','buybrick_main');
	add_settings_field('buybrick_totposy','<strong>Y Position of Total Raised</strong><br />Enter the vertical position in the image (in pixels) to display the total raised so far.','buybrick_totposy_string','buybrickplugin','buybrick_main');
}

 function buybrick_section_text() {
	echo '<p>Here you can set the settings for the Buy-a-Brick image map.';
} 

 function buybrick_links_text() {
	echo '<p>Here you can specify the various fundraising levels for bricks within the Buy-a-Brick image map as well as the URLs that bricks of each level link to.  If you are using Paypal button code, please select the "Email" tab within the Paypal button wizard and modify it as shown on the <a href="http://www.ghtech.org/tools/plugins.html">main plugin page</a>.  If you do not wish to use all of the five levels, please leave the corresponding settings fields blank.</p>';
}

function buybrick_xmlpath_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_xmlpath' name='buybrick_options[xmlpath]' size='40' type='text' value='{$options['xmlpath']}' />";
}

function buybrick_numfields_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_numfields' name='buybrick_options[numfields]' size='40' type='text' value='{$options['numfields']}' />";	
}

function buybrick_link_a() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_bricklink1' name='buybrick_options[bricklink1]' size='40' type='text' value='{$options['bricklink1']}' />";	
}

function buybrick_link_b() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_bricklink2' name='buybrick_options[bricklink2]' size='40' type='text' value='{$options['bricklink2']}' />";	
}

function buybrick_link_c() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_bricklink3' name='buybrick_options[bricklink3]' size='40' type='text' value='{$options['bricklink3']}' />";	
}

function buybrick_link_d() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_bricklink4' name='buybrick_options[bricklink4]' size='40' type='text' value='{$options['bricklink4']}' />";	
}

function buybrick_link_e() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_bricklink5' name='buybrick_options[bricklink5]' size='40' type='text' value='{$options['bricklink5']}' />";	
}

function buybrick_imgpath_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_imagepath' name='buybrick_options[imagepath]' size='40' type='text' value='{$options['imagepath']}' />";	
}

function buybrick_pimage_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_pbrickimg' name='buybrick_options[pbrickimg]' size='40' type='text' value='{$options['pbrickimg']}' />";	
}

function buybrick_upimage_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_upbrickimg' name='buybrick_options[upbrickimg]' size='40' type='text' value='{$options['upbrickimg']}' />";	
}

function buybrick_butcol_string() {
	$options = get_option('buybrick_options');
	echo "<input id='buybrick_butimg' name='buybrick_options[butimg]' size='40' type='text' value='{$options['butimg']}' />";
}

function buybrick_imghigh_string() {
   $options = get_option('buybrick_options');
	echo "<input id='buybrick_imghigh' name='buybrick_options[imghigh]' size='30' type='text' value='{$options['imghigh']}' />";
}

function buybrick_imgwide_string() {
   $options = get_option('buybrick_options');
	echo "<input id='buybrick_imgwide' name='buybrick_options[imgwide]' size='30' type='text' value='{$options['imgwide']}' />";
}

function buybrick_totposx_string() {
$options = get_option('buybrick_options');
echo "<input id='buybrick_totposx' name='buybrick_options[totposx]' size = '30' type='text' value='{$options['totposx']}' />";
}

function buybrick_totposy_string() {
$options = get_option('buybrick_options');
echo "<input id='buybrick_totposy' name='buybrick_options[totposy]' size = '30' type='text' value='{$options['totposy']}' />";
}
 // validate our options
function buybrick_options_validate($buybrick_options) {
	$patterns = array();
	$patterns[0] = '/\$/';
//	$patterns[1] = '/[a-z]/';
	$patterns[3] = '/[?@\\\]/';	
	
	$buybrick_options['xmlpath'] = preg_replace( $patterns, '', $buybrick_options['xmlpath'] );
	$buybrick_options['numfields'] = preg_replace( $patterns, '', $buybrick_options['numfields'] );
	$buybrick_options['bricklinka'] = htmlentities($buybrick_options['bricklinka']);
	$buybrick_options['bricklinka'] = str_replace('&',"&amp;",$buybrick_options['bricklinka']);
	$buybrick_options['bricklinkb'] = htmlentities($buybrick_options['bricklinkb']);
	$buybrick_options['bricklinkb'] = str_replace('&',"&amp;",$buybrick_options['bricklinkb']);
	$buybrick_options['bricklinkc'] = htmlentities($buybrick_options['bricklinkc']);
   $buybrick_options['bricklinkc'] = str_replace('&',"&amp;",$buybrick_options['bricklinkc']);	
	$buybrick_options['bricklinkd'] = htmlentities($buybrick_options['bricklinkd']);
   $buybrick_options['bricklinkd'] = str_replace('&',"&amp;",$buybrick_options['bricklinkd']);	
	$buybrick_options['bricklinke'] = htmlentities($buybrick_options['bricklinke']);
   $buybrick_options['bricklinke'] = str_replace('&',"&amp;",$buybrick_options['bricklinke']);	
	$buybrick_options['imagepath'] = preg_replace( $patterns, '', $buybrick_options['imagepath'] );
	$buybrick_options['pbrickimg'] = preg_replace( $patterns, '', $buybrick_options['pbrickimg'] );
	$buybrick_options['upbrickimg'] = preg_replace( $patterns, '', $buybrick_options['upbrickimg'] );
	$buybrick_options['butimg'] = preg_replace( $patterns, '', $buybrick_options['butimg'] );
	$buybrick_options['imgwide'] = preg_replace( $patterns, '', $buybrick_options['imgwide'] );
	$buybrick_options['imghigh'] = preg_replace( $patterns, '', $buybrick_options['imghigh'] );
	$buybrick_options['totpos'] = preg_replace( $patterns, '',$buybrick_options['totpos'] );
	return $buybrick_options;
}

add_action('admin_menu', 'buybrick_menu');
add_action('admin_menu', 'buybrick_edit');
add_action('admin_init', 'buybrick_admin_init');
add_action( 'admin_head', 'admin_register_buybrick_styles' );

//add stylesheet for plugin
function admin_register_buybrick_styles(){
	$url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'buybrick.css';
	echo "<link rel='stylesheet' href='$url' />\r\n"; 
}

//add a settings link on the plugins page
function buybrick_settings_link($links){

  $settings_link = '<a href="plugins.php?page=buybrickplugin">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", "buybrick_settings_link");
?>