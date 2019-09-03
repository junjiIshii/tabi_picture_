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
            $dbh = dbconnect();
            $sql = 'SELECT password, userid FROM users WHERE userid = :userid';
            $data = array(':userid'=> $userid);
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            debug('クエリの中身：'.print_r($result,true));

            //パスワードの照合

            if(password_verify($oldpass, array_shift($result))){
                debug('変更前パスワードが合致');
                $editPass = password_hash($newpass, PASSWORD_DEFAULT);

                $dbh = dbconnect();
                $sql = 'UPDATE users SET password =:pass WHERE userid = :userid';
                $data = array(':pass'=> $editPass,':userid'=> $userid);
                $stmt = queryPost($dbh,$sql,$data);
                $result = $stmt -> fetch(PDO::FETCH_ASSOC);

                if($stmt){
                    debug('クエリ成功');
                    debug('パスワードを変更しました。');
                    debug('マイページへ遷移');
                    header("location:mypage.php");
                }else{
                    debug('クエリ失敗');
                    $err_msg['fatal']= MSG06;
                }
            }elseif(password_verify($newPass, array_shift($result))){
                $err_msg['newpass'] = MSG13;
                debug('変更前パスワードと変更パスワードが変わらない');
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