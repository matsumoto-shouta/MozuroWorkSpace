<?php
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=insta_clone', 'username', 'password');

// 投稿を取得
$stmt = $pdo->prepare("SELECT * FROM posts");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 現在のユーザーID（セッションから取得するか、ログイン機能がある場合はそちらから）
session_start();
$current_user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>インスタ風コメント機能</title>
</head>
<body>
    <?php foreach ($posts as $post): ?>
        <div>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <?php
            // コメントを取得
            $stmt = $pdo->prepare("SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY comments.created_at DESC");
            $stmt->execute([$post['id']]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div>
                <?php foreach ($comments as $comment): ?>
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
                <?php endforeach; ?>
            </div>
            <form action="comment.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <textarea name="comment" rows="3" placeholder="コメントを追加..."></textarea>
                <button type="submit">コメントする</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
