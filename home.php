<?php session_start(); ?>
<link rel="stylesheet" href="css/home.css?v=1.0.1">

<head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="javascript/hamburger.js"></script>
</head>

<?php require 'db-connect.php'; ?>  
<div class="container">
    <!-- ハンバーガーメニュー -->
    <header class="header">
    <div class="logo"><img src="image/instakiro.png" width="48" height="48"></div>
    <button class="hamburger-menu" id="js-hamburger-menu">
        <span class="hamburger-menu__bar"></span>
        <span class="hamburger-menu__bar"></span>
        <span class="hamburger-menu__bar"></span>
    </button>
    <nav class="navigation">
        <ul class="navigation__list">
        <li class="navigation__list-item"><a href="mypage.php" class="navigation__link">マイページ</a></li>
        <li class="navigation__list-item"><a href="index.php" class="navigation__link">アップロード</a></li>
        <li class="navigation__list-item"><a href="logout_input" class="navigation__link">ログアウト</a></li>
        </ul>
    </nav>
    </header>
    <!-- ここまでハンバーガーメニュー -->
    
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
