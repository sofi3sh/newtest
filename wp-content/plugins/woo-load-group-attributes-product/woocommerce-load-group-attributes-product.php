<?php
/**
 * WooCommerce Load Group Attributes Product
 *
 * @link		https://github.com/msn60/oop-wordpress-boilerplate
 * @since		1.0.0
 * @package		woocommerce-load-group-attributes-product
 *
 *
 *
 * Plugin Name: WooCommerce Load Group Attributes Product
 * Plugin URI:
 * Description: Define default attributes to be automatically added in WooCommerce new product page.
 * Version: 	1.0.0
 * Author: Ali 	Zarei
 * Author URI: 	http://www.alizarei.ir
 * Text Domain: woolgap
 * Domain Path: /languages
 License: 		GPL2
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
 /**
 * Copyright (c) 2019 AliZarei (email: alizarei.kashan@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */
 
/**
 * If this file is called directly, then abort execution.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Define WOOLGAP_ABSPATH.
 */
if (!defined('WOOLGAP_ABSPATH')) {
    define('WOOLGAP_ABSPATH', untrailingslashit(dirname(__FILE__)));
}

/**
 * Define WOOLGAP_URL.
 */
if (!defined('WOOLGAP_URL')) {
    define('WOOLGAP_URL', untrailingslashit(plugins_url('/', __FILE__)));
}
/**
 * Define WOOLGAP_VERSION.
 */
if (!defined('WOOLGAP_VERSION')) {
    define('WOOLGAP_VERSION', '1.0.0');
}

/**
 * Class Woocommerce_Load_Group_Attributes_Product class
 *
 * @class Woocommerce_Load_Group_Attributes_Product The class that holds the entire Woocommerce_Load_Group_Attributes_Product plugin
 */
