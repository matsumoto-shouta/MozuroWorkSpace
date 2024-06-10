<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/image.css">
    <title>画像詳細</title>
</head>
<body>
<div class="container">
    <?php
    require 'DB-connect.php'; 

    if (isset($_GET['id'])) {
        $picture_id = htmlspecialchars($_GET['id']);
        $sql = "SELECT * FROM Upload 
                JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                JOIN UserData ON UserData.user_ID = Upload.user_ID
                WHERE Picture.picture_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$picture_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "<div class='post'>";
            echo "<h1>" . htmlspecialchars($row['user_name']) . "の画像</h1>";
            echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像' class='post-image'>";
            echo "<p class='caption'>" . htmlspecialchars($row['caption']) . "</p>";
            echo "</div>";
        } else {
            echo "<p>画像が見つかりません。</p>";
        }
    } else {
        echo "<p>無効なIDです。</p>";
    }
    ?>
</div>
</body>
</html>
