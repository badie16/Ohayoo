<?php
session_start();

use classes\{Config, Cookie, DB, Session, User};

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
$session_name =  Config::get('session/session_name');
$user = new User();

if(Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))) {
    $hash = Cookie::get(Config::get("remember/cookie_name"));
    $res = DB::getInstance()->prepare("SELECT * FROM user_session WHERE token = ?", array($hash));
    if($res->count()) {
        $user->fetchUser("userId", $res->results()[0]["user_id"]);
        $user->login($user->getPropertyValue("username"),$user->getPropertyValue("password"),true);
    }
}
if(Session::exists(Config::get("session/session_name"))){
    if(!$user->fetchUser("userId",Session::get(Config::get("session/session_name")) )){
        Session::delete(Config::get("session/session_name"));
    }
}
if($user->getPropertyValue("isLoggedIn")) {
    $user->update_active();
}

/* 
IMPORTANT : 
1 - sanitize function file could not be included here because the path will be relative to the caller script
so if for example include it like following: include_once "functions/sanitize.php" only scripts in the root 
directory can use it, otherwise a fatal error will be thrown
So you should include it along with autoload and init file in every page needs it

2 - Composer autoload file also follow the same rule you can't import it here
*/