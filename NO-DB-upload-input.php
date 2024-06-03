<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="style.css">
<head>
    <meta charset="UTF-8">
    <title>画像アップロード</title>
</head>
<body>
    <h2>画像アップロードフォーム</h2>
    <form action="upload-output.php" method="post" enctype="multipart/form-data">
        <label for="file">画像を選択してください:</label>
        <input type="file" name="file" id="file">
        <input type="submit" value="アップロード">
    </form>
    <h2>画像ギャラリー</h2>
    <div class="gallery">
        <?php
        // 画像ギャラリーの表示
        $images = glob("uploads/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($images as $image) {
            echo "<div class='gallery-item'>";
            echo "<img src='$image' alt='アップロードされた画像'>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
