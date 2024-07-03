<?php
session_start();
require 'db-connect.php';
require "hamburger.php";
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
}

// メッセージ取得
$sql = "SELECT * FROM message 
        WHERE (user_id = :current_user_id AND destination_user_id = :destination_user_id) 
           OR (user_id = :destination_user_id AND destination_user_id = :current_user_id) 
        ORDER BY created_id DESC";
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
        .message { margin-bottom: 10px; }
        .message img { max-width: 100px; max-height: 100px; }
        .profile { display: flex; align-items: center; margin-bottom: 15px; }
        .profile img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="profile">
        <img src="<?php echo htmlspecialchars($destination_user['user_picture']); ?>" alt="ユーザーアイコン">
        <h2><?php echo htmlspecialchars($destination_user['user_name']); ?></h2>
    </div>

    <form action="" method="post">
        <textarea name="text" placeholder="メッセージを入力してください"></textarea><br>
        <input type="text" name="image" placeholder="画像のパス（任意）"><br>
        <button type="submit">送信</button>
    </form>

    <h2>メッセージ履歴</h2>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <p>送信者: <?php echo htmlspecialchars($message['user_id']); ?></p>
                <p>メッセージ: <?php echo htmlspecialchars($message['text']); ?></p>
                <?php if (!empty($message['image'])): ?>
                    <img src="<?php echo htmlspecialchars($message['image']); ?>" alt="画像">
                <?php endif; ?>
                <p>送信日時: <?php echo htmlspecialchars($message['created_id']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>メッセージがありません。</p>
    <?php endif; ?>
</body>
</html>
