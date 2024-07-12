<?php session_start(); ?>

<?php require 'db-connect.php'; ?> 
<?php require "hamburger.php"; ?> 

<head>

<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>

<div class="container">

    <!-- ここまでハンバーガーメニュー -->
    
    <h2>投稿一覧</h2>
    <div class="gallery">
        <?php
        if(isset($_SESSION['UserData']['id'])){
            $user_ID = $_SESSION['UserData']['id'];
            $sql = "SELECT Picture.picture_ID, Picture.picture_name, UserData.user_name, UserData.user_picture, Upload.caption, COUNT(likes.id) as like_count,
                    (SELECT COUNT(*) FROM likes WHERE likes.post_id = Picture.picture_ID AND likes.user_ID = :user_ID) as user_liked
                    FROM Upload 
                    JOIN Picture ON Upload.picture_ID = Picture.picture_ID 
                    JOIN UserData ON UserData.user_ID = Upload.user_ID
                    LEFT JOIN likes ON Picture.picture_ID = likes.post_id
                    GROUP BY Picture.picture_ID
                    ORDER BY  up_ID desc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_ID' => $user_ID]);

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $likedClass = $row['user_liked'] > 0 ? 'liked' : '';
                    echo "<div class='gallery-item'>";
                    echo "<div class='user-info'>";
                    echo "<img src='" . htmlspecialchars($row['user_picture']) . "' class='user-icon' alt='ユーザーアイコン'>";
                    echo "<span class='user-name'>" . htmlspecialchars($row['user_name']) . "</span>";
                    echo "</div>";
                    echo "<a href='image.php?id=" . htmlspecialchars($row['picture_ID']) . "'>";
                    echo "<img src='" . htmlspecialchars($row['picture_name']) . "' alt='アップロードされた画像'width='700' height='400'>";
                    echo "</a>";
                    echo "<div class='post-footer'>";
                    echo "<div class='post-actions'>";
                    echo "<form action='like.php' class='like-form' method='post'>";
                    echo "<input type='hidden' name='post_ID' value='" . htmlspecialchars($row['picture_ID']) . "'>";
                    echo "<button type='submit' class='like-button $likedClass'>";
                    echo "<i class='fa fa-heart'></i>";
                    echo "</button>";
                    echo "</form>";
                    echo "<span class='like-count'>" . htmlspecialchars($row['like_count']) . "</span>";
                    echo "</div>";
                    echo "<div class='post-caption'>" . htmlspecialchars($row['caption']) . "</div>";
                    echo "</div>";
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
 
