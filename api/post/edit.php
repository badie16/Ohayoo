<?php
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{Post,FuncGlobal as FG};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if(!isset($_POST["post_id"])) {
    echo json_encode(array(
        "msg"=>"post id required !",
        "success"=>false
    ));
    return;
}
if(!isset($_POST["new_post_text"])) {
    echo json_encode(array(
        "msg"=>"new post text required !",
        "success"=>false
    ));
    return;
}
$post_id = FG::sanitize_id($_POST["post_id"]);
$new_post_text = FG::sanitize_text($_POST["new_post_text"]);

$post = new Post();
$post->fetchPost($post_id);
if ($post->getPropertyValue("post_owner") != $user->getUserId()){
    echo json_encode(array(
        "msg"=>"error you can't delete this post",
        "success"=>false
    ));
    return;
}
$post->set_property('text_content', $new_post_text);
if($post->update("text_content")) {
    echo json_encode(array(
        "msg"=>$post->getPropertyValue("text_content"),
        "success"=>true
    ));
}else{
    echo json_encode(array(
        "msg"=>"error in data base",
        "success"=>false
    ));
}

