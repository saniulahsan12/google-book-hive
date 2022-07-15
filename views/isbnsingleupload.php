<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function google_book_hive_isbn_single_upload() {

    if( isset($_POST['upload-single']) && wp_verify_nonce( $_POST['file-nonce'], 'upload-nonce' ) && !empty($_POST['isbn-number']) ):
        $result_processed = google_book_hive_extract_and_upload_product_criteria_3( sanitize_text_field($_POST['isbn-number']), sanitize_text_field($_POST['sale-price']), sanitize_text_field($_POST['regular-price']), sanitize_text_field($_POST['product-quantity']) );
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
                    <label for="isbn-number">ISBN Number</label>
					<input type="text" class="form-control" id="isbn-number" name="isbn-number">
				</div>
                <div class="form-group">
                    <label for="sale-price">Sale Price</label>
					<input type="number" class="form-control" id="sale-price" name="sale-price" min="0">
				</div>
                <div class="form-group">
                    <label for="regular-price">Regular Price</label>
					<input type="number" class="form-control" id="regular-price" name="regular-price" min="0">
				</div>
                <div class="form-group">
                    <label for="product-quantity">Product Quantity</label>
					<input type="number" class="form-control" id="product-quantity" name="product-quantity" min="0">
				</div>
                <?php wp_nonce_field('upload-nonce', 'file-nonce'); ?>
                <input id="submit" type="submit" name="upload-single" class="btn btn-success btn-block" value="Submit Data">
            </form>

            <?php if(!empty($result_processed)): ?>
                <br>
                <div class="alert alert-success">
                    <strong>Congrats </strong> <?php echo $result_processed; ?> Product Created.
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>
<?php
}
