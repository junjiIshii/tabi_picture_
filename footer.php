<footer>
    <div class="copy-right">
        Copyright(C)2019 TABI PICTUREs All Rights Reserved
    </div>
    <!--共通のJSファイル -->
    <!--JQ読み込み -->
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>

    <!--FOOTERの調整（つねにブラウザの下にいる） -->
    <script type="text/javascript" src="footerAdj.js"></script>

    <script type="text/javascript" src="showMessage.js"></script>

    <script type="text/javascript" src="slidemenu.js"></script>

    <!--特定のページのみに働くJSファイル -->
    <?php
        switch(basename($_SERVER['REQUEST_URI'])){
            case 'signup.php':
                echo '<script type="text/javascript" src="signupVal.js"></script>';
                break;

            case 'profileEdit.php':
                echo '<script type="text/javascript" src="imgPreview.js"></script>';
                break;

        }
    ?>
</footer>

