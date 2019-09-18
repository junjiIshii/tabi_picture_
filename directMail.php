<?php
    require_once('functions.php');
    loginAuth();
    debug('セッション情報：'.print_r($_SESSION,true));
    $opposUser = getOneUserData($_GET['to'],'username,icon_img,delete_flg');
    $u_id = $_SESSION['user_id'];
    
    if(!empty($_GET['to'])){
        $send = $_GET['to'];
    }
    
    if($opposUser['delete_flg']==1){
        header('location:mypage.php');
    }elseif($_GET['to']==$_SESSION['user_id']){
        header('location:mypage.php');
    }
    
    

//EMPTY→0が送れない。POST['send_mes']内の空文字（未入力）は排除できる。
//isset⇨0は送れる。　空文字、未入力が排除できない。
//文字数で条件分岐したところ解決。


    if(isset($_POST['send_mes']) && strlen($_POST['send_mes'])>0){
        $msg = $_POST['send_mes'];
        $t = [$send,$u_id,$msg];
        debug(print_r($t,true));
        sendMessage($send,$u_id,$msg);
        //リロード時にメッセージが送信されない様にするためにPOSTをリセットする。
        $_POST =array();
    }

    $dmData = getMesaage($send,$u_id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ダイレクトメッセージ</title>
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

    .showErr{
        width:100%;
        display:inline-block;
        color:red;
        text-align: center;
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
        align-self:center;
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
        width:40px;
        height: 40px;
        border-radius: 50%;
        margin:0px 5px;
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
        font-size:10px;
        margin-top:5px;
    }



    
    </style>
</head>
<body>
    <?php require_once('header.php')?>

    <div class="main-conteiner">
        <div class="mainWrapper"> 
            <span class="showErr"><?php cautionEcho('fatal')?></span>
            <div class="oppositInfo-continer">
                <div class="oppositUser-icon" data-url="<?php echo "profile_detail.php?u_id=".$send?>">
                    <img src="<?php echo $opposUser['icon_img']?>" alt="">
                </div>

                <p class="oppositUser-name"><?php echo $opposUser['username']?></p>
            </div>

            <div class="message-conteiner">
                <?php foreach ($dmData as $key => $val):?>

                    <?php if($val['send_from']==$send){?>
                        <div class="opposit-message">
                            <div class="mini-user-icon">
                                <img src="<?php echo $val['icon_img']?>" alt="">
                            </div>
                            
                            
                            <p class="message"><?php echo $val['send_msg']?></p>
                            
                            <span class="postTime"><?php echo $val['create_time']?></span>
                        </div>
                    <?php }elseif($val['send_from']==$u_id){?>
                        <div class="my-message">
                            
                            <div class="mini-user-icon inMessage">
                                <img src="<?php echo $val['icon_img']?>" alt="">
                            </div>
                            <p class="message"><?php echo $val['send_msg']?></p>
                            <span class="postTime"><?php echo $val['create_time']?></span>
                        </div>
                    <?php }?>

                <?php endforeach;?>
            </div>

            <div class="messageEnter">
                <form action="" method="post">
                    <textarea name="send_mes" cols="40"></textarea>
                    <input class="send-btn" type="submit" value="メッセージ送信">
                </form>
            </div>
        </div>
        <?php require_once('mypageBar.php')?>
    </div>
    <pre><?php var_dump($_GET)?></pre>
    <?php require_once('footer.php')?>
    <script  type="text/javascript">
        $('.oppositUser-icon').click(function(){
        location.href=$(this).attr('data-url')
        });
    </script>
</body>
</html>