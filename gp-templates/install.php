<?php
gp_title(__('Install &lt; GlotPress'));
gp_breadcrumb(array(__('Install')));
wp_enqueue_script('install');

// include, do not require, as it's ok if file is not there
include_once 'gp-config.php';

// these value are there, whether from previous config or defaults
$config_defaults = array(
	'GPDB_HOST' => defined('GPDB_HOST') ? GPDB_HOST : 'localhost',
	'GPDB_CHARSET' => defined('GPDB_CHARSET') ? GPDB_HOST : 'utf8',
	'GPDB_COLLATE' => defined('GPDB_COLLATE') ? GPDB_HOST : 'utf8_unicode_ci',
	'gp_table_prefix' => isset($gp_table_prefix) ? $gp_table_prefix : 'gp_',
	'CUSTOM_USER_TABLE' => defined('CUSTOM_USER_TABLE') ? CUSTOM_USER_TABLE : 'wp_users',
	'CUSTOM_USER_META_TABLE' => defined('CUSTOM_USER_META_TABLE') ? CUSTOM_USER_META_TABLE : 'wp_usermeta'
);

// these values are only added if previous config was present
if ( defined('GPDB_NAME') ) $config_defaults['GPDB_NAME'] = GPDB_NAME;
if ( defined('GPDB_USER') ) $config_defaults['GPDB_USER'] = GPDB_USER;
if ( defined('GPDB_PASSWORD') ) $config_defaults['GPDB_PASSWORD'] = GPDB_PASSWORD;
if ( defined('GP_AUTH_KEY') ) $config_defaults['GP_AUTH_KEY'] = GP_AUTH_KEY;
if ( defined('GP_SECURE_AUTH_KEY') ) $config_defaults['GP_SECURE_AUTH_KEY'] = GP_SECURE_AUTH_KEY;
if ( defined('GP_LOGGED_IN_KEY') ) $config_defaults['GP_LOGGED_IN_KEY'] = GP_LOGGED_IN_KEY;
if ( defined('GP_NONCE_KEY') ) $config_defaults['GP_NONCE_KEY'] = GP_NONCE_KEY;
if ( defined('CUSTOM_USER_TABLE') ) $config_defaults['gp_enable_wordpress_users'] = 'on';
if ( defined('GP_NONCE_KEY') ) $config_defaults['GP_NONCE_KEY'] = GP_NONCE_KEY;
if ( defined('GP_NONCE_KEY') ) $config_defaults['GP_NONCE_KEY'] = GP_NONCE_KEY;

$config = array_merge($config_defaults, $config);

$wordpress_enabled = ($config['gp_enable_wordpress_users'] == 'on');

gp_tmpl_header();
?>
<h2><?php echo wptexturize(__('Edit your installation options')); ?></h2>
<form action="" method="post">

<dl>
	<dt><h3><?php echo __('Database Configuration'); ?></h3></dt>
	<dd>
		<label for="config[GPDB_NAME]"><?php _e('Database Name');  ?></label>
		<input type="text" name="config[GPDB_NAME]" value="<?php echo $config['GPDB_NAME']; ?>" id="config[GPDB_NAME]"><br/>
		<label for="config[GPDB_USER]"><?php _e('Username');  ?></label>
		<input type="text" name="config[GPDB_USER]" value="<?php echo $config['GPDB_USER']; ?>" id="config[GPDB_USER]"><br/>
		<label for="config[GPDB_PASSWORD]"><?php _e('Password');  ?></label>
		<input type="password" name="config[GPDB_PASSWORD]" value="<?php echo $config['GPDB_PASSWORD']; ?>" id="config[GPDB_PASSWORD]"><br/>
		<label for="config[GPDB_HOST]"><?php _e('Server Host');  ?></label>
		<input type="text" name="config[GPDB_HOST]" value="<?php echo $config['GPDB_HOST']; ?>" id="config[GPDB_HOST]"><br/>
		<label for="config[GPDB_CHARSET]"><?php _e('Default Character Set');  ?></label>
		<input type="text" name="config[GPDB_CHARSET]" value="<?php echo $config['GPDB_CHARSET']; ?>" id="config[GPDB_CHARSET]"><br/>
		<label for="config[GPDB_COLLATE]"><?php _e('Default Collation');  ?></label>
		<input type="text" name="config[GPDB_COLLATE]" value="<?php echo $config['GPDB_COLLATE']; ?>" id="config[GPDB_COLLATE]"><br/>
		<label for="config[gp_table_prefix]"><?php _e('GlotPress Table Prefix');  ?></label>
		<input type="text" name="config[gp_table_prefix]" value="<?php echo $config['gp_table_prefix']; ?>" id="config[gp_table_prefix]"><br/>
	</dd>
