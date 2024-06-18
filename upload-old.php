<?php
session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');

// ファイルの他の部分を含める前に
include('db-connect.php');
//require 'DB-connect.php'; // データベース接続をインクルード

// セッションで設定したIDを使いやすい変数に入れてる
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
    echo "ファイルは画像ではありません。<br>";
    $uploadOk = 0;
}

// ファイルサイズをチェック (5MB以下)
if ($_FILES["file"]["size"] > 5000000) {
    echo "申し訳ありませんが、ファイルサイズが大きすぎます。<br>";
    $uploadOk = 0;
}

// 画像ファイルのフォーマットをチェック
if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
    echo "申し訳ありませんが、JPG, JPEG, PNG, 及び GIF ファイルのみ許可されています。<br>";
    $uploadOk = 0;
}

// アップロードが許可されているかチェック
if ($uploadOk == 0) {
    echo "申し訳ありませんが、ファイルはアップロードされませんでした。<br>";
} else {
    // ファイルをアップロード
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // データベースにファイルパスを保存
        $pic = $pdo->prepare("INSERT INTO Picture (picture_name) VALUES (?)");

        if ($pic) {
            $pdo->beginTransaction();
            try {
                $pic->execute([$target_file]);
                $picture_ID = $pdo->lastInsertId(); // picture_IDを取得

                // 投稿したユーザーID、キャプション、picture_ID、comments_ID（仮にNULL）、likes_ID（仮にNULL）を保存
                $up = $pdo->prepare("INSERT INTO Upload (user_ID, caption, picture_ID, comments_ID, likes_ID) VALUES (?, ?, ?, ?, ?)");
                $up->execute([$user_id, $caption, $picture_ID, null, null]);

                $pdo->commit();
                echo "ファイル ". htmlspecialchars($original_filename) . " がアップロードされました。<br>";
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "申し訳ありませんが、データベースへの保存中にエラーが発生しました。<br>";
            }
        } else {
            echo "申し訳ありませんが、データベースへの保存中にエラーが発生しました。<br>";
        }
    } else {
        echo "申し訳ありませんが、ファイルのアップロード中にエラーが発生しました。<br>";
    }
}

// アップロード後に画像ギャラリーページにリダイレクト
header('Location: home.php');
exit();
ob_end_flush(); // 出力バッファをフラッシュして終了
?>