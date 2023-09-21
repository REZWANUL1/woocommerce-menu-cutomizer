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

//? add carbon fields conditions to menu
add_action('carbon_fields_register_fields', 'wmcrh_attach_menu_options');
function wmcrh_attach_menu_options()
{
   if (!class_exists("WooCommerce")) {
      return;
   }
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
         Field::make('Select', 'crb_product', __('Display Products Hare'))
            ->set_options(
               $_products
            )
      ));
}
//? Get the nav menus by filter
add_filter('wp_get_nav_menu_items', 'crb_get_nav_menu_item');
function crb_get_nav_menu_item($items)
{
   if (!class_exists("WooCommerce")) {
      return $items;
   }
   $to_hide = [];
   if (!is_admin()) {
      foreach ($items as $key => $item) {
         $product_id = carbon_get_nav_menu_item_meta($item->ID, 'crb_product');

         if ($product_id != 0) {
            $current_user = wp_get_current_user();
            if ($current_user) {
               $is_owner = wc_customer_bought_product($current_user->user_email, $current_user->ID, $product_id);
               if (!$is_owner) {
                  $to_hide[$key] = $item->ID;
               }
            }
         }
      }
      foreach ($to_hide as $key => $value) {
         unset($items[$key]);
      }
   }
   return $items;
}

//? load carbon filed
add_action('after_setup_theme', 'crb_load');
function crb_load()
{
   require_once('vendor/autoload.php');
   \Carbon_Fields\Carbon_Fields::boot();
}
