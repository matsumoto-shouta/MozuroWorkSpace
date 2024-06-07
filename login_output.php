<?php session_start(); ?>
<?php require 'DB-connect.php'; ?>
<link rel="stylesheet" href="css/login_output.css?v=1.0.1">
<div class="flex">
<figure class="image"><img 
src="image/rogo.jpg">
</figure>
<h1>ログイン<h1>
</div>
<?php 
unset($_SESSION['UserData']);
if($_POST['password'] != null && $_POST['login'] != null){
$pdo = new PDO($connect,USER,PASS);
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
    echo '<a href="home.php" id="my"><button>ホーム画面へ</button></a>';

}else{
    echo '<p class="log">ログイン名またはパスワードが違います。</p>';
    echo '<a href="login_input.php" id="my"><button>ログイン画面へ</button></a>';
}
}else{
    echo '<p class="log">ログイン名またはパスワードを入力してください。</p>';
    echo '<a href="login_input.php" id="my"><button>ログイン画面へ</button></a>';
}