<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「ログインページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    //仮にログイン済みのユーザーが来た場合
    if(!empty($_SESSION['user_id'])){
        $_SESSION['msg_suc']="すでにログイン済です。";
        header('location:mypage.php');
        exit();//後続の処理でmsg_sucが消えないようにする。
    }
    
//==================
//バリデーション
//==================
    if(!empty($_POST)){

        //1.Email形式かのチェック
        vaidemail($_POST['email'],'email');


        //入力されているか？（未入力チェック）
        mustEnter($_POST['email'],'email');
        mustEnter($_POST['password'],'password');
    }

    $pass_save= (!empty($_POST['pass_save'])) ? true : false;

//==================
//ログイン処理
//==================
    signin();
    debug('================画面表示処理終了');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
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
    <link href="responsive.css" rel="stylesheet">
    </head>
    <body>

        <?php require_once('header.php')?>
            <div class="main-conteiner">
                <div class="signup-conteiner">
                    <h2 class= "formName">ログイン</h2>
                    <div class="help-block"><?php if(!empty($err_msg['fatal'])) echo $err_msg['fatal'];?></div>

                    <form method="post">
                        <div class="form-group">
                            <label class="formLabel" for="email">登録Email<span class="help-block">
                                <?php if(!empty($err_msg['email'])) echo $err_msg['email'];?></span>
                                <input class="formArea valid-email" id="email" type="text" name="email"
                                value=<?php if(!empty($_POST['email'])) echo $_POST['email'];?>></label>
                        </div>

                        <div class="form-group">
                            <label class="formLabel" for="password">登録パスワード<span class="help-block">
                            <?php if(!empty($err_msg['password'])) echo $err_msg['password'];?></span>
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