<?php

namespace classes;
use classes\{DB};
class Comment {
    private $db,
        $comment_id,
        $comment_owner,
        $post_id,
        $comment_date='',
        $comment_text='',
        $replayId = null;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getPropertyValue($propertyName) {
        return $this->$propertyName;
    }

    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->comment_owner = $data["comment_owner"];
        $this->post_id = $data["post_id"];
        $this->comment_text = $data["comment_text"];
    }

    public function add() {
        $this->db->prepare("INSERT INTO `comments` 
        (`comment_owner`, `post_id`, `comment_text`) 
        VALUES (?, ?, ?)", array(
            $this->comment_owner,
            $this->post_id,
            $this->comment_text
        ));
        $id = $this->db->conn()->insert_id;
        return !$this->db->error() ? $id : false;
    }

    public function update(): bool
    {
        $this->db->prepare("UPDATE comments SET `comment_owner` = ?, 
            `post_id` = ?, `comment_date` = ?, `comment_text` = ? WHERE `comment_id` = ?
        ", array(
            $this->comment_owner,
            $this->post_id,
            $this->comment_date,
            $this->comment_text,
            $this->comment_id,
        ));
        return !$this->db->error();
    }

    public function delete(): bool
    {
        $this->db->prepare("DELETE FROM `comments` WHERE comment_id = ?",
            array($this->comment_id));
        return !$this->db->error();
    }

    public function fetch_comment($property = "comment_id", $value): bool
    {
        $this->db->prepare("SELECT * FROM comments WHERE $property = ?", array($value));
        if($this->db->count() > 0) {
            $fetched_comment = $this->db->results()[0];
            $this->comment_id = $fetched_comment['comment_id'];
            $this->comment_owner = $fetched_comment['comment_owner'];
            $this->post_id = $fetched_comment['post_id'];
            $this->comment_date = $fetched_comment['comment_date'];
            $this->comment_text = $fetched_comment["comment_text"];
            return true;
        }
        return false;
    }
    public static function getReplayComments($comment_id)
    {
        DB::getInstance()->prepare("SELECT comment_id FROM comments WHERE replayID = ?", array($comment_id));
        $commentReplayArr = array();
        if(DB::getInstance()->count() > 0) {
            foreach (DB::getInstance()->results() as $c){
                $comment = new Comment();
                $comment->fetch_comment("comment_id",$c["comment_id"]);
                $commentReplayArr[$comment->comment_id] = $comment;
            }
            return $commentReplayArr;
        }
        return array();
    }
    public static function fetch_post_comments($post_id) {
        DB::getInstance()->prepare("SELECT comment_id FROM comments WHERE post_id = ? AND replayID IS NULL ", array($post_id));
        $comments =array();
        if(DB::getInstance()->count() > 0) {
            $comment_ids =  DB::getInstance()->results();
            foreach ($comment_ids as $id){
                $comment_id = $id["comment_id"];
                $comment = new Comment();
                $comment->fetch_comment("comment_id",$comment_id);
                $comments[] = $comment;
            }
        }
        return $comments;
    }
    public static function count_post_comments($post_id) :int{
        DB::getInstance()->prepare("SELECT COUNT(comment_id) num FROM comments WHERE post_id = ?", array($post_id));
        $num= 0;
        if(DB::getInstance()->count() > 0) {
            $num =  DB::getInstance()->results()[0]["num"];
        }
        return $num;
    }
    public static function get($field_name, $field_value) {
        DB::getInstance()->prepare("SELECT * FROM posts WHERE $field_name = ?", array($field_value));
        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results();
        }

        return array();
    }

    public static function delete_post_comments($post_id) {
        DB::getInstance()->prepare("DELETE FROM `comments` WHERE post_id = ?",
            array($post_id)
        );

        return !DB::getInstance()->error();
    }
    function tosString(){
        return array(
            "comment_id"=>$this->comment_id,
            "comment_owner"=>$this->comment_owner,
            "post_id"=>$this->post_id,
            "comment_text"=>$this->comment_text
        );
    }
}



//$com = Comment::fetch_post_comments(89);
//echo "<pre>";
//var_export($com);