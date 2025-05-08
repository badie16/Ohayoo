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
    <title>Home</title>
    <?php include "layouts/header.php" ?>
    <link rel="stylesheet" href="asset/css/simple-line-icons.css">
</head>


<body>
    <div class="home">
        <?php include "layouts/page_parts/partTop.php"?>
        <section class="d-flex justify-content-between">
            <?php include "layouts/page_parts/menuLeft.php"?>
            <section class="app-center center col-sm-10 col-md-9 col-12 col-xl-6">
                <section class="stories">
                    <div class="sliderStories">
<!--                        <div class="story add-story">-->
<!--                        </div>-->
                        <div class="story add-story">
                            <div class="add">
                                <i class="fa fa-plus"></i>
                            </div>
                            <p>Add Story</p>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-1.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-3.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-4.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-6.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-3.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-5.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" width="50px">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-5.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.png" alt="">
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                        <div class="story">
                            <img class="story-bg" src="upload/story-5.jpg" alt=""/>
                            <div class="info">
                                <div class="profile-img">
                                    <img src="./upload/default.jpg" alt="" >
                                </div>
                                <p class="name">Badie bahida</p>
                            </div>
                        </div>
                    </div>
                    <div class="btn-left-slider btn active"  onclick="sliderMoveLeft('.sliderStories')">
                        <i class="fa fa-angle-left"></i>
                    </div>
                    <div class="btn-right-slider btn active" style="--r:100%" onclick="sliderMoveRight('.sliderStories')">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </section>

                <section class="create-post" >
                        <div class="text-post">
                            <div class="info">
                                <img class="profile-img" src="<?php echo $current_user_picture ?>" alt="profile">
                            </div>
                            <label for="postText">
                                <textarea class="text-post-filed bi-textarea" type="text" name="postText" id="postText" form="formCreatePost" placeholder="What's on your mind?"></textarea>
                            </label>
                        </div>
                        <div class="upload-media d-flex">

                        </div>
                        <hr>
                        <div class="option-post">
                            <div class="media">
                                <input type="file" accept=".jpg,.jpeg,.png,.gif" id="postImg" multiple form="formCreatePost"/>
                                <label for="postImg">
                                    <i class="fad fa-images" style="--i:var(--bs-green)"></i>
                                    <p>Images</p>
                                </label>
                            </div>
                            <div class="media">
                                <input type="file" accept=".mp4,.webm,.mpg,.mp2,.mpeg,.mpe,.mpv,.ogg,.mp4,.m4p,.m4v,.avi" id="postVideo" form="formCreatePost"/>
                                <label for="postVideo">
                                    <i class="fad fa-film" style="--i:var(--bs-blue)"></i>
                                    <p>Video</p>
                                </label>
                            </div>
                            <div class="hashtag">
                                <i class="fad fa-hashtag" style="--i:var(--bs-red)"></i>
                                <p>Hashtag</p>
                            </div>
                            <form id="formCreatePost" name="formCreatePost" enctype="multipart/form-data">
                                <div class="left justify-content-end">
                                    <div class="dropdown visible">
                                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fad fa-earth" id="visible"></i>
                                        </button>
                                        <ul class="dropdown-menu visibleBox " style="">
                                            <li>
                                                <label class="dropdown-item ">
                                                    <div>
                                                        <i class="fad fa-earth"></i>
                                                        Public
                                                    </div>
                                                    <input type="radio" value="public" name="visible" checked>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item">
                                                    <div>
                                                        <i class="fad fa-user-friends"></i>
                                                        Friends
                                                    </div>
                                                    <input type="radio" value="friend" name="visible" >
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item">
                                                    <div>
                                                        <i class="fad fa-lock"></i>
                                                        Only me
                                                    </div>
                                                    <input type="radio" value="only_me" name="visible" >
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="submit"  disabled class="btn-post-create" value="Post">
                                    <input type="hidden"  value="<?php echo $user->getPropertyValue("userId");?>" name="post_owner">
                                </div>
                            </form>
                        </div>
                </section>
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
                <div class="d-xl-block d-none">
                    <section class="contacts-section">
                        <div class="head d-flex justify-content-between align-items-center">
                            <h5>Contact </h5>
                            <a href="#"><p>View All</p></a>
                        </div>
                        <div class="search-user search-f1">
                            <label>
                                <i class="fad fa-search"></i>
                                <input type="text" placeholder="Enter name to search" class="active">
                            </label>
                        </div>
                        <div class="contacts users">
                            <?php
                            $arrUser =  UserRelation::get_friendsWithOrder($user->getUserId(),"F",5);
                            foreach ($arrUser as $u){
                                $isOnline="";
                                $time = time()- strtotime($u->getPropertyValue("last_active"));
                                if ($time < 60){
                                    $isOnline="online";
                                }
                                $linkProfile = FG::generateUrlProfile($u,"username");
                                echo <<<U
                                    <div class="user $isOnline">                                        
                                            <div class="profile d-flex relative" >
                                                <a class="position-absolute w-100 h-100" style="z-index: 2;cursor: pointer" href="$linkProfile"></a>                                                                                                                                        
                                                <div class="position-relative">
                                                    <div class="onlineDot"></div>
                                                    <img src="$root{$u->getPropertyValue("picture")}" alt="user-img"/>
                                                </div>
                                                <span class="name">{$u->fullname()}</span>                                                                                                        
                                                <a href="#" class="ms-auto msg-link" style="z-index: 3">
                                                    <i class="btnF2 smaleBtn btn-outline-primary fa fa-paper-plane"></i> 
                                                </a>                                                                                                                                                                                                                                                                                                              
                                            </div>                                       
                                    </div>
U; }
                            ?>

                        </div>
                    </section>
                    <section class="suggestions-section">
                        <div class="head d-flex justify-content-between align-items-center">
                            <h5>suggestions </h5>
                            <a href="#"><p>View All</p></a>
                        </div>
                        <div class="suggestions users">
                            <?php
                                $users = \classes\User::getUsers($user->getUserId());
                                $c = 0;
                                foreach ($users as $k=> $u) {
                                    if ($c>= 5)break;
                                    $uF = new \classes\User();
                                    $uF->fetchUser("userId",$k);
                                    $link = FG::generateUrlProfile($uF,"username");
                                    echo <<<ER
                                <div class="user">
                                <a class="profile" href="$link">
                                    <div class="position-relative">
                                        <img src="$root{$uF->getPropertyValue("picture")}" alt="user-img">
                                    </div>
                                    <span class="name">{$uF->fullName()}</span>
                                </a>
                                <div class="add">
                                    <i class="fad fa-user-plus"></i>
                                </div>
                            </div>
ER;
                                    $c++;
                                }
                            ?>
                        </div>
                    </section>
                </div>
            </section>
        </section>
    </div>
    <div id="generatePostUp">
        <section>

        </section>
    </div>
    <script src="asset/js/storyScroll.js"></script>
    <script src="asset/js/home.js"></script>
    <script src="asset/js/post.js"></script>

</body>
</html>
