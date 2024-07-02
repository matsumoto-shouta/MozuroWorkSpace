<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/mypage.css">
    <title>マイページ</title>
    <style>
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 15px;
            border: 3px solid #000; /* 画像周りの枠線 */
        }
        .profile h2 {
            margin: 0;
        }
    </style>
</head>
<body>

    <img src="image/kkrn_icon_user_13.png" alt="ユーザーアイコン" style="width: 100px; height: 100px;"><br>
    <?php
    if(isset($_SESSION['UserData']['id'])){
        $user_id = $_SESSION['UserData']['id'];
        $sql = "SELECT user_name, user_picture FROM UserData WHERE user_ID = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user picture is set, otherwise use default icon
        $user_picture = !empty($user['user_picture']) ? htmlspecialchars($user['user_picture']) : 'image/icon.jpg';
        $user_name = htmlspecialchars($user['user_name']);
    }
    ?>

    <div class="profile">
        <img src="<?php echo $user_picture; ?>" alt="ユーザーアイコン">
        <h2><?php echo $user_name; ?></h2>
    </div>

    <div class="container">
        <!-- ユーザーのアップロードした画像の表示 -->
        <div class="gallery">
            <?php
            if(isset($_SESSION['UserData']['id'])){
                $user_id = $_SESSION['UserData']['id'];
                $sql = "SELECT Upload.*, Picture.picture_name, UserData.user_name 
                        FROM Upload 
                        JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                        JOIN UserData ON UserData.user_ID = Upload.user_ID 
                        WHERE Upload.user_ID = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='gallery-item'>";
                        echo "<a href='image.php?id=" . htmlspecialchars($row['picture_ID']) . "'>";
                        echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt=''>";
                        echo "<div class='overlay'>";
                        echo "<div class='text'>" . htmlspecialchars($row['user_name']) . "</div>";
                        echo "<div class='text'>" . htmlspecialchars($row['caption']) . "</div>";
                        echo "</div>";
                        echo "</a>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
    
    <form action="edit_profile.php" method="post">
        <button type="submit">プロフィールを編集</button>
    </form><br>
    
    <form action="home.php" method="post">
        <button type="submit">ホームに戻る</button>
    </form>
</body>
</html>
