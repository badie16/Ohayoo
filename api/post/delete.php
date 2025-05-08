<?php


require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{Post,FuncGlobal as FG};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if(!isset($_POST["post_id"])) {
    echo json_encode(array(
        "message"=>"post id required !",
        "success"=>false
    ));
    return;
}
$post_id = FG::sanitize_id($_POST["post_id"]);

if(Post::exists($post_id)) {
    if($user->getUserId() == Post::get_post_owner($post_id)['post_owner']) {
        $post = new Post();
        $post->set_property('post_id', $post_id);
        $post->delete();

        /*
            When the original post is deleted we want to edit all postst that are a shared post of that post and edit the column
            shared_post_id to empty
        */
//        $shared_posts = Post::get('post_shared_id', $post_id);
//        foreach($shared_posts as $p) {
//            $p->set_property('post_shared_id', null);
//            $test = $p->update();
//        }

        echo json_encode(array(
            "success"=>true,
            "message"=>'post deleted successfully !'
        ));
    } else {
        echo json_encode(array(
            "success"=>false,
            "message"=>'you can\' delete this post'
        ));
    }

} else {
    echo json_encode(array(
        "success"=>false,
        "message"=>'post not exists'
    ));
}

