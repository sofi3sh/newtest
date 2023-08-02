<?php
/**
 * Blocks Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$block_id = isset( $_REQUEST['block_id'] ) ? sanitize_key( $_REQUEST['block_id'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

if ( $block_id ) {
	include YITH_WAPO_DIR . '/templates/admin/block-editor.php';
} else {
	include YITH_WAPO_DIR . '/templates/admin/blocks-table.php';
}

?>

<script type="text/javascript">
	jQuery('.yith-wapo a, .yith-wapo button, .yith-wapo input').on( 'click', function() {
		window.onbeforeunload = null;
	});
</script>
