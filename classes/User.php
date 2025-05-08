<?php
namespace classes;
class User implements \JsonSerializable
{
        private  $userId,
        $username = '',
        $email = '',
        $password = '',
        $firstname = '',
        $lastname = '',
        $user_type = 1,
        $cover = '',
        $picture = '',
        $private = -1,
        $dateBirth="",
        $last_active= '',
        $bio,
        $isLoggedIn = false,
        $db,
        $sessionName,
        $cookieName;

    // Everytime we instantiate a user object we need to check if the session is already set to determine wethere we login or not

    public function __construct()
    {
        $this->db = DB::getInstance();
        $this->sessionName = Config::get('session/session_name');
        $this->cookieName = Config::get('remember/cookie_name');
        if (Session::exists($this->sessionName)) {
            if ($this->fetchUser("userId", Session::get($this->sessionName))) {
                $this->isLoggedIn = true;
            }
        }
    }

    public function getPropertyValue($propertyName)
    {
        return $this->$propertyName;
    }
    public function getUserId(){
        return $this->userId;
    }
    public function setPropertyValue($propertyName, $propertyValue)
    {
        $this->$propertyName = $propertyValue;
    }



    public static function user_exists($field, $value): bool
    {
         DB::getInstance()->prepare("SELECT * FROM users WHERE $field = ?",array($value));
        if ( DB::getInstance()->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function fetchUser($field_name, $field_value)
    {
        $this->db->prepare("SELECT * FROM users WHERE $field_name = ?", array($field_value));
        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if ($this->db->count() > 0) {
            $fetchedUser = $this->db->results()[0];
            $this->setData($fetchedUser);
            return $this;
        }
        return false;
    }

    public function setData($fetchedUser = array()): void
    {
        $this->userId = $fetchedUser["userId"];
        $this->username = $fetchedUser["userName"];
        $this->email = $fetchedUser["email"];
        $this->password = $fetchedUser["pass"];
        $this->firstname = $fetchedUser["fName"];
        $this->lastname = $fetchedUser["lName"];
        $this->cover = $fetchedUser["imgC"];
        $this->picture = $fetchedUser["imgP"];
        $this->last_active = $fetchedUser["last_active"];
        $this->dateBirth = $fetchedUser["dateBirth"];
        $this->bio = $fetchedUser["bio"];
    }

    /*
    Note that if you want to add new user by specifying id, you can actually fetch the last user and add 1 to its id,
    then classes add function by adding id to add query
    */
    public function add(){
    }

    /*
    After getting the user by id and editing its properties, all you need to do to edit it is to call this function
    and it will do all the work for you
    */
    public function update(): bool
    {
        $this->db->prepare("UPDATE users SET username=?, email=?, pass=?, fName=?, lName=?, imgC=?, imgP=?,bio=? WHERE userId=?",
            array(
                $this->username,
                $this->email,
                $this->password,
                $this->firstname,
                $this->lastname,
                $this->cover,
                $this->picture,
                $this->bio,
                $this->userId
            ));

        return !$this->db->error();
    }

    public function update_property($property, $new_value){

    }
    public function delete()
    {

    }

    public static function search($keyword)
    {
        global $conn;
        if (empty($keyword)) {
            return array();
        }

        $keywords = strtolower($keyword);
        $keywords = htmlspecialchars($keywords);
        $keywords = trim($keywords);

        /*
        keyword could be multiple keywords separated by spaces.
        keep in mind that if the keyword is empty, explode will return an array with one empty element
        meaning you need to handle the situation where the first element is empty
        */
        $keywords = explode(' ', $keyword);

        if ($keywords[0] == '') {
            // Handle situation where $keyword passed is empty
            $query = "";
        } else {
            $query = "SELECT * FROM users ";
            for ($i = 0; $i < count($keywords); $i++) {
                $k = $keywords[$i];
                if ($i == 0)
                    $query .= "WHERE userName LIKE '%$k%' OR fName LIKE '%$k%' OR lName LIKE '%$k%' ";
                else
                    $query .= "OR userName LIKE '%$k%' OR fName LIKE '%$k%' OR lName LIKE '%$k%' ";
            }
        }

        /*
        We set WHERE false because if the $keywords is empty we don't appear anything and we display a message to the user
        informing him to fill in the search box to find friends or posts ..
        */

        $res = $conn->query($query);
        return $res->fetch_assoc();
    }

    public static function search_by_username($username)
    {
        global $conn;
        if (empty($username)) {
            return array();
        }

        $keyword = strtolower($username);
        $keyword = htmlspecialchars($username);
        $keyword = trim($username);

       $query = "SELECT * FROM users WHERE userName LIKE '$keyword%'";
        return $conn->query($query);
    }



    public function update_active()
    {
        $conn = $this->db->conn();
        $sql = "UPDATE users SET last_active = ? WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $var1 = date("Y-m-d H:i:s");
        $stmt->bind_param("si", $var1, $this->userId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }

    static function  getUsers($userCurentId): array
    {
        $conn = DB::getInstance()->conn();
        $sql = "SELECT * FROM users where userId != $userCurentId";
        $res = $conn->query($sql);
        $arrUser= array();
        if ($res->num_rows >0){
            while ($row = $res->fetch_object()){
                $arrUser[$row->userId] = $row;
            }
        }
        return $arrUser;
    }
    public function fullName(): string
    {
        return $this->firstname." ".$this->lastname;
    }

    public function login($email_or_username='', $password='', $remember=false): bool
    {
        if($this->userId) {
            Session::put($this->sessionName, $this->userId);
            $this->db->prepare("SELECT * FROM user_session WHERE user_id = ?",
                array($this->userId));
            // If this user is not exists in user_session table
            if(!$this->db->count()) {
                $token = FuncGlobal::unique();
                $chat_auth = FuncGlobal::uniqueAuth(19);
                $this->db->prepare('INSERT INTO user_session (`user_id`, `token`,chat_auth) VALUES (?, ?,?)',
                    array($this->userId, $token,$chat_auth));
            } else {
                // If the user does exist we
                $token = $this->db->results()[0]["token"];
            }
            if($remember) {
                Cookie::put($this->cookieName, $token, Config::get("remember/cookie_expiry"));
            }
            $this->isLoggedIn = true;
            return true;
        } else {
            $fetchBy = "username";
            if(strpos($email_or_username, "@")) {
                $fetchBy = "email";
            }
            if($this->fetchUser($fetchBy, $email_or_username)) {
                if(password_verify($password,$this->password)) {
                    $this->login($email_or_username,$password,$remember);
                }
            }
        }
        return false;
    }

    public function jsonSerialize()
    {
//        $vars = get_object_vars($this);
        $vars = array(
            "userId" => $this->userId,
            "userName" => $this->username
        );
        return $vars;
    }
    public function logout(): void
    {
        $this->db->prepare("DELETE FROM user_session WHERE user_id = ?", array($this->userId));
        Session::delete($this->sessionName);
        Session::delete(Config::get("session/tokens/logout"));
        Cookie::delete($this->cookieName);
    }
}