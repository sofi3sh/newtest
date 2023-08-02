<?php
/**
 * Option group template
 *
 * @var WC_Product $product
 * @var Array $single_type
 * @var String $type
 * @var YITH_WAPO_Frontend $yith_wapo_frontend
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Group Data.

$type_id                      = $single_type['id'];
$type_title                   = stripslashes( $single_type['label'] );
$description                  = stripslashes( $single_type['description'] );
$operator                     = $single_type['operator'];
$conditional                  = $yith_wapo_frontend->check_conditional_options( $single_type['depend'] );
$conditional_variation        = $single_type['depend_variations'];
$conditional_hidden           = ! empty( $conditional ) ? 'ywapo_conditional_hidden' : '';
$conditional_variation_hidden = ! empty( $conditional_variation ) ? 'ywapo_conditional_variation_hidden' : '';
$disabled                     = ! empty( $conditional ) ? 'disabled' : '';
$image                        = $single_type['image'];
$type_type                    = strtolower( $single_type['type'] );
$collapsed                    = $single_type['collapsed'];
$required                     = $single_type['required'];
$required_all_options         = $single_type['required_all_options'];
$sold_individually            = $single_type['sold_individually'];
$first_options_free           = $single_type['first_options_free'] ?? 0;
$max_item_selected            = $single_type['max_item_selected'] ?? 0;
$minimum_product_quantity     = $single_type['minimum_product_quantity'] ?? 0;
$max_input_values_amount      = $single_type['max_input_values_amount'] ?? 0;
$min_input_values_amount      = $single_type['min_input_values_amount'] ?? 0;
$change_featured_image        = $single_type['change_featured_image'];
$calculate_quantity_sum       = $single_type['calculate_quantity_sum'];
$class_calculate_quantity_sum = $calculate_quantity_sum ? 'yith_wapo_calculate_quantity' : '';
$name                         = 'ywapo_' . $type_type . '_' . $type_id;
$value                        = 'ywapo_value_' . $type_id;
$empty_option_text            = apply_filters( 'ywapo_empty_option_text', __( 'Choose an option', 'yith-woocommerce-product-add-ons' ), $type_title );

// Options Data.
$options = maybe_unserialize( $single_type['options'] );

$addon_title_tag = apply_filters( 'wapo_addon_title_tag', 'h3' );

if ( ! ( isset( $options['label'] ) ) || count( $options['label'] ) <= 0 ) {
	return; } ?>

<div id="<?php echo esc_attr( $value ); ?>"
	class="ywapo_group_container
		ywapo_group_container_<?php echo esc_attr( $type_type ); ?>
		form-row form-row-wide
		min_qty_<?php echo $minimum_product_quantity > 0 && apply_filters( 'wapo_enable_minimum_product_quantity', false ) ? esc_attr( $minimum_product_quantity ) : 0; ?>
		<?php echo 1 === $collapsed ? 'collapsed' : ''; ?>
		<?php echo esc_attr( $class_calculate_quantity_sum . ' ' . $conditional_hidden . ' ' . $conditional_variation_hidden ); ?>"
	data-requested="<?php echo $required ? '1' : '0'; ?>"
	data-requested-all-options="<?php echo $required_all_options ? '1' : '0'; ?>"
	data-type="<?php echo esc_attr( $type_type ); ?>"
	data-id="<?php echo esc_attr( $type_id ); ?>"
	data-operator="<?php echo esc_attr( $operator ); ?>"
	data-condition="<?php echo esc_attr( $conditional ); ?>"
	data-condition-variations="<?php echo esc_attr( $conditional_variation ); ?>"
	data-sold-individually="<?php echo $sold_individually ? '1' : '0'; ?>"
	data-first-options-free="<?php echo $first_options_free > 0 ? esc_attr( $first_options_free ) : 0; ?>"
	data-first-options-free-temp="<?php echo $first_options_free > 0 ? esc_attr( $first_options_free ) : 0; ?>"
	data-max-item-selected="<?php echo $max_item_selected > 0 ? esc_attr( $max_item_selected ) : 0; ?>"
	data-minimum-product-quantity="<?php echo $minimum_product_quantity > 0 && apply_filters( 'wapo_enable_minimum_product_quantity', false ) ? esc_attr( $minimum_product_quantity ) : 0; ?>"
	data-change-featured-image="<?php echo $change_featured_image ? '1' : '0'; ?>"
	data-calculate-quantity-sum="<?php echo $calculate_quantity_sum ? '1' : '0'; ?>"
	data-max-input-values-amount="<?php echo $max_input_values_amount > 0 ? esc_attr( $max_input_values_amount ) : 0; ?>"
	data-min-input-values-amount="<?php echo $min_input_values_amount > 0 ? esc_attr( $min_input_values_amount ) : 0; ?>"
	<?php echo $minimum_product_quantity > 0 && apply_filters( 'wapo_enable_minimum_product_quantity', false ) ? ' style="display: none;"' : ''; ?>>

	<?php if ( $type_title && 'yes' === $yith_wapo_frontend->option_show_label_type ) : ?>

		<<?php echo esc_attr( $addon_title_tag ); ?>>
			<?php echo esc_html( wptexturize( $type_title ) ); ?>
			<?php echo $required ? '<abbr class="required" title="' . esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ) . '">*</abbr>' : ''; ?>
			<?php echo $sold_individually ? '<abbr class="sold_individually"> (' . esc_html__( 'Sold individually', 'yith-woocommerce-product-add-ons' ) . ')</abbr>' : ''; ?>
		</<?php echo esc_attr( $addon_title_tag ); ?>>

	<?php endif; ?>

	<?php if ( $image && 'yes' === $yith_wapo_frontend->option_show_image_type ) : ?>

		<div class="ywapo_product_option_image"><img src="<?php echo esc_attr( $image ); ?>" alt="<?php echo esc_attr( $type_title ); ?>"/></div>

	<?php endif; ?>

	<?php if ( $description && 'yes' === $yith_wapo_frontend->option_show_description_type ) : ?>

		<div class="ywapo_product_option_description"><?php echo wptexturize( $description ); // phpcs:ignore ?></div>

	<?php endif; ?>

	<div class="ywapo_options_container">

		<?php if ( 'select' === $type_type ) : ?>

			<?php if ( apply_filters( 'yith_wapo_enable_select_option_image', false ) ) : ?>
				<div class="wapo_option_image"></div>
			<?php endif; ?>

			<label for="<?php echo esc_attr( $name ); ?>"></label>
			<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" class="ywapo_input" <?php echo $required ? 'required' : ''; ?> <?php echo esc_attr( $disabled ); ?>>
			<option value=""><?php echo esc_html( $empty_option_text ); ?></option>

		<?php endif; ?>

		<?php
		if ( is_array( $options ) ) {

			$options['label']       = array_map( 'stripslashes', $options['label'] );
			$options['description'] = array_map( 'stripslashes', $options['description'] );

			$options_label_count = count( $options['label'] );
			for ( $i = 0; $i < $options_label_count; $i++ ) {

				// --- WPML ----------
				if ( YITH_WAPO::$is_wpml_installed ) {
					$wpml_options = get_option( 'icl_sitepress_settings' );
					$default_lang = $wpml_options['default_language'];
					if ( apply_filters( 'yith_wapo_wpml_direct_translation', false ) && ICL_LANGUAGE_CODE !== $default_lang ) {
						$options['label'][ $i ]       = stripslashes( $options[ 'label_' . ICL_LANGUAGE_CODE ][ $i ] );
						$options['description'][ $i ] = stripslashes( $options[ 'description_' . ICL_LANGUAGE_CODE ][ $i ] );
						$options['placeholder'][ $i ] = stripslashes( $options[ 'placeholder_' . ICL_LANGUAGE_CODE ][ $i ] );
						$options['tooltip'][ $i ]     = stripslashes( $options[ 'tooltip_' . ICL_LANGUAGE_CODE ][ $i ] );
					} else {
						$options['label'][ $i ]       = YITH_WAPO_WPML::string_translate( $options['label'][ $i ] );
						$options['description'][ $i ] = YITH_WAPO_WPML::string_translate( $options['description'][ $i ] );
						$options['placeholder'][ $i ] = isset( $options['placeholder'][ $i ] ) ? YITH_WAPO_WPML::string_translate( $options['placeholder'][ $i ] ) : '';
						$options['tooltip'][ $i ]     = isset( $options['tooltip'][ $i ] ) ? YITH_WAPO_WPML::string_translate( $options['tooltip'][ $i ] ) : '';
					}
				}
				// ---END WPML---------

				$min        = $options['min'][ $i ] ?? false;
				$max        = $options['max'][ $i ] ?? false;
				$image      = isset( $options['image'][ $i ] ) && 'yes' === $yith_wapo_frontend->option_show_image_option ? $options['image'][ $i ] : '';
				$image_alt  = isset( $options['image_alt'][ $i ] ) && 'yes' === $yith_wapo_frontend->option_show_image_option ? $options['image_alt'][ $i ] : '';
				$price_type = $options['type'][ $i ] ?? 'fixed';

				$description = $options['description'][ $i ] ?? '';
				$placeholder = $options['placeholder'][ $i ] ?? '';
				$tooltip     = $options['tooltip'][ $i ] ?? '';

				$checked    = isset( $options['default'] ) && in_array( $i, $options['default'] ); //phpcs:ignore
				$hidelabel  = isset( $options['hidelabel'] ) && in_array( $i, $options['hidelabel'] ); //phpcs:ignore
				$hideoption = isset( $options['hideoption'] ) && in_array( $i, $options['hideoption'] ); //phpcs:ignore

				$required_option = false;
				if ( $required_all_options ) {
					$required_option = $required; }

				if ( ! $required_option ) {
					$required_option = isset( $options['required'] ) && in_array( $i, $options['required'] ); //phpcs:ignore
				}

				$yith_wapo_frontend->print_options(
					$i,
					$product,
					$type_id,
					$type_type,
					$name,
					$value,
					$options['price'][ $i ],
					$options['label'][ $i ],
					$image,
					$image_alt,
					$price_type,
					$description,
					$placeholder,
					$tooltip,
					$required_option,
					$checked,
					$hidelabel,
					$hideoption,
					$disabled,
					'before',
					$min,
					$max
				);

			}
		}

		if ( 'select' === $type_type ) :

			?>
		</select><p class="wapo_option_description"></p><?php endif; ?>

	</div>

</div>
