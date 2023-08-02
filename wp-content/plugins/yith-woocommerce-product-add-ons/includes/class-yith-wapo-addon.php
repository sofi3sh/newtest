<?php
/**
 * WAPO Addon Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Addon' ) ) {

	/**
	 *  Addon class.
	 *  The class manage all the Addon behaviors.
	 */
	class YITH_WAPO_Addon {

		/**
		 *  ID
		 *
		 *  @var int
		 */
		public $id = 0;

		/**
		 *  Settings
		 *
		 *  @var array
		 */
		public $settings = array();

		/**
		 *  Options
		 *
		 *  @var array
		 */
		public $options = array();

		/**
		 *  Priority
		 *
		 *  @var int
		 */
		public $priority = 0;

		/**
		 *  Visibility
		 *
		 *  @var array
		 */
		public $visibility = 1;

		/**
		 *  Type
		 *
		 *  @var string
		 */
		public $type = 0;

		/**
		 *  Constructor
		 *
		 * @param int $id Addon ID.
		 */
		public function __construct( $id ) {

			global $wpdb;

			if ( $id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_addons WHERE id='$id'";
				$row   = $wpdb->get_row( $query ); // phpcs:ignore

				if ( isset( $row ) && $row->id === (string) $id ) {

					$this->id         = $row->id;
					$this->settings   = @unserialize( $row->settings ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize, WordPress.PHP.NoSilencedErrors.Discouraged
					$this->options    = @unserialize( $row->options ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize, WordPress.PHP.NoSilencedErrors.Discouraged
					$this->priority   = $row->priority;
					$this->visibility = $row->visibility;

					// Settings.
					$this->type = isset( $this->settings['type'] ) ? $this->settings['type'] : 'html_text';

				}
			}

		}

		/**
		 *  Get Setting
		 *
		 * @param string $option Option name.
		 * @param string $default Default value.
		 */
		public function get_setting( $option, $default = '' ) {
			return isset( $this->settings[ $option ] ) ? $this->settings[ $option ] : $default;
		}

		/**
		 *  Get Option
		 *
		 * @param string $option Option name.
		 * @param int    $index Option index.
		 * @param string $default Default value.
		 */
		public function get_option( $option, $index, $default = '' ) {
			if ( is_array( $this->options )
				&& isset( $this->options[ $option ] )
				&& is_array( $this->options[ $option ] )
				&& isset( $this->options[ $option ][ $index ] ) ) {
				if ( YITH_WAPO::$is_wpml_installed ) {
					return YITH_WAPO_WPML::string_translate( $this->options[ $option ][ $index ] );
				}
				return $this->options[ $option ][ $index ];
			}
			return $default;
		}

		/**
		 *  Get Option Price
		 *
		 * @param int $index Option index.
		 */
		public function get_option_price( $index ) {
			global $product, $variation;

			$product_price = YITH_WAPO_Front()->current_product_price;
			$currency_rate = yith_wapo_get_currency_rate();
			$price         = 0;
			$tax_inc       = get_option( 'woocommerce_prices_include_tax' ) === 'yes';

			if ( $this->get_option( 'price_method', $index ) !== 'free' ) {
				if ( $this->get_option( 'price_type', $index ) === 'percentage' ) {
					$option_percentage      = floatval( $this->get_option( 'price', $index ) );
					$option_percentage_sale = floatval( $this->get_option( 'price_sale', $index ) );
					$price                  = ( $product_price / 100 ) * $option_percentage;
				} else {
					$price = (float) $this->get_option( 'price', $index ) * $currency_rate;
				}
				if ( $this->get_option( 'price_method', $index ) === 'decrease' ) {
					$price = -1 * $price;
				}

				if ( wc_tax_enabled() && ! $tax_inc && 0 !== $price ) {
					$price += $price * ( yith_wapo_get_tax_rate() / 100 );
				}

				$price = $price + ( ( $price / 100 ) * yith_wapo_get_tax_rate() );
			}
			return apply_filters( 'yith_wapo_option_' . $this->id . '_' . $index . '_price', $price );
		}

		/**
		 *  Get Option Price HTML
		 *
		 * @param int $index Option index.
		 */
		public function get_option_price_html( $index ) {
			global $product, $variation;

			$html_price    = '';
			$product_price = YITH_WAPO_Front()->current_product_price;
			$currency_rate = yith_wapo_get_currency_rate();
			$tax_inc       = get_option( 'woocommerce_prices_include_tax' ) === 'yes';

			$price_method = $this->get_option( 'price_method', $index );
			$price_type   = $this->get_option( 'price_type', $index );

			$option_price      = (float) $this->get_option( 'price', $index );
			$option_price_sale = (float) $this->get_option( 'price_sale', $index );

			if ( $price_method !== 'free' ) {
				if ( 'percentage' === $price_type ) {
					$option_percentage      = floatval( $this->get_option( 'price', $index ) );
					$option_percentage_sale = floatval( $this->get_option( 'price_sale', $index ) );
					$option_price           = ( $product_price / 100 ) * $option_percentage;
					$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
				} elseif ( 'multiplied' === $price_type ) {
					$option_price      = (float) $this->get_option( 'price', $index ) * $currency_rate;
					$option_price_sale = 0;
				} else {
					$option_price      = $option_price * $currency_rate;
					$option_price_sale = $option_price_sale * $currency_rate;
				}
				$sign       = '+';
				$sign_class = 'positive';
				if ( $this->get_option( 'price_method', $index ) === 'decrease' ) {
					$sign              = '-';
					$sign_class        = 'negative';
					$option_price_sale = 0;
				}

				if ( wc_tax_enabled() && ! $tax_inc && 0 !== $option_price ) {
					$option_price += $option_price * ( yith_wapo_get_tax_rate() / 100 );
				}
				if ( wc_tax_enabled() && ! $tax_inc && 0 !== $option_price_sale ) {
					$option_price_sale += $option_price_sale * ( yith_wapo_get_tax_rate() / 100 );
				}

				if ( '' !== $option_price && $option_price > 0 ) {
					if ( '' !== $option_price_sale && $option_price_sale > 0 ) {
						$html_price = '<small class="option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span><del>' . wc_price( $option_price ) . '</del> ' . wc_price( $option_price_sale ) . '<span class="brackets">)</span></small>';
					} else {
						$html_price = '<small class="option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span>' . wc_price( $option_price ) . '<span class="brackets">)</span></small>';
					}
				}
			}
			return apply_filters( 'yith_wapo_option_' . $this->id . '_' . $index . '_price_html', $html_price );
		}

	}

}
