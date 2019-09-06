<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「マイページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- 案内用のニュッと出てくるやつ-->
    <p id="js-show-msg"  class="msg-slide" style="display:none;">
        <?php echo getSessionFlash('msg_suc') ;?>
    </p>

    <?php require_once('header.php')?>
        <ul>
            <li><a href="products_list.php">商品リスト</a></li>
            <li><a href="users_list.php"></a>ユーザーリスト</li>
            <li><a href="signin.php"></a>ログイン</li>
            <li><a href="signup.php">新規登録</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            <li><a href="profileEdit.php">プロフ編集</a></li>
            <li><a href="passEdit.php">パスワード変更</a></li>
            <li><a href="productEdit.php">商品アップロード</a></li>
            <li><a href="withdraw.php">退会</a></li>
        </ul>
    <?php require_once('footer.php')?>
</body>
</html>