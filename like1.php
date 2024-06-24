<?php
session_start();
require 'db-connect.php';

if (isset($_POST['post_ID']) && isset($_SESSION['UserData']['id'])) {
    $post_ID = $_POST['post_ID'];
    $user_ID = $_SESSION['UserData']['id'];

    // いいねの重複を防ぐためにチェック
    $sql = "SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['post_id' => $post_ID, 'user_id' => $user_ID]);

    if ($stmt->rowCount() == 0) {
        $sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['post_id' => $post_ID, 'user_id' => $user_ID]);
    }
}

header('Location: home.php');
exit;
?>
