<?php session_start(); ?>
<?php require 'DB-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録(出力)</title>
</head>
<body>
    
    <?php
        $pdo = new PDO($connect, USER, PASS);
        if(isset($_SESSION['UserDate'])){
            $id = $_SESSION['UserDate']['user_ID'];
            $sql = $pdo->prepare('select * from UserDate where user_ID = ? and mail = ?');
            $sql->execute([$id, $_POST['mail']]);
        }else{
            $sql = $pdo->prepare('select * from UserDate where mail =?');
            $sql->execute([$_POST['mail']]);
        }

        if(empty($sql->fetchAll())){
            if(isset($_SESSION['UserData'])){
                $sql = $pdo->prepare('insert into UserDate(user_ID, user_name, mail, pass) values(?,?,?,?)');
                $sql->execute([$id,
                                $_POST['user_name'],
                                $_POST['mail'],
                                $_POST['pass']
                            ]);

                $_SESSION['UserData'] = [
                    'user_ID' => $id,
                    'user_name' => $_POST['user_name'],
                   'mail' => $_POST['mail'],
                ];
                
                echo 'ユーザー情報を更新しました';
            }else{
                $sql = $pdo->prepare('insert into UserDate(user_name, mail, pass) values(?,?,?)');
                $sql->execute([
                    $_POST['user_name'],
                    $_POST['mail'],
                    $_POST['pass']
                ]);

                echo 'ユーザー情報を登録しました';
            }
        }else{
            echo 'メールアドレスが重複しています';
        }
        ?>

</body>
</html>