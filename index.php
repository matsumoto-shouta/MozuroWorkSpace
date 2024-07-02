<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="javascript/Preview.js"></script>
</head>
<body>
    <?php require "hamburger.php"; ?>

    <h2>画像アップロードフォーム</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="file">
            <label for="file" class="select">画像を選択してください:</label><br>
            <input type="file" name="file" id="file"><br>
        </div>

        <div id="preview"></div><br>

        <div class="ef">
            <label class="caption">
                <textarea type="text" name="caption" id="caption" placeholder="キャプション"></textarea><br>
            </label>
        </div>
        
        <button type="submit" class="upbtn">アップロード</button>

        
    </form>

   
</body>
