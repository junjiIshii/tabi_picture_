<?php 
    require_once('functions.php');


    $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
    //GETからユーザーIDをとる。ユーザーIDが空の場合はユーザリストへ遷移
    $u_id = (!empty($_GET['u_id']))? $_GET['u_id']:header("location:users_list.php");

    //GETに不正な値が入った場合はユーザーリストへ遷移
    if((int)$currentPg === 0 ||(int)$u_id===0){
        header("location:users_list.php");
    }

    //ユーザーのデータを取得
    $u_data = getOneUserData($u_id,"username,introduction,header_img,icon_img,delete_flg");

    if(empty($u_data)){
        debug('存在しないユーザーのIDが入力されました。');
        header("location:mypage.php");
    }elseif($u_data['delete_flg']==1){
        debug('削除されたユーザーのIDが入力されました。');
        $_SESSION['msg_suc']=MSG20;
        header("location:mypage.php");
    }

    //ここからページングの調整。全体数の取得
    $allNum = getUserProducNum($u_id);

    //商品を一つでも登録してなければページングはしない。
    if($allNum != 0){

        $maxShowNum = 12;
        $offset =($currentPg-1)*$maxShowNum;
        //ユーザーIDからそのユーザーが登録した商品の情報を取得
        $p_data = makeUserProducList($maxShowNum,$offset,$u_id);

        $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数

        //最大ページ以上のGETパラメータを不正に入力した場合、１ページ目へ送る。
        if($currentPg > $lastPg_count){
            header("location:mypage.php");
        }

        $pgData = paging($allNum,$currentPg,$lastPg_count,$maxShowNum);
    }
    

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール編集</title>
    <link href="style.css" rel="stylesheet">

    <style>
    .main-conteiner{
        width: 85%;
        margin: 0 auto;
        margin-top:10px;
            }

    .mypage-menu-conteiner{
        display: inline-block;
        vertical-align: top;
        width:200px;
        padding: 10px;
        background-color: lightgrey;
        height: 100%;
        }

    .mypage-menu-section{
        list-style: none;
        margin-bottom: 15px;
    }

    .mypage-menu-section:last-child{
        margin-bottom: 0px;
    }

    .user-data-container{
        display: inline-block;
        height: 100%;
        width:70%;
        background: #f5f5f5;
        position:relative;
        
    }

    .user-header{
        width:100%;
        height:300px;
    }

    .header_img{
        width:100%;
        height:100%;
        object-fit: cover;
    }

    .user-icon{
        width:180px;
        height: 180px;
        border-radius: 50%;
        border: white 5px solid;
        position:absolute;
        top:200px;
        left:10px;
    }

    .icon_img{
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .profileArea{
        width:85%;
        margin:10px auto;
    }

    

    .btn-wrapper{
        text-align: right;
    }

    .dm-btn{
            background:#0489B1;
            color:white;
            font-size:18px;
            width:150px;
            height:40px;
            border-radius: 20px;
            cursor: pointer;
        }

    .follow-btn{
        font-size:18px;
        width:150px;
        height:40px;
        border-radius: 20px;
        cursor: pointer;
    }

    .unfollow{
        color: #0065a8;
        border:2px solid #0065a8;
        background:#f1f1f1;
    }

    .followed{
        border:2px solid #0065a8;
        background: #0065a8;
        color:white;
    }

    .info-wrap{
        margin:30px 0px 20px 0px;

    }

    .userName{
        font-size:24px;
        font-weight: bold;
    }

    .introduction{
        margin-top:10px;
        padding:5px 0px;
        border-top: 2px dotted gray;
        border-bottom: 2px dotted gray;
    }

    .productArea{
        
    }

    .product-unit-wrap{
        display:flex;
        justify-content: center;
        flex-wrap:wrap;
    }

    .product-unit{    
        width:200px;
        height: 400px;
        display: inline-block;
        font-size: 15px;
        margin: 10px 5px;
        background: #f1f1f1;
        box-shadow:3px 8px   rgba(61, 60, 60, 0.2);
        vertical-align:top;
    }

    .product-unit:hover{
        background: #d9d9d9;
    }

    .product-img img{
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .paging {
        width: 100%;
        height:40px;
        text-align:center;
        margin:20px 0px;
    }

    .pageNum{
        height:50px;
        width:40px;
        line-height:50px;
        display:inline-block;
        background-color: #01DFA5;
        cursor:pointer;
    }
    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">

        <div class="user-data-container">
            
                <div class="user-header">
                    <img class="header_img" src="<?php echo $u_data['header_img']?>" alt="" >               
                </div>

                <div class="user-icon">
                    <img class="icon_img" src="<?php echo $u_data['icon_img']?>" alt="" >               
                </div>

            <div class="profileArea">

                <div class="btn-wrapper">
                <button type="button" class="dm-btn">DMをする</button>
                <button type="button" class="card follow-btn unfollow" >フォロー</button>
                </div>

                <div class="info-wrap">
                    <p class="userName"><?php echo $u_data['username']?></p>
                    <p class="introduction"><?php echo $u_data['introduction']?></p>
                </div>
                <h2>出品商品一覧</h2>
                <div class="productArea">
                    <?php if($allNum != 0){?>
                    <div class="product-unit-wrap"> 
                        <?php for($i=0; $i< $maxShowNum; $i++) {;?>
                            <div class="product-unit" data-url="<?php echo "product_detail.php?p_id=".$p_data[$i]['productid']?>">
                                
                                <div class="product-img">
                                    <img src="<?php echo $p_data[$i]['pic1']?>">
                                </div>
                            
                                <p class = "product title"><?php echo $p_data[$i]['title']?></p>

                                <div class="product pictureinfo">
                                    <p><?php echo $p_data[$i]['detail']?></p>
                                </div>

                            </div>
                        <?php }?>
                    </div>

                    
                        <div class="paging">
                                <ul class="paging-list">
                                    <?php if($currentPg != 1):?>
                                    <li class="pageNum" data-url="<?php echo "?u_id={$u_id}&pg=1"?>">＜</li>
                                    <?php endif?>

                                    <?php for($p=$pgData['minPg'];$p<=$pgData['maxPg'];$p++){?>
                                        <li style="<?php if($currentPg==$p)echo'background:#088A4B;'?>"
                                        data-url="<?php echo "?u_id={$u_id}&pg={$p}"?>"
                                        class="pageNum">
                                        <?php echo $p?></li>
                                    <?php }?>

                                    <?php if($currentPg != $lastPg_count):?>
                                    <li class="pageNum" data-url="<?php echo "?u_id={$u_id}&pg={$lastPg_count}"?>">＞</li>
                                    <?php endif;?>
                                </ul>
                        </div> 

                    <?php }else{?>
                        <div class="noProducts">
                            まだ商品を登録していません。
                        </div>
                    <?php };?>

                </div>

            </div>
            
        </div>
        <?php require_once('mypageBar.php')?>
    </div>
    <div class="cd"></div>
    
    <?php require_once('footer.php')?>
    <script type="text/javascript" src="follow_btn.js"></script>
    <script>
        $('.product-unit').click(function(){
        location.href=$(this).attr('data-url')
        });

        $('.pageNum').click(function(){
        location.href=$(this).attr('data-url')
        });
    </script>
</body>
</html>