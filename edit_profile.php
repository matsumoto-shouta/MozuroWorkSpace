<?php
session_start();
require 'db-connect.php';


try {
    // データベース接続の確立
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ユーザー情報の取得
    $user_ID = $_SESSION['UserData']['id'];
    $stmt = $pdo->prepare("SELECT user_name FROM UserData WHERE user_ID=:user_ID");
    $stmt->bindParam(':user_ID', $user_ID, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "ユーザーが見つかりません。";
        exit();
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>プロフィール編集</title>
</head>
<body>
    <h1>プロフィール編集</h1>
    <form action="update_profile.php" method="post">
        <label for="user_name">ユーザー名:</label>
        <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required><br>
        <label for="pass">パスワード:</label>
        <input type="password" id="pass" name="pass" required><br>
        <input type="submit" value="更新">
    </form>
</body>
</html>
