<?php
/**
 * WAPO Functions
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'yith_wapo_get_addons_by_block_id' ) ) {
	/**
	 * Get Addons by Block ID Function
	 *
	 * @param int $block_id Block ID.
	 * @return array
	 */
	function yith_wapo_get_addons_by_block_id( $block_id ) {

		global $wpdb;

		$query   = "SELECT id FROM {$wpdb->prefix}yith_wapo_addons WHERE block_id='$block_id' AND deleted='0' ORDER BY priority ASC";
		$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		$addons = array();
		foreach ( $results as $key => $addon ) {
			$addons[] = new YITH_WAPO_Addon( $addon->id );
		}
		return apply_filters( 'yith_wapo_addons_by_block_id', $addons, $block_id );
	}
}

if ( ! function_exists( 'yith_wapo_get_blocks' ) ) {
	/**
	 * Get Blocks Function
	 *
	 * @return array
	 */
	function yith_wapo_get_blocks() {

		global $wpdb;

		// YITH Multi Vendor integration.
		$vendor_check = '';
		if ( ! current_user_can( 'administrator' ) && class_exists( 'YITH_Vendors' ) && ! is_product() ) {
			$vendor = yith_get_vendor( 'current', 'user' );
			if ( $vendor->is_valid() ) {
				$vendor_check = "AND vendor_id='$vendor->id'";
			}
		}

		$query   = "SELECT id FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0' $vendor_check ORDER BY priority ASC";
		$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		$blocks = array();
		foreach ( $results as $key => $block ) {
			$blocks[] = new YITH_WAPO_Block( $block->id );
		}
		return $blocks;
	}
}

if ( ! function_exists( 'yith_wapo_get_currency_rate' ) ) {
	/**
	 * Get Currency Rate Function
	 *
	 * @return float|int
	 */
	function yith_wapo_get_currency_rate() {
		$currency_rate = 1;
		if ( function_exists( 'yith_wcmcs_get_current_currency' ) ) {
			if ( ! empty( yith_wcmcs_get_current_currency() ) ) {
				$currency_rate = (float) yith_wcmcs_get_current_currency()->get_data()['rate'];
			}
		}
		return $currency_rate;
	}
}

if ( ! function_exists( 'yith_wapo_get_option_info' ) ) {
	/**
	 * Get Option Info
	 *
	 * @param int $addon_id Addon ID.
	 * @param int $option_id Option ID.
	 * @return array
	 */
	function yith_wapo_get_option_info( $addon_id, $option_id ) {

		$info = array();

		if ( $addon_id > 0 ) {

			$addon = new YITH_WAPO_Addon( $addon_id );

			// Option.
			$info['color']        = $addon->get_option( 'color', $option_id );
			$info['label']        = $addon->get_option( 'label', $option_id );
			$info['tooltip']      = $addon->get_option( 'tooltip', $option_id );
			$info['price_method'] = $addon->get_option( 'price_method', $option_id );
			$info['price_type']   = $addon->get_option( 'price_type', $option_id );
			$info['price']        = $addon->get_option( 'price', $option_id );
			$info['price_sale']   = $addon->get_option( 'price_sale', $option_id );

			// Addon settings.
			$info['addon_label'] = $addon->get_setting( 'title' );
			$info['addon_type']  = $addon->get_setting( 'type' );

			// Addon advanced.
			$info['addon_first_options_selected'] = $addon->get_setting( 'first_options_selected' );
			$info['addon_first_free_options']     = $addon->get_setting( 'first_free_options' );

		}
		return $info;
	}
}

