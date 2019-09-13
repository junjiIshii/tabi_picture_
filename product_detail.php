<?php
    require_once('functions.php');
    loginAuth();

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
            width:65%;
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
            margin-right: 20px;
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
            margin:180px 20px 0px 20px ;
            cursor:pointer;
            color:#01DFA5;
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
            text-align: center;
            margin: 0 auto;
            margin: 15px 0px;

        }

        .detail-product{
            width: 400px;
            height:400px;
            display: inline-block;
            vertical-align: top;
            margin: 0px 5px;
            box-shadow:3px 4px   rgba(61, 60, 60, 0.2);
            /*position:relative;*/
        }

        /*.imgWrap{
            position:absolute;
            width:300px;
            height:400px;
            overflow: hidden;
            left:0;
        }*/

        .slidesImg{
            
            height:400px;
            width:400px;
            

        }


        .is_hide{
            width:0px;
        }

        .is_show{
            width:400px;
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

        .price{
            display:inline-block;
            font-size:26px;
            font-weight:bold;
        }

        .under-coteiner{
            width:60%;
            height: 180px;
            background:#f5f5f5;
            vertical-align: top;
            margin:20px 0px;
            padding:8px;
        }
        
        .user-icon-unit{
            width: 25%;
            height:100%;
            position: relative;
            vertical-align: middle;
            display:inline-block;
        }

        .userName{
            border-bottom:2px dotted gray;
            font-weight:bold;
            font-size:25px;
        }
        .user-icon-unit img{
            object-fit: cover;
            width: 100%;
            border-radius: 50%;
            position: absolute;
            border: white 5px solid;
        }

        .right-side{
            display:inline-block;
            vertical-align: top;
            margin-left:10px;
            width:70%;
        }

        .introduction{
            width:100%;
        }

        .actions{
            margin:15px 0px;
            padding:10px 5px;
            background:#f5f5f5;
        }

        .actions button{
            margin-left:10px;
            width:180px;
            vertical-align: top;
            padding:5px 10px;
            font-size: 18px;
            font-weight:bold;
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


    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">
        <div class="detail-baseinfo">
            <p class="productName"><?php echo $p_data['title']?></p>
        </div>
        <span class="productCategory">
            <?php echo $category?>
        </span>

        <div class="detail-pictures">
            
            <i class="fas fa-angle-left fa-4x slide-btn toLess"></i>
            <div class="detail-product">
                
                    <?php for($i=1; $i<=9; $i++ ):?>
                        <div class=<?php echo "imgWrap wrapNum".$i?>>
                        <?php if(!empty($p_data['pic'.$i])):?>
                            <img class="slidesImg <?php echo "pic".$i?>"
                            src="<?php echo $p_data['pic'.$i]?>"
                            style="<?php if($i>1) echo 'display:none';?>"
                            >
                        <?php endif?>
                        </div>

                    <?php endfor;?>
                
            </div>
            <i class="fas fa-angle-right fa-4x slide-btn toMore"></i>
            

            <div class="now-imgNum">
                <?php for($i=1; $i<=9; $i++ ):?>

                    <?php if(!empty($p_data['pic'.$i])):?>
                        <i class="<?php echo 'fas fa-circle dots dot'.$i?>"></i>
                    <?php endif?>

                <?php endfor;?>
            </div>


        </div>

        <div class="detail-info">
            <p class="detail-caption">商品説明</p>
            <p　class="detailText">
                <?php echo $p_data['detail']?>
            </p>

            <div class="actions">
                <p class="price"><?php echo "￥".number_format($p_data['price'])?></p>
                <button type="button" class="buy-btn">購入する</button>
                <button type="button" class="dm-btn">作者にDM</button>
                <button type="button" class="favorit-btn">お気に入り</button>
            </div>
        </div>


        
        <div class="under-coteiner">

            <div class="user-icon-unit" data-url="<?php echo "profile_detail.php?u_id=".$p_data['userid']?>">
                <img src="<?php echo $p_data['icon_img']?>">
            </div>

            <div class="right-side">
                <p class = "card userName"><?php echo $p_data['username']?></p>
                <p class = "introduction"><?php echo $p_data['introduction']?></p>
            </div>

        </div>
    </div>

    <?php require_once('footer.php')?>
    <script type="text/javascript" src="imgSlide.js"></script>
    <script>
        $('.user-icon-unit').click(function(){
        location.href=$(this).attr('data-url')
        });
    </script>
</body>
</html>