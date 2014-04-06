<?php
/**
Plugin Name: Weblines Base
Description: Essential elements for most sites
Version: 1.0
Author: Weblines
Author URI: http://weblines.com.au
*/
// CUSTOMISE THE ADMIN
// Customise Admin Menu Order
function custom_menu_order($menu_ord) {
   if (!$menu_ord) return true;
   return array(
    'index.php', // this represents the dashboard link
    'edit.php?post_type=page', // this is the default page menu
    'edit.php', // this is the default POST admin menu
    'upload.php', // this is the media menu
    'separator1', // this is a separator
    //'users.php', // this is the users menu
);
}
add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');

// and Move Updates to a different sub-menu
function move_submenus() {
  global $submenu;
  // Remove 'Updates' from Dashboard menu
  unset($submenu['index.php'][10]);
  // Now add it to the Tools menu
  add_management_page('WordPress Updates', 'Updates', 'manage_options', 'update-core.php');
}
add_action('admin_menu', 'move_submenus');

// end customised admin

// Customised Login Logo, URL and Link Text
// Recommended max size for logo 320 x 80
function custom_login_logo() {
    echo '<style type="text/css">h1 a { background: white url(' . get_stylesheet_directory_uri() . '/images/logo-login.gif) 50% 50% no-repeat !important; width: 320px !important; margin-bottom:0 !important; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);}</style>';
}
function change_wp_login_url() {
    $myurl = get_bloginfo('siteurl');  // OR ECHO YOUR OWN URL
    return $myurl;
}
function change_wp_login_title() {
    $myname = get_bloginfo('name'); // OR ECHO YOUR OWN ALT TEXT
    return $myname;
}
add_action('login_head', 'custom_login_logo');
add_filter('login_headerurl', 'change_wp_login_url');
add_filter('login_headertitle', 'change_wp_login_title');

// end customised admin

// Remove Meta Generator
remove_action('wp_head', 'wp_generator');

// Make YouTube videos sit behind drop-menus
// plus new fix add ?wmode=transparent to src of iframe
function add_video_wmode_transparent($html, $url, $attr) {
   if (strpos($html, "<embed src=" ) !== false) {
    	$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed wmode="transparent" ', $html);
   		return $html;
   } elseif (strpos($html, "<iframe" ) !== false) {
     	$html = str_replace('feature=oembed', 'feature=oembed&wmode=transparent', $html);
        return $html;
   } else {
        return $html;
   }
}
add_filter('embed_oembed_html', 'add_video_wmode_transparent', 10, 3);

// Don't display "no categories "
function bm_dont_display_it($content) {
  if (!empty($content)) {
     $content = str_ireplace('<li>' .__( "No categories" ). '</li>', "", $content);
   }
   return $content;
}
add_filter('wp_list_categories','bm_dont_display_it');

?>