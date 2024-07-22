<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/Register.css">
    <title>ユーザー登録(入力)</title>
</head>
<body>
<div class="all">
    <h1>ユーザー情報の登録</h1>
    <form action="Register-output.php" method="post">
        <div class="form-group">
            <input type="text" class="content" name="user_name" placeholder="ユーザーネーム">
            <input type="text" class="content" name="mail" placeholder="メールアドレス">
            <input type="password" class="content" name="pass" placeholder="パスワード">
        </div>
        <div class="group">
            <button class="bbtn" type="button" onclick="window.location.href='login_input.php'">戻る</button>
            <button class="btn" type="submit">登録</button>
        </div>
    </form>
</div>
</body>
</html>
