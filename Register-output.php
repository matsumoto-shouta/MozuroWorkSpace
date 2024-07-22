<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/Register.css">
    <title>ユーザー登録(出力)</title>
</head>
<body>
    <div class="result">
    <?php
        $pdo = new PDO($connect, USER, PASS);
        if(isset($_SESSION['UserData'])){
            $id = $_SESSION['UserData']['user_ID'];
            $sql = $pdo->prepare('select * from UserData where user_ID = ? and mail = ?');
            $sql->execute([$id, $_POST['mail']]);
        }else{
            $sql = $pdo->prepare('select * from UserData where mail =? and pass = ?');
            $sql->execute([$_POST['mail']], $_POST['pass']);
        }

        if(empty($sql->fetchAll())){
            if(isset($_SESSION['UserData'])){
                $sql = $pdo->prepare('insert into UserData(user_ID, user_name, mail, pass) values(?,?,?,?)');
                $sql->execute([$id,
                                $_POST['user_name'],
                                $_POST['mail'],
                                $_POST['pass']
                            ]);

                $_SESSION['UserData'] = [
                    'user_ID' => $id,
                    'user_name' => $_POST['user_name'],
                   'mail' => $_POST['mail']
                ];
                
                echo 'ユーザー情報を更新しました';
            }else{
                $sql = $pdo->prepare('insert into UserData(user_name, mail, pass) values(?,?,?)');
                $sql->execute([
                    $_POST['user_name'],
                    $_POST['mail'],
                    $_POST['pass']
                ]);

                echo 'ユーザー情報を登録しました';
            }
        }else{
            echo 'メールアドレスが重複しているか、必要情報が入力されていません';
        }
        ?>
    </div>
    
<a href="login_input.php"><button class="btn2">ログイン画面に戻る</button></a>
</body>
</html>