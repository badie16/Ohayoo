<?php


require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use classes\{FuncGlobal as FG, Post, User};
use layouts\post\Post_Manager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$post_id = null;
if(!isset($_POST["post_id"])) {
    echo 0;
}

$post_id = FG::sanitize_id($_POST["post_id"]);

$post = new Post();
$post->fetchPost($post_id);
$post_component = new Post_Manager();
$post_component = $post_component->generate_post($post, $user);

echo $post_component;
