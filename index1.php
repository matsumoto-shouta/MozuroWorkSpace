<?php
session_start();

if (!isset($_SESSION["user_ID"])) {
    header("Location: login.php");
    exit();
}

echo "ユーザーID: " . $_SESSION["user_ID"] . "<br>";

// データベース接続
$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1517807"; // データベースのユーザー名
$password = "Pass0514"; // データベースのパスワード
$dbname = "LAA1517807-insta"; // データベース名

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 写真と説明文の投稿
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload"])) {
    $user_ID = $_SESSION["user_ID"];
    $caption = $_POST["caption"];
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $file_destination = "uploads/" . $file_name;

    // uploadsディレクトリが存在しない場合は作成する
    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // ファイルの移動
    if (move_uploaded_file($file_tmp, $file_destination)) {
        echo "ファイルがアップロードされました";

        // Pictureテーブルにレコードを挿入し、新しいpicture_IDを取得
        $stmt = $conn->prepare("INSERT INTO Picture (picture_name) VALUES (?)");
        $stmt->bind_param("s", $file_name);
        $stmt->execute();
        $picture_ID = $stmt->insert_id; // 挿入されたレコードのIDを取得
        $stmt->close();

        // Uploadテーブルにレコードを挿入（comments_IDはNULLのまま）
        $stmt = $conn->prepare("INSERT INTO Upload (user_ID, caption, picture_ID) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $user_ID, $caption, $picture_ID);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "ファイルのアップロードに失敗しました";
    }
}

// 写真とコメントのデータを取得
$sql = "SELECT Upload.*, Comments.commnets_text, Picture.picture_name 
        FROM Upload 
        LEFT JOIN Comments ON Upload.picture_ID = Comments.reply_ID
        LEFT JOIN Picture ON Upload.picture_ID = Picture.picture_ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<img src='uploads/" . $row["picture_name"] . "' alt='" . $row["caption"] . "' onclick='showComments(" . $row["picture_ID"] . ")' style='max-width: 300px;'><br>";
        echo "説明文: " . $row["caption"] . "<br>";
        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "写真はまだ投稿されていません";
}

// データベース接続のクローズ
$conn->close();
?>

<script>
function showComments(picture_ID) {
    window.location.href = "comments.php?picture_ID=" + picture_ID;
}
</script>

<!DOCTYPE html>
<html>
<head>
    <title>写真の投稿</title>
</head>
<body>
    <h2>写真の投稿</h2>
    <form method="post" action="" enctype="multipart/form-data">
        説明文: <input type="text" name="caption" required><br>
        写真: <input type="file" name="file" required><br>
        <input type="submit" name="upload" value="投稿">
    </form>
</body>
</html>
