<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<link rel="stylesheet" href="css/Login.css">
<img src="image/instakiro.png" width="100" height="100">
<h1>ログイン<h1>
<?php 
unset($_SESSION['UserData']);
if($_POST['password'] != null && $_POST['login'] != null){
$sql = $pdo->prepare('select * from UserData where user_name=?');
$sql->execute([$_POST['login']]);

foreach($sql as $row){
    
    if($_POST["password"]==$row['pass']){
    $_SESSION['UserData']=[
        // rowの中身にsql（DB）の内容が入ってる。rowの中身をセッションのそれぞれの所に入れてる。

        //ここバリ重要！！！！！！！！！！！！！！！！！！！！！！！！！！！

        'id'=>$row['user_ID'],
        'name'=>$row['user_name'],
        'mail'=>$row['mail'],
        'pass'=>$row['pass']];

        //おらーーーーーーーーーーー！！！！！！！！！！！！！！！！！！！

    }
}
if(isset($_SESSION['UserData'])){
    echo '<p class="log">いらっしゃいませ、',$_SESSION['UserData']['name'],'さん。</p>';
    echo '<form action="home.php" method="post">';
    echo '<button class="btn3" type="submit">ホーム画面へ</button>';
    echo '</form>';

}else{
    echo '<p class="log">ログイン名またはパスワードが違います。</p>';
    echo '<form action="login_input.php" method="post">';
    echo '<button class="btn3" type="submit">ログイン画面へ</button>';
    echo '</form>';
}
}else{
    echo '<p class="log">ログイン名またはパスワードを入力してください。</p>';
    echo '<form action="login_input.php" method="post">';
    echo '<button class="btn3" type="submit">ログイン画面へ</button>';
    echo '</form>';
}