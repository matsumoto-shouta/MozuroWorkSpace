<?php session_start(); ?>
<div class="all">
<link rel="stylesheet" href="css/Logout.css">
    <div class="flex">
<figure class="image">
<img src ="image/instakiro.png" width="100" height="100">
</figure>
    <h1>ログアウト</h1>
</div>
<div id='a'>
<?php
if(isset($_SESSION['UserData'])){
    unset($_SESSION['UserData']);
    echo '<p class="log">ログアウトしました。</p>';
}else{
    echo '<p class="log">既にログアウトしています。</p>';
}
?>

</div>
    <a href="login_input.php" id="my"><button class="btn">ログインへ戻る</button></a>
</div>
