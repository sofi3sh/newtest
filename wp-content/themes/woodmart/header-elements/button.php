<?php
woodmart_enqueue_inline_style( 'header-elements-base' );

if ( isset( $params['link'] ) ) {
	$link_attrs = '';

	if ( isset( $params['link']['url'] ) ) {
		$link_attrs = 'url:' . rawurlencode( $params['link']['url'] );
	}

	if ( ! empty( $params['link']['blank'] ) ) {
		$link_attrs .= '|target:_blank';
	}

	$params['link'] = $link_attrs;
}

echo woodmart_shortcode_button($params);
