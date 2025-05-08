<?php

global $user;
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{User, Comment,DB,FuncGlobal as FG};
use layouts\post\Post_Manager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/* json code
 * success : false/true
 * msg : message or last comment
*/
if(!isset($_POST["post_id"]) || !isset($_POST["comment_owner"])) {
    echo json_encode(array(
        "msg"=>"parameter required !",
        "success"=>false
    ));
    return;
}
if(empty($_POST["comment_text"])) {
    echo json_encode(array(
        "msg"=>"comment should not be empty or unset !",
        "success"=>false
    ));
    return;
}
$comment_owner = FG::sanitize_id($_POST["comment_owner"]);
$post_id = FG::sanitize_id($_POST["post_id"]);
$comment_text = FG::sanitize_text($_POST["comment_text"]);

$comment = new Comment();
$comment->setData(array(
    "comment_owner"=>$comment_owner,
    "post_id"=>$post_id,
    "comment_text"=>$comment_text
));
$captured_id = $comment->add();
if ($comment->fetch_comment("comment_id", $captured_id)){
    $user->fetchUser("userId",$_POST["comment_owner"]);
    $post_manager = Post_Manager::generate_comment($comment, $user);
    echo json_encode(array(
        "msg"=>$post_manager,
        "success"=>true
    ));
}

