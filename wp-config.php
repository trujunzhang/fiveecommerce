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
define('DB_NAME', 'fiveecommerce');

/** MySQL database username */
define('DB_USER', 'cwfiveecommerce');

/** MySQL database password */
define('DB_PASSWORD', 'cwfiveecommerce720');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'a!21mQRV9|Pg:4=A!wyU8a[HyzcN[oo?`AV!_DU6qqFC% u$vp0Rp%xT4pZ$o-t2');
define('SECURE_AUTH_KEY',  '4Wk`]49xxIc@T@h|C4E1aLCc+z@ :yy8cFv>|C3~5Woui}S<yxj.k`<aqc{z8lq`');
define('LOGGED_IN_KEY',    'JB6]4-scymsxUH&cE Z(ts|N^js.NP90X)-fhc-9HDb,|e_$PUa2l2{#~%zmCW*3');
define('NONCE_KEY',        'AY-J8I8zyhs|H<X>S t8/McFoMrKgeM1d6=DO(HwOx&8*[+UM!#K<}Q`mz=+?>?H');
define('AUTH_SALT',        ']1-uF||j|L;7Dr3R$,J}0UaMx2>QnvUsZ<ff=_EV=.,05Z3>? g{}e%w>=F:4y6{');
define('SECURE_AUTH_SALT', 'YDZ|}Yz!+a-kj~c*hU2lAv{O @SAgKyecj(G?Owh_hv{h _9=Gb/3n2R8?_T[Oi$');
define('LOGGED_IN_SALT',   'U^hUgdjl?c5B~uJbSg,c(Yh=DRqD>zTRS1qNq?G?0G%3]wS#L,$ O`f2!=CgN==q');
define('NONCE_SALT',       'Qx(M;Md{|m`[.A+s,>Y$ u @g[/at3O?N6KZ+_~Tk+y?rmwUE5ta#pZ3L<.20ZTF');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
