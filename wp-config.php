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
define('DB_NAME', 'sm');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'GsZfdDlWDQRvmv8a5vNTHI1wRNBWtmSg8JaG3ypYVJZ35aMKmNdR9STH5NO7f8wR');
define('SECURE_AUTH_KEY',  'aj7uLXbkkX1n7pxJZ4qNTAf3tqFSrwWJ56hA8kgPfcPSljxaZreyJKI0rfNI2E9U');
define('LOGGED_IN_KEY',    'cqJFshvroNg616983ubfCyx1Cm7YJ43oFA9q6TLmvwLIRoHp2wyJmOFlbtHUduiC');
define('NONCE_KEY',        'EykXw7O0XQQdNT5YL7urecsaOLZAzGwaBJkr7fVfIod0FtkorXPKXtY8h7M5O0tE');
define('AUTH_SALT',        'vt02qIO2jinpCHvcjd4gcNTDfxPZZLsCu7tdre8YpcIiKikjJ8qPrIJeFbAnYGuw');
define('SECURE_AUTH_SALT', 'TsSGsWqK2OgcpxwuMdHTUrxjNqQ8yGcM1ZJbU3BctkQN8LSUPmi6CQ2Yr7qTbdh4');
define('LOGGED_IN_SALT',   'LqEpyHbkpxvo6kD9VAIRVwwt6tLcB5loMQC4AEHOSfTdHpp2yAsw0CARKJaRQ4jf');
define('NONCE_SALT',       'U8vBtUDAOFssN0JKr3fJ4xtctb3sNBdWhQ5zw4XrgjCPDBIh6nYGcnExCiMaC6uK');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
