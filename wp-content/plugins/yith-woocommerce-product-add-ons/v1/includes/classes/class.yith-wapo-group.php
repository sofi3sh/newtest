<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Group class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons Premium
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WAPO_Group' ) ) {

	/**
	 * WAPO Group class
	 * The class manage all the groups behaviors.
	 */
	class YITH_WAPO_Group {

		/**
		 * Table name
		 *
		 * @var string $table_name Table name.
		 */
		public static $table_name = 'yith_wapo_groups';

		/**
		 * ID
		 *
		 * @var int
		 */
		public $id = 0;

		/**
		 * Name
		 *
		 * @var string
		 */
		public $name = '';

		/**
		 * User ID
		 *
		 * @var string
		 */
		public $user_id = '';

		/**
		 * Vendor ID
		 *
		 * @var string
		 */
		public $vendor_id = '';

		/**
		 * Product ID
		 *
		 * @var string
		 */
		public $products_id = '';

		/**
		 * Products exclude ID
		 *
		 * @var string
		 */
		public $products_exclude_id = '';

		/**
		 * Categories
		 *
		 * @var string
		 */
		public $categories_id = '';

		/**
		 * Attributes
		 *
		 * @var string
		 */
		public $attributes_id = '';

		/**
		 * Priority
		 *
		 * @var int
		 */
		public $priority = 0;

		/**
		 * Visibility
		 *
		 * @var int
		 */
		public $visibility = 0;

		/**
		 * Deleted
		 *
		 * @var int
		 */
		public $del = 0;

		/**
		 * Registration date
		 *
		 * @var string
		 */
		public $reg_date = '0000-00-00 00:00:00';

		const VISIBILITY_HIDDEN = 0;
		const VISIBILITY_ADMIN  = 1;
		const VISIBILITY_PUBLIC = 9;

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * @param int $id ID.
		 */
		public function __construct( $id = 0 ) {

			global $wpdb;

			if ( $id > 0 ) {

				$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}yith_wapo_groups WHERE id='$id'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

				if ( isset( $row ) && $row->id === $id ) {

					$this->id                  = $row->id;
					$this->name                = $row->name;
					$this->user_id             = $row->user_id;
					$this->vendor_id           = $row->vendor_id;
					$this->products_id         = $row->products_id;
					$this->products_exclude_id = $row->products_exclude_id;
					$this->categories_id       = $row->categories_id;
					$this->attributes_id       = $row->attributes_id;
					$this->priority            = $row->priority;
					$this->visibility          = $row->visibility;
					$this->reg_date            = $row->reg_date;
					$this->del                 = $row->del;

				}
			}

		}

		/**
		 * Save
		 *
		 * @param int $id ID.
		 */
		public function save( $id = 0 ) {

			global $wpdb;
			$wpdb->hide_errors();

			$new_name                = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_user_id             = isset( $_POST['user_id'] ) && $_POST['user_id'] > 0 ? sanitize_key( $_POST['user_id'] ) : get_current_user_id(); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_vendor_id           = isset( $_POST['vendor_id'] ) ? sanitize_text_field( wp_unslash( $_POST['vendor_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_products_id         = isset( $_POST['products_id'] ) ? $_POST['products_id'] : ''; // phpcs:ignore
			$new_products_exclude_id = isset( $_POST['products_exclude_id'] ) ? $_POST['products_exclude_id'] : ''; // phpcs:ignore
			$new_categories_id       = isset( $_POST['categories_id'] ) ? $_POST['categories_id'] : ''; // phpcs:ignore
			$new_attributes_id       = isset( $_POST['attributes_id'] ) ? $_POST['attributes_id'] : ''; // phpcs:ignore
			$new_priority            = isset( $_POST['priority'] ) ? sanitize_text_field( wp_unslash( $_POST['priority'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_visibility          = isset( $_POST['visibility'] ) ? sanitize_text_field( wp_unslash( $_POST['visibility'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$new_del                 = isset( $_POST['del'] ) ?? 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// Multi Vendor.
			if ( isset( $vendor_user ) && is_object( $vendor_user ) ) {
				$new_vendor_id = $vendor_user->id;
			}

			if ( is_array( $new_products_id ) ) {
				$new_products_id = implode( ',', $new_products_id );
			}

			if ( is_array( $new_products_exclude_id ) ) {
				$new_products_exclude_id = implode( ',', $new_products_exclude_id );
			}

			$new_categories_id = is_array( $new_categories_id ) ? implode( ',', $new_categories_id ) : $new_categories_id;

			if ( $id > 0 ) {

				$sql = "UPDATE {$wpdb->prefix}yith_wapo_groups SET
						name				= '$new_name',
						user_id				= '$new_user_id',
						vendor_id			= '$new_vendor_id',
						products_id			= '$new_products_id',
						products_exclude_id	= '$new_products_exclude_id',
						categories_id		= '$new_categories_id',
						attributes_id		= '$new_attributes_id',
						priority			= '$new_priority',
						visibility			= '$new_visibility',
						del					= '$new_del'
						WHERE id='$id'";

			} else {

				$sql = "INSERT INTO {$wpdb->prefix}yith_wapo_groups (
						id,
						name,
						user_id,
						vendor_id,
						products_id,
						products_exclude_id,
						categories_id,
						attributes_id,
						priority,
						visibility,
						reg_date,
						del
					) VALUES (
						'',
						'$new_name',
						'$new_user_id',
						'$new_vendor_id',
						'$new_products_id',
						'$new_products_exclude_id',
						'$new_categories_id',
						'$new_attributes_id',
						'$new_priority',
						'$new_visibility',
						CURRENT_TIMESTAMP,
						'0'
					)";

			}

			$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		}

		/**
		 * Insert
		 */
		public function insert() {
			$this->save(); }

		/**
		 * Update
		 *
		 * @param int $id ID.
		 */
		public function update( $id ) {
			$this->save( $id ); }

		/**
		 * Delete
		 *
		 * @param int $id ID.
		 */
		public function delete( $id ) {
			global $wpdb;
			$wpdb->hide_errors();
			$sql = "UPDATE {$wpdb->prefix}yith_wapo_groups SET del='1' WHERE id='$id'";
			$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		}

		/**
		 * Duplicate a group
		 * This function will duplicate a group of add-ons with all related options.
		 *
		 * @since 1.5.0
		 * @author Your Inspiration Themes
		 */
		public function duplicate() {
			global $wpdb;

			// Create duplicated group.
			$new_name          = $this->name . ' (copy)';
			$groups_table_name = self::$table_name;
			$sql               = "INSERT INTO {$wpdb->prefix}$groups_table_name (name, user_id, vendor_id, products_id, products_exclude_id, categories_id, attributes_id, priority, visibility, del, reg_date)
					VALUES ('$new_name', '$this->user_id', '$this->vendor_id', '$this->products_id', '$this->products_exclude_id', '$this->categories_id', '$this->attributes_id', '$this->priority', '$this->visibility', '$this->del', CURRENT_TIMESTAMP)";
			$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

			// Duplicated group id.
			$group_id = $wpdb->insert_id;

			// Get all related add-ons.
			$addons = yith_wapo_get_addons_by_group_id( $this->id );
			foreach ( $addons as $key => $value ) {
				$value->duplicate( $group_id );
			}

		}

		/**
		 * Create tables
		 */
		public static function create_tables() {

			/**
			 * Check if dbDelta() exists
			 */
			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}

			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$wpdb->prefix}yith_wapo_groups (
						id					BIGINT(20) NOT NULL AUTO_INCREMENT,
						name				VARCHAR(250),
						user_id				BIGINT(20),
						vendor_id			BIGINT(20),
						products_id			TEXT,
						products_exclude_id	TEXT,
						categories_id		TEXT,
						attributes_id		VARCHAR(250),
						priority			INT(2),
						visibility			INT(1),
						reg_date			TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
						del					TINYINT(1) NOT NULL DEFAULT '0',
						PRIMARY KEY (id)
					) $charset_collate;";

			$wpdb->hide_errors();
			$wpdb->suppress_errors( true );
			$wpdb->show_errors( false );
			dbDelta( $sql );

		}

		/**
		 * Print Options Vendor List
		 *
		 * @param int $selected_vendor_id Selected vendor ID.
		 */
		public static function printOptionsVendorList( $selected_vendor_id ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			if ( YITH_WAPO::$is_vendor_installed ) {

				$vendors = YITH_Vendors()->get_vendors();

				foreach ( $vendors as $single_vendor ) {
					echo '<option  value=' . esc_attr( $single_vendor->id ) . ' ' . ( $selected_vendor_id === $single_vendor->id ? 'selected' : '' ) . '>' . wp_kses_post( $single_vendor->name ) . '</option>';
				}
			}

		}

	}

}
