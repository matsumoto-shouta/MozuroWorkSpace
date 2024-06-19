<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/mypage.css">
    <title>マイページ</title>
</head>
<body>
<form action="icon.php" method="post" enctype="multipart/form-data">
        <label for="file">アイコン画像を選択:</label>
        <input type="file" name="file" id="file">
        <button type="submit">アップロード</button>
    </form>
    <img src="image/kkrn_icon_user_13.png" alt="ユーザーアイコン" style="width: 100px; height: 100px;"><br>

    <form action="edit_profile.php" method="post">
        <button type="submit">プロフィールを編集</button>
    </form><br>

    <!-- <img src="uploads/uploaded_image.png" alt="アップロードされた画像"><br> -->
    <div class="container">
    <div class="gallery">
        <?php
        if(isset($_SESSION['UserData']['id'])){
            $sql = "SELECT * FROM Upload 
                    JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                    JOIN UserData ON UserData.user_ID = Upload.user_ID";
            $stmt = $pdo->query($sql);

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='gallery-item'>";
                    echo "<a href='image.php?id=" . htmlspecialchars($row['picture_ID']) . "'>";
                    echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
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
    
    <form action="home.php" method="post">
        <button type="submit">ホームに戻る</button>
    </form>
</body>
</html>