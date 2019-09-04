<?php
//==============================================
//エラー定数
//==============================================
define('MSG01','※入力必須です。');
define('MSG02','※Emailの形式ではありません。');
define('MSG03','※再入力パスワードが一致しません。');
define('MSG04','※半角で入力してください。');
define('MSG05','※入力したアドレスは既に登録済みです。');
define('MSG06','※エラーが発生しています。しばらく時間を空けてお試しください。');
define('MSG07','※メールアドレスまたはパスワードが違います。');
define('MSG08','※半角英数字で入力してください。');
define('MSG09','※30文字以内で入力して下さい。');
define('MSG10','※電話番号の形式に一致しません。');
define('MSG11','※郵便番号の形式に一致しません。');
define('MSG12','※半角数字で入力してください。');
define('MSG13','※変更前のパスワードが一致しません。');
define('MSG14','※変更前と同じパスワードです。');
define('SUC01','パスワードを変更しました。');
$err_msg =array();
$signup_db = array();


//==============================================
//共通INI,SESSION,DEBUG設定
//==============================================

ini_set('debug.log','on');
ini_set('display_errors', 1);
error_reporting(E_ALL^E_NOTICE);

//!!!!!!!!!!!!!
//要編集↓
//!!!!!!!!!!!!!
if($_SERVER['HTTP_HOST']=='localhost:8888'){
    session_save_path("/var/tmp/");
}else{
    session_save_path("/home/junji1996/english-protocol.net/xserver_php/session");
}

ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_start();
session_regenerate_id();

$debug_flg= true;

function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log("\n".'debug：'.$str,3,'debug.log');
    }
}

function getSessionFlash($key){
    if(!empty($_SESSION[$key])){
        $data=$_SESSION[$key];
        $_SESSION['msg_suc']='';
        debug('suc内容：'.$data);

        //↓この$dataには空文字にする前のデータが入っている。
        return $data;
    }
}

//==============================================
//ログインデバッグ開始
//==============================================
function debugLogStart(){
    debug('================画面表示処理開始');
    debug('>>>>>>>>>>セッション情報');
    debug('セッションID：'.session_id());
    debug('セッション変数：'.print_r($_SESSION,true));
    debug('現在日時：'.time());
    if(!empty($_SESSION['login_date'])){
        debug('ログイン済みのユーザー');
        debug('>>>>>>>>>>>>>>>>>>>>');

    }else{
        debug('未ログインのユーザー');
        debug('>>>>>>>>>>>>>>>>>>>>');
    }

    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug('ログイン期限日時タイムスタンプ：'.($_SESSION['login_date'] + $_SESSION['login_limit']));
        debug('>>>>>>>>>>>>>>>>>>>>');
    }
}


//==============================================
//ログイン認証設定
//==============================================
function loginAuth(){
    //ログインしている（logidateが発行されている）
    if(!empty($_SESSION['login_date'])){

        //現在日時が最終ログイン日時＋有効期限を越えていた場合
        if($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
            debug('ログイン有効期限オーバーです。セッションを削除してログインページに遷移');

            //セッションを削除
            session_destroy();
            header('Location:signin.php');
        }else{
            debug('ログイン有効期限以内です。');
            //最終ログイン日時を現在日時に更新
            $_SESSION['login_date'] = time();
            if($_SERVER['REQUEST_URI'] =='/tabi_picture/signin.php'){
                debug('ログインユーザーがログインページにアクセス。マイページに遷移');
                header('location:mypage.php');
            }
            return true;
        }
    }else{
        debug('未ログインユーザー');
        if($_SERVER['REQUEST_URI'] !='/tabi_picture/signin.php'){
            debug('未ログインユーザーがマイページにアクセス。ログインページに遷移');
            header('location:signin.php');
        }
    }
}



//==============================================
//共通フッター
//==============================================
function footer(){
    //コピーライト→jQueryファイル読み込み→フッター位置調整コード
    echo'
    <div class="copy-right">Copyright(C)2019 TABI PICTUREs All Rights Reserved</div>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="footerAdj.js"></script>';
}


