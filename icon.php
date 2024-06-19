<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// 画像が実際に画像であるかをチェック
$check = getimagesize($_FILES["file"]["tmp_name"]);
if($check !== false) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // データベースにファイルパスを保存
        require 'db-connect.php';

        // 接続チェック
        if ($pdo->connect_error) {
            die("Connection failed: " . $pdo->connect_error);
        }

        $sql = "INSERT INTO UserData (user_picture) VALUES ('$target_file')";

        if ($pdo->query($sql) === TRUE) {
            echo "ファイル ". basename($_FILES["file"]["name"]). " がアップロードされ、データベースに保存されました。";
        } else {
            echo "Error: " . $sql . "<br>" . $pdo->error;
        }

        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "File is not an image.";
}
?>