if ( ! function_exists( 'yith_wapo_get_option_label' ) ) {
	/**
	 * Get Option Label
	 *
	 * @param int $addon_id Addon ID.
	 * @param int $option_id Option ID.
	 * @return string
	 */
	function yith_wapo_get_option_label( $addon_id, $option_id ) {

		$label = '';
		$info = yith_wapo_get_option_info( $addon_id, $option_id );

		if ( ! empty( $info ) && is_array( $info ) ) {
			if ( in_array( $info['addon_type'], array(
				'checkbox',
				'radio',
				'color',
				'select',
				'label',
				'file',
				'product'
			), true ) ) {
				$label = isset( $info['addon_label'] ) && ! empty( $info['addon_label'] ) ? $info['addon_label'] : __( 'Option', 'yith-woocommerce-product-add-ons' );
			} else {
				$label = isset( $info['label'] ) && ! empty( $info['label'] ) ? $info['label'] : __( 'Option', 'yith-woocommerce-product-add-ons' );
			}
		}

		return $label;
	}
}



if ( ! function_exists( 'yith_wapo_get_option_price' ) ) {
	/**
	 * Get Option Price
	 *
	 * @param int $product_id Product ID.
	 * @param int $addon_id Addon ID.
	 * @param int $option_id Option ID.
	 * @param int $quantity Option Quantity.
	 */
	function yith_wapo_get_option_price( $product_id, $addon_id, $option_id, $quantity = 0 ) {
		$info              = yith_wapo_get_option_info( $addon_id, $option_id );
		$option_price      = '';
		$option_price_sale = '';
		if ( 'percentage' === $info['price_type'] ) {
			$_product = wc_get_product( $product_id );

			// WooCommerce Measurement Price Calculator (compatibility).
			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
				$product_price = $cart_item['pricing_item_meta_data']['_price'];
			} else {
				$product_price = ( $_product instanceof WC_Product ) ? floatval( $_product->get_price() ) : 0;
			}
			// end WooCommerce Measurement Price Calculator (compatibility).
			$option_percentage      = floatval( $info['price'] );
			$option_percentage_sale = floatval( $info['price_sale'] );
			$option_price           = ( $product_price / 100 ) * $option_percentage;
			$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
		} elseif ( 'multiplied' === $info['price_type'] ) {
			$option_price      = $info['price'] * $quantity;
			$option_price_sale = $info['price'] * $quantity;
		} else {
			$option_price      = $info['price'];
			$option_price_sale = $info['price_sale'];
		}

		return array(
			'price'      => $option_price,
			'price_sale' => $option_price_sale,
		);

	}
}

if ( ! function_exists( 'yith_wapo_get_tax_rate' ) ) {
	/**
	 * Get WooCommerce Tax Rate
	 */
	function yith_wapo_get_tax_rate() {
		$wc_tax_rate = false;

		if ( get_option( 'woocommerce_calc_taxes', 'no' ) === 'yes' ) {

			$wc_tax_rates = WC_Tax::get_rates();

			if ( is_cart() || is_checkout() ) {
				$wc_tax_rate = false;

				if ( get_option( 'woocommerce_prices_include_tax' ) === 'no' && get_option( 'woocommerce_tax_display_cart' ) === 'incl' ) {
					$wc_tax_rate = reset( $wc_tax_rates )['rate'] ?? 0;
				}
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'yes' && get_option( 'woocommerce_tax_display_cart' ) === 'excl' ) {
					$wc_tax_rate = - reset( $wc_tax_rates )['rate'] ?? 0;
				}
			} else {
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'no' && get_option( 'woocommerce_tax_display_shop' ) === 'incl' ) {
					$wc_tax_rate = reset( $wc_tax_rates )['rate'] ?? 0;
				}
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'yes' && get_option( 'woocommerce_tax_display_shop' ) === 'excl' ) {
					$wc_tax_rate = - reset( $wc_tax_rates )['rate'] ?? 0;
				}
			}
		}

		return $wc_tax_rate;
	}
}

