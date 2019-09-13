<?php
    require_once('functions.php');


    $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
    if((int)$currentPg === 0){header("location:?pg=1");}

    $allNum = getNumData('productid','products');
    $maxShowNum = 12;
    $offset =($currentPg-1)*$maxShowNum;
    $p_data = makeProducList($maxShowNum,$offset);

    $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数

    //最大ページ以上のGETパラメータを不正に入力した場合、１ページ目へ送る。
    if($currentPg > $lastPg_count){
        header("location:?pg=1");
    }

    debug($lastPg_count);
    $firstPg = 1;
    
    //基本は現在のページから±2ページをだす。
    $minPageNum=$currentPg-2;
    $maxPageNum=$currentPg+2;

    if($lastPg_count <5){
        //ページ表示数が５より少ない時は５個全てだす。
        $maxPageNum = $lastPg_count;
        $minPageNum = $firstPg;
    }elseif($minPageNum<= $firstPg){
        //ページナンバーが1を下回ってしまう場合。
        $minPageNum = 1;
        $maxPageNum = $firstPg+4;
    }elseif($maxPageNum>=$lastPg_count && $lastPg_count >=5){
        //ページナンバーが最大を上回ってしまう場合。
        $maxPageNum=$lastPg_count;
        $minPageNum = $lastPg_count-4;
    }
    
    $startNum = ($currentPg -1)*$maxShowNum +1;

    //最後のページで表示できるカード数の調整。余り＝表示する数。（0を除く）
    if($currentPg==$lastPg_count && $allNum % $maxShowNum!=0){
        $maxShowNum = $allNum % $maxShowNum;
    }

    $endNum = $startNum + $maxShowNum -1;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <title>商品一覧</title>
    <style>

.guide{
    border: 1px solid black;
    width: 100%;
    height: 50px;
    margin-bottom: 20px;
    font-size:15px;
    padding:0px 10px;
}

.show-result{
    display: inline-block;
    line-height:50px;
}

.show-nowNum{
    display: inline-block;
    float:right;
    line-height:50px;
}

.prodocuts-conteiner{
    margin: 30px 0px;
    width:80%;
    /*border: 2px black solid;目印用後で消す*/
    float: right;
}

.show-nowNum{
    text-align: left;
}

.product-unit{
    /*border: 1px red solid;目印用後で消す*/
    width:300px;
    height: 600px;
    display: inline-block;
    font-size: 20px;
    margin: 0px 10px;
    margin-bottom: 20px;
    background: #f1f1f1;
    box-shadow:2px 8px   rgba(61, 60, 60, 0.2);
    position: relative;
    vertical-align:top;
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
    height: 300px;
    object-fit: cover;
}

.user-icon_producunit img{
    object-fit: cover;
    width: 80px;
    border-radius: 50%;
    position: absolute;
    top:270px;
    left:5px;
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

.paging {
    width: 100%;
    height:40px;
    text-align:center;
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

        <div class="search-conteiner">
            <div class="serchsection byName">
                <p>ユーザー名で探す</p>
                <input type="text" placeholder="ユーザー名" name="searchByName"
                value="<?php if(!empty ($_POST['searchByName'])) echo $_POST['searchByName'];?>">
            </div>

            <div class="serchsection bycategory">
                <p>カテゴリーで探す<span style="font-size:10px;"><br>(スペースでAND検索)</span></p>
                <input type="text" placeholder="カテゴリ名" name="searchBycategory"
                value="<?php if(!empty ($_POST['searchBycategory'])) echo $_POST['searchBycategory'];?>">
            </div>

            <div class="serchsection byintro">
                <p>キーワード検索</p>
                <input type="text" placeholder="キーワード" name="searchByintro"
                value="<?php if(!empty ($_POST['searchByintro'])) echo $_POST['searchByintro'];?>">
            </div>
        </div>

        <div class="prodocuts-conteiner">

            <div class="guide">
                <span class="show-result"><?php echo"{$maxShowNum}件の商品を表示します。"?></span>
                <span class="show-nowNum"><?php echo "$startNum-$endNum 件/ $allNum 件"; ?></span>
            </div>

            <?php for($i=0; $i<$maxShowNum; $i++) {;?>
            <div class="product-unit">

                <div class="user-icon_producunit">
                    <a href="<?php echo "profile_detail.php?u_id=".$p_data[$i]['userid']?>"><img src="<?php echo $p_data[$i]['icon_img']?>"></a>
                </div>

                <div class="link-cover" data-url="<?php echo 'product_detail.php?p_id='.$p_data[$i]['productid']?>">
                    <div class="product-img">
                        <img src="<?php echo $p_data[$i]['pic1']?>">
                    </div>
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

            <div class="paging">
                <ul class="paging-list">
                    <?php if($currentPg != 1):?>
                    <li class="pageNum" data-url="?pg=1">＜</li>
                    <?php endif?>

                    <?php for($p=$minPageNum;$p<=$maxPageNum;$p++){?>
                        <li style="<?php if($currentPg==$p)echo'background:#088A4B;'?>"
                        data-url='?pg=<?php echo $p?>'
                        class="pageNum">
                        <?php echo $p?></li>
                    <?php }?>

                    <?php if($currentPg != $lastPg_count):?>
                    <li class="pageNum" data-url="?pg=<?php echo $lastPg_count?>">＞</li>
                    <?php endif;?>
                </ul>
            </div>  

        </div>
        <div class="cd"></div>
    </div>
    <?php require_once('footer.php')?>
    <script>

    $('.link-cover').click(function(){
        location.href=$(this).attr('data-url')
    });

    $('.pageNum').click(function(){
        location.href=$(this).attr('data-url')
    });


    </script>

</body>
</html>