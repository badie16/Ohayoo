<?php
global $user;
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use classes\{FuncGlobal as FG, Validation};

if($user->isLoggedIn()) {
    $validate = new Validation();
    $validate->check($_POST, array(
        "firstname"=>array(
            "name"=>"firstname",
            "required"=>true,
            "min"=>2,
            "max"=>40
        ),
        "lastname"=>array(
            "name"=>"lastname",
            "required"=>true,
            "min"=>2,
            "max"=>40
        ),
//            "private"=>array(
//                "name"=>"Profile (public/private)",
//                "range"=>array(-1, 1)
//            )
    ));

    if(!empty($_FILES["picture"]["name"])) {
        $validate->check($_FILES, array(
            "picture"=>array(
                "name"=>"picture",
                "image"=>"image"
            )
        ));
    }
    if(!empty($_FILES["cover"]["name"])) {
        $validate->check($_FILES, array(
            "cover"=>array(
                "name"=>"cover",
                "image"=>"image"
            )
        ));
    }
    if($validate->passed()) {
        // Set textual data
        $user->setPropertyValue("firstname", $_POST["firstname"]);
        $user->setPropertyValue("lastname", $_POST["lastname"]);
        $user->setPropertyValue("bio", $_POST["bio"]);
//            $user->setPropertyValue("private", $_POST["private"]);
        if (!file_exists("../../upload/users/media/cover") || !file_exists("../../upload/users/media/picture")){
            FG::setupFoldersOfUser($user->getUserId(),"../..");
        }
        $profilePicturesDir = '/upload/users/' . $user->getUserId() . "/media/pictures/";
        $coversDir = '/upload/users/' . $user->getUserId() . "/media/covers/";
        // First we check if the user is changed the image
        if(!empty($_FILES["picture"]["name"])) {
            // If so we generate a unique hash to name the image
            $generatedName = FG::unique();
            $generatedName = htmlspecialchars($generatedName);

            // Then we fetch the image type t o concatenate it with the generated name
            $file = $_FILES["picture"]["name"];
            $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);
            $targetFile = $profilePicturesDir . $generatedName . $original_extension;
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], "../../".$targetFile)) {
                $user->setPropertyValue("picture", $targetFile);
            } else {
                $validate->addError("Sorry, there was an error uploading your profile picture.");
            }
        }
        if(!empty($_FILES["cover"]["name"])) {
            $generatedName = FG::unique();
            $generatedName = htmlspecialchars($generatedName);
            $file = $_FILES["cover"]["name"];
            $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);

            $targetFile = $coversDir . $generatedName . $original_extension;
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], "../../".$targetFile)) {
                $user->setPropertyValue("cover", $targetFile);

            } else {
                $validate->addError("Sorry, there was an error uploading your profile cover.");
            }
        }
        if(!$user->update()){
            $validate->addError("Sorry, profile not update");
        }
    }
    if ($validate->passed()){
        echo json_encode(
            array(
                "msg"=>"profile is update successfully",
                "cover"=>$root.$user->getPropertyValue("cover"),
                "picture"=>$root.$user->getPropertyValue("picture"),
                "success"=>true
            )
        );

    }else{
        echo json_encode(
            array(
                "msg"=>$validate->errors(),
                "success"=>false,
                "code"=>0
            )
        );
    }
}else{
    echo json_encode(
        array(
            "msg"=>"missing required parameters !",
            "success"=>false,
            "code"=>-1
        )
    );
}
exit();
