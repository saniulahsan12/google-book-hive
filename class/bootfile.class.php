<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Add files
class GoogleBookHiveAddFile {
	public static function addFiles( $path, $filename, $ext, $state = false ) {
		$file = $path.'/'.$filename.'.'.$ext;

		if($state == false):
			require plugin_dir_path( __DIR__  ) . $file;
		else:
			return plugins_url( $file, dirname(__FILE__) );
		endif;
	}
}
// ends
