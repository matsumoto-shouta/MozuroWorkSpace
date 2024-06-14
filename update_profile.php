<?php
session_start();

if (!isset($_SESSION['user_ID'])) {
    // ログインしていない場合、ログインページにリダイレクト
    header("Location: login_input.php");
    exit();
}

require 'db-connect.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームデータの取得
    if (isset($_POST['user_name']) && isset($_POST['pass'])) {
        $user_ID = $_SESSION['user_ID'];
        $user_name = $_POST['user_name'];
        $pass = $_POST['pass']; 

        // プリペアドステートメントの使用
        $stmt = $pdo->prepare("UPDATE UserData SET user_name=:user_name, pass=:pass WHERE user_ID=:user_ID");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':user_ID', $user_ID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // 更新が成功した場合、output.phpにリダイレクト
            header("Location: edit_output.php");
            exit();
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error: Form data not set properly.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
