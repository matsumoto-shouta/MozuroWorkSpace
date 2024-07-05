<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['UserData']['id'])) {
    header('Location: login.php'); // ログインページにリダイレクト
    exit;
}

$current_user_id = $_SESSION['UserData']['id'];

$sql = "SELECT user_ID, user_name, user_picture FROM UserData WHERE user_ID != :current_user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>ユーザー一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .user-list {
            list-style: none;
            padding: 0;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }
        .user-item:hover {
            background-color: #f9f9f9;
        }
        .user-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .user-item a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
        .user-item a:hover {
            color: #007BFF;
        }
        h2 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <?php require "hamburger.php"; ?>
    <div class="container">
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
    </div>
</body>
</html>
