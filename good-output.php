<?php
session_start();
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=insta_clone', 'username', 'password');

// 現在のユーザーID（セッションから取得するか、ログイン機能がある場合はそちらから）
$current_user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];

    // すでにいいねしているかチェック
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $current_user_id]);
    
    if ($stmt->rowCount() > 0) {
        // いいねを取り消す
        $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $current_user_id]);
    } else {
        // いいねを追加
        $stmt = $pdo->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $current_user_id]);
    }

    // 前のページにリダイレクト
    header('Location: index.php');
    exit;
}
