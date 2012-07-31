<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'nextstepmaine_wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1309piCa');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'gXd/yqubFT4`jD 46cRG$sen!^bihjPw%pb,6L[C|Tor3JvdN)|CAqKkXwg*3w7[');
define('SECURE_AUTH_KEY',  'g/9x!7YeL0InOao/608SyDyZ)bq/B5jOkW3g|[PHYo7QQ.k/jf5cuk$DFvdby_)&');
define('LOGGED_IN_KEY',    's6Edo(b(8wc&8qIAw%/C)ow|:yZC*3j^tECC*&uGTb_(y7PKQf6|q9ou7Z?5Hv;H');
define('NONCE_KEY',        ',/ gal&>F yccAwJp^FCJ^6M:1=fmd0N@fMq71v|N}.x?Ppnj=3TVQBM6+x|S{Dz');
define('AUTH_SALT',        '!%DA@n%`9rBY[2{g*u*|,*N(JH0&0*D~o}o~/@+5qY&{2p#RW >uLQ-@-hMQG2-Q');
define('SECURE_AUTH_SALT', 'MK`*%7r548h$A3APeGcX.4S?3PAo}1<TFT^1Qf~@7}4?DlCM`1N9i-mVpSO7Ij7 ');
define('LOGGED_IN_SALT',   'Sp&8-93g4ttc;(_-`%&DjEPIe`x^8|j Wm+}tKz6>?gsV7f;nXQmXcn h?Jn1<cg');
define('NONCE_SALT',       ')eQHR} +S%C.xTMzQI+epO5LzQ?V-Z+vrnxYCz2K@v1=PGMkLS<p/rM`_|hc5E+,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'nsm_wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
