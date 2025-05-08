<?php

namespace classes;

class Like {
    private $db,
        $like_id,
        $post_id,
        $user_id,
        $like_date='';

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }

    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->post_id = $data["post_id"];
        $this->user_id = $data["user_id"];
    }
    public function exists() {
        $this->db->prepare("SELECT * FROM `like_post` 
        WHERE `post_id` = ? AND `user_id` = ?", array(
            $this->post_id,
            $this->user_id
        ));
        return $this->db->count() > 0 ? true : false;
    }

    public function add(): bool|int
    {
        if($this->exists()) {
            return -1;
        }
        $this->db->prepare("INSERT INTO `like_post` 
        (`post_id`, `user_id`, `like_date`) 
        VALUES (?, ?, ?)", array(
            $this->post_id,
            $this->user_id,
            $this->like_date,
        ));

        return !$this->db->error() ? true : false;
    }

    public function delete() {
        $this->db->prepare("DELETE FROM `like_post` WHERE `post_id` = ? AND `user_id` = ?",
            array(
                $this->post_id,
                $this->user_id
            )
        );
        return !$this->db->error() ? true : false;
    }

    public static function delete_post_likes($post_id) {
        DB::getInstance()->prepare("DELETE FROM `like_post` WHERE `post_id` = ?",
            array(
                $post_id
            )
        );

        return DB::getInstance()->error() == false ? true : false;
    }

    public function  get_post_users_likes_by_post($post_id) {
        $this->db->prepare("SELECT * FROM like_post WHERE `post_id` = ?", array($post_id));
        $users = array();
        if($this->db->count() > 0) {
            $fetched_like_users = $this->db->results();
            foreach($fetched_like_users as $user) {
                $u = new User();
                $u->fetchUser("userId", $user['user_id']);
                $users[] = $u;
            }
        }
        return $users;
    }
}
