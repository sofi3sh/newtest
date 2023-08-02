<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nx445983_alucat' );

/** Database username */
define( 'DB_USER', 'nx445983_alucat' );

/** Database password */
define( 'DB_PASSWORD', 'rpZ)J46a~7' );

/** Database hostname */
define( 'DB_HOST', 'nx445983.mysql.tools' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'FkNf 07Vyd*QT(6]f7LlMyS$~/hVIo`!9.GIg,p3*=[i%1eBbwdJQ|q;3Q|c|a&=' );
define( 'SECURE_AUTH_KEY',  'P0SXl>VeM4wT]P:qej+`&o[C$+ehs;rmQvQ].^i-Ue{|W$(MQRzvp*F.Yc7V;OB*' );
define( 'LOGGED_IN_KEY',    '0xC%90L>pcGylk~&fR%}QcI[>!iIZA$dJul{mxh/lF1KfW6g<+lw2qzRSQP[4&Q>' );
define( 'NONCE_KEY',        'sylEduj6`;N%!AZan+ h4&!?Hmt+qbhmrJ{r4vN|-g}yiSH~@!cYbAxiY]|6.P)D' );
define( 'AUTH_SALT',        ']R{nVwdN9k$e7@K}{wi%b%QZ@w[C~~A&SA6DR!on=&e%CNa&Mix0>@N$zIu0jCUU' );
define( 'SECURE_AUTH_SALT', '$#*o?ApVTg$/k2W*I-9]g>V7odm7M9dKtirb.yi0deSZ;$DV*6kqkb{YBsd HCc+' );
define( 'LOGGED_IN_SALT',   '8JkA(wt3&^(IV)NAX+$H#&te1OIZ01lU%?:7e]{c;3qNq]bODJ{`,LdP)CGt!P$)' );
define( 'NONCE_SALT',       'I=-YEyyLc@M.j`ZS?q 7!1X8QJa^%JKzXHk3Z&MBtT:j3:2?A]*yOMu)@Vo48*-2' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
