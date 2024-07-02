$(function() {
    $('input:file').on('change', function() {
        var fileReader = new FileReader();

        fileReader.onload = function(event) {
            var loadedImageUri = event.target.result;
            $('#preview').after('<img src=' + loadedImageUri + ' class="preImg">');
        };

        fileReader.readAsDataURL(this.files[0]);
    });
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
  