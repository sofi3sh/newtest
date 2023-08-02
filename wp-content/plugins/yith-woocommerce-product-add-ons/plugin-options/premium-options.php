<?php
/**
 *  Premium Tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$premium = array(
	'premium' => array(
		'landing' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wapo_premium_tab',
			// 'hide_sidebar' => true, phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		),
	),
);

return apply_filters( 'yith_wapo_panel_premium_options', $premium );
