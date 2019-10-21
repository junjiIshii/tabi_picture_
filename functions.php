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
define('MSG15','※選択必須です。');
define('MSG16','※最低１枚写真を追加してください。');
define('MSG17','※認証キーが異なります。');
define('MSG18','※認証キーの有効期限が切れました。再度発行して下さい。');
define('MSG19','この商品は削除されています。');
define('MSG20','このユーザーは退会済みです。');

define('SUC01','パスワードを変更しました。');
define('SUC02','プロフィール内容を変更しました。');
define('SUC03','商品の出品が完了しました。');
define('SUC04','商品内容を変更しました。');
define('SUC05','商品内容を削除しました。');
define('SUC06','入力したアドレスに認証キーを送信しました。');
define('SUC07','パスワードを再発行しました。メールをご確認ください。');
define('SUC08','メッセージを送信しました。');

$err_msg =array();


//==============================================
//共通INI,SESSION,DEBUG設定
//==============================================

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );
ini_set('debug.log','on');
require('secretpass.php');

//!!!!!!!!!!!!!
//要編集↓
//!!!!!!!!!!!!!
if($_SERVER['HTTP_HOST']=='localhost:8888'){
    session_save_path($local_session_path);
}else{
    session_save_path($real_session_path);
}

ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_start();
session_regenerate_id();


//デバックログの設定TRUE→ログ開始、FALSE→ログを出さない
$debug_flg= false;

function debug($str){
    global $debug_flg;
    if($debug_flg){
        error_log("\n".'debug：'.$str,3,'debug.log');
    }
}

function getSessionFlash(){
    if(!empty($_SESSION['msg_suc']) && empty($_POST)){
        echo $_SESSION['msg_suc'];
        unset($_SESSION['msg_suc']);
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
//認証設定
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
        $err_msg[$errkey] = MSG01;
    }
}

