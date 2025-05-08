<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use layouts\chat\ChatManager;
use classes\{Discussion, User, Message, FuncGlobal as FG};
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$from = FG::sanitize_id(filter_input(INPUT_POST, 'from'));
$to = FG::sanitize_id(filter_input(INPUT_POST, 'to'));
$lastMsg= FG::sanitize_num(filter_input(INPUT_POST, 'last_msg', FILTER_VALIDATE_INT));
if (!$from || !$to ) {
    $response = [
        'code' => 4,
        'msg' => 'missing required parameters',
        'success' => false
    ];
    echo json_encode($response);
    exit;
}
// Define an array of non-text message types
$nonTextTypes = array('1', '2', '3');

//// Build an array of message objects
$listNewMsg = Message::fetchNewMessages($from,$to,$lastMsg);
$isTyping = Discussion::exit_writing_message_notifier($to,$from);
// get ths status of the last msg
if (count($listNewMsg) > 0) {
    $receiverUser = new User();
    $receiverUser->fetchUser("userId",$to);
    // Return the array of message objects
    $messages = array();
    $lastMsg = 0;
    foreach ($listNewMsg as $msg){
        $messages[] = ChatManager::generate_msg($msg,$receiverUser,$user);
        $lastMsg = $msg->get_property("msg_id");
    }
    $response = [
        'code' => 0,
        'msg' => 'new msg',
        'success' => true,
        'data' => $messages,
        'last_msg'=>$lastMsg,
        '$isTyping'=>$isTyping,
        "msgStatusContainer"=>ChatManager::getStatusOfMsgF1($from,$lastMsg)
    ];
    echo json_encode($response);
} else {

    if(!Message::exists($lastMsg)){
        $lastMsg = Message::getLastMsgExit($to,$from);
    }
    // No messages found
    $response = [
        'code' => 3,
        'msg' => 'no messages found',
        'success' => true,
        'last_msg'=>$lastMsg,
        'isTyping'=>$isTyping,
        "msgStatusContainer"=>ChatManager::getStatusOfMsgF1($from,$lastMsg)
    ];
    echo json_encode($response);
}


