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
    
    $stmt = $pdo->prepare("SELECT user_name FROM UserData WHERE user_ID=:user_ID");
    $stmt->bindParam(':user_ID', $user_ID, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "ユーザーが見つかりません。";
        exit();
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>プロフィール編集フォーム</title>
</head>
<body>
    <form action="update_profile.php" method="post">
        <label for="user_name">ユーザー名:</label>
        <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required><br>
        <label for="pass">パスワード:</label>
        <input type="password" id="pass" name="pass" required><br>
        <input type="submit" value="更新">
    </form>
</body>
</html>
