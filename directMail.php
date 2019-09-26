<?php
    require_once('functions.php');
    loginAuth();
    debug('セッション情報：'.print_r($_SESSION,true));

    //自分と送信先の相手のIDを指定されたGET値から取得。そして送信先相手の情報を取得する。
    $u_id = $_SESSION['user_id'];
    if(!empty($_GET['to'])){
        $send = $_GET['to'];
        $opposUser = getOneUserData($_GET['to'],'userid,username,icon_img,delete_flg');
        createMesRoom($u_id,$opposUser['userid']);

        //自分と送信相手のメッセージルームのルーム情報を取得
        $thisRomm = getMesRomm($u_id,$opposUser['userid']);
    }else{
        $send = 0;
        $opposUser= array('delete_flg'=>"0");
        //マイページからDM画面に来たとき。リストから送信相手を選択させる。初期値。
    }
    if($opposUser['delete_flg']==1){
        header('location:mypage.php');
    }elseif($send==$_SESSION['user_id']){
        header('location:mypage.php');
    }
    
    //他にDMができるユーザーの一覧作成に必要なデータを取得
    $roomList =getMesRommList($u_id);
    $userdata = array();
    for($i=0;$i<count($roomList);$i++){
        
        if($roomList[$i]['user1']==$u_id){
            $s = array_merge($roomList[$i],getOneUserData($roomList[$i]['user2'],'userid,username,icon_img'));
            array_push($userdata,$s);
        }elseif($roomList[$i]['user2']==$u_id){
            $s = array_merge($roomList[$i],getOneUserData($roomList[$i]['user1'],'userid,username,icon_img'));
            array_push($userdata,$s);
        }
        
    }
    //debug('ユーザーリスト：'.print_r($userdata,true));

//EMPTY→0が送れない。POST['send_mes']内の空文字（未入力）は排除できる。
//isset⇨0は送れる。　空文字、未入力が排除できない。
//文字数で条件分岐したところ解決。


    if(isset($_POST['send_mes']) && strlen($_POST['send_mes'])>0){
        $msg = $_POST['send_mes'];
        $_POST =array();
        $t = [$send,$u_id,$msg];
        debug(print_r($t,true));
        sendMessage($send,$u_id,$msg);

        updateMesRoom($thisRomm['roomid'],$msg);
        set_notify($send,$u_id,2,1);
        //リロード時にメッセージが送信されない様にするためにPOSTをリセットする。
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
        /*width: 60%;*/
        margin-top:20px;
        flex-wrap: nowrap;
        justify-content: flex-start;
    }

    .mypage-menu-conteiner{
        margin-top:0px;
    }

    .mypage-menu-conteiner{
        margin-left:20px;
        
    }
    .mainWrapper{
        width:50%;
    }

    .showErr{
        width:100%;
        display:inline-block;
        color:red;
        text-align: center;
    }

    .upper{
        display:flex;
    }

    .mailList-conteiner{
        width:30%;
        display:flex;
        flex-direction: column;
        margin-right:10px;
        border:2px solid rgb(221, 228, 226);
        padding-top:5px;
        height:500px;
        overflow: scroll;
    }

    .listUnit{
        display:flex;
        flex-direction:column;
        align-content: center;
        margin-bottom:5px;
        border-bottom:1px dotted rgb(221, 228, 226);
        cursor:pointer;
    }

    .listUnit :last-child{
        margin-bottom:0px;
        border-bottom:none;
    }

    /*.listUnit :hover{
        background: #f5f5f5;
        なぜかlistUnit全体の色が変わらないで、子要素個別に色が変わってしまう
    }*/

    .userName{
        align-self: flex-end;
    }

    .last-mes{
        word-break: break-all;
        padding:7px;
        font-size:13px;
        color:gray;
        height:50px;
        max-height:80px;
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
        cursor:pointer;
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
        flex-wrap: nowrap;
        
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


    /*↓DMないのアイコンとリストのアイコンのCSSを兼ねている。*/
    .mini-user-icon{
        width:40px;
        height: 40px;
        border-radius: 50%;
        margin:0px 5px;
    }

    /*↓DMないのアイコンとリストのアイコンのCSSを兼ねている。*/
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
        text-align:right;
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
        width: 160px;
        height:45px;
        padding: 5px;
        margin-top:5px;
        font-size:13px;
        font-weight:bold;
        cursor:pointer;
        background:skyblue;
        color:dodgerblue;
    }

    .first-guide{
        text-align: center;
        font-size:13px;
    }



    
    </style>
</head>
<body>
        <!-- 案内用のニュッと出てくるやつ-->
    <p id="js-show-msg"  class="msg-slide" style="display:none;">
        <?php echo getSessionFlash('msg_suc') ;?>
    </p>

    <?php require_once('header.php')?>

    <div class="main-conteiner">
        <div class="mailList-conteiner">
            <h3>メッセージリスト</h3>
            <?php foreach($userdata as $key => $val){?>
            <div class="listUnit link-cover" data-url="<?php echo "directMail.php?to=".$val['userid']?>">
                <div class="upper">
                    <div class="mini-user-icon">
                        <img src="<?php echo $val['icon_img']?>">
                    </div>
                    <h3 class="userName"><?php echo $val['username']?></h3>
                </div>

                <p class="last-mes"><?php 
                if(!empty($val['last_mes'])){
                    //メッセージ有り
                    if(strlen($val['last_mes'])>30){
                        //メッセージが３０文字以上の場合は３０文字からは表示しない。
                        echo mb_substr($val['last_mes'],0,30)."...";
                    }else{
                        //メッセージが３０文字以下ならそのまま表示
                        echo $val['last_mes'];
                    }
                }?></p>
            </div>
            <?php }?>
        </div>


        <div class="mainWrapper"> 
            <?php if($send != 0){?>
                <?php if(!empty($err_msg)){ //エラーがあった場合表示?>
                    <span class="showErr"><?php cautionEcho('fatal')?></span>
                <?php }?>
                <div class="oppositInfo-continer">
                    <div class="oppositUser-icon link-cover" data-url="<?php echo "profile_detail.php?u_id=".$send?>">
                        <img src="<?php echo $opposUser['icon_img']?>" alt="">
                    </div>

                    <p class="oppositUser-name link-cover"><?php echo $opposUser['username']?></p>
                </div>

                <div class="message-conteiner">
                    <?php if(!empty($dmData)){ ?>
                        <?php foreach ($dmData as $key => $val):?>

                            <?php if($val['send_from']==$send){?>
                                <div class="opposit-message">
                                    <div class="mini-user-icon link-cover" data-url="<?php echo "profile_detail.php?u_id=".$sends?>">
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
                    <?php }?>
                </div>

                <div class="messageEnter">
                    <form action="" method="post">
                        <textarea name="send_mes" cols="40" placeholder="ここにメッセージを入力"></textarea>
                        <input class="send-btn" type="submit" value="メッセージ送信">
                    </form>
                </div>
            <?php }else{ //まずリストから送信先を選ぶ場合?>
                <p class="first-guide">左のメッセージリストから送信先を選択してください。</p>
                <p class="first-guide">送信ユーザーを追加する場合、プロフィール一覧＞ユーザーを選択＞DMをする で追加してください。</p>
            <?php }?>
        </div>
        <?php require_once('mypageBar.php')?>
    </div>

    <?php require_once('footer.php')?>
    <script  type="text/javascript">
        $('.link-cover').click(function(){
        location.href=$(this).attr('data-url')
        });
    </script>
</body>
</html>