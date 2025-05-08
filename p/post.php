
<?php

require_once "../vendor/autoload.php";
require_once "../core/init.php";
$current_user_picture = $root.$user->getPropertyValue("picture");
use classes\{DB, Config, Validation, FuncGlobal as FG, Session, Token, Cookie,Post, User, Comment, Like};
use layouts\post\Post_Manager as Post_View;
$post_id = FG::getInput($_GET,"p");

if (!$post_id || !Post::exists($post_id,"id_unique")){
    FG::goToLocation(404);
}
$post = new Post();
$post->fetchPost($post_id,"id_unique");

$pv = new Post_View();
$postG = $pv->generate_post($post,$user,false);
$comments ="";
$commentArr = Comment::fetch_post_comments($post->getPropertyValue("post_id"));
foreach ($commentArr as $comment){
    $comments.=Post_View::generate_comment($comment,$user);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video</title>
    <?php include_once "../layouts/header.php"; ?>
    <link rel="stylesheet" href="<?php echo $root?>/asset/css/post_view.css">
</head>
<body>
    <?php include "../layouts/page_parts/partTop.php"?>
    <section class="app-center">
        <div class="post-view d-flex">
           <div class="left medias col-md-7">
               <?php

                   echo $pv->generatePostMediaSlide($post,$user);
               ?>
           </div>
            <div class="right post col-md-5 posts post-up">
                <div class="post-up-center">
                <?php echo $postG?>
                    <div class="comment-container">
                        <div class="comment-content">
                            <?php echo $comments?>
                        </div>
                    </div>
                </div>
                <div class="comment-form-option">
                    <div class="comment-op">
                        <div class="profile">
                            <img src="<?php echo  $current_user_picture?>" class="profile-img" alt="proile" />
                        </div>
                    </div>
                    <form action="" method="POST" class="comment-form">
                        <label class="w-100">
                            <input
                                type="text"
                                name="comment_text"
                                autocomplete="off"
                                placeholder="Write a comment .."
                                class="comment-style comment-input"
                            />
                        </label>
                        <button class="comment-btn">
                            <i class="fa fa-paper-plane-top"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="../asset/js/post.js"></script>
    <script src="../asset/js/post_view.js"></script>
</body>
</html>