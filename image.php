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
        echo "<h1>" . htmlspecialchars($row['user_name']) . "の画像</h1>";
        echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
        echo "<p>" . htmlspecialchars($row['caption']) . "</p>";
    } else {
        echo "<p>画像が見つかりません。</p>";
    }
} else {
    echo "<p>無効なIDです。</p>";
}
?>
