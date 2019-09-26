<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「プロフィール編集画面」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();
    $dbUserData = getUserData($_SESSION['user_id']);

    if(!empty($_POST)){
        debug('POST送信があります。');
        debug('POST内容：'.print_r($_POST,true));
        debug('FILES内容：'.print_r($_FILES,true));

        //変数をユーザー情報に格納
        $username =htmlspecialchars($_POST['username']);
        $introduction = htmlspecialchars($_POST['introduction']);
        $tel = $_POST['tel'];
        $zip = (!empty($_POST['zip']))? $_POST['zip']:0;
        $adress = htmlspecialchars($_POST['adress']);
        $age = (!empty($_POST['age']))? $_POST['age']:0;
        $email = htmlspecialchars($_POST['email']);

        //ユーザーネームのチェック：文字数と入力必須
        if($dbUserData['username'] != $username){
            minMaxWords($username, 0, 15,'username');
            mustEnter($username,'username');
        }

        //紹介文のチェック：150文字以内であるか？
        if($dbUserData['introduction'] !== $introdution){
            minMaxWords($introduction, 0, 150,'introduction');
        }

        //電話番号形式のチェック
        if($dbUserData['tel'] !== $tel && !empty($_POST['tel'])){
            validTel($tel,'tel');
            validNum($tel,'tel');
        }

        //郵便番号の形式チェック
        if((int)$dbUserData['zip'] !== $zip){
            validZip($zip,'zip');
            validNum($zip,'zip');
        }

        //年齢のチェック：半角英数字で入力しているか？
        if($dbUserData['age'] !== $age){
            validNum($age,'age');
        }

        //Emailのチェック
        if($dbUserData['email'] !== $email){
            mustEnter($email,'email');
            vaidemail($email,'email');
        }

        //ヘッダー画像のバリデーションとパスの格納
        if(!empty($_FILES['header_img']['name'])){
            debug('header_imgデータアリ');
            $hed_img =
            uploadImg($_FILES['header_img'],'header_img');
        }elseif(empty($_FILES['header_img']['name']) && !empty($dbUserData['header_img'])){
            $hed_img = $dbUserData['header_img'];
        }else{
            $hed_img = '';
        }

        //!フォームで入力した画像の保持と毎回アップロードするのを防ぐ!
        //ユーザーアイコン画像のバリデーションとパスの格納
        if(!empty($_FILES['icon_img']['name'])){
            $icon_img =
            uploadImg($_FILES['icon_img'],'icon_img');
        }elseif(empty($_FILES['icon_img']['name']) && !empty($dbUserData['icon_img'])){
            $icon_img = $dbUserData['icon_img'];
        }else{
            $icon_img = '';
        }
    }

    //エラーが合ったらどのエラーなのかをデバッグ
    if(!empty($err_msg)){
        debug('バリデーションエラー有り：'.print_r($err_msg,true));
    }

