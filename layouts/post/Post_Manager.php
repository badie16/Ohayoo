<?php
    namespace layouts\post;
//    include "../../vendor/autoload.php";
//    include "../../core/init.php";
    use classes\{Config,User,Post,Comment,Like,FuncGlobal as FG};
    class Post_Manager {
        function generate_post($post, $user,$media=true): string
        {
            $root = Config::get("root/path");
            $current_user_id = $user->getPropertyValue("userId");
            $current_user_picture = Config::get("root/path") . (($user->getPropertyValue("picture") != "") ? $user->getPropertyValue("picture") : "/upload/default.jpg");
            $post_owner_user = new User();
            $post_owner_user->fetchUser("userId", $post->getPropertyValue("post_owner"));
            $post_owner_picture = Config::get("root/path") . (($post_owner_user->getPropertyValue("picture") != "") ? $post_owner_user->getPropertyValue("picture") : "/upload/default.jpg");
            $post_id= $post->getPropertyValue("post_id");
            $id_unique= $post->getPropertyValue("id_unique");
            $post_owner_name = $post_owner_user->fullName();
            $post_owner_actions = <<<EOO
                        <a class=" share-post ">
                            <i class="fal fa-share-all"></i>
                            Share post
                        </a>
                        <a class=" hide-post ">
                            <i class="fal fa-eye-slash"></i>
                            Hide post
                        </a>
EOO;
            //if post owner == user curent
            if($post->getPropertyValue("post_owner") == $user->getPropertyValue('userId')) {
                $post_owner_actions .= <<<EOO
                        <a class=" delete-post ">
                        <i class="fal fa-trash"></i>
                        Delete post
                        </a>                                     
                        <a class=" edit-post ">
                            <i class="fal fa-edit"></i>
                            Edit post
                        </a>               
EOO;
            }else{
                $post_owner_actions .= <<<EOO
                        <a class=" repost-post ">
                            <i class="fal fa-flag"></i>
                            Report post
                        </a>
EOO;
            }


            $post_date = $post->getPropertyValue("post_date");
            $post_date = date("F d \a\\t Y h:i A",strtotime($post_date)); //January 9 at 1:34 PM

            $post_visibility = "";
            if($post->getPropertyValue('post_visibility') == "public") {
                $post_visibility = '<i class="fad fa-earth"></i>';
            } else if($post->getPropertyValue('post_visibility') == "friend") {
                $post_visibility ='<i class="fad fa-user-friends"></i>';
            }  else if($post->getPropertyValue('post_visibility') == "only_me") {
                $post_visibility =' <i class="fad fa-lock"></i>';
            } 

            $post_owner_profile = Config::get("root/path") . "profile.php" . $post_owner_user->getPropertyValue("username");

            $image_components = "";
            $video_components = "";
            $post_mediaArr = $post->getPropertyValue("postMedia");
            if ($media) {
                foreach ($post_mediaArr as $media) {
                    if (!empty($media["type"]) && !empty($media["url"])) {
                        if ($media["type"] === "video") {
                            $video_components .= $this->generate_post_video($root . $media["url"], $media["url"]);
                        } else if ($media["type"] === "img") {
                            $image_components .= <<<SO
                                <a href="$root/p/post.php?p=$id_unique">
                                {$this->generate_post_image($root . $media["url"])}
                                </a>
SO;

                        }
                    }
                }
            }
            $post_text_content = htmlspecialchars_decode($post->getPropertyValue("text_content"));

            $like_class = "";
            $nodisplay = 'no-display';
            $shared_post_component = "";
            if(false) {
                // We don't want the entire post so we're forced to hard code it hhh
                //$shared_post_component = $this->generate_post($shared_post, $user);

                $shared = new Pst();
                $shared->fetchPost($post->getPropertyValue("post_shared_id"));

                $shared_post_owner_user = new User();
                $shared_post_owner_user->fetchUser("id", $shared->getPropertyValue("post_owner"));

                $shared_post_owner_picture = Config::get("root/path") . (($shared_post_owner_user->getPropertyValue("picture") != "") ? $shared_post_owner_user->getPropertyValue("picture") : "public/assets/images/logos/logo512.png");
                
                $shared_post_id= $shared->getPropertyValue("post_id");
                $shared_post_owner_name = $shared_post_owner_user->getPropertyValue("firstname") . " " . $shared_post_owner_user->getPropertyValue("lastname") . " -@" . $shared_post_owner_user->getPropertyValue("username");

                $shared_post_date = $shared->getPropertyValue("post_date");
                $shared_post_date = date("F d \a\\t Y h:i A",strtotime($shared_post_date)); //January 9 at 1:34 PM

                $shared_post_owner_profile = Config::get("root/path") . "profile.php" . $shared_post_owner_user->getPropertyValue("username");

                $shared_post_visibility = "";
                if($post->getPropertyValue('post_visibility') == 1) {
                    $shared_post_visibility = "public/assets/images/icons/public-white.png";
                } else if($post->getPropertyValue('post_visibility') == 2) {
                    $shared_post_visibility = "public/assets/images/icons/group-w.png";
                }  else if($post->getPropertyValue('post_visibility') == 3) {
                    $shared_post_visibility = "public/assets/images/icons/lock-white.png";
                }

                $shared_image_components = "";
                $shared_video_components = "";

                $shared_post_images_location = $shared->getPropertyValue("picture_media");
                $shared_post_videos_location = $shared->getPropertyValue("video_media");

                $shared_post_images_dir = $project_path . $shared->getPropertyValue("picture_media");
                $shared_post_videos_dir = $project_path . $shared->getPropertyValue("video_media");

                if($post->getPropertyValue('post_shared_id') == null) {
                    $shared_post_text_content = <<<e
                        <div class="clickable">
                            <a href="#" class="post-text link-style-3">POST DELETED</a>
                            <p class="regular-text post-text"><em>The owner of this post is either delete that post or make it private ..</em></p>
                        </div>
                    e;
                } else {
                    $shared_post_text_content = htmlspecialchars_decode($shared->getPropertyValue("text_content"));
                }

                if(is_dir($shared_post_images_dir) && $shared_post_images_dir != $project_path) {
                    if($this->is_dir_empty($shared_post_images_dir) == false) {
                        $fileSystemIterator = new \FilesystemIterator($shared_post_images_dir);
                        foreach ($fileSystemIterator as $fileInfo){
                            $shared_image_components .= $this->generate_post_image($root . $shared_post_images_location . $fileInfo->getFilename());
                        }
                    }
                }

                if(is_dir($shared_post_videos_dir) && $shared_post_videos_dir != $project_path) {
                    if($this->is_dir_empty($shared_post_videos_dir) == false) {

                        $fileSystemIterator = new \FilesystemIterator($shared_post_videos_dir);
                        foreach ($fileSystemIterator as $fileInfo){
                            $src = $root . $shared_post_videos_location . $fileInfo->getFilename();
                            $shared_video_components = <<<VIDEO
                            <video class="post-video" controls>
                                <source src="$src" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
    VIDEO;
                        }
                    }
                }

                $shared_post_component = <<<SHARED_POST
                <div class="post-item ">
                    <div class="timeline-post image-post">
                        <div class="post-header flex-space">
                            <div class="post-header-without-more-button">
                                <a class="post-owner-picture-container" href="">
                                    <img src="$shared_post_owner_picture" class="post-owner-picture" alt="">
                                </a>
                                <div class="post-header-textual-section">
                                    <a href="$shared_post_owner_profile" class="post-owner-name">$shared_post_owner_name</a>
                                    <div class="row-v-flex">
                                        <p class="regular-text"><a href="" class="post-date">$shared_post_date</a> <span style="font-size: 8px">.</span></p>
                                        <img src="$shared_post_visibility" class="image-style-8" alt="" style="margin-left: 8px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="post-text">
                            $shared_post_text_content
                        </p>
                        <div class="media-container">
                            $shared_video_components
                            $shared_image_components
                        </div>
                    </div>
                    <input type="hidden" class="pid" value="$shared_post_id">
                </div>
SHARED_POST;
            }

            // Comment meta
            $comments_count = Comment::count_post_comments($post_id);
            $comments_count = FG::sanitize_num($comments_count);


            $like_manager = new Like();
            $likes_count = count($like_manager->get_post_users_likes_by_post($post_id));
            $likes_count = FG::sanitize_num($likes_count);
//
//            if(($shares = Pst::get_post_share_numbers($post_id)) > 0) {
//                $nodisplay = '';
//                $post_meta_share = <<<SM
//                <div class="post-meta-shares post-meta"><span class="meta-count">$shares</span>Shares</div>
//SM;
//            }

            $like_manager->setData(array(
                "user_id"=>$current_user_id,
                "post_id"=>$post_id
            ));
            $like_class = "";
            if($like_manager->exists()) {
                $like_class = "active";
            }

//            $comments_components = '';
//            foreach(Comment::fetch_post_comments($post_id) as $comment) {
//                $cm = new Comment();
//                $cm->fetch_comment($comment->id);
//
//                $comments_components .= self::generate_comment($cm, $current_user_id);
//            }
            $profileUrl = FG::generateUrlProfile($post_owner_user,"username");
            return <<<EOS
        <div class="post-item newPost handle">
				<div class="timeLine-post">
					<div
						class="post-header d-flex justify-content-between align-items-center"
					>
						<div class="left d-flex">
							<a class="profile" href="$profileUrl">
								<img
									src="$post_owner_picture"
									class="post-user-profile imgF1"
									alt=""
								/>
							</a>
							<div>
								<a href="$profileUrl"><h5 class="post-user-name m-0">$post_owner_name</h5></a>
								<div class="d-flex align-items-center">
									<span class="post-date">$post_date</span>
									$post_visibility
								</div>
							</div>
						</div>
						<div class="right">
							<i class="btnF1 circle bi-three-dots-vertical btn-more-option"></i>
							<div class="uk-dropdown">
							    <nav>
							    $post_owner_actions
                                </nav>
							    
                            </div>
						</div>
					</div>
					<div class="post-text">
					    <div class="post-edit-container relative">
                            <textarea autocomplete="off" class="editable-input post-editable-text"></textarea>
                            <i class="close-post-edit fa fa-close"> </i>
                            <button class="submit-editing btnF1 circle">
                                  <i class="fa fa-paper-plane-top"></i>
                            </button>
                        </div>
						<p class="text">
							$post_text_content
						</p>
					</div>					
					<div class="post-media">								
					    $image_components
						$video_components
					</div>
					<hr />
					<div class="post-option-btn">
						<div class="left">
							<div class="btn-option post-meta-like" id="like">							  
								<button class="$like_class" id="like">
									<i class="fal fa-thumbs-up"></i>
								</button>
								<span class="value">$likes_count</span>
							</div>
							<div class="btn-option post-meta-comment">
								<button class="" id="comment">
									<i class="fal fa-comment"></i>
								</button>
								<span class="value">$comments_count</span>
							</div>
							<div class="btn-option post-meta-share">
								<button class="" id="share">
									<i class="fal fa-share"></i>
								</button>
								<span class="value">77</span>
							</div>
						</div>
						<div class="right"></div>
					</div>
				</div>
				<input type="hidden" class="postId" value="$post_id" />
			</div> 
EOS;

        }

        public static function generate_comment($comment, $current_user): string
        {

            $comment_owner = new User();
            $comment_owner->fetchUser('userId', $comment->getPropertyValue("comment_owner"));

            $comment_owner_picture = Config::get("root/path") .
                (empty($comment_owner->getPropertyValue("picture")) ? "/upload/default.jpg" : $comment_owner->getPropertyValue("picture"));
            $comment_owner_username = $comment_owner->getPropertyValue("username");
            $comment_owner_fullName = $comment_owner->getPropertyValue("firstname")." ".$comment_owner->getPropertyValue("lastname");;
            $comment_owner_profile = Config::get("root/path") . "profile.php" . $comment_owner_username;
            $comment_text = $comment->getPropertyValue("comment_text");
            $comment_id = $comment->getPropertyValue("comment_id");

            $now = strtotime("now");
            $seconds = floor($now - strtotime($comment->getPropertyValue("comment_date")));
            if ($seconds > 29030400) {
                $comment_life = floor($seconds / 29030400) . "y";
            } else if ($seconds > 604799 && $seconds < 29030400) {
                $comment_life = floor($seconds / 604800) . "w";
            } else if ($seconds < 604799 && $seconds > 86400) {
                $comment_life = floor($seconds / 86400) . "d";
            } else if ($seconds < 86400 && $seconds > 3600) {
                $comment_life = floor($seconds / 3600) . "h";
            } else if ($seconds < 3600 && $seconds > 60) {
                $comment_life = floor($seconds / 60) . "min";
            } else if ($seconds > 15) {
                $comment_life = $seconds . "sec";
            } else {
                $comment_life = "Now";
            }

            /*
                Here we want to give the user the ability to delete a comment only in two situations:
                1- If the comment owner if the same user logged in
                2- if the user who is currently logging in is the owner of the post (Here we need to get post owner from Post table
                by using comment post_id)
                -----------
                First we get the post id from comment and then pass it to get_post_owner function to get the owner of post
                Notice only the comment owner could edit his comment. The post owner could only delete it
            */

            $comment_options = <<<CO
        <div class="sub-options-container">
            <div class="options-container-style-1 black">
CO;
            $owner_of_post_contains_current_comment = $comment->getPropertyValue('post_id');
            // We use Pst as Post model alias cauz we alsready have Post view manager in use
            $owner_of_post_contains_current_comment = Post::get_post_owner($owner_of_post_contains_current_comment);
            $owner_of_post_contains_current_comment = $owner_of_post_contains_current_comment["post_owner"];
            $current_user_id = $current_user->getPropertyValue("userId");
            if (($comment->getPropertyValue("comment_owner") == $current_user_id)
                || $current_user_id == $owner_of_post_contains_current_comment) {
                if ($comment->getPropertyValue("comment_owner") == $current_user_id) {
                    $comment_options .= <<<CO
                <div class="sub-option-style-2">
                    <a class="black-link edit-comment">
                    <i class="fa fa-edit"></i>
                    Edit comment</a>
                </div>
CO;
                }
                $comment_options .= <<<CO
                <div class="sub-option-style-2">
                    <a class="black-link delete-comment">
                    <i class="fa fa-trash"></i>
                    Delete comment</a>
                </div>
CO;
            }
            $comment_options .= <<<CO
            <div class="sub-option-style-2">           
                <a class="black-link hide-button">
                <i class="fa fa-eye-slash"></i>
                Hide comment
                </a>
            </div>
        </div>
</div>
CO;
            $comment_replay = "";
            $comment_replayArr = Comment::getReplayComments($comment->getPropertyValue("comment_id"));
            foreach ($comment_replayArr as $replay){
                $comment_replay .= Post_Manager::generate_comment($replay,$current_user);
            }

            return <<<COM
        <div class="comment-item">
            <div class="comment-block">
                <div class="small-text hidden-comment-hint" style="display: none;">
                    The comment is hidden ! click 
                    <span class="show-comment">here</span>
                    to show it again
                </div>
                <input type="hidden" class="comment_id" value="$comment_id" />
                <div class="profile comment-op">
                    <img src="$comment_owner_picture" class="profile-img imgF1 user-img" alt="profile" />
                </div>
                <div class="comment-global-wrapper">
                    <div class="d-flex align-items-center">
                        <div class="comment-wrapper">
                            <a href="#" class="user-name">$comment_owner_fullName</a>
                            <div class="comment">
                                <p class="comment-text">$comment_text</p>
                                <div class="comment-edit-container relative">
                                    <textarea autocomplete="off" class="editable-input comment-editable-text"></textarea>
                                    <i class="close-edit fa fa-close"> </i>
                                    <button class="comment-btn">
                                        <i class="fa fa-paper-plane-top"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="comment-menu">
<!--                            <div class="btn-option-comment-menu">-->
                                <i class="bi-three-dots btn-option-comment-menu" id="btn-comment-menu"></i>
<!--                            </div>-->
                            $comment_options
                        </div>
                    </div>
                    <div class="comment-btn-2">
                        <button class="like-comment">like</button>
                        <button class="reply-comment">reply</button>
                        <p class="regular-text-style-2">
                            . <span class="time-of-comment">$comment_life</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="replay-comments">
                $comment_replay
            </div>
        </div>
COM;
        }
        public function generate_post_image($url): string
        {
            return <<<PI
                <div class="post-media-item relative">
                    <img src="$url" alt="post" />
                    <div class="post-view-button"></div>
                </div>                
            PI;
        }
        public function generate_post_video($url,$src): string
        {
            return <<<VI
                    <div class="post-media-item">
                        <video controls class="video-js" data-setup="{}" >
                            <source src="$url"/>
                            <source src="$src"/>
                            <!--<script src="{$GLOBALS["config"]["root"]["path"]}/asset/js/video.min.js"></script>-->                          
                        </video>
                    </div>
                VI;
        }
        function is_dir_empty($dir) {
            return (count(glob("$dir/*")) === 0); // empty
        }
        function generatePostMediaSlide($post, $user): string
        {
            $root = Config::get("root/path");
            $components = array();

            $post_mediaArr = $post->getPropertyValue("postMedia");
            if (count($post_mediaArr) == 0){
                return <<<SO
                    <h2>no media  found</h2>
SO;
            }
            foreach ($post_mediaArr as $media) {
                if (!empty($media["type"]) && !empty($media["url"])) {
                    if ($media["type"] === "video") {
                        $components[] = $this->generate_post_video($root . $media["url"], $media["url"]);
                    } else if ($media["type"] === "img") {
                        $components[] = $this->generate_post_image($root . $media["url"]);
                    }
                }
            }
            $isMultiple = false;
            if (count($post_mediaArr) >1){
                $isMultiple = true;
            }
            $btnSlide = "";
            $slide="";
            $i=0;
            $active = "active";
            foreach ($components as $cp){
                if ($isMultiple) {
                    $btnSlide .= '<button type="button" data-bs-target="#slideImg" data-bs-slide-to="' . $i . '" class="' . $active . '" aria-current="true" aria-label="Slide ' . ++$i . '"></button>';
                }
                $slide .='
                    <div class="carousel-item h-100 active">'.
                           $cp
                    .'</div>
                ';
                $active ="";

            }
            $btnNextPre = "";
            $btnSlideC="";
            if ($isMultiple){
                $btnNextPre = '<button class="carousel-control-prev" type="button" data-bs-target="#slideImg" data-bs-slide="prev">
                       <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                       <span class="visually-hidden">Previous</span>
                   </button>
                   <button class="carousel-control-next" type="button" data-bs-target="#slideImg" data-bs-slide="next">
                       <span class="carousel-control-next-icon" aria-hidden="true"></span>
                       <span class="visually-hidden">Next</span>
                   </button>';
                $btnSlideC = '<div class="carousel-indicators ">'.
                       $btnSlide
                   .'</div>';
            }
            return <<<SO
               <div id="slideImg" class="carousel slide">
                   $btnSlideC
                   <div class="carousel-inner h-100">
                       $slide
                   </div>
                   $btnNextPre                   
               </div>
SO;
        }
    }


//    include "../header.php";
//    $post_Manager = new Post_Manager();
//    $post = new Post();
//    $post->fetchPost(97);
//    $user = new User();
//    $user->fetchUser("userId",1382513644);
//    echo $post_Manager->generate_post($post,$user);
//    $like_manager = new Like();
//    $like_manager->setData(array(
//        "user_id"=>$user->getPropertyValue("userId"),
//        "post_id"=>$post->post_id
//    ));
//    $like_class = "";
//    if($like_manager->exists()) {
//        $like_class = "active";
//    }
//    var_export($like_manager->exists());
//    include "../header.php";
//    $comment = new Comment();
//    $comment->fetch_comment("comment_id",4);
//    $user = new User();
////    $user->fetchUser("userId",1084115894);
//    echo Post_Manager::generate_comment($comment,$user);




?>





