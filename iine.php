まずはいいねボタンを実装します。
ボタンを配置する場所は適切なところに設置してください。

#user_disp.php

//ユーザーIDと投稿IDを元にいいね値の重複チェックを行っています
//既にいいねをしているかしてないかのチェック

function check_favolite_duplicate($user_id,$post_id){
    $dsn='mysql:dbname=db;host=localhost;charset=utf8';
    $user='ユーザ名';
    $password='パスワード';
    $dbh=new PDO($dsn,$user,$password);
    $sql = "SELECT *
            FROM favorite
            WHERE user_id = :user_id AND post_id = :post_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $user_id ,
                         ':post_id' => $post_id));
    $favorite = $stmt->fetch();
    return $favorite;
}

DBコネクトPHP(インスタ)
<?php
    const SERVER = 'mysql305.phy.lolipop.lan';
    const DBNAME = 'LAA1517807-insta';
    const USER = 'LAA1517807';
    const PASS = 'Pass0514';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
?>



<form class="favorite_count" action="#" method="post">
        <input type="hidden" name="post_id">
        <button type="button" name="favorite" class="favorite_btn">
        <?php if (!check_favolite_duplicate($_SESSION['user_id'],$post_id)): ?>
          いいね
        <?php else: ?>
          いいね解除
        <?php endif; ?>
        </button>
</form>
上記ではいいねボタンをクリックした際に、check_favolite_duplicate関数ですでに投稿をお気に入りしているかを判断し、
ボタンをいいねかいいね解除に切り替えています。

当然このままではajax処理は行われないため、JavaScriptを利用していきます。
jsファイルを作成し、下記を追加します。

//URLから引数に入っている値を渡す処理

--------function get_param(name, url) {------------------------------------------------------------------------
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return false;
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


$(document).on('click','.favorite_btn',function(e){
    e.stopPropagation();
    var $this = $(this),
        page_id = get_param('page_id'),
        post_id = get_param('procode');
    $.ajax({
        type: 'POST',
        url: 'ajax_post_favorite_process.php',
        dataType: 'json',
        data: { page_id: page_id,
                post_id: post_id}
    }).done(function(data){
        location.reload();
    }).fail(function() {
      location.reload();
    });-------------------------------------------------------------------------------------------------------
 ---------- });-----------------------------------------------------------------------------------------

上記処理ではクラス名がfavorite_btnであるボタンをクリックした際に、
ajax_post_favorite_process.phpにpage_idとpost_idを渡して処理を進めています。

get_paramとはURLから引数に入っている値を取り出すことができます。
この関数からpage_id(ユーザーID)とpost_id(投稿ID)を受け取っています。

ではajax_post_favorite_process.phpで進めている処理を見ていきます。

<script src=" https://code.jquery.com/jquery-3.4.1.min.js "></script>
<script src="../js/user_page.js"></script>
<?php
session_start();
session_regenerate_id(true);
require_once('config.php');

function check_favolite_duplicate($user_id,$post_id){
    $dsn='mysql:dbname=db;host=localhost;charset=utf8';
    $user='root';
    $password='';
    $dbh=new PDO($dsn,$user,$password);
    $sql = "SELECT *
            FROM favorite
            WHERE user_id = :user_id AND post_id = :post_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $user_id ,
                         ':post_id' => $post_id));
    $favorite = $stmt->fetch();
    return $favorite;
}

if(isset($_POST)){

  $current_user = get_user($_SESSION['user_id']);
  $page_id = $_POST['page_id'];
  $post_id = $_POST['post_id'];

  $profile_user_id = $_POST['page_id'] ?: $current_user['user_id'];

  //既にいいねされているか確認
  if(check_favolite_duplicate($current_user['id'],$post_id)){
    $action = '解除';
    $sql = "DELETE
            FROM favorite
            WHERE :user_id = user_id AND :post_id = post_id";
  }else{
    $action = '登録';
    $sql = "INSERT INTO favorite(user_id,post_id)
            VALUES(:user_id,:post_id)";
  }

  try{
    $dsn='mysql:dbname=shop;host=localhost;charset=utf8';
    $user='root';
    $password='';
    $dbh=new PDO($dsn,$user,$password);
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['code'] , ':post_id' => $post_id));

  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
    echo json_encode("error");
  }
}

check_favolite_duplicate関数で現在のユーザーIDと投稿IDを取得しデータベースに組み合わせが重複していないか確認を取り、
重複していた場合にいいねを解除しています。
重複していなかった場合はfavoriteテーブルにuser_idとpost_idを追加します。