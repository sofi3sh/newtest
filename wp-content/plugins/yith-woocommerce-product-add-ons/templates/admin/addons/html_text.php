<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase: Filenames
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var object $addon
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="options-editor-color">

	<!--<h3><?php echo esc_html__( 'Text', 'yith-woocommerce-product-add-ons' ); ?></h3>-->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-text-content"><?php echo esc_html__( 'Enter your text content', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field rule">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'option-text-content',
					'name'  => 'option_text_content',
					'type'  => 'textarea',
					'value' => $addon->get_setting( 'text_content' ),
				),
				true
			);
			?>
			<span class="description"></span>
		</div>
	</div>
	<!-- End option field -->

</div><!-- #options-editor-color -->
