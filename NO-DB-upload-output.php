<?php
// アップロードフォルダの指定
$target_dir = "uploads/";

// フォルダが存在しない場合、作成
if (!is_dir($target_dir)) {
    // ディレクトリの作成
    if (mkdir($target_dir, 0777, true)) {
        echo "ディレクトリが作成されました: $target_dir<br>";
    } else {
        echo "ディレクトリの作成に失敗しました: $target_dir<br>";
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
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "申し訳ありませんが、JPG, JPEG, PNG, 及び GIF ファイルのみ許可されています。<br>";
    $uploadOk = 0;
}

// アップロードが許可されているかチェック
if ($uploadOk == 0) {
    echo "申し訳ありませんが、ファイルはアップロードされませんでした。<br>";
} else {
    // ファイルをアップロード
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "ファイル ". htmlspecialchars($original_filename) . " がアップロードされました。<br>";
    } else {
        echo "申し訳ありませんが、ファイルのアップロード中にエラーが発生しました。<br>";
    }
}

// アップロード後に画像ギャラリーページにリダイレクト
header('Location: upload-input.php');
exit();
?>
