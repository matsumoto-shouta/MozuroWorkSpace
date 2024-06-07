<?php session_start(); ?>
<link rel="stylesheet" href="css/shohin_top.css?v=1.0.1">
<?php require 'DB-connect.php'; ?>  
<div class="flex">
    <figure class="image">
    <img src ="image/rogo.jpg">
    </figure>
    <div id="fm">
    <!-- S<form action="shohin.php" method="post"> -->
    <!-- S<input type="text" name="kensaku" size="70" ><input type="submit" value="検索" size="35" > -->
    </form>
    </div>
        <div id="div">   
            <h1>ホーム画面</h1>
        <a href="mypage.php" id="hi">マイページへ</a>
        <a href="index.php" id="hi">アップロード画面</a>
        <h2>画像ギャラリー</h2>
    <div class="gallery">
    <?php
    if(isset($_SESSION['UserData']['id'])){
    //echo "セッションにユーザーIDが保存されています: " . htmlspecialchars($_SESSION['UserData']['id']);

        // 画像情報をデータベースから取得
        //$sql1 = "SELECT picture_name FROM Picture";
        //$sql2 = "SELECT * FROM Upload";

        $sql = "SELECT * FROM Upload 
         JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
         JOIN UserData ON UserData.user_ID = Upload.user_ID";
         ;

        $stmt = $pdo->query($sql);
        //$stmt2 = $pdo->query($sql2);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='gallery-item'>";
                echo htmlspecialchars($row['user_name']);
                echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
                echo htmlspecialchars($row['caption']);
                echo "</div>";
            }
        } else {
            echo "ギャラリーに画像がありません。";
        }}
        else {
            echo "ログインしてください";
            }
            ?>
    </div>
        <br>
        <a href="logout_input.php" id="hi">ログアウトへ</a>
        </div>
</div>
