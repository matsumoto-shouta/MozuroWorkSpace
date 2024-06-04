<?php session_start();?>
<?php
$name=$mail=$pass='';
if(isset($_SESSION['UserData'])){
    $name=$_SESSION['UserData']['user_name'];
    $mail=$_SESSION['UserData']['mail'];
    $pass=$_SESSION['UserData']['pass'];

}
echo '<form action= "profileedit-output.php" method="post">';
echo '<table>';
echo '<tr><td>お名前</td><td>';
echo '<input type="text" name="name" value="',$name,'">';
echo '</td><tr>';
echo '<tr><td>メールアドレス</td><td>';
echo '<input type="text" name="mail" value="',$mail,'">';
echo '</td><tr>';
echo '<tr><td>パスワード</td><td>';
echo '<input type="text" name="pass" value="',$pass,'">';
echo '</td><tr>';
echo '</table>';
echo '<input type="submit" value="変更">';
echo '</form>';
?>
