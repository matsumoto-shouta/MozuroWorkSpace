<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録(入力)</title>
</head>
<body>

    <h1>ユーザー情報の登録</h1>

    <from action="Register-output.php" method="post">

    <p>ユーザーネーム</p>
    <input type="label" name="user_name">
    <p>メールアドレス</p>
    <input type="label" name="mail">
    <p>パスワード</p>
    <input type="password" name="pass">

    <button type="submit">登録</button>
    </from>

</body>
</html>