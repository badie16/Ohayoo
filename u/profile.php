<?php
global $user,$root;
require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\{UserRelation, User, Post, FuncGlobal as FG, Validation};
use layouts\post\Post_Manager;



$_SESSION['pageC'] = "profile.php";
if(!$user->isLoggedIn()){
    FG::goToLocation("login.php");
}
$current_user_picture = $root.$user->getPropertyValue("picture");

if (!isset($_GET['u'])){
    $_GET['u'] = $user->getPropertyValue("username");
}
if (!User::user_exists("username",$_GET['u'])){
    header("location: $root/404.html");
}
$fetched_user = new User();
$fetched_user->fetchUser("username",$_GET['u']);
$isCurrentUser =false;
if ($fetched_user->getUserId() == $user->getUserId()){
    $isCurrentUser = true;
}
$numberOfFriends = UserRelation::get_friends_number($fetched_user->getUserId());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../asset/css/profile.css">
    <?php include "../layouts/header.php" ?>
</head>
<body data-page="profile">
<div class="profileMain menuV2">
    <?php include "../layouts/page_parts/partTop.php" ?>
    <section class="d-flex">
        <?php include "../layouts/page_parts/menuLeft.php" ?>
        <section class="app-center center">
            <section id="first-section" class="shadowF1">
                <div class="relative ">
                    <div class="cover-container">
                        <?php
                        $cover = $fetched_user->getPropertyValue("cover");
                        if (isset($cover)){
                            echo '<img src="'.$root.$fetched_user->getPropertyValue("cover").'" class="cover-photo" alt="">';
                        }
                        ?>

                    </div>
                    <div id="profile-picture-container">
                        <div class="relative ">
                            <div class="profile-picture-cnt">
                                <img src="<?php echo $root.$fetched_user->getPropertyValue("picture") ?>" class="profile-picture" alt="">
                                <?php
                                    if($isCurrentUser){
                                        echo "<i class='fa fa-camera btnImg'></i>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info">
                    <div class="name-and-username-container">
                        <h1><?php echo$fetched_user->fullName() ?></h1>
                        <input class="userOfP" type="hidden" value="<?php echo $fetched_user->getUserId()?>">
                        <p >@<?php echo $fetched_user->getPropertyValue("username"); ?></p>
                    </div>
                    <div class="bio">
                       <?php echo $fetched_user->getPropertyValue("bio")?>
                    </div>
                </div>
                <div class="pe-3 ps-3 d-flex  justify-content-between profile-menu">
                    <nav class="d-flex gap-2">
                        <a href="#" class="active">Timeline</a>
                        <a href="#" class="">Friend </a>
                        <a href="#" class="">Photo</a>
                        <a href="#" class="">Video</a>
                    </nav>
                    <?php
                        if ($isCurrentUser){
                            echo "<div class='btnF2 editBtnF1 btn-show-edit-profile-view' >Edit Profile</div>";
                            include "../layouts/page_parts/profile/profileOfCurentUser.php";
                        }else{
                            include "../layouts/page_parts/profile/contact-header.php";
                        }
                    ?>
                </div>
            </section>
            <section id="second-section" class="d-flex container">
                <!-- feed posts -->
                <div class="left col-lg-7 col-12">
                    <?php
                     if($isCurrentUser){
                    echo <<<CreatPost
                        <section class="create-post" >
                        <div class="text-post">
                            <div class="info">
                                <img class="profile-img" src="$current_user_picture" alt="profile">
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
                                    <input type="hidden"  value="{$user->getPropertyValue("userId")}" name="post_owner">
                                </div>
                            </form>
                        </div>
                    </section>
CreatPost;
                     }
                    ?>
                    <section class="postes-global">
                        <section class="posts">
                            <?php
                                    $lastPost = -1;
                                    $posts = Post::fetchPostsOfUser($fetched_user->getUserId(),$user->getUserId());
                                    if(count($posts) == 0) {
                                        if(!$isCurrentUser){
                                            echo '<div id="empty-posts-message">
                                                        <h3>Try to add friends, or follow them to<BR> see their posts ..</h3>
                                                   </div>';
                                        }else{
                                            echo '<div id="empty-posts-message">
                                                        <h3>Try to add new Post</h3>
                                                   </div>';
                                        }
                                    }else {
                                        foreach ($posts as $post) {
                                            $lastPost = $post->getPropertyValue("post_id");
                                            $post_view = new Post_Manager();
                                            echo $post_view->generate_post($post, $user);
                                        }
                                    }
                            ?>
                        </section>
                        <div class="more-post MorePostsScroll"  style="text-align: center;">
                            <img src="<?php echo $root?>/asset/img/loading_video.gif" style="width: 25px" alt="loading more post">
                            <input hidden type="hidden" value="<?php echo $lastPost?>" id="limitPost">
                        </div>
                        <div class="NoMorePostsDiv d-none">
                            <p style="color: #b1b1b1;text-align: center;font-size: 16px;margin-bottom: 10px;">No more posts</p>
                        </div>
                    </section>
                </div>
                <!-- slide bar -->
                <div class="right d-none d-lg-block col-lg-5">
                    <div>
                        <div class="card into-container cardF1 shadow-xss rounded-xxl">
                            <div class="card-body head d-flex align-items-center  p-3">
                                <h5 class="fw-700 mb-0 font-xssss text-grey-900">Into</h5>
                                <?php
                                if($isCurrentUser){
                                    echo '<a href="#" class="fw-600 ms-auto font-xssss text-primary">Edit</a>';
                                }
                                ?>

                            </div>
                            <ul class="ps-4">
                                <li class="d-flex align-items-center gap-3">
                                    <i class="fal fa-calendar-alt"></i>
                                    <div>  Member since  <span class="text-black"> March 2024  </span> </div>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="fal fa-location-dot"></i>
                                    <div>  Live In <span class="text-black"> Troudante , Maroc  </span> </div>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="fal fa-graduation-cap"></i>
                                    <div>  Studied at <span class=" text-black"> University of FPT  </span> </div>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="fal fa-camera"></i>
                                    <div>  Works at <span class=" text-black">  Envanto Martket </span> </div>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="fal fa-camera"></i>
                                    <div>  Flowwed By <span class=" text-black"> 3,240 People </span> </div>
                                </li>

                            </ul>
                        </div>
                        <div class="card media-container cardF1 shadow-xss rounded-xxl">
                            <div class="card-body head d-flex align-items-center  p-3">
                                <h5 class="fw-700 mb-0 font-xssss text-grey-900">Photos</h5>
                                <a href="#" class="fw-600 ms-auto font-xssss text-primary">See all</a>
                            </div>
                            <div class=" items card-body">
                                <?php
                                    $Photos = Post::getPostMediaOfUser($fetched_user->getUserId(),'img',9);
                                    foreach ($Photos as $img){
                                        echo <<<IMG
                                             <div class="item">
                                                <a href="$root/{$img['url']}" class="h-100 w-100">
                                                    <div data-img="" class="img-fluid rounded-3 w-100" style="background-image: url('$root/{$img['url']}')"></div>
                                                    <input type="hidden" class="pid" value="{$img["id_unique"]}">
                                                </a>
                                             </div>
IMG;
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="card friends-container cardF1 shadow-xss rounded-xxl">

                            <div class="card-body head d-flex align-items-center  p-3">
                                <div>
                                    <h5 class="fw-700 mb-0 font-xssss text-grey-900">Friends</h5>
                                    <span class="text-gray-300"><?php echo $numberOfFriends?> Friends</span>
                                </div>
                                <?php if ($numberOfFriends) {
                                    echo '<a href="#" class="fw-600 ms-auto font-xssss text-primary" > Find Friend </a >';
                                }
                                ?>
                            </div>
                            <div class=" items card-body">
                                <?php

                                    if($isCurrentUser && !$numberOfFriends){
                                        echo "Try to add friends";
                                    }else{
                                        $friends = UserRelation::get_friends($fetched_user->getUserId());
                                        foreach ($friends as $f){
                                            $profileUrl = FG::generateUrlProfile($f,"username");
                                            echo <<<F
                                           <div class="item">
                                                <a href="$profileUrl">
                                                    <div class="profile">
                                                        <img src="$root/{$f->getPropertyValue("picture")}" alt="@{$f->getPropertyValue("username")}">
                                                        <div class="info">
                                                            <h5 class="fullName">{$f->fullname()}</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div> 
F;
                                        }
                                    }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
    <div id="generatePostUp">
        <section>

        </section>
    </div>
</div>
<script src="<?php echo $root?>/asset/js/profile.js" defer></script>
<script src="<?php echo $root?>/asset/js/post.js"></script>
</body>

