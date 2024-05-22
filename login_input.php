<div class="main">
<link rel="stylesheet" href="css/login_input.css">
<img class="main-image" src="image/wakadan.jpg">
<form action="login_output.php" method="post">
    <p class="main-a">ユーザー名　　　<input type="text" name="login"><br></p>
    <p class="main-a">_____________________________________</p>
    <p class="main-a">パスワード　　　<input type="password" name="password"><br></p>
    <p class="main-a">_____________________________________</p>
    <input type="submit" value="ログイン">
</form>
<p>初めての方はこちらから</p>
<p>　　　　　↓　　　　　</p>
<a href="Register-input.php"><button>新規登録</button></a>
</div>
<?php require 'footer.php'; ?>    