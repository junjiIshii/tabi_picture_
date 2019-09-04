<?php
//==============================================
//共通ヘッダー
//==============================================

    //メニューの一部を特定のページで変更
    switch($_SERVER['REQUEST_URI']){

        //写真リストページなら、ユーザーリストへのリンクになる。
        case '/tabi_picture/products_list.php':
            $menuLink='<li><a href="users_list.php">ユーザーを見つける</a></li>';
            break;

            //ユーザーリストなら、写真リストページへのリンクになる。
        case '/tabi_picture/users_list.php':
            $menuLink='<li><a href="products_list.php">写真を見つける</a></li>';
            break;
    };

    //ログイン状態（ログインID発行時か否か）でのメニュー。
    if(!empty($_SESSION['user_id'])){

        //ログイン時：マイページ＆ログアウト
        $regiOrMypage= '<li><a href="mypage.php">マイページ</a></li>';
        $log_InOrOut = '<li><a href="logout.php">ログアウト</a></li>';
    }else{

        //未ログイン時：新規登録＆ログイン
        $regiOrMypage= '<li><a href="signup.php">新規登録</a></li>';
        $log_InOrOut = '<li><a href="signin.php">ログイン</a></li>';
    }
?>
<header>
        <div class='head-conteiner'>
            <a  class='logo-icon' href='/tabi_picture/products_list.php'>TABI PICTUREs</a>

        <div class='header-menue'>
            <ul>
                <?php echo $menuLink;?>
                <?php echo $regiOrMypage;?>
                <?php echo $log_InOrOut;?>
            </ul>
        </div>
        </div>

</header>