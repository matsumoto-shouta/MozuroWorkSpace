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
    <img src="image/kkrn_icon_user_13.png" alt="ユーザーアイコン"><br>

    <form action="profileedit-input" method="post">
        <button type="submit">プロフィールを編集</button>
    </form><br>

    <img src="uploads/uploaded_image.png" alt="アップロードされた画像"><br>
    
    <form action="home.php" method="post">
        <button type="submit">ホームに戻る</button>
    </form>
</body>
</html>