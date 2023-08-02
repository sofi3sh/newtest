<?php
/**
 * Blocks Tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$blocks = array(
	'blocks' => array(
		'blocks-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wapo_show_blocks_tab',
		),
	),
);

return apply_filters( 'yith_wapo_panel_blocks_options', $blocks );