//==============================================
//バリデーション
//==============================================

//未入力のチェック
function mustEnter($str,$errkey){
    global $err_msg;
    if(empty($str)){
        $err_msg[$errkey] = MSG01;}
}

//Emailの形式かどうかをチェックしている
function vaidemail($email, $errkey){
    global $err_msg;
    if(!empty($email) && !preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/",$email)){
        $err_msg[$errkey] = MSG02;};
}

//パスワードと再入力が一致しているか
function passMuch($pass,$repass,$errkey){
    global $err_msg;
    if($pass !== $repass){
        $err_msg[$errkey] = MSG03;
    }
}

//Emailの重複登録防止バリデーション
function vaidemailDup($email){
    global $err_msg;
    try{
        $dbh = dbconnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg=0';
        $data = array(':email' => htmlspecialchars($email));

        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if($result['count(*)'] != 0){
            $err_msg['email']= MSG05;
        }
    }catch(Exception $e){
        error_log('エラー発生'.$e->getMessage(), 3, 'debug.log');
        $err_msg['fatal']= MSG06;
    }
}

//最低・最大文字数バリデーション
function minMaxWords($str,$min=8,$max=30,$errkey){
    global $err_msg;
    if(strlen($str) < $min){
        $err_msg[$errkey] = "※{$min}文字以上入力して下さい。";
    }elseif(strlen($str) > $max){
        $err_msg[$errkey] = "※{$max}文字以内で入力して下さい。";
    }
}

//半角チェック
function validHalf($str,$errkey){
    if(!preg_match("/^[a-zA-z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$errkey]=MSG04;
    }
}

//電話番号形式チェック
function validTel($str, $errkey){
    if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/",$str)){
        global $err_msg;
        $err_msg[$errkey]= MSG10;
    }
}

//郵便番号の形式☑
function validZip($str, $errkey){
    if(!preg_match("/^\d{7}$/",$str)){
        global $err_msg;
        $err_msg[$errkey]=MSG11;
    }
}

//半角数字チェック
function validNum($str,$errkey){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$errkey]=MSG12;
    }
}

//画像ファイルアップロードバリデーション
function uploadImg($file, $key){
    global $err_msg;
    debug('画像アップロード処理開始');
    debug('FLIE情報：'.print_r($file,true));

    if(isset($file['error']) && is_int($file['error'])){
        try{
            debug('FILESエラー値をキャッチ');
            switch($file['error']){
                case UPLOAD_ERR_OK://アップロードは出来ている
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファイルのサイズが大きすぎます。');
                case UPLOAD_ERR_NO_FILE:
                    debug('デバッグ：ファイルが入っていませんでした。');
                default:
                    throw new RuntimeException('その他のエラーが発生しました。');
            }

            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], TRUE)){
                throw new RuntimeException('画像が形式が未対応です。');
            }
            debug('画像はアップロード可能です。');


            debug('アップロードします。');
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            debug('ファイルパス：'.$path);

            if(!move_uploaded_file($file['tmp_name'], $path)){
                throw new RuntimeException('ファイル保存時にエラーが発生しました。');
            }

            chmod($path, 0644);

            debug('ファイルは正常にアップロードされました。');
            debug('ファイルパス：'.$path);
            return $path;

        }catch(RuntimeException $e){
            debug($e->getMessage());
            $err_msg[$key]=$e->getMessage();
        }
    }
}


//PHPバリデーションの内容出力
function cautionEcho($errKey){
    global $err_msg;
    if(isset($err_msg[$errKey])){
        echo $err_msg[$errKey];
    }
}

//==============================================
//DB接続
//==============================================

//DataBaseコネクション!実装時にELSEを加える!
//共通のDB接続設定
function dbconnect(){

    $dsn = 'mysql:dbname=tabi_picture; host=localhost; charset=utf8';
    $user = 'root';
    $password = 'root';

    $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );
    //PDOオブジェクトを生成
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}



//共通のクエリ作成と実行関数
function queryPost($dbh,$sql,$data){
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}

