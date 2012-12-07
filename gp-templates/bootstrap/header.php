<?php
// wp_enqueue_style('base');
wp_enqueue_script('common');
?>
<!doctype html>  
<html dir="ltr" ><!-- lang="de-DE" -->
    <head>
        <meta charset="utf-8">
        <title><?php echo gp_title(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- favicon.ico and touch icons -->
        <link rel="shortcut icon" href="gp-templates/bootstrap/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="gp-templates/bootstrap/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="gp-templates/bootstrap/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="gp-templates/bootstrap/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="gp-templates/bootstrap/ico/apple-touch-icon-57-precomposed.png">

        <link rel="stylesheet" href="gp-templates/bootstrap/css/bootstrap.css" type="text/css" media="all" />
        <link rel="stylesheet" href="gp-templates/bootstrap/css/bootstrap-responsive.css" type="text/css" media="all" />
        <script type="text/javascript" src="gp-templates/bootstrap/js/bootstrap.js"></script>
        <?php gp_head(); ?>
    </head>
    <body class="no-js">
        <script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
        <div id="gp-js-message"></div>
        <header>
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="<?php echo gp_url('/'); ?>">ReGlot</a>
                        <div class="nav-collapse collapse">
                            <p class="navbar-text pull-right">
                                <?php
                                if (GP::$user && GP::$user->logged_in()) {
                                ?>
                                <a href="<?php echo gp_url_users() ?>" class="navbar-link"><?php _e('Users'); ?></a>
                                <a href="<?php echo gp_url_user_profile() ?>" class="navbar-link"><?php _e('Profile'); ?></a>
                                <a href="<?php echo gp_url_logout() ?>" class="navbar-link"><?php _e('Log out'); ?>
                                    <?php
                                } else if (GP::$user) {
                                    ?>
                                <?php if (gp_get_option('user_registration') == 'on') { ?>
                                <strong><a href="<?php echo gp_url_register(); ?>" class="navbar-link"><?php _e('Register'); ?></a></strong>
                                <?php } ?>
                                <strong><a href="<?php echo gp_url_login(); ?>" class="navbar-link"><?php _e('Log in'); ?></a></strong>
                                <?php } ?>
                            </p>
                            <ul class="nav">
                                <li class="active"><a href="#">Home</a></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>





            <h1>
                <a class="logo" href="<?php echo gp_url('/'); ?>">
                    <img alt="<?php esc_attr(__('GlotPress logo')); ?>" src="<?php echo gp_url_img('glotpress-logo.png'); ?>" />
                </a>
                <?php echo gp_breadcrumb(); ?>
                <nav id="menu">
                    <ul>
                        <?php
                        if (GP::$user && GP::$user->logged_in()) {
                            ?>
                            <li class="first"><?php
                        $user = GP::$user->current();
                        printf(__('Welcome %s! &nbsp; &nbsp; '), $user->display_name);
                            ?></li>
                            <?php
                        }

                        do_action('after_hello');

                        if (gp_get_option('public_home') == 'on') {
                            ?>
                            <li><a href="<?php echo gp_url_project() ?>"><?php _e('Projects'); ?></a></li>
                            <li><a href="<?php echo gp_url_by_translation() ?>"><?php _e('Translations'); ?></a></li>
                            <li><a href="<?php echo gp_url_tools() ?>"><?php _e('Tools'); ?></a></li>
                            <?php
                        }
                        if (GP::$user && GP::$user->logged_in() && GP::$user->admin()) {
                            ?>
                            <li><a href="<?php echo gp_url_settings() ?>"><?php _e('Settings'); ?></a></li>
                            <?php
                        }
                        if (GP::$user && GP::$user->logged_in()) {
                            ?>
                            <li><a href="<?php echo gp_url_users() ?>"><?php _e('Users'); ?></a></li>
                            <li><a href="<?php echo gp_url_user_profile() ?>"><?php _e('Profile'); ?></a></li>
                            <li><a href="<?php echo gp_url_logout() ?>"><?php _e('Log out'); ?></a>
                                <?php
                            } else if (GP::$user) {
                                ?>
                            <?php if (gp_get_option('user_registration') == 'on') { ?>
                                <li><strong><a href="<?php echo gp_url_register(); ?>"><?php _e('Register'); ?></a></strong></li>
                            <?php } ?>
                            <li><strong><a href="<?php echo gp_url_login(); ?>"><?php _e('Log in'); ?></a></strong></li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
                <div class="clearfix"></div>
            </h1>
        </header>
            <?php if (gp_notice('error')): ?>
            <div class="error">
            <?php echo gp_notice('error'); //TODO: run kses on notices  ?>
            </div>
        <?php endif; ?>
            <?php if (gp_notice()): ?>
            <div class="notice">
            <?php echo gp_notice(); ?>
            </div>
        <?php endif; ?>
<?php do_action('after_notices'); ?>