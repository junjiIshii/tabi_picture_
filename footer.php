<footer>
    <div class="copy-right">
        Copyright(C)2019 TABI PICTUREs All Rights Reserved
    </div>
    <!--共通のJSファイル。上：JQ　下：フッター調整 -->
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="footerAdj.js"></script>

    <?php
        switch(basename($_SERVER['REQUEST_URI'])){
            case 'signup.php':
                echo '<script type="text/javascript" src="signupVal.js"></script>';
                break;

            case 'users_list.php':
                echo '<script type="text/javascript" src="follow_btn.js"></script>';
                break;

            case 'profileEdit.php':
                echo '<script type="text/javascript" src="imgPreview.js"></script>';
                break;
        }
    ?>
</footer>

