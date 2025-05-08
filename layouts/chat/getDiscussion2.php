<?php

global $user;
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use layouts\chat\ChatManager;
use classes\{Discussion};
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$listDiscussion = Discussion::getListOfDiscussion($user->getUserId());
$list="";
$code = count($listDiscussion);
if (count($listDiscussion) > 0){
    foreach ($listDiscussion as $disc){
        $list.= ChatManager::generateDiscussion($disc,$user);
    }
}
$response = [
    'code' => $code,
    'msg' => $list,
];
echo json_encode($response);

