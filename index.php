
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>投稿フォーム</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_ID" value="1">
        <textarea name="caption" rows="5" cols="40" placeholder="ここにキャプションを入力してください"></textarea>
        <br>
        <input type="file" name="picture_ID">
        <br>
        <input type="submit" value="投稿">
    </form>
</body>
</html>