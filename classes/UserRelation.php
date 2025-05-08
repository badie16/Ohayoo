<?php

namespace classes;


class UserRelation {
    private $db,
        $id_relation,
        $from_u,
        $to_u,
        $status,
        $date;
    /**$id_relation,
     * $from_u,
     * $to_u,
     * $status,
     * $date; */
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
        $this->from_u = $data["from"];
        $this->to_u = $data["to"];
        $this->status = $data["status"];
    }

    public function send_request(): bool
    {
        // Only send request if there's no existed relation between the two users
        $existed_relation_status = $this->bidirectional_relation_exists();

        if(!$existed_relation_status) {
            $this->db->prepare("INSERT INTO user_relation (`from_u`, `to_u`, `status`) 
                VALUES (?, ?, ?)", array(
                    $this->from_u,
                    $this->to_u,
                    "P"
                )
            );
            return !$this->db->error();
        }
        return false;
    }

    public function cancel_request(): bool
    {
        /*
            First we need to check if there's a pending request
        */
        $pending_request_exists = $this->micro_relation_exists($this->from_u, $this->to_u, "P");
        if($pending_request_exists) {
            return $this->delete_relation("P");

        }
        return false;
    }

    public function accept_request(): bool
    {
        /*
            Only accept request if there's a realtion between the two, AND the returned status is P (pending)
            when the condition is true, we need to update the pending relation record to friend and insert new friend realtion
            in the other direction. e.g. A send friend request to B, if there's a Pending request between the two in one of the direction, we need to update
            that P entry to A to B (friend) and add record -> B to A (friend)
        */
        // Only send request if there's no existed relation between the two users
        $existed_relation_status = $this->bidirectional_relation_exists();

        if($existed_relation_status === "P") {
            /*
                Here w passed P as argument to update method to tell to update record with:
                                                                                            from=$this->from
                                                                                            to=$this->to
                                                                                            status="P"
                and change it to be like :
                                                                                            from=$this->from
                                                                                            to=$this->to
                                                                                            status="F"
            */

            // Update the current direction record
            $this->status = "F";
            $this->update("P");
            // Add the other direction record
            $other_end = new UserRelation();
            $other_end->set_data(array(
                'from'=>$this->to_u,
                'to'=>$this->from_u,
                'status'=>"F"
            ));
            $other_end->add();
            return true;
        }

        return false;
    }

    public function unfriend(): bool
    {
        // Only unfriend if there's a relation between the two and it shoulf be friend relationship
        $existed_relation_status = $this->get_relation_by_status("F");
        if($existed_relation_status) {
            $this->delete_relation("F");
            $relation = new UserRelation();
            $relation->set_property("from_u", $this->to_u);
            $relation->set_property("to_u", $this->from_u);
            $relation->delete_relation("F");
            return true;
        }

        return false;
    }

    public function block(): bool
    {
        /*
            This function will check if there's a BLOCK relation between the two; If so then unblock them; Otherwise
            from user will block to user.
            Notice users who are not friends to each others cannot block each others
        */
        $friendship_relation_exists = $this->micro_relation_exists($this->from_u, $this->to_u, "F");
        $exists = $this->micro_relation_exists($this->from_u, $this->to_u, "B");

        if($friendship_relation_exists) {
            if($exists) {
                // Unblock
                $this->db->prepare("DELETE FROM user_relation WHERE `from_u` = ? AND `to_u` = ? AND `status` = ?"
                    ,array(
                        $this->from_u,
                        $this->to_u,
                        "B"
                    ));

            } else {
                // Block
                $this->db->prepare("INSERT INTO user_relation (`from_u`, `to_u`, `status`) 
                VALUES (?, ?, ?)", array(
                    $this->from_u,
                    $this->to_u,
                    "B"
                ));
            }

            return true;
        }
        return false;
    }

    public function add(): bool
    {
        $existed_relation_status = $this->bidirectional_relation_exists();
        if($existed_relation_status) {
            $this->db->prepare("INSERT INTO user_relation (`from_u`, `to_u`, `status`) 
                VALUES (?, ?, ?)", array(
                    $this->from_u,
                    $this->to_u,
                    $this->status
                )
            );
            return !$this->db->error();
        }
        return false;
    }

    public function update($status): bool
    {
        /*
            Notice when we need to update a record in user_relation table we need the status to be updated to be passed
            as argument to update; in order to have a unique identifier(from, to, $status) to identify the record to be updated
            Also notice that from and to properties are not meant by update
        */

        $this->db->prepare("UPDATE user_relation SET `status`=? WHERE `from_u`=? AND `to_u`=? AND `status` = ?",
            array(
                $this->status,
                $this->from_u,
                $this->to_u,
                $status
            )
        );
        return !$this->db->error();
    }

    public function delete_relation($status=""): bool
    {
        /*
            This function delete any relation between the two users in from and to properties
            I added $status parameter; If you don't pass anything to status it will delete every relation between the two;
            but if you specify the status(for exemple "P") it will only delete the pending friend request
            When we want to delete a relation record we only need the relation to be present regardless of the type of status returned by bidirectional_relation_exists()
        */
        $existed_relation_status = $this->bidirectional_relation_exists();

        if($existed_relation_status) {
            $query = "DELETE FROM user_relation WHERE `from_u` = ? AND `to_u` = ?";
            if(!empty($status)) {
                $query .= " AND `status` = '$status'";
            }
            $this->db->prepare($query,
                array(
                    $this->from_u,
                    $this->to_u
                ));
            return !$this->db->error();
        }

        return false;
    }

    public static function get_friends($user_id): array
    {
        /*
            This function will take user_id and find his friends in relations table and get his friends in form of users
            using User class and fetch each user based on his id and return array of friends
        */

        DB::getInstance()->prepare("SELECT * FROM user_relation WHERE `from_u` = ? AND `status` = 'F'",
            array(
                $user_id
            ));
        $relations = DB::getInstance()->results();
        $friends = array();
        foreach($relations as $relation) {
            $friend_id = $relation["to_u"];
            $user = new User();
            $user->fetchUser("userId", $friend_id);
            $friends[] = $user;
        }
        return $friends;
    }

    public static function get_friendsWithOrder($userId,$status="F",$numMax=-1): array
    {
        $limit="";
        if ($limit!=-1){
            $limit = "LIMIT ".$numMax;
        }
        DB::getInstance()->prepare("
                        SELECT count(*) as numOfmsg, r.*
                        FROM user_relation r join messages m 
                        on ( msg_from = from_u AND msg_to = to_u) OR ( msg_from =  to_u AND msg_to = from_u)
                        WHERE `from_u` = ? AND `status` = ?
                        GROUP BY to_u
                        order by numOfmsg DESC $limit",
            array(
                $userId,
                $status
            ));
        $relations = DB::getInstance()->results();
        $friends = array();
        foreach($relations as $relation) {
            $friend_id = $relation["to_u"];
            $user = new User();
            $user->fetchUser("userId", $friend_id);
            $friends[] = $user;
        }
        return $friends;
    }

    public static function get_friends_number($user_id): int
    {
        DB::getInstance()->prepare("SELECT * FROM user_relation WHERE `from_u` = ? AND `status` = 'F'",
            array(
                $user_id
            ));
        return DB::getInstance()->count();
    }

    public function get_relation_by_status($status): mixed
    {
        $this->db->prepare("SELECT * FROM user_relation WHERE `from_u` = ? AND `to_u` = ? AND `status` = ?",
            array(
                $this->from_u,
                $this->to_u,
                $status
            ));
        if($this->db->count() > 0) {
            return $this->db->results()[0]['status'];
        } else {
            return false;
        }
    }
    public function micro_relation_exists($from_u, $to_u, $status): bool
    {
        $this->db->prepare("SELECT * FROM user_relation WHERE `from_u` = ? AND `to_u` = ? AND `status` = ?",
            array(
                $from_u,
                $to_u,
                $status
            ));
        return $this->db->count() > 0;
    }

    public function bidirectional_relation_exists() {
        /*
            this function will check if there's a relation between two users
            Note: Notice when we perform a check we check in both direction if(there's a relation created from A to B and also from B to A)
            e.g. user B could send friend request to user A and when we want to check if there's a relation between A and B we need
            also to check if there's a relation between B to A because in this case It exists between B to A

            return: returns the type of relation in case a relation exists, otherwise returns false
        */
        $this->db->prepare("SELECT * FROM user_relation WHERE (`from_u` = ? AND `to_u` = ?) OR (`from_u` = ? AND `to_u` = ?)",
            array(
                $this->from_u,
                $this->to_u,
                $this->to_u,
                $this->from_u,
            ));
        if($this->db->count() > 0) {
            return $this->db->results()[0]['status'];
        } else {
            return false;
        }
    }

    public static function get_friendship_requests($user_id): array
    {
        DB::getInstance()->prepare("SELECT * FROM user_relation WHERE `to_u` = ? AND `status` = ?",
            array(
                $user_id,
                'P'
            ));
        return DB::getInstance()->results();
    }
    public static function isFriend($user1 ,$user2): bool
    {
        DB::getInstance()->prepare("SELECT * FROM user_relation WHERE ((`from_u` = ? AND `to_u` = ?) OR (`from_u` = ? AND `to_u` = ?)) AND status = 'F'",
            array(
                $user1,
                $user2,
                $user2,
                $user1,
            ));
        return DB::getInstance()->count() > 0;
    }
}
