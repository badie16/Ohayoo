<?php
global $user;
require_once "../../vendor/autoload.php";
    require_once "../../core/init.php";
    use classes\{Like,FuncGlobal as FG};
    if (isset($_GET["post_id"])){
        $like_manager = new Like();
        $likes_count = count($like_manager->get_post_users_likes_by_post($_GET["post_id"]));
        $likes_count = FG::sanitize_num($likes_count);
        $like_manager->setData(array(
            "user_id"=>$user->getUserId(),
            "post_id"=>$_GET["post_id"]
        ));
        echo json_encode(array(
            "numberOfLikes"=>$likes_count,
            "curentUserIsLikePost"=>$like_manager->exists()
        ));
    }