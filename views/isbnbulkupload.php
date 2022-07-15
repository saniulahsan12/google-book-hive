<?php
defined('ABSPATH') or die('No script kiddies please!');
function google_book_hive_isbn_bulk_upload()
{

    global $file_upload_status;
    $result_processed = null;

    if (isset($_POST['upload-excel']) && wp_verify_nonce($_POST['file-nonce'], 'upload-nonce') && !empty($_FILES['file-data'])) :
        GoogleBookHiveAddFile::addFiles('includes', 'isbn.upload', 'php');

        if (!empty(get_user_meta(get_current_user_id(), 'isbn_file', true))) :
            $result_processed = google_book_hive_extract_and_upload_product(get_user_meta(get_current_user_id(), 'isbn_file', true));
            update_user_meta(get_current_user_id(), 'isbn_file', null);
        endif;

    endif;

    wp_enqueue_style('bootstrap_cosmo_google_book_hive');
?>

    <p></p>
    <div class="container">
        <div class="col-md-6 col-md-push-3 col-sm-12 col-xs-12">
            <div class="form-area">
                <img class="img-responsive center-block" src="<?php echo GoogleBookHiveAddFile::addFiles('assets/images', 'icon', 'jpg', true); ?>" alt="logo">
                <form action="" method="post" enctype="multipart/form-data">
                    <br style="clear:both">
                    <div class="form-group">
                        <label for="fileupload">Choose Excel/CSV File by ISBN Number</label>
                        <input type="file" class="form-control" id="fileupload" name="file-data">
                    </div>
                    <?php wp_nonce_field('upload-nonce', 'file-nonce'); ?>
                    <input id="submit" type="submit" name="upload-excel" class="btn btn-warning btn-block" value="Upload Excel">
                </form>
                <?php if (!empty($file_upload_status)) : ?>
                    <br>
                    <?php echo $file_upload_status; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($result_processed)) : $counter = 1; ?>
                <br>
                <table id="successTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#Serial</th>
                            <th>ISBN Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_processed as $result) : ?>
                            <tr>
                                <td><code class="w3-codespan"><?php echo $counter; ?></code></td>
                                <td><code class="w3-codespan"><?php echo $result; ?></code></td>
                                <td><span class="label label-success">Imported</span></td>
                            </tr>
                        <?php $counter++;
                        endforeach; ?>
                    </tbody>
                </table>
                <br>
                <input type="button" class="btn btn-info btn-block" onclick="tableToExcel('successTable', 'W3C Example Table')" value="Export to Excel / CSV">
            <?php endif; ?>

        </div>
    </div>

    <?php if (!empty($result_processed)) : ?>
        <script type="text/javascript">
            var tableToExcel = (function() {
                var uri = 'data:application/vnd.ms-excel;base64,',
                    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
                    base64 = function(s) {
                        return window.btoa(unescape(encodeURIComponent(s)))
                    },
                    format = function(s, c) {
                        return s.replace(/{(\w+)}/g, function(m, p) {
                            return c[p];
                        })
                    }
                return function(table, name) {
                    if (!table.nodeType) table = document.getElementById(table)
                    var ctx = {
                        worksheet: name || 'Worksheet',
                        table: table.innerHTML
                    }
                    window.location.href = uri + base64(format(template, ctx))
                }
            })()
        </script>
    <?php endif; ?>

<?php
}
