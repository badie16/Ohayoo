<?php

use classes\{Follow, User, Config, UserRelation};

$current = $user->getUserId();
$friend = $fetched_user->getUserId();

$follow = new Follow();
$follow->set_data(array(
    "follower"=>$current,
    "followed"=>$friend
));
$nmbOfFollow =Follow::get_user_followers_number($friend);

?>
    <div class="flex-row-column d-flex p-2 align-items-center">
        <form id="follow-form" class="flex follow-form follow-menu-header-form">
            <input type="hidden" name="current_profile_id" value="<?php echo $friend ?>">
            <?php
            if($follow->fetch_follow()) {
                $follow_unfollow_C = <<<EOS
                            <label  class="d-flex  ele post-to-option follow-button unfollow-black follow-label">
                                <i class="fal fa-heart-broken"></i>
                                <input type="submit" form="follow-form" hidden="" class="">
                                Unfollow
                            </label>                                                 
EOS;
                ?>
                <div class="follow-user-btn-container">
                    <label class="btnF2" style="--i:var(--bs-purple);color: var(--bg-color1)">
                        <i class="fa fa-heart"></i>
                        <input type="submit" class="follow-button" value="Followed <?php echo $nmbOfFollow?>">
                    </label>
                </div>

            <?php } else {

                $follow_unfollow_C = <<<EOS
                        <label  class="d-flex ele  post-to-option follow-button follow-black follow-label">
                                <i class="fal fa-heart"></i>
                                <input type="submit" form="follow-form" hidden="" class="">
                                Follow
                        </label>                                
EOS;
                ?>
                <div class="follow-user-btn-container">
                    <label class=" btnF2 " style="--i:var(--bs-pink);color: var(--bg-color1)">
                        <i class="fal fa-heart"></i>
                        <input type="submit" class="follow-button" value="Follow <?php echo $nmbOfFollow?>">
                    </label>
                </div>


            <?php } ?>
        </form>
        <form action="" method="POST" class="d-flex relation-form" enctype="multipart/form-data">
            <input type="hidden" name="current_profile_id" value="<?php echo $friend ?>">
            <?php
            $user_relation = new UserRelation();
            $user_relation->set_property("from_u", $current);
            $user_relation->set_property("to_u", $friend);

            $is_blocked = $user_relation->get_relation_by_status("B");
            $is_friend = $user_relation->get_relation_by_status("F");
            $is_pending = $user_relation->get_relation_by_status("P");
            /*
                                                                      -------  --------
                                                                        from      to
            */
            $wait_your_accept = $user_relation->micro_relation_exists($friend, $current, "P");
            $block_unblock_C="";
            if($is_blocked) {
                $block_unblock_C =  <<<EOS
                        <label class="ele block-user unblock-user-back" style="--i:var(--bs-secondary)">
                            <i class="fa fa-stop-circle"></i>
                            Unblock
                            <input type="submit" class="d-none" hidden>                            
                        </label>                          
EOS;
            }else{
                $block_unblock_C =  <<<EOS
                        <label class="ele block-user block-user-back" style="--i:rgba(var(--bs-danger-rgb),10%);color: rgb(248 113 113)">
                            <i class="fa fa-stop-circle"></i>
                            <input type="submit" class="d-none" hidden="" >
                            block
                         </label>                       
EOS;
            }
            $unfriend_C = "";
            if($is_friend){
                echo <<<EOS
                            <div>
                                <label class="btnF2" style="--i:var(--bs-green);color: var(--bg-color1)">
                                    <i class="fa fa-user-friends"></i>
                                    <span type="button" data-active=".option-menu-profile .menu-optionF2" onclick="toggleActiveClass(this)">Friend</span>
                                </label>
                            </div>
EOS;
                $unfriend_C = <<<EOS
                        <label class="ele unfriend-black">
                            <i class="fa fa-user-minus"></i>
                            <input type="submit" class="d-none unfriend" hidden="" >
                            Unfriend
                        </label>
EOS;
            } else if($is_pending) {
                echo <<<EOS
                        <div class="sendOrCancelRequestContainer">
                            <label class="btnF2 add-user " style="--i:var(--bs-secondary);color: var(--bg-color1)">
                                <i class="fa fa-user-minus "></i>
                                <input type="submit" class="" value="Cancel Request" >
                            </label>
                        </div>
EOS;
            } else if($wait_your_accept) {
                echo <<<EOS
                        <label class="btnF2 accept-user add-user-back" style="--i:var(--bs-success);color: var(--bg-color1)">
                            <i class="fa fa-user-plus "></i>
                            <input type="submit" class="" value="Accept" >
                        </label>
                        <label class="btnF2 decline-user unfriend-white-back" style="--i:var(--bs-danger);color: var(--bg-color1)">
                            <i class="fa fa-user-minus "></i>
                            <input type="submit" class="" value="Decline">
                        </label>                        
EOS;
            }
            else {
                echo <<<EOS
                    <div class="sendOrCancelRequestContainer">
                        <label class="btnF2 add-user "  style="--i:var(--bs-primary);color: var(--bg-color1)">
                            <i class="fa fa-user-plus "></i>
                            <input type="submit" value="Add">
                        </label>
                    </div>
EOS;
            }
            ?>
        </form>
        <?php
            echo <<<EOS
            <div class="relative option-menu-profile">
                <div class="btnF2 btn-more-option" data-active=".option-menu-profile .menu-optionF2" onclick="toggleActiveClass(this)" style="padding: ">
                    <i class="bi-three-dots"style="font-size:18px"></i>
                </div>
                <input type="hidden" name="current_profile_id" value="<?php echo $friend ?>">
                <div class="menu-option-event-V2  menu-optionF2 uk-dropdown">                                                              
                    <nav>
                        $unfriend_C                                                            
                        $follow_unfollow_C
                        <div class="ele report-user report">
                            <i class="fa fa-flag"></i>                           
                            Report 
                        </div>
                        <div class="ele share-user">
                            <i class="fa fa-share-from-square"></i>
                            Share Profile
                        </div>
                        <hr>
                        $block_unblock_C
                    </nav>
                </div>
            </div>
EOS;
        ?>
    </div>