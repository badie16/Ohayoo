<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use classes\{Post,User,FuncGlobal as FG};
if($user->isLoggedIn()){
    var_export($_POST);
    $userId = $user->getUserId();
    $text_content = FG::sanitize_text($_POST["postText"]);
    $post_visibility = FG::sanitize_text($_POST["visible"]);
    $supported_video_extensions = array(".mp4", ".mov", ".wmv", ".flv", ".avi", ".avchd", ".webm", "mkv");
    $supported_image_extensions = array(".png", ".jpg", ".jpeg", ".gif");
    if(!empty($_FILES)) {
        foreach($_FILES as $file) {
            $fileName = $file["name"];
            $ext = strtolower(FG::get_extension($fileName));
            echo $ext;
            if(!in_array($ext, $supported_video_extensions) && !in_array($ext, $supported_image_extensions)) {
                $errorInMedia = true;
            }
        }
    }
    if (!isset($errorInMedia)){
        $user_id_exists = User::user_exists("userId", $userId);
        if ($user_id_exists){
            $user = new User();
            $user->fetchUser("userId", $userId);

            $post_id = uniqid('', true);
            $post = new Post();
            $post_media_arr =array();
            $posts_path = "../../upload/post/";
            $post_path_media = "/upload/post/";
            if(!file_exists($posts_path)) {
                mkdir($posts_path, 0777, true);
                echo "mkdir yes";
            }else{
                echo "mkdir non";
            }
            foreach($_FILES as $file) {
                $fileName = $file["name"];
                $ext = strtolower(FG::get_extension($fileName));
                if(in_array($ext, $supported_video_extensions)) {
                    $type = "video";
                }else if(in_array($ext, $supported_image_extensions)){
                    $type = "img";
                }
                $post_media_arr[$fileName]=array(
                    "post_id" => $post_id,
                    "type"=> $type,
                    "url"=> $post_path_media.$post_id.FG::unique().$ext
                );
                if(!empty($file["name"])) {
                    if(!move_uploaded_file($file["tmp_name"], "../..".$post_media_arr[$fileName]["url"])) {
                        echo ("Sorry, there was an error uploading your post picture.");
                    }
                }
            }
            $post->setPostFromArray(array(
                "post_owner"=> $userId,
                "post_visibility"=> $post_visibility,
                "post_date"=> date("Y/m/d H:i:s"),
                "text_content"=> $text_content,
                "id_unique"=>$post_id,
                "post_media"=>$post_media_arr
            ));
            if($post->setDataToDb($post)){
                echo "success";
            }
        }

    }else{
        echo "error";
    }
}else{
    echo "pas de user";
}