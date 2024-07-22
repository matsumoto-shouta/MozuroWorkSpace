<?php
session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
require 'db-connect.php';

// エラーメッセージを初期化
$error = '';
$success = '';

// セッションとファイル情報を取得
$user_id = isset($_SESSION['UserData']['id']) ? $_SESSION['UserData']['id'] : null;
$caption = isset($_POST['caption']) ? trim($_POST['caption']) : '';
$file = isset($_FILES['file']) ? $_FILES['file'] : null;

// ユーザーIDが設定されていることを確認
if ($user_id === null) {
    $error = "ユーザーIDが設定されていません。";
}

// キャプションの文字数を検証 (101文字以上)
if (strlen($caption) > 100) {
    $error = 'キャプションは100文字以内で入力してください。';
}

// 画像ファイルが選択されているかチェック
if (empty($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
    $error = '画像ファイルが選択されていません。';
}

// エラーメッセージがない場合はファイルをアップロード
if (empty($error)) {
    $target_dir = "image/";
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            $error = "ディレクトリの作成に失敗しました: $target_dir";
        }
    }

    $original_filename = basename($file["name"]);
    $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    $unique_name = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $unique_name;

    $uploadOk = 1;

    // 画像ファイルが実際の画像かどうかをチェック
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        $error = 'ファイルは画像ではありません。';
        $uploadOk = 0;
    }

    // ファイルサイズをチェック (5MB以下)
    if ($file["size"] > 5000000) {
        $error = '申し訳ありませんが、ファイルサイズが大きすぎます。';
        $uploadOk = 0;
    }

    // アップロードが許可されているかチェック
    if ($uploadOk == 0) {
        $error = '申し訳ありませんが、ファイルはアップロードされませんでした。';
    } else {
        // ファイルをアップロード
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();

                $pic = $pdo->prepare("INSERT INTO Picture (picture_name) VALUES (?)");
                $pic->execute([$target_file]);

                $picture_ID = $pdo->lastInsertId(); // picture_IDを取得

                if ($picture_ID == 0) {
                    throw new Exception("Picture テーブルへの INSERT に失敗しました。");
                }

                $up = $pdo->prepare("INSERT INTO Upload (user_ID, caption, picture_ID) VALUES (?, ?, ?)");
                $up->execute([$user_id, $caption, $picture_ID]);

                $pdo->commit();
                $success = 'ファイルがアップロードされました。';
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "申し訳ありませんが、データベースへの保存中にエラーが発生しました: " . $e->getMessage();
                error_log($e->getMessage(), 3, '/path/to/your/logfile.log'); // エラーログに記録
            }
        } else {
            $error = '申し訳ありませんが、ファイルのアップロード中にエラーが発生しました。';
        }
    }
}

// HTMLファイルにエラーメッセージと成功メッセージを渡して表示
include 'index.php';
ob_end_flush(); // 出力バッファをフラッシュして終了
?>
