<?php
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=insta_clone', 'username', 'password');

// 投稿を取得
$stmt = $pdo->prepare("SELECT * FROM posts");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>インスタ風いいね機能</title>
</head>
<body>
    <?php foreach ($posts as $post): ?>
        <div>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <?php
            // いいね数を取得
            $stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?");
            $stmt->execute([$post['id']]);
            $like_count = $stmt->fetch(PDO::FETCH_ASSOC)['like_count'];

            // すでにいいねしているかチェック
            $stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$post['id'], $current_user_id]);
            $liked = $stmt->rowCount() > 0;
            ?>
            <form action="like.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit"><?php echo $liked ? 'いいねを取り消す' : 'いいね'; ?></button>
                <span><?php echo $like_count; ?> いいね</span>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
