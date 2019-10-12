<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「退会ページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();

    if(!empty($_POST)){
        debug('POST送信あり');
        try{
            $dbh= dbconnect();
            $sql1= 'UPDATE users SET delete_flg = 1 WHERE userid = :us_id';
            $sql2= 'UPDATE products SET delete_flg = 1 WHERE userid = :us_id';
            $sql3= 'UPDATE favorite SET delete_flg = 1 WHERE userid = :us_id';

            $data = array(':us_id'=>$_SESSION['user_id']);

            $stmt1 =queryPost($dbh,$sql1,$data);
            $stmt2 =queryPost($dbh,$sql2,$data);
            $stmt3 =queryPost($dbh,$sql2,$data);

            if($stmt1){
                session_destroy();
                debug('退会によるセッション削除：'.print_r($_SESSION,true));
                debug('トップページへ遷移');
                header("location:products_list.php");
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
    <title>退会ページ</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
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
    <link href="responsive.css" rel="stylesheet">
</head>
    <body>
        <?php require_once('header.php')?>
            <div class="main-conteiner">
                <div class="signup-conteiner">
                    <h2 class= "formName">退会手続き</h2>
                    <div class="help-block"><?php if(!empty($err_msg)) echo $err_msg['fatal'];?></div>

                    <form method="post">

                        <input class="submit-btn" type="submit" name="submit" value="退会する">

                    </form>
                </div>
            </div>
            <?php require_once('footer.php')?>
    </body>
</html>