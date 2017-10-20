<?php include 'inc/header.php'; ?>
<?php include 'db_conn_open.php' ?>
<?php

function encrypt_decrypt($action, $string) {
    // you may change these values to your own
    $secret_key = $GLOBALS['ENCRYPT_KEY'];
    $secret_iv = $GLOBALS['ENCRYPT_KEY'];

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
if(isset($_GET['id'])) {
  $id = encrypt_decrypt('decrypt', $_GET['id']);
  $query = "SELECT * FROM articles ";
  $query .= " WHERE article_id=".$id." LIMIT 1";
  $result = mysqli_query($GLOBALS['con'], $query);
  $data = mysqli_fetch_assoc($result);
  $article_content = $data['article_content'];
} else {
   $article_content = '';
}
?>
<!-- Titlebar -->
<div id="titlebar">
    <div class="row">
        <div class="col-md-12">
            <h2>Add Article</h2>
            <!-- Breadcrumbs -->
            <nav id="breadcrumbs">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li>Add Article</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="row">
        <form class="" id="addForm" name="addForm" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="notification notice closeable">
                    <p><span>Info!</span> Article sent for review</p>
                    <a class="close"></a>
                </div>
                <div class="form-group">
                    <label for="article_title" class="form-label">Title </label>
                    <input type="text" class="form-control" name="article_title"  id="article_title" value="<?php echo $data['article_title'] ?>">
                </div>

                <div class="form-group">
                    <label class="form-label"> Featured Image </label>
                    <input id="featured_image" name="featured_image" class="input-file" type="file">
                </div>
                <div class="form-group">
                    <label for="article_content" class="form-label">Content</label>
                    <div id="article_content"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="" align="right">
                    <button type="button" name="button" class="button btn-block margin-top-20" onclick="article.add();"><?php echo isset($_GET['id']) ? 'Edit & Publish':'Publish'?></button>
                    <input type="hidden" name="controller" value="BLOG">
                    <input type="hidden" name="action" value="<?php echo isset($_GET['id']) ? 'edit':'add'?>">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <div class="featured margin-top-20">
                      <img src="../images/blog/<?php echo $data['article_featured_image']; ?>" alt="">
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<?php include 'inc/footer.php'; ?>
<script src="module/add-article.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
  $("#article_content").summernote("code", '<?php echo $article_content;?>');
});
</script>
