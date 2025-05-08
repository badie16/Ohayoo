<?php


require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{Message,FuncGlobal as FG};
use layouts\chat\ChatManager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (!isset($_POST["msg_id"])){
    echo "".json_encode([
        "msg" => "data required !",
        "success" => false
    ]);
    return ;
}else{
    $message_id = FG::sanitize_id($_POST["msg_id"]);
    if(Message::exists($message_id)) {
        $message_manager = new Message();
        $message_manager->fetchmessage("msg_id", $message_id);
        if ($user->getUserId() !== Message::get_creator_by_msg_id($message_id)["msg_from"]){
            echo json_encode([
                "success"=>false,
                "message"=>'you cant\' this msg'
            ]);
        }else{
            if ($message_manager->delete_sended_msg_content()){
                echo json_encode([
                    "success"=>true,
                    "code"=>1,
                    "message"=>'message deleted successfully !'
                ]);

            }else{
                echo json_encode([
                    "success"=>true,
                    "code"=>2,
                    "message"=>'message not deleted !'
                ]);
            }
        }
    } else {
        echo json_encode([
            "success"=>false,
            "message"=>'message not exists'
        ]);
    }
}

