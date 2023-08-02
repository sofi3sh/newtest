<?php
/**
 *  Import Export Tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$impexp = array(
	'impexp' => array(
		'impexp-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wapo_impexp_tab',
		),
	),
);

return apply_filters( 'yith_wapo_panel_impexp_options', $impexp );
