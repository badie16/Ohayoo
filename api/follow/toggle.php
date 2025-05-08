<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{User, Follow,FuncGlobal};

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

$follower = $user->getUserId();
$followed = $_POST["current_profile_id"];
if($follower === $followed) {
    echo json_encode(
        array(
            "msg"=>"You can't follow yourself",
            "success"=>false
        )
    );
    exit();
}
if(($user->getUserId()) &&
    User::user_exists("userId", $user->getUserId())) {
    if(($followed = FuncGlobal::sanitize_id($followed)) &&
        User::user_exists("userId", $followed)) {
        $follow = new Follow();
        $follow->set_data(array(
            "follower"=>$follower,
            "followed"=>$followed
        ));
        if(Follow::follow_exists($follower, $followed)) {
            $follow->fetch_follow();
            if($follow->delete()){
                $nmbOfFollow =Follow::get_user_followers_number($followed);
                echo json_encode(
                    array(
                        "code"=>0,
                        "msg" =>"The follower with id: $follower unfollows the user with id: $followed successfully !",
                        "html"=>'<label class=" btnF2 " style="--i:var(--bs-pink);color: var(--bg-color1)">
                            <i class="fal fa-heart"></i>
                            <input type="submit" class="follow-button" value="Follow '.$nmbOfFollow.'">
                        </label>',
                        "success" =>true
                    )
                );
                return;
            }
        } else {
            // Now we know the follower id is valid as well as the followed id, now we can add it to our database
            if($follow->add()) {
                $nmbOfFollow =Follow::get_user_followers_number($followed);
                echo json_encode(
                    array(
                        "code" => 1,
                        "msg" => "user with id: " . $follower . " followed user with id: " . $followed . " successfully !",
                        "html" => '<label class="btnF2" style="--i:var(--bs-purple);color: var(--bg-color1)">
                            <i class="fa fa-heart"></i>
                            <input type="submit" class="follow-button" value="Followed '.$nmbOfFollow.'">
                        </label>',
                        "success" => true
                    )
                );
            }
        }
    } else {
        echo json_encode(
            array(
                "msg"=>"followed id is either not valid or not exists in our db",
                "success"=>false
            )
        );
    }
} else {
    echo json_encode(
        array(
            "message"=>"your id is either not valid or not exists in our db",
            "error"=>true
        )
    );
}