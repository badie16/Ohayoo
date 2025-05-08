<?php
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use classes\{User, Like,FuncGlobal as FG};
if (isset($_POST["post_id"]) && $user->isLoggedIn()){
    $userId=  $user->getUserId();
    $userId = FG::sanitize_id($userId);
    if (User::user_exists("userId",$userId)){
        $post_id = $_POST["post_id"];
        $like = new Like();
        $like->setData(array(
            "post_id" => $post_id,
            "user_id" => $userId,
        ));
        $res = $like->exists();
        /*
            1: deleted successfully
            2: added successfully
           -1: there's a problem
        */
        if (!$like->exists()){
            if($like->add()) {
                echo 2;
            }
        }else{
            if($like->delete()) {
                echo 1;
            }
        }
    }else{
        echo -1;
    }
}else{
    echo -1;
}