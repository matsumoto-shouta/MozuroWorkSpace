<?php
session_start();
require 'db-connect.php';

if (!isset($_GET['user_id'])) {
    echo "ユーザーIDが指定されていません。";
    exit;
}

$user_id = $_GET['user_id'];

$sql = "SELECT user_name, user_picture FROM UserData WHERE user_ID = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "指定されたユーザーが見つかりません。";
    exit;
}

// Check if user picture is set, otherwise use default icon
$user_picture = !empty($user['user_picture']) ? htmlspecialchars($user['user_picture']) : 'image/icon.jpg';
$user_name = htmlspecialchars($user['user_name']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/profile.css">
    <title><?php echo $user_name; ?>のプロフィール</title>
    <?php require 'hamburger.php'; ?>
    <style>
        .back-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="al">
        <div class="profile">
            <img src="<?php echo $user_picture; ?>" alt="ユーザーアイコン">
            <h2><?php echo $user_name; ?></h2>
        </div>

        <div class="container">
            <div class="gallery">
                <?php
                $sql = "SELECT Picture.picture_name, Picture.picture_ID, UserData.user_name, Upload.caption 
                        FROM Upload 
                        JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                        JOIN UserData ON UserData.user_ID = Upload.user_ID 
                        WHERE Upload.user_ID = :user_id"; 
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $default_picture = 'image/default-picture.jpg';
                        $picture_name = !empty($row['picture_name']) ? htmlspecialchars($row['picture_name']) : $default_picture;

                        echo "<div class='gallery-item'>";
                        echo "<a href='image.php?id=" . htmlspecialchars($row['picture_ID']) . "'>";
                        echo "<img src='" . $picture_name . "' alt=''>";
                        if (!empty($row['caption'])) {
                            echo "<div class='overlay'>";
                            echo "<div class='text'>" . htmlspecialchars($row['caption']) . "</div>";
                            echo "</div>";
                        }
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>アップロードされた画像がありません。</p>";
                }
                ?>
            </div>
            <button class="back-button" onclick="history.back()">戻る</button>
        </div>
    </div>
</body>
</html>
