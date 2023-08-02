<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options;

Options::add_field(
	array(
		'id'          => 'maintenance_mode',
		'name'        => esc_html__( 'Enable maintenance mode', 'woodmart' ),
		'hint'        => wp_kses( __( '<img data-src="' . WOODMART_TOOLTIP_URL . 'enable-maintenance-mode.jpg" alt="">', 'woodmart' ), true ),
		'description' => esc_html__( 'If enabled you need to create maintenance page in Dashboard - Pages - Add new. Choose "Template" to be "Maintenance" in "Page attributes". Or you can import the page from our demo in Dashboard - Woodmart - Prebuilt websites', 'woodmart' ),
		'type'        => 'switcher',
		'section'     => 'maintenance',
		'default'     => false,
		'priority'    => 10,
	)
);
