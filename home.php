<?php session_start(); ?>
<link rel="stylesheet" href="css/home.css?v=1.0.1">
<?php require 'db-connect.php'; ?>  
<div class="container">
    <header>
        <h1>ホーム画面</h1>
        <nav>
            <a href="mypage.php" class="nav-link">マイページへ</a>
            <a href="index.php" class="nav-link">アップロード画面へ</a>
            <a href="logout_input.php" class="nav-link">ログアウトへ</a>
        </nav>
    </header>
    <h2>画像ギャラリー</h2>
    <div class="gallery">
        <?php
        if(isset($_SESSION['UserData']['id'])){
            $sql = "SELECT Picture.picture_ID, Picture.picture_name, UserData.user_name, Upload.caption, COUNT(likes.id) as like_count
                    FROM Upload 
                    JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                    JOIN UserData ON UserData.user_ID = Upload.user_ID
                    LEFT JOIN likes ON Picture.picture_ID = likes.post_id
                    GROUP BY Picture.picture_ID";
            $stmt = $pdo->query($sql);

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='gallery-item'>";
                    echo "<a href='image.php?id=" . htmlspecialchars($row['picture_ID']) . "'>";
                    echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'>";
                    echo "<div class='overlay'>";
                    echo "<div class='text'>" . htmlspecialchars($row['user_name']) . "</div>";
                    echo "<div class='text'>" . htmlspecialchars($row['caption']) . "</div>";
                    echo "</div>";
                    echo "</a>";
                    echo "<form action='like1.php' method='post'>";
                    echo "<input type='hidden' name='post_ID' value='" . htmlspecialchars($row['picture_ID']) . "'>";
                    echo "<button type='submit'>いいね</button>";
                    echo "</form>";
                    echo "<p>いいね数: " . htmlspecialchars($row['like_count']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>ギャラリーに画像がありません。</p>";
            }
        } else {
            echo "<p>ログインしてください</p>";
        }
        ?>
    </div>
</div>
