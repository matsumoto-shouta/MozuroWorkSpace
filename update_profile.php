<?php
ob_start(); // 出力バッファリングを開始

session_start();
require 'db-connect.php';

// アップロードディレクトリのパス
$upload_dir = 'uploads/';

// アップロードディレクトリが存在するか確認し、存在しない場合は作成
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if(isset($_SESSION['UserData']['id'])){
    $user_id = $_SESSION['UserData']['id'];
    $user_name = $_POST['user_name'];
    $user_picture_path = null;

    if(isset($_FILES['user_picture']) && $_FILES['user_picture']['error'] == UPLOAD_ERR_OK){
        $user_picture_path = $upload_dir . basename($_FILES['user_picture']['name']);
        if (!move_uploaded_file($_FILES['user_picture']['tmp_name'], $user_picture_path)) {
            echo "Failed to move uploaded file.";
            exit();
        }
    }

    $sql = "UPDATE UserData SET user_name = :user_name";
    if($user_picture_path){
        $sql .= ", user_picture = :user_picture";
    }
    $sql .= " WHERE user_ID = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    if($user_picture_path){
        $stmt->bindParam(':user_picture', $user_picture_path, PDO::PARAM_STR);
    }
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: mypage.php");
    exit();
}

ob_end_flush(); // 出力バッファをフラッシュして終了
?>
