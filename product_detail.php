<?php
    require_once('functions.php');

    //loginAuth();

    $currentPg_id = $_GET['p_id']; 
    $getCategory = getCategory();

    if((int)$currentPg_id === 0){
        debug('不正な値が入りました。');
        header("location:mypage.php");
    }

    $p_data = showProductData($currentPg_id);
    $category = $getCategory[$p_data['categoryid']]['category_name'];

    if(empty($p_data)){
        debug('存在しない商品のIDが入力されました。');
        header("location:mypage.php");
    }elseif($p_data['delete_flg']==1){
        debug('削除された商品のIDが入力されました。');
        $_SESSION['msg_suc']=MSG19;
        header("location:mypage.php");
    }elseif($p_data['open_flg']==0){
        debug('非公開の商品にアクセスしました。');
        header("location:mypage.php");
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <style>
        .main-conteiner{
            width:75%;
            flex-direction:column;
            justify-content:center;
        }

        .detail-baseinfo{
            width:100%;
            margin-top: 30px;
            
        }
        .detail-baseinfo p{ 
            display: inline-block;
            vertical-align: middle;
            /*border: 1px black solid;*/
            padding:0px 10px;
        }

        .productName{
            width: 100%;
            font-size: 25px;
            font-weight:bold;
            height: 70px;
            line-height: 70px;
            background:#f5f5f5;
        }

        .productCategory{
            display:inline-block;
            margin:5px 0px;
            padding:5px;
            background:#01DFA5;
            color:white;
            font-weight:bold;
        }

        .slide-btn{
            cursor:pointer;
            color:#01DFA5;
            align-self:center;
            margin:0px 10px;
        }

        .now-imgNum{
            margin-top:10px;
        }


        .dots{
            color:#01DFA5;
        }

        .dot1{
            color:#088A4B;
        }

        .detail-pictures{
            display: flex;
            flex-direction:column;
            align-items: center;
            margin: 15px 0px;

        }

        .detail-product{
            display: flex;
            flex-wrap:wrap;
            margin: 0px 5px;
            /*position:relative;*/
        }

        .imgWrap{
            width:350px;
            height:350px;
        }

        .slidesImg{
            height:100%;
            width:100%;
            box-shadow:3px 4px rgba(61, 60, 60, 0.2);
        }


        .detail-info{
            width:90%;
        }

        .detail-caption{
            font-size:18px;
            border-bottom:2px dotted gray;
            margin-bottom:10px;
            width:200px;
        }

        .detailText{
            height: 130px;
        }

        .under-coteiner{
            display:flex;
            flex-direction:column;
            width:60%;
            height: 180px;
            background:#f5f5f5;
            vertical-align: top;
            margin:20px 0px;
            padding:8px;
        }

        .upper-side{
            border-bottom:2px dotted gray;
            display:flex;
        }

        .under-side{
            padding:4px;
            width:70%;
            width:100%;
        }

        .price{
            margin-right:10px;
            font-size:26px;
            font-weight:bold;
            background:#f5f5f5;
            display:inline-block;
            padding:5px;
            margin:5px 0px;
        }
        
        .user-icon-unit{
            width: 70px;
            height:70px;
        }

        .userName{
            align-self:flex-end;
            font-weight:bold;
            font-size:25px;
            margin-left:5px;
        }

        
        .user-icon-unit img{
            object-fit: cover;
            width: 100%;
            height:100%;
            border-radius: 50%;
            cursor:pointer;
            border: white 5px solid;
        }

        .mini-imgNum{
            display:none;
        }

        .introduction{
            width:100%;
            height:auto;
            max-height:90px;
        }

        .actions{
            display:flex;
            flex-wrap:wrap;
            margin:15px 0px;
        }

        .buttons{
            width: auto;
            display:flex;
            flex-wrap:wrap;
        }

        .buttons button{
            width:180px;
            vertical-align: top;
            padding:5px 10px;
            font-size: 18px;
            font-weight:bold;
            margin:2px;
        }

        .buy-btn{
            background: #04B45F;
            color:white;
        }

        .dm-btn{
            background:#0489B1;
            color:white;
        }

        .favorit-btn{
            color:#B40404;
            background:#f5f5f5;
            border:2px solid #B40404;
        }

        .checked{
            background:#B40404;
            color:white;
            border:2px solid #B40404;
        }

        .notSignin-mes{
            background:lightgray;
            font-weight:bold;
            padding:5px;
            cursor: pointer;
        }

    </style>
    <link href="responsive.css" rel="stylesheet">

</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner" id="product-detail">
        <div class="detail-baseinfo">
            <p class="productName"><?php echo $p_data['title']?></p>

            <span class="productCategory">
                <?php echo $category?>
            </span>
        </div>
        

        <div class="detail-pictures">
            
            
            <div class="detail-product">
                <i class="fas fa-angle-left fa-4x slide-btn normal toLess"></i>
                    
                        <div class="imgWrap wrapNum">
                            <?php for($i=1; $i<=9; $i++ ):?>
                                <?php if(!empty($p_data['pic'.$i])):?>
                                    <img class="slidesImg <?php echo "pic".$i?>"
                                    src="<?php echo $p_data['pic'.$i]?>"
                                    style="<?php if($i>1) echo 'display:none';?>"
                                    >
                                <?php endif?>
                            <?php endfor;?>
                        </div>

                    
                <i class="fas fa-angle-right fa-4x slide-btn normal toMore"></i>
            </div>
            
            

            <div class="now-imgNum">
                <?php for($i=1; $i<=9; $i++ ):?>

                    <?php if(!empty($p_data['pic'.$i])):?>
                        <i class="<?php echo 'fas fa-circle dots dot'.$i?>"></i>
                    <?php endif?>

                <?php endfor;?>
            </div>

            <div class="mini-imgNum">
                <i class="fas fa-angle-left fa-4x slide-btn mini toLess"></i>
                    <?php for($i=1; $i<=9; $i++ ):?>

                        <?php if(!empty($p_data['pic'.$i])):?>
                            <i class="<?php echo 'fas fa-circle dots dot'.$i?>"></i>
                        <?php endif?>

                    <?php endfor;?>
                <i class="fas fa-angle-right fa-4x slide-btn mini toMore"></i>
            </div>


        </div>

        <div class="detail-info">
            <p class="detail-caption">商品説明</p>
            <p　class="detailText">
                <?php echo $p_data['detail']?>
            </p>

            <p class="price"><?php echo "￥".number_format($p_data['price'])?></p>
            <div class="actions">
                
                <div class="buttons">
                    <?php if(!isset($_SESSION['user_id'])){//ログインしているか？?>
                        <p class="notSignin-mes has-link" data-url="signin.php">購入、DMにはログインが必要です</p>
                    <?php }else{ if($p_data['userid'] != $_SESSION['user_id']){ //ログインしていて自分の商品か？?>
                            <button type="button" class="buy-btn">購入する</button>
                            <button type="button" class="dm-btn has-link" data-url="<?php echo "directMail.php?to=".$p_data['userid']?>">作者にDM</button>
                            <button type="button" class="favorit-btn <?php if(isFavorit($p_data['productid']))echo "checked"?>" data-productid="<?php echo $p_data['productid']?>">お気に入り</button>
                    <?php }else{ //自分の商品ならば編集するボタンにする。？?>
                        <button type="button" class="buy-btn has-link" data-url="<?php echo "productEdit.php?p_id=".$p_data['productid']?>">編集する</button>
                    <?php }?>
                    <?php }?>
                </div>
            </div>

            <div class="under-coteiner">
                <div class="upper-side">
                    <div class="user-icon-unit has-link" data-url="<?php echo "profile_detail.php?u_id=".$p_data['userid']?>">
                        <img src="<?php echo $p_data['icon_img']?>">
                    </div>
                    <p class = "card userName"><?php echo $p_data['username']?></p>
                </div>
                
                <div class="under-side">
                    <p class = "introduction"><?php echo $p_data['introduction']?></p>
                </div>

            </div>
        </div>
    </div>


        
        

    <?php require_once('footer.php')?>
    <script type="text/javascript" src="imgSlide.js"></script>
    <script>
        $('.has-link').click(function(){
            location.href=$(this).attr('data-url')
        });


        var $favo,$p_id;
        $favo = $('.favorit-btn')|| null ;
        $p_id = $('.favorit-btn').attr('data-productid') || null ;

        if($p_id !== undefined && $p_id !== null){
            $favo.on('click',function(){
                $.ajax({
                    type:"POST",
                    url:"ajaxfavo.php",
                    data:{productid:$p_id}
                }).done(function(data){
                    console.log('AjaxSuccess');
                    $($favo).toggleClass('checked')
                }).fail(function(msg){
                    console.log('AjaxFailed');
                });
            });
        }

    </script>
</body>
</html>