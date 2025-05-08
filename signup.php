<?php

global $user;

use classes\{Token,FuncGlobal as FG};
require_once "vendor/autoload.php";
require_once "core/init.php";
if($user->isLoggedIn()){
    FG::goToLocation("/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Signup to ohayoo </title>
        <?php include "layouts/header.php" ?>
    </head>
	<body >
        <section class="app">
            <div class="wrapper">
                <section class="form signup">
                    <header>Sign up</header>
                    <form action="" enctype="multipart/form-data" class="formSignUp" method="post">
                        <div class="error-text"></div>
                        <div class="name ParteOne">
                            <div class="field input">
                                <label for="firstName"></label><input
                                    type="text"
                                    name="fName"
                                    id="firstName"
                                    placeholder="First Name"
                                />
                            </div>
                            <div class="field input">
                                <label for="lastName"></label><input
                                    type="text"
                                    name="lName"
                                    id="lastName"
                                    placeholder="Last Name"
                                />
                            </div>
                        </div>
                        <div class="ParteTwo">
                            <div class="field input">
                                <label for="userName"></label><input
                                    type="text"
                                    name="user"
                                    id="userName"
                                    placeholder="User Name"
                                />
                            </div>
                            <div class="field input">
                                <label for="email"></label><input
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="email Address"
                                />
                            </div>
                            <div class="field input">
                                <label for="pass"></label><input
                                type="password"
                                name="pass"
                                id="pass"
                                placeholder="New password"
                            />
                            <i class="fa fa-eye eye"></i>
                        </div>
                        </div>
                        <div class="ParteTree">
                            <div class="field dateBirth">
                                <p for="">Date of Birth</p>
                                <div>
                                    <select name="dateD" id="dateD"></select>
                                    <select name="dateM" id="dateM">
                                        <option value="1">Jan</option>
                                        <option value="2">Feb</option>
                                        <option value="3" selected="1">Mar</option>
                                        <option value="4">Apr</option>
                                        <option value="5">May</option>
                                        <option value="6">Jun</option>
                                        <option value="7">Jul</option>
                                        <option value="8">Aug</option>
                                        <option value="9">Sep</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                    </select>
                                    <select name="dateY" id="dateY"></select>
                                </div>
                            </div>
                            <div class="field gender">
                                <p for="">Gender</p>
                                <div>
                                    <label for="female">
                                        <input type="radio" id="female" checked name="gender" value="Female" />
                                        Female
                                    </label>
                                    <label for="male">
                                        <input type="radio" id="male" name="gender" value="Male" />
                                        Male
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="field image">
                            <label for="img">Select Image</label>
                            <input type="file" name="img" id="img" />
                        </div> -->
                        <div class="field button">
                            <input
                                    type="button"
                                    class="btn next"
                                    name="next"
                                    value="Next"
                                    data-next="0"
                            />
                            <input
                                    type="submit"
                                    class="btn signUpBtn"
                                    name="signup"
                                    value="Sing Up"
                            />
                        </div>
                        <input type="hidden" name="token_reg" value="<?php echo Token::generate("register"); ?>">
                    </form>
                    <div class="link">
                        Already signed up ? <a href="login.php">Login now</a>
                    </div>
                </section>
            </div>
        </section>
		<script src="asset/js/pass-show-hede.js"></script>
		<script src="asset/js/signup.js"></script>
	</body>
</html>
