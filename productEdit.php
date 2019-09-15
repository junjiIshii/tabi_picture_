<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「商品登録、編集画面」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();
    loginAuth();

    //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
    //GETの設定
    //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

    //categoryデータの取得
    $categories = getCategory();
    debug('カテゴリの内容：'.print_r($categories,true));

    //GETデータを格納
    $p_id = (!empty($_GET['p_id']))? $_GET['p_id']:'';

    //DBから指定したproduct_IDの商品データを持ってくる。
    $dbProdData = (!empty($p_id))? getProduct($_SESSION['user_id'],$p_id):'';

    //編集画面なのか、新規登録画面なのか。
    if(empty($dbProdData)){
        debug('新規登録です。');
        $edit_flg = false;
    }else{
        debug('編集画面です。');
        $edit_flg = true;
    }

    debug('商品ID：'.$p_id);
    debug('フォーム用DBデータ：'.print_r($dbProdData,true));

    //指定したP_IDのゲットパラメーターが所有者（ユーザーID）と合致しない場合。
    if(!empty($p_id) && empty($dbProdData)){
        debug('GETパラメータの商品IDが違います。マイページへ遷移させます。');
        header("location:mypage.php");
    }



    //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
    //バリデーション
    //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
    if(!empty($_POST)){

        $pics = [];
        $title = htmlspecialchars($_POST['title']);
        $ctg = htmlspecialchars($_POST['categoryid']);
        debug('POSTのなかみ：'.print_r($_POST,true));
        $intr = htmlspecialchars($_POST['introduction']);
        $price = htmlspecialchars($_POST['price']);

    if($edit_flg == false){
        //新規
        minMaxWords($title,0,50,'title');
        minMaxWords($intr,0,500,'introduction');
        validNum($price,'price');

        mustEnter($title,'title');
        mustEnter($intr,'introduction');
        mustSelect($ctg,'category');
        mustEnter($price,'price');

    }else{

        //編集
        if($dbProdData['title'] !== $tile){
            minMaxWords($title,0,50,'title');
            mustEnter($title,'title');
        }

        if($dbProdData['detail'] !== $intr){
            minMaxWords($intr,0,500,'introduction');
            mustEnter($intr,'introduction');
        }

        if($dbProdData['category'] !== $ctg){
            mustSelect($ctg,'category');
        }

        if($dbProdData['price'] !== $price){
            validNum($price,'price');
            mustEnter($price,'price');
        }

    }

    if(isset($_POST['delete'])){
        productDel($_GET['p_id']);
        exit();
    }

        //全ての画像に対してUPLOAD処理を行う→パスは配列に入れる
        for($i=1;$i<=9;$i++){

            if(!empty($_FILES["pic{$i}"]['name'])){
                array_push($pics,uploadImg($_FILES["pic{$i}"],'pics'));
            }elseif(empty($_FILES["pic{$i}"]['name']) && !empty($dbProdData["pic{$i}"])){
                array_push($pics,$dbProdData["pic{$i}"]);
            }else{
                array_push($pics,'');
            }
        }
        debug('FILESの中身：'.print_r($_FILES,true));

        //何もFILE情報が無い要素を削除して、番号を詰める。
        $pic_fordb = array_filter($pics,"strlen");
        $pic_fordb = array_values($pic_fordb);

        $pic_fordb = array_pad($pic_fordb,9,'');
        debug('picのなかみ：'.print_r($pic_fordb,true));

        //画像が一枚も追加されていない場合（配列０が空の場合）
        if(empty($pic_fordb[0])){
            $err_msg['product_img'] = MSG16;
        }

        if(!empty($err_msg)){
            debug('バリデーションエラー有り：'.print_r($err_msg,true));
        }
    }

    if(!empty($_POST) && empty($err_msg)){
        $dbh = dbconnect();
        try{
            if($edit_flg == false){
                $sql = 'INSERT INTO products(
                        userid,
                        pic1,
                        pic2,
                        pic3,
                        pic4,
                        pic5,
                        pic6,
                        pic7,
                        pic8,
                        pic9,
                        title,
                        detail,
                        price,
                        create_time,
                        categoryid
                    )VALUES(
                        :u_id,
                        :p1,
                        :p2,
                        :p3,
                        :p4,
                        :p5,
                        :p6,
                        :p7,
                        :p8,
                        :p9,
                        :title,
                        :detail,
                        :price,
                        :cre_time,
                        :cat_id
                    )';

                $data = array(
                        ':u_id'=>$_SESSION['user_id'],
                        ':p1'=>$pic_fordb[0],
                        ':p2'=>$pic_fordb[1],
                        ':p3'=>$pic_fordb[2],
                        ':p4'=>$pic_fordb[3],
                        ':p5'=>$pic_fordb[4],
                        ':p6'=>$pic_fordb[5],
                        ':p7'=>$pic_fordb[6],
                        ':p8'=>$pic_fordb[7],
                        ':p9'=>$pic_fordb[8],
                        ':title'=>$title,
                        ':detail'=>$intr,
                        ':price'=>$price,
                        ':cre_time'=>date('Y-m-d H:i:s'),
                        ':cat_id'=>$ctg

                );
                //写真のデータを挿入

                debug('クエリ内容：'.print_r($data,true));

            }else{
                //編集の場合
                $sql = 'UPDATE products SET
                        pic1= :p1,
                        pic2= :p2,
                        pic3= :p3,
                        pic4= :p4,
                        pic5= :p5,
                        pic6= :p6,
                        pic7= :p7,
                        pic8= :p8,
                        pic9= :p9,
                        title= :title,
                        detail= :detail,
                        price= :price,
                        categoryid= :cat_id
                    WHERE productid = :p_id';

                $data = array(
                    ':p1'=>$pic_fordb[0],
                    ':p2'=>$pic_fordb[1],
                    ':p3'=>$pic_fordb[2],
                    ':p4'=>$pic_fordb[3],
                    ':p5'=>$pic_fordb[4],
                    ':p6'=>$pic_fordb[5],
                    ':p7'=>$pic_fordb[6],
                    ':p8'=>$pic_fordb[7],
                    ':p9'=>$pic_fordb[8],
                    ':title'=>$title,
                    ':detail'=>$intr,
                    ':price'=>$price,
                    ':cat_id'=>$ctg,
                    ':p_id'=>$p_id
                );

                debug('データの中身：'.print_r($data,true));

            }

            //クエリの実行
            $stmt = queryPost($dbh,$sql,$data);

            if($stmt && $edit_flg == false){
                debug('クエリ成功。マイページへ移動');
                $_SESSION['msg_suc']= SUC03;
                header("location:mypage.php");
            }elseif($stmt && $edit_flg == true){
                debug('クエリ成功。マイページへ移動');
                $_SESSION['msg_suc']= SUC04;
                header("location:mypage.php");
            }else{
                debug('クエリ失敗');
                throw new Exception;
            }

        }catch(Exception $e){
            debug('エラー発生：'.$e->getMessage());
            $err_msg['fatal']= MSG06;
        }
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品出品・編集</title>
    <link href="style.css" rel="stylesheet">

    <style>
    .main-conteiner{
        width: 85%;
        margin: 0 auto;
        margin-top:10px;
            }

    .edit-menu-wrapper{
        display: inline-block;
        height: 100%;
        width:80%;
        background: #f5f5f5;
    }

    .edit-menu-conteiner{
        width:700px;
        margin: 0 auto;
        padding: 20px 30px;
    }

    .conteiner-Name{
        text-align: center;
    }
    .editArea{
        margin-bottom: 30px;

    }

    .dataName{
        display: inline-block;
    }


    .images-contener{
        width: 100%;
        text-align: center;
    }


    /*EditArea*/
    .product_img{
        width:200px;
        position: relative;
        background:rgba(204,255,229,0.5);
        margin-bottom:5px;
        display: inline-block;
    }



    .preview-product_img{
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        position: absolute;
        top:0;
    }



    /*↓INPUT*/
    .editContent-product_img{
        width: 100%;
        height:200px;
        opacity:0;
    }

    .import-guide{
        position: absolute;
        text-align: center;
        top:50%;
        color:rgba(104,125,114,0.5);
        font-size:10px;
        width: 100%;
    }

    .img_num{
        top:30%;
        font-size:15px;
    }

    .imgbtn{
        display: inline-block;
        padding:10px;
        cursor:pointer;
        margin-bottom: 10px;
    }
    .title{
        margin-top:10px;
    }

    .btn-conteiner{
        text-align: center;
        margin-top:10px;
    }

    .lessNum{
        margin-left:10px;
        background-color :rgba(85,120,210,0.5);
    }

    .moreNum{
        background-color :rgba(200,80,65,0.5);
    }

    .editContent{
        font-size:20px;
        display: block;
        width:60%;
        height: 40px;
        border:none;
        border-bottom:1px solid #99FFCC;
        background: rgba(204,255,229,0.5);
        padding:5px;
    }

    #category{
        display: block;
        margin-top:5px;
        width:60%;
        height: 40px;
        font-size:18px;;
        border: none;
        border-radius:0px;
        background: rgba(204,255,229,0.5);

    }


    .price{
        width:30%;
    }

    .editContent-textArea{
        border-bottom:1px solid #99FFCC;
        background: rgba(204,255,229,0.5);
        font-size:15px;
        display: block;
        resize:none;
        width:100%;
        height: 150px;
        padding: 5px;
    }

    .caution{
        font-size: 10px;
        color:gray;
    }

    .err_msg{
        color:red;
    }

    .fatal{
        display:block;
        text-align: center;
    }
    .btn-conteiner{
        text-align: center;
    }

    .form-btn{
            width: 160px;
            height: 40px;
            margin: 0 auto;
            font-size: 20px;
            font-weight: blod;
            color:white;
            border: none;
            cursor:pointer;
        }

        .register{
            background-color: darkcyan;
        }
        .delete{
                margin-left:20px;
                background-color: darkred;
            }

    </style>
