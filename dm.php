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
    $imagePath = NULL;

    // 画像のアップロード処理
    // if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    //     $uploadDir = 'uploads/';
    //     $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    //     $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    //     $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    //     // 画像のサイズと種類の検証
    //     if (in_array($imageFileType, $allowedTypes) && $_FILES['image']['size'] < 5000000) { // 5MB以下
    //         if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
    //             $imagePath = $uploadFile;
    //         }
    //     }
    // }

    $sql = "INSERT INTO message (text, image, user_id, destination_user_id) VALUES (:text, :image, :user_id, :destination_user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
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
            background: linear-gradient(106deg, #6fad44, #34c2db);
            font-family: 'Helvetica Neue', sans-serif;
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
            height: 100px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }
        .message-form input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
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
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .message-meta {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
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

        <form class="message-form" action="" method="post" enctype="multipart/form-data">
            <textarea name="text" placeholder="メッセージを入力してください"></textarea>
            <!-- <input type="file" name="image" accept="image/*"> -->
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

        <!-- ユーザー一覧ページに戻るリンク -->
        <a href="User_list.php" class="back-button">ユーザー一覧に戻る</a>
    </div>
</body>
</html>
