<?php

namespace layouts\chat;
//require_once "../../vendor/autoload.php";
//require_once "../../core/init.php";

use classes\{Config, Discussion, Message, User, FuncGlobal as FG};
//include "../header.php";
class ChatManager
{
   function generate_chat_box(){

   }
    static function generate_msg($msg,$receiverUser,$curentUser)
    {
        $root = Config::get("root/path");
        $msg_type= $msg->get_property("msg_type");
        $msg_content= $msg->get_property("msg_content");
        $msg_time= date("H:i",strtotime($msg->get_property("msg_date")));
        $classUser = "";
        if ($msg->get_property("msg_to") == $receiverUser->getUserId()){
            $classUser = "out";
        }else{
            $classUser = "in";
        }
        $typeArr =array(
            0=>"text",
            1=>"img",
            2=>"sound"
        );
        $msgCont = "";
        if ($msg_type == 0){
            $msgCont = "<p>$msg_content</p>";
        }else if($msg_type == 2){
            $idHash =  FG::unique();
            $msgCont = <<<Vi
                    <div class="myAudioF1 d-flex align-items-center">
                        <input type="hidden" class="Visrc" value="$root/$msg_content">
                        <i  class="fal fa-play playAudioMsg play" id="{$idHash}" play=false></i>
                        <div class="progress-position">
                            <label class="w-100 d-flex relative">
                                <input id="slide$idHash" class="slider" value="0" type="range">
                                <div class="progressF1">
                                </div>
                            </label>
                        </div>
                    </div>
Vi;
        }else if ($msg_type == 1){
            $msgCont ='<div class="myImgF1"><img src="'.$root.$msg_content.'"></div>';
        }
        $options_container="";
        $options_container .= <<<SO
                    <div class="sub-option-style-2">
                        <a href="#" class="replyBtn">
                        <i class="fa fa-reply"> </i>
                        Reply
                        </a>
                        <input type="hidden" value="{$msg->get_property('msg_id')}" class="message_id">
                    </div>                   
SO;
        if ($msg->get_property("msg_to") == $receiverUser->getUserId()){
            $options_container.=<<<S0
                    <div class="sub-option-style-2">
                        <a href="#" class="deleteBtn">
                        <i class="fa fa-trash"></i>
                         Delete message
                        </a>
                        <input type="hidden" value="{$msg->get_property('msg_id')}" class="msg_id">
                    </div>
S0;
        }
        if ($msg_type == 0){
            $options_container.=<<<SO
                    <div class="sub-option-style-2">
                        <a href="#" class="copyBtn">
                        <i class="fa fa-copy"></i>
                        copy</a>                      
                    </div>
SO;
        }

        $replaySection="";
	$isReplay= "";
        if ($msg->get_property('reply') && Message::exists($msg->get_property('reply'))){
	        $isReplay = "replay";
            $a = Message::get_msg_content_obj("msg_id",$msg->get_property('reply'));
            $replaySection = self::generateReplyContainer($a,$curentUser);
        }
        return <<<msg
        <div class="chat-items $classUser handle" id="msg{$msg->get_property('msg_id')}">                
            <div class="chat-items-container">                  
                <div class="chat $isReplay" >
                    $replaySection                        
                    <div class="msg type_{$typeArr[$msg_type]}">
                        $msgCont
                    </div>                   
                    <div class="stateContainer d-flex">                  
                        <span>$msg_time</span>                   
                    </div>
                </div>
                <div class="option-chat-item">
                    <div class="btn-option chat-message-more-button"><i class="bi-three-dots"></i></div>
                    <div class="sub-options-container sub-options-container-style-2" style="">
                        <div class="options-container-style-1">
                            $options_container
                        </div>
                    </div>
                </div>
            </div>
        </div>
msg;
    }