if ( ! function_exists( 'yith_wapo_is_addon_type_available' ) ) {
	/**
	 * Is addon type available
	 *
	 * @param string $addon_type Addon type.
	 */
	function yith_wapo_is_addon_type_available( $addon_type ) {
		if ( '' === $addon_type || substr( $addon_type, 0, 5 ) === 'html_' || in_array( $addon_type, YITH_WAPO()->get_available_addon_types(), true ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'yith_wapo_previous_version_exists' ) ) {
	/**
	 * Previous version check
	 */
	function yith_wapo_previous_version_exists() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'yith_wapo_groups';

		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		if ( $wpdb->get_var( $query ) === $table_name ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
			return true;
		}
		return ( $wpdb->get_var( $query ) === $table_name ) ? true : false; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
	}
}

if ( ! function_exists( 'yith_wapo_product_has_blocks' ) ) {
	/**
	 * Product has blocks
	 *
	 * @param int $product_id Product ID.
	 */
	function yith_wapo_product_has_blocks( $product_id ) {

		if ( ! $product_id ) {
			return false;
		}

		$product = wc_get_product( $product_id );

		if ( $product instanceof WC_Product ) {
			$product_categories = $product->get_category_ids();
			$exclude_global     = apply_filters( 'yith_wapo_exclude_global', get_post_meta( $product_id, '_wapo_disable_global', true ) === 'yes' ? 1 : 0 );

			foreach ( yith_wapo_get_blocks() as $key => $block ) {

				if ( '1' === $block->visibility ) {

					$show_in                   = $block->get_rule( 'show_in' );
					$included_product_check    = in_array( (string) $product->get_id(), (array) $block->get_rule( 'show_in_products' ), true );
					$included_category_check   = count( array_intersect( (array) $block->get_rule( 'show_in_categories' ), $product_categories ) ) > 0;
					$exclude_in                = $block->get_rule( 'exclude_products' );
					$excluded_product_check    = ( 'all' === $show_in || 'categories' === $show_in ) && in_array( absint( $product->get_id() ), array_map( 'absint', (array) $block->get_rule( 'exclude_products_products' ) ), true );
					$excluded_categories_check = 'all' === $show_in && count( array_intersect( (array) $block->get_rule( 'exclude_products_categories' ), $product_categories ) ) > 0;

					$show_to            = $block->get_rule( 'show_to' );
					$show_to_user_roles = $block->get_rule( 'show_to_user_roles' );
					$show_to_membership = $block->get_rule( 'show_to_membership' );

					// Include.
					if ( ( 'all' === $show_in && ! $exclude_global ) || ( 'products' === $show_in && ( $included_product_check || $included_category_check ) ) ) {
						// Exclude.
						if ( 'yes' !== $exclude_in || ( ! $excluded_product_check && ! $excluded_categories_check ) ) {
							// Show to.
							if (
								'' === $show_to
								|| 'all' === $show_to
								|| ( 'guest_users' === $show_to && ! is_user_logged_in() )
								|| ( 'logged_users' === $show_to && is_user_logged_in() )
								|| ( 'user_roles' === $show_to && count( array_intersect( (array) $show_to_user_roles, (array) wp_get_current_user()->roles ) ) > 0 )
								|| ( 'membership' === $show_to && yith_wcmbs_user_has_membership( get_current_user_id(), $show_to_membership ) )
							) {
								$addons = yith_wapo_get_addons_by_block_id( $block->id );
								if ( count( $addons ) > 0 ) {
									return true;
								}
							}
						}
					}
				}
			}
		}

		return false;

	}
}

if ( ! function_exists( 'yith_wapo_wpml_register_string' ) ) {
	/**
	 * Register a string in wpml translation.
	 *
	 * @param string $context The context name.
	 * @param string $name    The name.
	 * @param string $value   The value to translate.
	 */
	function yith_wapo_wpml_register_string( $context, $name, $value ) {
		do_action( 'wpml_register_single_string', $context, $name, $value );
	}
}

if( !function_exists('yith_wapo_calculate_price_depending_on_tax') ) {
	/**
	 * Calculate the price with the tax included if necessary.
	 *
	 * @author Ivan Sosa
	 * @param float $price The price added.
	 * @return float
	 */
	function yith_wapo_calculate_price_depending_on_tax( $price ) {
		if ( ! wc_tax_enabled() ) {
			return $price;
		}

		if ( 0 !== $price && '' !== $price ) {
			$price += floatval( $price ) * floatval( yith_wapo_get_tax_rate() / 100 );
		}
		return $price;
	}
}
