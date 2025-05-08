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
$friend = FG::sanitize_id($_POST["current_profile_id"]);
$current_user = $user->getUserId();
if($current_user === $friend) {
    echo json_encode(
        array(
            "msg"=>"You can't add yourself or cancel request",
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
        if($user_relation->send_request()) {
            echo json_encode(
                array(
                    "code"=> 1,
                    "html"=>'
                            <label class="btnF2 add-user" style="--i:var(--bs-secondary);color: var(--bg-color1)">
                                <i class="fa fa-user-minus "></i>
                                <input type="submit" class="" value="Cancel Request" >
                            </label> 
                            ',
                    "msg"=> "user with id: $current_user sends a friend request to user with id: $friend",
                    "success"=>true
                )
            );
        } else if ($user_relation->cancel_request()){
            echo json_encode(
                array(
                    "code"=>0,
                    "html"=>'
                            <label class="btnF2 add-user add-user-back"  style="--i:var(--bs-primary);color: var(--bg-color1)">
                                <i class="fa fa-user-plus "></i>
                                <input type="submit" value="Add">
                            </label>
                    ',
                    "msg"=>"user with id: $current_user cancels a request that was sent to user with id: $friend successfully !",
                    "success"=>true
                )
            );
        }else{
            echo json_encode(
                array(
                    "msg"=>"Error in base de donner",
                    "success"=>false
                )
            );
        }
    } else {
        echo json_encode(
            array(
                "message"=>"friend's id is either not valid or not exists in our db",
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