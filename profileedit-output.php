<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php
$pdo=new PDO($connect,USER,PASS);
if(isset($_SESSION['UserData'])){
    $id=$_SESSION['UserData']['user_id'];
    $sql=$pdo->prepare('select * from UserData where id!=? and mail=?');
    $sql->execute([$id,$_POST['mail']]);
}else{
    $sql=$pdo->prepare('select * from UserData where mail=?');
    $sql->execute([$_POST['mail']]);
}
if(empty($sql->fetchAll())){
    if(isset($_SESSION['UserData'])){
        $sql=$pdo->prepare('update UserData set name=?, mail=?, pass=? where id=?');
        $sql->execute([
            $_POST['name'],$_POST['mail'],$_POST['pass'],$id]);
        $_SESSION['UserData']=[
            'id'=>$id,'name'=>$_POST['name'],
        'mail'=>$_POST['mail'],
        'pass'=>$_POST['pass']];
        echo 'プロフィールを更新しました。';
        echo '<form action="home.php" method="post">';
        echo '<button type="submit">ホームに戻る</button>';
}else{
    $sql=$pdo->prepare('insert into UserData values(null,?,?,?)');
    $sql->execute([
        $_POST['name'],$_POST['mail'],$_POST['pass']]);
        echo 'プロフィールを更新しました。';
        echo '<form action="home.php" method="post">';
        echo '<button type="submit">ホームに戻る</button>';
    }
}else{
    echo 'メールアドレス及びパスワードがすでに使用されていますので、変更してください。';
    echo '<form action= "profileedit-input.php" method="post">';
    echo '<input type="submit" value="変更">';
}
?>

