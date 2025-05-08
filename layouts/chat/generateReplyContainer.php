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

$msg_id = FG::sanitize_id(filter_input(INPUT_POST, 'msg_id'));
$response="";
if (!$msg_id){
    $response = [
        'code' => -1,
        'msg' => 'missing required parameters',
        'success' => false
    ];

}else{
    if(Message::exists($msg_id)){
        $msg = Message::get_msg_content_obj("msg_id",$msg_id);
        $response = [
            'code' => 0,
            'msg' => ChatManager::generateReplyContainer($msg,$user),
            'success' => true
        ];
    }else{
        $response = [
            'code' => 1,
            'msg' => 'error in msg id',
            'success' => true
        ];
    }
}
echo json_encode($response);
