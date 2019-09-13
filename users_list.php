<?php
    require_once('functions.php');
    $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
    if((int)$currentPg === 0){header("location:?pg=1");}

    $allNum = getNumData('userid','users');
    $maxShowNum = 12;
    $offset =($currentPg-1)*$maxShowNum;
    $u_data = getSelectData($maxShowNum ,$offset,'users','userid,username,introduction,header_img,icon_img');

    //ページングのアルゴリズム
    $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数

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
    


    //最後のページで表示できるカード数の調整。余り＝表示する数。（0を除く）
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
    .usercards-conteiner{
    margin: 30px 20px;
    width:80%;
    /*border: 2px black solid;目印用後で消す*/
    float: right;
    overflow: hidden;
}

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
.usercard-unit{
    /*border: 1px red solid;目印用後で消す*/
    width:300px;
    height: 500px;
    display: inline-block;
    vertical-align:top;
    font-size: 20px;
    margin: 0px 10px;
    margin-bottom: 20px;
    background: #f1f1f1;
    color:black;
    box-shadow:2px 8px   rgba(61, 60, 60, 0.2);
    position: relative;
}


.usercard-unit:hover{
    background: #d9d9d9;
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
    position: absolute;
    top:205px;
    left:80px;
    font-size:18px;
    text-align: center;
    width:150px;
    margin: 0 auto;
    border-radius: 20px;
    cursor: pointer;
}

.unfollow{
    color: #0065a8;
    border:2px solid #0065a8;
    background:#f1f1f1;
}

.usercard-unit:hover .unfollow{
    background: #d9d9d9;
}


.followed{
    border:2px solid #0065a8;
    background: #0065a8;
    color:white;
}

.userinfo{
    margin-top: 40px;
    text-align: left;
    font-size: 15px;
    padding: 8px;
    height: 200px;
}

.to-detail{
    display:block;
    text-align: center;
}

.paging {
    width: 100%;
    height:40px;
    text-align:center;
}

.pageNum{
    height:50px;
    width:40px;
    line-height:40px;
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

        <div class="usercards-conteiner">

            <div class="guide">
                <span class="show-result"><?php echo"{$maxShowNum}人のユーザーを表示します。"?></span>
                <span class="show-nowNum"><?php echo "$startNum-$endNum 件/ $allNum 件"; ?></span>
            </div>

            <?php for($i=0; $i<$maxShowNum; $i++) {?>
            <div class="usercard-unit" >

                <div class="link-cover" data-url="<?php echo "profile_detail.php?u_id=".$u_data[$i]['userid']?>">
                    <div class="user-icon_usercardunit">
                        <img src="<?php echo $u_data[$i]['icon_img']?>">
                    </div>
                    <div class="card-headerimg">
                        <img src="<?php echo $u_data[$i]['header_img']?>">
                    </div>
                    <p class = "card userName"><?php echo $u_data[$i]['username']?></p>
                    <div class="card userinfo">
                        <p><?php echo $u_data[$i]['introduction']?></p>
                    </div>
                </div>

                <button class="card follow-btn unfollow" >
                        フォロー
                </button>

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