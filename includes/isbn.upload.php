<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( is_user_logged_in() && is_admin() && wp_verify_nonce( $_POST['file-nonce'], 'upload-nonce' ) ):

	// for write
	$upload_dir  = wp_upload_dir();
    $outputdir   = str_replace( '\\', '/', $upload_dir['path'] );
    $extension   = pathinfo(sanitize_file_name($_FILES["file-data"]["name"]), PATHINFO_EXTENSION);
	$file_size   = $_FILES["file-data"]["size"];

	global $file_upload_status;

	if( !empty($_FILES) && $file_size <= 5*1024*1024 && in_array( $extension, array('csv') ) ):

		$attachment_id = google_book_hive_upload_user_file( $_FILES['file-data'] );

		if( $attachment_id ):
			$filename = $outputdir.'/'.basename( get_attached_file( $attachment_id ) );
			update_user_meta( get_current_user_id(), 'isbn_file',  $filename );
			$file_upload_status = '<span class="label label-success center-block">File Uploaded Successfully.</span>';
		endif;

	else:
		$file_upload_status = '<span class="label label-danger center-block">Sorry, there was an error uploading your file.</span>';
	endif;

endif;
