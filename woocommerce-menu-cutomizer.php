<?php
/*
 * Plugin Name:       Woocommerce Menu Customizer
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rezwanul Haque
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wmcrh
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
   exit;
}
function load_my_plugin_translation()
{
   load_plugin_textdomain('your-plugin-textdomain', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'load_my_plugin_translation');

//? load carbon filed 
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options()
{
   global $wpdb;
   $posts_table = $wpdb->prefix . 'posts';
   $query = "SELECT ID, post_title FROM $posts_table 
          WHERE post_type = 'product' AND post_status = 'publish'";
   $_products = [0 => 'Always Display'];
   $results = $wpdb->get_results($query, ARRAY_A);
   // Check if there are results
   if ($results) {
      foreach ($results as $product) {
         $_products[$product['ID']] = $product['post_title'];
      }
   }

   if ($results) {
      foreach ($results as $product) {
         $_products[$product['ID']] = $product['post_title'];
      }
   }
   Container::make('nav_menu_item', __('User Settings'))
      ->add_fields(array(
         Field::make('Select', 'crb_color', __('Display Products Hare'))
            ->set_options(
               $_products
            )
      ));
}

add_action("admin_footer", "admin_footercallback");
function admin_footercallback()
{
   // global $wpdb;
   // $products = $wpdb->get_results('SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_status="publish" AND post_type="product"', ARRAY_A);
   // print_r($products);
   global $wpdb;
   $posts_table = $wpdb->prefix . 'posts';
   $query = "SELECT ID, post_title FROM $posts_table 
          WHERE post_type = 'product' AND post_status = 'publish'";
   $_products = [0 => 'Always Display'];
   $results = $wpdb->get_results($query, ARRAY_A);
   // Check if there are results
   if ($results) {
      foreach ($results as $product) {
         $_products[$product['ID']] = $product['post_title'];
      }
   }
   print_r($_products);
}

//? load carbon filed
add_action('after_setup_theme', 'crb_load');
function crb_load()
{
   require_once('vendor/autoload.php');
   \Carbon_Fields\Carbon_Fields::boot();
}
