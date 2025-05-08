<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{User, UserRelation,FuncGlobal as FG};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if(!isset($_POST["current_profile_id"])) {
    echo json_encode(
        array(
            "msg"=>"missing required parameters !",
            "success"=>false
        )
    );
    exit();
}

$current_user = $user->getUserId();
$friend = FG::sanitize_id($_POST["current_profile_id"]);
if($current_user === $friend) {
    echo json_encode(
        array(
            "msg"=>"You can't unfriend yourself",
            "success"=>false
        )
    );
    exit();
}

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($current_user) &&
    User::user_exists("userId", $current_user)) {
    // Same check here with the followed user
    if($friend &&
        User::user_exists("userId", $friend)) {
        $user_relation = new UserRelation();
        $user_relation->set_property("from_u", $current_user);
        $user_relation->set_property("to_u", $friend);
        if($user_relation->unfriend()) {
            echo json_encode(
                array(
                    "msg"=>"user with id: $current_user unfriends user with id: $friend successfully",
                    "success"=>true,
                )
            );
        } else {
            echo json_encode(
                array(
                    "msg"=>"some invalide data is provided",
                    "success"=>false
                )
            );
        }
    } else {
        echo json_encode(
            array(
                "msg"=>"friend's id is either not valid or not exists in our db",
                "success"=>false
            )
        );
    }
} else {
    echo json_encode(
        array(
            "message"=>"your id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}