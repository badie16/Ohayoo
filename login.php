<?php

use classes\FuncGlobal;
use classes\Token;

require_once "vendor/autoload.php";
require_once "core/init.php";
if($user->isLoggedIn()) {
    FuncGlobal::goToLocation("/home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Login to ohayoo</title>
        <?php include "layouts/header.php" ?>
	</head>
	<body>
        <section class="app">
            <div class="wrapper">
                <section class="form login">
                    <header>Login</header>
                    <form action="" method="post">
                        <div class="error-text">this is on error message</div>
                        <div class="field input">
                            <input type="text" name="emailORuser" id="emailORuser" placeholder=""/>
                            <label for="emailORuser">Email / User Name</label>
                        </div>
                        <div class="field input">
                            <input type="password" name="pass" id="pass" placeholder=""/>
                            <label for="pass">Password</label>
                            <i class="fa fa-eye eye"></i>
                        </div>
                        <div class="field button">
                            <input type="submit" class="btn" name="login" value="Login" />
                        </div>
                        <input type="hidden" name="token_log" value="<?php echo Token::generate("login"); ?>">
                    </form>
                    <div class="link">Not yet signed up? <a href="signup.php">Signup now</a></div>
                </section>
            </div>
        </section>
		<script src="asset//js/pass-show-hede.js"></script>
        <script src="asset/js/login.js" ></script>
	</body>
</html>
