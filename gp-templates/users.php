<?php
$is_admin = GP::$user->current()->admin();
gp_title(__('Users', 'glotpress'));
wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<h2><?php _e('User Management', 'glotpress'); ?></h2>
<?php
	if ( $is_admin && !defined('CUSTOM_USER_TABLE') ) {
?>
<h3><?php _e('Options', 'glotpress'); ?></h3>
<form action="" method="post" class="secondary">
	<input type="hidden" name="settings[gp_handle_settings]" value="on">
	<p>
	<input type="checkbox" name="settings[public_home]" value="on"<?php if ( $settings['public_home'] == 'on' ) echo ' checked'; ?>>
	<label for="settings[public_home]"><?php echo __('Make home page visible to everyone', 'glotpress'); ?></label>
	</p>
	<p>
	<input type="checkbox" name="settings[user_registration]" value="on"<?php if ( $settings['user_registration'] == 'on' ) echo ' checked'; ?>>
	<label for="settings[user_registration]"><?php echo __('Enable User Self-Registration', 'glotpress'); ?></label>
	</p>
	<p>
	<input type="submit" name="submit" value="<?php _e('Save', 'glotpress'); ?>" id="submit" />
	</p>
</form>
<?php
	}
?>
<h3><?php _e('User List', 'glotpress'); ?></h3>
<p>
	<a href="<?php echo gp_url_user_profile(REGLOT_NEW_USER); ?>"><?php _e('Create a New User', 'glotpress'); ?></a>
</p>

<table id="translations" class="translations clear">
	<thead>
	<tr>
		<th><?php _e('ID', 'glotpress'); ?></th>
		<th><?php _e('Login', 'glotpress'); ?></th>
		<th><?php _e('Display Name', 'glotpress'); ?></th>
<?php if ( $is_admin ) { ?>
		<th><?php _e('Email', 'glotpress'); ?></th>
		<th><?php _e('Nice Name', 'glotpress'); ?></th>
<?php } ?>
		<th><?php _e('URL', 'glotpress'); ?></th>
		<th><?php _e('Translations', 'glotpress'); ?></th>
<?php if ( $is_admin ) { ?>
		<th><?php _e('Status', 'glotpress'); ?></th>
		<th><?php _e('Actions', 'glotpress'); ?></th>
<?php } ?>
	</tr>
	</thead>
<?php
foreach( $users as $user ):
	$parity = gp_parity_factory();
?>
<tr class="preview <?php echo $parity() ?>">
	<td class="user_short">
		<?php echo $user->id; ?>
	</td>
	<td class="user_long">
		<a href="<?php echo gp_url_user_profile($user->id); ?>"><?php echo esc_html( $user->user_login ); ?></a>
		<?php if ( $user->admin() ) echo '<span class="active bubble">Admin</span>'; ?>
	</td>
	<td class="user_long">
		<?php echo esc_html( $user->display_name ); ?>
	</td>
<?php if ( $is_admin ) { ?>
	<td class="user_long">
		<?php echo esc_html( $user->user_email ); ?>
	</td>
	<td class="user_long">
		<?php echo esc_html( $user->user_nicename ); ?>
	</td>
<?php } ?>
	<td class="user_long">
		<a href="<?php echo $user->user_url; ?>" target="_blank">
		<?php echo esc_html( $user->user_url ); ?>
		</a>
	</td>
	<td class="user_short">
		<?php
		$trans_count = GP::$translation->count_by_user($user->id);
		if ( $trans_count ) {
		?>
		<a href="<?php echo gp_url_user_translations($user->id) ?>"><?php echo esc_html( GP::$translation->count_by_user($user->id) ); ?></a>
		<?php
		} else {
			echo __('None', 'glotpress');
		}
		?>
	</td>
<?php if ( $is_admin ) { ?>
	<td class="user_short">
		<?php echo esc_html( $user->user_status ); ?>
	</td>
	<td class="user_actions">
		<a href="<?php echo gp_url_user_profile($user->id); ?>" class="bubble action edit"><?php _e('Edit', 'glotpress'); ?></a>
		<a href="<?php echo gp_url('/admin/users/admin/') . $user->id; ?>" class="bubble action edit"><?php $user->admin() ? _e('Revoke Admin', 'glotpress') : _e('Make Admin', 'glotpress'); ?></a> 
		<a href="<?php echo gp_url('/admin/users/delete/') . $user->id; ?>" class="bubble action delete"><?php _e('Delete', 'glotpress'); ?></a>
	</td>
<?php } ?>
</tr>
<?php
endforeach;
if ( empty($users) ):
?>
	<tr><td colspan="<?php if ( $is_admin ) echo '9'; else echo '5'; ?>"><?php _e('No users were found!', 'glotpress'); ?></td></tr>
<?php
endif;
?>
</table>
<?php // echo gp_pagination( $page, $per_page, $total_translations_count ); ?>

<?php
gp_tmpl_footer();