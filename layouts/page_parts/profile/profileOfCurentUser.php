<?php

use classes\{Session, Token};

?>
<div class="viewer centerFlex edit-profile-view" style="display: none">
    <div class="close-view"></div>
    <div id="edit-profile-container">
        <div class="flex-space" id="edit-profile-header">
            <i href="" class="close-viewer-btn btn btn-danger fa fa-close btn-show-edit-profile-view"></i>
            <h5 class="m-0">Edit profile</h5>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="save-profile-edits-form" enctype="multipart/form-data">
                <input type="hidden" name="save_token" value="">
                <input type="submit" value="Save"  name="save_profile_edits" class="btnSave btn px-3 btn-sm btn-primary rounded-3">
                <button class="btn btn-sm btn-primary btnLanding" style="display: none" disabled>
                    <span class="spinner-border spinner-border-sm"></span>
                    Loading..
                </button>
            </form>
        </div>
        <div id="edit-sub-container">
            <div id="picture-and-cover-container">
                <div href="" id="change-cover-button">
                    <div id="cover-changer-container" class="relative">
                        <img src="<?php echo $root.$fetched_user->getPropertyValue("cover"); ?>" id="cover-changer-dim" alt="">
                        <label class="change-image-icon position-absolute">
                            <i class="fa fa-image" ></i>
                            <input type="file" class="d-none" id="change-cover" name="cover" form="save-profile-edits-form">
                        </label>
                    </div>
                </div>
                <div class="relative justify-content-center d-flex">
                    <div id="change-picture-button" class="absolute">
                        <div id="picture-changer-container" class="relative">
                            <img src="<?php echo $current_user_picture ?>" class="former-picture-dim" alt="">
                            <label class="position-absolute change-image-icon">
                                <i class="fa fa-image" ></i>
                                <input type="file" class="d-none" id="change-avatar"  name="picture" form="save-profile-edits-form">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="textual-data-edit">
                <div class="field-style-1">
                    <label class="label-style-1">
                        First name
                        <input type="text" form="save-profile-edits-form" value="<?php echo htmlspecialchars($fetched_user->getPropertyValue("firstname")); ?>" name="firstname">
                    </label>
                </div>
                <div class="field-style-1">
                    <label for="display-name" class="label-style-1">
                        Last name
                        <input type="text" form="save-profile-edits-form"  value="<?php echo htmlspecialchars($fetched_user->getPropertyValue("lastname")); ?>" name="lastname">
                    </label>
                </div>
                <div class="field-style-1">
                    <label for="bio" class="label-style-1">
                        Bio
                        <textarea type="text" form="save-profile-edits-form" maxlength="800"  placeholder="Add your bio.." name="bio"><?php echo $fetched_user->getPropertyValue('bio'); ?></textarea>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>