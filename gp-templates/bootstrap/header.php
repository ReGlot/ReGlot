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
            <script type="text/javascript" src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php gp_head(); ?>
        <!-- favicon.ico and touch icons -->
        <link rel="shortcut icon" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/ico/apple-touch-icon-57-precomposed.png">

        <link rel="stylesheet" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/css/bootstrap.css" type="text/css" media="all" />
        <link rel="stylesheet" href="<?php echo gp_url('/'); ?>gp-templates/bootstrap/css/bootstrap-responsive.css" type="text/css" media="all" />
        <script type="text/javascript" src="<?php echo gp_url('/'); ?>gp-templates/bootstrap/js/bootstrap.js"></script>

        <style type="text/css">

            /* Sticky footer styles
            -------------------------------------------------- */

            html,
            body {
                height: 100%;
                /* The html and body elements cannot have any padding or margin. */
            }

            /* Wrapper for page content to push down footer */
            #wrap {
                min-height: 100%;
                height: auto !important;
                height: 100%;
                /* Negative indent footer by it's height */
                margin: 0px auto -60px;
            }

            /* Set the fixed height of the footer here */
            #push {
                height: 80px;
            }
            #footer {
                height: 60px;
            }
            #footer {
                background-color: #f5f5f5;
            }
            #footer p {
                margin: 20px;
            }

            /* Lastly, apply responsive CSS fixes as necessary */
            @media (max-width: 767px) {
                #footer {
                    margin-left: -20px;
                    margin-right: -20px;
                    padding-left: 20px;
                    padding-right: 20px;
                }
            }

            table.translations, table.translation-sets {
                border-spacing: 0.1em;
                font-size: 90%;
                width: 100%;
            }
            table.translations thead th, table.translations tfoot th, table.translation-sets thead th, table.translations tfoot th {
                background-color: #555555;
                color: #EEEEEE;
                font-weight: bold;
                padding: 0.5em;
            }
            table.translations tr, table.translation-sets tr {
                border: 0 none;
            }
            table.translations td.translation ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            table.translations td.translation li {
                border-bottom: 1px dotted #CCCCCC;
                padding-bottom: 0.25em;
            }
            table.translations td.translation li:last-child {
                border-bottom: medium none;
                padding-bottom: 0;
            }
            table.translations td.translation span.missing {
                color: #DDDDDD;
                font-style: italic;
            }
            table.translations td.translation span.missing a {
                color: #CCCCCC;
                font-style: italic;
            }
            table.translations td, table.translations th, table.translation-sets td, table.translation-sets th {
                border: 1px solid #EEEEEE;
                border-spacing: 0;
                margin: 0;
                padding: 0.5em;
            }
            table.translations td.checkbox {
                text-align: center;
                vertical-align: middle;
            }
            th.checkbox input[type="checkbox"] {
                margin: 0;
            }
            table.translations td.priority {
                font-size: 1.3em;
                text-align: center;
            }
            table.translations td.actions {
                text-align: center;
            }
            table.translations td.original, table.translations td.translation {
                width: 40%;
            }
            table.translations th.original, table.translations th.translation {
                width: 45%;
            }
            table.translations.originals th.original, table.translations.originals td.original {
                width: 20%;
            }
            table.translations.originals th.translation, table.translations.originals td.translation {
                width: 40%;
            }
            table.translations.originals th.comment, table.translations.originals td.comment {
                width: 40%;
            }
            table.translations tr.even {
                background-color: #FCFCFC;
            }
            table.translations tr.editor {
                background-color: #F8FFEC;
                display: none;
            }
            table.translations tr.preview.status-fuzzy, #legend .status-fuzzy {
                background-color: #FFCC66;
            }
            table.translations tr.preview.status-current, #legend .status-current {
                background-color: #E9FFD8;
            }
            table.translations tr.preview.status-old, #legend .status-old {
                background-color: #FEE4F8;
            }
            table.translations tr.preview.status-waiting, #legend .status-waiting {
                background-color: #FFFFC2;
            }
            table.translations tr.preview.status-rejected, #legend .status-rejected {
                background-color: #FF8E8E;
            }
            table.translations tr.preview.has-warnings td.original, div#legend div.has-warnings {
                border-left: 2px solid red;
            }
            table.translations a.action {
                font-size: 100%;
                font-style: normal;
            }
            .editor .original {
                font-weight: bold;
                white-space: pre-wrap;
                width: 50em;
            }
            .editor .strings {
                float: left;
                width: 60%;
            }
            .editor .strings p.plural-numbers {
                color: #555555;
                font-size: 0.75em;
                margin-bottom: 0;
            }
            .editor .strings p.plural-numbers span.numbers {
                font-weight: bold;
            }
            .editor .textareas, .editor .actions {
                clear: both;
            }
            .editor .textareas textarea {
                min-height: 13em;
                width: 50em;
            }
            .editor .meta {
                float: left;
                margin-left: 2em;
            }
            .editor .meta dl {
                margin: 0.1em 1em;
            }
            .editor .meta dt, .editor .meta dd {
                display: inline;
                margin: 0;
            }
            .editor .meta dt {
                margin-right: 0.5em;
            }
            .editor .meta dt {
                margin-right: 0.5em;
            }
            .editor .meta dd {
                color: #444444;
                font-weight: bold;
            }
            .project-list li {
                border: 1px solid #CCCCCC;
                margin-bottom: 2px;
            }
            .project-list li.new-project {
                border: 0;
            }
            .project-list li.new-project a {
                float: left;
            }
            .project-list > li.new-project:hover {
                background-color: none; 
            }
            .project-list > li:hover {
                background-color: #EEEEEE;
            }
            .project-list a.project-title {
                float: left;
            } 
            .project-list .action {
                float: right;
            }
        </style>    
    </head>
    <body class="no-js">
        <div id="wrap">
            <script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
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
                                    <a href="<?php echo gp_url_users() ?>" class="navbar-link"><i class="icon-user icon-white"></i> <?php _e('Users'); ?></a> |
                                    <a href="<?php echo gp_url_user_profile() ?>" class="navbar-link"><?php _e('Profile'); ?></a> |
                                    <a href="<?php echo gp_url_logout() ?>" class="navbar-link"><i class="icon-off icon-white"></i> <?php _e('Log out'); ?>
                                        <?php
                                    } else if (GP::$user) {
                                        ?>
                                        <?php if (gp_get_option('user_registration') == 'on') { ?>
                                            <strong><a href="<?php echo gp_url_register(); ?>" class="navbar-link"><?php _e('Register'); ?></a></strong> | 
                                        <?php } ?>
                                        <strong><a href="<?php echo gp_url_login(); ?>" class="navbar-link"><?php _e('Log in'); ?></a></strong>
                                    <?php } ?>
                            </p>
                            <ul class="nav">
                                <?php
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
                                <?php } ?>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <div class="container-fluid content">
                <?php echo gp_breadcrumb(); ?>
                <div id="gp-js-message"></div>
                <?php if (gp_notice('error')): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php echo gp_notice('error'); //TODO: run kses on notices  ?>
                    </div>
                <?php endif; ?>
                <?php if (gp_notice()): ?>
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php echo gp_notice(); ?>
                    </div>
                <?php endif; ?>
                <?php do_action('after_notices'); ?>