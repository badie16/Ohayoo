<?php
//$date1 = strtotime(date("2024-04-27 16:01:23"));
////$date2 = time();
////$arr =timezone_abbreviations_list();
////echo "<pre>";
////var_export($arr);
////var_export(date("H:i:s",$date2));
//
//
use classes\Token;

require_once "../vendor/autoload.php";
require_once "../core/init.php";
//include "../layouts/header.php";
//use classes\{Discussion, Post, User, Message, FuncGlobal as FG};
//use layouts\post\Post_Manager;
//echo "<pre>";
//$msg_to = 1245533531;
//$msg_from = 1382513644;
//
//
//$generatePost="";
//foreach (Post::fetchPostsOfUser($msg_from) as $post) {
//    $post_view = new Post_Manager();
//    $generatePost .=  $post_view->generate_post($post, $user);
//    $limitPost = $post->post_id;
//}
//var_export($limitPost);
//echo "<div class='posts'>";
//var_export($generatePost);
?>





<?php
//
//use classes\UserRelation;
//
//var_export(UserRelation::isFriend(1585660075,1382513644));
//$post = \classes\Post::fetch_journal_posts(1585660075,-1,10);
echo "<pre>";
//foreach ($post as  $p){
//    var_export($p->toString());
//    var_export("<br>########################<br>");
//}
//
echo Token::generate("saveEdits")."<br>";
var_export($_SESSION);

?>


















