<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var object $addon
 * @var int    $x
 * @var string $style_images_position
 * @var string $style_images_equal_height
 * @var string $style_images_height
 * @var string $style_label_position
 * @var array  $style_label_padding
 * @var string $style_description_position
 * @var bool   $hide_option_label
 * @var bool   $hide_option_prices
 * @var string $option_description
 * @var string $selection_type
 * @var string $options_width_css
 * @var string $image_replacement
 * @var string $addon_image
 * @var string $hide_option_images
 * @var string $setting_hide_images
 * @var string $price
 * @var string $price_sale
 * @var string $price_type
 * @var string $price_method
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$required = $addon->get_option( 'required', $x ) === 'yes';
$checked  = $addon->get_option( 'default', $x ) === 'yes';
$selected = $checked ? 'selected' : '';

//Style options tab
$dimensions_array_default = array(
	'dimensions' => array(
		'top'    => '',
		'right'  => '',
		'bottom' => '',
		'left'   => '',
	),
);
$style_images_position      = get_option( 'yith_wapo_style_images_position', 'above' );
$style_images_equal_height  = get_option( 'yith_wapo_style_images_equal_height', 'no' );
$style_images_height        = get_option( 'yith_wapo_style_images_height' );
$style_label_position       = get_option( 'yith_wapo_style_label_position', 'inside' );
$style_description_position = get_option( 'yith_wapo_style_description_position', 'outside' );
$style_label_padding        = get_option( 'yith_wapo_style_label_padding', $dimensions_array_default )['dimensions'];

// Individual style options
$images_position      = 'above';
$images_height        = '';
$label_position       = 'inside';
$label_padding        = '';
$label_content_align  = '';
$description_position = 'outside';
if ( $addon->get_setting( 'custom_style' ) === 'yes' ) {
	$images_position = $addon->get_setting( 'options_images_position', 'above' );
	if ( $addon->get_setting( 'image_equal_height' ) === 'yes' ) {
		$images_height = 'width: auto; max-width: none; height: ' . $addon->get_setting( 'images_height', 100 ) . 'px'; }
	$label_position      = $addon->get_setting( 'label_position', 'inside' );
	$label_content_align = $addon->get_setting( 'label_content_align', 'left' );
	if ( is_array( $addon->get_setting( 'label_padding' ) ) ) {
		$label_padding_dim = $addon->get_setting( 'label_padding' )['dimensions'];
		$label_padding     = 'padding: ' . $label_padding_dim['top'] . 'px ' . $label_padding_dim['right'] . 'px ' . $label_padding_dim['bottom'] . 'px ' . $label_padding_dim['left'] . 'px;';
	}
	$description_position = $addon->get_setting( 'description_position', 'outside' );
} else {
	$images_position = $style_images_position;
	if ( 'yes' === $style_images_equal_height ) {
		$images_height = 'width: auto; max-width: none; height: ' . $style_images_height . 'px';
	}
	$label_position       = $style_label_position;
	$label_padding        = 'padding: ' . $style_label_padding['top'] . 'px ' . $style_label_padding['right'] . 'px ' . $style_label_padding['bottom'] . 'px ' . $style_label_padding['left'] . 'px;';
	$description_position = $style_description_position;
}

$label_price_html  = '<div class="label_price">';
$label_price_html .= ! $hide_option_label ? $addon->get_option( 'label', $x ) : '';
$label_price_html .= $required ? ' <span class="required">*</span>' : '';
$label_price_html .= ! $hide_option_prices ? ' ' . $addon->get_option_price_html( $x ) : '';
$label_price_html .= '</div>';

$description_html = '' !== $option_description ? '<p class="description">' . wp_kses_post( $option_description ) . '</p>' : '';

?>

