<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use classes\Post;
use layouts\post\Post_Manager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($user->isLoggedIn()) {
    $post = Post::get_last_postOfUser($user->getUserId());
    $p = new Post();
    $p->fetchPost($post["post_id"]);
    $post_component = new Post_Manager();
    $post_component = $post_component->generate_post($p, $user);

    echo $post_component;
}