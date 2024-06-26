<?php
session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');

// ファイルの他の部分を含める前に
include('db-connect.php');

// セッションで設定したIDを使いやすい変数に入れる
$user_id = isset($_SESSION['UserData']['id']) ? $_SESSION['UserData']['id'] : null;
$caption = isset($_POST['caption']) ? $_POST['caption'] : '';

// ユーザーIDが設定されていることを確認
if ($user_id === null) {
    die("ユーザーIDが設定されていません。");
}

// アップロードフォルダの指定
$target_dir = "image/";

// フォルダが存在しない場合、作成
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
        die("ディレクトリの作成に失敗しました: $target_dir");
    }
}

// アップロードされたファイルの情報を取得
$original_filename = basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
$unique_name = uniqid() . '.' . $imageFileType;
$target_file = $target_dir . $unique_name;

$uploadOk = 1;

// 画像ファイルが実際の画像かどうかをチェック
$check = getimagesize($_FILES["file"]["tmp_name"]);
if ($check !== false) {
    echo "ファイルは画像です - " . $check["mime"] . "です。<br>";
    $uploadOk = 1;
} else {
    echo "ファイルは画像ではありませんが、アップロードを続行します。<br>";
    $uploadOk = 1; // 画像でなくてもアップロードを続行する
}

// ファイルサイズをチェック (5MB以下)
if ($_FILES["file"]["size"] > 5000000) {
    echo "申し訳ありませんが、ファイルサイズが大きすぎます。<br>";
    $uploadOk = 0;
}

// アップロードが許可されているかチェック
if ($uploadOk == 0) {
    echo "申し訳ありませんが、ファイルはアップロードされませんでした。<br>";
} else {
    // ファイルをアップロード
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
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
            echo "ファイル ". htmlspecialchars($original_filename) . " がアップロードされました。<br>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "申し訳ありませんが、データベースへの保存中にエラーが発生しました: " . $e->getMessage() . "<br>";
            error_log($e->getMessage(), 3, '/path/to/your/logfile.log'); // エラーログに記録
        }
    } else {
        echo "申し訳ありませんが、ファイルのアップロード中にエラーが発生しました。<br>";
    }
}

header('Location: home.php');
exit();
ob_end_flush(); // 出力バッファをフラッシュして終了
