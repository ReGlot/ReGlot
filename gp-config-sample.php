<?php
/* MySQL settings - You can get this info from your web host */
/* Do not insert one line (//) comments into this file - they may be replaced by the installer */

/** The name of the database for bbPress */
define('GPDB_NAME', '<<GPDB_NAME>>');

/** MySQL database username */
define('GPDB_USER', '<<GPDB_USER>>');

/** MySQL database password */
define('GPDB_PASSWORD', '<<GPDB_PASSWORD>>');

/** MySQL hostname */
define('GPDB_HOST', '<<GPDB_HOST>>');

/** Database Charset to use in creating database tables. */
define('GPDB_CHARSET', '<<GPDB_CHARSET>>');

/** The Database Collate type. Don't change this if in doubt. */
define('GPDB_COLLATE', '<<GPDB_COLLATE>>');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 0.1
 */
define('GP_AUTH_KEY',         '<<GP_AUTH_KEY>>');
define('GP_SECURE_AUTH_KEY',  '<<GP_SECURE_AUTH_KEY>>');
define('GP_LOGGED_IN_KEY',    '<<GP_LOGGED_IN_KEY>>');
define('GP_NONCE_KEY',        '<<GP_NONCE_KEY>>');
/**#@-*/

/**
 * GlotPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to languages/. For example, install
 * fr_FR.mo to languages/ and set GP_LANG to 'fr_FR' to enable French
 * language support.
 */
define('GP_LANG', '<<GP_LANG>>');

/**
 * Custom users and usermate tables for integration with WordPress user system
 * 
 * You might want to delete your current permissions, since they will point to different
 * users in the custom table. You can use `php scripts/wipe-permissions.php` for that.
 * 
 * If you start with fresh permissions, you can add admins via `php scripts/add-admin.php`
 */
//define('CUSTOM_USER_TABLE', '<<CUSTOM_USER_TABLE>>');
//define('CUSTOM_USER_META_TABLE', '<<CUSTOM_USER_META_TABLE>>');

/**
 * GlotPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$gp_table_prefix = '<<gp_table_prefix>>';