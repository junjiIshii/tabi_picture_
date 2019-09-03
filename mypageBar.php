<div class="mypage-menu-conteiner">
                <ul>
                <?php
                    $menus = array(
                        ['url'=> 'mypage.php',
                        'menuName'=>'マイページ',
                        'subClass'=>'toMypage'],

                        ['url'=> '#',
                        'menuName'=>'お気に入り',
                        'subClass'=>'toFavorite'],

                        ['url'=> '#',
                        'menuName'=>'フォロー管理',
                        'subClass'=>'toFollows'],

                        ['url'=> '#',
                        'menuName'=>'販売履歴',
                        'subClass'=>'toHistory'],

                        ['url'=> '#',
                        'menuName'=>'パスワード変更',
                        'subClass'=>'toPassCng'],

                        ['url'=> 'profileEdit.php',
                        'menuName'=>'プロフィール編集',
                        'subClass'=>'toProfEdit'],

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