<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/mypage.css">
    <title>プロフィール編集</title>
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
        .preview {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        .preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 15px;
            border: 3px solid #000; /* 画像周りの枠線 */
        }
    </style>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profilePicturePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>
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
        <img src="<?php echo $user_picture; ?>" alt="ユーザーアイコン" id="profilePicture">
        <h2><?php echo $user_name; ?></h2>
    </div>

    <form action="update_profile.php" method="post" enctype="multipart/form-data">
        <label for="user_name">ユーザー名:</label><br>
        <input type="text" id="user_name" name="user_name" value="<?php echo $user_name; ?>"><br><br>
        
        <label for="user_picture">プロフィール画像:</label><br>
        <input type="file" id="user_picture" name="user_picture" accept="image/*" onchange="previewImage(event)"><br><br>

        <div class="preview">
            <img id="profilePicturePreview" src="<?php echo $user_picture; ?>" alt="プレビュー画像">
        </div><br>

        <button type="submit">更新</button>
    </form>

    <form action="mypage.php" method="post">
        <button type="submit">キャンセル</button>
    </form>
</body>
</html>
