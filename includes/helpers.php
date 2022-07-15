<?php
defined('ABSPATH') or die('No script kiddies please!');

function google_book_hive_get_value($value)
{
    return !empty($value) ? $value : null;
}

function google_book_hive_admin_style()
{
    wp_register_style('bootstrap_cosmo_google_book_hive', GoogleBookHiveAddFile::addFiles('assets/css', 'bootstrap.min', 'css', true), false);
}

function google_book_hive_extract_and_upload_product($inputFileName)
{

    $book_info = array();
    $csv = array();

    //  Read your CSV file
    try {
        $rows = array_map('str_getcsv', file($inputFileName));
        $header = array_shift($rows);
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }
    
    foreach ($csv as $rowData) {

        $isbn     = $rowData['isbn number'];
        $quantity = $rowData['quantity'];
        $stock_pr = $rowData['stock price'];
        $sell_pr  = $rowData['sell price'];

        try {
            $get_response = @file_get_contents('https://www.googleapis.com/books/v1/volumes?q=' . $isbn . '&isbn=' . $isbn . '&key=' . get_user_meta(get_current_user_id(), 'google-api-key', true));

            if ($get_response === false) {
                continue;
            } else {
                $get_response = json_decode($get_response);
                $book_info[]  = google_book_hive_create_new_product($get_response, $isbn, $stock_pr, false, $quantity, $sell_pr);
            }
        } catch (Exception $e) {
            echo 'Invalid Error Fetching Data..';
        }

    }

    return array_filter($book_info);
}

function google_book_hive_extract_and_upload_product_criteria_2($inputFileName)
{

    $book_info = array();
    $csv = array();

    //  Read your CSV workbook
    try {
        $rows = array_map('str_getcsv', file($inputFileName));
        $header = array_shift($rows);
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }
    
    foreach ($csv as $rowData) {
        
        if (empty($rowData)) {
            continue;
        }
        $author   = urlencode($rowData['author']);
        $title    = urlencode($rowData['title']);
        $price    = $rowData['price'];
        $hit_url  = 'https://www.googleapis.com/books/v1/volumes?q=' . $title . '+intitle:' . $title . '+inauthor:' . $author . '&key=' . get_user_meta(get_current_user_id(), 'google-api-key', true);

        try {
            $get_response = @file_get_contents($hit_url);
            if ($get_response === false) {
                continue;
            } else {
                $get_response = json_decode($get_response);
                $book_info[]  = google_book_hive_create_new_product($get_response, null, $price, $title);
            }
        } catch (Exception $e) {
            echo 'Invalid Error Fetching Data..';
        }
    }

    return array_filter($book_info);
}

function google_book_hive_extract_and_upload_product_criteria_3($isbn, $sell_pr, $stock_pr, $quantity)
{

    try {
        $get_response = @file_get_contents('https://www.googleapis.com/books/v1/volumes?q=' . $isbn . '&isbn=' . $isbn . '&key=' . get_user_meta(get_current_user_id(), 'google-api-key', true));

        if ($get_response === false) {
            echo 'No Data found for this ISBN number..';
            return;
        } else {
            $get_response = json_decode($get_response);
            $book_info  = google_book_hive_create_new_product($get_response, $isbn, $stock_pr, false, $quantity, $sell_pr);
        }
    } catch (Exception $e) {
        echo 'Invalid Error Fetching Data..';
    }

    return $book_info;
}

