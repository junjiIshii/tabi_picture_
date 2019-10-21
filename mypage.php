<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「マイページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();
    

    if(!empty($_GET['max'])){
        $max = $_GET['max'];
    }else{
        $max = 5;
    }
    $notifyData = get_notify($_SESSION['user_id'],$max);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <style>
        .main-conteiner{
            justify-content: center;
        }

        .guide-conteiner{
            margin:10px 0px;
        }

        .forProdList{
            /*flex-basis: 150px;*/
            background: rgba(7, 230, 163, 0.6);
            color:cadetblue;
        }

        .forUpload{
            /*flex-basis: 160px;*/
            background:skyblue;
            color:dodgerblue;
        }

        .upload-guide{
            display: flex;
            flex-direction: column;
        }

        .url-button{
            width: 160px;
            height:45px;
            padding: 5px;
            font-size:13px;
            font-weight:bold;
            cursor:pointer;
        }

        .blue{
            background:skyblue;
            color:dodgerblue;
            margin-right:10px;
        }

        .green{
            background: rgba(7, 230, 163, 0.6);
            color:cadetblue;
            margin-right:10px;
        }

        .user-icon{
            width:50px;
            height: 50px;
            border: white 5px solid;
            border-radius: 50%;
            min-width:50px;
            min-height:50px;
        }

        .user-icon img{
            object-fit: cover;
            width: 100%;
            height:100%;
            border-radius: 50%;
        }

        .notify-conteiner{
            display: flex;
            flex-direction:column; 
            margin-right:10px;
            width:510px;
        }

        .guide-button{
            display:flex;
            justify-content: center;
            margin-bottom:10px;
        }

        .notifyUnit{
            display: flex;
            align-items: center;
            border-bottom:1px solid #f5f5f5;
        }

        .notify-conteiner :hover{ 
            background: #f5f5f5;
            transition: background 0.4s;
        }

        .menu-name{
            margin-top:10px;
        }

        .fas{
            margin:0px 8px;
            align-self: center;
        }
        .fa-heart{
            color:pink;
        }

        .fa-user{
            color:mediumaquamarine;
        }

        .fa-envelope{
            color:dodgerblue;
        }
        

        .content-area{
            display:flex;
        }

        .notify_time{
            font-size:10px;
        }

    </style>
    <link href="responsive.css" rel="stylesheet">
</head>
<body>

    <?php require_once('header.php')?>
    <div class="main-conteiner">
        <div class="guide-conteiner">
            
            <div class="upload-guide">
                <h3 class="menu-name">メイン</h3>
                <div class="guide-button first">
                    <button class="url-button green has-link" type="button" data-url="myproducts_list.php">商品を編集する</button>
                    <button class="url-button blue has-link " type="button" data-url="productEdit.php">商品をアップロードする</button>
                </div>

                <div class="guide-button second">
                    <button class="url-button blue has-link" type="button" data-url="directMail.php">DMを見る</button>
                    <button class="url-button green has-link" type="button" data-url="profileEdit.php">プロフィール編集</button>
                </div>

                <div class="guide-button third">
                    <button class="url-button green has-link" type="button" data-url="products_list.php">商品一覧</button>
                    <button class="url-button blue has-link" type="button" data-url="users_list.php">ユーザー一覧</button>
                </div>


            </div>

                <h3 class="menu-name">お知らせ一覧</h3>
                <div class="notify-conteiner ">
                    
                        <?php if(!empty($notifyData)){?>
                        <?php foreach($notifyData as $key => $val){?>

                            <?php if($val['type']==1){ //お気に入りの通知?>
                                <div class="notifyUnit has-link" data-url="<?php echo "directMail.php?to=".$val['userid']?>">
                                    <div class="user-icon has-link" data-url="<?php echo "directMail.php?to=".$val['userid']?>">
                                        <img src="<?php echo $val['icon_img']?>">
                                    </div>

                                    <div class="content-area">
                                        <i class="fas fa-heart favorit-btn fa-lg" ></i><p class = "content"><?php echo $val['contents']?><span class="notify_time"><?php echo $val['create_time']?></span></p>
                                        
                                    </div>
                                </div>
                            <?php }elseif($val['type']==0){ //フォローの通知?>
                                <div class="notifyUnit has-link" data-url="<?php echo "profile_detail.php?u_id=".$val['userid']?>">
                                    <div class="user-icon has-link" data-url="<?php echo "profile_detail.php?u_id=".$val['userid']?>">
                                            <img src="<?php echo $val['icon_img']?>">
                                    </div>

                                    <div class="content-area">
                                        <i class="fas fa-user fa-lg"></i><p class = "content"><?php echo $val['contents']?><span class="notify_time"><?php echo $val['create_time']?></span></p>
                                    </div>
                                </div>
                            <?php }elseif($val['type']==2){ //DMの通知?>
                                <div class="notifyUnit has-link" data-url="<?php echo "directMail.php?to=".$val['userid']?>">
                                    <div class="user-icon has-link" data-url="<?php echo "profile_detail.php?u_id=".$val['userid']?>">
                                        <img src="<?php echo $val['icon_img']?>">
                                    </div>
                                    <div class="content-area">
                                        <i class="fas fa-envelope fa-lg"></i><p class = "content"><?php echo $val['contents']?><span class="notify_time"><?php echo $val['create_time']?></span></p>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php } ?>
                        <?php }else{ ?>
                            <p>まだ通知はありません。</p>
                        <?php } ?>
                </div>
            </div>
            <?php require_once('mypageBar.php')?>
        </div>
        
    </div>
    <?php require_once('footer.php')?>
    <script>
        $('.has-link').click(function(){
            location.href=$(this).attr('data-url')
        });

    </script>
</body>
</html>