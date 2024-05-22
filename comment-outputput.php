<?php
session_start();
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=insta_clone', 'username', 'password');

// 現在のユーザーID（セッションから取得するか、ログイン機能がある場合はそちらから）
$current_user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    if (!empty($comment)) {
        // コメントを追加
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $current_user_id, $comment]);
    }

    // 前のページにリダイレクト
    header('Location: index.php');
    exit;
}
