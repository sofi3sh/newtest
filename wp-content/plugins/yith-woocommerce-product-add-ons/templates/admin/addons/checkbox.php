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
	<?php echo esc_html__( 'CHECKBOX', 'yith-woocommerce-product-add-ons' ); ?> -
	<?php echo esc_html( $addon->get_option( 'label', $x ) ); ?>
</div>

<div class="fields">
	<?php require YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>
</div>
