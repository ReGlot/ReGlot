<?php
gp_title(__('User Management'));
wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<h2><?php _e('User Management'); ?></h2>
<?php
	if ( !defined('CUSTOM_USER_TABLE') ) {
?>
<h3><?php _e('Options'); ?></h3>
<form action="" method="post" class="secondary">
	<p>
	<input type="checkbox" name="options[gp_enable_user_reg]" value="on"<?php if ( $gp_enable_user_reg ) echo ' checked'; ?>>
	<label for="config[gp_enable_user_reg]"><?php echo __('Enable User Self-Registration'); ?></label>
	</p>
	<p>
	<input type="submit" name="submit" value="<?php _e('Save'); ?>" id="submit" />
	</p>
</form>
<?php
	}
?>
<h3><?php _e('User List'); ?></h3>
<p>
	<a href="<?php echo gp_url('/admin/users/new/'); ?>"><?php _e('Create New User'); ?></a>
</p>

<table id="translations" class="translations clear">
	<thead>
	<tr>
		<th><?php _e('ID'); ?></th>
		<th><?php _e('Login'); ?></th>
		<th><?php _e('Display Name'); ?></th>
		<th><?php _e('Email'); ?></th>
		<th><?php _e('Nice Name'); ?></th>
		<th><?php _e('Status'); ?></th>
		<th><?php _e('Actions'); ?></th>
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
		<a href="<?php echo gp_url('/admin/users/edit/') . $user->id; ?>"><?php echo esc_html( $user->user_login ); ?></a>
		<?php if ( $user->admin() ) echo '<span class="active bubble">Admin</span>'; ?>
	</td>
	<td class="user_long">
		<?php echo esc_html( $user->display_name ); ?>
	</td>
	<td class="user_long">
		<?php echo esc_html( $user->user_email ); ?>
	</td>
	<td class="user_long">
		<?php echo esc_html( $user->user_nicename ); ?>
	</td>
	<td class="user_short">
		<?php echo esc_html( $user->user_status ); ?>
	</td>
	<td class="user_actions">
		<a href="<?php echo gp_url('/admin/users/edit/') . $user->id; ?>" class="bubble action edit"><?php _e('Edit'); ?></a> 
		<a href="<?php echo gp_url('/admin/users/admin/') . $user->id; ?>" class="bubble action edit"><?php $user->admin() ? _e('Revoke Admin') : _e('Make Admin'); ?></a> 
		<a href="<?php echo gp_url('/admin/users/delete/') . $user->id; ?>" class="bubble action delete"><?php _e('Delete'); ?></a>
	</td>
</tr>
<?php
endforeach;
if ( !$users ):
?>
	<tr><td colspan="7"><?php _e('No users were found!'); ?></td></tr>
<?php
endif;
?>
</table>
<?php echo gp_pagination( $page, $per_page, $total_translations_count ); ?>

<?php
gp_tmpl_footer();