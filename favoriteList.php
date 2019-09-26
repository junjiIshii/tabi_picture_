<?php 
    require_once('functions.php');
    $p_data = getFavoList($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="style.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <title>商品一覧</title>
        <!--CSSを整える-->
        <style>
            
        .main-conteiner {
            justify-content: center;
        }

        .prodocuts-conteiner{
            display: flex;
            flex-wrap:wrap;
            width:70%;
        }
        .product-unit{
            /*border: 1px red solid;目印用後で消す*/
            width:280px;
            height: 600px;
            font-size: 20px;
            margin: 0px 10px;
            margin-bottom: 20px;
            background: #f1f1f1;
            box-shadow:2px 8px   rgba(61, 60, 60, 0.2);
            position: relative;
            vertical-align:top;
        }

        .favorit-btn{
            position:absolute;
            right:2px;
            top:2px;
            color:lightgray;
        }

        .checked{
            color:pink;
        }

        .product-unit:hover{
            background: #d9d9d9;
        }

        .product{
            text-align: center;
            padding: 5px;
            margin: 2px;
        }

        .product-img img{
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .user-icon_producunit{
            width:80px;
            height: 80px;
            position: absolute;
            top:270px;
            left:5px;
            border-radius: 50%;
        }

        .user-icon_producunit img{
            object-fit: cover;
            width: 100%;
            height:100%;
            border-radius: 50%;
            border: white 5px solid;
        }


        .author{
            width:200px;
            text-align: left;
            margin-left: 90px;
        }

        .pictureinfo{
            text-align: left;
            font-size: 15px;
        }

        .to-detail{
            position: absolute;
            display: block;
            width:50%;
            text-align: center;
            padding:5px;
            bottom:0;
            left:25%;

        }

        .guide{
            width: 100%;
            height: 50px;
            margin-bottom: 20px;
            font-size:15px;
            padding:0px 10px;
            display:flex;
            align-items:center;
        }
        </style>
    </head>
    <body>
        <?php require_once('header.php')?>

        <div class="main-conteiner">
            <div class="prodocuts-conteiner">
            <?php if(count($p_data) !=0){ //お気に入り数が0の時は何も表示しない?>
                <span class="guide"><?php echo count($p_data)."件のお気に入り登録があります。"; ?></span>

                <?php for($i=0; $i< count($p_data); $i++) {;?>
                    <div class="product-unit">
                        <div class="user-icon_producunit">
                            <a href="<?php echo "profile_detail.php?u_id=".$p_data[$i]['userid']?>"><img src="<?php echo $p_data[$i]['icon_img']?>"></a>
                        </div>

                        
                            <div class="product-img">
                                <i class="fas fa-heart favorit-btn fa-2x <?php if(isFavorit($p_data[$i]['productid']))echo "checked"?>"
                                data-productid ="<?php echo $p_data[$i]['productid']?>"></i>
                                <img src="<?php echo $p_data[$i]['pic1']?>">
                            </div>
                        <div class="link-cover" data-url="<?php echo 'product_detail.php?p_id='.$p_data[$i]['productid']?>">
                            <p class = "product author"><?php echo $p_data[$i]['username']?></p>
                            <p class = "product title"><?php echo $p_data[$i]['title']?></p>

                            <div class="product pictureinfo">
                                <p><?php $detail = $p_data[$i]['detail'] ;
                                    //商品詳細文は150文字までだす。
                                    if(strlen($detail)>150){
                                        echo mb_substr($detail,0,150)."...";
                                    }else{
                                        echo $detail;
                                    }
                                    ?></p>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <?php }else{?>
                    
                    <span class="guide">まだお気に入りには何も登録されていません。<a href="mypage.php">戻る</a></span>
                    
                <?php }?>
            </div>
            <?php require_once('mypageBar.php') ?>
        </div>
            <?php require_once('footer.php')?>
            <script>

                $('.link-cover').click(function(){
                    location.href=$(this).attr('data-url')
                });

                $p_id = $(this).attr('data-productid') || null ;

                $('.favorit-btn').on('click',function(){
                    $(this).toggleClass("checked");


                    $p_id = $(this).attr('data-productid') || null ;

                    if($p_id !== undefined && $p_id !== null){
                    
                        $.ajax({
                            type:"POST",
                            url:"ajaxfavo.php",
                            data:{productid:$p_id}
                        }).done(function(data){
                            console.log('AjaxSuccess');
                        }).fail(function(msg){
                            console.log('AjaxFailed');
                        });        
                    }
                });
            </script>
        

    </body>
</html>