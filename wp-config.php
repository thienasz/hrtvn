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
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '}9)<8TQFqB(*@9PoG^[rIZe{*;)}Fuh{N9UKw|ArpGB:~sK)Lpbw`4V,Gr$VC#w0');
define('SECURE_AUTH_KEY',  'P,`%UUMX;7#P.knKD%MG^Q4wG/mnVA?4$k@,!7NR*s7kI0dZZQ>hrHwADNr+-a =');
define('LOGGED_IN_KEY',    'k*nywi.@={ZL}ACcImuiPvwnPhgG=11>z%=I-f_  ];(OMtNwBr8?K9-/X+M@WGD');
define('NONCE_KEY',        'Av^%Jo&e$&`v5xV|8JD|?@:rri5V+;P`%zbTx)uK&#Yr=C14TY}+D::Uq-HeO:wv');
define('AUTH_SALT',        '8zJ%U!ti-Y`oLR#SfCB:A%X:t2XB!>{wv5SuS~f+SKtKEr=%,|O$|pm$?>B9h.p0');
define('SECURE_AUTH_SALT', ':dr1.Kic3wiwz[Du//QqDr38OfstL_]{aS]$~zAor!e5?OJCrhk2qRK=gh;<BjV^');
define('LOGGED_IN_SALT',   'X5a| .^zgj40<0s3a<nSUho7vDI%4cVa%g^gVm [i~uJ+iq-Q,KOvH?1}!DuiMIb');
define('NONCE_SALT',       ',0Pq&:>Pc&pj7mA uzF|&2Uoh+Fv=hLNx^}86Z_if4VyN<E(nt0q<Wj3IRhq}$ku');

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
