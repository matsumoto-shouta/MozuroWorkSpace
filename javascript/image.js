document.addEventListener("DOMContentLoaded", function() {
    const commentsContainer = document.querySelector(".comments");
    const canvasContainer = document.getElementById("canvasContainer"); // IDで取得する
    const toggleButton = document.getElementById("toggleComments");
    let commentsVisible = true;

    // コメントアニメーションを開始する関数
    function startCommentsAnimation() {
        // canvasContainerをクリアする
        canvasContainer.innerHTML = '';

        // .commentsからコメントを取得
        const comments = commentsContainer.querySelectorAll(".comment");

        comments.forEach(comment => {
            const commentText = comment.querySelector("p").textContent.trim();
            const commentElement = document.createElement("div");
            commentElement.classList.add("comment-flow");
            commentElement.textContent = commentText;

            // canvasContainer内のランダムな位置に配置
            const topPosition = Math.floor(Math.random() * (canvasContainer.clientHeight - commentElement.offsetHeight));
            commentElement.style.top = `${topPosition}px`;
            commentElement.style.left = `${canvasContainer.clientWidth}px`;

            canvasContainer.appendChild(commentElement);
        });
    }

    // 初期状態でコメントアニメーションを開始
    startCommentsAnimation();

    // トグルボタンの機能
    toggleButton.addEventListener("click", function() {
        if (commentsVisible) {
            canvasContainer.style.display = 'none'; // コメント非表示
            toggleButton.textContent = 'コメントを表示';
        } else {
            canvasContainer.style.display = 'block'; // コメント表示
            startCommentsAnimation(); // アニメーションを再開始
            toggleButton.textContent = 'コメントを非表示';
        }

        commentsVisible = !commentsVisible;
    });
});