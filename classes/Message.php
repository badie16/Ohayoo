<?php
namespace classes;
class Message {
    private $db,
        $msg_id,
        $msg_from,
        $msg_to,
        $msg_content,
        $msg_date = '',
        $msg_type = '',
        $msg_status = false,
        $reply=null,
        $room_token = "";

    public function __construct() {
        $this->db = DB::getInstance();
    }
    public function get_property($propertyName) {
        return $this->$propertyName;
    }

    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function set_data($data = array()) {
        $this->msg_from = $data["msg_from"];
        $this->msg_to = $data["msg_to"];
        $this->msg_content = $data["msg_content"];
        $this->msg_type = $data["msg_type"];
    }
    public static function exists($msg_id) {
        DB::getInstance()->prepare("SELECT * FROM messages WHERE msg_id = ?", array($msg_id));
        return DB::getInstance()->count();
    }

    public function fetchmessage($property, $value) {
        $this->db->prepare("SELECT * FROM messages WHERE `$property` = ?", array($value));
        if($this->db->count() > 0) {
            $fetched_msg_content = $this->db->results()[0];
            $this->msg_id = $fetched_msg_content["msg_id"];
            $this->msg_from = $fetched_msg_content["msg_from"];
            $this->msg_to = $fetched_msg_content["msg_to"];
            $this->msg_type = $fetched_msg_content["msg_type"];
            $this->msg_content = $fetched_msg_content['msg_content'];
            $this->msg_date = $fetched_msg_content['msg_date'];
            $this->msg_status = $fetched_msg_content['msg_status'];
            $this->reply = $fetched_msg_content['reply'];
            return true;
        }

        return false;
    }

    public static function get_creator_by_msg_id($msg_content_msg_id) {
        DB::getInstance()->prepare("SELECT msg_from FROM messages WHERE `msg_id` = ?", array($msg_content_msg_id));
        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results()[0];
        }