if ( ! class_exists( 'Woocommerce_Load_Group_Attributes_Product' ) ) {
class Woocommerce_Load_Group_Attributes_Product{

	/**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';
	
   /**
	 * Instance property of Woocommerce_Load_Group_Attributes_Product Class.
	 * This is a property in your plugin primary class. You will use to create
	 * one object from Woocommerce_Load_Group_Attributes_Product class in whole of program execution.
	 *
	 * @access private
	 * @var    Woocommerce_Load_Group_Attributes_Product $instance create only one instance from plugin primary class
	 * @static
	 */
	private static $instance;
	 
    /**
     * Constructor for the Woocommerce_Load_Group_Attributes_Product class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses add_action()
	 * @access public
     */  
    public function __construct()
    {

        add_action('woocommerce_init', array($this, 'init'));

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        add_action('wp_ajax_get_load_attribute_content', array($this, 'get_load_attribute_content'));
        add_action('wp_ajax_nopriv_get_load_attribute_content', array($this, 'get_load_attribute_content'));

        add_action('wp_ajax_woolgap_add_attribute', array($this, 'add_attribute'));
        add_action('wp_ajax_woolgap_add_attribute', array($this, 'add_attribute'));

		/**
		 * Register activation hook.
		 * Register activation hook for this plugin by invoking my_plugin_activate
		 * in Woocommerce_Load_Group_Attributes_Product class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is activated.
		 */		 
        register_activation_hook(__FILE__, array($this, 'my_plugin_activate'));
    }

	/**
	 * Create an instance from Woocommerce_Load_Group_Attributes_Product class.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return Woocommerce_Load_Group_Attributes_Product
	 */
    public static function instance() {
        if (is_null(self::$instance)) :
            self::$instance = new self();
        endif;

        return self::$instance;
    }

    /**
     * Initializes the Woocommerce_Load_Group_Attributes_Product() class
     *
     * @uses add_action()
	 * @access public
     */
    public function init()
    {

        if ((isset($_GET['post']) && ('product' == get_post_type($_GET['post']))) || (isset($_GET['post_type']) && ('product' == $_GET['post_type']))) {

            add_action('woocommerce_product_options_attributes', array($this, 'attributes_content_toolbar'));
            add_action('admin_footer', array($this, 'admin_footer'));
        }
		
        /**
         * 
         */
        include_once WOOLGAP_ABSPATH . '/includes/class-woolgap-admin-post-type.php';
    }
 
	/**
	 * Placeholder for activation function
	 * This function calls activate method from Activator class.
	 * You can use from this method to run every thing you need when plugin is activated.
	 *
	 * @access public
	 * @since  1.0.0
	 */
    function my_plugin_activate()
    {

    }
	
	/**
	 * Get attribute by slug
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug
	 *
	 * @return array
	 */
    public function get_attribute_by_slug($slug)
    {
        global $wpdb;

        if (empty($slug)) {
            return false;
        }

        $attribute_row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "woocommerce_attribute_taxonomies
												WHERE attribute_name = '$slug'");
        return $attribute_row;
    }
	
	/**
    * Enqueue all scripts
	*
    * @access public
	* @since  1.0.0
	*
    * @return void
    **/
    public function admin_enqueue_scripts()
    {

        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';

        if (('product' == $screen_id) || ('w_default_attributes' == $screen_id)) {

            wp_enqueue_style('css-woolgap', WOOLGAP_URL . "/assets/css/woolgap.css", array(), WOOLGAP_VERSION);
            wp_enqueue_script('js-woolgap', WOOLGAP_URL . "/assets/js/woolgap.js", array('jquery', 'wc-backbone-modal'), WOOLGAP_VERSION, true);

            $params = array(
                'add_attribute_nonce' => wp_create_nonce('add-attribute'),
            );

            wp_localize_script('js-woolgap', 'woolgap_admin_ajax', $params);
        }

    }

	/**
	 * Ge Load Attribute Content
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @return json
	 */
    public function get_load_attribute_content()
    {

        if (!current_user_can('edit_products')) {
            wp_die(-1);
        }

        $ID = absint($_POST["attributes_content_id"]);

        $post = get_post((int)$ID);

        $post_title = $post->post_title;
        $post_name = $post->post_name;
        $post_content = unserialize($post->post_content);

        $html = "";

        include(WOOLGAP_ABSPATH . "/views/html-ajax-load-attributes.php");

        wp_send_json_success(
            array(
                'title' => $post_title,
                'html' => $html,
            )
        );
    }
	
	/**
	 * Add Attribute Product
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @return json
	 */
    public static function add_attribute()
    {
        ob_start();
		
        check_ajax_referer('add-attribute', 'security');

        if (!current_user_can('edit_products') || !isset($_POST['taxonomy'], $_POST['i'])) {
            wp_die(-1);
        }

        $i = absint($_POST['i']);
        $id_post = absint($_POST['df']);
        $metabox_class = array();
        $attribute = new WC_Product_Attribute();

        $attribute->set_id(wc_attribute_taxonomy_id_by_name(sanitize_text_field(wp_unslash($_POST['taxonomy']))));
        $attribute->set_name(sanitize_text_field(wp_unslash($_POST['taxonomy'])));
        $attribute->set_visible(apply_filters('woocommerce_attribute_default_visibility', 1));
        $attribute->set_variation(apply_filters('woocommerce_attribute_default_is_variation', 0));

        if ($attribute->is_taxonomy()) {
            $metabox_class[] = 'taxonomy';
            $metabox_class[] = $attribute->get_name();
        }
		
		$select_values 	= "";
		
		if( $id_post ){
			$result 		= get_post($id_post);
			if( !empty($result->post_content) ){
				$post_content 	= unserialize($result->post_content);
				if( is_array( $post_content ) ){
					$select_values 	= $post_content[$_POST['taxonomy']]["values"];
				}
			}
		}

        include(WOOLGAP_ABSPATH . "/views/html-product-attribute.php");
        wp_die();
    }

	/**
	 *  Load Attributes
	 *
	 * @access public
	 * @since  1.0.0
	 */

    public function attributes_content_toolbar()
    {

        if (!current_user_can('edit_products')) {
            wp_die(-1);
        }

        $args = array('post_type' => 'w_default_attributes');
        $myposts = get_posts($args);

        include(WOOLGAP_ABSPATH . "/views/html-df-select-product.php");
    }
	
	/**
	 *  Admin footer scripts for the product admin screen
	 *
	 * @access public
	 * @since  1.0.0
	 */
    public function admin_footer() {
		
        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';

        if ('product' == $screen_id) {

            include(WOOLGAP_ABSPATH . "/views/html-modal-load-attributes.php");
        }
    }

}
}

/**
 * Returns the global instance of WooCommerce Load Group Attributes Product
 */
if (!function_exists('WOOLGAP')) {
    function WOOLGAP()
    {
        return Woocommerce_Load_Group_Attributes_Product::instance();
    }

}

if (!function_exists('woolgap_plugins_loaded')) {
    function woolgap_plugins_loaded()
    {

        if (!function_exists('is_plugin_active')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (function_exists('WC') || class_exists('woocommerce') || class_exists('WooCommerce')) {
            WOOLGAP();
        } else {
            add_action('admin_notices', 'woolgap_wc_notice');
        }


        /**
         * Load Localisation files.
         *
         * @since        1.0.0
         */
        load_plugin_textdomain('woolgap', false, basename(dirname(__FILE__)) . '/languages');

    }
}
add_action('plugins_loaded', 'woolgap_plugins_loaded', 11);


/**
 * WooCommerce fallback notice.
 *
 * @return string
 * @since 1.0.0
 */
if (!function_exists('woolgap_wc_notice')) {
function woolgap_wc_notice()
{ ?>
    <div class="error">
        <p><?php _e('WooCommerce Load Group Attributes Product is enabled but not effective. It requires <a href="'. admin_url( "plugin-install.php?s=WooCommerce&tab=search&type=term" ) . '" target="_blank">WooCommerce</a> in order to work.', 'yith-woocommerce-compare'); ?></p>
    </div>
<?php }
} ?>