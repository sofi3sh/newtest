<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$heading_text  = $addon->get_setting( 'heading_text' );
$heading_type  = $addon->get_setting( 'heading_type' );
$heading_color = $addon->get_setting( 'heading_color' );

if ( YITH_WAPO::$is_wpml_installed ) {
	$heading_text = YITH_WAPO_WPML::string_translate( $heading_text );
}

?>

<<?php echo esc_attr( $heading_type ); ?> style="color: <?php echo esc_attr( $heading_color ); ?>;">

	<?php echo wp_kses_post( $heading_text ); ?>

</<?php echo esc_attr( $heading_type ); ?>>
