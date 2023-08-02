<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$text_content = $addon->get_setting( 'text_content' );

if ( YITH_WAPO::$is_wpml_installed ) {
	$text_content = YITH_WAPO_WPML::string_translate( $text_content );
}

?>

<p>
	<?php echo wp_kses_post( $text_content ); ?>
</p>