<div id="yith-wapo-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
	class="yith-wapo-option selection-<?php echo esc_attr( $selection_type ); ?> <?php echo esc_attr( $selected ); ?>"
	data-replace-image="<?php echo esc_attr( $image_replacement ); ?>">

	<!-- INPUT -->
	<input type="checkbox"
		id="yith-wapo-<?php echo esc_attr( $addon->id . '-' . $x ); ?>"
		class="yith-proteo-standard-checkbox"
		name="yith_wapo[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
		value="<?php echo esc_attr( $addon->get_option( 'label', $x ) ); ?>"
		data-price="<?php echo esc_attr( $price ); ?>"
		data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
		data-price-type="<?php echo esc_attr( $price_type ); ?>"
		data-price-method="<?php echo esc_attr( $price_method ); ?>"
		data-first-free-enabled="<?php echo esc_attr( $addon->get_setting( 'first_options_selected', 'no' ) ); ?>"
		data-first-free-options="<?php echo esc_attr( $addon->get_setting( 'first_free_options', 0 ) ); ?>"
		data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
		<?php echo $required ? 'required' : ''; ?>
		<?php echo $checked ? 'checked="checked"' : ''; ?>
		style="display: none;">

	<div class="label_container <?php echo esc_attr( $images_position ); ?> <?php echo esc_attr( $label_position ); ?>" style="display: inline-block; <?php echo esc_attr( $options_width_css ); ?>">

		<?php if ( 'outside' === $label_position && 'under' === $images_position ) : ?>
			<?php echo wp_kses_post( $label_price_html ); ?>
		<?php endif; ?>

		<label for="yith-wapo-<?php echo esc_attr( $addon->id . '-' . $x ); ?>"
			style="width: 100%; <?php echo esc_attr( $label_padding ); ?> text-align: <?php echo esc_attr( $label_content_align ); ?>;">

			<?php if ( 'inside' === $label_position && 'under' === $images_position ) : ?>
				<?php echo wp_kses_post( $label_price_html ); ?>
			<?php endif; ?>

			<?php if ( $addon->get_option( 'show_image', $x ) && $addon->get_option( 'image', $x ) !== '' && ! $hide_option_images && 'yes' !== $setting_hide_images ) : ?>
				<?php
					$post_id_image = attachment_url_to_postid( $addon->get_option( 'image', $x ) );
					$alt_text_image = get_post_meta( $post_id_image, '_wp_attachment_image_alt', true );
				?>
				<div class="image" style="display: inline-block;">
					<img src="<?php echo esc_attr( $addon->get_option( 'image', $x ) ); ?>" style="<?php echo esc_attr( $images_height ); ?>" alt="<?php echo esc_attr( $alt_text_image ) ?>">
				</div>
			<?php endif; ?>
			<div class="inside">

				<?php if ( 'inside' === $label_position && 'under' !== $images_position ) : ?>
					<?php echo wp_kses_post( $label_price_html ); ?>
				<?php endif; ?>

				<?php if ( 'inside' === $description_position ) : ?>
					<?php echo wp_kses_post( $description_html ); ?>
				<?php endif; ?>

			</div>

		</label>

		<div class="outside">

			<?php if ( 'outside' === $label_position && 'under' !== $images_position ) : ?>
				<?php echo wp_kses_post( $label_price_html ); ?>
			<?php endif; ?>

			<?php if ( 'outside' === $description_position ) : ?>
				<?php echo wp_kses_post( $description_html ); ?>
			<?php endif; ?>

		</div>

	</div>

	<?php if ( $required ) : ?>
		<small class="required-error" style="color: #f00; padding: 5px 0px; display: none;"><?php echo esc_html__( 'This option is required.', 'yith-woocommerce-product-add-ons' ); ?></small>
	<?php endif; ?>

	<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
		<span class="tooltip position-<?php echo esc_attr( get_option( 'yith_wapo_tooltip_position' ) ); ?>" style="<?php echo esc_attr( $options_width_css ); ?>">
			<span><?php echo esc_html( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>

</div>
