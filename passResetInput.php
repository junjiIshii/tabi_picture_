<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「パスワードリセット」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    if(empty($_SESSION['auth_key'])){
        //認証キーを発行しないでアクセス（URL直接入力）
        header("location:passReset.php");
    }

    if(!empty($_POST)){

        //authKeyのチェック
        $inpAuth = htmlspecialchars($_POST['authKey']);

        lengthCheck($inpAuth,8,'authKey');
        validHalf($inpAuth,'authKey');
        mustEnter($inpAuth,'authKey');

    }
    //ここから！！！バリデーションのチェックと認証キーが期限切れと間違った場合の処理をかく。
    if(!empty($_POST) && empty($err_msg) && $inpAuth == $_SESSION['auth_key'] ){
        debug('バリデーションOKかつ認証キー合致');
        $new_pass = createAuthKey($leng = 8);

        try{
            $pass_fordb = password_hash($new_pass, PASSWORD_DEFAULT);
            $email = $_SESSION['auth_email'];

            $dbh = dbconnect();
            $sql = 'UPDATE users SET password =:pass WHERE email = :email';
            $data = array(':pass'=> $pass_fordb,':email'=> $email);
            $stmt = queryPost($dbh,$sql,$data);

            if($stmt){
                debug('クエリ成功');
                $to= $email;
                $sjt = '【パスワードを再発行しました】TABI PICTURE';
                $msg= <<<EOM
認証キーによる本人確認ができました。
パスワードを再発行します。こちらの
パスワードを用いてログインをしてください。

パスワード：{$new_pass}

ログイン画面はこちら
http://localhost:8888/tabi_picture/signin.php

ログイン後はパスワードを変更することをお勧めします。
パスワード変更はマイページの「パスワード変更」から
アクセスしてください。
EOM;

                mail($to,$sjt,$msg);
                session_unset();
                $_SEESION['msg_suc']=SUC07;
                header('location:signin.php');
            }
        }catch (Exception $e){
            debug('エラー発生：'.$e->getMessage());
            $err_msg['fatal']= MSG06;
        }
    }else if(!empty($_POST) && empty($err_msg) && $inpAuth != $_SESSION['auth_key']){
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

                        <label class="formLabel" for="authKey">認証キー入力<span class="help-block">
                            <?php if(!empty($err_msg)) echo $err_msg['authKey'];?></span>
                            <input class="formArea valid-authKey" id="authKey" type="password" name="authKey"
                            value=<?php if(!empty($_POST['authKey'])) echo $_POST['authKey'];?>></label>
                    </div>

                    <input class="submit-btn" type="submit" value="送信する">
                    <a class="forgetPass" href="signin.php">ログイン画面へ戻る</a>

                </form>
            </div>
        </div>
        <?php require_once('footer.php')?>
</body>
</html>