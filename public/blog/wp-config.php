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

define('FORCE_SSL_ADMIN', true);

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
define("DB_NAME", trim($url["path"], "/"));
define("DB_USER", trim($url["user"]));
define("DB_PASSWORD", trim($url["pass"]));
define("DB_HOST", trim($url["host"]));
define("DB_CHARSET", "utf8");

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Ycgqq1pbDQ)@CfwC]DjcAUsL%RXAYzJwPc)u A0<:5c$iEuq>++Qf{8z( n8he7R');
define('SECURE_AUTH_KEY',  'b+%_j(A#c%k=e;!6~GK<r6D4v1s)j6S^e?Ygd{yIiUJizJ]4*1in+]xjI$<l8>hX');
define('LOGGED_IN_KEY',    'QiQ[LO560TTidQb<aSOf<;/rq Q:,2%G; n=dPKG?H]v7*f, V:J.qS}uTa&?V.$');
define('NONCE_KEY',        'W)cqY,]=)TOHc(BQ|(e19pxHS`G[@A~G^ye+B Y4WF ;6j&=>T|h|PfjA#&%%!4+');
define('AUTH_SALT',        '2yy6BtW}@V0?0=eB9][a2spO?mv(cxLQnQ2r3Xx %L|er%VnE1AoO~#b-j2_n<*B');
define('SECURE_AUTH_SALT', 'wp{p,TI%6YSZ^$^8~SbDs;VS;_i#Jtk6<MmxdI5MXtS3vOq>2}W/g5XrQi15E6F)');
define('LOGGED_IN_SALT',   'd@Ez LRFg]T`jJkc8:/KGM73C^_EIY&[WwH`mno<hg*G]Rd`N>o)F>[o6|[|%*fp');
define('NONCE_SALT',       'lhLB$lw(W~cY|yrLH[N`Kk{>z 5_N,r=6!GvdEa35[Hwv9sAb7}_4uHD/Z,6PCk<');

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

