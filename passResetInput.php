<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「パスワードリセットインプット」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    if(empty($_SESSION['auth_key'])){
        //認証キーを発行しないでアクセス（URL直接入力）
        header("location:passReset.php");
    }

    if(!empty($_POST)){
        $inpAuth = htmlspecialchars($_POST['authKey']);
        $newPass = htmlspecialchars($_POST['password']);

        mustEnter($inpAuth,'authKey');
        mustEnter($newPass,'password');
    }



    if(!empty($_POST['authKey'])){

        //authKeyのチェック
        lengthCheck($inpAuth,8,'authKey');
        validHalf($inpAuth,'authKey');
    }

    if(!empty($_POST['password'])){

        //authKeyのチェック
        validHalf($newPass,'password');
        minMaxWords($newPass,8,30,'password');
    }
    

    if(!empty($_POST) && empty($err_msg) && $inpAuth == $_SESSION['auth_key'] ){
        debug('バリデーションOKかつ認証キー合致');

        try{
            $pass_fordb = password_hash($newPass, PASSWORD_DEFAULT);
            $email = $_SESSION['auth_email'];
            $dbh = dbconnect();
            $sql = 'UPDATE users SET password =:pass WHERE email = :email';
            $data = array(':pass'=> $pass_fordb,':email'=> $email);
            $stmt = queryPost($dbh,$sql,$data);

            if($stmt){
                    debug('クエリ成功');
                $to= $email;
                $sjt = '【パスワードを再設定しました】TABI PICTURE';
                $msg= <<<EOM
認証キーによる本人確認とパスワードの変更ができました。
引き続き安全のためログイン画面よりログインしてください。


ログイン画面はこちら
http://test.english-protocol.net/tabi_picture/signin.php

EOM;

            mail($to,$sjt,$msg);
            session_unset();
            unset($_SESSION['auth_key'],$_SESSION['auth_key_limit'],$_SESSION['auth_email']);
            $_SESSION['msg_suc'] ="パスワードの再設定が完了しました。安全のため再度ログインをしてください。";
            
            header('location:signin.php');
            
        }
    }catch (Exception $e){
        debug('エラー発生：'.$e->getMessage());
        $err_msg['fatal']= MSG06;
    }

    }else if(!empty($_POST) && $inpAuth != $_SESSION['auth_key']){
        //認証キーが違う場合
        debug('バリデーションOK、認証キー非合致');
        $err_msg['authKey']= MSG17;
    }else if (!empty($_POST) && time() > $_SESSION['auth_key_limit']){
        debug('認証キー期限切れ');
        $err_msg['authKey']= MSG17;
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
            margin: 0 auto;
        }

        .signup-conteiner{
            padding: 25px 30px;
            width: 50%;
            margin: 110px auto;
            background-color: #f5f5f5;
        }

        .formName{
            margin-bottom: 30px;
        }

        .formLabel{
            display: block;
            font-size: 18pxpx;
            margin-bottom:15px;
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
    <link href="responsive.css" rel="stylesheet">
</head>
<body>
    <?php require_once('header.php')?>

        <div class="main-conteiner">
            <div class="signup-conteiner">

                <form method="post">

                    <div class="form-group">
                        <p class= "formName">入力したアドレス宛に認証キーを送信しました。認証キーを入力しパスワードを再設定してください。</p>
                        <div class="help-block"><?php if(!empty($err_msg['fatal'])) echo $err_msg['fatal'];?></div>

                        <label class="formLabel" for="authKey">認証キー入力<span class="help-block">
                            <?php if(!empty($err_msg['authKey'])) echo $err_msg['authKey'];?></span>
                            <input class="formArea valid-authKey" id="authKey" type="password" name="authKey"
                            value=<?php if(!empty($_POST['authKey'])) echo $_POST['authKey'];?>>
                        </label>

                        <label class="formLabel" for="password">パスワードを再設定<span class="help-block">
                            <?php if(!empty($err_msg['password'])) echo $err_msg['password'];?></span>
                            <input class="formArea valid-pass" id="pass" type="password" name="password"
                            value=<?php if(!empty($_POST['password'])) echo $_POST['password'];?>>
                        </label>
                    </div>

                    <input class="submit-btn" type="submit" value="確認">
                    <a class="forgetPass" href="signin.php">ログイン画面へ戻る</a>

                </form>
            </div>
        </div>
        <?php require_once('footer.php')?>
</body>
</html>