        return false;
    }

    public static function get_msg_content_obj($property, $value) {
        DB::getInstance()->prepare("SELECT * FROM `messages` WHERE `$property` = ?", array($value));
        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results()[0];
        }
        return false;
    }
    public static function getListOfDiscussion2($userId): array
    {
        $listDiscussion = array();
        $db = DB::getInstance();
        $db->prepare("SELECT MAX(msg_id) as mid  FROM `messages`
            WHERE `msg_from` = ? OR `msg_to` = ?
            group by messages.msg_to
            order by mid DESC",
            array($userId,$userId));
        if (!$db->error()){
            foreach ($db->results() as $res){
                $disc = new Message();
                $disc->fetchmessage("msg_id",$res["mid"]);
                $listDiscussion[] = $disc;
            }
        }
        return $listDiscussion;
    }
    public static function getLastMsgExit($to ,$from):int|null{
        $db = DB::getInstance();
        $db->prepare("SELECT MAX(msg_id) as mid  FROM messages
            WHERE (`msg_from` = ? AND `msg_to` = ?) OR ( `msg_from` = ? AND `msg_to` = ?)",
            array($to,$from,$from,$to));
        return $db->results()[0]["mid"];
    }


    public function add(): false|int|string
    {
        $this->db->prepare("INSERT INTO messages 
        (`msg_from`,`msg_to`, `msg_content`, `reply`, `msg_type`) 
        VALUES (?, ?, ?, ?, ?)", array(
            $this->msg_from,
            $this->msg_to,
            $this->msg_content,
            $this->reply,
            $this->msg_type,
        ));
        // GET LAST msg_content msg_id TO GIVE IT TO RECIPIENT TABLE
        $last_inserted_msg_content_msg_id = $this->db->conn()->insert_id;
        return !$this->db->error() ? $last_inserted_msg_content_msg_id : false;
    }

    public function update_property($property) {
        $this->db->prepare("UPDATE `messages` SET $property=? WHERE msg_id=?",
            array(
                $this->$property,
                $this->msg_id
            ));

        return !$this->db->error();
    }

    public function delete_sended_msg_content() {
        // Then we detach or actually delete the whole msg_content from msg_content table
        $this->db->prepare("DELETE FROM messages WHERE msg_id = ?", array(
            $this->msg_id
        ));

        return !$this->db->error();
    }
    public static function getmsg_contents($from, $to) {
        DB::getInstance()->prepare("SELECT * FROM messages
        WHERE msg_from = ? AND msg_to = ?", array(
            $from,
            $to
        ));
        return DB::getInstance()->results();
    }
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
    static function fetchmessages($from,$to,int $limit_msg = -1): array
    {
        $disc = new Discussion();
        $disc->fetchDiscussion("room_id",Discussion::exitByUsers($from,$to));
        $date="";
        if ($disc->get_property("user1_id") == $from){
            $date = $disc->get_property("lastStatusU1");
        }else{
            $date = $disc->get_property("lastStatusU2");
        }
        if ($date == null){
            $date = 0;
        }
        $conn = DB::getInstance()->conn();
        if ($limit_msg != -1){
            $res = $conn->query("SELECT * FROM messages WHERE msg_id < $limit_msg 
                    AND ((msg_from = $from and msg_to = $to) OR (msg_from = $to and msg_to = $from) )
                       ORDER BY msg_id DESC LIMIT 20");
        }else{
            $res = $conn->query("SELECT * FROM messages
         WHERE ((msg_from = $from and msg_to = $to) OR (msg_from = $to and msg_to = $from))
         ORDER BY msg_id DESC LIMIT 20");
        }
        $msgs = array();
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        $i = 0;
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()){
                if ($row["msg_date"] > $date){
                    $msg_id = $row["msg_id"];
                    $msg = new Message();
                    $msg->fetchmessage("msg_id",$msg_id);
                    $msgs[$i] = $msg;
                    $i++;
                }
            }
        }
        return array_reverse($msgs);
    }
    static function updateStatusOfMsgOFUser($to,$from,$lastMsg,$status): bool
    {
        DB::getInstance()->prepare("UPDATE `messages` SET msg_status=? 
                  WHERE msg_to=? AND msg_from=? AND msg_status < ? AND msg_id <= ?"
            ,array($status,$to,$from,$status,$lastMsg));
        return !DB::getInstance()->error();
    }
    static function fetchNewMessages($from,$to,int $lastMsg = 0): array
    {
        $disc = new Discussion();
        $disc->fetchDiscussion("room_id",Discussion::exitByUsers($from,$to));
        $date="";
        if ($disc->get_property("user1_id") == $from){
            $date = $disc->get_property("lastStatusU1");
        }else{
            $date = $disc->get_property("lastStatusU2");
        }
        if ($date == null){
            $date = 0;
        }
        $conn = DB::getInstance()->conn();
        $res = $conn->query("SELECT * FROM messages WHERE msg_id > $lastMsg
                and ((msg_from = $from and msg_to = $to) OR (msg_from = $to and msg_to = $from) ) 
                   ORDER BY msg_id");
        $msgs = array();
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        $i = 0;
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()){
                if ($row["msg_date"] < $date){
                    continue;
                }
                $msg_id = $row["msg_id"];
                $msg = new Message();
                $msg->fetchmessage("msg_id",$msg_id);
                $msgs[$i] = $msg;
                $i++;
            }
        }
        return $msgs;
    }

    static function getMsgFromStatus($msg_to,$msg_from,$status):int
    {
        DB::getInstance()->prepare("SELECT count(*) n  FROM messages
WHERE msg_to = ? AND msg_from = ? AND msg_status < ?", array(
            $msg_to,
            $msg_from,
            $status
        ));
        return DB::getInstance()->results()[0]["n"];
    }


}


//var_export(Message::get_creator_by_msg_id(2));
//var_export( Message::getNumberOFMsgNotSeen(1382513644,1245533531));