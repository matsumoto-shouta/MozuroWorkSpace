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
        <h2>画像ギャラリー</h2>
    <div class="gallery">
        <?php
        require 'DB-connect.php'; // データベース接続をインクルード

        // 画像情報をデータベースから取得
        $sql = "SELECT picture_name FROM Picture";
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='gallery-item'>";
                echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
                echo "</div>";
            }
        } else {
            echo "ギャラリーに画像がありません。";
        }
        ?>
    </div>
        <br>
        <a href="logout_input.php" id="hi">ログアウトへ</a>
        </div>
</div>