<br/>
	<dt><h3><?php echo __('Authentication Unique Keys'); ?></h3></dt>
	<dd>
		<label for="config[GP_AUTH_KEY]">GP_AUTH_KEY</label>
		<input type="text" name="config[GP_AUTH_KEY]" value="<?php echo $config['GP_AUTH_KEY']; ?>" id="config[GP_AUTH_KEY]"><br/>
		<label for="config[GP_SECURE_AUTH_KEY]">GP_SECURE_AUTH_KEY</label>
		<input type="text" name="config[GP_SECURE_AUTH_KEY]" value="<?php echo $config['GP_SECURE_AUTH_KEY']; ?>" id="config[GP_SECURE_AUTH_KEY]"><br/>
		<label for="config[GP_LOGGED_IN_KEY]">GP_LOGGED_IN_KEY</label>
		<input type="text" name="config[GP_LOGGED_IN_KEY]" value="<?php echo $config['GP_LOGGED_IN_KEY']; ?>" id="config[GP_LOGGED_IN_KEY]"><br/>
		<label for="config[GP_NONCE_KEY]">GP_NONCE_KEY</label>
		<input type="text" name="config[GP_NONCE_KEY]" value="<?php echo $config['GP_NONCE_KEY']; ?>" id="config[GP_NONCE_KEY]"><br/>
		<small><?php _e('You can generate these using the <a href="https://api.wordpress.org/secret-key/1.1/" target="_blank">WordPress.org secret-key service</a> page'); ?></small>
	</dd>
<br/>
	<dt>
		<h3><input type="checkbox" name="config[gp_enable_wordpress_users]" value="on"<?php if ( $wordpress_enabled ) echo ' checked'; ?> id="check_enable_wordpress_users">
		<label for="config[gp_enable_wordpress_users]"><?php echo __('Enable WordPress User Base Integration'); ?></label></h3>
	</dt>
	<dd id="div_enable_wordpress_users"<?php if ( !$wordpress_enabled ) echo ' style="display: none;"'; ?>>
		<label for="config[CUSTOM_USER_TABLE]">User Table Name</label>
		<input type="text" name="config[CUSTOM_USER_TABLE]" value="<?php echo $config['CUSTOM_USER_TABLE']; ?>" id="config[CUSTOM_USER_TABLE]"><br/>
		<label for="config[CUSTOM_USER_META_TABLE]">User Meta Table Name</label>
		<input type="text" name="config[CUSTOM_USER_META_TABLE]" value="<?php echo $config['CUSTOM_USER_META_TABLE']; ?>" id="config[CUSTOM_USER_META_TABLE]"><br/>
	</dd>
<br/>
	<dt><h3><?php echo __('Other Options'); ?></h3></dt>
	<dd>
		<label for="config[GP_LANG]"><?php echo __('GlotPress Language'); ?></label>
		<input type="text" name="config[GP_LANG]" value="" id="config[CUSTOM_USER_TABLE]"><br/>
		<small><?php _e('A corresponding MO file for the chosen language must be installed to <i>languages/</i>. Leave blank for English.'); ?></small>
	</dd>
</dl>

<?php echo gp_js_focus_on('config[GPDB_NAME]'); ?>

	<p>
		<input type="submit" name="submit" value="<?php echo esc_attr(__('Install')); ?>" id="submit" />
	</p>
</form>

<?php gp_tmpl_footer(); ?>