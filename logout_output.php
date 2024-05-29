<?php session_start(); ?>
<?php require 'header.php'; ?>
<link rel="stylesheet" href="css/logout-output.css">
    <div class="flex">
<figure class="image">
<img src ="image/rogo.jpg">
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
    <a href="login_input.php" id="my"><button>ログインへ戻る</button></a>
<?php require 'footer.php'; ?>