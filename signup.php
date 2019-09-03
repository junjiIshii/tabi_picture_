<?php
    require("functions.php");

    if(!empty($_POST)){
        $chk=array(
                'user-name'=>$_POST['user-name'],
                'email'=>$_POST['email'],
                'password'=>$_POST['password'],
                'repassword'=>$_POST['repassword']);

        //1.Email形式かのチェック
        vaidemail($chk[0],'email');

        //2.パスワードと再入力が合っているかのチェック
        passMuch($chk['password'],$chk['repassword'],'repassword');

        //3-1.ユーザーネームの最大・最小文字数
        minMaxWords($chk['user-name'],0,15,'user-name');

        //3-2.パスワードの最大・最小文字数
        minMaxWords($chk['password'],8,30,'password');

        //4.アドレスが重複していないか。
        vaidemailDup($chk['email']);
        if(delFlagchek($chk['email']) !=0 ){
            debug('デリート該当あり');
            $err_msg['email']= '退会処理済みです。復活する場合はログインしてください。';
        }

        //5.そもそも入力されているか？（未入力チェック）
        foreach($chk as $key => $value){
        mustEnter($value,$key);
        }
    }
    if(delFlagchek($chk['email']) !=0 && empty($err_msg)){
        debug('デリート該当あり');
        $_SESSION['past_email'] = $chk['email'];
        header('location:signup_again.php');
        exit();
    }

    if(!empty($_POST) && empty($err_msg)){
        signup();
        debug('登録処理実行');
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録ページ</title>
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
        }

        .has-error input {}
    </style>

</head>
<body>
    <?php require_once('header.php')?>
        <div class="main-conteiner">
            <div class="signup-conteiner">
                <h2 class= "formName">ユーザー登録</h2>
                <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                <form method="post">

                    <div class="form-group">
                        <label class="formLabel" for="user-name">ユーザー名を入力<span class="help-block">
                            <?php if(!empty($err_msg)) echo $err_msg['user-name'];?></span>
                            <input class="formArea valid-user-name" id="user-name" type="text" name="user-name"
                            value=<?php if(!empty($_POST['user-name'])) echo $_POST['user-name'];?>></label>
                            <!--<div class="err-msg"></div>PHP-->
                    </div>

                    <div class="form-group">
                        <label class="formLabel" for="email">Emailを入力<span class="help-block">
                            <?php if(!empty($err_msg)) echo $err_msg['email'];?></span>
                            <input class="formArea valid-email" id="email" type="text" name="email"
                            value=<?php if(!empty($_POST['email'])) echo $_POST['email'];?>></label>
                            <!--<div class="err-msg"></div>PHP-->
                    </div>

                    <div class="form-group">
                        <label class="formLabel" for="password">パスワードを入力<span class="help-block">
                        <?php if(!empty($err_msg)) echo $err_msg['password'];?></span>
                            <input class="formArea valid-pass" id="password" type="password" name="password"
                            value=<?php if(!empty($_POST['password'])) echo $_POST['password'];?>></label>
                    </div>

                    <div class="form-group">
                        <label class="formLabel" for="repassword">パスワードを再入力<span class="help-block">
                        <?php if(!empty($err_msg)) echo $err_msg['repassword'];?></span>
                            <input class="formArea valid-repass" id="repassword" type="password" name="repassword"
                            value=<?php if(!empty($_POST['repassword'])) echo $_POST['repassword'];?>></label>
                    </div>
                    <input class="submit-btn" type="submit" value="登録する">
                    <a class="reSignup" href="signup_again.php">退会して再度登録する場合はこちら</a>

                </form>
            </div>
        </div>
        <pre>
            <?php
            var_dump($_POST);
            var_dump(delFlagchek($_POST['email']));?>
        </pre>
        <?php require_once('footer.php')?>
</body>
</html>