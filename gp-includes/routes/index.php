<?php
class GP_Route_Index extends GP_Route_Main {
	function login_only() {
		gp_notice_set(__('You need to be logged in to access this site'));
		$this->redirect(gp_url('/login'));
	}
	function index() {
		$this->redirect( gp_url_project( '' ) );
	}
}