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
$sql = "SELECT message.*, UserData.user_name, UserData.user_picture FROM message 
        JOIN UserData ON message.user_id = UserData.user_ID 
        WHERE (message.user_id = :current_user_id AND message.destination_user_id = :destination_user_id) 
           OR (message.user_id = :destination_user_id AND message.destination_user_id = :current_user_id) 
        ORDER BY message.created_id DESC";
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
            margin-bottom: 20px;
        }
        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .profile h2 {
            margin: 0;
            font-size: 24px;
        }
        .message-form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .message-form textarea, 
        .message-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .message-form button {
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message-form button:hover {
            background-color: #4cae4c;
        }
        .message-history {
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #fff;
        }
        .message img {
            max-width: 100px;
            max-height: 100px;
            display: block;
            margin: 10px 0;
        }
        .message .sender {
            font-weight: bold;
        }
        .message .timestamp {
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php require "hamburger.php"; ?>

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
        <div class="message-history">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <p class="sender"><?php echo htmlspecialchars($message['user_name']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($message['text'])); ?></p>
                        <?php if (!empty($message['image'])): ?>
                            <img src="<?php echo htmlspecialchars($message['image']); ?>" alt="画像">
                        <?php endif; ?>
                        <p class="timestamp"><?php echo htmlspecialchars($message['created_id']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>メッセージがありません。</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
