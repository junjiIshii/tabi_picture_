<?php
    require_once('functions.php');

 
    if(empty($_GET['st'])){
        //stが空＝検索をかけていない通常の表示の時のページング設定

        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}
    
        $allNum = getNumData('userid','users');
        $maxShowNum = 12;
        $offset =($currentPg-1)*$maxShowNum;
        $u_data = getSelectData($maxShowNum ,$offset,'users','userid,username,introduction,header_img,icon_img');
        $lastPg_count = ceil($allNum/$maxShowNum); //　全ページ数　全体数÷表示数
        //debug('検索データ内容：'.print_r($u_data,true));
    }else{
        $currentPg = (!empty($_GET['pg']))? $_GET['pg']:1;
        if((int)$currentPg === 0){header("location:?pg=1");}

        //ここをユーザーようにする
        $rst =  showSearchUser($currentPg);
        $allNum= $rst['total'];
        $maxShowNum = $_GET['showNum'];
        $lastPg_count = ceil($allNum/$maxShowNum);

        $u_data = $rst['data'];
        debug('検索データ内容：'.print_r($u_data,true));
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
    display:flex;
    align-items:center;
}

.show-nowNum{
    margin-left:auto;
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

.user-icon_usercardunit{
    width:90px;
    height: 90px;
    position: absolute;
    top:20%;
    left:5px;
    border: white 5px solid;
    border-radius: 50%;
}

.user-icon_usercardunit img{
    object-fit: cover;
    width: 100%;
    height:100%;
    border-radius: 50%;
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

/*ここにあったページングのCSSは共通のCSSファイルに格上げした*/


</style>
</head>
<body>
    <?php require_once('header.php')?>

    <div class="main-conteiner">

        <div class="search-conteiner">
            <form class="search-form" method="get">
                <div class="serchsection byName">
                    <p>ユーザー名で探す</p>
                    <input type="text" placeholder="ユーザー名" name="byName"
                    value="<?php if(!empty ($_GET['byName'])) echo $_GET['byName'];?>">
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
                    <input type="hidden" name="st" value="searchu">
                    <input class="searchStart" type="submit" value="検索">
                </div>
            </form>
        </div>

        <!--ユーザー情報 -->
        <div class="usercards-conteiner">
            <?php if($allNum !=0){ //検索結果や商品が０の時は出力しない。?>
                <div class="guide">
                    <span class="show-result"><?php echo"{$pgData['maxShow']}人のユーザーを表示します。"?></span>
                    <span class="show-nowNum"><?php echo "{$pgData['start']}-{$pgData['end']} 件/ {$allNum} 件"; ?></span>
                </div>

                
                <?php for($i=0; $i<$pgData['maxShow']; $i++) {?>
                <div class="usercard-unit" >

                <!-- link-coverはユーザー詳細に映るためのURLデータを持っている。-->
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
                    <?php if($u_data[$i]['userid'] != $_SESSION['user_id']){?>
                        <button class="card follow-btn <?php if(isFollow($u_data[$i]['userid'])) echo "followed"?>" data-userid="<?php echo $u_data[$i]['userid']?>">
                                フォロー
                        </button>
                    <?php }?>

                </div>

                <?php }?>

                <div class="paging">
                    <ul class="paging-list">
                        <?php if($currentPg != 1):?>
                        <li class="pageNum" data-url="<?php echo "?pg=1".withGetPram()?>">＜</li>
                        <?php endif?>

                        <?php for($p=$pgData['minPg'];$p<=$pgData['maxPg'];$p++){?>
                            <li style="<?php if($currentPg==$p)echo'background:#088A4B;'?>"
                            data-url='<?php echo "?pg={$p}".withGetPram()?>'
                            class="pageNum">
                            <?php echo $p?></li>
                        <?php }?>

                        <?php if($currentPg != $lastPg_count):?>
                            <li class="pageNum" data-url="<?php echo "?pg={$lastPg_count}".withGetPram()?>">＞</li>
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

        $('.link-cover').click(function(){
            location.href=$(this).attr('data-url')
        });

        $('.pageNum').click(function(){
            location.href=$(this).attr('data-url')
        });

        //なぜがファイルを外に出すと効かなくなる↓
        $('.follow-btn').on('click',function(){
            $(this).toggleClass("followed");


                $u_id = $(this).attr('data-userid') || null ;

                if($u_id !== undefined && $u_id !== null){
                
                    $.ajax({
                        type:"POST",
                        url:"ajaxfavo.php",
                        data:{userid:$u_id}
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