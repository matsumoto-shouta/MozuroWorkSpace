<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['UserData']['id']) || !isset($_GET['user_id'])) {
    header('Location: User_list.php'); // ユーザー一覧ページにリダイレクト
    exit;
}

$current_user_id = $_SESSION['UserData']['id'];
$destination_user_id = $_GET['user_id'];

// メッセージ送信処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['text'];
    $image = isset($_POST['image']) ? $_POST['image'] : NULL;

    $sql = "INSERT INTO message (text, image, user_id, destination_user_id) VALUES (:text, :image, :user_id, :destination_user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':destination_user_id', $destination_user_id, PDO::PARAM_INT);
    $stmt->execute();

    // リダイレクトしてフォームの再送信を防ぐ
    header("Location: dm.php?user_id=" . $destination_user_id);
    exit;
}

// メッセージ取得
$sql = "SELECT m.*, u.user_name FROM message m
        JOIN UserData u ON m.user_id = u.user_ID
        WHERE (m.user_id = :current_user_id AND m.destination_user_id = :destination_user_id)
           OR (m.user_id = :destination_user_id AND m.destination_user_id = :current_user_id)
        ORDER BY m.created_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
$stmt->bindParam(':destination_user_id', $destination_user_id, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 選択したユーザー情報取得
$sql = "SELECT user_name, user_picture FROM UserData WHERE user_ID = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $destination_user_id, PDO::PARAM_INT);
$stmt->execute();
$destination_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>DM</title>
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
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .message-form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .message-form textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }
        .message-form input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }
        .message-form button {
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message-form button:hover {
            background-color: #0056b3;
        }
        .message-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .message-item {
            display: flex;
            padding: 10px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 60%;
            word-wrap: break-word;
        }
        .message-item.right {
            align-self: flex-end;
            background-color: #dcf8c6;
        }
        .message-item.left {
            align-self: flex-start;
            background-color: #fff;
        }
        .message-item p {
            margin: 0;
        }
        .message-item img {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
        }
        .message-meta {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php require "hamburger.php"; ?>

    <div class="container">
        <div class="profile">
            <img src="<?php echo htmlspecialchars($destination_user['user_picture']); ?>" alt="ユーザーアイコン">
            <h2><?php echo htmlspecialchars($destination_user['user_name']); ?></h2>
        </div>

        <form class="message-form" action="" method="post">
            <textarea name="text" placeholder="メッセージを入力してください"></textarea>
            <input type="text" name="image" placeholder="画像のパス（任意）">
            <button type="submit">送信</button>
        </form>

        <h2>メッセージ履歴</h2>
        <ul class="message-list">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <li class="message-item <?php echo $message['user_id'] == $current_user_id ? 'right' : 'left'; ?>">
                        <div>
                            <p><?php echo htmlspecialchars($message['text']); ?></p>
                            <?php if (!empty($message['image'])): ?>
                                <img src="<?php echo htmlspecialchars($message['image']); ?>" alt="画像">
                            <?php endif; ?>
                            <p class="message-meta">
                                <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($message['created_id']))); ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="message-item">
                    <p>メッセージがありません。</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>