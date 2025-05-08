<?php
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use classes\{Post, FuncGlobal as FG, Session};
use layouts\post\Post_Manager;
if (isset($_POST["limitPost"]) && $user->isLoggedIn()) {
    $limitPost = $_POST["limitPost"];
    if (isset($_POST["userProfile"])){
        $userProfile = FG::sanitize_id($_POST["userProfile"]);
        $posts = Post::fetchPostsOfUser($userProfile,$user->getUserId(),$limitPost);
    }else{
        $posts = Post::fetch_journal_posts($user->getUserId(),$limitPost);
    }
    $output = "";
    if (count($posts) == 0) {
        $output =  FG::arrayJsonF1("s","-1",$limitPost);
    } else {
        $generatePost = "";
        foreach ($posts as $post) {
            $post_view = new Post_Manager();
            $generatePost .=  $post_view->generate_post($post, $user);
            $limitPost = $post->post_id;
        }
        $output =  FG::arrayJsonF1("s",$generatePost,$limitPost);

    }
    echo json_encode($output);
}