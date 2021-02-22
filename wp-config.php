<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'eagledb' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lwln4o.:nqd^56pm? Adghs.<*o0pCQ-s_SK$allMWnv1x7F;fY6<t)k5UgI=rTd' );
define( 'SECURE_AUTH_KEY',  '!-Mlr~fWz6l1gSndGMhb*CEf1 xu~t0$#+GlT^?Ys7rT_Qotxv*tX|0Z(0Vw7Vb5' );
define( 'LOGGED_IN_KEY',    'L4|mSA5%RA*3Ah`6-ATnVD}0qQw39c00HFyTTkBgIh9JW#lBqurs3~tH5H,gQhH-' );
define( 'NONCE_KEY',        '1$a[!L5:+YBxK)h{:Bb8isxH.TBkZ(?9+a$a1d~-uMW;f#:xPNTQ@J2MfTAc;54>' );
define( 'AUTH_SALT',        ';$n*$.`!- On2H_6A~:/Z2lS{X;0;pz=/Qf9i+WiV ;`E&#E+Ac]zTOo1QXxnUeU' );
define( 'SECURE_AUTH_SALT', 'qf?fr|_9{D4mMmD^g$B4:aE%D|8RSs;;3>Xlkf>@n1CT0Et[0feD^L&<|NEeH|9-' );
define( 'LOGGED_IN_SALT',   '46XCX)$0y~TUB_dAo6Y}lulm;OKQGF|:.>^ko3PC]S2TU=DXa?b}rhdCoDONcp,G' );
define( 'NONCE_SALT',       'L?mWip1)4TENhp5#T):u[I#JFiGSaeS)f}6}%Gu~C##;#c~,Yj)KE: Q=Q0.~h+Q' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'eagle_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
// define( 'WP_DEBUG', true );
define( 'WP_DEBUG', true );
define('WP_ALLOW_REPAIR', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
