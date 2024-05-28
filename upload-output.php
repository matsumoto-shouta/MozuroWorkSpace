<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php
// 投稿データの取得
$sql = "SELECT up_ID, user_ID, caption, picture_ID FROM Upload";
$result = $connect->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧</title>
</head>
<body>
    <h1>投稿一覧</h1>
    <?php
    if ($result->num_rows > 0) {
        // データの出力
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($row["content"])) . "</p>";
            echo "</div><hr>";
        }
    } else {
        echo "投稿はありません。";
    }

    // 接続のクローズ
    $conn->close();
    ?>
</body>
</html>
