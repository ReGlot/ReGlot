<?php
gp_title(__('Import Translations', 'glotpress'));
gp_breadcrumb_project($project);
gp_tmpl_header();
gp_tmpl_load('project-import', get_defined_vars());
gp_tmpl_footer();
?>
