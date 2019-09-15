<?php
    require_once('functions.php');
    loginAuth();
    if(!empty($_GET['to'])){
        $send = $_GET['to'];
    }else{
        header('location:mypage.php');
    }
    
    $u_id = $_SESSION['user_id'];

    //やること、SEND側の実装。TIMESTAMPのCSS調整。

    function getMesaage($to,$user){
        debug('DM内容を取得します。');
        try{
            $dbh = dbconnect();
            $sql = 'SELECT send_from,send_to,send_msg,d.create_time,u.username,u.icon_img
                    FROM users AS u RIGHT JOIN dm AS d ON u.userid = send_to
                    WHERE send_from= :sender AND send_to = :sendto
                    ORDER BY d.create_time DESC';

            //送信者が自分自身。このデータでは自分が送ったメッセージを取得
            $dataSed = array(':sender'=>$user, ':sendto'=>$to);

            //自分宛に送られた相手のメッセージ
            $dataRec = array(':sender'=>$to, ':sendto'=>$user);

            $stmtSed = queryPost($dbh,$sql,$dataSed);
            $stmtRec = queryPost($dbh,$sql,$dataRec);

            $sendData = $stmtSed -> fetchall(PDO::FETCH_ASSOC);
            $reciveData = $stmtRec -> fetchall(PDO::FETCH_ASSOC);;

            //自分が送信したもの、相手から受信したものを一つの配列にマージする。
            $dmData = array_merge($reciveData,$sendData);
            //debug('ソート前DM配列：'.print_r($dmData,true));

            //creat_timeの昇順でソートするために、そのソート源としての配列を作る。
            foreach($dmData as $key =>$val){
                $sort[$key] = $val['create_time'];
            }
            //debug('ソート配列：'.print_r($sort,true));
            //ソート用配列を元に、creat_time=送信時間順番に並び替える。
            array_multisort($sort,SORT_ASC,$dmData);
            //debug('ソート後DM配列：'.print_r($dmData,true));
            
            return $dmData;
        }catch(Exception $e){
            debug('エラー内容：'.$e->getMessage());
        }
    }

    $dmData = getMesaage($u_id,$send);
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
        margin:0px 3px 8px 3px;
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
                <?php foreach ($dmData as $key => $val):?>

                    <?php if($val['send_from']==$send){?>
                        <div class="opposit-message">
                            <div class="mini-user-icon inMessage">
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