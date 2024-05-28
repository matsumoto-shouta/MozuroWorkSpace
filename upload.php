<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php


// フォームデータの取得
$up_ID=$_POST['up_ID']
$user_ID = $_POST['user_ID'];
$caption = $_POST['caption'];
$picture_ID = $_POST['picture_ID'];


// SQLステートメントの準備
$stmt = $connect->prepare("INSERT INTO Upload (up_ID,user_ID, caption, picture_ID) VALUES (null, ?, ? ,?)");
$stmt->bind_param("isi", $up_ID, $user_ID, $caption, $picture_ID);

// クエリの実行
if ($stmt->execute()) {
    echo "新しい投稿が作成されました。";
} else {
    echo "エラー: " . $stmt->error;
}

// 接続のクローズ
$stmt->close();
$connect->close();
?>
