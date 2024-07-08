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

// デフォルト画像のURL
$default_picture = 'img/defalt.png'; // 適切なパスに置き換えてください
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー　ー覧</title>
    <link rel="stylesheet" href="css/user_list.css">
    <?php require 'hamburger.php'; ?>
</head>
<body>
    <div class="container">
        <h2>ユーザー　ー覧</h2>
        <ul class="user-list">
            <?php foreach ($users as $user): ?>
                <?php
                // 画像URLが空の場合、デフォルト画像を使用
                $user_picture = !empty($user['user_picture']) ? htmlspecialchars($user['user_picture']) : $default_picture;
                ?>
                <li class="user-item">
                    <img src="<?php echo $user_picture; ?>" alt="ユーザーアイコン">
                    <div class="links">
                        <a href="profile.php?user_id=<?php echo htmlspecialchars($user['user_ID']); ?>">
                            <?php echo htmlspecialchars($user['user_name']); ?> のプロフィール
                        </a>
                        <a href="dm.php?user_id=<?php echo htmlspecialchars($user['user_ID']); ?>">
                            DM
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
