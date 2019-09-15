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
        width: 60%;
        margin-top:15px;
        flex-wrap: nowrap;
        justify-content: flex-start;
    }

    .mypage-menu-conteiner{
        margin-left:20px;
        
    }
    .mainWrapper{
        width:100%;
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
        margin-left: 20px;
        font-size:18px;
        font-weight: bold;
    }

    .message-conteiner{
        width:100%;
        display: flex;
        flex-direction: column;
        margin-bottom:20px;
        overflow: scroll;
        height:400px;
        margin-top:5px;
    }

    .opposit-message{
        margin-top:10px;
        display:flex;
        
    }

    .my-message{
        margin-top:10px;
        display:flex;
        /*justify-content:flex-end;を消したら右に詰まってくれた。。。*/
        flex-direction:row-reverse;
        
    }

    .message {
        align-self:center;
        padding:5px;
        max-width: 60%;
        word-break: break-all;
        border-radius: 5px;
    }

    .opposit-message .message{
        background: rgba(221, 228, 226, 0.6);
    }

    .my-message .message{
        background: rgba(7, 230, 163, 0.6);
    }

    .mini-user-icon{
        width:60px;
        height: 60px;
        border-radius: 50%;
        border: white 5px solid;
        display:inline-block;
    }

    .mini-user-icon img{
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .postTime{
        align-self: flex-end;
        font-size:10px;
        margin:0px 3px;
    }

    .messageEnter form{
        width:100%;
        display: flex;
        margin-bottom:10px;
        padding:5px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .messageEnter textarea{
        width:100%;
        resize: none;
        height:80px;
        padding: 5px;
        font-size:16px;
    }

    .send-btn{
        text-align:center;
        padding:5px;
        vertical-align: bottom;
        font-size:13px;
        margin-top:5px;
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
                    <div class="mini-user-icon inMessage">
                        <img src="pictures/sample01.jpg" alt="">
                    </div>
                    
                    
                    <p class="message">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</p>
                    
                    <span class="postTime">2019/7/15 16:30</span>
                </div>

                <div class="my-message">
                    
                    <div class="mini-user-icon inMessage">
                        <img src="pictures/sample03.jpg" alt="">
                    </div>
                    <p class="message">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</p>
                    <span class="postTime">2019/7/15 16:30</span>
                </div>

                

                
                
            </div>

            <div class="messageEnter">
                <form action="" method="post">
                    <textarea name="sendMes" cols="40"></textarea>
                    <input class="send-btn" type="submitt" value="メッセージ送信">
                </form>
            </div>
        </div>
        <?php require_once('mypageBar.php')?>
    </div>

    <?php require_once('footer.php')?>
</body>
</html>