</head>
<body>
    <?php require_once('header.php')?>
    <div class="main-conteiner">

            <div class="edit-menu-wrapper">
                    <div class="edit-menu-conteiner">
                        <h2 class="conteiner-Name"><?php if($edit_flg){echo '商品編集';}else{echo '商品登録';}?></h2>
                        <span class="fatal err_msg"><?php cautionEcho('fatal');?></span>

                        <form method="post" enctype="multipart/form-data">
                            <div class="btn-conteiner">

                                <button type="button" class="moreNum imgbtn">
                                    画像＋
                                </button>

                                <button type="button" class="lessNum imgbtn">
                                    画像ー
                                </button>

                            </div>

                            <div class="images-contener">

                            <?php for ($i=1; $i<=9; $i++){?>
                                    <div class="<?php echo "product_img area-drop im{$i}"?>" 
                                        style="<?php if($i>=4 && empty(editValSet("pic{$i}",$dbProdData)))echo "display:none;";?>">

                                        <span class="import-guide img_num"><?php if($i==1){echo "画像１（必須）";}else{echo "画像{$i}";}?></span>
                                        <span class="import-guide">ファイルをドロップまたはクリック</span>

                                        <img class="preview-product_img" src="<?php echo editValSet("pic{$i}",$dbProdData)?>"
                                        alt="" style="<?php if(empty(editValSet("pic{$i}",$dbProdData))) echo 'display:none;'?>">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
                                        <input
                                        type="file" class="editContent-product_img input_img" name="<?php echo "pic{$i}"?>">
                                    </div>
                            <?php }?>

                            </div>
                            <span class="err_msg imgCount" ><?php cautionEcho('product_img');?></span>
                                


                            

                            <div class="editArea title">
                                <p class="dataName">商品名</p>
                                <span class="caution">入力必須</span>
                                <input
                                type="text" class="editContent" name="title"
                                value="<?php echo editValSet('title',$dbProdData);?>">
                                <span class="err_msg"><?php cautionEcho('title');?></span>
                            </div>
                            
                            <div class="editArea category">
                                <p class="dataName">カテゴリー</p>
                                <span class="caution">選択必須</span>

                                <select name="categoryid" id="category">
                                    <option value="" hidden>選択してください</option>

                                    <!--DBからカテゴリ名を持ってきて、VALUEには対応するID番号を入れる。-->
                                    <?php foreach($categories as $value){?>
                                        <option <?php if($value['categoryid'] == editValSet('categoryid',$dbProdData))echo 'selected'?>
                                        value="<?php echo $value['categoryid']?>"><?php echo $value['category_name']?></option>
                                    <?php }?>
                                </select>
                                <span class="err_msg"><?php cautionEcho('category')?></span>
                            </div>


                            <div class="editArea introduction">
                                <p class="dataName">商品詳細</p>
                                <span class="caution">500文字以内で入力</span>
                                <textarea name="introduction" class="editContent-textArea"><?php echo editValSet('detail',$dbProdData);?></textarea>
                                <span class="err_msg"><?php cautionEcho('introduction');?></span>
                            </div>
                            

                            <div class="editArea">
                                <p class="dataName">金額</p>
                                <span class="caution">半角英数字で入力</span>
                                <input
                                type="text" class="editContent price"name="price"
                                value="<?php echo editValSet('price',$dbProdData) ;?>">
                                <span class="err_msg"><?php cautionEcho('price');?></span>
                            </div>


                            <div class="btn-conteiner">
                                <input type="submit" class="register form-btn" 
                                value="<?php if($edit_flg){echo '変更する';}else{echo '出品する';}?>">  

                                <input type="submit" class="delete form-btn" name="delete"
                                value="商品削除" style="<?php if($edit_flg==false){echo 'display:none;';}?>"> 
                            </div>
        
                        </form>
                    </div>
            </div>
            <?php require_once('mypageBar.php')?>

        <div class="cd"></div>
    </div>
    <?php require_once('footer.php')?>
    <script type="text/javascript" src="imgIncDecBtn.js"></script>
    <script type="text/javascript" src="imgPreview.js"></script>
    

</body>
</html>