function mustSelect($str,$errkey){
    global $err_msg;
    if(empty($str)){
        $err_msg[$errkey] = MSG15;
    }
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

function lengthCheck($str,$rightLen,$errKey){
    global $err_msg;
    if(mb_strlen($str) !== $rightLen){
        $err_msg[$errKey] = "{$rightLen}文字で入力してください。";
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

    require('secretpass.php');//パスワードなどを管理する別ファイル
    $dsn = $dataBase_name;//データベース名を入力
    $user = $user_name;//DB管理者名
    $password = $server_pass;//パスワード

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

//Emailデリートフラグのチェック（復活処理）
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


//ゲッター系
//==============================================

//ユーザー情報（）の取得
function getUserData($userid){
    debug('ユーザー情報を全て取得します');
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

function getOneUserData($userid,$colKey){
    debug($userid.'のユーザー情報を取得します：'.$colKey);
    try{
        $dbh =dbconnect();
        $sql = "SELECT $colKey FROM users WHERE userid = :userid";
        $data = array(':userid'=> $userid);

        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        //debug(print_r($result,true));
        return $result ;

    }catch(Exception $e){
        debug('エラー発生'.$e->getMessage());
    }
}

function getCategory(){
    debug('カテゴリー情報を取得します');
    try{
        $dbh =dbconnect();
        $sql = 'SELECT categoryid,category_name FROM category';

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $result =$stmt -> fetchall(PDO::FETCH_ASSOC);
        debug(print_r($result,true));
        return $result;
    }catch(Exception $e){
        debug('エラー発生'.$e->getMessage());
    }
}


function getProduct($u_id, $p_id){
    debug('ユーザーIDに対する商品情報を取得します。');
    debug('ユーザーID；'.$u_id);
    debug('商品ID：'.$p_id);

    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM products WHERE userid = :u_id AND productid= :p_id AND delete_flg=0';
        $data = array(':u_id'=> $u_id,':p_id'=>$p_id);

        $stmt = queryPost($dbh,$sql,$data);

        if($stmt){
            debug('クエリ成功');
            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

function getOneProductData($productid,$colKey){
    debug('getOneProductData以下の商品情報を取得します：'.$colKey);
    try{
        $dbh =dbconnect();
        $sql = "SELECT $colKey FROM products WHERE productid = :productid";
        $data = array(':productid'=> $productid);

        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        debug('検索結果：'.print_r($result,true));
        return $result;

    }catch(Exception $e){
        debug('エラー発生'.$e->getMessage());
    }
}

//特定のデータを指定数分だけ取得する。
function getSelectData($getCount,$offSet,$table,$column){
    debug("{$table}情報を{$offSet}番目から{$getCount}個取得します。");
    try{
        $dbh = dbconnect();
        $sql = "SELECT {$column} FROM {$table} LIMIT {$getCount} OFFSET {$offSet}";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $result = $stmt -> fetchAll();
        return $result;
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//任意テーブルにおける、任意のカラムのレコード個数を取得する。
function getNumData($column,$table){
    try{
        $dbh= dbconnect();
        //ユーザーリストはここにOPENFLGは入れてはいけない。
        $sql= "SELECT count({$column}) FROM {$table} WHERE delete_flg = 0" ;
        if($table=='products'){ $sql .= ' AND open_flg = 1';}

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $result =  $stmt->fetch();
        debug($result["count({$column})"]);
        return $result["count({$column})"];
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//IDで指定したユーザーが持っている商品データの総数を取得する。
function getUserProducNum($u_id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT count(productid) FROM products WHERE userid=:u_id AND delete_flg = 0';
        $data = array(':u_id'=>$u_id);

        $stmt = queryPost($dbh,$sql,$data);

        if($stmt){
            debug('クエリが成功');
            $result = $stmt -> fetch();
            debug('取得データ：'.$result["count(productid)"]);
            return $result["count(productid)"];
        }
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//DM取得
function getMesaage($to,$user){
    debug('DM内容を取得します。');
    try{
        $dbh = dbconnect();
        $sql = 'SELECT send_from,send_to,send_msg,d.create_time,u.username,u.icon_img
                FROM users AS u RIGHT JOIN dm AS d ON u.userid = send_from
                WHERE send_from= :sender AND send_to = :sendto
                ORDER BY d.create_time DESC';

        //送信者が自分自身。自分が相手に送ったメッセージを取得
        $dataSed = array(':sender'=>$user, ':sendto'=>$to);

        //自分宛に送られた相手のメッセージ
        $dataRec = array(':sender'=>$to, ':sendto'=>$user);

        $stmtSed = queryPost($dbh,$sql,$dataSed);
        $stmtRec = queryPost($dbh,$sql,$dataRec);

        $sendData = $stmtSed -> fetchall(PDO::FETCH_ASSOC);
        $reciveData = $stmtRec -> fetchall(PDO::FETCH_ASSOC);;

        //自分が送信したもの、相手から受信したものを一つの配列にマージする。
        $dmData = array_merge($reciveData,$sendData);
        //debug('ソート前DM配列：'.print_r($dmData,true));

        //こDMデータが空＝初めてDMするときは、実行しない。
        if(!empty($dmData)){
            //creat_timeの昇順でソートするために、そのソート源としての配列を作る。
            foreach($dmData as $key =>$val){
                $sort[$key] = $val['create_time'];
            }
            //debug('ソート配列：'.print_r($sort,true));
            //ソート用配列を元に、creat_time=送信時間順番に並び替える。
            array_multisort($sort,SORT_ASC,$dmData);
            debug('ソート後DM配列：'.print_r($dmData,true));
            
            return $dmData;
        }else{
            return $dmData;
        }

    }catch(Exception $e){
        debug('エラー内容：'.$e->getMessage());
        global $err_msg;
        $err_msg['fatal']=MSG06;
    }
}

//ユーザーがフォローしているユーザーの名前、アイコン、IDをとる。
function getFollowData($u_id){
    try{
        $dbh = dbconnect();
        $sql ='SELECT f.userid, follow,username,icon_img
        FROM follows AS f LEFT JOIN users AS u ON f.follow = u.userid 
        WHERE u.delete_flg =0 AND f.userid = :f_user';

        $data = array(':f_user'=>$u_id);
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        debug('取得データ：'.print_r($result,true));
        return $result;
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//ユーザーをフォローしているユーザー一覧作成のため、フォロワーの名前、アイコン、IDをとる。
function getWhoFollow($u_id){
    try{
        $dbh = dbconnect();
        $sql ='SELECT follow, f.userid, u.userid,username,icon_img
        FROM follows AS f RIGHT JOIN users AS u ON f.userid = u.userid 
        WHERE u.delete_flg =0 AND f.follow = :f_user';

        $data = array(':f_user'=>$u_id);
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        debug('取得データ：'.print_r($result,true));
        return $result;
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

function getFavoList($u_id){
    try{
        $dbh = dbconnect();
        $sql ='SELECT p.productid,p.userid,u.username,pic1,title,detail,icon_img FROM favorite AS fv
        LEFT JOIN products AS p ON fv.productid = p.productid 
        LEFT JOIN users AS u ON p.userid = u.userid 
        WHERE p.delete_flg=0 AND fv.userid = :u_id';

        $data = array(':u_id'=>$u_id);
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        debug('取得データ：'.print_r($result,true));
        return $result;
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//削除フラグ無しの登録されている商品のデータと、合致するユーザーデータを任意数分取得する。
function makeProducList($maxShow,$offset){
    try{
        $dbh = dbconnect();
        $sql = "SELECT productid,u.userid, username, icon_img,pic1,title,detail
        FROM users AS u RIGHT JOIN products AS p ON u.userid = p.userid WHERE p.delete_flg = 0 AND open_flg = 1
        LIMIT {$maxShow} OFFSET {$offset}";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $result = $stmt -> fetchAll();
        debug('取得データ：'.print_r($result,true));
        return $result;

    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//IDで指定したユーザーが持っている商品のデータを任意数分、取得する。
function makeUserProducList($maxShow,$offset,$u_id){
    try{
        $dbh = dbconnect();
        $sql = "SELECT productid,pic1,title,detail,open_flg FROM products WHERE delete_flg = 0 AND userid = :u_id;
        LIMIT {$maxShow} OFFSET {$offset}";

        $data = array(':u_id'=>$u_id);
        
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetchAll();
        debug('取得データ：'.print_r($result,true));
        return $result;

    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//指定した商品IDを元に商品情報とユーザー情報を確かめる。
//また入力された商品のデリートされている（不正な入力）出ないかチェック
function showProductData($p_id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT
                productid,u.userid, username,icon_img,pic1,pic2,pic3,pic4,pic5,pic6,pic7,pic8,pic9,
                title,detail,price,p.delete_flg,open_flg,categoryid,username,icon_img,introduction
                FROM users AS u RIGHT JOIN products AS p ON u.userid = p.userid
                WHERE productid=:p_id';
        $data = array(':p_id'=>$p_id);

        $stmt = queryPost($dbh,$sql,$data);

        if($stmt){
            debug('クエリが成功');
            $result = $stmt -> fetch(PDO::FETCH_ASSOC);
            debug('取得データ：'.print_r($result,true));
            return $result;
        }
    }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
    }
}

//検索結果分のデータを表示する。$type=1は通常検索、2の時は自分の商品の中での検索(myproducts_List)
function showSearchProd($currentPg,$type=1){
    $nameSer = htmlspecialchars($_GET['byName']);
    $catSer =  htmlspecialchars($_GET['c_id']);
    $slectShowNum =  htmlspecialchars($_GET['showNum']);
    $slectShowType =  htmlspecialchars($_GET['sort']);

    try{
        $dbh=dbconnect();

        //検索にヒットするレコードを指定数分取得する。
        $sql = 'SELECT productid,u.userid, username, icon_img,pic1,title,detail,p.create_time,open_flg
        FROM users AS u RIGHT JOIN products AS p ON u.userid = p.userid';

        //検索総数を出すためのカウント
        $sql2 = 'SELECT count(productid)
        FROM users AS u RIGHT JOIN products AS p ON u.userid = p.userid';
        $data = array();

        //
        if(!empty($nameSer)){
            $sql .= ' WHERE title=:tle';
            $sql2 .= ' WHERE title=:tle';
            $data[':tle'] = $nameSer;
        }

        if(!empty($nameSer) && $catSer !=0 ){
            $sql .= ' AND categoryid=:cat_id';
            $sql2 .=' AND categoryid=:cat_id';
            $data[':cat_id'] = $catSer;
        }elseif($catSer !=0){
            $sql .= ' WHERE categoryid=:cat_id';
            $sql2 .=' WHERE categoryid=:cat_id';
            $data[':cat_id'] = $catSer;
        }

        if((!empty($nameSer) || $catSer !=0 ) && $type==2){
            $sql .= ' AND p.userid=:u_id';
            $sql2 .=' AND p.userid=:u_id';
            $data[':u_id'] = $_SESSION['user_id'];
        }elseif(empty($nameSer) && $catSer ==0 && $type==2){
            $sql .= ' WHERE p.userid=:u_id';
            $sql2 .=' WHERE p.userid=:u_id';
            $data[':u_id'] = $_SESSION['user_id'];
        }

        switch($slectShowType){
            case 1:
            $sql .= " ORDER BY create_time ASC";
            break;

            case 2:
            $sql .= " ORDER BY create_time DESC";
            break;

            
        }
        $stmt2 = queryPost($dbh,$sql2,$data);
        $allcount = $stmt2 -> fetch();
        debug('検索総数結果：'.print_r($allcount,true));

        
        $offset =($currentPg-1)*$slectShowNum;
        $sql .= " LIMIT {$slectShowNum} OFFSET {$offset}";
        $stmt = queryPost($dbh,$sql,$data);
        
        

        if($stmt && $stmt2){
            $arrayData= $stmt -> fetchall(PDO::FETCH_ASSOC);
            $result['total'] = $allcount['count(productid)'];
            $result['data'] = $arrayData;

            return $result;
        }
        
    }catch(Exception $e){
        debug('エラー発生:'.$e->getMessage());
    }
}

function showSearchUser($currentPg){
    $nameSer = htmlspecialchars($_GET['byName']);
    $slectShowNum =  htmlspecialchars($_GET['showNum']);
    $slectShowType =  htmlspecialchars($_GET['sort']);

    try{
        $dbh=dbconnect();

        //検索にヒットするレコードを指定数分取得する。
        $sql = 'SELECT userid,username,introduction,header_img,icon_img,create_date
        FROM users';

        //検索総数を出すためのカウント
        $sql2 = 'SELECT count(userid) FROM users';
        $data = array();

        //
        if(!empty($nameSer)){
            $sql .= ' WHERE username=:u_name';
            $sql2 .= ' WHERE username=:u_name';
            $data[':u_name'] = $nameSer;
        }


        switch($slectShowType){
            case 1:
            $sql .= " ORDER BY create_date ASC";
            break;

            case 2:
            $sql .= " ORDER BY create_date DESC";
            break;

            
        }
        $stmt2 = queryPost($dbh,$sql2,$data);
        $allcount = $stmt2 -> fetch();
        debug('検索総数結果：'.print_r($allcount,true));

        
        $offset =($currentPg-1)*$slectShowNum;
        $sql .= " LIMIT {$slectShowNum} OFFSET {$offset}";
        $stmt = queryPost($dbh,$sql,$data);
        
        

        if($stmt && $stmt2){
            $arrayData= $stmt -> fetchall(PDO::FETCH_ASSOC);
            $result['total'] = $allcount['count(userid)'];
            $result['data'] = $arrayData;

            return $result;
        }
        
    }catch(Exception $e){
        debug('エラー発生:'.$e->getMessage());
    }
}

//編集画面でのデフォルトVALUEset
function editValSet($key,$dbData){
    global $err_msg;

    //DBにデータがある場合
    if(!empty($dbData)){
        //POSTされたが入力内容にエラーがある。
        if(!empty($err_msg[$key])){
            //POST内容がある。
            if(isset($_POST[$key])){
                return $_POST[$key];
            }else{
                return $dbData[$key];
            }
        }else{
            //エラーなしの入力内容がPOSTされた。
            //DBと違う内容だった場合（更新する場合）
            if(isset($_POST[$key]) && $_POST[$key] !== $dbData[$key]){
                return $_POST[$key];
            }else{
                //DBと同じ内容だった場合（入力したけど更新前と同じだった）
                return $dbData[$key];
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


function signinAs($guestid){
    $_SESSION['user_id'] = $guestid;
    $_SESSION['login_date']= time();
    $_SESSION['login_limit']=3600;
    header("location:mypage.php");
}

//===================
//削除処理
//===================

function productDel($p_id){
    debug('商品削除ボタンが押されました。');
    try{
        $dbh= dbconnect();
        $sql1= 'UPDATE products SET delete_flg = 1 WHERE productid = :p_id';

        $data = array(':p_id'=>$p_id);

        $stmt =queryPost($dbh,$sql1,$data);


        if($stmt){
            debug('商品を削除しました。商品情報：'.print_r($p_id,true));
            debug('マイページへ遷移。');
            $_SESSION['msg_suc']=SUC05;
            header("location:mypage.php");
        }else{
            debug('クエリ失敗！！');
            $err_msg['fatal']=MSG06;
        }
    }catch(Exception $e){
        debug('エラー発生：'.($e->getMessage()));
        $err_msg['fatal']= MSG06;
    }
}

//===================
//その他
//===================

//ランダムな文字列を用意する
function createAuthKey($leng = 8){
    $cahrs ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str ='';
    for($i=0;$i< $leng; $i++){
        $str .= $cahrs[mt_rand(0,61)];
    }
    return $str;
}

//ページングの関数。引数について前から、表示する要素の全ての数、現在のページ、最後のページ番号、１ページあたりの表示数。
//AllNum:表示できる要素の数、CurrentPg:閲覧中のページの番号、LastPg_count:総要素数と表示数から算出した最後のページ番号、MaxShowNum:1ページに表示する要素の数。
function paging($allNum,$currentPg,$lastPg_count,$maxShowNum){

    //最小値は必ず１
    $firstPg = 1;
    
        //基本は現在のページから±2ページ分の番号をだす
        $minPageNum=$currentPg-2;
        $maxPageNum=$currentPg+2;

        if($lastPg_count <5){
            //ページ表示数が５より少ない時は５個全てだす。
            $maxPageNum = $lastPg_count;
            $minPageNum = $firstPg;
        }elseif($minPageNum<= $firstPg){
            //ページナンバーが1を下回ってしまう場合。
            $minPageNum = 1;
            $maxPageNum = $firstPg+4;
        }elseif($maxPageNum>=$lastPg_count && $lastPg_count >=5){
            //ページナンバーが最大を上回ってしまう場合。
            $maxPageNum=$lastPg_count;
            $minPageNum = $lastPg_count-4;
        }
        
        $startNum = ($currentPg -1)*$maxShowNum +1;

        //最後のページなど、表示数(Ex:12)が要素数(Ex:6)を上回る時、最大表示数を変更する。余り＝表示する数。（0を除く）
        if($currentPg==$lastPg_count && $allNum % $maxShowNum!=0){
            $maxShowNum = $allNum % $maxShowNum;
        }

        $endNum = $startNum + $maxShowNum -1;
        
        return array(
                    "start"=>$startNum,//現在のページで表示する商品の件数（はじめ）、
                    "minPg"=>$minPageNum,//現在ページから表示する最小のページ番号
                    "maxPg"=>$maxPageNum,//現在ページから表示する最大のページ番号
                    "end"=>$endNum,//現在のページで表示する商品の件数（終わり）
                    "maxShow"=>$maxShowNum//1ページあたりの最大表示数。カードの数の調整が入った時に更新するため。
                    );
}

//検索モードと通常表示モードでのGETパラメータの付け方を変更。
function withGetPram(){
    if(!empty($_GET['st'])){
        //検索モードの時は＆にして、検索用のGETパラメータが消えないようにする。
        if(!empty($_GET['pg'])) unset($_GET['pg']);
        $serGet = "";
        foreach ($_GET as $key => $value) {
            $serGet .= "&".$key."=".$value;
        }
        return $serGet;
    }
}

//確か必要なやつ、、
function selectedEcho($key,$num){
    if(!empty($_GET) && isset($_GET[$key])){
        if($_GET[$key] == "$num"){echo "selected";
        }
    }
}

//指定文字数以上を表示しないようにする。
function hiddenOverStr($str,$max){
    if(mb_strlen($str)>$max){
        return mb_substr($str,0,$max)."...";
    }else{
        return $str;
    }
}

//FROMユーザーが発信したメッセージMSGをTO宛てに送る。
function sendMessage($to,$user,$msg){
    debug('メッセージを送信します。');
    try{
        $to = htmlspecialchars($to);
        $user = htmlspecialchars($user);
        $msg = htmlspecialchars($msg);

        $dbh =dbconnect();
        $sql = 'INSERT INTO dm(send_from,send_to,send_msg,create_time) VALUES(:sefrom, :seto, :msg,:cre_time)';
        $data = array(':sefrom'=>$user, ':seto'=>$to, ':msg'=>$msg, ':cre_time'=>date('Y-m-d H:i:s'));

        $stmt = queryPost($dbh,$sql,$data);
        if($stmt){
            debug('メッセージを送信しました。');
            $_SESSION['msg_suc'] = SUC08;
        }

    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

//どのユーザーとユーザーのDMルームかを区別するためのデータを作成、作成する。
function createMesRoom($host, $client){
    try{
        $dbh=dbconnect();
        $sql1='SELECT count(roomid) FROM dmLists WHERE (user1 = :u1 AND user2=:u2) OR (user1 = :u2 AND user2=:u1)';
        $data = array(':u1'=>$host, ':u2'=>$client);
    
        $stmt = queryPost($dbh,$sql1,$data);
        $result = $stmt -> fetch();
    
        if($result['count(roomid)']==0){
            $sql2='INSERT INTO dmLists(user1,user2) VALUES (:u3,:u4)';
            $data2 = array(':u3'=> $host, ':u4'=>$client);
            queryPost($dbh,$sql2,$data2);
            debug($host.'と：'.$client.'メッセージルームが作成されました。ユーザー：');
        }else{
            debug($host.'と：'.$client.'同士のメッセージルームは作成済みです。');
        }

    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}


function getMesRommList($host){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT * FROM dmLists WHERE user1 = :u1 OR user2=:u1';
        $data = array(':u1'=>$host);
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetchall(PDO::FETCH_ASSOC);

        debug('ユーザーID：'.$host.'が含まれているメッセージルームの情報'.print_r($result,true));
        return $result;

    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

function getMesRomm($host,$client){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT roomid FROM dmLists WHERE (user1 = :u1 AND user2=:u2) OR (user1 = :u2 AND user2=:u1)';
        $data = array(':u1'=>$host, ':u2'=>$client);
        $stmt = queryPost($dbh,$sql,$data);

        $result = $stmt -> fetch();

        debug($client.'と'.$host.'が話しているメッセージルームのID'.print_r($result,true));
        return $result;

    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

function updateMesRoom($roomid,$mes){
    try{
        $mes = htmlspecialchars($mes);
        $dbh = dbconnect();
        $sql = 'UPDATE dmLists SET last_mes = :mes, update_time = :u_time WHERE roomid = :r_id';
        $data = array(':mes'=>$mes,':r_id'=>$roomid, ':u_time'=>date('Y-m-d h:i:s'));
        $stmt = queryPost($dbh,$sql,$data);

        debug('ルームID：'.$roomid.'の内容をアップデートしました。');

    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

//商品を非公開または公開状態に変更するための関数
function updateProductState($p_id){
    try{
        $dbh = dbconnect();
        $sql = 'SELECT open_flg FROM products WHERE productid = :p_id';
        $data = array(':p_id'=> $p_id);

        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt -> fetch();

        switch($result['open_flg']){
            case 1:
                $sql2 = 'UPDATE products SET open_flg = 0 WHERE productid = :p_id';
                debug('商品ID'.$p_id.'の公開状態を「非公開」変更しました。');
                $_SESSION['msg_suc']="商品を非公開にしました。";
                break;
            case 0:
                $sql2 = 'UPDATE products SET open_flg = 1 WHERE productid = :p_id';
                debug('商品ID'.$p_id.'の公開状態を「公開」変更しました。');
                break;
        }

        $stmt2 = queryPost($dbh,$sql2,$data);
    
    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }

}

//対照ユーザーがログインユーザーであるか(セッションがセットまたは、時間オーバーでない。)))をチェックする。
function islogin(){
    if(time() > $_SESSION['login_limit']+$_SESSION['login_date']){
        debug('有効期限切れています。');
        session_destroy();
        return false;
    }elseif(empty(['user_id'])){
        debug('ログインしていません。');
        return false;
    }elseif(!empty($_SESSION['login_date'])){
        debug('ログインユーザーのです。');
        return true;
    }else{
        debug('ログインしていません。');
        return false;
    }
}

//ユーザーが対象の商品をお気に入り登録しているかを調べる。
function isFavorit($p_id){
    try{
        $p_id=htmlspecialchars($p_id);

        $dbh = dbconnect();
        $sql = 'SELECT * FROM favorite WHERE productid =:p_id AND userid = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id'=>$p_id);

        $stmt = querypost($dbh,$sql,$data);
        $resultCount = $stmt -> rowCount();

        if(!empty($resultCount)){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

//ユーザーがTargetをフォローしているかをチェックする。
function isFollow($target){
    try{
        $target=htmlspecialchars($target);

        $dbh = dbconnect();
        $sql = 'SELECT * FROM follows WHERE follow =:fl AND userid = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':fl'=>$target);

        $stmt = querypost($dbh,$sql,$data);
        $resultCount = $stmt -> rowCount();

        if(!empty($resultCount)){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

//==================
//通知設定
//==================

//通知内容をDMにセットする。、TOが送り先、FROMが送り主、TYPEは通知内容の種別わけ（DMなのか、フォローなのか等）
function set_notify($toUser,$fromUser,$type,$p_id=1){
    debug('通知内容をセットします。');
    $fromUser=  getOneUserData($fromUser,'userid, username');
    $p_data = getOneProductData($p_id,'productid, title');

    try{
        switch($type){
            case 0://フォロー通知
                $mes = $fromUser['username']."さんがあなたをフォローしました。";
                break;
    
            case 1://いいね通知
                $mes = $fromUser['username']."さんが ".$p_data['title']." をいいねしました。";
                break;
    
            case 2://DM通知
                $mes = $fromUser['username']."さんからのDMが届いています。";
                break;
        }



        $dbh= dbconnect();
        $sql = 'INSERT INTO notify(to_user, from_user, type, contents,create_time) VALUES (:to_u,:from_u, :typ, :content,:create_time)';
        $data = array(
                        ':to_u' => $toUser,
                        ':from_u' => $fromUser['userid'],
                        ':typ' => $type,
                        ':content' => $mes,
                        ':create_time'=> date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh,$sql,$data);
    


    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
    
}

//マイページに表示する通知の取得
function get_notify($u_id,$limit=5){
    try{
            $dbh = dbconnect();
            $sql = "SELECT u.userid, u.icon_img, contents,type,create_time 
                    FROM notify AS n LEFT JOIN users AS u ON n.from_user = u.userid 
                    WHERE n.to_user = :u_id 
                    ORDER BY create_time DESC 
                    LIMIT $limit ";

            $data = array(':u_id'=>$u_id);
            
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            debug('取得データ：'.print_r($result,true));

            return $result;
    }catch(Exception $e){
        debug('エラー発生；'.$e->getMessage());
    }
}

