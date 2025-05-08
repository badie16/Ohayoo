<?php

namespace classes;

use Random\RandomException;

class FuncGlobal
{
    static function arrayJsonF1(string $type,string $msg,int $code): array
    {
        return ["type"=> $type,"msg"=>$msg,"code"=>$code];
    }
    static function get_extension($fileName): string
    {
        return (false === $pos = strrpos($fileName, '.')) ? '' : substr($fileName, $pos);
    }
    static function make($string, $salt = ''): string
    {
        return hash("sha256", $string . $salt);
    }
    // This one will add salt hhh
    /**
     * @throws \Random\RandomException
     */
    function salt($length): string
    {
        return bin2hex(random_bytes($length));
    }
    static function uniqueAuth($length): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (RandomException $e) {
            return uniqid();
        }
    }
    static function unique(): string
    {
        return self::make(uniqid());
    }
    static function sanitize_id($id): float|false|int|string
    {
        if(empty($id)) {
            return false;
        }
        if(is_numeric($id) || $id == 0) {
            return $id;
        }
        return  false;
    }
    static function sanitize_num($num): float|int|string
    {
        if(empty($num) || !is_numeric($num)) {
            return 0;
        }
        return $num;
    }
    static function sanitize_text($text): string
    {
        $text = trim($text);
        return htmlspecialchars($text);
    }
    static function escape($string) {
        // Iprefer to use htmlspecialchars over htmlentities
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    static function sanitize_F1(int $n)
    {
        return $n;
    }

    static function getDateForm1($date): string
    {
        $s = (time() - strtotime($date));
        if ($s < 3600){
            return date("H:i",strtotime($date));
        }else if ($s < 60*60){
            return floor($s/60)."min";
        }else{
            return date("Y-m-d",strtotime($date));
        }
    }
    static function getDateForm2($date): string
    {
        $s = (time() - strtotime($date));
        if ($s < 60){
            return "now";
        }else if ($s < 60*60){
            return floor($s/60)."min";
        }else if($s < 60*60*24){
            return floor($s/60/60)."h";
        }else if($s < 60*60*24*8){
            return floor($s/60/60/24)."j ";
        }else{
            return date("Y-m-d",strtotime($date));
        }
    }
    public static function goToLocation($location=null): void
    {
        if(isset($location)) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header("HTTP/1.0 404 Not Found");
                        header("Location: " . Config::get("root/path") . "/404.html");
                        exit();
                        break;
                }
            }
            if (str_starts_with($location,"/")){
                header("Location: " .Config::get("root/path"). $location);
            }else{
                header("Location: " .Config::get("root/path")."/".$location);
            }

        }
    }


    public static function getInput($source, $fieldName) {
        if(isset($source[$fieldName])) {
            return $source[$fieldName];
        }
        return '';
    }
    /*
        The code taken from php documentation contribution notes:
        Code writer: Ghanshyam Katriya
        link: https://www.php.net/manual/en/function.array-unique.php#116302
    */
    public static function unique_multi_array($array, $key): array
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val->getPropertyValue($key), $key_array)) {
                $key_array[$i] = $val->getPropertyValue($key);
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
    public static function generateUrlProfile($obj,$field): string
    {
        return Config::get("root/path")."/u/profile.php?u=".$obj->getPropertyValue("$field");
    }
    public static function creatFolder($str): void
    {
        if (!file_exists($str)){
            mkdir($str);
        }
    }
    public static function setupFoldersOfUser($userId,$src=".."): void
    {
        self::creatFolder("$src/upload/users/");
        self::creatFolder("$src/upload/users/" . $userId."/");
        self::creatFolder("$src/upload/users/" . $userId."/posts/");
        self::creatFolder("$src/upload/users/" . $userId."/media/");
        self::creatFolder("$src/upload/users/" . $userId."/media/pictures/");
        self::creatFolder("$src/upload/users/" . $userId."/media/covers/");
    }
}

//FuncGlobal::getDateForm1("2024-04-26 21:00:00");