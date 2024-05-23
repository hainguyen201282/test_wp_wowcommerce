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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test_wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost:8889' );

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
define( 'AUTH_KEY',         'OFKUPZXl1Tyz~iN3aH**l3SLXq~,s)9xRg=3&vVPLVc3IXP@Pqp.ix/t~E942sBX' );
define( 'SECURE_AUTH_KEY',  '.r{|Hp;9^*9a3PcW4eTPT&8WSO*za{Iad>+Que1Vc@wJ,Z7z;qDjc&;b{|Tr`RC7' );
define( 'LOGGED_IN_KEY',    'BIcMe&IWTk`4(z` f1HAYv%`%,dJ/.H2[R3p+j4&)VHX$Lm&Gk5RL~Z0<E)*g9/r' );
define( 'NONCE_KEY',        'O|c__Mq`L%#Hq-%&ZHK?k>#yF!S@$L[N(/e2et*AH%t3:nL&i0?:2lqv$c(%PQ1;' );
define( 'AUTH_SALT',        '*8J$XqoW-%JE}TM}L7e!=aTS)@n7d{mD::JxY`IZo%h$o%}PwG5-r?8.drW%nmT}' );
define( 'SECURE_AUTH_SALT', '/_:]%2rzOkFzDtuAbo{B5*u~flv4Umz1i`qa]C2;gFvX=.EtiStW8 (/F&/I,M;P' );
define( 'LOGGED_IN_SALT',   'AuRr;ePlUVk[6:ZqBpL+Cl(`}?yG~A8jsTLY(fSTlh&L*L!xOzB4Er!l+&Ko8P/V' );
define( 'NONCE_SALT',       '$O=?Bp}A3>h:9=USaE]v+j%vkWyf$%IK5F(A]AOj?hxAAG$mjyX18D!/i#f{%6@V' );

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
