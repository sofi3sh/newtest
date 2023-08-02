<?php
/**
 * WAPO Cart Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Cart' ) ) {

	/**
	 *  YITH_WAPO Cart Class
	 */
	class YITH_WAPO_Cart {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO_Instance
		 */
		public static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO_Instance
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Loop add to cart button.
			if ( 'select' === get_option( 'yith_wapo_button_in_shop' ) ) {
				add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 50, 1 );
				add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 1 );
				add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 2 );
			}

			// Add options to cart item.
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 25, 2 );
            add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 'add_cart_item_data_order_again' ), 25, 3 );

            // Display custom product thumbnail in cart.
			if ( 'yes' === get_option( 'yith_wapo_show_image_in_cart', 'no' ) ) {
				add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 3 );
			}

			// Add to cart the total price of the item with the addons.
			add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1 );

			// Display options in cart and checkout page.
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 25, 2 );

			// Load cart data per page load.
			add_filter( 'woocommerce_get_cart_item_from_session', array(
				$this,
				'get_cart_item_from_session'
			), 100, 2 );

			// Update cart total
			// add_filter( 'woocommerce_calculated_total', array( $this, 'custom_calculated_total' ), 10, 2 );
			// Add order item meta.
			add_action( 'woocommerce_new_order_item', array( $this, 'add_order_item_meta' ), 10, 3 );


			// Product Bundles
			add_filter( 'yith_wcpb_woocommerce_cart_item_price', array( $this, 'ywcpb_woocommerce_cart_item_price' ), 10, 3 );

			add_filter( 'yith_wcpb_bundle_pip_bundled_items_subtotal', array(
				$this,
				'ywcpb_bundle_pip_bundled_items_subtotal'
			), 10, 3 );

			add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_subtotal' ), 10, 3 );

		}

		/**
		 * Add to cart validation
		 *
		 * @param bool $passed Passed.
		 * @param int $product_id Product ID.
		 *
		 * @return false|mixed
		 */
		public function add_to_cart_validation( $passed, $product_id ) {

			// Disable add_to_cart_button class on shop page.
			if ( wp_doing_ajax() && ! isset( $_REQUEST['yith_wapo_is_single'] ) && yith_wapo_product_has_blocks( $product_id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}

			return $passed;
		}

		/**
		 * Filter cart item from session.
		 *
		 * @param array $cart_item Cart item.
		 * @param array $values Add-ons options.
		 *
		 * @return mixed
		 */
		public function get_cart_item_from_session( $cart_item, $values ) {

			if ( ! empty( $values['yith_wapo_options'] ) ) {
				$cart_item['yith_wapo_options'] = $values['yith_wapo_options'];
				$cart_item                      = $this->add_cart_item( $cart_item );

				if ( isset( $cart_item['ywsbs-subscription-info'] ) ) {
					$cart_item['ywsbs-subscription-info']['recurring_price'] = $cart_item['data']->get_price();
				}
			}

			return $cart_item;
		}

		/**
		 * Set the data for the cart item in cart object.
		 *
		 * @param array $cart_item_data Cart item data.
		 * @param int $product_id Product ID.
		 * @param array $post_data Post data.
		 * @param bool $sold_individually Sold individually.
		 *
		 * @return mixed
		 */
		public function add_cart_item_data( $cart_item_data, $product_id, $post_data = null, $sold_individually = false ) {
			if ( is_null( $post_data ) ) {
				$post_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			}
			if ( isset( $cart_item_data['bundled_by'] ) ) {
				return $cart_item_data;
			}
			$data = array();

			if ( isset( $post_data['yith_wapo'] ) && is_array( $post_data['yith_wapo'] ) ) {
				$cart_item_data['yith_wapo_product_img'] = $post_data['yith_wapo_product_img'];
				foreach ( $post_data['yith_wapo'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						$cart_item_data['yith_wapo_options'][ $index ][ $key ] = $value;
						$data[ $key ]                                          = $value;
					}
				}
			}

			return $cart_item_data;
		}

        /**
         * Set the data for the cart item in cart object.
         *
         * @param array $cart_item_data Cart item data.
         * @param int   $item The item object.
         * @param array $order The Order Object.
         *
         * @return mixed
         */
        public function add_cart_item_data_order_again( $cart_item_data, $item, $order ) {

            $item_id       = $item->get_id();
            $meta_data     = wc_get_order_item_meta( $item_id, '_ywapo_meta_data', true );
            $product_image = wc_get_order_item_meta( $item_id, '_ywapo_product_img', true );

            if ( ! empty( $meta_data ) ) {
                $cart_item_data['yith_wapo_options'] = $meta_data;
            }
            if ( ! empty( $product_image ) ) {
                $cart_item_data['yith_wapo_product_img'] = $product_image;
            }

            return $cart_item_data;
        }

		/**
		 * Filter Item before add to cart.
		 *
		 * @param array $cart_item Cart item.
		 *
		 * @return mixed
		 */
		public function add_cart_item( $cart_item ) {

			// Avoid sum addons price of child products of YITH Composite Products.
			if ( isset( $cart_item['yith_wcp_child_component_data'] ) ) {
				return $cart_item;
			}

			// Avoid sum addons price of child products of YITH Product Bundles.
			if ( isset( $cart_item['bundled_by'] ) ) {
				return $cart_item;
			}

			$wapo_price = yit_get_prop( $cart_item['data'], 'yith_wapo_price' );
			if ( ! empty( $cart_item['yith_wapo_options'] ) && ! $wapo_price ) {
				$total_options_price      = 0;
				$first_free_options_count = 0;
				$currency_rate            = yith_wapo_get_currency_rate();
				$_product = wc_get_product( $cart_item['product_id'] );
				// WooCommerce Measurement Price Calculator (compatibility).
				if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
					$product_price = $cart_item['pricing_item_meta_data']['_price'];
				} else {
					$product_price = yit_get_display_price( $_product );
				}
				$addon_id_check = '';
				foreach ( $cart_item['yith_wapo_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {
                            if ( ! is_array( $value ) ) {
                                $value = stripslashes( $value );
                            }
							$explode = explode( '-', $key );
							if ( isset( $explode[1] ) ) {
								$addon_id  = $explode[0];
								$option_id = $explode[1];
							} else {
								$addon_id  = $key;
								$option_id = $value;
							}

							if ( $addon_id != $addon_id_check ) {
								$first_free_options_count = 0;
								$addon_id_check = $addon_id;
							}

							$info = yith_wapo_get_option_info( $addon_id, $option_id );

							if ( 'percentage' === $info['price_type'] ) {
								$option_percentage      = floatval( $info['price'] );
								$option_percentage_sale = floatval( $info['price_sale'] );
								$option_price           = ( $product_price / 100 ) * $option_percentage;
								$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
							} elseif ( 'multiplied' === $info['price_type'] ) {
								$option_price      = (float) $info['price'] * (float) $value;
								$option_price_sale = (float) $info['price_sale'] * (float) $value;
							} elseif ( 'characters' === $info['price_type'] ) {
								$remove_spaces     = apply_filters( 'yith_wapo_remove_spaces', false );
								$value             = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
								$option_price      = (float) $info['price'] * strlen( $value );
								$option_price_sale = (float) $info['price_sale'] * strlen( $value );
							} else {
								$option_price      = (float) $info['price'];
								$option_price_sale = (float) $info['price_sale'];
							}

							if ( 'number' === $info['addon_type'] ) {
								if ( 'value_x_product' === $info['price_method'] ) {
									$option_price = $value * $product_price;
								} else {
									if ( 'multiplied' === $info['price_type'] ) {
										$option_price      = $value * $info['price'];
										$option_price_sale = 0; // By default 0, since sale price doesn't exists.
									}
								}
							}

							if ( 'free' === $info['price_method'] ) {
								$option_price      = 0;
								$option_price_sale = 0;
							}

							// First X free options check.
							if ( 'yes' === $info['addon_first_options_selected'] && $first_free_options_count < $info['addon_first_free_options'] ) {
								$first_free_options_count ++;
							} else {
								$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;
								if ( 'product' === $info['addon_type'] && ( 'product' === $info['price_method'] || 'discount' === $info['price_method'] ) ) {
									$option_product_info = explode( '-', $value );
									$option_product_id   = $option_product_info[1];
									$option_product_qty  = $option_product_info[2];
									$option_product      = wc_get_product( $option_product_id );
									$product_price       = $option_product instanceof WC_Product ? $option_product->get_price() : '';
									if ( 'product' === $info['price_method'] ) {
										$option_price = $product_price;
									} elseif ( 'discount' === $info['price_method'] ) {
										$option_discount_value = floatval( $info['price'] );
										if ( 'percentage' === $info['price_type'] ) {
											$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
										} else {
											$option_price = $product_price - $option_discount_value;
										}
									}
									$total_options_price += floatval( $option_price );

								} elseif ( 'decrease' === $info['price_method'] ) {
									$total_options_price -= floatval( $option_price );
								} else {
									$total_options_price += floatval( $option_price );
								}
							}
						}
					}
				}
				$cart_item_price     = is_numeric( $cart_item['data']->get_price() ) ? ( $cart_item['data']->get_price() / $currency_rate ) : 0;
				$total_options_price = $total_options_price / $currency_rate;
				/* phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				 * Multi Currency test
				var_dump( $cart_item_price, $total_options_price );
				add_action( 'yith_wcmcs_pre_product_price', function( $cart_item_price, $total_options_price ) {
					return $cart_item_price + $total_options_price;
				}, 10, 3 );
				*/

				$cart_item['data']->set_price( $cart_item_price + $total_options_price );
				yit_set_prop( $cart_item['data'], 'yith_wapo_price', true );

			}

			return $cart_item;
		}

		/**
		 * Change the product image with the addon one (if selected).
		 *
		 * @param string $_product_img Product image.
		 * @param array $cart_item Cart item.
		 * @param string $cart_item_key Cart item key.
		 *
		 * @return mixed|string
		 */
		public function cart_item_thumbnail( $_product_img, $cart_item, $cart_item_key ) {
			if ( isset( $cart_item['yith_wapo_product_img'] ) ) {
				$image_url = $cart_item['yith_wapo_product_img'];
				if ( ! empty( $image_url ) ) {
					return '<img src="' . $image_url . '" />';
				}
			}

			return $_product_img;
		}

		/**
		 * Update cart items info.
		 *
		 * @param array $cart_data Cart data.
		 * @param array $cart_item Cart item.
		 *
		 * @return mixed
		 */
		public function get_item_data( $cart_data, $cart_item ) {

			// Avoid show addons of child products of YITH Composite Products.
			if ( isset( $cart_item['yith_wcp_child_component_data'] ) ) {
				return $cart_data;
			}

			$product_parent_id = yit_get_base_product_id( $cart_item['data'] );

			if ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ) {
				$base_product = new WC_Product_Variation( $cart_item['variation_id'] );
			} else {
				$base_product = wc_get_product( $product_parent_id );
			}

			if ( is_object( $base_product ) && ! empty( $cart_item['yith_wapo_options'] ) ) {
				//phpcs:ignore && 'yes' === get_option( 'yith_wapo_settings_show_product_price_cart' ) ) && ( isset( $cart_item['yith_wapo_sold_individually'] ) && ! $cart_item['yith_wapo_sold_individually'] ) ) {

				if ( 'yith_bundle' === $base_product->get_type() && true === $base_product->per_items_pricing && function_exists( 'YITH_WCPB_Frontend_Premium' ) && method_exists( yith_wcpb_frontend(), 'format_product_subtotal' ) ) {
					$price = yith_wcpb_frontend()->calculate_bundled_items_price_by_cart( $cart_item );
				} else {
					$price = yit_get_display_price( $base_product );
				}

				$price_html = wc_price( $price );

				$cart_data[] = apply_filters(
                    'yith_wapo_base_price_cart_data',
                    array(
					    'name'  => __( 'Base price', 'yith-woocommerce-product-add-ons' ),
					    'value' => $price_html,
				    )
                );
			}

			if ( ! empty( $cart_item['yith_wapo_options'] ) ) {
				// $total_options_price = 0; phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				$cart_data_array          = array();
				$first_free_options_count = 0;
				$currency_rate            = yith_wapo_get_currency_rate();
				$_product = wc_get_product( $cart_item['product_id'] );
				// WooCommerce Measurement Price Calculator (compatibility).
				if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
					$product_price = $cart_item['pricing_item_meta_data']['_price'];
				} else {
					$product_price = yit_get_display_price( $_product );
				}
				$addon_id_check = '';
				foreach ( $cart_item['yith_wapo_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {
							$value   = stripslashes( $value );
							$explode = explode( '-', $key );
							if ( isset( $explode[1] ) ) {
								$addon_id  = $explode[0];
								$option_id = $explode[1];
							} else {
								$addon_id  = $key;
								$option_id = $value;
							}

							if ( $addon_id != $addon_id_check ) {
								$first_free_options_count = 0;
								$addon_id_check = $addon_id;
							}

							$info = yith_wapo_get_option_info( $addon_id, $option_id );

							if ( 'percentage' === $info['price_type'] ) {
								$option_percentage      = floatval( $info['price'] );
								$option_percentage_sale = floatval( $info['price_sale'] );
								$option_price           = ( $product_price / 100 ) * $option_percentage;
								$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
							} elseif ( 'multiplied' === $info['price_type'] ) {
								$option_price      = floatval( $info['price'] ) * (float) $value * (float) $currency_rate;
								$option_price_sale = floatval( $info['price_sale'] ) * (float) $value * (float) $currency_rate;
							} elseif ( 'characters' === $info['price_type'] ) {
								$remove_spaces     = apply_filters( 'yith_wapo_remove_spaces', false );
								$value             = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
								$option_price      = floatval( $info['price'] ) * strlen( $value ) * (float) $currency_rate;
								$option_price_sale = floatval( $info['price_sale'] ) * strlen( $value ) * (float) $currency_rate;
							} else {
								$option_price      = floatval( $info['price'] ) * (float) $currency_rate;
								$option_price_sale = floatval( $info['price_sale'] ) * (float) $currency_rate;
							}

							$sign = 'decrease' === $info['price_method'] ? '-' : '+';
							$is_empty_select = 'select' === $info['addon_type'] && 'default' === $option_id;

							// First X free options check.
							if ( 'yes' === $info['addon_first_options_selected'] && $first_free_options_count < $info['addon_first_free_options'] ) {
								$option_price = 0;
								$first_free_options_count ++;
							} else {
								$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;
							}

							$cart_data_name = $info['addon_label'] ?? '';

							if ( in_array( $info['addon_type'], array(
								'checkbox',
								'color',
								'label',
								'radio',
								'select'
							), true ) ) {
								$value = ! empty( $info['label'] ) ? $info['label'] : ( $info['tooltip'] ?? '' );
							} elseif ( 'product' === $info['addon_type'] ) {
								$option_product_info = explode( '-', $value );
								$option_product_id   = $option_product_info[1];
								$option_product_qty  = $option_product_info[2];
								$option_product      = wc_get_product( $option_product_id );
								if ( $option_product && $option_product instanceof WC_Product ) {
									$value = $option_product->get_formatted_name();

									// product prices.
									$product_price = $option_product->get_price();
									if ( 'product' === $info['price_method'] ) {
										$option_price = $product_price;
									} elseif ( 'discount' === $info['price_method'] ) {
										$option_discount_value = floatval( $info['price'] );
										if ( 'percentage' === $info['price_type'] ) {
											$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
										} else {
											$option_price = $product_price - $option_discount_value;
										}
									}
								}
							} elseif ( 'file' === $info['addon_type'] ) {
								$file_url = explode( '/', $value );
								$value    = '<a href="' . $value . '" target="_blank">' . end( $file_url ) . '</a>';
							} elseif ( 'number' === $info['addon_type'] ) {
								if ( 'value_x_product' === $info['price_method'] ) {
									$option_price = $value * $product_price;
								} else {
									if ( 'multiplied' === $info['price_type'] ) {
										$option_price = $value * $info['price'];
									}
								}
							} elseif ( 'addon_title' === $option_id ) {
								$info['label']  = '<span class="yith-wapo-group-title"> ' . $info['addon_label'] . '</span>';
								$cart_data_name = $info['addon_label'];
								$value          = '';
							} else {
								$cart_data_name = $info['label'];
							}

							if ( 'free' === $info['price_method'] ) {
								$option_price = 0;
							}

							$option_price = '' !== $option_price ? $option_price : 0;

							$option_price = yith_wapo_calculate_price_depending_on_tax( $option_price );

							$option_price = apply_filters( 'yith_wapo_addon_prices_on_cart', $option_price );

							if ( 'yes' === get_option( 'yith_wapo_show_options_in_cart' ) ) {
								if ( !$is_empty_select ) {
									if (!isset($cart_data_array[$cart_data_name])) {
										$cart_data_array[$cart_data_name] = '';
									}
									$cart_data_array[$cart_data_name] .= '<div>' . $value . ('' !== $option_price && floatval(0) !== floatval($option_price) ? ' (' . $sign . wc_price($option_price) . ')' : '') . '</div>';
								}
							}

							if ( ! apply_filters( 'yith_wapo_show_options_grouped_in_cart', true ) ) {
								if ( !$is_empty_select ) {
									$cart_data[] = array(
										'name' => $info['label'],
										'display' => empty($option_price) ? $value : '<div>' . $value . ('' !== $option_price && floatval(0) !== floatval($option_price) ? ' (' . $sign . wc_price($option_price) . ')' : '') . '</div>',
									);
								}
							}
						}
					}
				}
				if ( apply_filters( 'yith_wapo_show_options_grouped_in_cart', true ) ) {
					foreach ( $cart_data_array as $key => $value ) {
						$key = rtrim( $key, ':' );
						if ( '' === $key ) {
							$key = __( 'Option', 'yith-woocommerce-product-add-ons' );
						}
						$cart_data[] = array(
							'name'    => $key,
							'display' => $value,
						);
					}
				}
			}

			return apply_filters( 'yith_wapo_cart_data', $cart_data, $cart_item );
		}

		/**
		 * Add order item meta
		 *
		 * @param int $item_id Item ID.
		 * @param array $cart_item Cart item.
		 * @param string $cart_item_key Cart item key.
		 */
		public function add_order_item_meta( $item_id, $cart_item, $cart_item_key ) {

			if ( is_object( $cart_item ) && property_exists( $cart_item, 'legacy_values' ) ) {
				$cart_item = $cart_item->legacy_values;
			}

			if ( isset( $cart_item['yith_wapo_options'] ) && ! isset( $cart_item['yith_wcp_child_component_data'] ) ) {

				foreach ( $cart_item['yith_wapo_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {
							$value   = stripslashes( $value );
							$explode = explode( '-', $key );
							if ( isset( $explode[1] ) ) {
								$addon_id  = $explode[0];
								$option_id = $explode[1];
							} else {
								$addon_id  = $key;
								$option_id = $value;
							}

							$info = yith_wapo_get_option_info( $addon_id, $option_id );
							if ( 'percentage' === $info['price_type'] ) {
								$_product = wc_get_product( $cart_item['product_id'] );
								// WooCommerce Measurement Price Calculator (compatibility).
								if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
									$product_price = $cart_item['pricing_item_meta_data']['_price'];
								} else {
									$product_price = floatval( $_product->get_price() );
								}
								$option_percentage      = floatval( $info['price'] );
								$option_percentage_sale = floatval( $info['price_sale'] );
								$option_price           = ( $product_price / 100 ) * $option_percentage;
								$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
							} elseif ( 'multiplied' === $info['price_type'] ) {
								$option_price      = $info['price'] * $value;
								$option_price_sale = $info['price_sale'] * $value;
							} elseif ( 'characters' === $info['price_type'] ) {
								$remove_spaces     = apply_filters( 'yith_wapo_remove_spaces', false );
								$value             = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
								$option_price      = $info['price'] * strlen( $value );
								$option_price_sale = $info['price_sale'] * strlen( $value );
							} else {
								$option_price      = $info['price'];
								$option_price_sale = $info['price_sale'];
							}

							$sign = 'decrease' === $info['price_method'] ? '-' : '+';

							$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;

							$name = ( ( isset( $info['addon_label'] ) && '' !== $info['addon_label'] ) ? $info['addon_label'] : '' );

							if ( in_array( $info['addon_type'], array(
								'checkbox',
								'color',
								'label',
								'radio',
								'select'
							), true ) ) {
								$value = rtrim( $info['label'], ':' );
							} elseif ( in_array( $info['addon_type'], array( 'product' ), true ) ) {
								$option_product_info = explode( '-', $value );
								$option_product_id   = $option_product_info[1];
								$option_product_qty  = $option_product_info[2];
								$option_product      = wc_get_product( $option_product_id );
								if ( $option_product && $option_product instanceof WC_Product ) {
									$value = $option_product->get_formatted_name();

									// Product prices.
									$product_price = $option_product->get_price();
									if ( 'product' === $info['price_method'] ) {
										$option_price = $product_price;
									} elseif ( 'discount' === $info['price_method'] ) {
										$option_discount_value = floatval( $info['price'] );
										if ( 'percentage' === $info['price_type'] ) {
											$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
										} else {
											$option_price = $product_price - $option_discount_value;
										}
									}

									// Stock.
									if ( $option_product->get_manage_stock() ) {
										$qty       = ( isset( $cart_item['quantity'] ) && $cart_item['quantity'] > 1 ) ? $cart_item['quantity'] : 1;
										$stock_qty = $option_product->get_stock_quantity() - $qty;
										wc_update_product_stock( $option_product, $stock_qty, 'set' );
										wc_delete_product_transients( $option_product );
									}
								}
							} elseif ( 'file' === $info['addon_type'] ) {
								$file_url = explode( '/', $value );
								$value    = '<a href="' . $value . '" target="_blank">' . end( $file_url ) . '</a>';
							} elseif ( 'addon_title' === $option_id ) {
								$name  = rtrim( $info['addon_label'] );
								$value = '<div></div>';
							} else {
								$name = rtrim( $info['label'], ':' );
							}

							if ( '' === $name ) {
								$name = apply_filters( 'yith_wapo_order_item_meta_name_default' , __( 'Option', 'yith-woocommerce-product-add-ons' ) ,$index, $item_id, $cart_item );
							}

							$option_price  = apply_filters( 'yith_wapo_addon_prices_on_cart', $option_price );
							$display_value = $value . ( '' !== $option_price && floatval( 0 ) !== floatval( $option_price ) ? ' (' . $sign . wc_price( $option_price ) . ')' : '' );

							wc_add_order_item_meta( $item_id, $name, $display_value );
						}
					}
				}
				wc_add_order_item_meta( $item_id, '_ywapo_meta_data', $cart_item['yith_wapo_options'] );
			}
		}

		/**
		 * Add to cart URL
		 *
		 * @param string $url URL.
		 *
		 * @return false|string|WP_Error
		 */
		public function add_to_cart_url( string $url = '' ) {
			global $product;
			$product_id = yit_get_base_product_id( $product );
			if ( yith_wapo_product_has_blocks( $product_id ) ) {
				return get_permalink( $product_id );
			}

			return $url;
		}

		/**
		 * Add to cart text
		 *
		 * @param string $text Text.
		 *
		 * @return false|mixed|string|void
		 */
		public function add_to_cart_text( string $text = '' ) {
			global $product, $post;
			if ( is_object( $product ) && ! is_single( $post ) && yith_wapo_product_has_blocks( $product->get_id() ) ) {
				return get_option( 'yith_wapo_select_options_label', esc_html__( 'Select options', 'yith-woocommerce-product-add-ons' ) );
			}

			return $text;
		}

		/**
		 * Filter price in cart for items included in a bundle (support for YITH WooCommerce Product Bundle).
		 *
		 * @param string $price Cart item price.
		 * @param float  $bundled_items_price Bundle items price.
		 * @param array  $cart_item Cart item.
		 *
		 * @return string
		 */
		public function ywcpb_woocommerce_cart_item_price( $price, $bundled_items_price, $cart_item ) {

			if ( isset( $cart_item['yith_wapo_options'] ) ) {

				$types_total_price = $this->get_total_add_ons_price( $cart_item );

				if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
					$bundled_items_price = 0;
				}

				$price = wc_price( $bundled_items_price + $types_total_price );

			}

			return $price;
		}

		/**
		 * Filter bundles item subtotal (support for YITH WooCommerce Product Bundles)
		 *
		 * @param string $subtotal Bundle item subtotal.
		 * @param array $cart_item Cart item.
		 * @param string $bundle_price Bundle price.
		 *
		 * @return mixed
		 */
		public function ywcpb_bundle_pip_bundled_items_subtotal( $subtotal, $cart_item, $bundle_price ) {

			if ( isset( $cart_item['yith_wapo_options'] ) ) {

				if ( method_exists( yith_wcpb_frontend(), 'format_product_subtotal' ) ) {
					$types_total_price = $this->get_total_add_ons_price( $cart_item );

					if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
						$bundle_price = 0;
					}

					$subtotal = yith_wcpb_frontend()->format_product_subtotal( $cart_item['data'], $bundle_price + $types_total_price );
				}
			}

			return $subtotal;
		}

		/**
		 * Get total price for add-ons
		 *
		 * @param array $cart_item Cart item.
		 *
		 * @return int
		 */
		public function get_total_add_ons_price( $cart_item ) {

			$product_id = isset( $cart_item['product_id'] ) ? $cart_item['product_id'] : null;

			$type_list         = $this->get_cart_wapo_options( $cart_item, 'all' );
			$types_total_price = $this->get_total_by_add_ons_list( $type_list, $cart_item );

			return $types_total_price;

		}

		/**
		 * Filter cart item and add add-ons options
		 *
		 * @param array $cart_item Cart item.
		 * @param string $type Option type.
		 *
		 * @return array
		 */
		public function get_cart_wapo_options( $cart_item, $type = 'all' ) {

			$cart_item_filtered = array();

			if ( isset( $cart_item['yith_wapo_options'] ) ) {

				if ( isset( $cart_item['yith_wapo_sold_individually'] ) ) {
					if ( $cart_item['yith_wapo_sold_individually'] ) {
						$type = 'sold_individually';
					} else {
						$type = 'simple';
					}
				}
				foreach ( $cart_item['yith_wapo_options'] as $key => $single_type_option ) {

					if ( 'all' === $type ) {
						$cart_item_filtered [ $key ] = $single_type_option;
					} elseif ( 'sold_individually' === $type && isset( $single_type_option['sold_individually'] ) && $single_type_option['sold_individually'] ) {
						$cart_item_filtered [ $key ] = $single_type_option;
					} elseif ( 'simple' === $type && ( ! isset( $single_type_option['sold_individually'] ) || ( isset( $single_type_option['sold_individually'] ) && ! $single_type_option['sold_individually'] ) ) ) {
						$cart_item_filtered[ $key ] = $single_type_option;
					}
				}
			}

			return $cart_item_filtered;
		}

		/**
		 * Get total price for add-ons list
		 *
		 * @param array $type_list Type list.
		 *
		 * @return int
		 */
		private function get_total_by_add_ons_list( $type_list, $cart_item ) {

			$option_price = 0;
			$total_price  = 0;

			$product_id = isset( $cart_item['product_id'] ) ? $cart_item['product_id'] : $cart_item['item_meta']['_product_id'][0];
			$_product = wc_get_product( $product_id );
			// WooCommerce Measurement Price Calculator (compatibility).
			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
				$product_price = $cart_item['pricing_item_meta_data']['_price'];
			} else {
				$product_price = yit_get_display_price( $_product );
			}
			foreach ( $type_list as $list ) {
				foreach ( $list as $key => $value ) {
					if ( $key && '' !== $value ) {
						$value   = stripslashes( $value );
						$explode = explode( '-', $key );
						if ( isset( $explode[1] ) ) {
							$addon_id  = $explode[0];
							$option_id = $explode[1];
						} else {
							$addon_id  = $key;
							$option_id = $value;
						}

						$info       = yith_wapo_get_option_info( $addon_id, $option_id );

						if ( 'percentage' === $info['price_type'] ) {

							$option_percentage      = floatval( $info['price'] );
							$option_percentage_sale = floatval( $info['price_sale'] );
							$option_price           = ( $product_price / 100 ) * $option_percentage;
							$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
						} elseif ( 'multiplied' === $info['price_type'] ) {
							$option_price      = $info['price'] * $value;
							$option_price_sale = $info['price_sale'] * $value;
						} elseif ( 'characters' === $info['price_type'] ) {
							$remove_spaces     = apply_filters( 'yith_wapo_remove_spaces', false );
							$value             = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
							$option_price      = $info['price'] * strlen( $value );
							$option_price_sale = $info['price_sale'] * strlen( $value );
						} else {
							$option_price      = $info['price'];
							$option_price_sale = $info['price_sale'];
						}

						if ( 'number' === $info['addon_type'] ) {
							if ( 'value_x_product' === $info['price_method'] ) {
								$option_price = $value * $product_price;
							} else {
								if ( 'multiplied' === $info['price_type'] ) {
									$option_price = $value * $info['price'];
								}
							}
						}

						$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;

						if ( in_array( $info['addon_type'], array( 'product' ), true ) ) {
							$option_product_info = explode( '-', $value );
							$option_product_id   = $option_product_info[1];
							$option_product      = wc_get_product( $option_product_id );

							// Product prices.
							$product_price = $option_product instanceof WC_Product ? $option_product->get_price() : 0;
							if ( 'product' === $info['price_method'] ) {
								$option_price = $product_price;
							} elseif ( 'discount' === $info['price_method'] ) {
								$option_discount_value = floatval( $info['price'] );
								if ( 'percentage' === $info['price_type'] ) {
									$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
								} else {
									$option_price = $product_price - $option_discount_value;
								}
							}
						}
						$option_price = apply_filters( 'yith_wapo_addon_prices_on_bundle_cart_item', $option_price );
					}
				}
				$total_price += (float) $option_price;
			}
			return apply_filters( 'yith_wapo_get_total_by_add_ons_list', $total_price, $type_list, $cart_item );
		}

		/**
		 * Return Order item subtotal
		 *
		 * @param string $product_sub_total Product subtotal.
		 * @param array  $item Order Item data.
		 * @param object $order WC Order object.
		 *
		 * @return string
		 */
		public function order_item_subtotal( $product_sub_total, $item, $order ) {

			if ( isset( $item['item_meta']['_ywapo_meta_data'] ) && isset( $item['item_meta']['_bundled_items'][0] ) ) {

				$type_list         = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'] );
				$types_total_price = $this->get_total_by_add_ons_list( $type_list, $item );

				$tax_display = $order->tax_display_cart;

				if ( 'excl' === $tax_display ) {
					$ex_tax_label      = $order->prices_include_tax ? 1 : 0;
					$product_sub_total = wc_price(
						$order->get_line_subtotal( $item ) + $types_total_price,
						array(
							'ex_tax_label' => $ex_tax_label,
							'currency'     => $order->get_order_currency(),
						)
					);
				} else {
					$product_sub_total = wc_price( $order->get_line_subtotal( $item, true ) + $types_total_price, array( 'currency' => $order->get_order_currency() ) );
				}

			}

			return $product_sub_total;
		}
	}
}

/**
 * Unique access to instance of YITH_WAPO_Cart class
 *
 * @return YITH_WAPO_Cart
 */
function YITH_WAPO_Cart() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_WAPO_Cart::get_instance();
}
