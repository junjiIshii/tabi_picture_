<?php
    require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
    <link href="style.css" rel="stylesheet">
    <style>
        .detail-baseinfo{
            width:100%;
            margin-top: 30px;
        }
        .detail-baseinfo p{ 
            display: inline-block;
            vertical-align: middle;
            border: 1px black solid;
            padding:0px 10px;
            margin-right: 20px;
        }

        .productName{
            width: 60%;
            font-size: 25px;
            font-weight:bold;
            height: 70px;
            line-height: 70px;
        }

        .favorit-btn{
            width: 10%;
            text-align: center;
            height: 40px;
            line-height: 40px;
        }

        .productCategory{
            height: 25px;
            display: flex;
            align-items: center;
            width: 60%;
        }

        .detail-pictures{
            text-align: center;
            margin: 0 auto;
            margin: 15px 0px;

        }

        .detail-product{
            width: 450px;
            display: inline-block;
            vertical-align: top;
            margin: 0px 5px;

        }

        .detail-product img{
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            box-shadow:3px 4px   rgba(61, 60, 60, 0.2);
        }

        .detail-info{
            width:90%;
            margin: 0 auto;
        }

        .detail-info p{
            height: 130px;
        }

        .under-coteiner{
            width:70%;
            margin: 0 auto;
        }

        .seller-info{
            position: relative;
            height: 200px;
            border:1px solid black;
            width:70%;
            display: inline-block;
            vertical-align: top;
        }

        .userName{
            display: inline-block;
            font-size:25px;
            width:300px;
            margin-left: 180px;
        }
        .user-icon_producunit img{
            object-fit: cover;
            width: 150px;
            border-radius: 50%;
            position: absolute;
            top:10px;
            left:5px;
            border: white 5px solid;
        }

        .actions{
            display: inline-block;
        }

  

        td{
            border:1px solid black;
            font-size:20px;
            padding:5px;
            width: 150px;
            
        }
        
        tr{
            margin-bottom: 10px;
        }

        tr:last-child{
            margin-bottom: none;
        }

    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">
        <div class="detail-baseinfo">
            <p class="productName">商品名</p>
            <p class="favorit-btn">お気に入り</p>
        </div>
        <div class="productCategory">
            カテゴリ
        </div>

        <div class="detail-pictures">
            <div class="detail-product pic01">
                    <img src="pictures/sample01.jpg">
            </div>
            <div class="detail-product pic02">
                    <img src="pictures/sample02.png">
            </div>

            <div class="detail-product pic03">
                    <img src="pictures/sample03.jpg">
            </div>
        </div>

        <div class="detail-info">
            <p>詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト
            詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト
            詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト
            詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト詳細テキスト
            </p>
        </div>
        
        <div class="under-coteiner">
            <div class="seller-info">
                    <div class="user-icon_producunit">
                        <a href="#"><img src="user_icon/usericon_sample01.jpg"></a>
                    </div>
                    <p class = "card userName">作者名</p>
            </div>

            <div class="actions">
                    <table>
                        <tbody>
                            <tr><td>￥15,000</td></tr>
                            <tr><td>購入する</td></tr>
                            <tr><td>作者にDM</td></tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <?php require_once('footer.php')?>
</body>
</html>