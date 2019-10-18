<?php
    require("functions.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>TABI_PICTURE</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    
    <style>
        .bg-img{
            background-image: url("pictures/sample04.jpg");
            background-size: cover;
            width:100%;
        }

        .bg-mask{
            width:100%;
            height:100%;
            background: rgba(255,255,255,0.8);
        }
        .main-conteiner{
            flex-direction:column;
            padding-bottom: 15px;
        }

        

        .info-conteiner{
            width:60%;
            min-width:460px;
            margin:150px auto 70px auto;
        }

        .intro-mes{
            color:darkcyan;
            text-align: center;
        }


        .tutorial-info{
            width:85%;
            margin:0 auto;
        }

        .info-top{
            margin-bottom:50px;
        }

        .tutorial-info h3{
            color:darkcyan;
        }


        .info-unit{
            margin:15px 0px 0px 10px;
            display:flex;
        }

        .info-unit .sentence{
            width:60%;
        }

        .info-imge{
            width:40%;
            min-width:250px;
            max-width:300px;
            max-height:350px;
        }

        .info-imge img{
            object-fit: cover;
            width:100%;
            height:100%;
        }

        .btn-conteiner{
            margin:60px 0px;
        }

        .actionbutton{
                width: 45%;
                min-width:300px;
                height:40px;
                padding: 3px;
                font-size:16px;
                font-weight:bold;
                cursor:pointer;
                margin:0 auto;
                display:block;
                
        }

        .blue{
            background:skyblue;
            color:dodgerblue;
            margin:15px auto; 
        }

        .green{
            background: rgba(7, 230, 163, 0.6);
            color:cadetblue;
        }

        .guest-signup{
            text-align: center;
        }
        

        .guest-signup li{
            margin-top:5px;
            list-style: none;
            
        }



    </style>
</head>

<body>
    <?php require_once('header.php')?>

        <div class="bg-img">
            <div class="bg-mask">
                <div class="main-conteiner" id="indexPage"> 

                    <div class="info-conteiner">

                        <h3 class="intro-mes">あなたのとっておきの旅先の写真を売ってみよう</h3>
                        <h3 class="intro-mes">TABI PICTUREs は旅専門の写真売買サイトです。</h3>


                        <div class="btn-conteiner">

                            <button class="actionbutton green has-link" type="button" data-url="signin.php">
                                新規アカウント作成
                            </button>

                            <button class="actionbutton blue has-link" type="button" data-url="signup.php">
                                ログイン
                            </button>

                            <button class="actionbutton green has-link" type="button" data-url="products_list.php">
                                写真を見てみる
                            </button>
                        </div>

                        <div class="guest-signup">
                            <p>ポートフォリオをご覧頂きありがとうございます。</p>
                            <p>ゲストユーザーとして登録をせず機能を見れます。</p>
                            <ul class="guest-selector">
                                <li class="guest01">ゲストユーザー１</li>
                                <li class="guest02">ゲストユーザー２</li>
                                <li class="guest03">ゲストユーザー３</li>
                            </ul>
                        </div>
                    </div>

                        <div class="tutorial-info">
                                <div class="info-top">
                                    <h3>TABI PICTUREsとは？</h3>
                                        <p>TABI PICTUREsとはイン○タグラムのように１：１の比率の写真を、ピク○タのように売買出来る写真売買サイトです。「旅」「旅行」に特化しているのは完全に作者の趣味です。インス○グラム x ピクス○もどきななものです。もどきサイトなわりには以下のような機能を持ち合わせています。</p>
                                </div>  

                                <h3>TABI PICTUREsで出来ること</h3>
                                <div class="info-unit merit1">
                                    <div class="sentence">
                                        <h4>最大９枚の写真を１アルバムとして売買ができます。</h4>
                                        <p>1枚絵でなく写真集のように売ることができます。見た人が旅の追体験を出来るような素敵な旅のアルバムにしてみよう。（！ポートフォリオのため実際の売買はできません！画像アップロードは可能です。）</p>
                                    </div>

                                    <div class="info-imge">
                                        <img src="pictures/info-picture01.png">
                                    </div>
                                    
                                </div>

                                <div class="info-unit merit2">
                                    <div class="sentence">
                                        <h4>好きなユーザーをフォローすることができます。</h4>
                                        <p>海外専門、国内専門、町専門、秘境専門、様々な旅人の新しい写真をいち早くゲットできます。気になるユーザーをフォローして好きな旅写真を集めよう。</p>
                                    </div>
                                    
                                    <div class="info-imge">
                                        <img src="pictures/info-picture02.png">
                                    </div>
                                </div>

                                <div class="info-unit merit3">
                                    <div class="sentence">
                                        <h4>ちょっと気になった写真をお気に入り保存。</h4>
                                        <p>今は買う気はないかもしれないけど、あとですぐ見るかも。そんな時はハートの「お気に入りボタン」で保存しよう。「お気に入り一覧」からすぐアクセスできます。</p>
                                    </div>

                                    <div class="info-imge">
                                        <img src="pictures/info-picture03.png">
                                    </div>
                                </div>

                                <div class="info-unit merit4">
                                    <div class="sentence">
                                        <h4>メッセージ機能を使ってコンタクトを取れます。</h4>
                                        <p>写真の感想を送ったり、撮影地を詳しく尋ねたりしてみよう。ユーザー一覧からメッセージを送りたいユーザーのマイページへアクセスして「DMを送る」ボタンでメッセージを送れます。メッセージ送信にはログインが必要です。</p>
                                    </div>

                                    <div class="info-imge">
                                        <img src="pictures/info-picture04.png">
                                    </div>
                                </div>


                            
                        </div>

                </div>
            </div>
        </div>

    <?php require_once('footer.php')?>
    <script>
        $('.has-link').click(function(){
            location.href=$(this).attr('data-url')
            });
    </script>
</body>