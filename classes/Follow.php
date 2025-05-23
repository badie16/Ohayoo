<?php

namespace classes;
class Follow {
    private $db,
        $id,
        $follower,
        $followed;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }

    public function set_property($propertyName, $propertyValue): void
    {
        $this->$propertyName = $propertyValue;
    }

    public function set_data($data = array()): void
    {
        $this->follower = $data["follower"];
        $this->followed = $data["followed"];
    }

    public function fetch_follow(): bool
    {
        $this->db->prepare("SELECT * FROM user_follow WHERE follower_id = ? AND followed_id = ?",
            array(
                $this->follower,
                $this->followed
            ));
        if($this->db->count() > 0) {
            $fetched_follow = $this->db->results()[0];
            $this->id = $fetched_follow['id'];
            $this->follower = $fetched_follow['follower_id'];
            $this->followed = $fetched_follow['followed_id'];
            return true;
        }

        return false;
    }

    public static function get_user_followers($id): array
    {
        DB::getInstance()->prepare("SELECT * FROM user_follow WHERE followed_id = ?", array($id));
        // Here we will store posts fetched by query method
        $followers = array();
        if(DB::getInstance()->count() > 0) {
            $fetched_followers = DB::getInstance()->results();
            foreach($fetched_followers as $fetched_follower) {
                $follower = new User();
                $follower->fetchUser("userId", $fetched_follower['followed_id']);
                $followers[] = $follower;
            }
        }
        return $followers;
    }

    public static function get_user_followers_number($id): int
    {
        DB::getInstance()->prepare("SELECT * FROM user_follow WHERE followed_id = ?", array($id));
        return DB::getInstance()->count();
    }

    public static function get_followed_users_number($userId): int
    {
        DB::getInstance()->prepare("SELECT * FROM user_follow WHERE follower_id = ?", array($userId));
        return DB::getInstance()->count();
    }

    public static function get_followed_users($id): array
    {
        DB::getInstance()->prepare("SELECT * FROM user_follow WHERE follower_id = ?", array($id));
        // Here we will store posts fetched by query method
        $followed_users = array();
        if(DB::getInstance()->count() > 0) {
            $fetched_followed_users = DB::getInstance()->results();
            foreach($fetched_followed_users as $fetched_followed_user) {
                $followed_user = new User();
                $followed_user->fetchUser("userId", $fetched_followed_user['followed_id']);

                $followed_users[] = $followed_user;
            }
        }

        return $followed_users;
    }

    public function add(): bool
    {
        $this->db->prepare("INSERT INTO user_follow 
        (follower_id, followed_id) 
        VALUES (?, ?)", array(
            $this->follower,
            $this->followed
        ));
        return !$this->db->error();
    }

    public function delete(): bool
    {
        $this->db->prepare("DELETE FROM user_follow WHERE id = ?", array($this->id));

        return !$this->db->error();
    }

    public static function follow_exists($follower, $followed): bool
    {
        DB::getInstance()->prepare("SELECT * FROM user_follow WHERE follower_id = ? AND followed_id = ?",
            array(
                $follower,
                $followed
            ));

        return DB::getInstance()->count() > 0;
    }
}
