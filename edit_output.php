<?php
session_start();

if (!isset($_SESSION['user_ID'])) {
    // ログインしていない場合、ログインページにリダイレクト
    header("Location: login_input.php");
    exit();
}

require 'db-connect.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_ID = $_SESSION['user_ID'];
    
    $stmt = $pdo->prepare("SELECT user_ID, user_name FROM UserData WHERE user_ID=:user_ID");
    $stmt->bindParam(':user_ID', $user_ID, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "ユーザーID: " . htmlspecialchars($user['user_ID']) . "<br>";
        echo "ユーザー名: " . htmlspecialchars($user['user_name']) . "<br>";
    } else {
        echo "ユーザーが見つかりません。";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
