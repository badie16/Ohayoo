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

    if (!isset($_POST["receiver"])){
        echo json_encode([
            "msg" => "receiver id required !",
            "success" => false
        ]);
        return 0;
    }
    $receiverUser = new User();
    $receiverUser->fetchUser("userId",$_POST["receiver"]);
    if (!isset($_POST["limit_msg"])) {
        $_POST["limit_msg"] = -1;
    }
    $limit_msg = FG::sanitize_id($_POST["limit_msg"]);
    $messages = Message::fetchmessages($user->getUserId(),$receiverUser->getUserId(),$limit_msg);

    $messagesCon = "";
    foreach ($messages as $msg){
        $messagesCon.=ChatManager::generate_msg($msg,$receiverUser,$user);
    }
    foreach (array_reverse($messages) as $msg){
        $limit_msg = $msg->get_property("msg_id");
    }
    echo json_encode([
        "msg"=> $messagesCon,
        "limit_msg"=>$limit_msg,
        "success"=>true
    ]);
