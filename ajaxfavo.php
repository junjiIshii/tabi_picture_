<?php 
    require_once('functions.php');

    debug('======Ajax通信開始======');
    debugLogStart();
    

    //Ajax通信内容
    if(!empty($_POST['productid']) && isset($_SESSION['user_id']) && islogin()){
        $p_id = $_POST['productid'];
        debug('お気に入り商品登録POST有り：'.$p_id);
        try{
            $p_id = htmlspecialchars($p_id);
            $dbh = dbconnect();
            $sql = 'SELECT * FROM favorite WHERE productid =:p_id AND userid = :u_id';
            $data = array(':u_id' => $_SESSION['user_id'], ':p_id'=>$p_id);

            $stmt = querypost($dbh,$sql,$data);
            $resultCount = $stmt -> rowCount();
            debug('検索結果：'.$resultCount);

            if(!empty($resultCount)){
                $sql = 'DELETE FROM favorite WHERE productid =:p_id AND userid = :u_id';
                $stmt = querypost($dbh,$sql,$data);
            }else{
                $sql = 'INSERT favorite (userid,productid,create_date) VALUES (:u_id, :p_id, :date)';
                $data = array(':u_id' => $_SESSION['user_id'], ':p_id'=>$p_id, ':date'=>date('Y-m-d H:i:s'));
                $stmt = querypost($dbh,$sql,$data);
            }
        }catch(Exception $e){
            debug('エラー発生；'.$e->getMessage());
        }
    }

    if(!empty($_POST['userid']) && isset($_SESSION['user_id']) && islogin()){
        $target = htmlspecialchars($_POST['userid']);
        debug($target.'へのフォローアクション：');
        try{
            $dbh = dbconnect();
            $sql ='SELECT * FROM follows WHERE userid = :u_id AND follow = :tgt';
            $data = array(':u_id' => $_SESSION['user_id'], ':tgt'=>$target);
            
            $stmt = querypost($dbh,$sql,$data);
            $resultCount = $stmt -> rowCount();
            debug('検索結果：'.$resultCount);

            if(!empty($resultCount)){
                $sql = 'DELETE FROM follows WHERE userid = :u_id AND follow = :tgt';
                $stmt = querypost($dbh,$sql,$data);
            }else{
                $sql = 'INSERT follows (userid,follow) VALUES (:u_id, :tgt)';
                $stmt = querypost($dbh,$sql,$data);
            }
        }catch(Exception $e){
            debug('エラー発生；'.$e->getMessage());
        }
        }
    

?>