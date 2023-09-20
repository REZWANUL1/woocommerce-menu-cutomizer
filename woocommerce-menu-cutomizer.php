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

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options()
{
   Container::make('post_meta', __('User Settings'))
      ->where('post_type', '=', 'page')
      ->add_tab(__('Profile'), array(
         Field::make('text', 'crb_first_name', __('First Name')),
         Field::make('text', 'crb_last_name', __('Last Name')),
         Field::make('text', 'crb_position', __('Position')),
      ));
      
}

add_action('after_setup_theme', 'crb_load');
function crb_load()
{
   require_once('vendor/autoload.php');
   \Carbon_Fields\Carbon_Fields::boot();
}
