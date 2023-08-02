<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Option class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;



if ( ! class_exists( 'YITH_WAPO_Option' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WAPO_Option {


		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Get Option By Value Key
		 *
		 * @param string $single_type Single Type.
		 * @param string $value Value.
		 * @param string $field_name Field name.
		 *
		 * @author Andrea Frascaspata
		 * @return int
		 */
		public static function getOptionDataByValueKey( $single_type, $value, $field_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$options = maybe_unserialize( $single_type->options );

			return stripslashes( $options[ $field_name ][ $value ] );

		}

		/**
		 * Get Option Data By Value Radio
		 *
		 * @param string $single_type Single Type.
		 * @param string $value Value.
		 * @param string $field_name Field name.
		 *
		 * @author Andrea Frascaspata
		 * @return int
		 */
		public static function getOptionDataByValueRadio( $single_type, $value, $field_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$options = maybe_unserialize( $single_type->options );

			return stripslashes( $options[ $field_name ][ $value ] );

		}

		/**
		 * Get Option Data By Value Select
		 *
		 * @param string $single_type Single Type.
		 * @param string $value Value.
		 * @param string $field_name Field name.
		 *
		 * @author Andrea Frascaspata
		 * @return mixed
		 */
		public static function getOptionDataByValueSelect( $single_type, $value, $field_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$options = maybe_unserialize( $single_type->options );

			return stripslashes( $options[ $field_name ][ $value ] );

		}

		/**
		 * Get Option Data By Value Labels
		 *
		 * @param string $single_type Single Type.
		 * @param array  $values Values.
		 * @param string $field_name Field name.
		 *
		 * @author Andrea Frascaspata
		 * @return mixed
		 */
		public static function getOptionDataByValueLabels( $single_type, $values, $field_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$options = maybe_unserialize( $single_type->options );

			$index = -1;
			foreach ( $values as $value ) {
				if ( '' !== $value ) {
					$index = $value;
					break;
				}
			}

			if ( $index >= 0 ) {
				if ( isset( $options[ $field_name ][ $index ] ) ) {
					return stripslashes( $options[ $field_name ][ $index ] );
				} else {
					return false;
				}
			} else {
				return false;
			}

		}

		/**
		 * Get Option Data By Value Multiple Labels
		 *
		 * @param string $single_type Single Type.
		 * @param string $selected_value Values.
		 * @param string $field_name Field name.
		 */
		public static function getOptionDataByValueMultipleLabels( $single_type, $selected_value, $field_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$options = maybe_unserialize( $single_type->options );

			if ( $selected_value >= 0 ) {
				return stripslashes( $options[ $field_name ][ $selected_value ] );
			} else {
				return false;
			}
		}

	}

}
