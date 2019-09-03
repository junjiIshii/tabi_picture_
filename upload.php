<?php
    require_once('functions.php');
    iniSetting();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php ?>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <title>画像アップロード</title>

    <style>
    .product-units{
        width:300px;
        height: 500px;
    }
    .product-units img{
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">
        <div class="pic-upload">
            <div class="product-units">
                <img src="pictures/sample01.jpg">
            </div>
        </div>
        <?php require_once('footer.php')?>
</body>
</html>