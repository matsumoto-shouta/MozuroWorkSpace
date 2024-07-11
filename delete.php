<?php
session_start();
include('db-connect.php');

// ユーザーIDの取得
$user_id = isset($_SESSION['UserData']['id']) ? $_SESSION['UserData']['id'] : null;

if ($user_id === null) {
    die("ユーザーIDが設定されていません。");
}

// フォームから送信された投稿IDを取得
if (isset($_POST['up_ID'])) {
    $up_ID = $_POST['up_ID'];

    try {
        // 投稿が現在のユーザーのものであることを確認
        $stmt = $pdo->prepare('SELECT * FROM Upload WHERE up_ID = ? AND user_ID = ?');
        $stmt->execute([$up_ID, $user_id]);
        $upload = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($upload) {
            // 投稿が存在し、ユーザーが所有している場合、削除を実行
            $pdo->beginTransaction();
            
            // コメントの削除
            $stmt = $pdo->prepare('DELETE FROM Comments WHERE comments_ID = ?');
            $stmt->execute([$up_ID]);

            // 投稿の削除
            $stmt = $pdo->prepare('DELETE FROM Upload WHERE up_ID = ?');
            $stmt->execute([$up_ID]);

            $pdo->commit();

            // 削除後、成功メッセージを表示するためにセッションに保存
            $_SESSION['success_message'] = "投稿が削除されました。";
        } else {
            $_SESSION['error_message'] = "不正な操作が検出されました。";
        }
        
        header('Location: mypage.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo '削除中にエラーが発生しました: ' . $e->getMessage();
    }
} else {
    // 投稿IDが送信されていない場合のエラー処理
    echo "削除する投稿が選択されていません。";
}
?>
