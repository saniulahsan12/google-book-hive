<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function google_book_hive_google_books_api_key() {

    if( isset($_POST['upload-gapi']) && wp_verify_nonce( $_POST['gapi-nonce'], 'gapi-upload-nonce' ) ):
        $status = update_user_meta( get_current_user_id(), 'google-api-key', sanitize_text_field($_POST['google-api-key']) );
    endif;

    wp_enqueue_style( 'bootstrap_cosmo_google_book_hive' );

?>

<p></p>
<div class="container">
    <div class="col-md-6 col-md-push-3 col-sm-12 col-xs-12">
        <div class="form-area">
            <img class="img-responsive center-block" src="<?php echo GoogleBookHiveAddFile::addFiles('assets/images', 'icon', 'jpg', true); ?>" alt="logo">
            <form action="" method="post">
                <br style="clear:both">
                <div class="form-group">
                    <label for="google-api-key">Google Books API Key</label>
                    <input class="form-control" type="text" name="google-api-key" value="<?php echo get_user_meta(get_current_user_id(), 'google-api-key', true); ?>">
                </div>
                <?php wp_nonce_field('gapi-upload-nonce', 'gapi-nonce'); ?>
                <input id="submit" type="submit" name="upload-gapi" class="btn btn-info btn-block" value="Save Google API Key">
            </form>
            <?php if(!empty($status)): ?>
                <br>
                <span class="label label-success center-block">
                    API Key Saved Successfully.
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
}
