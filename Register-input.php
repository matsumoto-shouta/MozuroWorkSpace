<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/Register.css">
    <title>ユーザー登録(入力)</title>
</head>
<body>

    <h1>ユーザー情報の登録</h1>
<div class="all">
    <form action="Register-output.php" method="post">

    <div class="form-group">
            <input type="text" class="content" name="user_name" placeholder="ユーザーネーム">
            <input type="text" class="content" name="mail" placeholder="メールアドレス">
            <input type="password" class="content" name="pass" placeholder="パスワード">
    </div>
    <button class="btn" type="submit">登録</button>
    </form>
</div>
</body>
</html>