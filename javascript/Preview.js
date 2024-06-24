function previewFile(file) {
    // プレビュー画像を追加する要素
    const preview = document.getElementById('preview');
  
    // FileReaderオブジェクトを作成
    const reader = new FileReader();
  
    // ファイルが読み込まれたときに実行する
    reader.onload = function (e) {
      const imageUrl = e.target.result; // 画像のURLはevent.target.resultで呼び出せる
      const img = document.createElement("img"); // img要素を作成
      img.src = imageUrl; // 画像のURLをimg要素にセット
      preview.appendChild(img); // #previewの中に追加
    }
    reader.readAsDataURL(e.target.files[0]);
};
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
  
    // いざファイルを読み込む
    reader.readAsDataURL(file);

  
  
  // <input>でファイルが選択されたときの処理
  const fileInput = document.getElementById('example');
  const handleFileSelect = () => {
    const files = fileInput.files;
    for (let i = 0; i < files.length; i++) {
      previewFile(files[i]);
    }
  }
  fileInput.addEventListener('change', handleFileSelect);
  
