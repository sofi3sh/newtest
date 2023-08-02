<?php
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$required = $addon->get_option( 'required', $x ) === 'yes';
$checked  = $addon->get_option( 'default', $x ) === 'yes';
$selected = $checked ? 'selected' : '';

?>

<div id="yith-wapo-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
	class="yith-wapo-option selection-<?php echo esc_attr( $selection_type ); ?> <?php echo esc_attr( $selected ); ?>"
	data-replace-image="<?php echo esc_attr( $image_replacement ); ?>">

	<!-- LEFT/ABOVE IMAGE -->
	<?php if ( ! empty( $option_image ) ) {
		if ( 'left' === $addon_options_images_position || 'above' === $addon_options_images_position ) : ?>
		<label class="yith-wapo-img-label" for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
			<?php include YITH_WAPO_DIR . '/templates/front/option-image.php'; ?>
		</label>
		<?php endif; ?>
	<?php } ?>


	<span class="radiobutton <?php echo $checked ? 'checked' : ''; ?>">

		<!-- INPUT -->
		<input type="radio"
			id="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
			name="yith_wapo[][<?php echo esc_attr( $addon->id ); ?>]"
			value="<?php echo esc_attr( $x ); ?>"
			data-price="<?php echo esc_attr( $price ); ?>"
			data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
			data-price-type="<?php echo esc_attr( $price_type ); ?>"
			data-price-method="<?php echo esc_attr( $price_method ); ?>"
			data-first-free-enabled="<?php echo esc_attr( $addon->get_setting( 'first_options_selected', 'no' ) ); ?>"
			data-first-free-options="<?php echo esc_attr( $addon->get_setting( 'first_free_options', 0 ) ); ?>"
			data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
			<?php echo $required ? 'required' : ''; ?>
			<?php echo $checked ? 'checked="checked"' : ''; ?>>

	</span>

	<!-- RIGHT IMAGE -->
	<?php if ( ! empty( $option_image ) ) {
		if ( 'right' === $addon_options_images_position ) : ?>
		<label class="yith-wapo-img-label" for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
			<?php include YITH_WAPO_DIR . '/templates/front/option-image.php'; ?>
		</label>
		<?php endif; ?>
	<?php } ?>


	<!-- LABEL -->
	<label class="yith-wapo-label" for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
		<?php echo ! $hide_option_label ? wp_kses_post( $addon->get_option( 'label', $x ) ) : ''; ?>
		<?php echo $required ? '<span class="required">*</span>' : ''; ?>

		<!-- PRICE -->
		<?php echo ! $hide_option_prices ? wp_kses_post( $addon->get_option_price_html( $x ) ) : ''; ?>
	</label>

	<!-- TOOLTIP -->
	<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
		<span class="tooltip position-<?php echo esc_attr( get_option( 'yith_wapo_tooltip_position' ) ); ?>" style="width: 100%">
			<span><?php echo wp_kses_post( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>

	<!-- UNDER IMAGE -->
	<?php if ( ! empty( $option_image ) ) {
		if ( 'under' === $addon_options_images_position ) : ?>
		<label class="yith-wapo-img-label" for="yith-wapo-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
			<?php include YITH_WAPO_DIR . '/templates/front/option-image.php'; ?>
		</label>
		<?php endif; ?>
	<?php } ?>

	<!-- DESCRIPTION -->
	<?php if ( '' !== $option_description ) : ?>
		<p class="description"><?php echo wp_kses_post( $option_description ); ?></p>
	<?php endif; ?>

</div>
