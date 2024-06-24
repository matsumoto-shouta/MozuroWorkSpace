// 画像切り替え時にプレビュー表示
$('#form').on('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#img").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});
//いいねボタンのクリックを処理
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const pictureId = this.getAttribute('data-picture-id');
            const button = this;
            fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ picture_id: pictureId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('liked');
                }
            });
        });
    });
});