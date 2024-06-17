<!DOCTYPE html>
<html>
<head>
    <title>プロフィール編集</title>
</head>
<body>
    <h1>プロフィール編集</h1>
    <form action="update_profile.php" method="post">
        <input type="hidden" name="user_ID" value="1"> <!-- ユーザーIDをここに設定 -->
        <label for="user_name">ユーザー名:</label>
        <input type="text" id="user_name" name="user_name" required><br>
        <label for="pass">パスワード:</label>
        <input type="password" id="pass" name="pass" required><br>
        <input type="submit" value="更新">
    </form>
</body>
</html>
