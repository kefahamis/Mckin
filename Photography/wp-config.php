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
define('DB_NAME', 'skillhub_wp444');

/** MySQL database username */
define('DB_USER', 'skillhub_wp444');

/** MySQL database password */
define('DB_PASSWORD', 'pT35!C96@S');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'WP_MEMORY_LIMIT', '256M' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ojr4tw7t53qbsuzu6h1nysqf2bd4a3htf4jfpfna8seoxlcnaoncdythgezxtl92');
define('SECURE_AUTH_KEY',  'ggrfaq8xrchlny09p0v4qhtdyjxe92bp3xjwhdbzwqx4szsrk8gqbaczgum2myjw');
define('LOGGED_IN_KEY',    'igk7d5wsq0te8zcagpu0oljqmjmgetqx40ifyyel62xxgpalni1wr9dt2zow7j28');
define('NONCE_KEY',        'ebo2pwmag6lgdndzurwdy4lri5ll51fgh8mkxzj4iudv01qkjwfantm33fyqc94v');
define('AUTH_SALT',        'qbojdattxofot3a5hw6twngrnj0tm5gk8ilv3d6ad9z5855b5utfmu40wykbnm67');
define('SECURE_AUTH_SALT', 'cgnob4yq9q0asyxfmkr7dqvhgq7q8bk3yfbvwnsy9i1uipn2fg74i8pwgliqn8mf');
define('LOGGED_IN_SALT',   'ccihueuisovg6wwdhamiwv3rgwdxzz3k0qk6wodcw1jiblkkls4tcg0vytowcttd');
define('NONCE_SALT',       '9tp6fdmeysrt6nvuqflsha0bxnlofixzzqx2rauavzsbavqrhm3ocbwldfebztpe');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpwx_';

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
