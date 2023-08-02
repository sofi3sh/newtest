<?php
/**
 *  Settings Tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$general_settings = array(

	'general-options'         => array(
		'id'    => 'yith_wapo_general_options',
		'title' => __( 'General options', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),
	'options-position'        => array(
		'id'        => 'yith_wapo_options_position',
		'name'      => __( 'Options position in product page', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the position for the options blocks.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'before',
		'options'   => array(
			'before' => __( 'Before "Add to cart"', 'yith-woocommerce-product-add-ons' ),
			'after'  => __( 'After "Add to cart"', 'yith-woocommerce-product-add-ons' ),
		),
	),
	'button-in-shop'          => array(
		'id'        => 'yith_wapo_button_in_shop',
		'name'      => __( 'In WooCommerce pages show', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the position for the options blocks.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'select',
		'options'   => array(
			'select' => __( '"Select options" button', 'yith-woocommerce-product-add-ons' ),
			'add'    => __( '"Add to cart" button', 'yith-woocommerce-product-add-ons' ),
		),
	),
	'select-options-label'    => array(
		'id'        => 'yith_wapo_select_options_label',
		'name'      => __( 'Label for "Select options" button', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enter the text for the "Select options" button.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'text',
		'default'   => 'Select options',
		'deps'      => array(
			'id'    => 'yith_wapo_button_in_shop',
			'value' => 'select',
			'type'  => 'hide-disable',
		),
	),
	'replace-product-price'   => array(
		'id'        => 'yith_wapo_replace_product_price',
		'name'      => __( 'Change the product base price with the calculated total', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to replace the product base price (below the title) with the newly calculated total of the selected options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),
	'hide-button-if-required' => array(
		'id'        => 'yith_wapo_hide_button_if_required',
		'name'      => __( 'Hide "Add to cart" until the required options are selected', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to hide the "Add to cart" button until the user selects the required options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),
	'total-price-box'         => array(
		'id'        => 'yith_wapo_total_price_box',
		'name'      => __( 'Total price box', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose what information to show in the total price box.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'select',
		'yith-type' => 'radio',
		'default'   => 'all',
		'options'   => array(
			'all'          => __( 'Show product price and total options', 'yith-woocommerce-product-add-ons' ),
			'hide_options' => __( 'Show the final total but hide options total only if the value is 0', 'yith-woocommerce-product-add-ons' ),
			'only_final'   => __( 'Show only the final total', 'yith-woocommerce-product-add-ons' ),
			'hide_all'     => __( 'Hide price box on the product page', 'yith-woocommerce-product-add-ons' ),
		),
	),
	'hide-titles-and-images'  => array(
		'id'        => 'yith-wapo-hide-titles-and-images',
		'name'      => __( 'Hide titles and images of options groups', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to hide all titles and images set in the "display" tab of the options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),
	'hide-images'             => array(
		'id'        => 'yith_wapo_hide_images',
		'name'      => __( 'Hide images of the single options', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to hide all the images uploaded in the "populate options" tab of the options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),
	'general-options-end'     => array(
		'id'   => 'yith-wapo-general-option',
		'type' => 'sectionend',
	),

);

$upload_settings = array(

	'upload-options'            => array(
		'id'    => 'yith-wapo-upload-options',
		'title' => __( 'Upload options', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),
	'uploads-text-to-show'      => array(
		'id'      => 'yith_wapo_uploads_text_to_show',
		'name'    => __( 'Text to show', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'text',
		'default' => __( 'Drop files to upload or', 'yith-woocommerce-product-add-ons' ),
	),
	'uploads-link-to-show'      => array(
		'id'      => 'yith_wapo_uploads_link_to_show',
		'name'    => __( 'Link to show', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'select',
		'default' => 'button',
		'options' => array(
			'text'   => __( 'Textual "browse"', 'yith-woocommerce-product-add-ons' ),
			'button' => __( 'Button "upload"', 'yith-woocommerce-product-add-ons' ),
		),
	),
	/* phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	'uploads-folder' => array(
		'id'		=> 'yith-wapo-uploads-folder',
		'name'		=> __( 'Uploads folder', 'yith-woocommerce-product-add-ons' ),
		'desc'		=> __( 'Enter the name of the folder used to storage the files uploaded from users', 'yith-woocommerce-product-add-ons' ),
		'type'		=> 'text',
		'default'	=> 'yith_advanced_product_options',
	),
	*/
	'upload-allowed-file-types' => array(
		'id'      => 'yith_wapo_upload_allowed_file_types',
		'name'    => __( 'Allowed file types', 'yith-woocommerce-product-add-ons' ),
		'desc'    => __( 'Enter which file types can be uploaded by users.', 'yith-woocommerce-product-add-ons' ) . '<br />'
						. __( 'Separate each file type with a comma. Example: .jpg, .png, .pdf', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'text',
		'default' => '.jpg, .pdf, .png, .rar, .zip',
	),

	'upload-max-file-size'      => array(
		'id'      => 'yith_wapo_upload_max_file_size',
		'name'    => __( 'Max file size allowed (MB)', 'yith-woocommerce-product-add-ons' ),
		'desc'    => __( 'Enter the maximum allowed size for files uploaded by users.', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'text',
		'default' => '5',
	),
	/* phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	'attach-file-to-email' => array(
		'id'		=> 'yith-wapo-attach-file-to-email',
		'name'		=> __( 'Attach uploaded files to order emails', 'yith-woocommerce-product-add-ons' ),
		'desc'		=> __( 'Enable if you want to receive the files uploaded by users also in orders emails', 'yith-woocommerce-product-add-ons' ),
		'type'		=> 'yith-field',
		'yith-type'	=> 'onoff',
		'default'	=> 'yes',
	),
	*/
	'upload-options-end'        => array(
		'id'   => 'yith-wapo-upload-option',
		'type' => 'sectionend',
	),

);

if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) {
	$general_settings = array_merge( $general_settings, $upload_settings );
}

