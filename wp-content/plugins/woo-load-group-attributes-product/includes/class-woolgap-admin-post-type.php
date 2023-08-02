<?php
/**
 * Post Type class
 *
 * Register and process all actions within add/edit screen
 *
 * @package WooCommerce Load Group Attributes Product
 * @version 1.0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WOOLGAP Post Type class.
 */
if ( ! class_exists( 'WOOLGAP_Post_Type' ) ) {
class WOOLGAP_Post_Type
{

    function __construct()
    {

        // register custom post type
        add_action('init', array($this, 'register_post_type'));

        //  add meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        // save meta boxes
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);

        // change screen id's before woocommerce outputs scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 9);

        // append admin coloumn headers
        add_filter('manage_w_default_attributes_posts_columns', array($this, 'columns_head'));

        // append admin coloumns
        add_action('manage_w_default_attributes_posts_custom_column', array($this, 'columns_content'), 10, 2);

    }

    /**
     * Register Default Attribute post type
     * @return void
     */
    public function register_post_type()
    {

        $labels = array(
            'name' => __('Default Attribute', 'woolgap'),
            'singular_name' => __('Default Attribute', 'woolgap'),
            'add_new' => __('Add New Default Attribute', 'woolgap'),
            'add_new_item' => __('Add New Default Attribute', 'woolgap'),
            'edit_item' => __('Edit Default Attribute', 'woolgap'),
            'new_item' => __('New Default Attribute', 'woolgap'),
            'all_items' => __('All Default Attribute', 'woolgap'),
            'view_item' => __('View Default Attribute', 'woolgap'),
            'search_items' => __('Search Default Attribute', 'woolgap'),
            'not_found' => __('No Default Attribute found', 'woolgap'),
            'not_found_in_trash' => __('No Default Attribute found in Trash', 'woolgap'),
            'parent_item_colon' => __('Parent Default Attribute:', 'woolgap'),
            'menu_name' => __('Default Attribute', 'woolgap'),
        );

        $args = array(
            'public' => false,
            'labels' => $labels,
            'show_ui' => true,
            'supports' => array('title', 'name'),
            'capability_type' => 'product',
            'show_in_menu' => 'edit.php?post_type=product'
        );
        register_post_type('w_default_attributes', $args);

    }

    /**
     * Load Default Attribute meta box on add/edit screen
     * @return void
     */
    function add_meta_boxes()
    {
        add_meta_box('attributes', __('Attributes', 'woolgap'), array($this, 'output_meta_box'), 'w_default_attributes', 'normal');
    }

    /**
     * Display attribute meta box on add/edit screen
     * @return void
     */
    function output_meta_box()
    {

        global $post;

        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $attributes = maybe_unserialize($post->post_content);

        wp_nonce_field('woolgap_meta', "w_default_attributes", null, true);
        $active = array();

        include(WOOLGAP_ABSPATH . '/views/html-product-data-attributes.php');

    }

    /**
     * Save data submitted from Default Attribute meta box
     * @param int $post_id
     * @param array $post
     * @return void
     */
    function save_meta_boxes($post_id, $post)
    {

        // $post_id and $post are required
        if (empty($post_id) || empty($post)) {
            return;
        }

        // Dont' save meta boxes for revisions or autosaves
        if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
            return;
        }

        // Check the nonce
        if (empty($_POST["w_default_attributes"]) || !wp_verify_nonce($_POST["w_default_attributes"], 'woolgap_meta')) {
            return;
        }

        // Check user has permission to edit
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check the post type and save
        if ('w_default_attributes' === $post->post_type && isset($_POST['attribute_names'])) {

            $result = array();

            $attribute_names = $_POST['attribute_names'];

            if (is_array($attribute_names)) {

                foreach ($attribute_names as $index => $name) {

                    $name = sanitize_text_field($name);
                    $is_visible = isset($_POST['attribute_visibility'][$index]) ? 'true' : 'false';

                    $values = isset($_POST['attribute_values'][$index]) ? $_POST['attribute_values'][$index] : array();

                    if (!is_array($values) && !empty($values)) {
                        $values = !is_array($values) ? explode('|', $values) : array();
                        foreach ($values as $i => $value) {
                            $values[$i] = esc_textarea($value);
                        }
                    }

                    // dont save attribute if no values selected
                    if (empty($values) && !isset($_POST['woolgap_attribute_visible'][$index])) {
                        continue;
                    }

                    // escape if no name is present as this is required
                    if (empty($name)) {
                        continue;
                    }

                    $result[$name] = array(
                        'values' => $values,
                        'slug' => $name,
                        'name' => wc_attribute_label($name),
                        'visible' => $is_visible,
                    );
                }

                // stop nesting in wp_update_post function
                remove_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
                wp_update_post(array('ID' => $post_id, 'post_content' => serialize($result)));
            }
        }
    }

    /**
     * Add admin attributes column
     * @param array $columns
     * @return array
     */
    function columns_head($columns)
    {

        unset($columns['date']);

        $columns['attributes'] = __('Attributes', 'woolgap');

        return $columns;
    }

    /**
     * Output list of attribites and values in attributes admin column
     * @param string $column_name
     * @return void
     */
    function columns_content($column_name)
    {
        global $post;

        if ($column_name == 'attributes') {

            // display list of attributes and chosen terms
            $attrs = maybe_unserialize($post->post_content);
            if (!empty($attrs)) {
                foreach ($attrs as $group) {
                    $name = $group['name'];

                    if (0 === strpos($name, 'pa_')) {
                        $name = substr($name, strlen('pa_'));
                    }

                    if (!empty($group['values'])) {
                        foreach ($group['values'] as $k => $v) {
                            $group['values'][$k] = urldecode($v);
                        }
                    }

                    echo '<strong>' . esc_html($name) . ' , </strong>';
                }
            }
        }

    }

    /**
     *  change screen id's before woocommerce outputs scripts
     *  scripts@return void
     */
    function admin_enqueue_scripts()
    {

        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';

        if ($screen_id == 'edit-w_default_attributes' || $screen_id == 'w_default_attributes') {

            $this->set_screen_id();
        }

    }

    /**
     * Change screen id to load in woocommerce assets
     * @return void
     */
    function set_screen_id()
    {
        global $current_screen;
        $screen = get_current_screen();

        if (($screen->id === 'w_default_attributes')) {
            $current_screen->id = 'product';

            add_action('admin_enqueue_scripts', array($this, 'reset_screen_id'), 11);

        }
    }

    /**
     * Reset screen back to default id
     * @return void
     */
    function reset_screen_id()
    {
        global $current_screen;
        $current_screen->id = 'w_default_attributes';
    }

}


new WOOLGAP_Post_Type();

}