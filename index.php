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

    <h2>画像ギャラリー</h2>
    <div class="gallery">
        <?php
        require 'DB-connect.php'; // データベース接続をインクルード

        // 画像情報をデータベースから取得
        $sql = "SELECT picture_name FROM Picture";
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='gallery-item'>";
                echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
                echo "</div>";
            }
        } else {
            echo "ギャラリーに画像がありません。";
        }
        ?>
    </div>
</body>
</html>