$settings = array(

	'settings'         => array(
		'general-options' => array(
			'type'     => 'multi_tab',
			'sub-tabs' => array(
				'settings-general' => array(
					'title' => esc_html_x( 'General options', 'Admin title of tab', 'yith-woocommerce-product-add-ons' ),
				),
				'settings-cart'    => array(
					'title' => esc_html_x( 'Cart & Order', 'Admin title of tab', 'yith-woocommerce-product-add-ons' ),
				),
			),
		),
	),

	'settings-general' => $general_settings,

	'settings-cart'    => array(

		'cart-order'                     => array(
			'id'    => 'yith-wapo-cart-order',
			'title' => __( 'Cart & Order options', 'yith-woocommerce-product-add-ons' ),
			'type'  => 'title',
			'desc'  => '',
		),

		'show-options-in-cart-page'      => array(
			'id'        => 'yith_wapo_show_options_in_cart',
			'name'      => __( 'Show options in the cart page', 'yith-woocommerce-product-add-ons' ),
			'desc'      => __( 'Enable to show the details of the options in the cart page.', 'yith-woocommerce-product-add-ons' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),

		'show-blocks-in-cart-page'      => array(
			'id'        => 'yith_wapo_show_blocks_in_cart',
			'name'      => __( 'Show block titles in the cart page', 'yith-woocommerce-product-add-ons' ),
			'desc'      => __( 'Enable to show the titles of the blocks in the cart page.', 'yith-woocommerce-product-add-ons' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'no',
		),

		'show-replacement-image-in-cart' => array(
			'id'        => 'yith_wapo_show_image_in_cart',
			'name'      => __( 'Show the replacement image in the cart', 'yith-woocommerce-product-add-ons' ),
			'desc'      => __( 'Enable to replace the product image with the option image in the cart.', 'yith-woocommerce-product-add-ons' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),

		'hide-options-in-order-email'    => array(
			'id'        => 'yith_wapo_hide_options_in_order_email',
			'name'      => __( 'Hide options in the order email', 'yith-woocommerce-product-add-ons' ),
			'desc'      => __( 'Enable to hide the options in the order email.', 'yith-woocommerce-product-add-ons' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),

		'cart-order-end'                 => array(
			'id'   => 'yith-wapo-cart-order',
			'type' => 'sectionend',
		),

	),
);

return apply_filters( 'yith_wapo_panel_settings_options', $settings );
