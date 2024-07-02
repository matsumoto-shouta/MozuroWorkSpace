<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="javascript/hamburger.js"></script>
    <script src="javascript/Preview.js"></script>
</head>
<body>
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
        <li class="navigation__list-item"><a href="home.php" class="navigation__link">ホーム</a></li>
        <li class="navigation__list-item"><a href="mypage.php" class="navigation__link">マイページ</a></li>
        <li class="navigation__list-item"><a href="index.php" class="navigation__link">アップロード</a></li>
        <li class="navigation__list-item"><a href="logout_input" class="navigation__link">ログアウト</a></li>
        </ul>
    </nav>
    </header>
    <!-- ここまでハンバーガーメニュー -->
    <h2>画像アップロードフォーム</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="file">
            <label for="file" class="select">画像を選択してください:</label><br>
            <input type="file" name="file" id="file"><br>
        </div>

        <div id="preview"></div><br>

        <div class="ef">
            <label class="caption">
                <textarea type="text" name="caption" id="caption" placeholder="キャプション"></textarea><br>
            </label>
        </div>
        
        <button type="submit" class="upbtn">アップロード</button>

        
    </form>

   
</body>
