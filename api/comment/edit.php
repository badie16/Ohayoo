<?php


require_once "../../vendor/autoload.php";
require_once "../../core/restInit.php";

use classes\{Comment,FuncGlobal as FG};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if(!isset($_POST["comment_id"]) || !isset($_POST["new_comment"])) {
    echo json_encode(array(
        "msg"=>"parameter required !",
        "success"=>false
    ));
    return;
}


$comment_id = FG::sanitize_id($_POST["comment_id"]);
$new_comment = FG::sanitize_text($_POST["new_comment"]);

$comment = new Comment();
$comment->fetch_comment("comment_id",$comment_id);

$comment->set_property("comment_text", $new_comment);

if($comment->update()) {
    echo json_encode(array(
        "msg"=>$new_comment,
        "success"=>true
    ));
} else {
    echo json_encode(array(
        "msg"=>false,
        "success"=>true
    ));
}