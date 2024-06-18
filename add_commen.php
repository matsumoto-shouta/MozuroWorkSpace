<?php
session_start();
require 'DB-connect.php';

if (isset($_SESSION['UserData']['id']) && isset($_POST['picture_id']) && isset($_POST['comment_text'])) {
    $user_id = $_SESSION['UserData']['id'];
    $picture_id = htmlspecialchars($_POST['picture_id']);
    $comment_text = htmlspecialchars($_POST['comment_text']);

    $sql = "INSERT INTO Comments (comment_text) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$comment_text]);

    $sql1 = "ALTER TABLE Upload (comment_text) VALUES (?)";
    $stmt = $pdo->prepare($sql1);
    $stmt->execute([$comment_text]);

    header("Location: image.php?id=" . $picture_id);
} else {
    echo "コメントの追加に失敗しました。";
}
?>
