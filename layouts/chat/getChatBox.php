<?php
    require_once "../../vendor/autoload.php";
    require_once "../../core/init.php";

    use layouts\chat\ChatManager;
    use classes\{Discussion, User, Message, FuncGlobal as FG};
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/html;");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    if (!isset($_POST["receiver"])){
        echo json_encode([
            "msg" => "receiver id required !",
            "success" => false
        ]);
        return ;
    }
    $receiverUser = new User();
    $receiverUser->fetchUser("userId",$_POST["receiver"]);
    if (!isset($_POST["limit_msg"]))
         $_POST["limit_msg"] = -1;

    $limitPost = FG::sanitize_id($_POST["limit_msg"]);
    $messages = Message::fetchmessages($user->getUserId(),$receiverUser->getUserId(),$limitPost);
    $last_msg=0;
    $messagesCon = "";
    foreach ($messages as $msg){
        $messagesCon.=ChatManager::generate_msg($msg,$receiverUser,$user);
        $last_msg = $msg->get_property("msg_id");
    }
    foreach (array_reverse($messages) as $msg){
        $limitPost = $msg->get_property("msg_id");
    }
    $last_msgObjArr= Message::get_msg_content_obj("msg_id",$last_msg);
    $msg_statusC="";
    if ($last_msgObjArr){
        $msg_status = $last_msgObjArr["msg_status"];
        if ($last_msgObjArr["msg_to"] === $receiverUser->getUserId())
            if ($msg_status >= 1){
                $msg_statusC.="<i class='bi-check-all'></i><span>seen</span>";
            }
    }
    $isTypingClass="";
    $isTyping = Discussion::exit_writing_message_notifier($receiverUser->getUserId(),$user->getUserId());
    if ($isTyping){
        $isTypingClass="active";
        Discussion::delete_writing_message_notifier($receiverUser->getUserId(),$user->getUserId());
    }
    $lastActiveClass ="";
    $lastActive = $receiverUser->getPropertyValue("last_active");
    $time = FG::getDateForm2($lastActive);

    if ($time == "now"){
        $time="Online";
        $lastActiveClass ="online";
    }
    $disc_id = Discussion::exitByUsers($user->getUserId(),$receiverUser->getUserId());
    $disc_token = Discussion::getToken($disc_id);
    $msg =  /** @lang HTML */
    <<<CB
                    <div class="headerChatBox">
                        <i class="fa fa-arrow-left closeChatBox"></i>                      
                        <div class="profile">
                            <img class="profile-img" alt="user-img" src="$root{$receiverUser->getPropertyValue("picture")}">
                        </div>
                        <div>
                            <div class="fullName">
                                <p>{$receiverUser->fullName()}</p>
                            </div>
                            <div class="isTyping $isTypingClass">
                                Typing...
                            </div>
                            <div class="lastActive $lastActiveClass">
                                $time
                            </div>
                        </div>
                        <div class="right ms-auto">
                            <i class="fal btnShowMenuP fa-info-circle btnF1 circle"></i>
                        </div>
                    </div>
                    <div class="chat-aria">
                        <div class="more-msg MoreMsgScroll"  style="text-align: center; display: none">
                            <img src="$root/asset/img/loading_video.gif" style="width: 25px" alt="loading more post">
                            <input hidden type="hidden" value="-1" id="limitPost">
                        </div> 
                        <div class="chats">                                                                                                 
                            $messagesCon                                                  
                        </div>                       
                        <div class="stateOfSeen">
                            $msg_statusC                          
                        </div>                        
                    </div>
                    <form method="post" class="inputMsgAria">                       
                        <input type="hidden" class="msg_from" name="msg_from" value="{$user->getUserId()}">
                        <input type="hidden" class="msg_to" name="msg_to" value="{$receiverUser->getUserId()}">
                        <div class="reply-container">
                            <i class="close-replay-container fa fa-close"></i>
                            <div class="replaySection">
                                                           
                            </div>
                        </div>                      
                        <div class="option-chat">
                            <i class="btnF1 fal fa-paperclip btnOpenOptionChat"></i>
                            <ul class="chat-option-share">
                                <li class="">
                                    <i class="fa fa-image"></i>
                                    <span>Images/Vedios</span>
                                </li>
                                <li class="">
                                    <i class="fa fa-file"></i>
                                    <span>Document</span>
                                </li>
                                <li class="">
                                    <i class="fa fa-paint-brush"></i>
                                    <span>Drawing</span>
                                </li>
                            </ul>                            
                        </div>
                        <div class="labels">
                            <label class="msg-text-label">
                                <input class="msg-text-field" type="text" name="msg_content" placeholder="Type a message">
                            </label>
                            <label class="msg-audio-label">
                                <div class=" btnDelete">
                                    <i class="btnF1 fal fa-trash"></i>
                                </div>
                                <div class="time centerFlex">
                                    <i class="fa fa-beat fa-he dot" style="--i:red"></i>
                                    <span class="value">00:00</span>
                                </div>
                                <div class="pauseAria d-flex">
                                    <audio controls class="testAudioPause d-none" style="display: none"></audio>
                                    <div class="btnF1 btnPause">
                                        <i class=" fal fa-pause"></i>
                                    </div>
                                    <div  class="btnF1 btnPlayResume " style="display: none">
                                        <i class=" fal fa-play"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="audioOrSend">
                            <button class="btnF1 send" style="display: none" >
                                <i class=" fal fa-paper-plane-top"></i>
                            </button>
                            <div class="btnF1 audio">
                                <i class="fal fa-microphone"></i>
                            </div>
                        </div>                       
                    </form>
                    
                    <div class="right-bar-info w-100 h-100 position-absolute top-0 end-0">
                        <div class="box-info position-absolute top-0 end-0 ">
                            <div class="hrF1"></div>
                            <div class="pb-4 text-center text-sm pt-5">
                                <img src="$root{$receiverUser->getPropertyValue("picture")}" class="w-24 h-24 rounded-full mx-auto mb-3" alt="">
                                <div class="mt-1">
                                    <div class="text-base fullName text-black"> {$receiverUser->fullName()}  </div>
                                    <div class="text-black-50 mt-1 userName">@{$receiverUser->getPropertyValue("username")}</div>
                                </div>
                                <div class="mt-3">
                                    <a href="$root/u/profile.php?u={$receiverUser->getPropertyValue("username")}" class="btnShowProfile">View profile</a>
                                </div>
                            </div>
    
                            <hr class="opacity-50 m-0">
                            <div class="menu-option  text-base font-medium p-3">
                                <input type="hidden" class="token_v" value="{$disc_token}">
                                <div class=" w-100 ele justify-content-start gap-3 rounded-4">
                                    <i  class="fal fa-bell-slash md hydrated" role="img"></i>
                                    Mute Notification
                                    <label class="switch cursor-pointer ms-auto"> <input type="checkbox" checked=""><span class="switch-button !relative"></span></label>
                                </div>
                                <button type="button" class="ele report w-100 justify-content-start gap-3  rounded-4">
                                    <i class="fal fa-flag  md hydrated" role="img"></i>
                                    Report
                                </button>
                                <button type="button" class="ele block w-100 justify-content-start gap-3   rounded-4">
                                    <i class="fal fa-stop-circle  md hydrated" role="img"></i>
                                    Block
                                </button>
                                <button type="button"  class=" ele delete w-100 justify-content-start gap-3 btnF1  rounded-4">
                                    <i  class="fal fa-trash  md hydrated" role="img"></i>
                                    Delete Chat
                                </button>
                            </div>
                            <!-- close button -->
                            <button type="button" class="position-absolute top-0 end-0 m-3 btnF1 btnShowMenuP circle" >
                                <i class="fa fa-close flex md hydrated" role="img"></i>
                            </button>
    
                        </div>
                    <!-- overly -->
                        <div class="shadow-box"></div>
                    </div>

            
CB;
    echo json_encode([
        "msg"=> $msg,
        "limit_msg"=>$limitPost,
        'last_msg'=> $last_msg,
        "success"=>true
    ]);
//    Message::updateStatusOfMsgOFUser($user->getUserId(),$receiverUser->getUserId(),$last_msg,"1");
?>