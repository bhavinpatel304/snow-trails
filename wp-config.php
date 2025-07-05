<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'trails' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'sX7azLi7+r))#~7+ci ^?6pu9Vb)TW.b5<x0YK.b1Sajx6jau x)xE4hA4u5-^G8' );
define( 'SECURE_AUTH_KEY',  '1+hbe8!z:Ucf%Y]0MxNpG)sr@eN~4SlQ?-:%jl &:Er>|3KGH= 2@l9VYR@ R|H3' );
define( 'LOGGED_IN_KEY',    'J~xX] #lFV-ws+<|(k}1[2^pgpz@ }Um>pYWVMJX7a/({HE%E?t-v*`syO<(o%2|' );
define( 'NONCE_KEY',        '5oSb4IcZ3N~.V5F3$Y_AuVD*x(xw%HSnzCl`>RK I#M{V@/0C04;vZg1J;T6ZIR&' );
define( 'AUTH_SALT',        '[2r1CV}*G,,ZyQo .n;1R<+>~:jR5G-O_*,adc`l<<niH(f*K$K0DU%x-7L6u(Ui' );
define( 'SECURE_AUTH_SALT', 'VEk]]C$b[orJN5*bOlI@O/8_efmt!hDlo)c=q_t:2%cp.n=E4LtHS@]wS-ArzuTj' );
define( 'LOGGED_IN_SALT',   '`d5|j[{SV)*q5}X6=PcY:QNo_3-d<GCp| .KDd0vQRi!JYM)J(pAxz#_@EyY)-0~' );
define( 'NONCE_SALT',       'r3ToDS_ z@}AhgWBKa#AdHYLGL-),yAY~9NyvX5a^00|}O7|W&64y#qU0~o|2dS.' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
