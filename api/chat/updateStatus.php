<?php
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use layouts\chat\ChatManager;
use classes\{User,Message,FuncGlobal as FG};
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$from = FG::sanitize_id(filter_input(INPUT_POST, 'msg_from'));
$last_msg = FG::sanitize_id(filter_input(INPUT_POST, 'last_msg'));
if (!isset($_POST["code"])){
    $_POST["code"] = 0;
}
$status = FG::sanitize_id(filter_input(INPUT_POST, 'code'));
if (!$from || !$last_msg) {
    $response = [
        'code' => 4,
        'msg' => 'missing required parameters',
        'success' => false
    ];
    echo json_encode($response);
    return;
}
if (Message::exists($last_msg)){
    $msg = Message::get_msg_content_obj("msg_id",$last_msg);
    if (($msg["msg_to"] == $user->getUserId() && $msg["msg_from"] == $from)
        ||( $msg["msg_from"] == $user->getUserId() && $msg["msg_to"] == $from)
    ){
        if ($status > 1){
            $status = 1;
        }
        if(Message::updateStatusOfMsgOFUser($user->getUserId(),$from,$last_msg,$status)){
            $response = [
                'code' => 1,
                'msg' => 'success',
                'success' => true
            ];
            echo json_encode(true);
        }else{
            echo "yes";
            $response = [
                'code' => 2,
                'msg' => 'error in db',
                'success' => true
            ];
        }
    }
}

