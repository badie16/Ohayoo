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
$code = FG::sanitize_id($_POST["code"]);
if (!isset($_POST["token"]) || !$code){
    echo json_encode([
        "msg" => "parameter required !",
        "success" => false
    ]);
    return ;
}
$disc = new Discussion();
$disc->fetchDiscussion("room_token",$_POST["token"]);
$room_id = $disc->get_property("room_id");
if ($room_id){
    if ($code == 1) {
        if ($disc->get_property("user1_id") == $user->getUserId()) {
            $disc->set_property("statusU1", 1);
            $disc->set_property("lastStatusU1", date("Y-m-d H:i:s"));
            if ($disc->update_property("statusU1")) {
                $disc->update_property("lastStatusU1");
            }
        } else if ($disc->get_property("user2_id") == $user->getUserId()){
            $disc->set_property("statusU2", 1);
            $disc->set_property("lastStatusU2", date("Y-m-d H:i:s"));
            if ($disc->update_property("statusU2")) {
                $disc->update_property("lastStatusU2");
            }
        }else{
            echo json_encode([
                "msg" => "you can't delete this room",
                "success" => true
            ]);
            return;
        }
        echo json_encode([
            "msg" => "success : disc is deleted",
            "success" => true
        ]);
    }
}else{
    echo json_encode([
        "msg" => "error in token ",
        "success" => false
    ]);
}