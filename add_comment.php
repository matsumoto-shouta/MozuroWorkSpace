<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['UserData']['id']) && isset($_POST['comment_text']) && isset($_POST['picture_id'])) {
        $reuser_id = $_SESSION['UserData']['id'];
        $comment_text = htmlspecialchars($_POST['comment_text']);
        $picture_id = htmlspecialchars($_POST['picture_id']);
        $up_time = date('Y-m-d H:i:s');

        // コメントをデータベースに追加
        $sql = "INSERT INTO Comments (commnets_text, reply_ID, reuser_ID, up_time) VALUES (?, NULL, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$comment_text, $reuser_id, $up_time]);

        // 追加されたコメントのIDを取得
        $comment_id = $pdo->lastInsertId();

        // 追加されたコメントを取得
        $comment_sql = "SELECT * FROM Comments 
                        JOIN UserData ON Comments.reuser_ID = UserData.user_ID
                        WHERE comments_ID = ?";
        $comment_stmt = $pdo->prepare($comment_sql);
        $comment_stmt->execute([$comment_id]);
        $comment = $comment_stmt->fetch(PDO::FETCH_ASSOC);

        // JSON形式でコメントデータを返す
        echo json_encode([
            'user_name' => $comment['user_name'],
            'comment_text' => $comment['commnets_text'],
            'up_time' => $comment['up_time']
        ]);
    }
}
?>