//デリートフラグのチェック（復活処理）
function delFlagchek($email){
    try{
        debug('関数実行');
        $dbh = dbconnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg=1';
        $data = array(':email'=> htmlspecialchars($email));

        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if($result['count(*)'] != 0){
            debug('退会済みのユーザーに該当あり');
        }else{
            debug('退会済みユーザーに該当なし');
        }
        return $result['count(*)'];
    }catch(Exception $e){
        debug('エラー発生'.$e->getMessage());
    }
}

//ユーザー情報（）の取得
function getUserData($userid){
    debug('ユーザー情報を取得します');
    try{
        $dbh =dbconnect();
        $sql = 'SELECT * FROM users WHERE userid = :userid';
        $data = array(':userid'=> $userid);

        $stmt = queryPost($dbh,$sql,$data);
        return $stmt -> fetch(PDO::FETCH_ASSOC);

    }catch(Exception $e){
        debug('エラー発生'.$e->getMessage());
    }
}

//編集画面でのデフォルトVALUEset
function profEditValSet($key){
    global $dbUserData;
    global $err_msg;

    //DBにデータがある場合
    if(!empty($dbUserData)){
        //POSTされたが入力内容にエラーがある。
        if(!empty($err_msg[$key])){
            //POST内容がある。
            if(isset($_POST[$key])){
                return $_POST[$key];
            }else{
                return $dbUserData[$key];
            }
        }else{
            //エラーなしの入力内容がPOSTされた。
            //DBと違う内容だった場合（更新する場合）
            if(isset($_POST[$key]) && $_POST[$key] !== $dbUserData[$key]){
                return $_POST[$key];
            }else{
                //DBと同じ内容だった場合（入力したけど更新前と同じだった）
                return $dbUserData[$key];
            }
        }
    //DBにデータがない。（未登録）
    }else{
        //POST内容があればとりあえずそれをだす。（他の項目でエラーがあった時、POSTした新登録内容が消えない）
        if(isset($_POST[$key])){
            return $_POST[$key];
        }
    }
}

function profEditImgSet($key){
    global $dbUserData;
    global $err_msg;

    //DBに画像データがある場合
    if(!empty($dbuserdata)){
        //POSTした画像にエラーがある
        if(!empty($err_msg[$key])){
            return $_FILES[$key]['name'];
        }else{
            //エラーがない（新しく更新する）
            if(isset($_FILES[$key]) && $_FILES[$key] !== $dbUserData[$key]){
                return $_FILES[$key]['name'];
            }else{
                //DBとおなじ（更新しない）
                return $dbUserData[$key];
            }
        }
    }else{
        //DBにデータがない（未登録）
        //POST内容があればそれを出す。
        if(isset($_FILES[$key])){
            return $_FILES[$key]['name'];
        }
    }
}



//===================
//サインアップ
//===================

//新規登録の接続
function signup(){
    global $err_msg;
    if(empty($err_msg)){
        $userName = htmlspecialchars($_POST['user-name']);
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars(password_hash($_POST['password'], PASSWORD_DEFAULT));

        try{
            $dbh= dbconnect();
            $sql = 'INSERT INTO users (username,email,password,login_time, create_date) VALUES (:username, :email,:password,:login_time, :create_date)';
            $data = array(
                    'username'=>$userName,
                    ':email'=> $email,
                    ':password'=> $pass,
                    ':login_time'=> date('Y-m-d H:i:s'),
                    ':create_date'=> date('Y-m-d H:i:s'));

        $stmt = queryPost($dbh,$sql,$data);

        //クエリ成功→登録ユーザーにログイン権限をスグに与える。これが無いとに二度手間になる。
        if($stmt){
            //デフォルトのログイン有効期限
            $sesLimit = 60*60;

            //最終ログイン日時を現在日時にする。
            $_SESSION['login_date']= time();
            $_SESSION['login_limit']=$sesLimit;

            $_SESSION['user_id']= $dbh->lastInsertId();
            header('Location:mypage.php');
        }

        }catch(Exception $e){
            debug('エラー発生'.$e->getMessage());
            $err_msg['fatal']=MSG06;
        }
    }
}

