<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「パスワードリセット」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    if(!empty($_SESSION['user_id'])){
        //ログインしているユーザーが再発行ページに来たので追い返す。
        header('location:mypage.php');
    }
    //期限切れの人が来た→すでにある認証キーの中身、アドレス、有効期限をリセットする。
    session_unset();
    if(!empty($_POST)){

        //Emailのチェック
        vaidemail($_POST['email'],'email');
        mustEnter($_POST['email'],'email');

    }

    if(!empty($_POST) && empty($err_msg)){
        debug('バリデーションOK');
        $email = htmlspecialchars($_POST['email']);
        try{
            $dbh = dbconnect();
            $sql = 'SELECT count(*) FROM users WHERE email = :email';
            $data = array(':email'=> $email);
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($stmt && $result['count(*)'] != 0 ){
                debug('Email合致。');

                $auth_key = createAuthKey(8);
                $to = $email;
                $sjt ='【パスワード再発行認証】TABI PICTURE';
                $msg =<<<EOM
本アドレス宛にパスワード再発行の申請がありました。
下記のURLにアクセスしていただき、認証キーを入力してください。

認証キー：{$auth_key}
認証キー入力URL：http://localhost:8888/tabi_picture/passResetInput.php
※30分以内に入力してください。

認証キーの再発行はこちらのページから行えます。
http://localhost:8888/tabi_picture/passReset.php

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
TABI　PICTUREカスタマーセンター
Email: info@tabipic.com
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
EOM;

                mail($to,$sjt,$msg);
                $_SESSION['auth_key'] = $auth_key;
                $_SESSION['auth_email'] = $email;
                $_SESSION['auth_key_limit'] = time()+(60*30);
                $_SESSION['msg_suc'] = SUC06;
                debug('SESSIONの中身：'.print_r($_SESSION,true));

                header('location:passResetInput.php');
            }else{
                debug('クエリ失敗またはDB未登録のEmailが入力。');
                $err_msg['fatal']= MSG06;
            }

        }catch(Exception $e){
            debug('エラー発生：'.($e->getMessage()));
            $err_msg['fatal']= MSG06;
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワード変更ページ</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .main-conteiner{
            width: 60%;
            margin: 0 auto;
        }

        .signup-conteiner{
            padding: 25px 30px;
            width: 60%;
            margin: 110px auto;
            background-color: #f5f5f5;
        }

        .formName{
            margin-bottom: 30px;
        }

        .formLabel{
            display: block;
            font-size: 18pxpx;
        }
        .form-group{
            width:90%;
            margin:0 auto 50px auto;
            
        }

        .formArea{
            height: 40px;
            width: 90%;
            font-size:20px;
            padding: 6px;
        }

        .formArea:last-child{
            margin-bottom: 0px;
        }

        .submit-btn{
            display: block;
            width: 160px;
            height: 40px;
            margin: 0 auto;
            font-size: 20px;
            font-weight: blod;
            background-color: darkcyan;
            color:white;
            border: none;
            cursor: pointer;
        }

        .reSignup{
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .help-block{
            font-size:15px;
            margin-left:5px;
            color: red;
            display: block;
        }

        .forgetPass{
            margin-top: 20px;
            display: block;
            text-align: center;
        }

    </style>

</head>
<body>
    <?php require_once('header.php')?>

    <p id="js-show-msg"  class="msg-slide" style="display:none;">
        <?php echo getSessionFlash('msg_suc') ;?>
    </p>

        <div class="main-conteiner">
            <div class="signup-conteiner">

                <form method="post">

                    <div class="form-group">
                        <p class= "formName">ご指定のメールアドレス宛にパスワード再発行用の<br>URLと認証キーをお送りします。</p>
                        <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                        <label class="formLabel" for="email">登録Email<span class="help-block">
                            <?php if(!empty($err_msg)) echo $err_msg['email'];?></span>
                            <input class="formArea valid-email" id="email" type="text" name="email"
                            value=<?php if(!empty($_POST['email'])) echo $_POST['email'];?>></label>
                    </div>

                    <input class="submit-btn" type="submit" value="送信する">
                    <a class="forgetPass" href="signin.php">ログイン画面へ戻る</a>

                </form>
            </div>
        </div>
        <?php require_once('footer.php')?>
</body>
</html>