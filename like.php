<?php
session_start();
include 'db-connect.php';

$user_ID = $_SESSION['UserData']['id'] ?? null;
$post_ID = $_POST['post_ID'] ?? null;

if ($user_ID && $post_ID) {
    // この画像に既に「いいね」しているかどうかをチェック
    $check_sql = "SELECT * FROM likes WHERE post_id = ? AND user_ID = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$post_ID, $user_ID]);

    if ($check_stmt->rowCount() > 0) {
        // 既に「いいね」している場合は「いいね」を削除
        $delete_sql = "DELETE FROM likes WHERE post_id = ? AND user_ID = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$post_ID, $user_ID]);
    } else {
        // まだ「いいね」していない場合は「いいね」を追加
        $insert_sql = "INSERT INTO likes (post_id, user_ID) VALUES (?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$post_ID, $user_ID]);
    }
}


$URL = 'https://'.$_SERVER['HTTP_HOST'].'/GitHub/MozuroWorkSpace/home.php';

echo '<script type="text/javascript">
      document.location.href = "'.$URL.'";
    </script>';