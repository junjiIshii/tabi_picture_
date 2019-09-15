<?php
 require_once('functions.php');
 loginAuth();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>プロフィール編集</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .main-conteiner{
            width: 85%;
        }

    .oppositInfo-continer{
        display:flex;
        border: 1px solid gray;
        padding:5px;
        width:100%;
    }

    .oppositUser-icon{
        width:80px;
        height: 80px;
        border-radius: 50%;
        border: white 5px solid;
    }

    .oppositUser-icon img{
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .oppositUser-name{
        align-self: center;
        margin-left: 20px;
        font-size:18px;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <?php require_once('header.php')?>

    <div class="main-conteiner">
        <div class="mainWrapper">
            <div class="oppositInfo-continer">
                <div class="oppositUser-icon">
                    <img src="pictures/sample01.jpg" alt="">
                </div>

                <p class="oppositUser-name">相手の名前</p>
            </div>

            <div class="message-conteiner">
                <div class="opposit-message">
                    <div class="oppsitUser-icon inMessage">
                        <img src="" alt="">
                    </div>

                    <div class="message">

                    </div>
                </div>

                <div class="my-message">
                    <div class="my-icon inMessage">
                        <img src="" alt="">
                    </div>
                    <div class="message">

                    </div>
                </div>
            </div>
        </div>
        <?php require_once('mypageBar.php')?>
    </div>

    <?php require_once('footer.php')?>
</body>
</html>