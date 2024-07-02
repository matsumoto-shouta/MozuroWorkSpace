<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="javascript/Preview.js"></script>
</head>
<body>
    <h2>画像アップロードフォーム</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file" class="select" multiple>画像を選択してください:</label><br>
        <div id="preview"></div>
        キャプション: <input type="text" name="caption" id="caption"><br>
        <input type="file" name="file" id="file">
        <button type="submit" class="upbtn">アップロード</button>

        
    </form>

   
</body>
</html>
