<div class="mypage-menu-conteiner">
                <ul>
                <?php
                    $menus = array(
                        ['url'=> 'mypage.php',
                        'menuName'=>'マイページ',
                        'subClass'=>'toMypage'],

                        ['url'=> 'profileEdit.php',
                        'menuName'=>'プロフィール編集',
                        'subClass'=>'toProfEdit'],

                        ['url'=> 'productEdit.php',
                        'menuName'=>'商品アップロード',
                        'subClass'=>'toUpload'],

                        ['url'=> 'myproducts_list.php',
                        'menuName'=>'商品編集一覧',
                        'subClass'=>'toUpload'],

                        ['url'=> 'favoriteList.php',
                        'menuName'=>'お気に入り一覧',
                        'subClass'=>'toFavorite'],

                        ['url'=> 'followList.php',
                        'menuName'=>'フォロー管理',
                        'subClass'=>'toFollows'],

                        ['url'=> 'passEdit.php',
                        'menuName'=>'パスワード変更',
                        'subClass'=>'toPassCng'],

                        ['url'=> 'withdraw.php',
                        'menuName'=>'退会する',
                        'subClass'=>'toWithdraw']
                    );

                    foreach($menus as $val){?>

                        <li class="mypage-menu-section <?php echo $val['subClass']?>">
                        <a  href="<?php echo $val['url']?>"> <?php echo $val['menuName']?></a> </li>


                    <?php }?>
                </ul>

            </div>