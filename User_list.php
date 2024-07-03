<?php
session_start();
require 'db-connect.php';

$sql = "SELECT user_ID, user_name, user_picture FROM UserData";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <?php require "hamburger.php"; ?>
    <title>ユーザー一覧</title>
    <style>
        .user-list { list-style: none; padding: 0; }
        .user-item { display: flex; align-items: center; margin-bottom: 10px; }
        .user-item img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
        .user-item a { text-decoration: none; color: #000; }
    </style>
</head>
<body>
    <h2>ユーザー一覧</h2>
    <ul class="user-list">
        <?php foreach ($users as $user): ?>
            <li class="user-item">
                <img src="<?php echo htmlspecialchars($user['user_picture']); ?>" alt="ユーザーアイコン">
                <a href="dm.php?user_id=<?php echo htmlspecialchars($user['user_ID']); ?>">
                    <?php echo htmlspecialchars($user['user_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
