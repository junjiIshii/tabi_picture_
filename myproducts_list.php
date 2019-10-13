<?php
    require_once('functions.php');
    loginAuth();
    debug('セッション情報：'.print_r($_SESSION,true));
    $ctg = getCategory();

    if(empty($_GET['st'])){
        //st(state)が空＝検索をかけていない通常の表示の時のページング設定

        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}
    
        $allNum = getUserProducNum($_SESSION['user_id']);
    
        $maxShowNum = 12;
        $offset =($currentPg-1)*$maxShowNum;
        $p_data = makeUserProducList($maxShowNum,$offset,$_SESSION['user_id']);
        $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数
    }else{
        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}

        $rst =  showSearchProd($currentPg,2);
        $allNum= $rst['total'];
        $maxShowNum = $_GET['showNum'];
        $lastPg_count = ceil($allNum/$maxShowNum);

        $p_data = $rst['data'];
        debug('検索データ内容：'.print_r($p_data,true));
    }

    if($currentPg > $lastPg_count && $allNum != 0){
        header("location:?pg=1");
    }

    
    if($allNum != 0){
        $pgData =  paging($allNum,$currentPg,$lastPg_count,$maxShowNum);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>商品編集一覧</title>
        <link href="style.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <style>
            .main-conteiner{
                justify-content: center;
            }
            
            .prodocuts-conteiner{
                width:50%;
                display:flex;
                flex-direction:column;
                margin:0px 10px 10px 10px;
            }

            .product-unit{
                display:flex;
                flex-wrap:nowrap;
                margin-bottom:10px;
                border-bottom:1px lightgray solid;
            }
            
            .product-info{
                width:100%;
                display:flex;
                flex-direction:column;
                padding:5px;
                
            }

            .product-img{
                min-width:110px;
                width: 110px;
                height: 110px;
                padding:2px;
            }

            .product-img img{
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .product-detail{
                font-size:14px;
                height: 45px;
                max-height:45px;
            }
            

            .actions{
                display:flex;
                flex-direction:column;
                align-self:center;
                margin-left:auto;
            }

            .actionbutton{
                width: 100px;
                height:25px;
                padding: 3px;
                font-size:13px;
                font-weight:bold;
                cursor:pointer;
            }

            .red{
                background:rgba(230, 7, 40, 0.6);;
                color:darkred;
            }

            .green{
                background: rgba(7, 230, 163, 0.6);
                color:cadetblue;
            }

            .locked{
                background:skyblue;
                color:dodgerblue;
                margin:5px 0px;
            }

            .open{
                border:2px solid skyblue;
                color:dodgerblue;
                margin:5px 0px;
            }
        </style>
        <link href="responsive.css" rel="stylesheet">
    </head>
    <body>
        <?php require_once('header.php')?>

        <div class="main-conteiner" id="myproducts_main-conteiner">
            <div class="search-conteiner">
                <form class="search-form" method="get">
                    
                    <div class="serchsection byName">
                        <p>商品タイトルで探す</p>
                        <input type="text" placeholder="商品名" name="byName"
                        value="<?php if(!empty ($_GET['byName'])) echo $_GET['byName'];?>">
                    </div>

                    <div class="serchsection bycategory">
                        <p>カテゴリーで探す</p>
                        <select name="c_id">
                            
                                <option value="0" <?php selectedEcho('c_id',"0")?>>指定しない</option>
                                <?php for($i=0;$i<count($ctg);$i++):?>
                                    <option value="<?php echo $ctg[$i]['categoryid']?>"
                                    <?php selectedEcho('c_id',$ctg[$i]['categoryid'])?>><?php echo $ctg[$i]['category_name']?></option>
                                <?php endfor;?>

                        </select>
                    </div>

                    <div class="serchsection showNumber">
                        <p>表示数</p>
                        <select name="showNum" class="showNum">
                            <?php for($i=1;$i<=4;$i++):?>
                                <option value="<?php echo $i*12?>" <?php selectedEcho('showNum',$i*12)?>><?php echo $i*12?></option>
                            <?php endfor;?>
                        </select>
                    </div>

                    <div class="serchsection showHow">
                        <p>表示形式</p>
                        <select name="sort" class="showType">
                            <option value="1" <?php selectedEcho('sort',"1")?>>新しい順</option>
                            <option value="2" <?php selectedEcho('sort',"2")?>>古い順</option>
                        </select>
                    </div>

                    <div class="serchsection submit-btn">
                    <input type="hidden" name="st" value="searchpr">
                    <input class="searchStart" type="submit" value="検索">
                    </div>

                    
                </form>
            </div>

            <div class="prodocuts-conteiner" id="myproduct-conteiner">
                <?php if($allNum !=0){ //検索結果や商品が０の時は出力しない。?>

                    <?php for($i=0; $i<$pgData['maxShow']; $i++) {;?>
                    <div class="product-unit" id="myproduct-unit">
                        <div class="product-img">
                            <img src="<?php echo $p_data[$i]['pic1']?>">
                        </div>

                        <div class="product-info">
                            <h3 class = "product-title"><?php echo $p_data[$i]['title']?></h3>
                            <p class="product-detail"><?php $detail =  $p_data[$i]['detail'] ;
                                //商品詳細文は150文字までだす。
                                if(strlen($detail)>50){
                                    echo mb_substr($detail,0,50)."...";
                                }else{
                                    echo $detail;
                                }
                                ?></p>

                        </div>

                        <div class="actions">
                                <button class="actionbutton green has-link" type="button" data-url="<?php echo "productEdit.php?p_id=".$p_data[$i]['productid']?>">編集する</button>

                                <?php if($p_data[$i]['open_flg']==1){?>
                                    <button class="actionbutton changeState open" type="button" data-productid="<?php echo $p_data[$i]['productid']?>">公開中</button>
                                <?php }else{?> 
                                    <button class= "actionbutton changeState locked " type="button" data-productid="<?php echo $p_data[$i]['productid']?>">非公開中</button>
                                <?php }?> 

                                <button class="actionbutton red doDlete" type="button" data-delproductid="<?php echo $p_data[$i]['productid']?>">削除する</button>
                        </div>
                    </div>
                    <?php }?>

                    <div class="paging">
                        <ul class="paging-list">
                            <?php if($currentPg != 1):?>
                            <li class="pageNum has-link" data-url="<?php echo "?pg=1".withGetPram()?>">＜</li>
                            <?php endif?>

                            <?php for($p=$pgData['minPg'];$p<=$pgData['maxPg'];$p++){?>
                                <li style="<?php if($currentPg==$p)echo'background:#088A4B;'?>"
                                data-url='<?php echo "?pg={$p}".withGetPram()?>'
                                class="pageNum has-link">
                                <?php echo $p?></li>
                            <?php }?>

                            <?php if($currentPg != $lastPg_count):?>
                                <li class="pageNum has-link" data-url="<?php echo "?pg={$lastPg_count}".withGetPram();?>">＞</li>
                            <?php endif;?>
                        </ul>
                    </div> 

                <?php }else{;?>
                    <div class="guide">
                        <span class="no-result">商品はまだ登録されていません。</span>
                    </div>
                <?php }?>
            </div>

            <?php require_once('mypageBar.php')?>
        </div>

        <?php require_once('footer.php')?>
        <script>
            $('.has-link').click(function(){
            location.href=$(this).attr('data-url')
            });

            $('.doDlete').on('click',function(){
                $p_id = $(this).attr('data-delproductid') || null ;
                $unit = $(this).parents('.product-unit');
                $.ajax({
                        type:"POST",
                        url:"ajaxfavo.php",
                        data:{delproductid:$p_id}
                    }).done(function(data){
                        console.log('AjaxSuccess');
                        $unit.fadeOut();
                    }).fail(function(msg){
                        console.log('AjaxFailed');
                    });  
            })

            $('.changeState').on('click',function(){
                $p_id = $(this).attr('data-productid') || null ;

                if($(this).hasClass('locked')){
                    $(this).removeClass('locked');
                    $(this).addClass('open').text('公開中');

                }else if($(this).hasClass('open')){
                    $(this).removeClass('open');
                    $(this).addClass('locked').text('非公開中');
                }

                $.ajax({
                        type:"POST",
                        url:"ajaxfavo.php",
                        data:{stChangeproductid:$p_id}
                }).done(function(data){
                        console.log('AjaxSuccess');
                }).fail(function(msg){
                    console.log('AjaxFailed');
                });  
            })
        </script>
    </body>
</html>