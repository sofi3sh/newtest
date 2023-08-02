<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * WAPO Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$separator_style = $addon->get_setting( 'separator_style' );
$separator_width = $addon->get_setting( 'separator_width', 100 );
$separator_size  = $addon->get_setting( 'separator_size', 2 );
$separator_color = $addon->get_setting( 'separator_color' );

if ( 'empty_space' === $separator_style ) {
	$css = 'height: ' . $separator_size . 'px';
} else {
	$css = 'width: ' . $separator_width . '%; border-width: ' . $separator_size . 'px; border-color: ' . ( ! is_array( $separator_color ) ? $separator_color : '' ) . ';';
}

?>

<div class="yith-wapo-separator <?php echo esc_attr( $separator_style ); ?>" style="<?php echo esc_attr( $css ); ?>"></div>
