<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function google_book_hive_documentation() {
    wp_enqueue_style( 'bootstrap_cosmo_google_book_hive' );
?>

<p></p>
<div class="container">
    <img class="center-block img img-responsive" src="<?php echo GoogleBookHiveAddFile::addFiles('assets/images', 'google-book-hive-banner', 'jpg', true); ?>" alt="Cover Image">
    <h1 class="text-center">Documentation</h1>

    <div class="col-md-9 col-md-offset-2 col-sm-12 col-xs-12">
        <h3>Google Book API key</h3>
        <p>First of all get your google book API key from this <a target="blank" href="https://developers.google.com/books/">link. </a> put it in the Google API key pages text box and save.</p>

        <h3>Google Book Hive</h3>
        <p>Here you can upload a CSV containing the book ISBN data to populate into the woocommerce product. Since all books may not be available at the Google bookstore, In that case after uploading the product a list of the uploaded product will be generated. You can download it and match to check which are not uploaded for manual upload. The sample file link is <a href="<?php echo GoogleBookHiveAddFile::addFiles('assets/samples', 'bulk_isbn', 'csv', true); ?>">here</a> or you can create your own CSV file.</p>

        <h3>Upload Book Name</h3>
        <p>This is just like the previous one but the fact is, this option will give you a chance to upload a CSV by author name and book title along with the price. For upload, the same rule will be applied as previous. The sample file link is <a href="<?php echo GoogleBookHiveAddFile::addFiles('assets/samples', 'bulk_book', 'csv', true); ?>">here</a> or you can create your own CSV file.</p>

        <h3>ISBN Single Product</h3>
        <p>This page is for quick importers who want a single book with price, quantity and other information set for the product. It will upload only a single book in the product section by its ISBN serial number.</p>

        <br>
        <br>
        <br>
        <p class="text-center">
            <a class="btn btn-info" target="blank" href="http://saniulahsan.info">Contact Me For More Information or Product Like This</a>
        </p>
    </div>
</div>
<?php
}
