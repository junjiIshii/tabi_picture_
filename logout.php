<?php
    require("functions.php");
    debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
    debug('「ログインページ」');
    debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
    debugLogStart();

    debug('ログアウトします');

    session_destroy();
    header("Location:signin.php");
?>