<?php
class GP_Route_Index extends GP_Route_Main {
	function login_only() {
		gp_notice_set(__('You need to be logged in to access this site'));
		$this->redirect(gp_url('/login'));
	}
	function index() {
		if ( has_action('index') ) {
			do_action('index');
		} else {
			gp_tmpl_load('index');
		}
	}
}