//==============================
//バリデーションはすべてOK→DBへ接続
//==============================
    if(empty($err_msg) && !empty($_POST)){
        debug('バリデーションはOK。データを送信をします。');
        try{
            $dbh = dbconnect();
            $sql = 'UPDATE users SET
                username = :username,
                age = :age,
                tel = :tel,
                zip = :zip,
                addr = :addr,
                email = :email,
                header_img = :hd_im,
                icon_img = :ic_im,
                introduction = :intro
                WHERE userid = :userid';
            $data = array(
                ':userid'=> $_SESSION['user_id'],
                ':username'=> $username,
                ':age'=> $age,
                ':tel'=> $tel,
                ':zip'=> $zip,
                ':addr'=> $adress,
                ':email'=> $email,
                ':hd_im'=> $hed_img,
                ':ic_im'=> $icon_img,
                ':intro'=> $introduction
            );
            $stmt = queryPost($dbh,$sql,$data);

            if($stmt){
                debug('クエリ成功');
                debug('マイページへ遷移');
                $_SESSION['msg_suc']= SUC02;
                header("location:mypage.php");
            }else{
                debug('クエリ失敗');
                $err_msg['fatal']= MSG06;
            }
        }catch (Exception $e){
            debug('エラー発生：'.$e->getMessage());
            $err_msg['fatal']= MSG06;
        }
    }
    ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール編集</title>
    <link href="style.css" rel="stylesheet">

    <style>
    .main-conteiner{
        width: 85%;
        margin: 0 auto;
        margin-top:10px;
            }





    .edit-menu-wrapper{
        display: inline-block;
        height: 100%;
        width:80%;
        background: #f5f5f5;
    }

    .edit-menu-conteiner{
        width:600px;
        margin: 0 auto;
        padding: 20px 30px;
    }

    .conteiner-Name{
        text-align: center;
    }
    .editArea{
        margin-bottom: 30px;

    }

    .dataName{
        display: inline-block;
    }


    /*EditArea*/
    .header_img{
        width:100%;
        position: relative;
        background:rgba(204,255,229,0.5);
        margin-bottom:15px;
    }

    .icon_img{
        background:rgba(204,255,229,0.5);
    }

    .preview-header{
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        position: absolute;
        top:0;
    }

    .preview-icon{
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top:0;
        border-radius:50%;

    }


    /*↓INPUT*/
    .editContent-header_img{
        width: 100%;
        height:200px;
        opacity:0;
    }
    .header-import-guide{
        position: absolute;
        top:45%;
        left:25%;
        color:rgba(104,125,114,0.5);
        font-size:15px;
    }

    .icon-import-guide{
        position: absolute;
        top:40px;
        left:20px;
        color:rgba(104,125,114,0.5);
        font-size:12px;
    }

    .icon_img{
        width:100px;
        height: 100px;
        border-radius: 50%;
        background:rgba(204,255,229,0.5);
        display:inline-block;
        vertical-align: top;
        position: relative;
    }

    .editContent-icon_img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        opacity:0;
    }

    .user_name{
        display:inline-block;
        width:70%;
        margin-left:15px;
    }

    .editContent{
        font-size:20px;
        display: block;
        width:60%;
        height: 40px;
        border:none;
        border-bottom:1px solid #99FFCC;
        background: rgba(204,255,229,0.5);
        padding:5px;
    }

        .adress{
            width:90%;
        }

        .age{
            width:15%;
        }

    .editContent-textArea{
        border-bottom:1px solid #99FFCC;
        background: rgba(204,255,229,0.5);
        font-size:15px;
        display: block;
        resize:none;
        width:100%;
        height: 150px;
        padding: 5px;
    }

    .caution{
        font-size: 10px;
        color:gray;
    }

    .err_msg{
        color:red;
    }

    .fatal{
        display:block;
        text-align: center;
    }

    .register-btn{
            display: block;
            width: 160px;
            height: 40px;
            margin: 0 auto;
            font-size: 20px;
            font-weight: blod;
            background-color: darkcyan;
            color:white;
            border: none;
            cursor:pointer;
        }

    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">

            <div class="edit-menu-wrapper">
                    <div class="edit-menu-conteiner">
                        <h2 class="conteiner-Name">ユーザー情報編集</h2>
                        <span class="fatal err_msg"><?php cautionEcho('fatal');?></span>

                        <form method="post" enctype="multipart/form-data">
                                <span class="err_msg"><?php cautionEcho('header_img');?></span>
                                <span class="err_msg" style="display:block;"><?php cautionEcho('icon_img');?></span>
                                
                                <div class="header_img area-drop">
                                    <span class="header-import-guide">ファイルをドロップまたはクリック</span>
                                    <img class="preview-header" src="<?php echo editValSet('header_img',$dbUserData);?>"
                                    alt="" style="<?php if(empty(editValSet('header_img',$dbUserData))) echo 'display:none;'?>">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                    <input
                                    type="file" class="editContent-header_img input_img" name="header_img">
                                </div>
                            <!-- 画像を追加OR変更→他のフォームでエラー→追加した画像のプレビューが消えてしまう...JSでのsrc指定が悪さしてる-->

                            <div class="editArea icon_img area-drop">
                                <span class="icon-import-guide">写真を追加</span>
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                <img class="preview-icon" src="<?php echo editValSet('icon_img',$dbUserData);?>"
                                    alt="" style="<?php if(empty(editValSet('icon_img',$dbUserData))) echo 'display:none;'?>">
                                <input
                                type="file" class="editContent-icon_img input_img" name="icon_img";?>
                            </div>

                            <div class="editArea user_name">
                                <p class="dataName">ユーザー名</p>
                                <span class="caution">入力必須</span>
                                <input
                                type="text" class="editContent" name="username"
                                value="<?php echo editValSet('username',$dbUserData);?>">
                                <span class="err_msg"><?php cautionEcho('username');?></span>
                            </div>
                            



                            <div class="editArea introduction">
                                <p class="dataName">自己紹介文</p>
                                <span class="caution">150文字以内で入力</span>
                                <textarea name="introduction" class="editContent-textArea"><?php echo editValSet('introduction',$dbUserData);?></textarea>
                                <span class="err_msg"><?php cautionEcho('introduction');?></span>
                            </div>
                            

                            <!--ここから。クラス名とVALUEの出力を変更する-->
                            <div class="editArea tel">
                                <p class="dataName">電話番号</p>
                                <span class="caution">ハイフン無しで入力して下さい</span>
                                <input
                                type="text" class="editContent"name="tel"
                                value="<?php echo editValSet('tel',$dbUserData);?>">
                                <span class="err_msg"><?php cautionEcho('tel');?></span>
                            </div>


                            <div class="editArea">
                                <p class="dataName">郵便番号</p>
                                <span class="caution">ハイフン無しで入力して下さい</span>
                                <input
                                type="text" class="editContent zip"name="zip"
                                value="<?php if(!empty(editValSet('zip',$dbUserData))){echo editValSet('zip',$dbUserData);}?>">
                                <span class="err_msg"><?php cautionEcho('zip');?></span>
                            </div>


                            <div class="editArea">
                                <p class="dataName">住所</p>
                                <span class="caution"></span>
                                <input
                                type="text" class="editContent adress"name="adress"
                                value="<?php echo editValSet('addr',$dbUserData);?>">
                                <span class="err_msg"><?php cautionEcho('addr');?></span>
                            </div>


                            <div class="editArea">
                                <p class="dataName">年齢</p>
                                <span class="caution">半角英数字で入力</span>
                                <input
                                type="text" class="editContent age"name="age"
                                value="<?php if(!empty(editValSet('age',$dbUserData))){echo editValSet('age',$dbUserData);}?>">
                                <span class="err_msg"><?php cautionEcho('age');?></span>
                            </div>


                            <div class="editArea">
                                <p class="dataName">Email</p>
                                <span class="caution">入力必須</span>
                                <input
                                type="text" class="editContent email" name="email"
                                value="<?php echo editValSet('email',$dbUserData);?>">
                                <span class="err_msg"><?php cautionEcho('email');?></span>
                            </div>

                            <input type="submit" class="register-btn" value="登録">
                        </form>
                    </div>
            </div>
            <?php require_once('mypageBar.php')?>

        <div class="cd"></div>
    </div>
    <?php require_once('footer.php')?>

</body>
</html>