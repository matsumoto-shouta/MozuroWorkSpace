<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿画面</title>
</head>
<body>
    <?php
        define('IMAGES_DIR', './image/');
        define('IMAGE_MAX_WIDTH', 600);

        if($_SESSION['csrf'] !== $_POST['csrf']){
            header('Location: ./');
            exit;
        }

        if(!isset($_FILES['post_image']['error']) || !is_int($_FILES['post-image']['error'])){
            throw new RuntimeException('不正なリクエストです');
        }

        switch($_FILES['post_image']['error']){
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('ファイルが選択されていません');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('ファイルサイズが大きすぎます');
            default:
                throw new RuntimeException('その他のエラーが発生しました');
        }

        if(!$extensiton = array_search( mime_content_type($_FILES['post_image']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ),
        true
        )){
            throw new RuntimeException('投稿できないファイル形式です');
        }
    
        $image_name = md5(uniqid(rand(), true)) . '.' . $extensiton;
        $new_image_path = IMAGES_DIR . $image_name;

        if(move_uploaded_file($_FILES['post_image']['tmp_name'], $new_image_path)){
            list($new_image_width, $new_image_height) = getimagesize($new_image_path);
            $resize_width = IMAGE_MAX_WIDTH;
            $resize_height = $resize_width * $new_image_height / $new_image_width;

            if($new_image_width > $resize_width){
                $resize_image_p = imagecreatetruecolor($resize_width,$resize_height);
            }
        }

        //https://zenn.dev/clevercure/articles/cc9e24c652553b//
        //50行目まで//
</body>
</html>