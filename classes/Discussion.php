<?php

namespace classes;

class Discussion
{
    private $db,
        $room_id,
        $room_token,
        $user1_id,
        $user2_id,
        $statusU1,
        $statusU2,
        $lastStatusU1,
        $lastStatusU2;

    public function __construct() {
        $this->db = DB::getInstance();
    }
    public function get_property($propertyName) {
        return $this->$propertyName;
    }

    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }
    public function setData($user1_id ,$user2_id)
    {
        $this->user1_id = $user1_id;
        $this->user2_id = $user2_id;
    }
    public function add(): bool
    {
        $this->room_token = FuncGlobal::unique();
        var_export($this);
        $this->db->prepare("INSERT INTO chat_room 
        (`room_token`,`user1_id`, `user2_id`) 
        VALUES (?, ?, ?)", array(
            $this->room_token,
            $this->user1_id,
            $this->user2_id,
        ));
        return !$this->db->error();
    }

    public function fetchDiscussion($property, $value): bool
    {
        $this->db->prepare("SELECT * FROM chat_room WHERE `$property` = ?", array($value));
        if($this->db->count() > 0) {
            $fetched_msg_content = $this->db->results()[0];
            $this->room_id = $fetched_msg_content["room_id"];
            $this->room_token = $fetched_msg_content["room_token"];
            $this->user1_id = $fetched_msg_content["user1_id"];
            $this->user2_id = $fetched_msg_content["user2_id"];
            $this->statusU1 = $fetched_msg_content['statusU1'];
            $this->statusU2 = $fetched_msg_content['statusU2'];
            $this->lastStatusU2 = $fetched_msg_content['lastStatusU2'];
            $this->lastStatusU1 = $fetched_msg_content['lastStatusU1'];

            return true;
        }
        return false;
    }
    public static function exitByUsers($user1, $user2): int
    {
        $db = DB::getInstance();
        $db->prepare("SELECT * FROM chat_room where user1_id in (?,?) AND user2_id in (?,?) AND user1_id != user2_id",
        array($user1,$user2,$user1,$user2));
        if ($db->count() >0 ){
            if (isset($db->results()[0]["room_id"])){
                return $db->results()[0]["room_id"];
            }
        }
        return 0;
    }
    public static function exitByUserOne($user1): int
    {
        $db = DB::getInstance();
        $db->prepare("SELECT * FROM chat_room where user1_id = ? AND user2_id = ?",
            array($user1,$user1));
        if ($db->count() > 0 ){
            if (isset($db->results()[0]["room_id"])){
                return $db->results()[0]["room_id"];
            }
        }
        return 0;
    }
    public static function exitByID($id){
        $db = DB::getInstance();
        $db->prepare("SELECT * FROM chat_room where room_id = ?",array($id));
        return $db->count();
    }
    public function update_property($property) {
        $this->db->prepare("UPDATE `chat_room` SET $property = ? WHERE room_id=?",
            array(
                $this->$property,
                $this->room_id
            ));
        return !$this->db->error();
    }
    public function initialiseDiscussion($from, $to): string
    {
        if ($to != $from){
            $room_id = self::exitByUsers($from, $to);
        }else{
            $room_id = self::exitByUserOne($from);
        }
        if (!$room_id){
            $this->setData($from,$to);
            $this->add();
            return "add";
        }else{
            if($this->fetchDiscussion("room_id",self::exitByUsers($from, $to))){
                if($this->user1_id = $from){
                    if ($this->statusU1 == 1){
                        $this->statusU1 = 0;
                        $this->update_property("statusU1");
                    }else{
                        $this->statusU2 = 0;
                        $this->update_property("statusU2");
                    }

                }
            }
            return "update";
        }
    }

    public static function getListOfDiscussion($userId): array
    {
        $listDiscussion = array();
        $db = DB::getInstance();
        $db->prepare("SELECT MAX(msg_id) as
            mid,c.room_token
            FROM chat_room c
            Right JOIN Messages m ON (
            (c.user1_id = m.msg_from AND c.user2_id = m.msg_to)
             OR (c.user1_id = m.msg_to AND c.user2_id = m.msg_from))
            WHERE
                (c.user1_id = ? AND not (c.statusU1 = 1))
                OR
                (c.user2_id = ? AND not (c.statusU2 = 1))            
            GROUP BY c.room_id
            ORDER BY mid DESC",
            array($userId,$userId));
        if (!$db->error()){
            foreach ($db->results() as $res){
                $disc = new Message();
                $disc->fetchmessage("msg_id",$res["mid"]);
                $disc->set_property("room_token",$res['room_token']);
                $listDiscussion[] = $disc;
            }
        }
        return $listDiscussion;
    }
    public static function exit_writing_message_notifier($from,$to): bool
    {
        $disc_id = self::exitByUsers($from,$to);
        if($disc_id) {
            DB::getInstance()->prepare("SELECT * FROM `writing_message_notifier`
                WHERE `disc_id`=? AND `user_id`= ? ",
                array($disc_id, $from));
            return DB::getInstance()->count();
        }
        return false;
    }
    public static function add_writing_message_notifier($from,$to): bool
    {
        if (!self::exit_writing_message_notifier($from, $to)) {
            $disc_id = self::exitByUsers($from, $to);
            if ($disc_id) {
                DB::getInstance()->prepare("INSERT INTO `writing_message_notifier` (`disc_id`, `user_id`) 
                VALUES (?, ?)", array(
                    $disc_id,
                    $from
                ));
                return !DB::getInstance()->error();
            }else{
                return false;
            }
        }
        return true;
    }
    public static function delete_writing_message_notifier($from,$to): bool
    {
        $disc_id = self::exitByUsers($from,$to);
        if($disc_id){
            DB::getInstance()->prepare("DELETE FROM `writing_message_notifier` WHERE `user_id` = ? AND `disc_id` = ?"
                , array(
                    $from,
                    $disc_id
                ));

            return !DB::getInstance()->error();
        }
        return false;
    }
    public static function getToken($id)
    {
        DB::getInstance()->prepare("SELECT room_token FROM `chat_room`
                WHERE `room_id`= ? ",
            array($id));
        if (DB::getInstance()->count()){
            return DB::getInstance()->results()[0]["room_token"];
        }else{
            return null;
        }

    }
}