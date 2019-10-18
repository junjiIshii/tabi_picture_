<?php
//==============================================
//共通ヘッダー
//==============================================


    //メニューの一部を特定のページで変更
    switch($url = $_SERVER['REQUEST_URI']){

        //写真リストページなら、ユーザーリストへのリンクになる。
        case strstr($url,'/tabi_picture/products_list.php'):
            $menuLink='<li><a href="users_list.php">ユーザーを見つける</a></li>';
            break;

            //ユーザーリストなら、写真リストページへのリンクになる。
        default:
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

<!--フラッシュメッセージ-->
<p id="js-show-msg"  class="msg-slide" style="display:none;">
    <?php echo getSessionFlash('msg_suc') ;?>
</p>

<header>
        <div class='head-conteiner'>
            <a  class='logo-icon' href='/tabi_picture/index.php'>TABI PICTUREs</a>

            <div class='header-menue'>
                <ul>
                    <?php echo $menuLink;?>
                    <?php echo $regiOrMypage;?>
                    <?php echo $log_InOrOut;?>
                </ul>
            </div>

            <div class="menu-button-wrapper">
                <i class="fas fa-align-justify fa-lg" id="menu-button"></i>
            </div>
        </div>
        <div class="slidemenu-conteiner slideoff">
    <ul>
        <?php if(isset($menuLink)) echo $menuLink;?>
        <?php echo $regiOrMypage;?>
        <?php echo $log_InOrOut;?>

        <?php if(!empty($_SESSION['user_id'])){?>
        <?php
            $menus = array(
                ['url'=> 'mypage.php',
                'menuName'=>'マイページ',
                'subClass'=>'toMypage'],

                ['url'=> 'profileEdit.php',
                'menuName'=>'プロフィール編集',
                'subClass'=>'toProfEdit'],

                ['url'=> 'productEdit.php',
                'menuName'=>'商品アップロード',
                'subClass'=>'toUpload'],

                ['url'=> 'myproducts_list.php',
                'menuName'=>'商品編集一覧',
                'subClass'=>'toUpload'],

                ['url'=> 'favoriteList.php',
                'menuName'=>'お気に入り一覧',
                'subClass'=>'toFavorite'],

                ['url'=> 'followList.php',
                'menuName'=>'フォロー管理',
                'subClass'=>'toFollows'],

                ['url'=> 'passEdit.php',
                'menuName'=>'パスワード変更',
                'subClass'=>'toPassCng'],

                ['url'=> 'withdraw.php',
                'menuName'=>'退会する',
                'subClass'=>'toWithdraw']
            );

            foreach($menus as $val){?>

                <li>
                <a  href="<?php echo $val['url']?>"> <?php echo $val['menuName']?></a> </li>
            <?php }?>
        <?php }?>
    </ul>
</div>
</header>



