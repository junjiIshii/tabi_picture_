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
        .usercards-conteiner{
    margin: 30px 20px;
    width:80%;
    /*border: 2px black solid;目印用後で消す*/
    float: right;
    overflow: hidden;
    font-size: 0px;
}

.usercard-unit{
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

.card-headerimg img{
    width: 100%;
    max-height: 150px;
    object-fit: cover;
}

.user-icon_usercardunit img{
    object-fit: cover;
    width: 90px;
    border-radius: 50%;
    position: absolute;
    top:20%;
    left:5px;
    border: white 5px solid;
}


.card{
    padding: 5px;
}

.userName{
    margin-left:95px;
    font-weight: bold;
    font-size: 25px;
}

.follow-btn{
    text-align: center;
    width:150px;
    margin: 0 auto;
    border-radius: 20px;
    cursor: pointer
}

.unfollow{
    color: #0065a8;
    border:2px solid #0065a8;
}

.followed{
    border:2px solid #0065a8;
    background: #0065a8;
    color:white;
}

.userinfo{
    margin-top: 10px;
    text-align: left;
    font-size: 15px;
    padding: 8px;
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

        <div class="usercards-conteiner">
            <?php for($i=1; $i<=9; $i++) echo
            '<div class="usercard-unit">
                
                <div class="user-icon_usercardunit">
                    <a href="#"><img src="user_icon/usericon_sample01.jpg"></a>
                </div>
                <div class="card-headerimg">
                    <img src="pictures/sample01.jpg">
                </div>
                <p class = "card userName">作者名</p>
                <div class="card follow-btn unfollow" >
                    フォロー
                </div>
                <div class="card userinfo">
                    <p>自転車旅が趣味です。日本各地を自転車で回って、その旅の軌跡の写真を上げています。
                    今まで行ったことのある場所は、北海道一周、台湾一周、東海道、渋峠などです。</p>
                </div>

                    <a class="to-detail" href="#">ユーザー詳細</a>
            </div>' ;?>

        </div>
        <div class="cd"></div>
    </div>

    <?php require_once('footer.php')?>
</body>
</html>