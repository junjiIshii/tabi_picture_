<?php
    require_once('functions.php');
    $ctg = getCategory();


    if(empty($_GET['st'])){
        //st(state)が空＝検索をかけていない通常の表示の時のページング設定

        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}
    
        $allNum = getNumData('productid','products');
    
        $maxShowNum = 8;
        $offset =($currentPg-1)*$maxShowNum;
        $p_data = makeProducList($maxShowNum,$offset);
        $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数
    }else{
        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}

        $rst =  showSearchProd($currentPg,1);
        $allNum= $rst['total'];
        $maxShowNum = $_GET['showNum'];
        $lastPg_count = ceil($allNum/$maxShowNum);

        $p_data = $rst['data'];
        debug('検索データ内容：'.print_r($p_data,true));
    }



    //最大ページ以上のGETパラメータを不正に入力した場合、１ページ目へ送る。
    if($currentPg > $lastPg_count && $allNum != 0){
        header("location:?pg=1");
    }

    
    if($allNum != 0){
        $pgData =  paging($allNum,$currentPg,$lastPg_count,$maxShowNum);
    }
    

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>商品一覧</title>
    <style>

.guide{
    border: 1px solid black;
    width: 80%;
    height: 50px;
    margin-bottom: 20px;
    font-size:15px;
    padding:0px 10px;
    display:flex;
    align-items:center;
    align-self: flex-start;
}


.show-nowNum{
    margin-left:auto;
}

.prodocuts-conteiner{
    margin: 0px 10px 10px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    width:80%;

    /*border: 2px black solid;目印用後で消す*/
}

.show-nowNum{
    text-align: left;
}

.unit-wrapper{
    display:flex;
    flex-wrap: wrap;
    width: 100%;
}

.product-unit{
    /*border: 1px red solid;目印用後で消す*/
    width:220px;
    height: 440px;
    font-size: 20px;
    margin: 0px 10px;
    margin-bottom: 20px;
    background: #f1f1f1;
    box-shadow:2px 8px   rgba(61, 60, 60, 0.2);
    position: relative;
    display: flex;
    flex-direction: column;
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
    padding: 5px;
    margin: 2px;
}

.product-img{
    width: 100%;
    height: 220px;
}

.product-img img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-icon_producunit{
    width:60px;
    height: 60px;
    position: absolute;
    top:170px;
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

.title{
    font-weight:bold;
    border-bottom:dotted 2px gray; 
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

.link-cover{
    position: absolute;
    width: 100%;
    height: 90%;
    align-self: end;
    bottom: 0;
}
/*ここにあったページングのCSSは共通のCSSファイルに格上げした*/

    </style>

<link href="responsive.css" rel="stylesheet">
</head>
<body>
    <?php require_once('header.php')?>

    <div class="main-conteiner" id="productList-main-conteiner">

        <div class="search-conteiner">
            <form class="search-form" method="get">
                
                <div class="serchsection byName">
                    <p>商品タイトルで探す</p>
                    <input type="text" placeholder="商品名" name="byName"
                    value="<?php if(!empty ($_GET['byName'])) echo $_GET['byName'];?>">
                </div>

                <div class="selectors">
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
                        <option value="8" <?php selectedEcho('showNum',8)?>>指定しない</option>
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
                </div>


                <div class="serchsection submit-btn">
                <input type="hidden" name="st" value="searchpr">
                <input class="searchStart" type="submit" value="検索">
                </div>

                
            </form>
        </div>

        <div class="prodocuts-conteiner" >
            <?php if($allNum !=0){ //検索結果や商品が０の時は出力しない。?>
                <div class="guide">
                    <span class="show-result"><?php echo"{$pgData['maxShow']}件の商品を表示します。"?></span>
                    <span class="show-nowNum"><?php echo "{$pgData['start']}-{$pgData['end']} 件/ {$allNum} 件"; ?></span>
                </div>

                <div class="unit-wrapper">
                    <?php for($i=0; $i<$pgData['maxShow']; $i++) {;?>
                    <div class="product-unit">
                        
                            <div class="product-img">
                                <i class="fas fa-heart favorit-btn fa-2x <?php if(isFavorit($p_data[$i]['productid']))echo "checked"?>"
                                data-productid ="<?php echo $p_data[$i]['productid']?>"></i>
                                <img src="<?php echo $p_data[$i]['pic1']?>">
                            </div>

                            <div class="user-icon_producunit">
                                <a href="<?php echo "profile_detail.php?u_id=".$p_data[$i]['userid']?>"><img src="<?php echo $p_data[$i]['icon_img']?>"></a>
                            </div>

                            <div class="link-cover has-link" data-url="<?php echo 'product_detail.php?p_id='.$p_data[$i]['productid']?>"></div>
                            
                            <div class="authorAndtitle">
                                <p class = "product author"><?php 
                                echo hiddenOverStr($p_data[$i]['username'],9)
                                ?></p>

                                <p class = "product title"><?php echo hiddenOverStr($p_data[$i]['title'],9)?></p>
                            </div>
                                

                                <div class="product pictureinfo">
                                    <p><?php $detail = $p_data[$i]['detail'] ;
                                        //商品詳細文は80文字までだす。
                                        echo hiddenOverStr($detail,60);?></p>
                                </div>
                            
                            
                        

                    </div>
                    <?php }?>
                </div>

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
                    <span class="no-result">商品が見つかりませんでした。</span>
                </div>
            <?php }?>

        </div>
        <div class="cd"></div>
    </div>
    <?php require_once('footer.php')?>
    <script>

    $('.has-link').click(function(){
        location.href=$(this).attr('data-url')
    });


    $('.favorit-btn').on('click',function(){
        $p_id = $(this).attr('data-productid') || null ;
        $(this).toggleClass('checked');
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
    })
s
    </script>
    

</body>
</html>