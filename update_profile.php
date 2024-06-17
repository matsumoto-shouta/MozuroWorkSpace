<?php
// データベース接続情報
require 'db-connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_ID = $_POST['user_ID'];
    $user_name = $_POST['user_name'];
    $pass = $_POST['pass'];

    try {
        // データベース接続の確立
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQLクエリの準備と実行
        $stmt = $pdo->prepare("UPDATE UserData SET user_name=:user_name, pass=:pass WHERE user_ID=:user_ID");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':user_ID', $user_ID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "プロフィールが更新されました。";
        } else {
            echo "更新に失敗しました。";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // データベース接続を閉じる
    $pdo = null;
} else {
    echo "Error: フォームデータが正しく設定されていません。";
}
?>
