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
<a href='home.php'>ホーム画面へ</a>
    <?php
    require 'db-connect.php'; 

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

            echo "<div class='comments'>";
            echo "<h2>コメント</h2>";

            $comment_sql = "SELECT * FROM Comments 
                            JOIN UserData ON Comments.user_ID = UserData.user_ID
                            WHERE picture_ID = ? ORDER BY up_time DESC";
            $comment_stmt = $pdo->prepare($comment_sql);
            $comment_stmt->execute([$picture_id]);

            while ($comment = $comment_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='comment'>";
                echo "<p><strong>" . htmlspecialchars($comment['user_name']) . ":</strong> " . htmlspecialchars($comment['comments_text']) . "</p>";
                echo "<p class='timestamp'>" . htmlspecialchars($comment['up_time']) . "</p>";
                echo "</div>";
            }
            echo "</div>";

            // コメントの追加フォーム
            if (isset($_SESSION['UserData']['id'])) {
                echo "<div class='comment-form'>";
                echo "<h3>コメントを追加</h3>";
                echo "<form action='add_comment.php' method='post'>";
                echo "<input type='hidden' name='picture_id' value='" . htmlspecialchars($picture_id) . "'>";
                echo "<textarea name='comment_text' rows='4' cols='50' required></textarea><br>";
                echo "<input type='submit' value='コメントを追加'>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "<p>コメントを追加するにはログインしてください。</p>";
            }
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
