<?php
/**
 * WAPO Install Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Install' ) ) {

	/**
	 *  YITH_WAPO Install Class
	 */
	class YITH_WAPO_Install {

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

			$yith_wapo_db_version         = apply_filters( 'yith_wapo_db_version', get_option( 'yith_wapo_db_version' ) );
			$yith_wapo_db_migration       = apply_filters( 'yith_wapo_db_migration', get_option( 'yith_wapo_db_migration', 0 ) );
			$yith_wapo_force_db_migration = isset( $_GET['yith_wapo_force_db_migration'] ) && 1 === $_GET['yith_wapo_force_db_migration']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( YITH_WAPO_DB_VERSION !== $yith_wapo_db_version ) {
				$this->db_check();
				update_option( 'yith_wapo_db_version', YITH_WAPO_DB_VERSION );
			}

			// Check migration.
			if ( yith_wapo_previous_version_exists() && ( ! $yith_wapo_db_migration || $yith_wapo_force_db_migration ) ) {
				update_option( 'yith_wapo_db_migration', 1 );
				update_option( 'yith_wapo_v2', 'no' );
				$this->migration();
			}

		}

		/**
		 * DB Check
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function db_check() {
			global $wpdb;

			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}

			$wpdb->hide_errors();
			$wpdb->suppress_errors( true );
			$wpdb->show_errors( false );

			$charset_collate = $wpdb->get_charset_collate();

			$sql_blocks = "CREATE TABLE {$wpdb->prefix}yith_wapo_blocks (
						id					INT(3) NOT NULL AUTO_INCREMENT,
						user_id				BIGINT(20),
						vendor_id			BIGINT(20),
						settings			LONGTEXT,
						priority			DECIMAL(9,5),
						visibility			INT(1),
						creation_date		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						last_update			TIMESTAMP,
						deleted				TINYINT(1) NOT NULL DEFAULT '0',
						PRIMARY KEY (id)
					) $charset_collate;";

			$sql_addons = "CREATE TABLE {$wpdb->prefix}yith_wapo_addons (
						id					INT(4) NOT NULL AUTO_INCREMENT,
						block_id			INT(3),
						settings			LONGTEXT,
						options				LONGTEXT,
						priority			DECIMAL(9,5),
						visibility			INT(1),
						creation_date		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						last_update			TIMESTAMP,
						deleted				TINYINT(1) NOT NULL DEFAULT '0',
						PRIMARY KEY (id)
					) $charset_collate;";

			dbDelta( $sql_blocks );
			dbDelta( $sql_addons );
		}

		/**
		 * Migration from 1.x version
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function migration() {
			global $wpdb;
			$query            = "SELECT * FROM {$wpdb->prefix}yith_wapo_groups WHERE del='0' ORDER BY priority, name ASC";
			$old_groups_array = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

			foreach ( $old_groups_array as $key => $block ) {

				$import_products_id         = strpos( $block->products_id, ',' ) !== false ? explode( ',', $block->products_id ) : $block->products_id;
				$import_categories_id       = strpos( $block->categories_id, ',' ) !== false ? explode( ',', $block->categories_id ) : $block->categories_id;
				$import_products_exclude_id = strpos( $block->products_exclude_id, ',' ) !== false ? explode( ',', $block->products_exclude_id ) : $block->products_exclude_id;

				$request['block_id']                             = 'new';
				$request['block_user_id']                        = empty( $block->user_id ) ? 0 : $block->user_id;
				$request['block_vendor_id']                      = empty( $block->vendor_id ) ? 0 : $block->vendor_id;
				$request['block_name']                           = empty( $block->name ) ? '' : $block->name;
				$request['block_rule_show_in']                   = empty( $block->products_id ) ? 'all' : 'products';
				$request['block_rule_show_in_products']          = empty( $block->products_id ) ? '' : $import_products_id;
				$request['block_rule_show_in_categories']        = empty( $block->categories_id ) ? '' : $import_categories_id;
				$request['block_rule_exclude_products_products'] = empty( $block->products_exclude_id ) ? '' : $import_products_exclude_id;
				$request['block_rule_show_to']                   = 'all';
				$request['block_priority']                       = empty( $block->priority ) ? '' : $block->priority;
				$block_id                                        = YITH_WAPO()->save_block( $request );

				$old_addons_query = "SELECT * FROM {$wpdb->prefix}yith_wapo_types WHERE group_id='$block->id' AND del='0' ORDER BY priority ASC";
				$old_addons_array = $wpdb->get_results( $old_addons_query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				foreach ( $old_addons_array as $addon_key => $addon ) {

					// General.
					$request['addon_id'] = 'new';
					$request['block_id'] = $block_id;
					if ( 'labels' === $addon->type ) {
						$request['addon_type'] = 'label';
					} elseif ( 'multiple_labels' === $addon->type ) {
						$request['addon_type'] = 'label';
					} else {
						$request['addon_type'] = $addon->type;
					}

					// Display options.
					$request['addon_title']             = $addon->label;
					$request['addon_description']       = $addon->description;
					$request['addon_show_image']        = ( '' !== $addon->image ? 'yes' : 'no' );
					$request['addon_image']             = $addon->image;
					$request['addon_image_replacement'] = '';
					$request['addon_show_as_toggle']    = 'no';

					// Conditional logic.
					$request['addon_enable_rules']                 = '';
					$request['addon_conditional_logic_display']    = '';
					$request['addon_conditional_logic_display_if'] = '';
					$request['addon_conditional_rule_addon']       = '';
					$request['addon_conditional_rule_addon_is']    = '';

					// Advanced options.
					$request['addon_first_options_selected'] = ''; // yes/no.
					$request['addon_first_free_options']     = '';
					$request['addon_enable_min_max']         = '';
					$request['addon_min_max_rule']           = '';
					$request['addon_min_max_value']          = '';

					$request['options'] = array();
					$options            = maybe_unserialize( $addon->options );
					if ( isset( $options['label'] ) && is_array( $options['label'] ) ) {
						foreach ( $options['label'] as $index => $value ) {
							$request['options']['label'][]        = $options['label'][ $index ];
							$request['options']['tooltip'][]      = $options['tooltip'][ $index ];
							$request['options']['description'][]  = $options['description'][ $index ];
							$request['options']['price_method'][] = 'increase';
							$request['options']['price'][]        = $options['price'][ $index ];
							$request['options']['price_type'][]   = $options['type'][ $index ];
							$request['options']['default'][]      = $options['default'][ $index ] ?? '';
							$request['options']['required'][]     = $options['required'][ $index ] ?? '';
							if ( isset( $options['image'][ $index ] ) && '' !== $options['image'][ $index ] ) { // Upload addon image if available.
								$request['options']['show_image'][] = 'yes';
								$request['options']['image'][]      = $options['image'][ $index ];
							}
						}
					}

					// $request['wapo_action'] = 'save-addon'; phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					YITH_WAPO()->save_addon( $request );

				}
			}
		}

	}
}

/**
 * Unique access to instance of YITH_WAPO_Install class
 *
 * @return YITH_WAPO_Install
 */
function YITH_WAPO_Install() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_WAPO_Install::get_instance();
}
