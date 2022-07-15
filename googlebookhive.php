<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package GoogleBookHive
 */
/*
Plugin Name: Google Book Hive
Plugin URI: http://blog.saniulahsan.info/google-book-hive
Description: This plugins gets information from a excel/csv file with a bulk amount of ISBN data and converts it to a Woocommere Product
Version: 1.1
Author: Saniul Ahsan
Author URI: http://saniulahsan.info
Text Domain: google-book-hive
*/

/*
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Copyright 2017 Saniul Ahsan, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	die( 'No script kiddies please!' );
}

require_once( plugin_dir_path( __FILE__ ).'/class/bootfile.class.php' );

GoogleBookHiveAddFile::addFiles('includes', 'helpers', 'php');

if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))):
	
    GoogleBookHiveAddFile::addFiles('views', 'isbnbulkupload', 'php');
    GoogleBookHiveAddFile::addFiles('views', 'googlebookapi', 'php');
    GoogleBookHiveAddFile::addFiles('views', 'booknamebulkupload', 'php');
    GoogleBookHiveAddFile::addFiles('views', 'isbnsingleupload', 'php');
    GoogleBookHiveAddFile::addFiles('views', 'documentation', 'php');

	add_action( 'admin_enqueue_scripts', 'google_book_hive_admin_style' );
	add_action( 'admin_menu', 'google_book_hive_reader_views' );

	function google_book_hive_reader_views() {
		add_menu_page( 'Google Book Hive', 'Google Book Hive', 'manage_options', 'google-book-hive-isbn-bulk-upload', 'google_book_hive_isbn_bulk_upload', GoogleBookHiveAddFile::addFiles('assets/images', 'icon-small', 'jpg', true), 100  );
		add_submenu_page( 'google-book-hive-isbn-bulk-upload', 'Upload Book Name File', 'Upload Book Name File', 'manage_options', 'google-book-hive-book-name-bulk-upload', 'google_book_hive_book_name_bulk_upload');
		add_submenu_page( 'google-book-hive-isbn-bulk-upload', 'ISBN Single Product', 'ISBN Single Product', 'manage_options', 'google-book-hive-isbn-single-upload', 'google_book_hive_isbn_single_upload');
		add_submenu_page( 'google-book-hive-isbn-bulk-upload', 'Google API Key', 'Google API Key', 'manage_options', 'google-book-hive-google-books-api-key', 'google_book_hive_google_books_api_key');
		add_submenu_page( 'google-book-hive-isbn-bulk-upload', 'Documentation', 'Documentation', 'manage_options', 'google-book-hive-documentation', 'google_book_hive_documentation');
	}

else:
	add_action( 'admin_notices', 'error_message' );
endif;
