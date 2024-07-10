<?php
ob_start();
session_start();
require 'db-connect.php';
require "hamburger.php";
?>
<?PHP
// コメントが投稿された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comments_text']) && isset($_POST['picture_id']) && isset($_SESSION['UserData']['id'])) {
    $comment_text = htmlspecialchars($_POST['comments_text']);
    $picture_id = htmlspecialchars($_POST['picture_id']);
    $user_id = $_SESSION['UserData']['id'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // トランザクション開始
        $pdo->beginTransaction();
        
        // コメントを追加
        $stmt = $pdo->prepare("INSERT INTO Comments (comments_text, reuser_ID, picture_ID, up_time) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$comment_text, $user_id, $picture_id]);

        // トランザクションをコミット
        $pdo->commit();

        // コメントが追加された後、ページをリダイレクト
        header("Location: image.php?id=" . $picture_id);
        exit();
    } catch (Exception $e) {
        // エラーが発生した場合、ロールバック
        $pdo->rollBack();
        echo "コメントの追加中にエラーが発生しました: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="javascript/image.js"></script>
    <link rel="stylesheet" href="css/image.css"> -->
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
            margin: 0 auto;
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
        }

        .canvas-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* キャンバスがマウスイベントをキャプチャしないようにする */
            overflow: hidden; /* 画像からはみ出した部分を非表示にする */
        }

        .comment-flow {
            position: absolute;
            white-space: nowrap;
            font-size: 16px; /* フォントサイズを調整 */
            font-weight: bold;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            animation: move 5s linear infinite; /* コメントを流すアニメーション */
        }

        @keyframes move {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100vw); }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- <a href='home.php'>ホーム画面へ</a> -->
    <!-- 表示/非表示ボタン -->
    <div>
        <button id="toggleComments">コメントを非表示</button>
    </div>
    <?php
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
            echo "<div class='canvas-container' id='canvasContainer'></div>"; // キャンバスコンテナをここに追加
            echo "<p class='caption'>" . htmlspecialchars($row['caption']) . "</p>";
            echo "</div>";

            // コメントの追加フォーム
            if (isset($_SESSION['UserData']['id'])) {
                echo "<div class='comment-form'>";
                echo "<h3>コメントを追加</h3>";
                echo "<form action='image.php' method='post'>";
                echo "<input type='hidden' name='picture_id' value='" . htmlspecialchars($picture_id) . "'>";
                echo "<textarea name='comments_text' rows='4' cols='50' required></textarea><br>";
                echo "<input type='submit' value='コメントを追加'>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "<p>コメントを追加するにはログインしてください。</p>";
            }
        } else {
            echo "<p>画像が見つかりません。</p>";
        }
    }
    ?>

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

    <!-- コメントをニコニコ動画風に流すスクリプト -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const commentsContainer = document.querySelector(".comments");
            const canvasContainer = document.getElementById("canvasContainer"); // IDで取得する
            const toggleButton = document.getElementById("toggleComments");
            let commentsVisible = true;

            // コメントアニメーションを開始する関数
            function startCommentsAnimation() {
                // canvasContainerをクリアする
                canvasContainer.innerHTML = '';

                // .commentsからコメントを取得
                const comments = commentsContainer.querySelectorAll(".comment");

                comments.forEach(comment => {
                    const commentText = comment.querySelector("p").textContent.trim();
                    const commentElement = document.createElement("div");
                    commentElement.classList.add("comment-flow");
                    commentElement.textContent = commentText;

                    // canvasContainer内のランダムな位置に配置
                    const topPosition = Math.floor(Math.random() * (canvasContainer.clientHeight - commentElement.offsetHeight));
                    commentElement.style.top = `${topPosition}px`;
                    commentElement.style.left = `${canvasContainer.clientWidth}px`;

                    canvasContainer.appendChild(commentElement);
                });
            }

            // 初期状態でコメントアニメーションを開始
            startCommentsAnimation();

            // トグルボタンの機能
            toggleButton.addEventListener("click", function() {
                if (commentsVisible) {
                    canvasContainer.style.display = 'none'; // コメント非表示
                    toggleButton.textContent = 'コメントを表示';
                } else {
                    canvasContainer.style.display = 'block'; // コメント表示
                    startCommentsAnimation(); // アニメーションを再開始
                    toggleButton.textContent = 'コメントを非表示';
                }

                commentsVisible = !commentsVisible;
            });
        });
    </script>

</div>
</body>
</html>
