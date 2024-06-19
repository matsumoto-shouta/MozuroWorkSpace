<?php
require 'db-connect.php';

// 接続チェック
if ($pdo->connect_error) {
    die("Connection failed: " . $pdo->connect_error);
}

$sql = "SELECT user_picture FROM UserData";
$result = $pdo->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<img src='" . $row["user_picture"] . "' alt='Image' style='width: 50px; height: 50px;'><br>";
    }
} else {
    echo "No images found.";
}
$pdo->close();
?>
