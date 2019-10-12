<?php
    $local_session_path="/var/tmp/";
    $real_session_path="/home/junji1996/english-protocol.net/xserver_php/session";

    if($_SERVER['HTTP_HOST']=='localhost:8888'){
        $dataBase_name = 'mysql:dbname=tabi_picture; host=localhost; charset=utf8';
        $user_name = 'root';
        $server_pass = 'root';
    }else{
        $dataBase_name = 'mysql:dbname=tabi_picture; host=localhost; charset=utf8';
        $user_name ='sample';
        $server_pass = 'saple';
    }
?>