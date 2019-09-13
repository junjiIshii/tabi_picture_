<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「パスワード変更」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();

    $oldpass = htmlspecialchars($_POST['oldpass']);
    $newpass = htmlspecialchars($_POST['newpass']);
    $userid = $_SESSION['user_id'];

    if(!empty($_POST)){

        minMaxWords($newpass,8,30,'newpass');
        mustEnter($oldpass,'oldpass');
        mustEnter($newpass,'newpass');

        debug('エラーの中身'.print_r($err_msg,true));


    }

    if(!empty($_POST) && empty($err_msg)){
        try{
            $userdata = getUserData($userid);
            $userpass = $userdata['password'];

            //DB上に保存してあったデータと、変更前パスワードが一致しているか？
            if(password_verify($oldpass, $userpass)){
                debug('変更前パスワードが合致');
                $editPass = password_hash($newpass, PASSWORD_DEFAULT);

                $dbh = dbconnect();
                debug('接続完了');
                $sql = 'UPDATE users SET password =:pass WHERE userid = :userid';
                $data = array(':pass'=> $editPass,':userid'=> $userid);
                $stmt = queryPost($dbh,$sql,$data);

                if($stmt){
                    debug('クエリ成功');
                    debug('パスワードを変更しました。');
                    debug('マイページへ遷移');
                    
                    $usename = $userdata['username'];
                    $to = $userdata['email'];
                    $sjt = 'パスワード変更のお知らせ。';

                    $mes= <<< EOM
{$usename} さん
こちらのアドレスで使用している
アカウントのパスワードが変更されました。
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
TABI　PICTUREカスタマーセンター
Email: info@tabipic.com
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
EOM;

                    mail($to,$sjt,$mes);
                    $_SESSION['msg_suc']= SUC01;
                    header("location:mypage.php");
                }else{
                    debug('クエリ失敗');
                    $err_msg['fatal']= MSG06;
                }
            }else{
                $err_msg['oldpass'] = MSG13;
                debug('変更前パスワードが非合致');
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
    <title>パスワード変更ページ</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .main-conteiner{
            width: 60%;
            margin: 0 auto;
        }

        .signup-conteiner{
            padding: 25px 30px;
            width: 80%;
            margin: 110px auto;
            background-color: #f5f5f5;
        }

        .formName{
            text-align: center;
            margin-bottom: 10px;
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
        <div class="main-conteiner">
            <div class="signup-conteiner">
                <h2 class= "formName">パスワード変更</h2>
                <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                <form method="post">

                    <div class="form-group">
                        <label class="formLabel" for="oldpass">変更前のパスワードを入力<span class="help-block">
                        <?php if(!empty($err_msg)) echo $err_msg['oldpass'];?></span>
                            <input class="formArea valid-oldpass" id="oldpass" type="password" name="oldpass"
                            value=<?php if(!empty($_POST['oldpass'])) echo $_POST['oldpass'];?>></label>
                    </div>

                    <div class="form-group">
                        <label class="formLabel" for="newpass">変更するパスワードを入力<span class="help-block">
                        <?php if(!empty($err_msg)) echo $err_msg['newpass'];?></span>
                            <input class="formArea valid-newpass" id="newpass" type="password" name="newpass"
                            value=<?php if(!empty($_POST['newpass'])) echo $_POST['newpass'];?>></label>
                    </div>

                    <input class="submit-btn" type="submit" value="パスワード変更">
                    <a class="forgetPass" href="#">パスワードを忘れた方はこちら</a>

                </form>
            </div>
        </div>
        <pre>
            <?php var_dump($_POST);?>
        </pre>
        <?php require_once('footer.php')?>
</body>
</html>