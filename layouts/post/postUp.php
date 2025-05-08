<?php
global $user,$root;
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";
use layouts\post\Post_Manager;
use classes\{Post,Comment};
if (!isset($_GET["postId"])){
    echo "-1";
    return;
}
$pm = new Post_Manager();
$post = new Post();
$post->fetchPost($_GET["postId"]);
$postItem = $pm->generate_post($post,$user);
$current_user_picture = $root.$user->getPropertyValue("picture");
$comments ="";
$commentArr = Comment::fetch_post_comments($_GET["postId"]);
foreach ($commentArr as $comment){
    $comments.=Post_Manager::generate_comment($comment,$user);
}

$output =  <<<post
<section class="post-up post-up2">
	<div class="bg-box-black" id="closeCommentBox"></div>
	<div class="post-up-content">
		<div class="post-up-header">
			<i class="fa fa-close" id="closeCommentBox"></i>
		</div>
		<div class="post-up-center">
			$postItem
			<div class="comment-container">
				<div class="comment-content">                    
					$comments
				</div>
			</div>
		</div>
		<div class="comment-form-option">
			<div class="comment-op">
				<div class="profile">
					<img src="$current_user_picture" class="profile-img" alt="proile" />
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
</section>                  
post;
echo $output;