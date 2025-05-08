<?php

namespace classes;

use mysqli;

class DB {
    // This will store database isntance if it is available
    private static $_instance = null;
    private $conn,
        $_error = false,
        $_query,
        $_results,
        $_count = 0,
        $server="localHost",
        $userDB="root",
        $passDB="",
        $dbName="ohayoo";

    private function __construct() {
        try {
            $this->conn = new mysqli($this->server,$this->userDB,$this->passDB,$this->dbName);
        }catch (\mysqli_sql_exception){
            echo "Error in data base";
            return;
        }

    }

    public static function getInstance() {

        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }


    public function query($sql) {
        /* Here we set error to false in order to not return error of some previous query */
        $this->error = false;
        // Check if the query has been prepared successfully
        // Here we assign and at the sametime check if a prepared statement has been set
        if($this->_query = $this->conn->query($sql)) {
            if (!isset($this->_query->num_rows )){
                return true;
            }
            // We're going to execute the query anyway regardless whether the query has params or not
            if($this->_query->num_rows > 0) {
                $this->_results = $this->_query->fetch_all(MYSQLI_ASSOC);
                $this->_count = $this->_query->num_rows;
            }else{
                $this->_results = array();
                $this->_count = 0;
            }
        }else{
            $this->error = true;
            echo "<script>alert('Error in file db')</script>";
        }
        // This allows you to chain everything with query function
        return $this;
    }

    public function prepare($sql,$params = array()) {
        /* Here we set error to false in order to not return error of some previous query */
        $this->error = false;

        // Check if the query has been prepared successfully
        // Here we assign and at the sametime check if a prepared statement has been set
        if($this->_query = $this->conn->prepare($sql)) {
            // We're going to execute the query anyway regardless whether the query has params or not
            if($this->_query->execute($params)) {
                $res = $this->_query->get_result();
                $this->_query->close();
                if (!empty($res) && isset($res->num_rows)) {
                    $this->_results = $res->fetch_all(MYSQLI_ASSOC);
                    $this->_count = $res->num_rows;
                }
            } else {
                $this->_error = true;
            }
        }

        // This allows you to chain everything with query function
        return $this;
    }
    public function conn() {
        return $this->conn;
    }

    public function error() {
        return $this->_error;
    }

    public function results() :array{
        return $this->_results;
    }

    public function count() :int{
        return $this->_count;
    }
    public function mysqli_r_e_s($string)
    {
        return mysqli_real_escape_string($this->conn(),$string);
    }
}



?>
<?php
//echo  "<pre>";
//      $db=DB::getInstance();
////
//      $db->prepare("SELECT * FROM posts where post_id = ?",array(53));
//      var_export($db->results());

//
//$sql = "SELECT * FROM `users` WHERE `email` = ? OR `userName` = ?";
//$db->prepare($sql,array("badie","badie"));
//if ($db->count() > 0) {
//    $row = $db->results()[0];
//    var_export($row);
//    $hash = $row["pass"];
//    if (password_verify("badie",$hash)) {
//        $_SESSION["userId"] = $row["userId"];
//        $sql2 = "UPDATE `users` SET `status` = 1 WHERE userId = '{$row["userId"]}'";
//        $db->query($sql2);
//        $_SESSION["userID"] = $row["userId"];
//        echo "success";
//    }else{
//        echo "Email or Password incorrect";
//    }
//}else{
//    echo "Email or Password incorrect";
//}

 ?>