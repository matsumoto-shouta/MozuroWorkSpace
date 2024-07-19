<?php
session_start();
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像詳細</title>
    <style>
        body {
            background: linear-gradient(106deg, #6fad44, #34c2db);
            font-family: 'Helvetica Neue', sans-serif;
        }
        .container {
            position: relative;
            width: 600px; /* 画像の幅に合わせて調整 */
            margin: auto; /* 中央に寄せる */
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }
        .post {
            position: relative;
            margin-bottom: 20px;
        }
        .post-image {
            width: 100%; /* 画像を幅いっぱいに表示 */
            display: block;
            border-radius: 8px;
        }
        .caption {
            margin-top: 10px;
            font-style: italic;
            color: #555;
        }
        .comments {
            margin-top: 20px;
        }
        .comment {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .timestamp {
            color: #aaa;
            font-size: 0.8em;
        }
        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #c0392b;
        }
        .back-button {
            background-color: #3498db;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">

    <?php if (isset($_GET['id'])):
        $picture_id = htmlspecialchars($_GET['id']);
        $sql = "SELECT * FROM Upload 
                JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                JOIN UserData ON UserData.user_ID = Upload.user_ID
                WHERE Picture.picture_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$picture_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row):
            ?>
            <div class='post'>
                <h1><?= htmlspecialchars($row['user_name']) ?>の画像</h1>
                <img src='<?= htmlspecialchars($row['picture_name']) ?>' alt='アップロードされた画像' class='post-image'>
                <p class='caption'><?= htmlspecialchars($row['caption']) ?></p>
            </div>

            <?php
            // ログインユーザーと投稿者が一致している場合に削除フォームを表示
            if (isset($_SESSION['UserData']['id']) && $_SESSION['UserData']['id'] === $row['user_ID']): ?>
                <div>
                    <form method='POST' action='delete.php'>
                        <input type='hidden' name='picture_id' value='<?= htmlspecialchars($picture_id) ?>'>
                        <button type='submit' onclick="return confirm('本当に削除しますか？');">削除</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="comments">
                <h2>コメント</h2>
                <?php
                $comment_sql = "SELECT * FROM Comments 
                                JOIN UserData ON Comments.reuser_ID = UserData.user_ID
                                WHERE picture_ID = ? ORDER BY up_time DESC";
                $comment_stmt = $pdo->prepare($comment_sql);
                $comment_stmt->execute([$picture_id]);

                if ($comment_stmt->rowCount() > 0) {
                    while ($comment = $comment_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . htmlspecialchars($comment['user_name']) . ":</strong> " . htmlspecialchars($comment['comments_text']) . "</p>";
                        echo "<p class='timestamp'>" . htmlspecialchars($comment['up_time']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>コメントがまだありません。</p>";
                }
                ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <button class="back-button" onclick="history.back()">戻る</button>
</div>
</body>
</html>
