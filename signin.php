<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「ログインページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();


//==================
//バリデーション
//==================
    if(!empty($_POST)){

        //1.Email形式かのチェック
        vaidemail($_POST['email'],'email');

        //2.パスワードが8文字以上入力/30文字以内で入力されているか？
        minMaxWords($_POST['password'],7,30,'password');


        //5.そもそも入力されているか？（未入力チェック）
        mustEnter($_POST['email'],'email');
        mustEnter($_POST['password'],'password');
    }

    $pass_save= (!empty($_POST['pass_save'])) ? true : false;

//==================
//ログイン処理
//==================
    if(!empty($_POST) && empty($err_msg)){
        debug('バリデーションOK');
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        try{
            $dbh = dbconnect();
            $sql = 'SELECT password, userid FROM users WHERE email = :email';
            $data = array(':email'=> $email);
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            debug('クエリの中身：'.print_r($result,true));

            //パスワードの照合

            //退会ユーザーでなく、合致した場合。EmailはEMPTYで区別している。
            if(delFlagchek($email) ==0 && !empty($result) && password_verify($password, array_shift($result))){
            debug('パスワードが合致');


            //デフォルトのログイン有効期限
            $sesLimit = 60*60;

            //最終ログイン日時を現在日時にする。
            $_SESSION['login_date']= time();


                //ログイン保持にチェックがある。
                if($pass_save){
                    debug('ログイン保持にチェックあり');

                    //ログイン有効期限を30日にする。
                    $_SESSION['login_limit']= $sesLimit*24*30;
                }else{
                    debug('ログイン保持にチェックなし');
                    $_SESSION['login_limit'] = $sesLimit;
                }

            //ユーザーIDをセッションに格納
            $_SESSION['user_id'] = $result['userid'];

            debug('セッション変数の中身：'.print_r($_SESSION,true));
            debug('マイページへ遷移');
            header('Location:mypage.php');

        }elseif(delFlagchek($email) !=0 && !empty($result) && password_verify($password, array_shift($result))){
            debug('退会ユーザーがログインしようとした');
            debug('復活登録フォームへ遷移');
            $_SESSION['past_email'] = $email;
            header('location:signup_again.php');
        }else{
            debug('パスワードが非合致');
            $err_msg['fatal'] = MSG07;
        }
    }catch(Exception $e){
        debug('エラー発生：'.($e->getMessage()));
        $err_msg['fatal']= MSG06;
    }
}
    debug('================画面表示処理終了');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
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
            margin-bottom: 5px;
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
        }

        .help-block{
            font-size:15px;
            margin-left:5px;
            color: red;
        }

        .forgetPass{
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .save-check{
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>

    </head>
    <body>
        <?php require_once('header.php')?>
            <div class="main-conteiner">
                <div class="signup-conteiner">
                    <h2 class= "formName">ログイン</h2>
                    <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                    <form method="post">
                        <div class="form-group">
                            <label class="formLabel" for="email">登録Email<span class="help-block">
                                <?php if(!empty($err_msg)) echo $err_msg['email'];?></span>
                                <input class="formArea valid-email" id="email" type="text" name="email"
                                value=<?php if(!empty($_POST['email'])) echo $_POST['email'];?>></label>
                        </div>

                        <div class="form-group">
                            <label class="formLabel" for="password">登録パスワード<span class="help-block">
                            <?php if(!empty($err_msg)) echo $err_msg['password'];?></span>
                                <input class="formArea valid-pass" id="password" type="password" name="password"
                                value=<?php if(!empty($_POST['password'])) echo $_POST['password'];?>></label>
                        </div>
                        <label class="save-check" for="sess_save"><input id="sess_save" type="checkbox" name="pass_save[]" value="save">ログインを保持する。</label>
                        <input class="submit-btn" type="submit" value="ログイン">
                        <a class="forgetPass" href="passReset.php">パスワードを忘れた方はこちら</a>

                    </form>
                </div>
            </div>
            <?php require_once('footer.php')?>
    </body>
</html>