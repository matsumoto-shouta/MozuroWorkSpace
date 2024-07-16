<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['picture_id']) && isset($_SESSION['UserData']['id'])) {
    $picture_id = htmlspecialchars($_POST['picture_id']);
    $user_id = $_SESSION['UserData']['id'];

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // トランザクション開始
        $pdo->beginTransaction();

        // ユーザーが投稿したものか確認
        $stmt = $pdo->prepare("SELECT user_ID FROM Upload WHERE picture_ID = ? AND user_ID = ?");
        $stmt->execute([$picture_id, $user_id]);
        $upload = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($upload) {
            // 投稿を削除
            $stmt = $pdo->prepare("DELETE FROM Upload WHERE picture_ID = ?");
            $stmt->execute([$picture_id]);

            // 関連するコメントも削除
            $stmt = $pdo->prepare("DELETE FROM Comments WHERE picture_ID = ?");
            $stmt->execute([$picture_id]);

            // トランザクションをコミット
            $pdo->commit();

            // 削除が完了した後、リダイレクト
            header("Location: home.php");
            exit();
        } else {
            // ユーザーが投稿していない場合、エラーメッセージを表示
            echo "この投稿を削除する権限がありません。";
            exit();
        }
    } catch (Exception $e) {
        // エラーが発生した場合、ロールバック
        $pdo->rollBack();
        echo "投稿の削除中にエラーが発生しました: " . $e->getMessage();
        exit();
    }
} else {
    echo "不正なリクエストです。";
    exit();
}
?>
