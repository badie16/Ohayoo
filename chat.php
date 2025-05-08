<?php
global $user,$root;
require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{Discussion,UserRelation,FuncGlobal as FG};
use layouts\chat\ChatManager;
$_SESSION['pageC'] = "chat.php";
if(!$user->isLoggedIn()){
    FG::goToLocation("login.php");
}
$current_user_picture = $root.$user->getPropertyValue("picture");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat</title>
    <?php include "layouts/header.php" ?>
</head>

<body data-page="chat">
    <div class="chatMain menuV2">
        <?php include "layouts/page_parts/partTop.php"?>
        <section class="d-flex">
            <?php include "layouts/page_parts/menuLeft.php"?>
            <section class="app-center center">

                <section class="chat-left-section col-12 col-md-5 col-lg-4 col-xl-3">
                    <div class="head">
                        <div class="contactList">
                            <section class="contacts-section">
                                <div class="head d-flex justify-content-between align-items-center">
                                    <h5>New chat </h5>
                                    <i class=" btnF1 btnShowListContact fa fa-close"></i>
                                </div>
                                <div class="search-user search-f1">
                                    <label>
                                        <i class="fad fa-search"></i>
                                        <input type="text" placeholder="Enter name to search" class="active">
                                    </label>
                                </div>
                                <div class="contacts users">
                                    <?php
                                    $arrUser =  UserRelation::get_friends($user->getUserId());
                                    foreach ($arrUser as $u){
                                            $isOnline="";
                                            $time= time()- strtotime($u->getPropertyValue("last_active"));
                                            if ($time < 60){
                                                $isOnline="online";
                                            }
                                            echo <<<U
                                    <div class="user $isOnline">
                                        <div class="profile" href="#">
                                            <input class="usid" type="hidden" value="{$u->getUserId()}">
                                            <div class="position-relative">
                                                <div class="onlineDot"></div>
                                                <img src="$root{$u->getPropertyValue("picture")}" class="profile-img" alt="user-img">
                                            </div>
                                            <span class="name">{$u->fullname()}</span>
                                        </div>
                                    </div>
U; }
                                    ?>
                                </div>
                            </section>
                        </div>
                        <div class="d-flex mb-2 justify-content-between align-items-center">
                            <h2>Chats</h2>
                            <div class="option-chat-head">
                                <i class="btnF1 btnShowListContact far fa-edit">
                                </i>
                                <i class="btnF1 fa fa-bars-filter"></i>

                            </div>
                        </div>
                        <div class="search-chat search-f1">
                            <label class="relative">
                                <i class="fad fa-search"></i>
                                <input class="searchChatInput" placeholder="Search chat" type="text">
                            </label>
                        </div>
                    </div>

                    <div class="listOfMessages">
                        <?php
                            $listDiscussion = Discussion::getListOfDiscussion($user->getUserId());
                            if (count($listDiscussion)>0){
                                foreach ($listDiscussion as $disc){
                                    echo ChatManager::generateDiscussion($disc,$user);
                                }
                            }
                        ?>
                    </div>
                </section>
                <section class="chat-box col-12 col-md-7 col-lg-8 col-xl-9">
                    <?php
                        if (isset($_GET['c'])){

                        }else{
                    ?>
                    <div id="no-discussion-yet">
                        <div class="flex-justify-column" style="text-align: center">
                            <i class="fa fad fa-paper-plane mb-2 fa-5x" style="color: #fd7e14"></i>
                            <h2>You don't have a message selected</h2>
                            <p class="regular-text">Choose one from your existing messages, or start a new one.</p>
                        </div>
                    </div>
                    <?php } ?>
                </section>
            </section>
        </section>
    </div>
    <script src="asset/js/chat.js"></script>
</body>