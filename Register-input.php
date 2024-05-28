<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/Register.css">
    <title>ユーザー登録(入力)</title>
</head>
<body>

    <h1>ユーザー情報の登録</h1>

    <form action="Register-output.php" method="post">

    <div class="form-group">
        <input type="label" name="user_name" placeholder="ユーザーネーム">
        <input type="label" name="mail" placeholder="メールアドレス">
        <input type="password" name="pass" placeholder="パスワード">
    </div>
    <button type="submit">登録</button>
    </form>

</body>
</html>