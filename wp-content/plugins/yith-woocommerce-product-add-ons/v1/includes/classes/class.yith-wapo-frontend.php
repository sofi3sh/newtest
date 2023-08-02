<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Frontend class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons Premium
 */

if ( ! defined( 'YITH_WAPO' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WAPO_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WAPO_Frontend {
		/**
		 * Plugin version
		 *
		 * @var string File version.
		 * @since 1.0.0
		 */
		public $version;

		/**
		 * Show label
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_show_label_type;

		/**
		 * Show description
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_show_description_type;

		/**
		 * Show Image Type
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_show_image_type;

		/**
		 * Show Description
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_show_description_option;

		/**
		 * Show Image
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_show_image_option;

		/**
		 * Icon description url
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_icon_description_option_url;

		/**
		 * Upload folder name
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_upload_folder_name;

		/**
		 * Upload allowed type
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_upload_allowed_type;

		/**
		 * Add to cart in loop text
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $option_loop_add_to_cart_text;

		/**
		 * Show product options.
		 *
		 * @var bool $show_product_options Show/hide product options.
		 * @since 1.0.0
		 */
		public $show_product_options = false;

		/**
		 * Constructor.
		 *
		 * @param string $version File version.
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct( $version ) {

			global $woocommerce;

			$this->version = $version;

			// Body class.
			add_filter( 'body_class', array( $this, 'wapo_add_body_class' ) );

			// Settings.
			$this->option_show_label_type             = get_option( 'yith_wapo_settings_showlabeltype', 'yes' );
			$this->option_show_description_type       = get_option( 'yith_wapo_settings_showdescrtype', 'yes' );
			$this->option_show_image_type             = get_option( 'yith_wapo_settings_showimagetype', 'yes' );
			$this->option_show_description_option     = get_option( 'yith_wapo_settings_showdescropt', 'yes' );
			$this->option_show_image_option           = get_option( 'yith_wapo_settings_showimageopt', 'yes' );
			$this->option_icon_description_option_url = get_option( 'yith_wapo_settings_tooltip_icon', YITH_WAPO_URL . '/v1/assets/img/description-icon.png' );
			$this->option_upload_folder_name          = get_option( 'yith_wapo_settings_uploadfolder', 'yith_advanced_product_options' );

			$this->option_upload_allowed_type = get_option( 'yith_wapo_settings_filetypes', '' );
			if ( ! empty( $this->option_upload_allowed_type ) ) {
				$this->option_upload_allowed_type = explode( ',', $this->option_upload_allowed_type );
				if ( is_array( $this->option_upload_allowed_type ) ) {
					foreach ( $this->option_upload_allowed_type as &$extension ) {
						$extension = trim( $extension, ' ' );
					}
				}
			}

			$this->option_loop_add_to_cart_text = get_option( 'yith_wapo_settings_addtocartlabel', __( 'Select options', 'yith-woocommerce-product-add-ons' ) );

			$this->option_loop_add_to_cart_text = call_user_func( '__', $this->option_loop_add_to_cart_text, 'yith-woocommerce-product-add-ons' );

			// Enqueue Scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_inline_styles_scripts' ), 1000 );

			// Shortcodes.
			add_shortcode( 'yith_wapo_show_options', array( $this, 'yith_wapo_show_options_shortcode' ) );

			// YITH Quick View Support.
			add_action( 'yith_wcqv_product_summary', array( $this, 'check_variable_product' ) );

			// Show product addons.
			$form_position = get_option( 'yith_wapo_settings_formposition', 'before' );

			if ( 'yes' === get_option( 'yith_wapo_compatibility_woo_layout_injector', 'no' ) ) {
				add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'show_product_options' ) );
			} elseif ( 'yes' === get_option( 'yith_wapo_compatibility_7up_themes', 'no' ) ) {
				$priority = 'before' === $form_position ? 10 : 50;
				add_action( 's7upf_template_single_add_to_cart', array( $this, 'show_product_options' ), $priority );
			} elseif ( 'after' === $form_position ) {
				add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'show_product_options' ), 1 );
			} else {
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'show_product_options' ), 7 );
			}

			add_action( 'yith_wapo_show_options_shortcode', array( $this, 'show_product_options' ) );
			add_action( 'wc_quick_view_pro_quick_view_product_details', array( $this, 'show_product_options' ) );

			// Bundle.
			add_action( 'woocommerce_before_bundled_items', array( $this, 'check_variable_product' ) );

			// Flatsome Lightbox Support.
			add_action( 'woocommerce_before_single_product_lightbox_summary', array( $this, 'check_variable_product' ) );

			// Unero Support.
			add_action( 'unero_single_product_summary', array( $this, 'check_variable_product' ) );

			// TheGem Support.
			add_action( 'thegem_woocommerce_single_product_right', array( $this, 'check_variable_product' ) );

			// Gift card integration.
			add_action( 'yith_gift_cards_template_' . $form_position . '_add_to_cart_button', array( $this, 'show_product_options' ) );

			add_action( 'wc_ajax_yith_wapo_update_variation_price', array( $this, 'yith_wapo_update_variation_price' ) );

			add_action( 'wc_ajax_yith_wapo_get_calculated_display_price', array( $this, 'yith_wapo_get_calculated_display_price' ) );

			// YITH theme.
			add_action( 'add_to_cart_text', array( $this, 'add_to_cart_text' ), 99, 1 );

			/* CART */

			// Add item data to the cart.
			if ( 'yes' !== get_option( 'yith_wapo_settings_enable_loop_add_to_cart', false ) ) {
				add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 50, 1 );
				add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 1 );
				add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 6 );
			}
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );
			add_filter( 'woocommerce_cart_item_quantity', array( $this, 'woocommerce_cart_item_quantity' ), 10, 3 );

			// Add to cart.
			add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1 );

			// Get item data to display.
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );

			// Load cart data per page load.
			add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 100, 2 );

			// Add meta to order.
			if ( version_compare( $woocommerce->version, '3.0', '>=' ) ) {
				add_action( 'woocommerce_new_order_item', array( $this, 'order_item_meta' ), 10, 2 );
			} else {
				// Deprecated.
				add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 10, 2 );
			}

			// Email Order.
			add_filter( 'woocommerce_order_items_meta_get_formatted', array( $this, 'order_items_meta_display' ) );

			// order again functionality.
			add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 're_add_cart_item_data' ), 10, 3 );

			// Remove undo link.
			add_action( 'woocommerce_cart_item_restored', array( $this, 'cart_item_restored' ), 10, 2 );

			// Sold individually options actions.
			add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ), 10, 6 );
			add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'hide_remove_link' ), 10, 2 );
			add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'hide_thumbnail_name' ), 10, 2 );
			add_filter( 'woocommerce_cart_item_name', array( $this, 'hide_thumbnail_name' ), 10, 2 );
			add_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10, 2 );

			// Subscription Cart Price.

			add_filter( 'ywsbs_order_formatted_line_subtotal', array( $this, 'ywsbs_order_formatted_line_subtotal' ), 10, 4 );

			// Product Bundle.
			add_action( 'yith_wcpb_ajax_get_bundle_total_price', array( $this, 'ywcpb_print_calculated_price' ), 10, 2 );
			add_filter( 'yith_wcpb_woocommerce_cart_item_price', array( $this, 'ywcpb_woocommerce_cart_item_price' ), 10, 3 );
			add_filter( 'yith_wcpb_bundle_pip_bundled_items_subtotal', array( $this, 'ywcpb_bundle_pip_bundled_items_subtotal' ), 10, 3 );
			add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_subtotal' ), 10, 3 );
			add_filter( 'yith_wcpb_add_cart_item_data_check', array( $this, 'exclude_sold_individually' ), 10, 2 );

			// Composite Products.
			add_filter( 'yith_wcp_composite_add_child_items', array( $this, 'exclude_sold_individually' ), 10, 2 );
			add_filter( 'yith_wcp_calculate_subtotals', array( $this, 'exclude_sold_individually' ), 10, 2 );

			// YITH WAPO Loaded.
			do_action( 'yith_wapo_loaded' );

			// Frontend Manager.
			add_action( 'init', array( 'YITH_WAPO_Admin', 'add_wc_product_data_tab' ) );
			add_action( 'woocommerce_process_product_meta', array( 'YITH_WAPO_Admin', 'woo_add_custom_general_fields_save' ) );

		}

		/**
		 * Add body class.
		 *
		 * @param array $classes Body classes.
		 * @return array
		 * @since  1.0.0
		 */
		public function wapo_add_body_class( $classes ) {
			return array_merge( $classes, array( 'yith-wapo-frontend' ) );
		}

		/**
		 * Show Options Shortcode.
		 *
		 * @param array $atts Shortcode attributes [yith_wapo_show_options].
		 * @return array
		 * @since  1.3.3
		 */
		public function yith_wapo_show_options_shortcode( $atts ) {
			ob_start();

			if ( function_exists( 'is_product' ) && is_product() ) {
				do_action( 'yith_wapo_show_options_shortcode' );
			} else {
				echo wp_kses_post( '<strong>' . __( 'This is not a product page!', 'yith-woocommerce-product-add-ons' ) . '</strong>' );
			}

			return ob_get_clean();

		}

		/**
		 * Enqueue frontend styles and scripts.
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function enqueue_styles_scripts() {

			if ( ( function_exists( 'is_product' ) && is_product() )
				|| ( apply_filters( 'yith_wapo_force_enqueue_styes_and_scripts', false ) )
				|| ( apply_filters( 'yith_wapo_force_enqueue_styles_and_scripts', false ) ) ) {

				$suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

				// css.

				if ( ! apply_filters( 'yith_wapo_disable_jqueryui', false ) ) {
					wp_register_style( 'jquery-ui', YITH_WAPO_URL . '/v1/assets/css/jquery-ui.min.css', false, '1.11.4' );
					wp_enqueue_style( 'jquery-ui' );
				}
				if ( ! apply_filters( 'yith_wapo_disable_colorpicker', false ) ) {
					wp_register_style( 'yith_wapo_frontend-colorpicker', YITH_WAPO_URL . '/v1/assets/css/color-picker' . $suffix . '.css', array( 'yith_wapo_frontend' ), $this->version );
					wp_enqueue_style( 'yith_wapo_frontend-colorpicker' );
				}

				wp_register_style( 'yith_wapo_frontend', YITH_WAPO_URL . '/v1/assets/css/yith-wapo.css', false, $this->version );
				wp_enqueue_style( 'dashicons' );
				wp_enqueue_style( 'yith_wapo_frontend' );

				// js.

				wp_enqueue_script( 'jquery' );

				if ( ! apply_filters( 'yith_wapo_disable_jqueryui', false ) ) {
					wp_register_script( 'yith_wapo_frontend-jquery-ui', YITH_WAPO_URL . '/v1/assets/js/jquery-ui/jquery-ui' . $suffix . '.js', '', '1.11.4', true );
					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'yith_wapo_frontend-jquery-ui' );
				}
				if ( ! apply_filters( 'yith_wapo_disable_colorpicker', false ) ) {
					wp_register_script( 'yith_wapo_frontend-colorpicker', YITH_WAPO_URL . '/v1/assets/js/color-picker' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
					wp_enqueue_script( 'yith_wapo_frontend-colorpicker' );
				}

				wp_register_script( 'yith_wapo_frontend-accounting', YITH_WAPO_URL . '/v1/assets/js/accounting' . $suffix . '.js', '', '0.4.2', true );
				wp_register_script( 'yith_wapo_frontend-iris', YITH_WAPO_URL . '/v1/assets/js/iris.min.js', array( 'jquery' ), '1.0.0', true );
				wp_register_script( 'yith_wapo_frontend', YITH_WAPO_URL . '/v1/assets/js/yith-wapo-frontend.js', array( 'jquery', 'wc-add-to-cart-variation' ), $this->version, true );

				wp_enqueue_script( 'yith_wapo_frontend-accounting' );
				wp_enqueue_script( 'yith_wapo_frontend-iris' );
				wp_enqueue_script( 'wc-add-to-cart-variation' );
				wp_enqueue_script( 'yith_wapo_frontend' );

				global $post;
				$post_id                  = is_object( $post ) ? $post->ID : 0;
				$ywctm_check_price_hidden = apply_filters( 'ywctm_check_price_hidden', false, $post_id );

				$script_params = array(
					'ajax_url'                     => admin_url( 'admin-ajax' ) . '.php',
					'wc_ajax_url'                  => WC_AJAX::get_endpoint( '%%endpoint%%' ),
					'tooltip'                      => true, // deprecated.
					'tooltip_pos'                  => get_option( 'yith-wapo-tooltip-position' ),
					'tooltip_ani'                  => get_option( 'yith-wapo-tooltip-animation' ),
					'currency_format_num_decimals' => absint( get_option( 'woocommerce_price_num_decimals' ) ),
					'currency_format_symbol'       => get_woocommerce_currency_symbol(),
					'currency_format_decimal_sep'  => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
					'currency_format_thousand_sep' => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
					'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
					'do_submit'                    => true,
					'date_format'                  => get_option( 'yith_wapo_settings_date_format', 'mm/dd/yy' ),
					'keep_price_shown'             => ( 'yes' === get_option( 'yith_wapo_settings_show_add_ons_price_table', false ) ) && ! $ywctm_check_price_hidden,
					'alternative_replace_image'    => get_option( 'yith_wapo_settings_alternative_replace_image', false ),
				);

				wp_localize_script( 'yith_wapo_frontend', 'yith_wapo_general', $script_params );

				$color_picker_param = array(
					'clear'         => __( 'Clear', 'yith-woocommerce-product-add-ons' ),
					'defaultString' => __( 'Default', 'yith-woocommerce-product-add-ons' ),
					'pick'          => __( 'Select color', 'yith-woocommerce-product-add-ons' ),
					'current'       => __( 'Current color', 'yith-woocommerce-product-add-ons' ),
				);

				wp_localize_script( 'yith_wapo_frontend', 'wpColorPickerL10n', $color_picker_param );

				$color      = get_option( 'yith-wapo-tooltip-text-color' );
				$background = get_option( 'yith-wapo-tooltip-background' );

				$inline_css = "
				.wapo_option_tooltip .yith_wccl_tooltip > span {
					background: {$background};
					color: {$color};
				}
				.wapo_option_tooltip .yith_wccl_tooltip.bottom span:after {
					border-bottom-color: {$background};
				}
				.wapo_option_tooltip .yith_wccl_tooltip.top span:after {
					border-top-color: {$background};
				}";

				wp_add_inline_style( 'yith_wapo_frontend', $inline_css );

			}

		}

		/**
		 * Enqueue frontend styles and scripts.
		 *
		 * @access public
		 * @return void
		 * @since  1.5.24
		 */
		public function enqueue_inline_styles_scripts() {
			$css    = sprintf( ".ywapo_miss_required::before {content: '%s';}", esc_html__( 'Error: Wrong selection!', 'yith-woocommerce-product-add-ons' ) );
			$handle = 'yith_wapo_frontend';

			wp_add_inline_style( $handle, $css );
		}

		/**
		 * Show the product advanced options.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function show_product_options() {

			global $product;

			if ( function_exists( 'is_product' ) && is_product() && is_object( $product ) && ! $this->show_product_options ) {

				$product_id = yit_get_base_product_id( $product );

				if ( $product_id > 0 ) {

					$product_type_list = YITH_WAPO::getAllowedProductTypes();

					if ( in_array( $product->get_type(), $product_type_list, true ) && apply_filters( 'yith_wapo_show_group_container', true ) ) {

						$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

						wc_get_template(
							'yith-wapo-group-container.php',
							array(
								'yith_wapo_frontend' => $this,
								'product'            => $product,
								'types_list'         => $types_list,
							),
							'',
							YITH_WAPO_DIR . 'v1/templates/frontend/'
						);

						$this->show_product_options = true;

					}
				}
			}

		}

		/**
		 * Add add-ons form to product on frontend.
		 */
		public function check_variable_product() {
			global $product;
			$form_position = get_option( 'yith_wapo_settings_formposition', 'before' );
			if ( $product->is_type( 'variable' ) ) {
				$priority = 'after' === $form_position ? 25 : 15;
				add_action( 'woocommerce_single_variation', array( $this, 'show_product_options' ), $priority );
			} else {
				add_action( 'woocommerce_' . $form_position . '_add_to_cart_button', array( $this, 'show_product_options' ), 20 );
			}
		}

		/**
		 * Print the single product options group.
		 *
		 * @param object $product WC product.
		 * @param mixed  $single_type Add-on type.
		 * @access private
		 * @since  1.0.0
		 */
		public function print_single_group_type( $product, $single_type ) {

			$single_type = (array) $single_type;

			// --- WPML --- .
			if ( YITH_WAPO::$is_wpml_installed ) {

				$single_type['label']       = YITH_WAPO_WPML::string_translate( $single_type['label'] );
				$single_type['description'] = YITH_WAPO_WPML::string_translate( $single_type['description'] );

			}
			// ---END WPML--------- .

			wc_get_template(
				'yith-wapo-group-type.php',
				array(
					'yith_wapo_frontend' => $this,
					'product'            => $product,
					'single_type'        => $single_type,
				),
				'',
				YITH_WAPO_DIR . 'v1/templates/frontend/'
			);

		}

		/**
		 * Print add-ons options
		 *
		 * @param string $key Option key.
		 * @param object $product WC product.
		 * @param string $type_id Type ID.
		 * @param string $type Option type.
		 * @param string $name Option name.
		 * @param string $value Option value.
		 * @param float  $price Option price.
		 * @param string $label Option label.
		 * @param string $image Option image.
		 * @param string $image_alt Alt attribute for image.
		 * @param string $price_type Price type.
		 * @param string $description Option descritpion.
		 * @param string $placeholder Option placeholder.
		 * @param string $tooltip Option tooltip.
		 * @param bool   $required Option is required or not.
		 * @param bool   $checked Option is checked or not.
		 * @param bool   $hidelabel Show/Hide label.
		 * @param bool   $hideoption Show/hide option.
		 * @param string $disabled Option is disabled or not.
		 * @param string $label_position Label position.
		 * @param bool   $min Show/hide attribute "min" for the option.
		 * @param bool   $max Show/hide attribute "max" for the option.
		 */
		public function print_options(
			$key,
			$product,
			$type_id,
			$type,
			$name,
			$value,
			$price,
			$label = '',
			$image = '',
			$image_alt = '',
			$price_type = 'fixed',
			$description = '',
			$placeholder = '',
			$tooltip = '',
			$required = false,
			$checked = false,
			$hidelabel = false,
			$hideoption = false,
			$disabled = '',
			$label_position = 'before',
			$min = false,
			$max = false
		) {

			// arg type exception.
			if ( 'text' === $type || 'number' === $type || 'range' === $type || 'textarea' === $type || 'color' === $type || 'date' === $type ) {
				$value = '';
			}
			if ( 'radio' === $type || 'checkbox' === $type ) {
				$label_position = 'after';
			}

			// Catalog Mode.
			$product_id = yit_get_base_product_id( $product );
			if ( $this->hide_price( $product_id ) ) {
				$price = 0;
			}

			$price = apply_filters( 'wapo_print_option_price', $price, $product );

			// WooCommerce Multi-currency (woocommerce-multicurrency).
			if ( class_exists( 'WOOMC\API' ) ) {
				$price = WOOMC\API::convert( $price, \WOOMC\API::default_currency(), get_woocommerce_currency() );

				// Multi Currency for WooCommerce (woo-multi-currency).
			} elseif ( class_exists( 'WOOMULTI_CURRENCY_F_Data' ) ) {
				$woo_settings         = WOOMULTI_CURRENCY_F_Data::get_ins();
				$woo_current_currency = $woo_settings->get_current_currency();
				$woo_list_currencies  = $woo_settings->get_list_currencies();
				$woo_currency_rate    = $woo_list_currencies[ $woo_current_currency ]['rate'];
				$price                = str_replace( ',', '.', $price ) * $woo_currency_rate;
			}

			// Price check.
			if ( empty( $price ) ) {
				$price = 0; } elseif ( false !== strpos( $price, ',' ) ) {
				$price = floatval( str_replace( ',', '.', str_replace( '.', '', $price ) ) );
				}

				$price_html  = '';
				$use_display = $price < 0 ? false : true;

				$price_calculated = $this->get_display_price( $product, $price, $price_type, $use_display );

				/**
				 * Use the filter 'yith_wapo_allow_frontend_free_price' to display "+$0.00" label.
				 */
				if ( ! empty( $price ) || apply_filters( 'yith_wapo_allow_frontend_free_price', false ) ) {

					$simple_price     = $price_calculated ? $price_calculated : $price;
					$price_calculated = yith_wapo_currency_check( $price_calculated );
					$formatted_price  = wc_price( abs( $simple_price ) );
					$price_sign       = $simple_price < 0 ? apply_filters( 'yith_wapo_option_price_minus_sign', '-' ) : apply_filters( 'yith_wapo_option_price_plus_sign', '+' );
					$price_html       = ' <span class="ywapo_label_price"><span class="ywapo_price_sign">' . $price_sign . '</span> ' . $formatted_price . '</span>';

					$price_html = apply_filters( 'yith_wapo_option_price_html', $price_html, $simple_price, $price_sign );

					if ( get_option( 'woocommerce_price_display_suffix' ) ) {
						$price_suffix        = get_option( 'woocommerce_price_display_suffix' );
						$price_including_tax = wc_get_price_including_tax( $product, array( 'price' => abs( $price ) ) );
						$price_excluding_tax = wc_get_price_excluding_tax( $product, array( 'price' => abs( $price ) ) );
						$price_suffix        = str_replace( '{price_including_tax}', wc_price( $price_including_tax ), $price_suffix );
						$price_suffix        = str_replace( '{price_excluding_tax}', wc_price( $price_excluding_tax ), $price_suffix );
						$price_html         .= ' <small>' . $price_suffix . '</small>';
					}

					$price_html = apply_filters( 'yith_wapo_frontend_price_html', $price_html, $type_id );
				}

				if ( apply_filters( 'yith_wapo_force_display_price_without_tax', false ) ) {
					$price_html = wc_price( abs( $price ) );
				}

				$image_html = '';
				if ( $image ) {
					global $wpdb;
					$attachment    = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s;", $image ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$attachment_id = $attachment[0] ?? 0;

					if ( $attachment_id && apply_filters( 'yith_wapo_get_thumbnail_for_addons_image', true ) ) {
						$thumbnail  = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
						$fullsize   = wp_get_attachment_image_src( $attachment_id, 'full' );
						$image_html = '<img src="' . $thumbnail[0] . '" fullsize="' . $fullsize[0] . '" attachment_id="' . $attachment_id . '"  alt="' . $image_alt . '" class="ywapo_single_option_image">';
					} else {
						$image_html = '<img src="' . $image . '" fullsize="' . $image . '"  alt="' . $image_alt . '" class="ywapo_single_option_image">';
					}
				}

				$control_id = 'ywapo_ctrl_id_' . $type_id . '_' . $key;

				$required_simbol = $required && ( 'select' !== $type && 'labels' !== $type && 'multiple_labels' !== $type && 'radio' !== $type ) ? '<abbr class="required" title="' . __( 'Required', 'yith-woocommerce-product-add-ons' ) . '">(*)</abbr>' : '';
				$span_label      = sprintf(
					'<label for="%s" class="ywapo_label ' . ( $image_html ? 'with_image' : '' ) . ' ywapo_label_tag_position_%s">%s<span class="ywapo_option_label ywapo_label_position_%s">%s</span> %s</label>',
					$control_id,
					$label_position,
					$image_html,
					$label_position,
					$hidelabel ? '' : $label,
					$required_simbol
				);
			$before_label        = 'before' === $label_position ? $span_label : '';
			$after_label         = 'after' === $label_position ? $span_label : '';

			$min_html = false !== $min && is_numeric( $min ) ? 'min="' . esc_attr( $min ) . '"' : '';
			$max_html = false !== $max && is_numeric( $max ) ? 'max="' . esc_attr( $max ) . '"' : '';

			$max_length = false !== $max && is_numeric( $max ) ? 'maxlength=' . esc_attr( $max ) . '' : '';

			$step = '';

			$default_args = apply_filters(
				'yith_wapo_print_options_default_args',
				array(
					'yith_wapo_frontend' => $this,
					'control_id'         => $control_id,
					'product'            => $product,
					'key'                => $key,
					'type_id'            => $type_id,
					'type'               => $type,
					'name'               => $name,
					'value'              => $value,
					'price'              => $price,
					'price_html'         => $price_html,
					'price_type'         => $price_type,
					'price_calculated'   => $price_calculated,
					'label'              => $label,
					'span_label'         => $span_label,
					'before_label'       => $before_label,
					'after_label'        => $after_label,
					'description'        => $description,
					'placeholder'        => $placeholder,
					'tooltip'            => $tooltip,
					'required'           => $required,
					'checked'            => $checked,
					'hidelabel'          => $hidelabel,
					'hideoption'         => $hideoption,
					'disabled'           => $disabled,
					'label_position'     => $label_position,
					'min'                => $min,
					'max'                => $max,
					'min_html'           => $min_html,
					'max_html'           => $max_html,
					'max_length'         => $max_length,
					'image_url'          => esc_attr( $image ),
					'step'               => $step,
				)
			);

			if ( ! $hideoption ) {

				switch ( $type ) {

					case 'textarea':
						wc_get_template( 'yith-wapo-input-textarea.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

						break;

					case 'select':
						wc_get_template( 'yith-wapo-input-select.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

						break;

					case 'labels':
						wc_get_template( 'yith-wapo-input-labels.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

						break;

					case 'multiple_labels':
						wc_get_template( 'yith-wapo-input-multiple-labels.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

						break;

					case 'color':
						wc_get_template( 'yith-wapo-input-color.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

						break;

					default:
						wc_get_template( 'yith-wapo-input-base.php', $default_args, '', YITH_WAPO_DIR . 'v1/templates/frontend/' );

				}
			}

		}


		/**
		 * Update variation price on add-on selection.
		 */
		public function yith_wapo_update_variation_price() {

			if ( ! isset( $_REQUEST['variation_id'] ) || ! isset( $_REQUEST['variation_price'] ) || ! isset( $_REQUEST['type_id'] ) || ! isset( $_REQUEST['option_index'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_die();
			}

			$variation_id = intval( $_REQUEST['variation_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( $variation_id > 0 ) {

				$variation = new WC_Product_Variation( $variation_id );

				if ( is_object( $variation ) ) {

					$product_id = yit_get_base_product_id( $variation );

					$product = wc_get_product( $product_id );

					if ( is_object( $product ) ) {

						$type_id = intval( $_REQUEST['type_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

						if ( $type_id > 0 ) {

							$single_group_type = YITH_WAPO_Type::getSingleGroupType( $type_id );

							if ( is_array( $single_group_type ) ) {
								$single_group_type = $single_group_type[0];
							}

							if ( is_object( $single_group_type ) ) {

								$option_index = $_REQUEST['option_index'] ?? ''; // phpcs:ignore

								if ( $option_index >= 0 ) {

									$options = $single_group_type->options;
									$options = maybe_unserialize( $options );

									if ( is_array( $options ) ) {

										$price            = $options['price'][ $option_index ];
										$price_type       = $options['type'][ $option_index ];
										$value            = $_REQUEST['option_value'] ?? null; // phpcs:ignore
										$price_calculated = $this->get_display_price( $product, $price, $price_type, true, $variation, null, $value );

										echo wp_kses_post( $price_calculated );

									}
								}
							}
						}
					}
				}
			}

			wp_die();

		}

		/**
		 * Ajax call: get calculated displya price.
		 */
		public function yith_wapo_get_calculated_display_price() {

			if ( ! isset( $_REQUEST['product_id'] ) || ! isset( $_REQUEST['product_price'] ) || ! isset( $_REQUEST['type_id'] ) || ! isset( $_REQUEST['option_index'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_die();
			}

			$product_id = intval( $_REQUEST['product_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( $product_id > 0 ) {

				$type_id = intval( $_REQUEST['type_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				$product = wc_get_product( $product_id );

				$product_price = floatval( $_REQUEST['product_price'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( is_object( $product ) && $type_id > 0 && $product_price > 0 ) {

					$single_group_type = YITH_WAPO_Type::getSingleGroupType( $type_id );

					if ( is_array( $single_group_type ) ) {
						$single_group_type = $single_group_type[0];
					}

					if ( is_object( $single_group_type ) ) {

						$option_index = $_REQUEST['option_index'] ?? ''; // phpcs:ignore

						if ( $option_index >= 0 ) {

							$options = $single_group_type->options;
							$options = maybe_unserialize( $options );

							if ( is_array( $options ) ) {

								$price            = $options['price'][ $option_index ];
								$price_type       = $options['type'][ $option_index ];
								$value            = $_REQUEST['option_value'] ?? null; // phpcs:ignore
								$price_calculated = $this->get_display_price( $product, $price, $price_type, true, null, $product_price, $value );

								echo wp_kses_post( $price_calculated );

							}
						}
					}
				}
			}

			wp_die();

		}

		/**
		 * Get HTML for tooltip
		 *
		 * @param string $description Description.
		 *
		 * @return string
		 */
		public function get_tooltip( $description ) {

			$tooltip = '';

			if ( $description ) {

				$icon_url = ! empty( $this->option_icon_description_option_url ) ? $this->option_icon_description_option_url : YITH_WAPO_URL . '/v1/assets/img/description-icon.png';

				$tooltip = '<div class="wapo_option_tooltip" data-tooltip="' . esc_attr( $description ) . '">';

				$tooltip .= '<span><img src="' . esc_url( $icon_url ) . '" alt=""></span>';

				$tooltip .= '</div>';

			}

			return $tooltip;
		}

		/**
		 * Filter add to cart text.
		 *
		 * @param string $text Add to cart text.
		 *
		 * @return string|void
		 */
		public function add_to_cart_text( $text = '' ) {

			global $product, $post;

			if ( is_object( $product ) && ! is_single( $post ) ) {

				$product_type_list = YITH_WAPO::getAllowedProductTypes();

				if ( in_array( $product->get_type(), $product_type_list, true ) ) {

					$product_id = yit_get_base_product_id( $product );

					$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

					if ( ! empty( $types_list ) ) {
						$text = ! empty( $this->option_loop_add_to_cart_text ) ? $this->option_loop_add_to_cart_text : __( 'Select options', 'yith-woocommerce-product-add-ons' );
					}
				}
			}

			return $text;
		}

		/**
		 * Filter add to cart url
		 *
		 * @param string $url Add to cart url.
		 *
		 * @return false|string
		 */
		public function add_to_cart_url( $url = '' ) {

			global $product;

			if ( is_object( $product ) && ( ( is_shop() || is_product_category() || is_product_tag() ) ) ) {

				$product_type_list = YITH_WAPO::getAllowedProductTypes();

				if ( in_array( $product->get_type(), $product_type_list, true ) ) {

					$product_id = yit_get_base_product_id( $product );

					$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

					if ( ! empty( $types_list ) ) {
						$url = get_permalink( $product_id );
					}
				}
			}

			return $url;
		}


		/**
		 * Filter add to cart validation.
		 *
		 * @param bool  $passed Validate check.
		 * @param int   $product_id Product ID.
		 * @param int   $qty Product quantity.
		 * @param int   $variation_id Product Variation ID.
		 * @param array $variations Variations.
		 * @param array $cart_item_data Cart item data.
		 *
		 * @return bool
		 */
		public function add_to_cart_validation( $passed, $product_id, $qty, $variation_id = '', $variations = array(), $cart_item_data = array() ) {

			$order_id = WC()->session->order_awaiting_payment;
			$order    = wc_get_order( $order_id );
			if ( 'yes' === yit_get_prop( $order, 'ywraq_raq', true ) && 'no' === get_option( 'ywraq_allow_add_to_cart', 'no' ) ) {
				return false;
			}

			// Disable add_to_cart_button class on shop page.
			if ( wp_doing_ajax() && ! isset( $_REQUEST['yith_wapo_is_single'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				$product           = wc_get_product( $product_id );
				$product_type_list = YITH_WAPO::getAllowedProductTypes();

				if ( in_array( $product->get_type(), $product_type_list, true ) ) {
					$product_parent_id = yit_get_base_product_id( $product );
					$types_list        = YITH_WAPO_Type::getAllowedGroupTypes( $product_parent_id );
					if ( ! empty( $types_list ) ) {
						return false;
					}
				}
			}

			// Files.
			if ( ! empty( $_FILES ) ) {
				$upload_data = array();
				foreach ( $_FILES as $group_key => $group_values ) {
					if ( is_array( $group_values ) ) {
						foreach ( $group_values as $prop_key => $prop_values ) {
							if ( is_array( $prop_values ) ) {
								foreach ( $prop_values as $field_key => $field_value ) {
									$upload_data[ $group_key ][ $field_key ][ $prop_key ] = $field_value;
								}
							}
						}
					}
				}
				foreach ( $upload_data as $single_data ) {
					$passed = YITH_WAPO_Type::checkUploadedFilesError( $this, $single_data );
					if ( ! $passed ) {
						break;
					}
				}
			}

			return $passed;
		}


		/**
		 * Filter cart item data
		 *
		 * @param array $cart_item_meta Cart item meta data.
		 * @param int   $product_id Prodct ID.
		 * @param null  $post_data Post data.
		 * @param bool  $sold_individually Sold individually check.
		 * @param int   $cart_item_key_parent Cart parent item key.
		 * @return mixed|void
		 * @throws Exception Exception.
		 */
		public function add_cart_item_data( $cart_item_meta, $product_id, $post_data = null, $sold_individually = false, $cart_item_key_parent = 0 ) {

			// Others Plugin Exceptions.
			if ( $cart_item_meta instanceof YWGC_Gift_Card ) {
				return;
			}

			if ( isset( $cart_item_meta['bundled_by'] ) || isset( $cart_item_meta['yith_wcp_child_component_data'] ) ) {
				return $cart_item_meta;
			}

			if ( is_null( $post_data ) ) {
				$post_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			}

			$upload_data = array();
			if ( ! empty( $_FILES ) ) {
				$upload_data = $_FILES;
			}

			$type_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id, null, $sold_individually );

			if ( empty( $cart_item_meta['yith_wapo_options'] ) ) {
				$cart_item_meta['yith_wapo_options'] = array();
			}
			if ( is_array( $type_list ) && ! empty( $type_list ) ) {

				$product = wc_get_product( $product_id );

				$variation = isset( $post_data['variation_id'] ) ? new WC_Product_Variation( $post_data['variation_id'] ) : null;

				foreach ( $type_list as $single_type ) {

					$post_name = 'ywapo_' . $single_type->type . '_' . $single_type->id;

					$value = isset( $post_data[ $post_name ] ) ? $post_data[ $post_name ] : '';

					$upload_value = isset( $upload_data[ $post_name ] ) ? $upload_data[ $post_name ] : '';

					if ( '' === $value && empty( $upload_value ) ) {
						continue;
					} elseif ( is_array( $value ) ) {
						$value = array_map( 'stripslashes', $value );
					} else {
						$value = stripslashes( $value );
					}

					$data = YITH_WAPO_Type::getCartDataByPostValue( $this, $product, $variation, $single_type, $value, $upload_value );

					if ( is_wp_error( $data ) ) {
						// Throw exception for add_to_cart to pickup.
						throw new Exception( $data->get_error_message() );
					} elseif ( $data ) {
						$cart_item_meta['yith_wapo_options'] = array_merge( $cart_item_meta['yith_wapo_options'], apply_filters( 'yith_wapo_cart_item_data', $data, $single_type, $product_id, $post_data ) );
					}
				}

				if ( ! isset( $cart_item_meta['yith_wapo_sold_individually'] ) ) {
					$cart_item_meta['yith_wapo_sold_individually'] = $sold_individually;
				}

				if ( 0 !== $cart_item_key_parent && ! empty( $cart_item_key_parent ) ) {
					$cart_item_meta['yith_wapo_cart_item_key_parent'] = $cart_item_key_parent;
				}
			}

			return $cart_item_meta;
		}

		/**
		 * Filter cart item qauntity.
		 *
		 * @param int    $product_quantity Product quantity.
		 * @param string $cart_item_key Cart item key.
		 * @param array  $cart_item Cart item.
		 *
		 * @return string
		 */
		public function woocommerce_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item = null ) {

			if ( isset( $cart_item['yith_wapo_options'] ) ) {

				$add_ons = $cart_item['yith_wapo_options'];

				foreach ( $add_ons as $add_on ) {

					if ( isset( $add_on['calculate_quantity_sum'] ) && $add_on['calculate_quantity_sum'] ) {
						if ( $add_on['value'] > 0 ) {
							echo woocommerce_quantity_input( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $cart_item['quantity'],
									'min_value'   => $cart_item['quantity'],
								),
								$cart_item['data'],
								false
							);
						}
					}
				}
			}

			return $product_quantity;
		}

		/**
		 * Re-add addons the customer order again the same product from My Account.
		 *
		 * @param array  $cart_item_data Cart item data.
		 * @param array  $item $item to add to the cart.
		 * @param object $order WC Order.
		 *
		 * @return mixed
		 */
		public function re_add_cart_item_data( $cart_item_data, $item, $order ) {

			// Disable validation.
			remove_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 6 );

			$stored_meta_data = null;

			$extra_info = isset( $item['item_meta']['_ywapo_extra_info'][0] ) ? maybe_unserialize( $item['item_meta']['_ywapo_extra_info'][0] ) : array();

			if ( isset( $item['item_meta']['_ywapo_meta_data'] ) ) {

				$stored_meta_data = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'] );

			} elseif ( isset( $item['item_meta']['_ywapo_meta_data'][0] ) ) {

				$stored_meta_data = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'][0] );

			} elseif ( isset( $item['item_meta']['_ywraq_wc_ywapo'] ) ) { // order by request a quote.

				$stored_meta_data = maybe_unserialize( $item['item_meta']['_ywraq_wc_ywapo'] );

			}

			$cart_item_meta = array();

			if ( isset( $stored_meta_data ) ) {

				foreach ( $stored_meta_data as $key => $single_data ) {

					if ( isset( $single_data['type_id'] ) ) {

						$type_object = new YITH_WAPO_Type( $single_data['type_id'] );

						if ( is_object( $type_object ) ) {

							$product_id = isset( $item['product_id'] ) ? $item['product_id'] : $item['item_meta']['_product_id'][0];
							$product    = wc_get_product( $product_id );

							$variation = isset( $item['item_meta']['_variation_id'][0] ) && $item['item_meta']['_variation_id'][0] > 0 ? new WC_Product_Variation( $item['item_meta']['_variation_id'][0] ) : null;

							$new_single_data = YITH_WAPO_Type::getCartDataByPostValue( $this, $product, $variation, $type_object, $single_data['original_value'], array() );

							$index = isset( $single_data['original_index'] ) ? $single_data['original_index'] : 0;
							if ( isset( $new_single_data[ $index ] ) ) {
								$new_single_data = $new_single_data[ $index ];
							}

							if ( empty( $single_data ) || ! isset( $new_single_data['name'] ) || ( $new_single_data['name'] !== $single_data['name'] ) ) {
								unset( $single_data[ $key ] );
							} else {
								$stored_meta_data[ $key ] = $new_single_data;
							}
						}
					}
				}

				$cart_item_meta['yith_wapo_options'] = apply_filters( 'yith_wapo_re_add_cart_item_data', $stored_meta_data, $item );

				$cart_item_meta['yith_wapo_sold_individually'] = isset( $extra_info['yith_wapo_sold_individually'] ) && $extra_info['yith_wapo_sold_individually'];

			}

			return $cart_item_meta;
		}


		/**
		 * Filter Item before add to cart.
		 *
		 * @param array $cart_item Cart item.
		 * @return mixed
		 */
		public function add_cart_item( $cart_item ) {

			if ( isset( $cart_item['yith_wcp_child_component_data'] ) ) {
				return $cart_item;
			}

			// Adjust price if addons are set.
			$this->cart_adjust_price( $cart_item );

			return $cart_item;
		}

		/**
		 * Filter cart item data to display.
		 *
		 * @param array $other_data Item data to display.
		 * @param array $cart_item Cart item.
		 *
		 * @return array
		 */
		public function get_item_data( $other_data, $cart_item ) {

			if ( ! empty( $cart_item['yith_wapo_options'] ) ) {

				$product_parent_id = yit_get_base_product_id( $cart_item['data'] );

				if ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ) {
					$base_product = new WC_Product_Variation( $cart_item['variation_id'] );
				} else {
					$base_product = wc_get_product( $product_parent_id );
				}

				if ( ( is_object( $base_product ) && 'yes' === get_option( 'yith_wapo_settings_show_product_price_cart' ) ) && ( isset( $cart_item['yith_wapo_sold_individually'] ) && ! $cart_item['yith_wapo_sold_individually'] ) ) {

					if ( 'yith_bundle' === $base_product->get_type() && true === $base_product->per_items_pricing && function_exists( 'YITH_WCPB_Frontend_Premium' ) && method_exists( yith_wcpb_frontend(), 'format_product_subtotal' ) ) {
						$price = yith_wcpb_frontend()->calculate_bundled_items_price_by_cart( $cart_item );
					} else {
						$price = yit_get_display_price( $base_product );
					}

					$price_html = wc_price( $price );

					$other_data[] = array(
						'name'  => __( 'Base price', 'yith-woocommerce-product-add-ons' ),
						'value' => $price_html,
					);

				}
				$type_list = $this->get_cart_wapo_options( $cart_item, 'all' );
				foreach ( $type_list as $single_type_options ) {

					if ( apply_filters( 'yith_wapo_get_item_data', true, $single_type_options ) && isset( $single_type_options['name'] ) && $single_type_options['value'] ) {

						$single_type_options['price'] = yith_wapo_currency_check( $single_type_options['price'] );

						$name  = $single_type_options['name'];
						$value = $single_type_options['value'];
						$price = '' !== $single_type_options['price'] ? $single_type_options['price'] : 0;

						if ( YITH_WAPO::$is_wpml_installed ) {

							$name = YITH_WAPO_WPML::string_translate( $name );

							if ( isset( $single_type_options['add_on_type'] ) ) {

								switch ( $single_type_options['add_on_type'] ) {
									case 'checkbox':
									case 'labels':
									case 'multiple_labels':
									case 'select':
									case 'radio':
										$value = YITH_WAPO_WPML::string_translate( $value );
										break;
								}
							}
						}

						if ( isset( $price ) ) {

							if ( 0 !== $price && ! $this->hide_price( $product_parent_id ) ) {
								$name .= apply_filters( 'yith_wapo_cart_item_addon_price', ' (' . ( $price > 0 ? '+' : '' ) . wc_price( $price ) . ')' );
							}

							if ( ! isset( $single_type_options['uploaded_file'] ) ) {
								$value = esc_html( wp_strip_all_tags( $value ) );
							}
							$other_data[] = array(
								'name'  => $name,
								'value' => $value,
							);

						}
					}
				}
			}
			return $other_data;
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
		 * Add add-ons as meta data in the order for WooCommerce > 3.0.
		 *
		 * @param int   $item_id Item id to add to order.
		 * @param mixed $values Add-Ons options.
		 * @param array $type_list Add_ons type list.
		 */
		public function order_item_meta( $item_id, $values, $type_list = array() ) {

			if ( is_object( $values ) && property_exists( $values, 'legacy_values' ) ) {
				$values = $values->legacy_values;
			}

			if ( ! empty( $values['yith_wapo_options'] ) ) {

				$type_list = empty( $type_list ) ? $this->get_cart_wapo_options( $values, 'all' ) : $type_list;

				foreach ( $type_list as $single_type_options ) {

					if ( isset( $single_type_options['price'] ) ) {

						$name = '<span id="' . $single_type_options['type_id'] . '">' . $single_type_options['name'] . '</span>';

						if ( 0 !== $single_type_options['price'] && ! empty( $single_type_options['price'] ) ) {

							$name .= apply_filters( 'yith_wapo_order_item_addon_price', ' (' . wc_price( $single_type_options['price'] ) . ')' );

							if ( apply_filters( 'yith_wapo_show_sold_individually_label', true ) && isset( $values['yith_wapo_sold_individually'] ) && $values['yith_wapo_sold_individually'] ) {
								$name .= ' ' . _x( '* Sold individually', 'notice on admin order item meta', 'yith-woocommerce-product-add-ons' );
							}
						}

						wc_add_order_item_meta( $item_id, wp_strip_all_tags( $name ), $single_type_options['value'] );

					}

					$extra_info['yith_wapo_sold_individually'] = $values['yith_wapo_sold_individually'];

					wc_add_order_item_meta( $item_id, '_ywapo_meta_data', $values['yith_wapo_options'] );
					wc_add_order_item_meta( $item_id, '_ywapo_extra_info', $extra_info );

				}
			}
		}


		/**
		 * Format item meta adding add-ons informations.
		 *
		 * @param array $formatted_meta Formatted data.
		 *
		 * @return mixed
		 */
		public function order_items_meta_display( $formatted_meta ) {

			if ( YITH_WAPO::$is_wpml_installed ) {

				foreach ( $formatted_meta as $key => &$meta_values ) {

					$label = &$meta_values['label'];

					if ( ! strstr( $label, '<dl class="variation">' ) ) {

						// translate label.

						if ( strstr( $label, '(' ) ) {

							$display = explode( '(', $label );

							if ( count( $display ) > 1 ) {

								$name = trim( $display[0] );

								$translate_name = YITH_WAPO_WPML::string_translate( $name );

								if ( ! empty( $translate_name ) && $name !== $translate_name ) {

									$meta_values['label'] = $translate_name;

								}
							}
						} else {

							$name = $label;

							$translate_name = YITH_WAPO_WPML::string_translate( $name );

							if ( ! empty( $translate_name ) && $name !== $translate_name ) {

								$meta_values['label'] = $translate_name;

							}
						}

						// translate value.

						$value = &$meta_values['value'];

						$translate_value = YITH_WAPO_WPML::string_translate( $value );

						if ( ! empty( $translate_value ) && $value !== $translate_value ) {

							$meta_values['value'] = $translate_value;

						}
					}
				}
			}
			return $formatted_meta;
		}

		/**
		 * Adjust cart price any time an item is restored
		 *
		 * @param string $cart_item_key Cart item key.
		 * @param object $cart Cart object.
		 */
		public function cart_item_restored( $cart_item_key, $cart ) {

			if ( isset( $cart->cart_contents[ $cart_item_key ] ) ) {

				$cart_item = $cart->cart_contents[ $cart_item_key ];

				$this->cart_adjust_price( $cart_item );

				// search for sold individually item.

				$removed_cart_contents = $cart->removed_cart_contents;

				if ( isset( $_REQUEST['undo_item'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

					remove_action( 'woocommerce_cart_item_restored', array( $this, 'cart_item_restored' ), 10 );

					foreach ( $removed_cart_contents as $cart_item_key_removed => $values ) {

						if ( isset( $values['yith_wapo_cart_item_key_parent'] ) && $values['yith_wapo_cart_item_key_parent'] === $cart_item_key && $_REQUEST['undo_item'] === $cart_item_key ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$cart->restore_cart_item( $cart_item_key_removed );
						}
					}

					add_action( 'woocommerce_cart_item_restored', array( $this, 'cart_item_restored' ), 10, 2 );

				}
			}

		}

		/**
		 * Adjust cart price
		 *
		 * @param array $cart_item Cart item.
		 * @return mixed
		 */
		public function cart_adjust_price( $cart_item ) {

			// Adjust price if addons are set.
			if ( ! empty( $cart_item['yith_wapo_options'] ) && apply_filters( 'yith_wapo_adjust_price', true, $cart_item ) && isset( $cart_item['data'] ) ) {

				if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
					$cart_item['data']->price             = 0;
					$cart_item['data']->manage_stock      = 'no';
					$cart_item['data']->sold_individually = 'yes';
				}

				$types_total_price = $this->get_total_add_ons_price( $cart_item );

				if ( defined( 'YWCRBP_PREMIUM' ) ) {

					if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
						yit_set_prop( $cart_item['data'], 'price', $types_total_price );
					} else {
						yit_set_prop( $cart_item['data'], 'price', $cart_item['data']->get_price() + $types_total_price );
					}

					yit_set_prop( $cart_item['data'], 'yith_wapo_price', true );

				} elseif ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {

					$cart_item['data']->set_price( $types_total_price );

				} else {

					$yit_get_prop = yit_get_prop( $cart_item['data'], '_price', true, 'edit' );
					$yit_get_prop = is_numeric( $yit_get_prop ) ? $yit_get_prop : 0;
					$cart_item['data']->set_price( $yit_get_prop + $types_total_price );

				}

				$cart_item['data']->yith_wapo_adjust_price = true;

			}

		}

		/**
		 * Get total price for add-ons
		 *
		 * @param array $cart_item Cart item.
		 *
		 * @return int
		 */
		public function get_total_add_ons_price( $cart_item ) {

			$type_list         = $this->get_cart_wapo_options( $cart_item, 'all' );
			$types_total_price = $this->get_total_by_add_ons_list( $type_list );

			return $types_total_price;

		}

		/**
		 * Get total price for add-ons list
		 *
		 * @param array $type_list Type list.
		 *
		 * @return int
		 */
		private function get_total_by_add_ons_list( $type_list ) {

			$types_total_price = 0;

			foreach ( $type_list as $single_type_option ) {

				if ( isset( $single_type_option['price_original'] ) && 0 !== $single_type_option['price_original'] ) {
					$types_total_price += (float) $single_type_option['price_original'];
				}
			}
			return apply_filters( 'yith_wapo_get_total_by_add_ons_list', $types_total_price, $type_list );

		}

		/**
		 * Get display price.
		 *
		 * @param object $product WC product.
		 * @param string $price Product price.
		 * @param string $price_type Price type ( fixed/percentage/calculated_multiplication/calculated_character_count ).
		 * @param bool   $use_display Show price.
		 * @param null   $variation Variation data.
		 * @param null   $forced_product_price Product price to use.
		 * @param null   $value Value to use.
		 *
		 * @return float|int|mixed
		 */
		public function get_display_price( $product, $price, $price_type, $use_display = true, $variation = null, $forced_product_price = null, $value = null ) {

			// price calculation.
			$price_calculated  = 0;
			$decimal_separator = wc_get_price_decimal_separator();
			$price             = str_replace( $decimal_separator, '.', $price );

			// Returns the price including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
			$product_display_price = ( $use_display ) ? yit_get_display_price( $product, $price ) : $price;

			if ( apply_filters( 'yith_wapo_display_price_without_tax', false ) ) {
				$product_display_price = floatval( money_format( '%.2n', $price ) ); // phpcs:ignore PHPCompatibility.FunctionUse.RemovedFunctions.money_formatDeprecated
			}

			if ( 0 !== floatval( $price ) && ! empty( $price ) ) {

				switch ( $price_type ) {

					case 'fixed':
						$price_calculated = $use_display ? $product_display_price : $price;
						break;

					case 'percentage':
						$price_fixed = 0;
						if ( strpos( $price, ';' ) > 0 ) {
							$prices      = explode( ';', $price );
							$price       = $prices[0];
							$price_fixed = $prices[1];
						}

						if ( $forced_product_price > 0 ) {
							$product_final_price = $forced_product_price;
						} else {
							$product_object       = isset( $variation ) ? $variation : $product;
							$product_object_price = yit_get_prop( $product_object, '_price', true, 'edit' );
							$product_price        = defined( 'YWCRBP_PREMIUM' ) ? $product_object->get_price() : $product_object_price;
							$product_final_price  = ( $use_display ? yit_get_display_price( $product_object ) : $product_price );
						}
						$product_final_price = apply_filters( 'yith_wapo_product_final_price', $product_final_price, $price_type, $product_object );
						$price_calculated    = ( ( $product_final_price / 100 ) * $price ) + $price_fixed;
						break;

					case 'calculated_multiplication':
						$value = ( isset( $value ) && is_numeric( $value ) ) ? str_replace( $decimal_separator, '.', $value ) : 1;

						if ( apply_filters( 'yith_wapo_force_display_price_without_tax', false ) ) {
							$price_calculated = apply_filters( 'yith_wapo_calculated_multiplication_price', $price ) * floatval( $value );
						} else {
							$price_calculated = apply_filters( 'yith_wapo_calculated_multiplication_price', $product_display_price ) * floatval( $value );
						}
						break;

					case 'calculated_character_count':
						if ( isset( $value ) ) {
							$price_calculated = $price * strlen( $value );
						}
						break;

				}
			}

			return $price_calculated;

		}

		/**
		 * Get Upload dir
		 *
		 * @param array $param Upload dire params.
		 *
		 * @return mixed
		 */
		public function upload_dir( $param ) {

			$path_dir = '/' . $this->option_upload_folder_name . '/';

			$unique_dir = md5( WC()->session->get_customer_id() );
			$subdir     = $path_dir . $unique_dir;

			if ( empty( $param['subdir'] ) ) {
				$param['path']   = $param['path'] . $subdir;
				$param['url']    = $param['url'] . $subdir;
				$param['subdir'] = $subdir;
			} else {
				$param['path']   = str_replace( $param['subdir'], $subdir, $param['path'] );
				$param['url']    = str_replace( $param['subdir'], $subdir, $param['url'] );
				$param['subdir'] = str_replace( $param['subdir'], $subdir, $param['subdir'] );
			}

			return $param;

		}

		/**
		 * Get conditions for add-ons option
		 *
		 * @param string $depend Options list.
		 *
		 * @return string
		 */
		public function check_conditional_options( $depend ) {

			global $wpdb;

			$options_list = explode( ',', $depend );

			foreach ( $options_list as $key => $option_id ) {

				if ( strpos( $option_id, 'option_' ) !== false ) {

					$depend_value_info = explode( '_', $option_id );

					if ( is_array( $depend_value_info ) && 3 === count( $depend_value_info ) ) {

						$type_id      = $depend_value_info[1];
						$option_index = $depend_value_info[2];

						$result = $wpdb->get_row( $wpdb->prepare( "SELECT options FROM {$wpdb->prefix}yith_wapo_types WHERE id=%s and del=0", $type_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

						if ( isset( $result->options ) ) {

							$options = maybe_unserialize( $result->options );

							if ( isset( $options['label'][ $option_index ] ) ) {
								$option_data = $option_index;
							}
						}
					}
				} else {

					$option_data = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}yith_wapo_types WHERE id=%s and del=0", $option_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

				}

				if ( ! isset( $option_data ) ) {
					unset( $options_list[ $key ] );
				}
			}

			return implode( ',', $options_list );

		}


		/**
		 * Filter cart item and add add-ons options
		 *
		 * @param array  $cart_item Cart item.
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
		 * Add option as cart item.
		 *
		 * @param string $cart_item_key Cart item key.
		 * @param int    $product_id Product ID.
		 * @param int    $quantity Item quantity.
		 * @param int    $variation_id Variation ID.
		 * @param array  $variation Variation data.
		 * @param array  $cart_item_data Cart item data.
		 */
		public function add_to_cart_sold_individually( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

			if ( $product_id > 0 && isset( $_REQUEST['add-to-cart'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				$cart_item_meta = $this->add_cart_item_data( array(), $product_id, $_REQUEST, true, $cart_item_key ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $cart_item_meta ) && isset( $cart_item_meta['yith_wapo_options'] ) && ! empty( $cart_item_meta['yith_wapo_options'] ) ) {

					remove_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ), 10 );
					remove_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10 );

					$res = WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation, $cart_item_meta );

					add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ), 10, 6 );
					add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );

				}
			}

		}

		/**
		 * Hide remove link
		 *
		 * @param string $html Cart item remove link.
		 * @param string $cart_item_key Cart item key.
		 *
		 * @return mixed
		 */
		public function hide_remove_link( $html, $cart_item_key = '' ) {

			$cart = WC()->cart->cart_contents;

			if ( isset( $cart[ $cart_item_key ] ) ) {

				$cart_item_meta = $cart[ $cart_item_key ];

				if ( isset( $cart_item_meta['yith_wapo_sold_individually'] ) && $cart_item_meta['yith_wapo_sold_individually'] && isset( $cart_item_meta['yith_wapo_cart_item_key_parent'] ) && 0 !== $cart_item_meta['yith_wapo_cart_item_key_parent'] ) {

					return '';

				}
			}

			return $html;

		}

		/**
		 * Hide thumbnail name
		 *
		 * @param string $html Item thumbnail.
		 * @param array  $cart_item Cart item.
		 *
		 * @return string
		 */
		public function hide_thumbnail_name( $html, $cart_item = array() ) {

			if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {

				return '';

			}

			return $html;

		}

		/**
		 * Remove Items from cart
		 *
		 * @param string $cart_item_key Cart item key.
		 * @param object $cart WC Cart.
		 */
		public function remove_items_from_cart( $cart_item_key, $cart ) {

			remove_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10 );

			foreach ( $cart->get_cart() as $cart_item_key_ass => $value ) {

				if ( isset( $value['yith_wapo_cart_item_key_parent'] ) && $value['yith_wapo_cart_item_key_parent'] === $cart_item_key ) {

					WC()->cart->remove_cart_item( $cart_item_key_ass );

				}
			}

			add_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10, 2 );

		}

		/**
		 * Check if hide price of product.
		 *
		 * @param int $product_id Product ID.
		 *
		 * @return bool
		 */
		public function hide_price( $product_id ) {

			// Catalog Mode.

			if ( function_exists( 'YITH_WCTM' ) ) {

				$catalog_mode_object = YITH_WCTM();

				if ( method_exists( $catalog_mode_object, 'check_price_hidden' ) ) {
					return YITH_WCTM()->check_price_hidden( false, $product_id );
				}
			}

			// Request a Quote.

			if ( function_exists( 'YITH_Request_Quote_Premium' ) ) {

				return 'yes' === get_option( 'ywraq_hide_price' );

			}

			return false;

		}

		/**
		 * Filter order formatted line subtotal (Support for YITH WooCommerce Subscription).
		 *
		 * @param string $subtotal order line subtotal.
		 * @param array  $item Order item.
		 * @param object $subscription Subscription object.
		 * @param object $product WC Product.
		 *
		 * @return mixed
		 */
		public function ywsbs_order_formatted_line_subtotal( $subtotal, $item, $subscription, $product ) {

			if ( isset( $item['item_meta'] ) && isset( $item['item_meta']['_ywapo_meta_data'] ) && isset( $item['item_meta']['_line_total'] ) ) {

				if ( method_exists( $subscription, 'change_price_html' ) ) {

					$tax_inc = get_option( 'woocommerce_prices_include_tax' ) === 'yes';

					if ( wc_tax_enabled() && $tax_inc && apply_filters( 'yith_wapo_display_price_without_tax', false ) ) {
						$subtotal = $item['item_meta']['_line_total'][0] + $item['item_meta']['_line_tax'][0];

					} else {
						$subtotal = $item['item_meta']['_line_total'][0];
					}

					$subtotal = $subscription->change_price_html( wc_price( $subtotal ), $product );

				}
			}

			return $subtotal;

		}

		/**
		 * Filter calculated bundle price (support for YITH WooCommerce Product Bundles).
		 *
		 * @param string $price_html Price html for bundle.
		 * @param string $price Bundle price.
		 * @return string
		 */
		public function ywcpb_print_calculated_price( $price_html, $price ) {
			return $price_html . '<input type="hidden" class="yith-wcpb-wapo-bundle-product-price" value="' . esc_html( $price ) . '" />';
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
		 * @param array  $cart_item Cart item.
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
		 * Return Order item subtotal
		 *
		 * @param string $product_sub_total Product subtotal.
		 * @param array  $item Order Item data.
		 * @param object $order WC Order object.
		 *
		 * @return string
		 */
		public function order_item_subtotal( $product_sub_total, $item, $order ) {

			if ( isset( $item['item_meta']['_ywapo_meta_data'][0] ) && isset( $item['item_meta']['_bundled_items'][0] ) ) {

				$type_list = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'][0] );

				$types_total_price = $this->get_total_by_add_ons_list( $type_list );

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


		/**
		 * Exclude sold individually
		 *
		 * @param bool  $add_items Boolean value to establish if add ietm to cart.
		 * @param array $cart_item Cart item to check.
		 *
		 * @return bool
		 */
		public function exclude_sold_individually( $add_items, $cart_item ) {

			if ( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
				$add_items = false;
			}

			return $add_items;
		}


	}

}
