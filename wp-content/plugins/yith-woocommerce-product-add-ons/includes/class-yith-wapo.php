<?php
/**
 * WAPO Main Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO' ) ) {

	/**
	 * YITH_WAPO Class
	 */
	class YITH_WAPO {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO
		 */
		public static $instance;

		/**
		 * Admin object
		 *
		 * @var YITH_WAPO_Admin
		 */
		public $admin;

		/**
		 * Front object
		 *
		 * @var YITH_WAPO_Front
		 */
		public $front;

		/**
		 * Cart object
		 *
		 * @var YITH_WAPO_Cart
		 */
		public $cart;

		/**
		 * Check if YITH Multi Vendor is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_vendor_installed;

		/**
		 * Check if WPML is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_wpml_installed;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO|YITH_WAPO_Premium
		 */
		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->version = YITH_WAPO_VERSION;

			global $sitepress;
			self::$is_wpml_installed   = ! empty( $sitepress );
			self::$is_vendor_installed = function_exists( 'YITH_Vendors' );

			// Load Plugin Framework.
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) {
				add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
				add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
			}

			// Actions.
			$nonce  = ! function_exists( 'wp_verify_nonce' ) || isset( $_REQUEST['nonce'] )
			&& ( wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'wapo_action' ) || wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'wapo_admin' ) );
			$action = sanitize_key( $_REQUEST['wapo_action'] ?? '' );

			if ( $action && $nonce ) {
				$block_id = sanitize_key( $_REQUEST['block_id'] ?? '' );
				$addon_id = sanitize_key( $_REQUEST['addon_id'] ?? '' );
				if ( 'save-block' === $action ) {
					$this->save_block( $_REQUEST );
				} elseif ( 'duplicate-block' === $action ) {
					$this->duplicate_block( $block_id );
				} elseif ( 'remove-block' === $action ) {
					$this->remove_block( $block_id );
				} elseif ( 'save-addon' === $action ) {
					$this->save_addon( $_REQUEST );
				} elseif ( 'duplicate-addon' === $action ) {
					$this->duplicate_addon( $block_id, $addon_id );
				} elseif ( 'remove-addon' === $action ) {
					$this->remove_addon( $block_id, $addon_id );
				} elseif ( 'db-check' === $action ) {
					$this->db_check();
				} elseif ( 'reset-migration' === $action ) {
					$this->reset_migration();
				}
			}

			// Admin.
			if ( is_admin() && ( ! isset( $_REQUEST['action'] ) || ( isset( $_REQUEST['action'] ) && 'yith_load_product_quick_view' !== $_REQUEST['action'] ) ) ) {
				$this->admin = YITH_WAPO_Admin();
			}

			// Front.
			$is_ajax_request = defined( 'DOING_AJAX' ) && DOING_AJAX;
			if ( ! is_admin() || $is_ajax_request ) {
				$this->front = YITH_WAPO_Front();
				$this->cart  = YITH_WAPO_Cart();
			}

			// WCCL settings.
			add_action( 'init', array( $this, 'wccl_settings' ) );

		}

		/**
		 * Load Plugin Framework
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {
			if ( function_exists( 'YIT_Plugin_Licence' ) ) {
				YIT_Plugin_Licence()->register( YITH_WAPO_INIT, YITH_WAPO_SECRET_KEY, YITH_WAPO_SLUG );
			}
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			if ( function_exists( 'YIT_Upgrade' ) ) {
				YIT_Upgrade()->register( YITH_WAPO_SLUG, YITH_WAPO_INIT );
			}
		}

		/**
		 * Get HTML types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_html_types() {
			$html_types = array(
				array(
					'slug' => 'html_heading',
					'name' => __( 'Heading', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'html_text',
					'name' => __( 'Text', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'html_separator',
					'name' => __( 'Separator', 'yith-woocommerce-product-add-ons' ),
				),
			);
			return $html_types;
		}

		/**
		 * Get addon types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_addon_types() {
			$addon_types = array(
				array(
					'slug' => 'checkbox',
					'name' => __( 'Checkbox', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'radio',
					'name' => __( 'Radio', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'text',
					'name' => __( 'Input text', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'textarea',
					'name' => __( 'Textarea', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'color',
					'name' => __( 'Color swatch', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'number',
					'name' => __( 'Number', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'select',
					'name' => __( 'Select', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'label',
					'name' => __( 'Label or image', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'product',
					'name' => __( 'Product', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'date',
					'name' => __( 'Date', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'file',
					'name' => __( 'File upload', 'yith-woocommerce-product-add-ons' ),
				),
			);
			return $addon_types;
		}

		/**
		 * Get available addon types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_available_addon_types() {
			return array( 'checkbox', 'radio', 'text', 'select' );
		}

		/**
		 * Save Block
		 *
		 * @param array $request Request array.
		 * @return mixed
		 */
		public function save_block( $request ) {
			global $wpdb;

			if ( isset( $request['block_id'] ) ) {

				$rules = array(
					'show_in'                     => isset( $request['block_rule_show_in'] ) ? $request['block_rule_show_in'] : 'all',
					'show_in_products'            => isset( $request['block_rule_show_in_products'] ) ? $request['block_rule_show_in_products'] : '',
					'show_in_categories'          => isset( $request['block_rule_show_in_categories'] ) ? $request['block_rule_show_in_categories'] : '',
					'exclude_products'            => isset( $request['block_rule_exclude_products'] ) ? $request['block_rule_exclude_products'] : '',
					'exclude_products_products'   => isset( $request['block_rule_exclude_products_products'] ) ? $request['block_rule_exclude_products_products'] : '',
					'exclude_products_categories' => isset( $request['block_rule_exclude_products_categories'] ) ? $request['block_rule_exclude_products_categories'] : '',
					'show_to'                     => isset( $request['block_rule_show_to'] ) ? $request['block_rule_show_to'] : '',
					'show_to_user_roles'          => isset( $request['block_rule_show_to_user_roles'] ) ? $request['block_rule_show_to_user_roles'] : '',
					'show_to_membership'          => isset( $request['block_rule_show_to_membership'] ) ? $request['block_rule_show_to_membership'] : '',
				);

				$settings = array(
					'name'     => isset( $request['block_name'] ) ? $request['block_name'] : '',
					'priority' => isset( $request['block_priority'] ) ? $request['block_priority'] : 0,
					'rules'    => $rules,
				);

				$data = array(
					'settings'   => serialize( $settings ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'priority'   => isset( $request['block_priority'] ) ? $request['block_priority'] : 0,
					'visibility' => 1,
				);

				if ( isset( $request['block_user_id'] ) && $request['block_user_id'] > 0 ) {
					$data['user_id'] = sanitize_text_field( $request['block_user_id'] );
				}

				/** YITH Multi Vendor integration. **/
				$vendor_id = '';

				// migration.
				if ( isset( $request['block_vendor_id'] ) ) {
					$vendor_id = sanitize_text_field( $request['block_vendor_id'] );
				// v2.
				} else if ( isset( $request['vendor_id'] ) ) {
					$vendor_id = sanitize_text_field( $request['vendor_id'] );
				}
				$data['vendor_id'] = $vendor_id;


				$table = $wpdb->prefix . 'yith_wapo_blocks';

				if ( 'new' === $request['block_id'] ) {

					if ( ! isset( $request['block_priority'] ) || 0 === $request['block_priority'] ) {
						$new_priority = 0;
						// Get max priority value.
						$max_priority = $wpdb->get_var( "SELECT MAX(priority) FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						// Get number of blocks.
						$res_priority = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						$total_blocks = $wpdb->num_rows;
						// New priority value.
						if ( $max_priority > 0 && $total_blocks > 0 ) {
							$new_priority = $max_priority > $total_blocks ? $max_priority : $total_blocks;
						}
						$data['priority'] = $new_priority + 1;
					}

					$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
					$block_id = $wpdb->insert_id;

				} elseif ( $request['block_id'] > 0 ) {
					$block_id = $request['block_id'];
					$wpdb->update( $table, $data, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				}

				if ( isset( $request['add_options_after_save'] ) ) {
					wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id . '&addon_id=new' ) );
				} elseif ( isset( $request['wapo_action'] ) && 'save-block' === $request['wapo_action'] ) {
					wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );
				} else {
					return $block_id;
				}
			}

		}

		/**
		 * Duplicate Block
		 *
		 * @param int $block_id Block ID.
		 * @return void
		 */
		public function duplicate_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {

				$query_block        = "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE id='$block_id'";
				$query_addons       = "SELECT * FROM {$wpdb->prefix}yith_wapo_addons WHERE block_id='$block_id' AND deleted='0' ";
				$queried_block_row  = $wpdb->get_row( $query_block ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				$queried_addons_row = $wpdb->get_results( $query_addons ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

				if ( isset( $queried_block_row ) && $queried_block_row->id === $block_id ) {

					$addons_table = $wpdb->prefix . 'yith_wapo_addons';
					$block_table  = $wpdb->prefix . 'yith_wapo_blocks';
					$block_data   = array(
						'settings'   => $queried_block_row->settings,
						'priority'   => $queried_block_row->priority,
						'visibility' => $queried_block_row->visibility,
					);

					$wpdb->insert( $block_table, $block_data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
					$block_id = $wpdb->insert_id;

					$settings_addons_old = array();
					$addons_new_ids      = array();

					foreach ( $queried_addons_row as $addons_row ) {
						$addons_data = array(
							'block_id'   => $block_id,
							'settings'   => $addons_row->settings,
							'options'    => $addons_row->options,
							'priority'   => $addons_row->priority,
							'visibility' => $addons_row->visibility,
						);

						$wpdb->insert( $addons_table, $addons_data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						$addon_id = $wpdb->insert_id;

						if ( $addon_id ) { // Sync conditional logics with new data.
							$settings                               = unserialize( $addons_data['settings'] );
							$settings_addons_old[ $addons_row->id ] = $settings; // Save setting default addon.
							$addons_new_ids[ $addons_row->id ]      = $addon_id; // Create an array pair  default_addon => clone addon.

						}
					}

					if ( ! empty( $addons_new_ids ) ) {

						foreach ( $addons_new_ids as $old_id => $new_id ) {

							$conditional_rule_addon_old = $settings_addons_old[ $old_id ]['conditional_rule_addon'];

							if ( is_array( $conditional_rule_addon_old ) ) {

								$conditional_rule_addon_new = array();

								foreach ( $conditional_rule_addon_old as $id ) {

									if ( ! empty( $id ) ) {

										$split_addon = explode( '-', $id );

										if ( $split_addon ) {

											if ( 'v' !== $split_addon[0] ) { // Prevent change variations.

												$split_addon[0]               = $addons_new_ids[ $split_addon[0] ]; // change new addon_id.
												$new_value                    = implode( '-', $split_addon );
												$conditional_rule_addon_new[] = $new_value;

											} else {

												$conditional_rule_addon_new[] = $id;

											}
										} else { // Simple addon only switch the value.

											$conditional_rule_addon_new[] = $settings_addons_old[ $id ];
										}
									}
								}
								if ( ! empty( $conditional_rule_addon_new ) ) {

									$settings_addons_old[ $old_id ]['conditional_rule_addon'] = $conditional_rule_addon_new;
									$update_settings_values                                   = serialize( $settings_addons_old[ $old_id ] );
									$wpdb->update( $addons_table, array( 'settings' => $update_settings_values ), array( 'id' => $new_id ) );
								}
							}
						}
					}

					wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );

				}
			}

		}

		/**
		 * Remove Block
		 *
		 * @param int $block_id Block ID.
		 * @return void
		 */
		public function remove_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {
				$query  = "UPDATE {$wpdb->prefix}yith_wapo_blocks SET deleted='1' WHERE id='$block_id'";
				$result = $wpdb->query( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );
			}

		}

		/**
		 * Save Addon
		 *
		 * @param array $request Request array.
		 * @return mixed
		 */
		public function save_addon( $request ) {
			global $wpdb;

			if ( isset( $request['block_id'] ) && 'new' === $request['block_id'] ) {
				$request['block_id'] = $this->save_block( array( 'block_id' => 'new' ) );
			}

			if ( isset( $request['addon_id'] ) && isset( $request['block_id'] ) && $request['block_id'] > 0 ) {

				$conditional_logic = array();

				$settings = array(

					// General.
					'type'                         => $request['addon_type'] ?? '',

					// Display options.
					'title'                        => isset( $request['addon_title'] ) ? str_replace( '"', '&quot;', $request['addon_title'] ) : '',
					'description'                  => $request['addon_description'] ?? '',
					'required'                     => $request['addon_required'] ?? '',
					'show_image'                   => $request['addon_show_image'] ?? '',
					'image'                        => $request['addon_image'] ?? '',
					'image_replacement'            => $request['addon_image_replacement'] ?? '',
					'options_images_position'      => $request['addon_options_images_position'] ?? '',
					'show_as_toggle'               => $request['addon_show_as_toggle'] ?? '',
					'hide_options_images'          => $request['addon_hide_options_images'] ?? '',
					'hide_options_label'           => $request['addon_hide_options_label'] ?? '',
					'hide_options_prices'          => $request['addon_hide_options_prices'] ?? '',
					'hide_products_prices'         => $request['addon_hide_products_prices'] ?? '',
					'show_add_to_cart'             => $request['addon_show_add_to_cart'] ?? '',
					'show_sku'                     => $request['addon_show_sku'] ?? '',
					'show_stock'                   => $request['addon_show_stock'] ?? '',
					'show_quantity'                => $request['addon_show_quantity'] ?? '',
					'show_in_a_grid'               => $request['addon_show_in_a_grid'] ?? '',
					'options_per_row'              => $request['addon_options_per_row'] ?? '',
					'options_width'                => $request['addon_options_width'] ?? '',
					// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					// 'show_quantity_selector'	=> isset( $request['addon_show_quantity_selector'] )	? $request['addon_show_quantity_selector']	: '',

					// Style settings.
					'custom_style'                 => $request['addon_custom_style'] ?? '',
					'image_position'               => $request['addon_image_position'] ?? '',
					'label_content_align'          => $request['addon_label_content_align'] ?? '',
					'image_equal_height'           => $request['addon_image_equal_height'] ?? '',
					'images_height'                => $request['addon_images_height'] ?? '',
					'label_position'               => $request['addon_label_position'] ?? '',
					'label_padding'                => $request['addon_label_padding'] ?? '',
					'description_position'         => $request['addon_description_position'] ?? '',

					// Conditional logic.
					'enable_rules'                 => $request['addon_enable_rules'] ?? '',
					'conditional_logic_display'    => $request['addon_conditional_logic_display'] ?? '',
					'conditional_logic_display_if' => $request['addon_conditional_logic_display_if'] ?? '',
					'conditional_rule_addon'       => $request['addon_conditional_rule_addon'] ?? '',
					'conditional_rule_addon_is'    => $request['addon_conditional_rule_addon_is'] ?? '',

					// Advanced options.
					'first_options_selected'       => $request['addon_first_options_selected'] ?? '',
					'first_free_options'           => $request['addon_first_free_options'] ?? '',
					'selection_type'               => $request['addon_selection_type'] ?? '',
					'enable_min_max'               => $request['addon_enable_min_max'] ?? '',
					'min_max_rule'                 => $request['addon_min_max_rule'] ?? '',
					'min_max_value'                => $request['addon_min_max_value'] ?? '',

					// HTML elements.
					'text_content'                 => isset( $request['option_text_content'] ) ? str_replace( '"', '&quot;', $request['option_text_content'] ) : '',
					'heading_text'                 => isset( $request['option_heading_text'] ) ? str_replace( '"', '&quot;', $request['option_heading_text'] ) : '',
					'heading_type'                 => $request['option_heading_type'] ?? '',
					'heading_color'                => $request['option_heading_color'] ?? '',
					'separator_style'              => $request['option_separator_style'] ?? '',
					'separator_width'              => $request['option_separator_width'] ?? '',
					'separator_size'               => $request['option_separator_size'] ?? '',
					'separator_color'              => $request['option_separator_color'] ?? '',

					// Rules.
					'conditional_logic'            => $conditional_logic,
				);

				$data = array(
					'block_id'   => $request['block_id'],
					'settings'   => serialize( $settings ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'options'    => serialize( $request['options'] ?? '' ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'visibility' => 1,
				);

				$table = $wpdb->prefix . 'yith_wapo_addons';

				if ( 'new' === $request['addon_id'] ) {
					$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
					$addon_id = $wpdb->insert_id;

					// New priority value.
					$priority_data = array( 'priority' => $addon_id );
					$wpdb->update( $table, $priority_data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

				} elseif ( $request['addon_id'] > 0 ) {
					$addon_id = $request['addon_id'];
					$wpdb->update( $table, $data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				}

				if ( self::$is_wpml_installed ) {
					YITH_WAPO_WPML::register_option_type( $settings['title'], $settings['description'], $data['options'], $settings['text_content'], $settings['heading_text'] );
				}

				if ( isset( $request['wapo_action'] ) && 'save-addon' === $request['wapo_action'] ) {
					wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $request['block_id'] ) );
				} else {
					return $addon_id;
				}
			}

			return false;

		}

		/**
		 * Duplicate Addon
		 *
		 * @param int $block_id Block ID.
		 * @param int $addon_id Addon ID.
		 * @return void
		 */
		public function duplicate_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_addons WHERE id='$addon_id'";
				$row   = $wpdb->get_row( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

				$data = array(
					'block_id'   => $row->block_id,
					'settings'   => $row->settings,
					'options'    => $row->options,
					'priority'   => $row->priority,
					'visibility' => $row->visibility,
				);

				$table = $wpdb->prefix . 'yith_wapo_addons';
				$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				$addon_id = $wpdb->insert_id;

				wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );

			}

		}

		/**
		 * Remove Addon
		 *
		 * @param int $block_id Block ID.
		 * @param int $addon_id Addon ID.
		 * @return void
		 */
		public function remove_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {
				$query  = "UPDATE {$wpdb->prefix}yith_wapo_addons SET deleted='1' WHERE id='$addon_id'";
				$result = $wpdb->query( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );
			}

		}

		/**
		 * Database check
		 *
		 * @return void
		 */
		public function db_check() {
			update_option( 'yith_wapo_db_version', '0' );
			wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=debug' ) );
		}

		/**
		 * Reset migratoin
		 *
		 * @return void
		 */
		public function reset_migration() {
			update_option( 'yith_wapo_db_migration', '0' );
			wp_safe_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );
		}

		/**
		 *  Is Quick View
		 *
		 *  @return bool
		 */
		private function is_quick_view() {
			$ajax   = defined( 'DOING_AJAX' ) && DOING_AJAX;
			$action = isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $ajax && ( 'yit_load_product_quick_view' === $action || 'yith_load_product_quick_view' === $action || 'ux_quickview' === $action );
		}

		/**
		 * Get Current MultiVendor
		 *
		 * @return null|YITH_Vendor
		 */
		public static function get_current_multivendor() {
			if ( self::$is_vendor_installed && is_user_logged_in() ) {
				$vendor = yith_get_vendor( 'current', 'user' );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Get MultiVendor by ID
		 *
		 * @param int    $id ID.
		 * @param string $obj Obj.
		 * @return null|YITH_Vendor
		 */
		public static function get_multivendor_by_id( $id, $obj = 'vendor' ) {
			if ( self::$is_vendor_installed ) {
				$vendor = yith_get_vendor( $id, $obj );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Is Plugin Enabled for Vendors
		 *
		 * @return bool
		 */
		public static function is_plugin_enabled_for_vendors() {
			return get_option( 'yith_wpv_vendors_option_advanced_product_options_management' ) === 'yes';
		}

		/**
		 * Set the color and label configuration
		 */
		public function wccl_settings() {
			// Disable color and labels on loop when switching from v1.
			$wccl_enable_in_loop = apply_filters( 'yith_wapo_wccl_enable_in_loop', 'no' );
			update_option( 'yith-wccl-enable-in-loop', $wccl_enable_in_loop );
		}
	}
}

/**
 * Unique access to instance of YITH_WAPO class
 *
 * @return YITH_WAPO|YITH_WAPO_Premium
 * @since 1.0.0
 */
function YITH_WAPO() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_WAPO::get_instance();
}
