<?php
/**
 * WAPO Block Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Block' ) ) {

	/**
	 *  Block class.
	 *  The class manage all the Block behaviors.
	 */
	class YITH_WAPO_Block {

		/**
		 *  ID
		 *
		 * @var int
		 */
		public $id = 0;

		/**
		 *  Settings
		 *
		 * @var array
		 */
		public $settings = array();

		/**
		 *  Visibility
		 *
		 * @var array
		 */
		public $visibility = 1;

		/**
		 *  Name
		 *
		 * @var string
		 */
		public $name = '';

		/**
		 *  Priority
		 *
		 * @var int
		 */
		public $priority = 0;

		/**
		 * Rules
		 *
		 * @var array
		 */
		public $rules = array();

		/**
		 * Constructor
		 *
		 * @param int $id Block ID.
		 */
		public function __construct( $id ) {

			global $wpdb;

			if ( $id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE id='$id'";
				$row   = $wpdb->get_row( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

				if ( isset( $row ) && $row->id === $id ) {

					$this->id         = $row->id;
					$this->user_id    = $row->user_id;
					$this->vendor_id  = $row->vendor_id;
					$this->settings   = @unserialize( $row->settings ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize, WordPress.PHP.NoSilencedErrors.Discouraged
					$this->priority   = $row->priority;
					$this->visibility = $row->visibility;

					// Settings.
					$this->name  = $this->settings['name'] ?? '';
					$this->rules = $this->settings['rules'] ?? array();

				}
			}

		}

		/**
		 * Get Setting
		 *
		 * @param string $option Option.
		 * @param string $default Default.
		 */
		public function get_setting( $option, $default = '' ) {
			return isset( $this->settings[ $option ] ) ? $this->settings[ $option ] : $default;
		}

		/**
		 * Get Rule
		 *
		 * @param string $name Name.
		 * @param string $default Default.
		 */
		public function get_rule( $name, $default = '' ) {
			return isset( $this->rules[ $name ] ) ? $this->rules[ $name ] : $default;
		}

	}

}
