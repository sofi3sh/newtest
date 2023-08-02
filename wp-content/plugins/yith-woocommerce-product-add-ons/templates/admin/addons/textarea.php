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
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title">
	<span class="icon"></span>
	<?php echo esc_html__( 'TEXTAREA', 'yith-woocommerce-product-add-ons' ); ?> -
	<?php echo esc_html( $addon->get_option( 'label', $x ) ); ?>
</div>

<div class="fields">

	<?php require YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-characters-limit"><?php echo esc_html__( 'Limit input characters', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-characters-limit',
					'class' => 'enabler',
					'name'  => 'options[characters_limit][]',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'characters_limit', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-characters-limit" style="display: none;">
		<label for="option-characters-limit"><?php echo esc_html__( 'Number of characters', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<small><?php echo esc_html__( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[characters_limit_min][]" id="option-characters-limit-min" value="<?php echo esc_attr( $addon->get_option( 'characters_limit_min', $x ) ); ?>" class="mini">
		</div>
		<div class="field">
			<small><?php echo esc_html__( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[characters_limit_max][]" id="option-characters-limit-max" value="<?php echo esc_attr( $addon->get_option( 'characters_limit_max', $x ) ); ?>" class="mini">
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<!--
	<div class="field-wrap">
		<label for="option-advanced-editor"><?php echo esc_html__( 'Advanced editor', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-advanced-editor',
					'name'  => 'options[advanced_editor][]',
					'type'  => 'onoff',
					'value' => $addon->get_option( 'advanced_editor', $x ),
				),
				true
			);
			?>
		</div>
	</div>
	-->
	<!-- End option field -->

</div>
