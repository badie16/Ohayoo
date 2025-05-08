<?php
global $user,$root;
require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\FuncGlobal as FG;
use classes\Post;
use classes\UserRelation;

$_SESSION['pageC'] = "home.php";
if(!$user->isLoggedIn()){
    FG::goToLocation("/login.php");
}
$current_user_picture = $root.$user->getPropertyValue("picture");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Videos</title>
    <?php include "layouts/header.php" ?>
    <link rel="stylesheet" href="asset/css/simple-line-icons.css">
</head>


<body>
<div class="video">
    <?php include "layouts/page_parts/partTop.php"?>
    <section class="d-flex justify-content-between">
        <?php include "layouts/page_parts/menuLeft.php"?>
        <section class="app-center center col-sm-10 col-md-9 col-12 col-xl-6">
            <section class="postes-global">
                <section class="posts">
                    <script> </script>
                    <?php
                    //                        $posts = Post::fetchPosts();
                    //                        use layouts\post\Post_Manager;
                    //                        if(count($posts) == 0) { ?>
                    <!--                            <div id="empty-posts-message">-->
                    <!--                                <h3>Try to add friends, or follow them to<BR> see their posts ..</h3>-->
                    <!--                                <p>click <a href="http://127.0.0.1/CHAT2/seadrch.php" class="link" style="color: rgb(66, 219, 66)">here</a> to go to the search page</p>-->
                    <!--                            </div>-->
                    <!--                        --><?php //}else {
                    //                            foreach ($posts as $post) {
                    //                                $post_view = new Post_Manager();
                    //                                echo $post_view->generate_post($post, $user);
                    //                            }
                    //                        }
                    ?>
                </section>
                <div class="more-post MorePostsScroll"  style="text-align: center;">
                    <img src="<?php echo $root?>/asset/img/loading_video.gif" style="width: 25px" alt="loading more post">
                    <input hidden type="hidden" value="-1" id="limitPost">
                </div>
                <div class="NoMorePostsDiv d-none">
                    <p style="color: #b1b1b1;text-align: center;font-size: 16px;margin-bottom: 10px;">No more posts</p>
                </div>
            </section>
        </section>
        <section class="right-section col-xl-3 ">
            <div class="d-xl-block pt-2 d-none">
                <section class="suggestions-section">
                    <div class="head d-flex justify-content-between align-items-center">
                        <h5>suggestions </h5>
                        <a href="#"><p>View All</p></a>
                    </div>
                    <div class="suggestions users">
                        <div class="user">
                            <a class="profile" href="#">
                                <div class="position-relative">
                                    <img src="./upload/default.jpg" alt="user-img">
                                </div>
                                <span class="name">Jawad amhoche</span>
                            </a>
                            <div class="add">
                                <i class="fad fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="user">
                            <a class="profile" href="#">
                                <div class="position-relative">
                                    <img src="upload/default.png" alt="user-img">
                                </div>
                                <span class="name">Badie bahida</span>
                            </a>
                            <div class="add">
                                <i class="fad fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="user">
                            <a class="profile" href="#">
                                <div class="position-relative">
                                    <img src="./upload/default.jpg" alt="user-img">
                                </div>
                                <span class="name">khadija ablhoche</span>
                            </a>
                            <div class="add">
                                <i class="fad fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="user">
                            <a class="profile" href="#">
                                <div class="position-relative">
                                    <img src="./upload/default.jpg" alt="user-img">
                                </div>
                                <span class="name">abd allitf os</span>
                            </a>
                            <div class="add">
                                <i class="fad fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="user">
                            <a class="profile" href="#">
                                <div class="position-relative">
                                    <img src="./upload/default.jpg" alt="user-img">
                                </div>
                                <span class="name">Amir bahida</span>
                            </a>
                            <div class="add">
                                <i class="fad fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </section>
</div>

<script src="asset/js/storyScroll.js"></script>
<script src="asset/js/videoPage.js"></script>
<script src="asset/js/post.js"></script>

</body>
</html>
