<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Admin class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons Premium
 */

if ( ! defined( 'YITH_WAPO' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WAPO_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WAPO_Admin {
		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version;

		/**
		 * Panel
		 *
		 * @var YIT_Plugin_Panel_WooCommerce
		 */
		protected $_panel; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Main Panel option
		 *
		 * @var string Main Panel Option
		 */
		protected $_main_panel_option; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Premium
		 *
		 * @var $_premium string Premium tab template file name
		 */
		protected $_premium = 'premium.php'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Panel Page
		 *
		 * @var string The panel page
		 */
		protected $_panel_page = 'yith_wapo_panel'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Official Doc
		 *
		 * @var string Official plugin documentation
		 */
		protected $_official_documentation = 'https://docs.yithemes.com/yith-woocommerce-product-add-ons'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Premium Landing
		 *
		 * @var string Official plugin landing page
		 */
		protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-product-add-ons'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Premium live
		 *
		 * @var string Official live demo
		 */
		protected $_premium_live = 'http://plugins.yithemes.com/yith-woocommerce-product-add-ons'; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Variations chosen list
		 *
		 * @var array
		 */
		public static $variations_chosen_list = array();

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * @param string $version Version.
		 */
		public function __construct( $version ) {

			$this->version = $version;

			// Actions.
			add_action( 'init', array( $this, 'init' ) );

			// Admin Menu.
			add_filter( 'ywapo_edit_advanced_product_options_capability', array( $this, 'ywapo_get_capability' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			// WooCommerce Product Data Tab.
			add_action( 'admin_init', array( $this, 'add_wc_product_data_tab' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'woo_add_custom_general_fields_save' ) );

			$page = sanitize_key( $_GET['page'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'yith_wapo_groups' === $page || 'yith_wapo_group' === $page || 'yith_wapo_group_addons' === $page ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
			}

			if ( ! defined( 'YITH_WAPO_PREMIUM' ) || ! YITH_WAPO_PREMIUM ) {
				// Type Options Template.
				add_action( 'yith_wapo_type_options_template', array( $this, 'type_options_template' ), 10, 1 );
				// Depend Variations Template.
				add_action( 'yith_wapo_depend_variations_template', array( $this, 'depend_variations_template' ), 10, 2 );
				// Addon Operator Template.
				add_action( 'yith_wapo_addon_operator_template', array( $this, 'addon_operator_template' ), 10, 1 );
				// Addon Options Template.
				add_action( 'yith_wapo_addon_options_template', array( $this, 'addon_options_template' ), 10, 1 );
			}

			// Admin Init.
			add_action( 'admin_init', array( $this, 'items_update' ), 9 );

			add_action( 'wp_ajax_ywcp_add_new_option', array( $this, 'add_new_option' ) );

			// Show Plugin Information.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WAPO_DIR . '/' . basename( YITH_WAPO_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// YITH WAPO Loaded.
			do_action( 'yith_wapo_loaded' );

		}


		/**
		 * Init method:
		 *  - default options
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function init() { }

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general' => __( 'General', 'yith-woocommerce-product-add-ons' ),
			);
			if ( ! defined( 'YITH_WAPO_PREMIUM' ) || ! YITH_WAPO_PREMIUM ) {
				$admin_tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-product-add-ons' );
				add_action( 'ywapo_premium_tab', array( $this, 'premium_tab' ) );
			} elseif ( defined( 'YITH_WAPO_WCCL' ) ) {
				$admin_tabs['variations'] = __( 'Variations', 'yith-woocommerce-product-add-ons' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Product Add-ons & Extra Options',
				'menu_title'       => 'Product Add-ons & Extra Options',
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'links'            => $this->get_panel_sidebar_links(),
				'admin-tabs'       => apply_filters( 'yith_wapo_admin_tabs', $admin_tabs ),
				'options-path'     => YITH_WAPO_DIR . '/plugin-options',
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_WAPO_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

			add_action( 'woocommerce_admin_field_yith_wapo_upload', array( $this->_panel, 'yit_upload' ), 10, 1 );

		}

		/**
		 * Premium tab
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_WAPO_DIR . 'v1/templates/admin/' . $this->_premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}
		}

		/**
		 * Get Panel sidebar Links
		 */
		public function get_panel_sidebar_links() {
			return array(
				array(
					'url'   => $this->_official_documentation,
					'title' => __( 'Plugin Documentation', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'url'   => 'https://yithemes.com/my-account/support/dashboard',
					'title' => __( 'Support platform', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'url'   => $this->_official_documentation . '/changelog',
					'title' => 'Changelog ( ' . YITH_WAPO_VERSION . ' )',
				),
			);
		}

		/**
		 * Get capability
		 *
		 * @author Andre Frascaspata
		 * @param string $capability Capability.
		 * @return string
		 */
		public function ywapo_get_capability( $capability ) {

			if ( YITH_WAPO::$is_vendor_installed ) {

				$vendor = yith_get_vendor( 'current', 'user' );

				if ( $vendor->is_valid() && $vendor->has_limited_access() && YITH_WAPO::is_plugin_enabled_for_vendors() ) {
					$capability = YITH_Vendors()->admin->get_special_cap();
				}
			}

			return $capability;

		}

		/**
		 * Admin menu
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function admin_menu() {

			$capability = apply_filters( 'ywapo_edit_advanced_product_options_capability', 'manage_woocommerce' );

			$page = add_submenu_page(
				'edit.php?post_type=product',
				__( 'Add-ons', 'yith-woocommerce-product-add-ons' ),
				__( 'Add-ons', 'yith-woocommerce-product-add-ons' ),
				$capability,
				'yith_wapo_groups',
				array( $this, 'yith_wapo_groups' )
			);
			$page = add_submenu_page(
				null,
				__( 'Add-ons Group', 'yith-woocommerce-product-add-ons' ),
				__( 'Add-ons Group', 'yith-woocommerce-product-add-ons' ),
				$capability,
				'yith_wapo_group',
				array( $this, 'yith_wapo_group' )
			);
			$page = add_submenu_page(
				null,
				__( 'Add-ons Options', 'yith-woocommerce-product-add-ons' ),
				__( 'Add-ons Options', 'yith-woocommerce-product-add-ons' ),
				$capability,
				'yith_wapo_group_addons',
				array( $this, 'yith_wapo_group_addons' )
			);
		}

		/**
		 * WAPO Admin
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function yith_wapo_groups() {
			require YITH_WAPO_DIR . '/v1/templates/admin/yith-wapo-groups.php';
		}

		/**
		 * Group
		 */
		public function yith_wapo_group() {
			require YITH_WAPO_DIR . '/v1/templates/admin/yith-wapo-group.php';
		}

		/**
		 * Group Addons
		 */
		public function yith_wapo_group_addons() {
			require YITH_WAPO_DIR . '/v1/templates/admin/yith-wapo-group-addons.php';
		}

		/**
		 * Items update
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function items_update() {

			global $wpdb;

			$nonce = ! function_exists( 'wp_verify_nonce' ) || isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'wapo_admin' );

			if ( $nonce ) {

				$id       = isset( $_REQUEST['id'] ) ? sanitize_key( $_REQUEST['id'] ) : '';
				$group_id = isset( $_REQUEST['group_id'] ) ? sanitize_key( $_REQUEST['group_id'] ) : '';
				$act      = isset( $_REQUEST['act'] ) ? sanitize_key( $_REQUEST['act'] ) : '';
				$class    = isset( $_REQUEST['class'] ) ? sanitize_key( $_REQUEST['class'] ) : '';

				// Delete Group.
				$delete_group_id = isset( $_REQUEST['delete_group_id'] ) ? sanitize_key( $_REQUEST['delete_group_id'] ) : 0;
				if ( $delete_group_id > 0 ) {
					$object = new YITH_WAPO_Group( $delete_group_id );
					$object->delete( $delete_group_id );
					wp_safe_redirect( 'edit.php?post_type=product&page=yith_wapo_groups' );
					exit;
				}

				// Duplicate Group.
				$duplicate_group_id = isset( $_REQUEST['duplicate_group_id'] ) ? sanitize_key( $_REQUEST['duplicate_group_id'] ) : 0;
				if ( $duplicate_group_id > 0 ) {
					$group = new YITH_WAPO_Group( $duplicate_group_id );
					$group->duplicate();
					wp_safe_redirect( 'edit.php?post_type=product&page=yith_wapo_groups' );
					exit;
				}

				// Delete Add-on.
				$delete_addon_id = isset( $_REQUEST['delete_addon_id'] ) ? sanitize_key( $_REQUEST['delete_addon_id'] ) : 0;
				if ( $delete_addon_id > 0 ) {
					$object = new YITH_WAPO_Type( $delete_addon_id );
					$object->delete( $delete_addon_id );
					wp_safe_redirect( 'edit.php?post_type=product&page=yith_wapo_group_addons&id=' . $id );
					exit;
				}

				// Duplicate Add-on.
				$duplicate_addon_id = isset( $_REQUEST['duplicate_addon_id'] ) ? sanitize_key( $_REQUEST['duplicate_addon_id'] ) : 0;
				if ( $duplicate_addon_id > 0 ) {
					$object = new YITH_WAPO_Type( $duplicate_addon_id );
					$object->duplicate();
					wp_safe_redirect( 'edit.php?post_type=product&page=yith_wapo_group_addons&id=' . $id );
					exit;
				}

				if ( class_exists( $class ) ) {
					$object = new $class( $id );
					if ( 'new' === $act ) {
						$object->insert();
						$id = 'YITH_WAPO_Group' === $class ? $wpdb->insert_id : $group_id;
					} elseif ( 'update' === $act ) {
						$object->update( $id );
						$id = 'YITH_WAPO_Group' === $class ? $id : $object->group_id;
					} elseif ( 'update-order' === $act ) {
						if ( isset( $_REQUEST['types-order'] ) && '' !== $_REQUEST['types-order'] ) {
							YITH_WAPO_Type::update_priorities( $_REQUEST['types-order'] ); // phpcs:ignore
						}
						$id = 'YITH_WAPO_Group' === $class ? $id : $object->group_id;
					}

					if ( 'YITH_WAPO_Group' === $class ) {
						$object = new YITH_WAPO_Group( $id );
					}
					$redirect_url = $id > 0 && 1 !== $object->del ?
						( $group_id > 0 ? 'edit.php?post_type=product&page=yith_wapo_group_addons&id=' . $id : 'edit.php?post_type=product&page=yith_wapo_group&id=' . $id )
						: 'edit.php?post_type=product&page=yith_wapo_groups';

					wp_safe_redirect( $redirect_url );
					exit;

				}
			}
		}

		/**
		 * Enqueue admin styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {

			global $pagenow;

			$suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

			/*
			 *  Js
			 */

			wp_enqueue_script( 'jquery' );
			// wp_enqueue_script( 'jquery-ui', YITH_WAPO_URL . 'v1/assets/js/jquery-ui/jquery-ui.min.js' );.

			wp_enqueue_script( 'jquery-blockui', YITH_WAPO_URL . 'v1/assets/js/jquery-ui/jquery.blockUI.min.js', array( 'jquery' ), true, true );

			wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select.min.js', array( 'jquery', 'select2', 'selectWoo' ), true, true );
			wp_enqueue_script( 'wc-enhanced-select' );

			wp_register_script( 'wc-tooltip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery', 'select2', 'selectWoo' ), true, true );
			wp_enqueue_script( 'wc-tooltip' );

			wp_register_script( 'yith_wapo_admin', YITH_WAPO_URL . 'v1/assets/js/yith-wapo-admin' . $suffix . '.js', array( 'jquery' ), true, true );
			wp_enqueue_script( 'yith_wapo_admin' );

			$script_params = array(
				'ajax_url'             => admin_url( 'admin-ajax.php', 'relative' ),
				'wc_ajax_url'          => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'confirm_text'         => __( 'Are you sure?', 'yith-woocommerce-product-add-ons' ),
				'uploader_title'       => __( 'Custom Image', 'yith-woocommerce-product-add-ons' ),
				'uploader_button_text' => __( 'Upload Image', 'yith-woocommerce-product-add-ons' ),
				'place_holder_url'     => YITH_WAPO_URL . 'v1/assets/img/placeholder.png',
			);

			wp_localize_script( 'yith_wapo_admin', 'yith_wapo_general', $script_params );

			/*
			 *  Css
			 */

			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'bootstrap-css' );
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css', array(), true );
			wp_enqueue_style( 'wapo-admin', YITH_WAPO_URL . 'v1/assets/css/yith-wapo-admin.css', array(), true );

		}

		/**
		 * Type Options Template
		 *
		 * @param string $field_type Field Type.
		 */
		public function type_options_template( $field_type ) { ?>
			<option value="checkbox" <?php selected( $field_type, 'checkbox' ); ?>><?php echo esc_html__( 'Checkbox', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="radio" <?php selected( $field_type, 'radio' ); ?>><?php echo esc_html__( 'Radio Button', 'yith-woocommerce-product-add-ons' ); ?></option>
			<option value="text" <?php selected( $field_type, 'text' ); ?>><?php echo esc_html__( 'Text', 'yith-woocommerce-product-add-ons' ); ?></option>
			<?php
		}

		/**
		 * Depend Variations Template
		 *
		 * @param string $type Type.
		 * @param string $group Group.
		 */
		public function depend_variations_template( $type, $group ) {
			?>
			<label for="variations">
				<?php echo esc_html__( 'Variations Requirements', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Show this add-on to users only if they have first selected one of the following variations.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</label>
			<select disabled="disabled" class="depend-select2" multiple="multiple" placeholder="<?php echo esc_attr__( 'Choose required variations', 'yith-woocommerce-product-add-ons' ); ?>..."></select>
			<?php
		}

		/**
		 * Addon Operator Template
		 *
		 * @param string $type Type.
		 */
		public function addon_operator_template( $type ) {
			?>
			<label for="depend">
				<?php echo esc_html__( 'Operator', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Select the operator for Options Requirements. Default: OR', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
			</label>
			<select disabled="disabled" name="operator"></select>
			<?php
		}

		/**
		 * Addon Options Template
		 *
		 * @param string $options Options.
		 */
		public function addon_options_template( $options ) {
			?>
			<div class="first_options_free">
				<?php echo esc_html__( 'The first', 'yith-woocommerce-product-add-ons' ); ?>
				<input type="number" disabled="disabled" class="regular-text" min="0">
				<?php echo esc_html__( 'options are free', 'yith-woocommerce-product-add-ons' ); ?>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="max_item_selected">
				<input type="number" disabled="disabled" class="regular-text" min="0">
				<?php echo esc_html__( 'Limit selectable elements', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Set the maximum number of elements that users can select for this add-on, 0 means no limits (works only with checkboxes)', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="max_input_values_amount">
				<input type="number" disabled="disabled" class="regular-text" min="0">
				<?php echo esc_html__( 'Max input values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Set the maximum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="min_input_values_amount">
				<input type="number" disabled="disabled" class="regular-text" min="0">
				<?php echo esc_html__( 'Min input values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>
				<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__( 'Set the minimum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="sold_individually">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'Sold individually', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'Check this box, if you want the selected add-ons not to increase if the product quantity changes.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="change_featured_image">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'Replace the product image', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'Check this box, if you want that the selected add-ons replace the product image.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="calculate_quantity_sum">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'Calculate quantity by values amount', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'Check this box, if you want that the quantity input will be updated with the sum of all add-ons values.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.0 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="required">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'Required', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'Check this option if you want the add-ons to be selected', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="required_all_options">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'All options required', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'Check this option if you want that the add-ons have all options required.', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<div class="collapsed">
				<input type="checkbox" disabled="disabled">
				<?php echo esc_html__( 'Collapsed by default', 'yith-woocommerce-product-add-ons' ); ?>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html__( 'If not selected it will take settings in Admin > YITH Plugins > Product Add-ons', 'yith-woocommerce-product-add-ons' ); // @since 1.1.3 ?>"></span>
				<strong>(<?php echo esc_html__( 'premium', 'yith-woocommerce-product-add-ons' ); ?>)</strong>
			</div>
			<?php
		}

		/**
		 * Add WC Product Data tab
		 */
		public static function add_wc_product_data_tab() {

			$current_vendor = YITH_WAPO::get_current_multivendor();
			if ( isset( $current_vendor ) && is_object( $current_vendor ) && $current_vendor->has_limited_access() && ! YITH_WAPO::is_plugin_enabled_for_vendors() ) {
				return;
			}

			add_filter( 'woocommerce_product_data_tabs', 'wapo_product_data_tab' );
			if ( ! function_exists( 'wapo_product_data_tab' ) ) {
				/**
				 * Product Data Tab
				 *
				 * @param string $product_data_tabs Product Data Tabs.
				 * @return mixed
				 */
				function wapo_product_data_tab( $product_data_tabs ) {
					$product_data_tabs['wapo-product-options'] = array(
						'label'  => __( 'Product Add-Ons', 'yith-woocommerce-product-add-ons' ),
						'target' => 'my_custom_product_data',
						'class'  => array( 'yith_wapo_tab_class' ),
					);
					return $product_data_tabs;
				}
			}

			add_action( 'woocommerce_product_data_panels', 'wapo_product_data_fields' );
			if ( ! function_exists( 'wapo_product_data_fields' ) ) {
				/**
				 * Product Data Fields
				 */
				function wapo_product_data_fields() {
					global $woocommerce, $post, $wpdb;
					?>

					<div id="my_custom_product_data" class="panel woocommerce_options_panel">

						<div class="options_group wapo-plugin" style="padding: 10px;">

							<div style="margin-bottom: 10px;">
								<label><?php echo esc_html__( 'Name', 'yith-woocommerce-product-add-ons' ); ?></label>
								<input type="text" name="wapo-group-name" id="wapo-group-name" placeholder="<?php echo esc_html__( 'Group name', 'yith-woocommerce-product-add-ons' ); ?>" style="width: 200px;">
								<input type="button" class="button button-primary wapo-add-group" value="<?php echo esc_html__( 'Add Group', 'yith-woocommerce-product-add-ons' ); ?>">
							</div>

							<ul id="sortable-list" class="sortable">
								<?php
								$rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}yith_wapo_groups WHERE FIND_IN_SET( {$post->ID} , products_id ) AND del='0' ORDER BY visibility DESC, priority ASC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
								foreach ( $rows as $key => $value ) :
									$visibility = '';
									switch ( $value->visibility ) {
										case 0:
											$visibility = __( 'hidden group.', 'yith-woocommerce-product-add-ons' );
											break;
										case 1:
											$visibility = __( 'private, visible to administrators only.', 'yith-woocommerce-product-add-ons' );
											break;
										case 9:
											$visibility = __( 'public, visible to everyone.', 'yith-woocommerce-product-add-ons' );
											break;
										default:
											$visibility = __( 'public, visible to everyone.', 'yith-woocommerce-product-add-ons' );
											break;
									}
									?>
									<li class="group-row">
										<span class="dashicons dashicons-exerpt-view" style="margin: 5px 5px 0px 0px;"></span>
										<strong class="wapo-group-edit"><?php echo esc_html__( 'Group', 'yith-woocommerce-product-add-ons' ); ?> "<?php echo esc_html( $value->name ); ?>"</strong> - <i><?php echo esc_html( $visibility ); ?></i>
										<a href="edit.php?post_type=product&page=yith_wapo_group&id=<?php echo esc_attr( $value->id ); ?>&KeepThis=true&TB_iframe=true&modal=false" onclick="return false;" class="thickbox button manage"><?php echo esc_html__( 'Manage', 'yith-woocommerce-product-add-ons' ); ?> &raquo;</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="options_group">

							<?php
							woocommerce_wp_checkbox(
								array(
									'id'            => '_wapo_disable_global',
									'wrapper_class' => 'wapo-disable-global',
									'label'         => __( 'Disable Globals', 'yith-woocommerce-product-add-ons' ),
									'description'   => __( 'Check this box if you want to disable global groups and use only the above ones!', 'yith-woocommerce-product-add-ons' ),
									'default'       => '0',
									'desc_tip'      => false,
								)
							);
							?>
						</div>

						<div class="options_group">
							<p>
								<a href="<?php echo esc_attr( site_url() ); ?>/wp-admin/edit.php?post_type=product&page=yith_wapo_groups&KeepThis=true&TB_iframe=true&modal=false&nonce=<?php echo esc_attr( wp_create_nonce( 'wapo_admin' ) ); ?>" onclick="return false;" class="thickbox button button-primary">
									<?php echo esc_html__( 'Manage all groups', 'yith-woocommerce-product-add-ons' ); ?> &raquo;
								</a>
							</p>
						</div>

					</div>

					<?php
				}
			}

			add_action( 'admin_footer', 'yit_wapo_my_action_javascript' );
			if ( ! function_exists( 'yit_wapo_my_action_javascript' ) ) {
				/**
				 * My Action JS
				 */
				function yit_wapo_my_action_javascript() {
					global $post;
					if ( is_object( $post ) ) :
						$nonce = wp_create_nonce( 'wapo_save_group' . $post->ID );
						?>
						<script type="text/javascript" >
							jQuery(document).ready(function($) {
								jQuery('.wapo-add-group').click( function(){
									var data = {
										'action': 'wapo_save_group',
										'group_name': jQuery('#wapo-group-name').val(),
										'post_id': <?php echo esc_attr( isset( $post->ID ) ? $post->ID : 0 ); ?>,
										'nonce': '<?php echo sanitize_key( $nonce ); ?>'
									};
									jQuery.post(ajaxurl, data, function(response) {
										if ( response == '::no_name' ) { alert( '<?php echo esc_html__( 'NO NAME', 'yith-woocommerce-product-add-ons' ); ?>' ); }
										else if ( response == '::db_error' ) { alert( '<?php echo esc_html__( 'DB ERROR', 'yith-woocommerce-product-add-ons' ); ?>' ); }
										else {

											response = response.split(',');
											var group_name = response[0];
											var post_id = response[1];

											var new_row = '<li class="group-row"><span class="dashicons dashicons-exerpt-view" style="margin: 5px 5px 0px 0px;"></span><strong class="wapo-group-edit"><?php echo esc_html__( 'Group', 'yith-woocommerce-product-add-ons' ); ?> "' + group_name + '</strong>" - <i><?php echo esc_html__( 'public, visible to everyone.', 'yith-woocommerce-product-add-ons' ); ?></i>';
											new_row += '<a href="edit.php?post_type=product&page=yith_wapo_group&id=' + post_id + '&KeepThis=true&TB_iframe=true&modal=false" class="thickbox button manage"> <?php echo esc_html__( 'Manage', 'yith-woocommerce-product-add-ons' ); ?> &raquo;</a></li>';

											jQuery('.wapo-plugin #sortable-list').prepend( new_row );
											jQuery('#wapo-group-name').val('');

										}
									});
								});
							});
						</script>
						<?php
					endif;
				}
			}

			add_action( 'wp_ajax_wapo_save_group', 'wapo_save_group_callback' );
			if ( ! function_exists( 'wapo_save_group_callback' ) ) {
				/**
				 * Save Group Callback
				 */
				function wapo_save_group_callback() {
					global $wpdb;
					$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
					if ( isset( $_POST['post_id'] ) && wp_verify_nonce( $nonce, 'wapo_save_group' . sanitize_key( $_POST['post_id'] ) ) && isset( $_POST['group_name'] ) && '' !== $_POST['group_name'] ) {
						$group_name        = sanitize_key( $_POST['group_name'] );
						$user_id           = get_current_user_id();
						$post_id           = isset( $_POST['post_id'] ) ? sanitize_key( $_POST['post_id'] ) : 0;
						$groups_table_name = YITH_WAPO_Group::$table_name;
						$sql               = "INSERT INTO {$wpdb->prefix}$groups_table_name ( id, name, user_id, vendor_id, products_id, products_exclude_id, categories_id, attributes_id, priority, visibility, del, reg_date )
								VALUES ('', '$group_name', '$user_id', '0', '$post_id', '', '', '', '1', '9', '0', CURRENT_TIMESTAMP)";
						$result            = $wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						echo $result ? esc_html( $group_name . ',' . $wpdb->insert_id ) : '::db_error';
					} else {
						echo '::no_name'; }
					wp_die();
				}
			}

		}

		/**
		 * Add Custom General Fields Save
		 *
		 * @param int $post_id Post ID.
		 */
		public static function woo_add_custom_general_fields_save( $post_id ) {

			// Checkbox.
			$woocommerce_checkbox = isset( $_POST['_wapo_disable_global'] ) ? 'yes' : 'no'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_post_meta( $post_id, '_wapo_disable_global', $woocommerce_checkbox );

		}

		/**
		 * Add new option
		 */
		public function add_new_option() {

			require YITH_WAPO_DIR . 'v1/templates/admin/yith-wapo-new-option.php';

			wp_die();
		}

		/**
		 *  Action Links
		 *
		 *  Add the action links to plugin admin page
		 *
		 * @param array $links Links.
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->_panel_page, true, YITH_WAPO_SLUG );
			return $links;
		}

		/**
		 * Plugin row meta
		 *
		 * @param array  $new_row_meta_args Args.
		 * @param string $plugin_meta Meta.
		 * @param string $plugin_file File.
		 * @param string $plugin_data Data.
		 * @param string $status Status.
		 * @param string $init_file File.
		 * @return mixed
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WAPO_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_WAPO_SLUG;
			}
			return $new_row_meta_args;
		}

		/**
		 * Get Dependencies Query
		 *
		 * @param object $wpdb WPDB.
		 * @param object $group Group.
		 * @param object $type Type.
		 * @param bool   $is_edit Is edit.
		 *
		 * @return string
		 */
		public static function getDependeciesQuery( $wpdb, $group, $type, $is_edit ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$dependecies_query = "SELECT id,label,depend,options FROM {$wpdb->prefix}yith_wapo_types";
			if ( ! $is_edit ) {
				$dependecies_query .= " WHERE group_id='{$group->id}' AND del='0'";
			} else {
				$dependecies_query .= " WHERE id!='{$type->id}' AND group_id='{$group->id}' AND del='0'";
			}
			$dependecies_query .= ' ORDER BY label ASC';
			return $dependecies_query;
		}


		/**
		 * Get Product Query Args
		 *
		 * @param string $product_ids Product IDs.
		 * @param string $categories_ids Categories IDs.
		 *
		 * @return array
		 */
		public static function getProductsQueryArgs( $product_ids, $categories_ids ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$atts = array(
				'orderby' => 'title',
				'order'   => 'asc',
			);

			// Default ordering args.
			$ordering_args = WC()->query->get_catalog_ordering_args( $atts['orderby'], $atts['order'] );

			$product_cat_query = array(
				'taxonomy' => 'product_cat',
				'field'    => 'ids',
				'operator' => 'IN',
			);

			if ( $categories_ids ) {

				if ( is_array( $categories_ids ) ) {
					$product_cat_query['terms'] = $categories_ids;
				} else {
					$product_cat_query['terms'] = explode( ',', $categories_ids );
				}
			}

			$args = array(
				'post_type'           => 'product',
				'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'variable', 'variable-subscription', 'grouped' ),
					),
					// $product_cat_query.
				),
				'ignore_sticky_posts' => 1,
				'post_status'         => array( 'any' ),
				'orderby'             => $ordering_args['orderby'],
				'order'               => $ordering_args['order'],
				'posts_per_page'      => -1,
			);

			if ( $product_ids ) {
				$ids              = explode( ',', $product_ids );
				$ids              = array_map( 'trim', $ids );
				$args['post__in'] = $ids;
			}

			if ( isset( $ordering_args['meta_key'] ) ) {
				$args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			}

			// Prevent WPML filter.
			$args['suppress_filters'] = true;

			return $args;

		}

		/**
		 * Echo Product Chosen List
		 *
		 * @param string $product_ids Product IDs.
		 * @param string $categories_ids Categories IDs.
		 * @param array  $options_value Options value.
		 */
		public static function echo_product_chosen_list( $product_ids, $categories_ids, $options_value = array() ) {
			$args = self::getProductsQueryArgs( $product_ids, $categories_ids );

			global $sitepress;
			if ( is_object( $sitepress ) && method_exists( $sitepress, 'get_current_language' ) ) {

				$current_lang = $sitepress->get_current_language();
				$languages    = icl_get_languages( 'skip_missing=0&orderby=code' );
				foreach ( $languages as $key => $value ) {
					$sitepress->switch_lang( $key );
					$loop = new WP_Query( $args );
					if ( $loop->have_posts() ) {
						while ( $loop->have_posts() ) {
							$loop->the_post();
							global $product;
							if ( isset( $product ) ) {
								if ( ! $product->is_purchasable() ) {
									continue; }
								$post_id    = yit_get_base_product_id( $product );
								$title      = $product->get_title();
								$variations = self::get_product_variations_chosen_list( $post_id );
								foreach ( $variations as $variation_id ) {
									$title_variation = $title . ': ' . self::get_product_variation_title( $variation_id );
									self::printSelectOptionValue( $variation_id, $options_value, $title_variation );
								}
							}
						}
					}
					wp_reset_postdata();
				}
				$sitepress->switch_lang( $current_lang );

			} else {

				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) {
						$loop->the_post();
						global $product;
						if ( isset( $product ) ) {
							if ( ! $product->is_purchasable() ) {
								continue; }
							$post_id    = yit_get_base_product_id( $product );
							$title      = $product->get_title();
							$variations = self::get_product_variations_chosen_list( $post_id );
							foreach ( $variations as $variation_id ) {
								$title_variation = $title . ': ' . self::get_product_variation_title( $variation_id );
								self::printSelectOptionValue( $variation_id, $options_value, $title_variation );
							}
						}
					}
				}
				wp_reset_postdata();

			}
		}

		/**
		 * Print Select Option Value
		 *
		 * @param int    $post_id Post ID.
		 * @param string $options_value Options value.
		 * @param string $title Title.
		 */
		private static function printSelectOptionValue( $post_id, $options_value, $title ) { //phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
			echo '<option value="' . esc_attr( $post_id ) . '" ' . ( in_array( (string) $post_id, $options_value, true ) ? 'selected="selected"' : '' ) . '>#' . esc_html( $post_id ) . ' ' . esc_html( $title ) . '</option>';
		}

		/**
		 * Get Product Variations Chosen List
		 *
		 * @param int $item_id Item ID.
		 */
		private static function get_product_variations_chosen_list( $item_id ) {
			// If variations haven't already been recovered.
			if ( ! isset( self::$variations_chosen_list[ $item_id ] ) || ! is_array( self::$variations_chosen_list[ $item_id ] ) || ! count( self::$variations_chosen_list[ $item_id ] ) > 0 ) {
				$variations = array();
				if ( $item_id ) {
					$args       = array(
						'post_type'   => 'product_variation',
						'post_status' => array( 'any' ),
						'numberposts' => apply_filters( 'yith_product_variations_chosen_list_limit', 20 ),
						'orderby'     => 'menu_order',
						'order'       => 'asc',
						'post_parent' => $item_id,
						'fields'      => 'ids',
					);
					$variations = get_posts( $args );
				}
				self::$variations_chosen_list[ $item_id ] = $variations;
			}
			return self::$variations_chosen_list[ $item_id ];
		}

		/**
		 * Get Product Variation Title
		 *
		 * @param int  $variation_id Variation ID.
		 * @param bool $print_father_title Title.
		 *
		 * @return bool
		 */
		private static function get_product_variation_title( $variation_id, $print_father_title = false ) {

			$description = '';

			if ( is_object( $variation_id ) ) {
				$variation = $variation_id;
			} else {
				$variation = wc_get_product( $variation_id );
			}

			if ( ! $variation ) {
				return false;
			}

			if ( $print_father_title ) {
				$description = $variation->get_title() . ' - ';
			}

			$attribute_description = wc_get_formatted_variation( $variation, true );

			return $description .= $attribute_description;
		}

		/**
		 * Print Chosen Dependencies
		 *
		 * @param string $rows_dep Dep.
		 * @param string $value Value.
		 */
		public static function printChosenDependencies( $rows_dep, $value ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$depsinarray = array();

			foreach ( $rows_dep as $key_dep => $value_dep ) {
				$depend_array = explode( ',', $value->depend );

				if ( in_array( $value_dep->id, $depend_array, true ) ) {
					$depsinarray[] = '#' . $value_dep->id . ' ' . $value_dep->label; }

				$options_values = maybe_unserialize( $value_dep->options );

				if ( isset( $options_values['label'] ) ) {

					foreach ( $options_values['label'] as $option_key => $option_value ) {
						$attribute_value = 'option_' . $value_dep->id . '_' . $option_key;

						if ( in_array( $attribute_value, $depend_array, true ) ) {
							$depsinarray[] = '#' . $value_dep->id . ' ' . esc_html( $value_dep->label ) . ': ' . esc_html( $option_value );
						}
					}
				}
			}

			if ( count( $depsinarray ) > 0 ) {
				echo esc_html__( 'Add-On Requirements: ', 'yith-woocommerce-product-add-ons' );
				foreach ( $depsinarray as $key_dep => $value_dep ) {
					echo '<i>' . esc_html( $value_dep ) . '</i>';
				}
			}

		}

		/**
		 * Print Chosen Dependencies Variations
		 *
		 * @param string $variations Variations.
		 */
		public static function printChosenDependenciesVariations( $variations ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			$variations_array = explode( ',', $variations );

			if ( count( $variations_array ) > 0 ) {
				echo esc_html_x( 'Variations Requirements: ', 'admin labels for add-ons list', 'yith-woocommerce-product-add-ons' );
				foreach ( $variations_array as $value_dep ) {
					$variation_title = self::get_product_variation_title( $value_dep, true );
					if ( $variation_title ) {
						echo '<i>' . esc_html( self::get_product_variation_title( $value_dep, true ) ) . '</i>';
					}
				}
			}

		}

		/**
		 * Print Products ID Select2
		 *
		 * @param string $title Title.
		 * @param string $name Name.
		 * @param string $value Value.
		 * @param bool   $is_less_than_2_7 Is.
		 */
		public static function printProductsIdSelect2( $title, $name, $value, $is_less_than_2_7 ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			?>

			<tr>
				<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $title ); ?></label></th>
				<td>
					<?php if ( $is_less_than_2_7 ) : ?>

					<input type="text" class="wc-product-search" style="width: 350px;" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>"
						data-placeholder="<?php esc_attr_e( 'Applied to...', 'yith-woocommerce-product-add-ons' ); ?>"
						data-action="woocommerce_json_search_products"
						data-multiple="true"
						data-exclude=""
						data-selected="
						<?php

						$product_ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );
						$json_ids    = array();
						foreach ( $product_ids as $product_id ) {
								$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
									$json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
							}
						}

						echo esc_attr( wp_json_encode( $json_ids ) );
						?>
						" value="<?php echo esc_attr( implode( ',', array_keys( $json_ids ) ) ); ?>" />

				<?php else : ?>

					<select class="wc-product-search" multiple="multiple" style="width: 50%;" name="<?php echo esc_attr( $name ); ?>[]" data-placeholder="<?php esc_attr_e( 'Applied to...', 'yith-woocommerce-product-add-ons' ); ?>" data-action="woocommerce_json_search_products" data-multiple="true" data-exclude="">
						<?php

						$product_ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );

						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
							}
						}
						?>
					</select>

				<?php endif ?>
				</td>
			</tr>

			<?php

		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing . '?refer_id=1030585';
		}

	}
}
