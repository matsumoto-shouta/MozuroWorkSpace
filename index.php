<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>画像アップロードフォーム</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">画像を選択してください:</label>
        <input type="file" name="file" id="file">
        <input type="submit" value="アップロード">
    </form>
</body>
</html>
