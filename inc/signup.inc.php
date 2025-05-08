<?php
    if (empty($_POST)){
        return;
    }
    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

use classes\{DB, FuncGlobal as FG, Token, User};


    $db = DB::getInstance();
    $pass = $db->mysqli_r_e_s($_POST["pass"]);
    $fName = $db->mysqli_r_e_s($_POST["fName"]);
    $lName = $db->mysqli_r_e_s($_POST["lName"]);
    $email = $db->mysqli_r_e_s($_POST["email"]);
    $userName = $db->mysqli_r_e_s($_POST["user"]);
    $gender = $db->mysqli_r_e_s($_POST["gender"]);
    $DateBirth  = mktime(0,0,0,$_POST['dateM'], $_POST['dateD'], $_POST['dateY']);
    $output = array();
if (Token::check(FG::getInput($_POST, "token_reg"), "register")) {
    if (!empty($pass) && !empty($email) && !empty($fName) && !empty($lName) && !empty($userName)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT email FROM users WHERE email = ?";
            $db->prepare($sql, array($email));
            if ($db->count() > 0) {
                $output = FG::arrayJsonF1("e", "Email already exist", 2);
            } else {
                $sql = "SELECT userName FROM users WHERE userName = ?";
                $db->prepare($sql, array($userName));
                if ($db->count() <= 0) {
                    $status = 1;
                    $userId = rand(time(), 1000000000);
                    $user_picture = "/upload/profile/user-" . strtolower($gender) . ".png";
                    $passHash = password_hash($pass, PASSWORD_ARGON2I);
                    $sql = "INSERT INTO `users`
                    (`userId`,`fName`, `lName`, `pass`, `email`, `gender`, `status`,`dateBirth`,`userName`,`imgP`) VALUES 
                    ('$userId','$fName','$lName','$passHash','$email','$gender','$status','$DateBirth','$userName','$user_picture')";
                    if ($db->query($sql)) {
                        $sql = "SELECT * FROM users WHERE email = ?";
                        $db->prepare($sql, array($email));
                        if ($db->count() > 0) {
                            $row = $db->results()[0];
                            //$_SESSION["userId"] = $row["userId"];
                            $log = $user->login($email, $pass);
                            FG::setupFoldersOfUser($userId);
                            $output = FG::arrayJsonF1("s", "success", 100);
                        }
                    } else {
                        $output = FG::arrayJsonF1("e", "Something went wrong!", -1);
                    }
                } else {
                    $output = FG::arrayJsonF1("e", "User name already exist", 3);
                }
            }
        } else {
            $output = FG::arrayJsonF1("e", "Ops email is not valid", -1);
        }
    } else {
        $output = FG::arrayJsonF1("e", "All input field are required!", 0);
    }
}else{
    $output = FG::arrayJsonF1("e", "Invalide csrf token!", -100);
}
echo json_encode($output);


