<?php
namespace classes;
use classes\DB;

class Post
{
    public  $post_id;
    public  $post_owner;
    public  $post_visibility;
    public  $post_date;
    public  $text_content;
    public  $id_unique;
    public array $postMedia = array();
    private $db;

    public function getPropertyValue($propertyName)
    {
        return $this->$propertyName;
    }
    public function set_property($propertyName, $propertyValue): void
    {
        $this->$propertyName = $propertyValue;
    }
    public function setPostFromArray(array $row): void
    {
        $this->post_owner = $row['post_owner'];
        $this->post_visibility = $row['post_visibility'];
        $this->post_date = $row['post_date'];
        $this->text_content = $row['text_content'];
        $this->id_unique = $row['id_unique'];
        $this->postMedia = $row['post_media'];
    }
    public function __construct(){
        $this->db = DB::getInstance();
    }
    // Remember to use this function only
    public function fetchPost($value, $field_name='post_id'): bool
    {
        $conn = $this->db->conn();
        $res = $conn->query("SELECT * FROM posts WHERE $field_name = '$value'");
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $this->post_id = $row["post_id"];
            $this->post_owner = $row['post_owner'];
            $this->post_visibility = $row['post_visibility'];
            $this->post_date = $row['post_date'];
            $this->text_content = $row['text_content'];
            $this->id_unique = $row['id_unique'];
            $res2 = $conn->query("SELECT * FROM postmedia WHERE post_id = '$this->post_id'");
            $post_media_arr = array();
            if($res2->num_rows > 0) {
                $i=0;
                while ($row = $res2->fetch_assoc()){
                    $post_media_arr[$i]=array(
                        "post_id" => $row["post_id"],
                        "type"=> $row["type"],
                        "url"=> $row["url"]
                    );
                    $i++;
                }
                $this->postMedia = $post_media_arr;
            }

            return true;
        }
        return false;
    }
    static function fetchPosts(int $limitPost = -1, int $limitNumber = 5): array
    {
        $conn = DB::getInstance()->conn();
        if ($limitPost != -1){
            $res = $conn->query("SELECT * FROM posts WHERE post_id < $limitPost ORDER BY post_id DESC LIMIT $limitNumber");
        }else{
            $res = $conn->query("SELECT * FROM posts ORDER BY post_id DESC LIMIT $limitNumber");
        }
        $posts = array();
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()){
                $post_id = $row["post_id"];
                $post = new Post();
                $post->fetchPost($post_id);
                $posts[] = $post;
            }
        }
        return $posts;
    }
    static function fetchPostsOfUser($userOfProfile,$curentUser,int $limitPost = -1, int $limitNumber = 5): array
    {
        if ($limitPost != -1){
            DB::getInstance()->prepare("SELECT * FROM posts WHERE post_owner = ? AND post_id < ? ORDER BY post_id DESC LIMIT ?",array($userOfProfile,$limitPost,$limitNumber));
        }else{
            DB::getInstance()->prepare("SELECT * FROM posts WHERE post_owner = ?  ORDER BY post_id DESC LIMIT ?",array($userOfProfile,$limitNumber));
        }
        $posts = array();
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if(DB::getInstance()->count() > 0) {
            foreach (DB::getInstance()->results() as $row){
                if ($userOfProfile != $curentUser){
                    if ($row["post_visibility"] =="only_me" OR ($row["post_visibility"] == "friend" AND !UserRelation::isFriend($userOfProfile,$curentUser))){
                        continue;
                    }
                }
                $post_id = $row["post_id"];
                $post = new Post();
                $post->fetchPost($post_id);
                $posts[] = $post;
            }
        }
        return $posts;
    }
    public static function get_last_post(): false|array|null
    {
        $conn = DB::getInstance()->conn();
        $sql = "SELECT * FROM posts ORDER BY post_id DESC LIMIT 1";
        $res =  $conn->query($sql);
        if ($res->num_rows > 0){
            return $res->fetch_assoc();
        }else{
            return null;
        }
    }
    public static function get_last_postOfUser($id): false|array|null
    {
        $conn = DB::getInstance()->conn();
        $res = $conn->query("SELECT * FROM posts WHERE post_owner = '$id' ORDER BY post_id DESC LIMIT 1");
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($res->num_rows > 0) {
            return $res->fetch_assoc();
        }
        return false;
    }

    function  setDataToDb(Post $post): bool
    {
        $conn = $this->db->conn();
        $sql = "INSERT INTO `posts`(`post_owner`, `post_visibility`, `text_content`, `id_unique`)
                VALUES ('$post->post_owner','$post->post_visibility','$post->text_content','$post->id_unique')";
        $res = $conn->query($sql);
        if ($res){
            $res2 =  $conn->query("SELECT post_id FROM posts WHERE `id_unique` = '$post->id_unique'");
            if ($res2->num_rows > 0){
                $row = $res2->fetch_assoc();
                $postID = $row["post_id"];
                $sql="";
                foreach($post->postMedia as $media) {
                    $sql .= "INSERT INTO postmedia (post_id, type, url) VALUES ('$postID','{$media['type']}','{$media['url']}') ;";
                }
                if(!empty($sql)){
                    if (!$conn->multi_query($sql) === TRUE) {
                        return false;
                    }
                }

            }
            return true;
        }
        return false;
    }

    public static function get_post_owner($post_id) {
        DB::getInstance()->prepare("SELECT * FROM posts WHERE post_id = ?", array($post_id));
        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results()[0];
        }
        return false;
    }
    public static function exists($value, $field_name='post_id'): bool
    {
        DB::getInstance()->prepare("SELECT * FROM posts WHERE $field_name = ?",array($value));
        return DB::getInstance()->count() > 0;
    }
    public function delete(): bool
    {
        $this->db->prepare('DELETE FROM posts WHERE post_id = ?', array($this->post_id));

        return !$this->db->error();
    }


    public static function get($field_name, $field_value): array
    {
        DB::getInstance()->prepare("SELECT * FROM posts WHERE $field_name = ?", array($field_value));
        // Here we will store posts fetched by query method
        $posts = array();
        if(DB::getInstance()->count() > 0) {
            $fetched_posts = DB::getInstance()->results();
            foreach($fetched_posts as $post) {
                $f_post = new Post();
                $f_post->fetchPost($post["post_id"]);
                $posts[] = $f_post;
            }
        }
        return $posts;
    }

    public static function fetch_journal_posts($user_id,$lastPost=-1,$numberMax=5): array
    {
        // Algorithm to fetch jounral posts of a specific user
        // First we get all users relevent to the user's journal
        $posts = array();
        $lastPostQ ="";
        if ($lastPost != -1){
            $lastPostQ =  "AND post_id < $lastPost";
        }
        DB::getInstance()->prepare("SELECT posts.*
                FROM posts
                         INNER JOIN users ON posts.post_owner = users.userId
                WHERE( users.userId = ?
                OR( userId in  (
                    SELECT to_u FROM user_relation
                    WHERE  user_relation.from_u = ? AND user_relation.status = 'F'
                ) AND post_visibility != 'only_me' )
                OR( userId in(
                    SELECT followed_id FROM user_follow
                    WHERE  user_follow.follower_id = ?
                ) AND post_visibility = 'public'))
                $lastPostQ
                GROUP BY post_id
                ORDER BY posts.post_id DESC
                LIMIT $numberMax ", array($user_id,$user_id,$user_id));
        if(DB::getInstance()->count() > 0) {
            $fetched_posts = DB::getInstance()->results();
            foreach($fetched_posts as $post) {
                $f_post = new Post();
                $f_post->fetchPost($post["post_id"]);
                $posts[] = $f_post;
            }
        }
        return $posts;
    }
    public static function fetch_journal_videos($user_id,$lastPost=-1,$numberMax=5): array
    {
        // Algorithm to fetch jounral video of a specific user
        // First we get all users relevent to the user's journal
        $posts = array();
        $lastPostQ ="";
        if ($lastPost != -1){
            $lastPostQ =  "AND post_id < $lastPost";
        }
        DB::getInstance()->prepare("SELECT posts.*
                FROM posts
                         INNER JOIN users ON posts.post_owner = users.userId
                WHERE( users.userId = ?
                OR( userId in  (
                    SELECT to_u FROM user_relation
                    WHERE  user_relation.from_u = ? AND user_relation.status = 'F'
                ) AND post_visibility != 'only_me' )
                OR( userId in(
                    SELECT followed_id FROM user_follow
                    WHERE  user_follow.follower_id = ?
                ) AND post_visibility = 'public'))
                $lastPostQ
                GROUP BY post_id
                ORDER BY posts.post_id DESC
                LIMIT $numberMax ", array($user_id,$user_id,$user_id));
        if(DB::getInstance()->count() > 0) {
            $fetched_posts = DB::getInstance()->results();
            foreach($fetched_posts as $post) {
                $f_post = new Post();
                $f_post->fetchPost($post["post_id"]);
                $posts[] = $f_post;
            }
        }
        return $posts;
    }
    public static function getPostMediaOfUser($userId,$typeOfMedia,$maxOfMedia=0): array
    {
        $limit = "";
        if ($maxOfMedia){
            $limit = "LIMIT $maxOfMedia";
        }
        DB::getInstance()->prepare("
                            SELECT p.post_id,p.post_visibility,p.id_unique,pm.url FROM posts p
                            JOIN postmedia pm 
                            on p.post_id = pm.post_id 
                            WHERE p.post_owner = ? AND pm.type = ? ORDER BY p.post_id DESC $limit",array($userId,$typeOfMedia));
        return DB::getInstance()->results();
    }

    public function update($field_name): bool
    {
        $this->db->prepare("UPDATE posts
        SET $field_name=? WHERE post_id=?"
            , array(
                $this->$field_name,
                $this->post_id
            ));
        return !$this->db->error();
    }
    public function toString(): string
    {
        $u = new User();
        $u->fetchUser("userId",$this->post_owner);
        return 'Post with id: ' . $this->post_id . " and owner of id: " . $u->fullName() . " published at: " . $this->post_date . " visible in: " . $this->post_visibility . "<br>";
    }
}
?>

<?php
//
//    $postDb = new PostDB();
//    $post = $postDb->getPost(3);
//    echo "<pre>";
//    var_export($post);
//?>