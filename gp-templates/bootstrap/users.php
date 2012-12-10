<?php
$is_admin = GP::$user->current()->admin();
gp_title(__('Users'));
wp_enqueue_script('confirm');
gp_tmpl_header();
?>
<section id="content">
    <h2><?php _e('User Management'); ?></h2>
    <?php
    if ($is_admin && !defined('CUSTOM_USER_TABLE')) {
        ?>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#"><?php _e('Options'); ?></a></li>
        </ul>
        <form action="" method="post" class="secondary">
            <input type="hidden" name="settings[gp_handle_settings]" value="on">
            <label for="settings[public_home]">
                <input type="checkbox" name="settings[public_home]" value="on"<?php if ($settings['public_home'] == 'on') echo ' checked'; ?>>
                <?php echo __('Make home page visible to everyone'); ?></label>
            <label for="settings[user_registration]">
                <input type="checkbox" name="settings[user_registration]" value="on"<?php if ($settings['user_registration'] == 'on') echo ' checked'; ?>>
                <?php echo __('Enable User Self-Registration'); ?></label>
            <input type="submit" name="submit" value="<?php _e('Save'); ?>" id="submit" class="btn btn-primary"/>
        </form>
        <?php
    }
    ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#"><?php _e('User List'); ?></a></li>
        <li><a href="<?php echo gp_url_user_profile(REGLOT_NEW_USER); ?>" class="btn btn-primary"><i class="icon-plus-sign"></i> <?php _e('Create a New User'); ?></a></li>
    </ul>
    <table id="translations" class="table table-hover">
        <thead>
            <tr>
                <th><?php _e('ID'); ?></th>
                <th><?php _e('Login'); ?></th>
                <th><?php _e('Display Name'); ?></th>
                <?php if ($is_admin) { ?>
                    <th><?php _e('Email'); ?></th>
                    <th><?php _e('Nice Name'); ?></th>
                <?php } ?>
                <th><?php _e('URL'); ?></th>
                <th><?php _e('Translations'); ?></th>
                <?php if ($is_admin) { ?>
                    <th><?php _e('Status'); ?></th>
                    <th><?php _e('Actions'); ?></th>
                <?php } ?>
            </tr>
        </thead>
        <?php
        foreach ($users as $user):
            $parity = gp_parity_factory();
            ?>
            <tr class="preview <?php echo $parity() ?>">
                <td class="user_short">
                    <?php echo $user->id; ?>
                </td>
                <td class="user_long">
                    <a href="<?php echo gp_url_user_profile($user->id); ?>"><?php echo esc_html($user->user_login); ?></a>
                    <?php if ($user->admin()) echo '<span class="label label-inverse">Admin</span>'; ?>
                </td>
                <td class="user_long">
                    <?php echo esc_html($user->display_name); ?>
                </td>
                <?php if ($is_admin) { ?>
                    <td class="user_long">
                        <?php echo esc_html($user->user_email); ?>
                    </td>
                    <td class="user_long">
                        <?php echo esc_html($user->user_nicename); ?>
                    </td>
                <?php } ?>
                <td class="user_long">
                    <a href="<?php echo $user->user_url; ?>" target="_blank">
                        <?php echo esc_html($user->user_url); ?>
                    </a>
                </td>
                <td class="user_short">
                    <?php
                    $trans_count = GP::$translation->count_by_user($user->id);
                    if ($trans_count) {
                        ?>
                        <a href="<?php echo gp_url_user_translations($user->id) ?>"><?php echo esc_html(GP::$translation->count_by_user($user->id)); ?></a>
                        <?php
                    } else {
                        echo __('None');
                    }
                    ?>
                </td>
                <?php if ($is_admin) { ?>
                    <td class="user_short">
                        <?php echo esc_html($user->user_status); ?>
                    </td>
                    <td class="user_actions">
                        <a href="<?php echo gp_url_user_profile($user->id); ?>" class="label label-info"><i class="icon-pencil icon-white"></i> <?php _e('Edit'); ?></a>
                        <a href="<?php echo gp_url('/admin/users/admin/') . $user->id; ?>" class="label label-<?php $user->admin() ? 'important' : 'success'; ?>"><?php $user->admin() ? _e('Revoke Admin') : _e('Make Admin'); ?></a> 
                        <a href="<?php echo gp_url('/admin/users/delete/') . $user->id; ?>" class="label label-important"><i class="icon-trash icon-white"></i> <?php _e('Delete'); ?></a>
                    </td>
                <?php } ?>
            </tr>
            <?php
        endforeach;
        if (empty($users)):
            ?>
            <tr><td colspan="<?php if ($is_admin) echo '9'; else echo '5'; ?>"><?php _e('No users were found!'); ?></td></tr>
            <?php
        endif;
        ?>
    </table>
    <?php // echo gp_pagination( $page, $per_page, $total_translations_count ); ?>
</section>
<?php gp_tmpl_footer(); ?>
