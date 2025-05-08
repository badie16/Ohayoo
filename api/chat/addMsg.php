<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{Discussion, Post, User, Message, FuncGlobal as FG};
use layouts\post\Post_Manager;
var_export($_POST);
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if (!isset($_POST["msg_from"]) && !isset($_POST["msg_to"]) && !isset($_POST["msg_content"])){
    echo json_encode([
        "msg" => "missing required parameters !",
        "success" => false
    ]);
    return 0;
}
$arrType = [0,1,2];
if (!isset($_POST["msg_type"])){
    $_POST["msg_type"] = 0;
}
$msg_from = FG::sanitize_id($_POST["msg_from"]);
$msg_to = FG::sanitize_id($_POST["msg_to"]);
$msg_content = FG::sanitize_text($_POST["msg_content"]);
$msg_type=  FG::sanitize_id($_POST["msg_type"]);

if ($msg_content == "" && $msg_type == 0){
    echo json_encode([
        "msg" => "msg is empty!",
        "success" => false
    ]);
    return 0;
}
if (!in_array($msg_type,$arrType)){
    $msg_type = 0;
}

if ($msg_type == 2){
    if(isset($_FILES["mediaRecorder"]) && !$_FILES["mediaRecorder"]['error']){
        $src = $msg_from.'user'.FG::unique().".ogg";
        move_uploaded_file($_FILES["mediaRecorder"]['tmp_name'], "../../upload/chat/".$src);
        $msg_content ="/upload/chat/".$src;
    }else{
        echo json_encode([
            "msg" => "error in media Recorder",
            "success" => false
        ]);
    }
}

$msg = new Message();
$msg->set_data([
    "msg_from"=>$msg_from,
    "msg_to"=>$msg_to,
    "msg_content"=>$msg_content,
    "msg_type"=>$msg_type
]);

if (isset($_POST["replyId"])){
    $replyId = FG::sanitize_id($_POST["replyId"]);
    if ($replyId){
        $msg->set_property("reply",$replyId);
    }
}
$r = $msg->add();
$disc = new Discussion();
var_export($disc->initialiseDiscussion($msg_from,$msg_to));
echo json_encode([
    "msg" => $r,
    "success" => true
]);


