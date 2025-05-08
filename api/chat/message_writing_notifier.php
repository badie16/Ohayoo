<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{Discussion, User, Message, FuncGlobal as FG};
use layouts\chat\ChatManager;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$to = FG::sanitize_id($_POST["to_id"]);
$code = FG::sanitize_id($_POST["code"]);
// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if (($to) &&
    User::user_exists("userId", $to)) {
    if ($code == 0){
        $msg = Discussion::add_writing_message_notifier($user->getUserId(),$to);
        if ($msg){
            $code = 1;
        }
    }else {
        $msg = Discussion::delete_writing_message_notifier($user->getUserId(),$to);
        if ($msg){
            $code = 0;
        }
    }
    echo json_encode(
        array(
            "code" => $code,
            "success" => true
        )
    );
} else {
    echo json_encode(
        array(
            "msg" => "receiver id required Or not exit ! ",
            "success" => false
        )
    );
}