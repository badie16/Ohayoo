<?php


require_once "../../vendor/autoload.php";
require_once "../../core/restInit.php";

use classes\{Comment,FuncGlobal as FG};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if (!isset($_POST["comment_id"])) {
    echo json_encode(array(
        "msg" => "comment id required !",
        "success" => false
    ));
    return;
}

$comment_id = FG::sanitize_id($_POST["comment_id"]);

$comment = new Comment();
$comment->fetch_comment("comment_id", $comment_id);
$postId = $comment->getPropertyValue("post_id");
$numberOfComments = 0;
$a = $comment->delete();
if ($a){
    $numberOfComments = Comment::count_post_comments($postId);
}
echo json_encode(array(
    "msg" => $a,
    "success" => true,
    "numberOfComments" => $numberOfComments
));