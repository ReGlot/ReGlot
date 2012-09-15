<?php
/**
 * Holds common functionality for routes.
 */
class GP_Route_Main extends GP_Route {

	function _admin_gatekeeper() {
		if ( !GP::$user->admin() ) {
			$this->redirect(gp_url());
			return false;
		}
		return true;
	}

	
	static function deltree($dirname) {
        //Deletes directory and all subdirectories and files
        if ( !file_exists($dirname) ) { return false; } // Sanity check
        // Simple delete for a file 
        if ( is_file($dirname) || is_link($dirname) ) {
            return unlink($dirname);
        } 
        // Loop through the folder 
        $dir = dir($dirname); 
        while ( false !== ($entry = $dir->read()) ) {
            // Skip pointers 
            if ( $entry == '.' || $entry == '..' ) { 
                continue; 
            } 
            // Recurse
            self::deltree("$dirname/$entry");
        }
        // Clean up 
        $dir->close();
        return rmdir($dirname); 
    }

}
