<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「アカウント復活処理」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    if(!empty($_POST)){
        debug('POST送信あり');
        try{
            $dbh= dbconnect();
            $sql = 'SELECT userid FROM users WHERE email = :email';
            $data1 = array(':email'=>$_SESSION['past_email']);

            $stmt = queryPost($dbh,$sql,$data1);
            $userid = $stmt -> fetch(PDO::FETCH_ASSOC);
            //debug('データ内容：'.print_r($userid,true));

            $sql1= 'UPDATE users SET delete_flg = 0 WHERE userid = :us_id';
            $sql2= 'UPDATE products SET delete_flg = 0 WHERE userid = :us_id';
            $sql3= 'UPDATE favorite SET delete_flg = 0 WHERE userid = :us_id';

            $data2=array(':us_id'=>$userid['userid']);
            //debug('データ内容：'.print_r($data2,true));
            
            $stmt1 =queryPost($dbh,$sql1,$data2);
            $stmt2 =queryPost($dbh,$sql2,$data2);
            $stmt3 =queryPost($dbh,$sql2,$data2);

            if($stmt1){
                $_SESSION['login_date']= time();
                $_SESSION['login_limit'] = 3600;
                $_SESSION['user_id'] = $userid['userid'];


                unset($_SESSION['past_email']);

                debug('退会によるセッション削除：'.print_r($_SESSION,true));
                debug('トップページへ遷移');

                $_SESSION['msg_suc']="アカウント復活処理が完了しました。";
                header("location:mypage.php");
            }else{
                debug('クエリ失敗！！');
                $err_msg['fatal']=MSG06;
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
    <title>アカウント復活</title>
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
                    <h2 class= "formName">アカウント復活手続き</h2>
                    <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                    <form method="post">
                        <?php
                            echo "<p>こちらのアドレス『{$_SESSION['past_email']}』は退会処理済みです。
                            <br>アカウントの復活を希望する場合はボタンを押すとログインできます。</p>";

                        ?>
                        <input class="submit-btn" type="submit" name="submit" value="アカウント復活">

                    </form>
                </div>
            </div>
            <?php require_once('footer.php')?>
    </body>
</html>