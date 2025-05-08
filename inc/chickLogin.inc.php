<?php
global $user;
require_once "../vendor/autoload.php";
require_once "../core/init.php";
use classes\DB;
use classes\{Token,FuncGlobal as FG};

$db = DB::getInstance();
$pass = mysqli_real_escape_string($db->conn(),$_POST["pass"]);
$emailORuser = mysqli_real_escape_string($db->conn(),$_POST["emailORuser"]);
//$pass = mysqli_real_escape_string($conn,$_POST[""]);
if (Token::check(FG::getInput($_POST, "token_log"), "login")) {
    if (!empty($pass) && !empty($emailORuser)) {
        $sql = "SELECT * FROM `users` WHERE `email` = ? OR `userName` = ?";
        $db->prepare($sql, array($emailORuser, $emailORuser));
        if ($db->count() > 0) {
            $row = $db->results()[0];
            $hash = $row["pass"];
            if (password_verify($pass, $hash)) {
//                $_SESSION["userId"] = $row["userId"];
                $remember = isset($_POST["remember"]);
//                $user->fetchUser("userId",$row["userId"]);
                $log = $user->login(FG::getInput($_POST, "emailORuser"), $pass, true);
                FG::setupFoldersOfUser($row["userId"]);
                if ($log) {
                    //FG::goToLocation("home.php");
                    echo "success";
                }else{
                    echo "reload";
                }
            } else {
                echo "Email or Password incorrect";
            }
        } else {
            echo "Email or Password incorrect";
        }
    } else {
        echo "All input field are required!";
    }
}else{
    echo "Invalide csrf token";
}
?>
