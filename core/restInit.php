<?php
$GLOBALS["config"] = array(
    "mysql" => array(
        'host'=>'localhost',
        'username'=>'root',
        'password'=>'',
        'db'=>'ohayoo'
    ),
    "remember"=> array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    "session"=>array(
        'session_name'=>'userId',
        "token_name" => "token",
        "tokens"=>array(
            "register"=>"register",
            "login"=>"login",
            "reset-pasword"=>"reset-pasword",
            "saveEdit"=>"saveEdit",
            "share-post"=>"share-post",
            "logout"=>"logout"
        )
    ),
    "root"=> array(
        'path'=> str_contains(strtolower($_SERVER["SERVER_PROTOCOL"]),"https") ?"https":'http'.'://' .$_SERVER["HTTP_HOST"].'/ohayoo',
        'project_name'=>"ohayoo"
    )
);

$root = $GLOBALS["config"]["root"]["path"];
$proj_name = $GLOBALS["config"]["root"]["project_name"];