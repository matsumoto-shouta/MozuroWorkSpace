<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <?php require "hamburger.php"; ?>
    <link rel="stylesheet" href="css/index.css">
    <script src="javascript/Preview1.js"></script>
</head>
<body>
    <div class="al">
        <h2>画像アップロードフォーム</h2>
        
        <!-- エラーメッセージの表示 -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- 成功メッセージの表示 -->
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="file">
                <label for="file" class="select">画像を選択してください:</label><br>
                <input type="file" name="file" id="file"><br>
            </div>

            <div id="preview"></div><br>

            <div class="ef">
                <label class="caption">
                    <textarea name="caption" id="caption" placeholder="キャプション"></textarea><br>
                </label>
            </div>

            <button type="submit" class="upbtn">アップロード</button>
        </form>
    </div>
</body>
</html>
