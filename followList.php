<?php
    require("functions.php");

    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「フォロー管理画面」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();

    //↓がフォローしている人のデータを取得する。
    $u_id = $_SESSION['user_id'];
    $followData =  getFollowData($u_id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>フォロー管理画面</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .main-conteiner{
            flex-wrap:nowrap;
            justify-content: center;
        }

        .list-conteiner{
            display: flex;
            flex-direction: column;
            width: 500px;
            margin:10px 10px;
            
        }

        .switchList{
            text-align: center;
        }

        .switchList button{
            width:150px;
            font-size:15px;
            padding:5px;
            border-radius: 10px;
        }

        .follows{
            background:#0065a8;
            color:white;
        }

        .followers{
            background:#f5f5f5;
            color:gray;
        }

        .userListCard{
            display: flex;
            flex-wrap:nowrap;
            align-items: center;
            padding:5px;
            border-bottom:1px gray solid;

        }

        .user-icon{
            width:70px;
            height: 70px;
            border: white 5px solid;
            border-radius: 50%;
        }

        .user-icon img{
            object-fit: cover;
            width: 100%;
            height:100%;
            border-radius: 50%;
        }

        .userName{
            margin-left:5px;
        }

        .follow-btn{
            margin-left: auto;
            font-size:18px;
            text-align: center;
            width:150px;
            border-radius: 20px;
            cursor: pointer;

            color: #0065a8;
            border:2px solid #0065a8;
            background:#f1f1f1;
        }

        .followed{
            border:2px solid #0065a8;
            background: #0065a8;
            color:white;
        }
    </style>
</head>
<body>
    <?php require_once('header.php')?>

        <div class="main-conteiner">
            <div class="list-conteiner">
            <div class="switchList">
                <button type="button" class="follows">フォロー</button>
                <button type="button" class="followers" data-url="followerList.php">フォロワー</button>
            </div>
                <?php foreach($followData as $key => $val){?>
                    <div class="userListCard">

                        <div class="user-icon">
                                <img src="<?php echo $val['icon_img']?>">
                        </div>

                        <p class = "userName"><?php echo $val['username']?></p>

                        <button class="follow-btn <?php if(isFollow($val['follow'])) echo "followed"?>" data-userid="<?php echo $val['follow']?>">
                                    フォロー
                        </button>

                    </div>
                <?php };?>
            </div>
            <?php require_once('mypageBar.php')?>
        </div>


    <?php require_once('footer.php')?>
    <script>
        $('.followers').on('click',function(){
            location.href=$(this).attr('data-url');
        })

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