function google_book_hive_create_new_product($get_response, $isbn, $price, $title_output = false, $quantity = false, $sell_price = false)
{

    $book_info = null;
    $html_desc = null;

    if (empty($get_response->items)) {
        return;
    }

    foreach ($get_response->items as $books) :

        if ($isbn == null) :
            $isbn = $books->volumeInfo->industryIdentifiers[1]->identifier;
        endif;

        $html_desc .= '<ul>';
        if (google_book_hive_get_value($isbn)) :
            $html_desc .= '<li><strong>ISBN(s): </strong>' . $isbn . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->authors)) :
            $html_desc .= '<li><strong>Author(s): </strong>' . google_book_hive_get_value(implode(',', $books->volumeInfo->authors)) . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->publisher)) :
            $html_desc .= '<li><strong>Publisher: </strong>' . google_book_hive_get_value($books->volumeInfo->publisher) . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->publishedDate)) :
            $html_desc .= '<li><strong>Published Date: </strong>' . google_book_hive_get_value($books->volumeInfo->publishedDate) . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->pageCount)) :
            $html_desc .= '<li><strong>Page Count: </strong>' . google_book_hive_get_value($books->volumeInfo->pageCount) . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->printType)) :
            $html_desc .= '<li><strong>Print Type: </strong>' . google_book_hive_get_value($books->volumeInfo->printType) . '</li>';
        endif;
        if (!empty($books->volumeInfo->categories)) :
            $html_desc .= '<li><strong>Category: </strong>' . google_book_hive_get_value(implode(',', $books->volumeInfo->categories)) . '</li>';
        endif;
        if (!empty($books->volumeInfo->averageRating)) :
            $html_desc .= '<li><strong>Average Rating: </strong>' . google_book_hive_get_value($books->volumeInfo->averageRating) . '</li>';
        endif;
        if (!empty($books->volumeInfo->ratingsCount)) :
            $html_desc .= '<li><strong>Rating Count: </strong>' . google_book_hive_get_value($books->volumeInfo->ratingsCount) . '</li>';
        endif;
        if (google_book_hive_get_value($books->volumeInfo->maturityRating)) :
            $html_desc .= '<li><strong>Maturity Rating: </strong>' . google_book_hive_get_value($books->volumeInfo->maturityRating) . '</li>';
        endif;
        $html_desc .= '</ul>';

        $new_post = array(
            'post_title'    => google_book_hive_get_value($books->volumeInfo->title) . " : " . google_book_hive_get_value($books->volumeInfo->subtitle),
            'post_content'  => google_book_hive_get_value($books->volumeInfo->description),
            'post_status'   => 'publish',
            'post_type'     => 'product',
            'post_author'   => get_current_user_id(),
            'post_excerpt'  => $html_desc,
        );

        $post_id   = wp_insert_post($new_post);
        $html_desc = null;

        if ($post_id) :
            $book_info = !empty($title_output) ? urldecode($title_output) : $isbn;
        endif;

        update_post_meta($post_id, '_regular_price', $price);

        if (!empty($quantity)) :
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, '_stock', $quantity);
            update_post_meta($post_id, '_manage_stock', 'yes');
            update_post_meta($post_id, '_sku', $isbn);
        endif;

        if (!empty($sell_price)) :
            update_post_meta($post_id, '_sale_price', $sell_price);
        endif;

        // Gets term object from csv extraction in the database.

        // wp_set_object_terms($post_id, 1047, 'product_cat');

        if (!empty($books->volumeInfo->categories)) {
            foreach ($books->volumeInfo->categories as $category) {
                $term = get_term_by('name', $category, 'product_cat');

                if (empty($term)) {
                    wp_insert_term($category, 'product_cat');
                    $term = get_term_by('name', $category, 'product_cat');
                    wp_set_object_terms($post_id, $term->term_id, 'product_cat');
                } else {
                    wp_set_object_terms($post_id, $term->term_id, 'product_cat');
                }
            }
        }

        // setting the thumbnail image
        if (google_book_hive_get_value($books->volumeInfo->imageLinks->thumbnail)) {
            $uploaddir  = wp_upload_dir();
            // $uploadfile = $uploaddir['path'] . '/' . $isbn .'.png';
            $uploadfile = $uploaddir['path'] . '/' . uniqid() . '.png';

            $contents = file_get_contents($books->volumeInfo->imageLinks->thumbnail);
            $savefile = fopen($uploadfile, 'w');
            fwrite($savefile, $contents);
            fclose($savefile);

            google_book_hive_Generate_Featured_Image($uploadfile, $post_id);
        }

        break;

    endforeach;

    return $book_info;
}

function google_book_hive_Generate_Featured_Image($image_url, $post_id)
{
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if (wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    $res1 = wp_update_attachment_metadata($attach_id, $attach_data);
    $res2 = set_post_thumbnail($post_id, $attach_id);
}

function google_book_hive_upload_user_file($file = array())
{
    require_once(ABSPATH . 'wp-admin/includes/admin.php');
    $file_return = wp_handle_upload($file, array('test_form' => false));
    if (isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
        return false;
    } else {
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        $attachment_id = wp_insert_attachment($attachment, $file_return['url']);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        if (0 < intval($attachment_id)) {
            return $attachment_id;
        }
    }
    return false;
}
