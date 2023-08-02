<?php
/**
 *  Settings Tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$general_style = array(

	'style-section'               => array(
		'id'    => 'yith_wapo_style',
		'title' => __( 'Style Options', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),

	'style-addon-titles'          => array(
		'id'      => 'yith_wapo_style_addon_titles',
		'name'    => __( 'Block titles', 'yith-woocommerce-product-add-ons' ),
		'desc'    => __( 'Choose which heading to use for the titles in the block of options.', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'select',
		'default' => 'h3',
		'options' => array(
			'h1' => 'H1',
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
		),
	),

	'style-addon-background'      => array(
		'id'           => 'yith_wapo_style_addon_background',
		'name'         => __( 'Block background', 'yith-woocommerce-product-add-ons' ),
		'desc'         => __( 'Set the background color for all block options.', 'yith-woocommerce-product-add-ons' ),
		'type'         => 'yith-field',
		'yith-type'    => 'multi-colorpicker',
		'colorpickers' => array(
			array(
				'name'          => '',
				'id'            => 'color',
				'default'       => '#ffffff',
				'alpha_enabled' => true,
			),
		),
	),

	'style-addon-padding'         => array(
		'id'        => 'yith_wapo_style_addon_padding',
		'name'      => __( 'Block padding', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Set the padding for the content in all block options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'dimensions',
		'default'   => array(
			'dimensions' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'unit'       => 'px',
			'linked'     => 'no',
		),
		'units'     => array( 'px' => 'px' ),
	),

	'style-form-style'            => array(
		'id'        => 'yith_wapo_style_form_style',
		'name'      => __( 'Form style', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the general style for form: checkbox, radio, select, input, textarea, etc.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'theme',
		'options'   => array(
			'theme'  => __( 'Theme style', 'yith-woocommerce-product-add-ons' ),
			'custom' => __( 'Custom style', 'yith-woocommerce-product-add-ons' ),
		),
	),

	'style-checkbox-style'        => array(
		'id'        => 'yith_wapo_style_checkbox_style',
		'name'      => __( 'Checkbox style', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the style for the checkbox.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'rounded',
		'options'   => array(
			'rounded' => __( 'Rounded', 'yith-woocommerce-product-add-ons' ),
			'square'  => __( 'Square', 'yith-woocommerce-product-add-ons' ),
		),
		'deps'      => array(
			'id'    => 'yith_wapo_style_form_style',
			'value' => 'custom',
			'type'  => 'hide-disable',
		),
	),

	'style-accent-color'          => array(
		'id'           => 'yith_wapo_style_accent_color',
		'name'         => __( 'Accent color', 'yith-woocommerce-product-add-ons' ),
		'desc'         => __( 'Set the accent color to use for the selected options.', 'yith-woocommerce-product-add-ons' ),
		'type'         => 'yith-field',
		'yith-type'    => 'multi-colorpicker',
		'colorpickers' => array(
			array(
				'name'          => '',
				'id'            => 'color',
				'default'       => '#03bfac',
				'alpha_enabled' => true,
			),
		),
		'deps'         => array(
			'id'    => 'yith_wapo_style_form_style',
			'value' => 'custom',
			'type'  => 'hide-disable',
		),
	),

	'style-borders-color'         => array(
		'id'           => 'yith_wapo_style_borders_color',
		'name'         => __( 'Form border-color', 'yith-woocommerce-product-add-ons' ),
		'desc'         => __( 'Set the color of the form borders.', 'yith-woocommerce-product-add-ons' ),
		'type'         => 'yith-field',
		'yith-type'    => 'multi-colorpicker',
		'colorpickers' => array(
			array(
				'name'          => '',
				'id'            => 'color',
				'default'       => '#7a7a7a',
				'alpha_enabled' => true,
			),
		),
		'deps'         => array(
			'id'    => 'yith_wapo_style_form_style',
			'value' => 'custom',
			'type'  => 'hide-disable',
		),
	),

	'style-label-font-size'       => array(
		'id'        => 'yith_wapo_style_label_font_size',
		'name'      => __( 'Label font size', 'yith-woocommerce-product-add-ons' ) . ' (px)',
		'desc'      => __( 'Set the label font size in pixel.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'number',
		'default'   => '16',
		'deps'      => array(
			'id'    => 'yith_wapo_style_form_style',
			'value' => 'custom',
			'type'  => 'hide-disable',
		),
	),

	'style-description-font-size' => array(
		'id'        => 'yith_wapo_style_description_font_size',
		'name'      => __( 'Description font size', 'yith-woocommerce-product-add-ons' ) . ' (px)',
		'desc'      => __( 'Set the description font size in pixel.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'number',
		'default'   => '12',
		'deps'      => array(
			'id'    => 'yith_wapo_style_form_style',
			'value' => 'custom',
			'type'  => 'hide-disable',
		),
	),

	'style-section-end'           => array(
		'id'   => 'yith_wapo_style_end',
		'type' => 'sectionend',
	),
);

$premium_style = array(

	// Color Swatches.

	'style-section-2'            => array(
		'id'    => 'yith_wapo_style_options',
		'title' => __( 'Color swatches', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),

	'style-color-swatch-style'   => array(
		'id'        => 'yith_wapo_style_color_swatch_style',
		'name'      => __( 'Color swatch style', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the style for color thumbnails.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'rounded',
		'options'   => array(
			'rounded' => __( 'Rounded', 'yith-woocommerce-product-add-ons' ),
			'square'  => __( 'Square', 'yith-woocommerce-product-add-ons' ),
		),
	),

	'style-color-swatch-size'    => array(
		'id'        => 'yith_wapo_style_color_swatch_size',
		'name'      => __( 'Color swatch size', 'yith-woocommerce-product-add-ons' ) . ' (px)',
		'desc'      => __( 'Set the size of the color thumbnails.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'number',
		'default'   => '40',
	),

	'style-section-2-end'        => array(
		'id'   => 'yith-wapo-style-options',
		'type' => 'sectionend',
	),

	// Label / Images.

	'style-section-3'            => array(
		'id'    => 'yith_wapo_style_options',
		'title' => __( 'Label / Images', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),

	'style-images-position'      => array(
		'id'      => 'yith_wapo_style_images_position',
		'name'    => __( 'Image position', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'select',
		'default' => 'above',
		'options' => array(
			'above' => __( 'Above label', 'yith-woocommerce-product-add-ons' ),
			'under' => __( 'Under label', 'yith-woocommerce-product-add-ons' ),
			'left'  => __( 'Left side', 'yith-woocommerce-product-add-ons' ),
			'right' => __( 'Right side', 'yith-woocommerce-product-add-ons' ),
		),
	),

	'style-images-equal-height'  => array(
		'id'        => 'yith_wapo_style_images_equal_height',
		'name'      => __( 'Force image equal heights', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),

	'style-images-height'        => array(
		'id'        => 'yith_wapo_style_images_height',
		'name'      => __( 'Image heights', 'yith-woocommerce-product-add-ons' ) . ' (px)',
		'type'      => 'yith-field',
		'yith-type' => 'number',
		'default'   => '',
		'deps'      => array(
			'id'    => 'yith_wapo_style_images_equal_height',
			'value' => 'yes',
			'type'  => 'hide-disable',
		),
	),

	'style-label-position'       => array(
		'id'      => 'yith_wapo_style_label_position',
		'name'    => __( 'Label position', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'select',
		'default' => 'inside',
		'options' => array(
			'inside'  => __( 'Inside borders', 'yith-woocommerce-product-add-ons' ),
			'outside' => __( 'Outside borders', 'yith-woocommerce-product-add-ons' ),
		),
	),

	'style-description-position' => array(
		'id'      => 'yith_wapo_style_description_position',
		'name'    => __( 'Description position', 'yith-woocommerce-product-add-ons' ),
		'type'    => 'select',
		'default' => 'outside',
		'options' => array(
			'inside'  => __( 'Inside borders', 'yith-woocommerce-product-add-ons' ),
			'outside' => __( 'Outside borders', 'yith-woocommerce-product-add-ons' ),
		),
	),

	'style-label-padding'        => array(
		'id'        => 'yith_wapo_style_label_padding',
		'name'      => __( 'Padding', 'yith-woocommerce-product-add-ons' ) . ' (px)',
		'type'      => 'yith-field',
		'yith-type' => 'dimensions',
		'default'   => array(
			'dimensions' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'unit'       => 'px',
			'linked'     => 'no',
		),
		'units'     => array( 'px' => 'px' ),
	),

	'style-section-3-end'        => array(
		'id'   => 'yith-wapo-style-options',
		'type' => 'sectionend',
	),

	// Tooltip.

	'style-section-4'            => array(
		'id'    => 'yith_wapo_style_options',
		'title' => __( 'Tooltip', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),

	'show-tooltips'              => array(
		'id'        => 'yith_wapo_show_tooltips',
		'name'      => __( 'Show tooltips', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to show the tooltips in product options.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'yes',
	),

	'tooltip-color'              => array(
		'id'           => 'yith_wapo_tooltip_color',
		'name'         => __( 'Tooltip color', 'yith-woocommerce-product-add-ons' ),
		'desc'         => __( 'Set the color for this heading.', 'yith-woocommerce-product-add-ons' ),
		'type'         => 'yith-field',
		'yith-type'    => 'multi-colorpicker',
		'colorpickers' => array(
			array(
				'name'          => __( 'BACKGROUND', 'yith-woocommerce-product-add-ons' ),
				'id'            => 'background',
				'default'       => '#03bfac',
				'alpha_enabled' => true,
			),
			array(
				'name'          => __( 'TEXT', 'yith-woocommerce-product-add-ons' ),
				'id'            => 'text',
				'default'       => '#ffffff',
				'alpha_enabled' => true,
			),
		),
		'deps'         => array(
			'id'    => 'yith_wapo_show_tooltips',
			'value' => 'yes',
			'type'  => 'hide-disable',
		),
	),

	'tooltip-position'           => array(
		'id'        => 'yith_wapo_tooltip_position',
		'name'      => __( 'Tooltip position', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Choose the default position for tooltips.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'default'   => 'top',
		'options'   => array(
			'top'    => __( 'Top', 'yith-woocommerce-product-add-ons' ),
			'bottom' => __( 'Bottom', 'yith-woocommerce-product-add-ons' ),
		),
		'deps'      => array(
			'id'    => 'yith_wapo_show_tooltips',
			'value' => 'yes',
			'type'  => 'hide-disable',
		),
	),

	'style-section-4-end'        => array(
		'id'   => 'yith-wapo-style-options',
		'type' => 'sectionend',
	),

	// Toggle.

	'style-section-5'            => array(
		'id'    => 'yith_wapo_style_options',
		'title' => __( 'Toggle', 'yith-woocommerce-product-add-ons' ),
		'type'  => 'title',
		'desc'  => '',
	),

	'show-in-toggle'             => array(
		'id'        => 'yith_wapo_show_in_toggle',
		'name'      => __( 'Show options in toggle', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to show the options blocks in toggle sections.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
	),

	'show-toggle-opened'         => array(
		'id'        => 'yith_wapo_show_toggle_opened',
		'name'      => __( 'Show toggle opened by default', 'yith-woocommerce-product-add-ons' ),
		'desc'      => __( 'Enable to show the toggle opened by default.', 'yith-woocommerce-product-add-ons' ),
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'default'   => 'no',
		'deps'      => array(
			'id'    => 'yith_wapo_show_in_toggle',
			'value' => 'yes',
			'type'  => 'hide-disable',
		),
	),

	'style-section-5-end'        => array(
		'id'   => 'yith-wapo-style-options',
		'type' => 'sectionend',
	),
);

if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) {
	$general_style = array_merge( $general_style, $premium_style );
}

$style = array( 'style' => $general_style );

return apply_filters( 'yith_wapo_panel_style_options', $style );
