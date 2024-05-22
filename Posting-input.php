<?php session_start(); ?>
<?php require 'DB-connect.php'; ?>
<?php $csrf = base64_encode( openssl_random_pseudo_bytes( 32 ) ); ?>
<?php $_SESSION['csrf'] = $csrf; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿画面</title>
</head>
<body>
    <h1>投稿</h1>

    <form action="Posting-output.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
        <div>画像（100MB以下のjpgかpng）<br><input id="post_image" type="file" name="post_image" accept=".jpg,.jpeg,.JPG,.JPEG,.png,.PNG" required></div>
        <input type="submit" value="upload">
        <input type="hidden" name="csrf" value="<?=$csrf;?>">
    </form>
</body>
</html>