    static function generateDiscussion($disc,$curentUser): string
    {
        $root = Config::get("root/path");
        $uF = new User();
        $nmbOfMsgC="";
        $statusC="";
        if ($disc->get_property("msg_from") == $curentUser->getUserId()){
            $uF->fetchUser("userId",$disc->get_property("msg_to"));
            $statusC = ChatManager::getStatusOfMsgF2($curentUser->getUserId(),$disc->get_property("msg_id"));
        }else{
            $uF->fetchUser("userId",$disc->get_property("msg_from"));
            $nmbOfMsg = Message::getMsgFromStatus($curentUser->getUserId(),$uF->getUserId(),1);
            if ($nmbOfMsg > 0){
                $nmbOfMsgC = <<<SI
                <p class="centerFlex nmbOfMsg">$nmbOfMsg</p>
SI;
            }
        }
        $date = FG::getDateForm1($disc->get_property('msg_date'));
        $msg_content_Container = "";
        if($curentUser->getUserId() == $disc->get_property("msg_from")) {
            $msg_content_Container = "You: ";
        }
        $msg_type = $disc->get_property('msg_type');

        if ($msg_type == 0){
            //text
            $msg_content_Container .= $disc->get_property("msg_content");
            // Here we need the message to be MAX length of
            if(strlen($msg_content_Container) > 28) {
                $msg_content_Container = substr($msg_content_Container, 0, 27) . " ..";
            }
        }else if ($msg_type == 2){
            //audio
            $msg_content_Container .= "<i class='fa fa-microphone microInDiscussion'></i>";
        }
        $isTypingClass="";
        $isTyping = Discussion::exit_writing_message_notifier($uF->getUserId(),$curentUser->getUserId());
        if ($isTyping){
            $isTypingClass="active";
            Discussion::delete_writing_message_notifier($uF->getUserId(),$curentUser->getUserId());
        }
        $isOnline="";
        $time= time()- strtotime($uF->getPropertyValue("last_active"));
        if ($time < 60){
            $isOnline="online";
        }
        return <<<DI
                <div class= "chat-user" id="{$disc->get_property('room_token')}">
                    <input class="DId" type="hidden" value="{$uF->getUserId()}">
                    <div class="left">
                        <div class="profile relative $isOnline">
                            <img src="$root{$uF->getPropertyValue("picture")}" class="profile-img" alt="user-img">
                            <div class="onlineDot"></div>
                        </div>
                        <div class="center flex-fill relative">
                            <div class="fullName">
                                <p>{$uF->fullName()}</p>
                            </div>
                            <div class="lastMsg">
                                <div class="isTyping $isTypingClass">
                                    Typing...
                                </div>
                                <p class="msg">
                                    $msg_content_Container
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <p class="timeOfLastMsg">
                            $date
                        </p>
                        <div class="statusOfMsg">
                            $nmbOfMsgC
                            $statusC
                        </div>
                    </div>
                </div>
DI;
    }
    static function getStatusOfMsgF1($from_id,$msg_id): string
    {
        $msg_statusC = "";
        $last_msgObjArr = Message::get_msg_content_obj("msg_id",$msg_id);
        if ($last_msgObjArr){
            $msg_status = $last_msgObjArr["msg_status"];
            if ($last_msgObjArr["msg_from"] == $from_id){
                if ($msg_status == 1){
                    $msg_statusC.="<i class='bi-check-all'></i><span>seen</span>";
                }
            }
        }
        return $msg_statusC;
    }

    static function getStatusOfMsgF2($from_id,$msg_id): string
    {
        $msg_statusC = "";
        $last_msgObjArr = Message::get_msg_content_obj("msg_id",$msg_id);
        if ($last_msgObjArr){
            $msg_status = $last_msgObjArr["msg_status"];
            if ($last_msgObjArr["msg_from"] == $from_id){
                if ($msg_status == 0){
                $msg_statusC.="<i class='bi-check-all double-check'></i>";
                }else if($msg_status == 1){
                    $msg_statusC.="<i class='bi-check-all  double-check seen'></i>";
                }else{
                    $msg_statusC.="<i class='fa fa-check ms-1'></i>";
                }
            }
        }
        return $msg_statusC;
    }


    static function generateReplyContainer($msg,$curentUser)
    {
        $root = Config::get("root/path");
        $msgReplay="";
        $typeDoc="";
        if ($msg["msg_type"] == 0) {
            $msgReplay .= "<p>{$msg["msg_content"]}</p>";
        }else if($msg["msg_type"] == 1){
                $msgReplay='<img src="'.$root.$msg["msg_content"].'" alt="img">';
                $typeDoc = "typeDoc";
        }else if($msg["msg_type"] == 2){
            $msgReplay='<i class="fa fa-microphone"></i>';
        }
        $you="";
        $color="";
        if ($msg["msg_from"]  == $curentUser->getUserId()){
            $you="<p class='from'>You</p>";
            $color= "c1";
        }else{
            $uF= new User();
            $uF->fetchUser("userID",$msg["msg_from"]);
            $you="<p class='from'>{$uF->fullName()}</p>";
            $color= "c2";
        }
        $replaySection=" ";
        $replaySection .= <<<Rp
                    <div class="replaySection $color">
                        <input type="hidden" name="replyId" value="{$msg['msg_id']}">
                        <a class="$typeDoc" href="#msg{$msg['msg_id']}">
                           $you $msgReplay
                        </a>
                    </div>
Rp;
        return $replaySection;
    }
}

//$msg = new Message();
//$msg->fetchmessage("msg_id",131);
//echo ChatManager::generateReplyContainer($msg,$user)
?>

