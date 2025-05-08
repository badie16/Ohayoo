<?php
use classes\{Token,Session,FuncGlobal};
    if(isset($_POST["logout"])) {
        if(Token::check(FuncGlobal::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            FuncGlobal::goToLocation("/login.php");
        }
    }
    if(Session::exists("logout"))
        $token_logout  =  Session::get("logout");
    else {
        $token_logout =  Token::generate("logout");
    }
    $selfAction = htmlspecialchars($_SERVER["PHP_SELF"]);
    echo <<<He
    <header class="main-header">
            <div class="d-flex">
                <div class="left col-6 d-flex">
                    <a href="$root/home.php" class="logo">
                        <img src="$root/asset/img/logo.svg" alt="">
                        <h3>Ohayoo</h3>
                        <i class="logoicon"></i>
                    </a>
                </div>
                <div class="right col-6 d-flex justify-content-end align-items-center" >
                    <!-- <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" placeholder="Search for creators">
                    </div> -->
                    <ul class="d-flex menu-icon align-items-center">
                        <li class="create">
                            <a href="#" class="active" style="--i:blue;" data-active=".notification-menu" onclick="toggleActiveClass(this)">
                                <i class="fal fa-bell"></i>
                                <span style="--i:8px;" ></span>
                            </a></li>
                        <li class="create">
                            <a href="$root/chat.php" class="active " style="--i:orange;">
                                <i class="fad fa-paper-plane" style="--"></i>
                                <span style="--i:5px;"></span>
                            </a>
                        </li>
                        <li class="create">
                            <a href="#">
                                <i class="fal fa-plus-square"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="profile">
                        <div class="profile-img  btnShow btn-more-option" data-active=".profile-menu" onclick="toggleActiveClass(this)">
                            <img class="imgF1" src="$current_user_picture" alt="profile">
                        </div>                        
                    </div>
                </div>
            </div>
        </header> 
    <div class="headerHd">
        <div class="close-menu-V2"></div>
        <div class="uk-dropdown d-blok profile-menu menu-option-event-V2">                               
            <a href="$root/u/profile.php?u={$user->getPropertyValue("username")}">
                <div class="d-flex align-items-center gap-2">
                    <img class="imgF1" src="$current_user_picture" alt="">
                    <div class="info">
                        <h4 class="">{$user->fullName()}</h4>
                        <h6 class="">@{$user->getPropertyValue("username")}</h6>
                    </div>
                </div>
            </a>
            <hr>
            <nav class="menu-option">        
                <a class="ele" href="setting.html">
                        <i class="fal fa-gear-complex"></i>
                        My Account                                       
                </a>
                <a class="ele">
                    <i class="fal fa-moon" role="img"></i>
                    Dark Mode
                    <label class="switch cursor-pointer ms-auto"> <input type="checkbox" checked=""><span class="switch-button !relative"></span></label>
                </a>
                <hr class="-mx-2 my-2 dark:border-gray-600/60">
                <button  class="ele"  name="logout" type="submit" form="logout-form">  
                                                       
                    <i class="fal fa-sign-out"></i>
                    Log Out
                                                         
                </button>
                <form action='$selfAction' method="post" class="d-none" id="logout-form">
                    <input type="hidden" name="token_logout" value="$token_logout">                                         
                </form> 
            </nav>
        </div>
        
        <div class="dropdown-menu notification-menu menu-option-event-V2 dropdown-menu-end p-4 rounded-3 border-0 shadow-lg " >               
                <h4 class="mb-4 ">Notification</h4>
                <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3">
                    <img src="images/user-8.png" alt="user" class=" position-absolute start-0">
                    <h5 >Hendrix Stamp <span class=""> 3 min</span></h5>
                    <h6 class="">There are many variations of pass..</h6>
                </div>                
            </div>
    </div>    
        <script>
            function  toggleActiveClass(e){              
                $(".headerHd .menu-option-event-V2").each(function (k,el){
                    if(el !== e.target){
                        $(".headerHd").removeClass("open");
                    }
                })            
                $(".headerHd").toggleClass("open");
                $(e.dataset.active).toggleClass("open");              
            } 
        </script>
    He;
