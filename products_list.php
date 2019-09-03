<?php
    require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <title>商品一覧</title>
    <style>
            
.prodocuts-conteiner{
    margin: 30px 10px;
    width:80%;
    /*border: 2px black solid;目印用後で消す*/
    float: right;
    overflow: hidden;
    font-size: 0px;
}

.product-unit{
    /*border: 1px red solid;目印用後で消す*/
    width:300px;
    height: 500px;
    float:left;
    font-size: 20px;
    margin: 0px 10px;
    margin-bottom: 20px;
    background: #f1f1f1;
    box-shadow:2px 8px   rgba(61, 60, 60, 0.2);
    position: relative;
}

.product{
    text-align: center;
    padding: 5px;
    margin: 2px;
}

.product-img img{
    width: 100%;
    max-height: 300px;
    object-fit: cover;
}

.user-icon_producunit img{
    object-fit: cover;
    width: 80px;
    border-radius: 50%;
    position: absolute;
    top:40%;
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
.cd{
    clear: both;
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
            <?php for($i=1; $i<=1; $i++) echo
                '<div class="product-unit">
                <div class="user-icon_producunit">
                    <a href="#"><img src="user_icon/usericon_sample01.jpg"></a>
                </div>
                <div class="product-img">
                    <img src="pictures/sample01.jpg">
                </div>
                <p class = "product author">作者名</p>
                <p class = "product title">北海道一周編</p>
                <div class="product pictureinfo">
                    <p>自転車で北海道2300kmを一周した時の写真集です。
                        苫小牧から左回りに回っていきました。
                        特に綺麗な景色だったところを厳選して特集しました。</p>
                </div>

                    <a class="to-detail" href="#">商品詳細</a>
            </div>' ;?>

        </div>
        <div class="cd"></div>
    </div>
    <?php require_once('footer.php')?>

</body>
</html>