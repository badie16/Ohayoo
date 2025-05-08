const postTextFiled = $(".text-post-filed");
const btnPost = document.querySelector(".btn-post-create")
postTextFiled.on({
    input : function (){
        if (postTextFiled.val() === ""){
            btnPost.setAttribute("disabled","");
        }else {
            btnPost.removeAttribute("disabled")
        }
    }
})

const visible = $("#visible");
const  visibleBox = $(".visibleBox");
const  visibleChois = $(".visibleBox input")
visible.click(
    function (){
        visibleBox.toggleClass("active");
    }
)
const  arrIconVisible = ["earth","user-friends","lock"];
visibleChois.each((k,e)=>{
    e.addEventListener("input",(ele)=>{
        visible.get(0).classList = "fad fa-"+arrIconVisible[k];
        visibleBox.removeClass("active");
    })
})


const  handle_video = function() {
    $("video").toArray().forEach((e)=>{
        videojs(e)
        // var reader = new FileReader();
        // let selectedFile = new Blob(e.src);
        // new VideoFrame().
        // console.log(selectedFile.src)
        // reader.readAsDataURL(selectedFile);
        // reader.onload = function(e) {
        //     let thumbnail = "";
        //     try {
        //         // get the frame at 1.5 seconds of the video file
        //
        //         let response =  getVideoCover(selectedFile, 0.0);
        //         e.setAttributes("poster",response)
        //
        //     } catch (ex) {
        //         console.log("ERROR: ", ex);
        //     }
        // };
    })
}

handle_video();
$("#formCreatePost").submit( (e) => {
    e.preventDefault();
    $(".btn-post-create").attr('value', "POSTING ..");
    $(".btn-post-create").attr("disabled");
    postTextFiled.val(postTextFiled.val().replace(/\n/g, '<br/>'));
    let formData = new FormData($("#formCreatePost").get(0));
    $("#postImg").get(0).removeAttribute("disabled")
    $("#postVideo").get(0).removeAttribute("disabled")
    // Append image files
    for(let i = 0;i<uploaded_post_assets.length;i++) {
        formData.append(uploaded_post_assets[i].name, uploaded_post_assets[i]);
    }
    // Append video files
    for(let i = 0;i<uploaded_post_assets_videos.length;i++) {
        formData.append(uploaded_post_assets_videos[i].name, uploaded_post_assets_videos[i]);
    }
    $.ajax({
        url: root+"api/post/addPost.php?i",
        method: 'POST',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: formData,
        success: function(response){
            console.log( response);
            $(".btn-post-create").attr('value', "POST");
            // Clear text
            $("#postText").val("");
            // Remove image template components
            $(".upload-media").find(".post-creation-item").remove();
            // Clear file
            restArrayFile();
            $(".posts").prepend(generatePostLoadingInfo());
            // generate the last post of user
            $.ajax({
                type: 'GET',
                url: root+"layouts/post/generate_last_post.php",
                success: function(component) {
                    $(".posts").prepend(component);
                    handle_video()
                    let post = $(".post-item").first();
                    handle_post_assets(post);
                    handlePost(post)
                    post.removeClass("d-none newPost")
                    $(".PostLoadingInfo").remove();
                }
            })
        }
    })
}
);

function restArrayFile(){
    $("#postImg").val("");
    $("#postVideo").val("")
    uploaded_post_assets = [];
    upa_counter = 0;
    uploaded_post_assets_videos = [];
}


/* start img upload */
let uploaded_post_assets = [];
let upa_counter = 0;
$("#postImg").change(function(event) {
    $("#postVideo").get(0).setAttribute("disabled","")
    // First get the new uploaded files and append them to our new array uploaded_post_assets
    let files = event.originalEvent.target.files;
    if(files.length !== validate_image_file_Type(files).length) {
        // $(".red-message").css("display", "flex");
        // $(".red-message-text").text("Some files have invalid format: Only JPG/PNG/JPEG and GIF files formats are supported.");
        window.alert("invalide img");
    }
    files = validate_image_file_Type(files);
    uploaded_post_assets.push(...files);
    // Then get the component skeleton
    $.ajax({
        type: 'GET',
        url: root+"layouts/post/generate_post_creation.php?type=img",
        success: function(response) {
            let container = response;
            // We check if there's no file and text area is empty we hide the share button
            if(files.length === 0 && postTextFiled.val() === "") {
                btnPost.setAttribute("disabled","");
            }else {
                btnPost.removeAttribute("disabled")
            }
            // Now we loop through the new files and append components to post component as small images to show them to user
            for (let i = 0; i < files.length; i++) {
                /*
                    Here first you need to check the incoming data and based on it, you can either decide to show the image or keep it none displayed
                    in case it is a malicious file or not an appropriate image
                */
                $(".upload-media").append(container);
                // We search for the last div added and go deep to the image to get the element
                let imgtag = $(".upload-media .post-creation-item").last().find(".image-post-uploaded");
                var selectedFile = files[i];
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgtag.attr("src", e.target.result)
                    // Here we adjust the image in center and choose height if width is greather and width if height is greather
                    if(imgtag.height() >= imgtag.width()) {
                        imgtag.width("100%");
                    } else {
                        imgtag.height("100%");
                    }
                    // Here we call this function to adjust indexes
                    adjust_post_uploaded_assets_indexes();
                    if(upa_counter === 0) {
                        $(".delete-uploaded-item").click(function() {
                            adjust_post_uploaded_assets_indexes();
                            let delete_index = Number($(this).parent().find(".pciid").val());
                            console.log("delete : " + delete_index);
                            let new_arr = [];
                            let cn = 0;
                            for(let k=0; k<uploaded_post_assets.length; k++) {
                                if(k !== delete_index) {
                                    new_arr[cn] = uploaded_post_assets[k];
                                    cn++;
                                }
                            }
                            // We remove it's component
                            $(this).parent().remove();
                            // We check if there's no file and text area is empty we hide the share button
                            if(new_arr.length === 0 && postTextFiled.val() === "") {
                                btnPost.setAttribute("disabled","");
                                $("#postVideo").get(0).removeAttribute("disabled")
                            }else {
                                btnPost.removeAttribute("disabled")
                                $("#postVideo").get(0).setAttribute("disabled","")
                            }

                            //we assign the new array which has deleted item removed to uploaded_post_assets array
                            uploaded_post_assets = new_arr;
                        });

                        upa_counter++;
                    }
                };

                reader.readAsDataURL(selectedFile);
            }

            upa_counter = 0;
        }
    });
})
/* end img upload */
/* start  video upload */
let uploaded_post_assets_videos = [];
$("#postVideo").change(function(event) {
    let files = event.originalEvent.target.files;
    if(files.length !== validate_video_file_Type(files).length) {
        // $(".red-message").css("display", "flex");
        // $(".red-message-text").text("Some files have invalid format: Only .mp4,.webm,.mpg,.mp2,.mpeg,.mpe,.mpv,.ogg,.mp4,.m4p,.m4v,.avi file formats are supported.");
        window.alert("invalide video")
        return false;
    }
    $("#postImg").get(0).setAttribute("disabled","")
    files = validate_video_file_Type(files);
    uploaded_post_assets_videos.push(...files);
    // Then get the component skeleton

    if(uploaded_post_assets_videos.length === 0) {
        document.getElementById("post-video").value = "";
        return false;
    }
    $.ajax({
        type: 'GET',
        url: root+"layouts/post/generate_post_creation.php?type=v",
        success: function(response) {
            let container = response
            // We check if there's no file and text area is empty we hide the share button
            if(files.length === 0 && postTextFiled.val() === "") {
                btnPost.setAttribute("disabled","");
            }else {
                btnPost.removeAttribute("disabled")
            }

            $(".upload-media").append(container);

            let component = $(".upload-media .post-creation-item").last();
            let vidtag = component.find(".video-post-thumbnail");

            var selectedFile = files[0];
            var reader = new FileReader();
            vidtag.parent().find(".assets-pending").css("display", "flex");
            reader.readAsDataURL(selectedFile);
            reader.onload = function(e) {
                let thumbnail = "";
                try {
                    // get the frame at 1.5 seconds of the video file
                    thumbnail = get_thumbnail(selectedFile, 1.5, component);
                } catch (ex) {
                    console.log("ERROR: ", ex);
                }

                // Here we adjust the image in center and choose height if width is greather and width if height is greather
                if(vidtag.height() >= vidtag.width()) {
                    vidtag.width("100%");
                } else {
                    vidtag.height("100%");
                }
                vidtag.parent().find(".assets-pending").css("display", "none");
                vidtag.parent().find(".post-creation-video-image-container").css("display", "flex");
                // Here we call this function to adjust indexes
                adjust_post_uploaded_assets_indexes();
                $(".delete-uploaded-item").click(function() {
                    // FileList in javascript is readonly So: for now let's botter our heads with only posting one image
                    // It's time to botter your fuckin' head with multiple images HHH Lol
                    //Here we need only to remove this image and not all the images in the queue
                    adjust_post_uploaded_assets_indexes();
                    // Here we want to get the index of item the user want to delete and loop through the array and
                    // Delete the item which has pciid input value with that index and
                    let delete_index = Number($(this).parent().find(".pciid").val());
                    let new_arr = [];
                    let cn = 0;
                    for(let k=0; k<uploaded_post_assets_videos.length; k++) {
                        if(k !== delete_index) {
                            new_arr[cn] = uploaded_post_assets_videos[k];
                            cn++;
                        }
                    }
                    // We remove it's component
                    $(this).parent().remove();
                    // We check if there's no file and text area is empty we hide the share button
                    if(new_arr.length === 0 && postTextFiled.val() === "") {
                        $("#postImg").get(0).removeAttribute("disabled")
                        btnPost.setAttribute("disabled","");
                    }else {
                        btnPost.removeAttribute("disabled")
                        $("#postImg").get(0).setAttribute("disabled","")
                    }
                    //we assign the new array which has deleted item removed to uploaded_post_assets array
                    uploaded_post_assets_videos = new_arr;
                });
            };
        }
    })
})
/* end  video upload */


// The following two function used to validate uploaded image or video
function validate_image_file_Type(files){
    let result = [];
    for(let i = 0; i<files.length;i++) {
        fileName = files[i].name;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile==="jpg" || extFile==="jpeg" || extFile==="png" || extFile==="gif"){
            result.push(files[i]);
        }
    }

    return result;
}
function validate_video_file_Type(files) {
    let result = [];
    for(let i = 0; i<files.length;i++) {
        fileName = files[i].name;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if ( extFile === "mp3" || extFile === "webm" || extFile === "mpg"
            || "mp2" === extFile || extFile === "mpeg" || extFile === "mpe"
            || extFile === "mpv" || extFile === "ogg" || extFile === "mp4"
            || extFile === "m4p" || extFile === "m4v" || extFile === "avi"){
            result.push(files[i]);
        }
    }

    return result;
}

/*
 This function adjust indexes of uploaded images
 when user wants to delete an image from
 a set of uploaded images before sharing the post
*/
function adjust_post_uploaded_assets_indexes() {
    let counter = 0;
    $(".post-creation-item").each(function() {
        $(this).find(".pciid").val(counter);
        counter++;
    });
}


/* start  Creat thumbnail from Video */
const get_thumbnail = async function(file, seekTo, component) {
    let response = await getVideoCover(file, seekTo);
    component.find(".video-post-thumbnail").attr("src", response);
}
function createPoster($video) {
    var canvas = document.createElement("canvas");
    canvas.width = 350;
    canvas.height = 350;
    canvas.getContext("2d").drawImage($video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL("image/jpeg");
}
function getVideoCover(file, seekTo = 0.0) {
    return new Promise((resolve, reject) => {
        // load the file to a video player
        const videoPlayer = document.createElement('video');
        videoPlayer.setAttribute('src', URL.createObjectURL(file));
        videoPlayer.load();
        videoPlayer.addEventListener('error', (ex) => {
            reject("error when loading video file", ex);
        });
        // load metadata of the video to get video duration and dimensions
        videoPlayer.addEventListener('loadedmetadata', () => {
            // seek to user defined timestamp (in seconds) if possible
            if (videoPlayer.duration < seekTo) {
                reject("video is too short.");
                return;
            }
            // delay seeking or else 'seeked' event won't fire on Safari
            setTimeout(() => {
                videoPlayer.currentTime = seekTo;
            }, 200);
            // extract video thumbnail once seeking is complete
            videoPlayer.addEventListener('seeked', () => {
                console.log('video is now paused at %ss.', seekTo);
                // define a canvas to have the same dimension as the video
                const canvas = document.createElement("canvas");
                canvas.width = videoPlayer.videoWidth;
                canvas.height = videoPlayer.videoHeight;
                // draw the video frame to canvas
                const ctx = canvas.getContext("2d");
                ctx.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
                // return the canvas image as a blob
                ctx.canvas.toBlob(
                    blob => {
                        resolve(createPoster(videoPlayer));
                    },
                    "image/jpeg",
                    1 /* quality */
                );
            });
        });
    });
}
/* end  Creat thumbnail from Video */

/* start like post */
$(document).on("click",".post-option-btn .post-meta-like", function (e){
    let postItem = getParent(e.target,"post-item");
    let idPost = findID(postItem,".postId").value;
    let btn = this;
    $.ajax({
        url: root + "api/like/post.php",
        type: 'post',
        data: {
            post_id: idPost,
            user_id: current_user_id,
        },
        success: function(response) {
            /*
               1: deleted successfully
               2: added successfully
              -1: there's a problem
            */
            console.log(response);
            response = parseInt(response);
            let count = parseInt($(postItem).find(".post-meta-like").find(".value").html());
            if (response === 2){
                $(btn).find("#like").addClass("active");
                count++;
            }else  if (response === 1){
                $(btn).find("#like").removeClass("active");
                count--;
            }
            $(postItem).find(".post-meta-like").find(".value").html(count);
        }
    })
})
function  getLikePost(idPost,post){
    $.ajax({
        url: root+ "api/like/getLikePost.php?post_id="+idPost,
        type: 'GET',
        success: function(response) {
            let data = JSON.parse(response);

            if (data.curentUserIsLikePost){
                $(post).find("button#like").addClass("active");
            }else {
                $(post).find("button#like").removeClass("active");
            }
            $(post).find(".post-meta-like").find(".value").html(data.numberOfLikes);
        }
    })
}
/* end like post */
/*start gestion des posts */
function handle_post_assets(post) {
    let container_width = $(post).width();
    let max_container_height = 600;
    let half_width_marg = container_width / 2 - 20; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
    let half_height_marg = max_container_height / 2 - 20; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
    let full_width_marg = container_width - 20;
    let full_height_marg = max_container_height - 20;

    let media_containers = $(post).find(".post-media-item").innerHeight;
    let num_of_medias = $(post).find(".post-media-item").length;

    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias === 1) {
        if($(post).find(".post-media-item img").height() <= $(post).find(".post-media-item img").width()) {
            $(post).find(".post-media-item img").width("100%");
            $(post).find(".post-media-image").height("auto");
        } else{
            $(post).find(".post-media-item img").height("100%");
            $(post).find(".post-media-item img").width("auto");
        }
        // $(post).find(".post-view-button").click(function() {
        //     view_image($(post));
        // });
        return;
    }
    if(num_of_medias === 2) {
        for(let k = 0;k < num_of_medias; k++) {
            $(media_containers[k]).css("width", half_width_marg);
            $(media_containers[k]).css("height", "100%");
            $(media_containers[k]).find("img").height("100%");

            // $(media_containers[k]).find(".post-media-item img").click(function() {
            //     view_image(media_containers[k]);
            // });
        }
    } else if(num_of_medias === 3) {
        for(let k = 0;k<3; k++) {
            let ctn = media_containers[k];
            if($(ctn).find("img").height() >= $(ctn).find("img").width()) {
                if ($(ctn).height() > $(ctn).find("img").height()){
                    $(ctn).find("img").height("100%");
                    break;
                }
                $(ctn).find("img").width("100%");
            } else {
                $(ctn).find("img").height("100%");
            }
            // $(ctn).find(".post-view-button").click(function() {
            //     view_image(ctn);
            // });
        }
        $(post).find(".post-media").addClass("multiple");
        let ctn = media_containers[0];
        $(ctn).css("grid-row", "1 / 3");
        $(ctn).find("img").css('height', "100%");
        $(ctn).find("img").css("width", "auto");

    } else if(num_of_medias == 4) {
        for(let k = 0;k<4; k++) {
            let ctn = media_containers[k];
            $(ctn).css("align-items", "self-start");
            $(ctn).css("margin", "3px");
            $(ctn).css("width", half_width_marg);
            $(ctn).css("height", half_height_marg);

            if($(ctn).find("img").height() >= $(ctn).find("img").width()) {
                $(ctn).find("img").width("100%");
            } else {
                $(ctn).find("img").height("100%");
            }

            // $(ctn).find(".post-view-button").click(function() {
            //     view_image(ctn);
            // });
        }
    } else if(num_of_medias > 4){
        let ctn = media_containers;
        $(post).find(".post-media").addClass("multiple");
        for(let k = 0;k<4; k++) {
            ctn = media_containers[k];
            if($(ctn).find("img").height() >= $(ctn).find("img").width()) {
                $(ctn).find("img").width("100%");
            } else {
                $(ctn).find("img").height("100%");
            }
            // $(ctn).find(".post-media-item img").click(function() {
            //     view_image(ctn);
            // });
        }

        let plus = num_of_medias - 4;
        for(let j = 4;j<num_of_medias;j++) {
            $(media_containers[j]).remove();
        }
        $(media_containers[3]).append("<div class='more-media-post'><p>+" + plus + "</p></div>");
        $(".more-media-post").click(function() {
            // go_to_post($(this));
            console.log($this);
        });
    }
}
$(".post-item").toArray().forEach((e)=>{
    handle_post_assets(e);
})

function generatePostLoadingInfo(){
    return $("<div class=\"PostLoadingInfo loading-info\" id=\"LoadingPostsDiv\" style=\"padding: 15px;padding-bottom: 100px;\">\n" +
        "                        <div class=\"animated-background\">\n" +
        "                            <div class=\"background-masker header-top\"></div>\n" +
        "                            <div class=\"background-masker header-left\"></div>\n" +
        "                            <div class=\"background-masker header-right\"></div>\n" +
        "                            <div class=\"background-masker header-bottom\"></div>\n" +
        "                            <div class=\"background-masker subheader-left\"></div>\n" +
        "                            <div class=\"background-masker subheader-right\"></div>\n" +
        "                            <div class=\"background-masker subheader-bottom\"></div>\n" +
        "                            <div class=\"background-masker content-top\"></div>\n" +
        "                            <div class=\"background-masker content-first-end\"></div>\n" +
        "                            <div class=\"background-masker content-second-line\"></div>\n" +
        "                            <div class=\"background-masker content-second-end\"></div>\n" +
        "                            <div class=\"background-masker content-third-line\"></div>\n" +
        "                            <div class=\"background-masker content-third-end\"></div>\n" +
        "                        </div>\n" +
        "\n" +
        "                    </div>");
}
let morePost = true;

function handlePost(){
    $(".post-item").each((k,e)=>{
        if ($(e).hasClass("handle")){
            handle_post_assets(e)
            handle_post_actions(e)
            $(e).removeClass("handle")
        }
        handle_video();
    })
}


/*end gestion des posts */

/*start gestion des comments */


$(document).on("submit",".comment-form",(e)=>{
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    let postUp = getParent(e.target,"post-up");
    let idPost = findID(postUp,".postId").value;
    formData.append("post_id",idPost);
    formData.append("comment_owner",current_user_id);
    $.ajax({
        url: root + "api/comment/post.php",
        type: 'post',
        contentType: false,
        processData: false,
        data: formData,
        success: function(response) {
            data = JSON.parse(response);
            if (data.success){
                getCommentPost(idPost,postUp);
                let newComment = $(data.msg);
                $(".comment-container .comment-content").append(newComment);
                handle_comment_event(newComment)
                $(".post-up-center").get(0).scrollTop += 1000;
                $(".comment-input").val('');
                $(form).find("button.comment-btn").removeClass("active");
            }

        }
    })
})
function handle_post_comment(post){
    $(post).find(".comment-item").each((k,v)=>{
        handle_comment_event(v);
    })
}
function handle_comment_event(element) {
    // Handle hide comment button
    let hide = $(element).find(".hide-button");
    $(hide).click({function() {
        let container = $(getParent(this,"comment-item"));
        container.find(".comment-op").css("display", "none");
        container.find(".comment-global-wrapper").css("display", "none");
        container.find(".btn-option-comment-menu").removeClass("active");
        container.find(".replay-comments").css("display", "none");
        container.find(".hidden-comment-hint").css("display", "block");
        return false;
    }});

    // Handle show comment after hide it
    let show_comment = $(element).find(".show-comment");
    $(show_comment).click(function() {
        let container = $(getParent(this,"comment-item"));
        container.find(".comment-op").css("display", "block");
        container.find(".comment-global-wrapper").css("display", "block");
        container.find(".replay-comments").css("display", "block");
        container.find(".hidden-comment-hint").css("display", "none");
        return false;
    });

    // Handle comment deletion
    let delete_comment = $(element).find(".delete-comment");
    $(delete_comment).click(function() {
        let container = $(getParent(this,"comment-item"));
        container.find(".btn-option-comment-menu").removeClass("active");
        let cid = container.find(".comment_id").val();
        $.ajax({
            url: root + "api/comment/delete.php",
            type: 'POST',
            data: {
                comment_id: cid
            },
            success(response) {
                let data  = JSON.parse(response);
                if(data.msg === true && data.success === true) {
                    let child = container.get(0);
                    let post_container = $(getParent(child,"post-up"));
                    post_container.find(".post-meta-comment").find(".value").html(data.numberOfComments);
                    container.remove();
                }
            }
        });

        return false;
    });

    $(".close-edit").click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }
        container.find(".comment-text").css("display", "block");
        $(this).parent().css("display", "none");
    });
    let edit_comment = $(element).find(".edit-comment");
    $(edit_comment).click(function() {
        let container = $(getParent(this,"comment-block"));
        container.find(".btn-option-comment-menu").removeClass("active");
        let cid = container.find(".comment_id").val();
        const btnSubmitEdit =container.find(".comment-btn");
        let comment = container.find(".comment .comment-text").html();
        let textAria = container.find(".comment-edit-container").find(".comment-editable-text");
        container.find(".comment .comment-text").css("display", "none");
        textAria.val(comment);
        container.find(".comment-edit-container").css("display", "block");
        textAria.focus();
        let canEdit = false;
        textAria.on({
            input:(e)=>{
                if ($(e.target).val() !== comment && $(e.target).val() !==""){
                    btnSubmitEdit.addClass("active")
                    canEdit = true;
                }else {
                    btnSubmitEdit.removeClass("active")
                    canEdit = false;
                }
            }
        })
        btnSubmitEdit.click(()=>{
            if (canEdit){
                let new_com = textAria.val();
                $.ajax({
                    url: root +"api/comment/edit.php",
                    type: 'post',
                    data: {
                        new_comment: new_com,
                        comment_id: cid,
                    },
                    success: function(response) {
                        let data = JSON.parse(response);
                        if(data.success) {
                            container.find(".comment-edit-container").css("display", "none");
                            container.find(".comment-text").css("display", "block");
                            if (data.msg !== false){
                                container.find(".comment-text").text(data.msg)
                            }
                        }
                    }
                })
            }
        })


        return false;
    });

    let replay_comment = $(".reply-comment");
    $(replay_comment).click(function (){
        let container = $(getParent(this,"comment-block"));
        let cid = container.find(".comment_id").val();
        commentInputPost = $(getParent(this,"post-up-content")).find(".comment-input");
    })
}
function getCommentPost(idPost,post){
    $.ajax({
        url:root + "api/comment/getCommentPost.php?post_id="+idPost,
        type: 'GET',
        success: function(numberOfComment) {
            let v = $(post).find(".post-meta-comment").find(".value");
            v.html(numberOfComment);
            // if (parseInt(v.html()) !== numberOfComment){
            //     $(post).find(".post-meta-comment").find(".value").html(numberOfComment);
            // }
        }
    })
}

// setInterval(()=>{
//     $(".post-item").toArray().forEach((e)=>{
//         let postId = findID(e,".postId");
//         if (postId !== null){
//             getLikePost( postId.value, e)
//             getCommentPost( postId.value, e)
//         }
//     })
// },2000)
/*end gestion des comments */

/* edit and delete post area code*/
function handle_post_actions(post) {
    $btnOpen = $(post).find(".btn-more-option");
    $btnOpen.click(function (){
        $(this).toggleClass("active");
    })
    try {
        handle_delete_post(post);
        handle_edit_post(post);
    } catch(error) {
        console.log("error: ");
        console.log(error);
    }
}

function handle_hide_post(post) {
    $(post).find('.hide-post').click(function() {
        $(post).append('<p class="small-text" style="padding: 10px 16px">This post is hidden. Click <a href="" class="show-again show-post-again">here</a> to see it again</p>')
        $(post).find(".show-post-again").click(function() {
            $(post).find('.timeline-post').css('display', 'block');
            $(this).parent().remove();

            $(".sub-options-container").css("display", "none");
            return false;
        })
        $(post).find('.timeline-post').css('display', 'none');
        return false;
    });
}
function handle_delete_post(post) {
    $(post).find('.delete-post').click(function(event) {
        let pid = $(getParent(this,"post-item")).find(".postId").val();
        $.ajax({
            url: root + 'api/post/delete.php',
            type: 'post',
            data: {
                post_id: pid,
            },
            success: function(response) {
                $(post).delay(200).fadeOut(500).remove();
            }
        });
    });
}
function handle_edit_post(post) {
    $(post).find(".edit-post").click(function() {
        let pid = $(getParent(this,"post-item")).find(".postId").val();
        // First we hide the old text of post
        let  postText = $(post).find(".post-text .text");
        $(postText).hide();
        // Then we show the edit container which contains the textatrea and exit button
        $(post).find(".post-edit-container").show();
        // Then we fill the textarea with the old post text and focus it
        let textArea = $(post).find(".post-editable-text");
        $(textArea).val(postText.text().trim());
        $(".btn-more-option").removeClass("active");
        $(textArea).focus();
        $(post).find(".submit-editing").hide();
        $(textArea).on("input",function (){
            if ($(textArea).val().trim() === $(postText).text().trim()){
                $(post).find(".submit-editing").hide();
            }else {
                $(post).find(".submit-editing").show();
            }
        })
        $(post).find(".submit-editing").on({
            click: function() {
                if($(textArea).val().trim() !== $(postText).text().trim()) {
                    let new_post_text = $(textArea).val();
                    $.ajax({
                        url: root + "api/post/edit.php",
                        type: 'post',
                        data: {
                            new_post_text: new_post_text,
                            post_id: pid,
                        },
                        success: function(response) {
                            let data = JSON.parse(response);
                            if(data.success) {
                                $(post).find(".post-text").text(data.msg);
                            }
                        },
                        complete:function (){
                            $(post).find(".post-edit-container").hide();
                            $(post).find(".post-text").show();
                        }
                    });
                }
            }
        })
        return false;
    });
    $(post).find(".close-post-edit").click(function() {
        $(post).find(".post-text .text").show();
        // Then we show the edit container which contains the textatrea and exit button
        $(post).find(".post-edit-container").hide();
    });
}



/* post up area code*/
const  generatePostUpSection = $("#generatePostUp section");
$(document).on("click",".post-up #closeCommentBox",(e)=>{
    $(".post-item").each((k,e)=>{
        let postId = findID(e,".postId").value;
        getLikePost(postId,e)
        getCommentPost(postId,e)
    })
    let postUp = getParent(e.target,"post-up");
    generatePostUpSection.hide();
    postUp.remove();
    document.body.classList.remove("overflow-hidden")
})
let dataHtml
$(document).on("click",".posts .post-option-btn #comment",(e)=>{
    let postItem = getParent(e.target,"post-item");
    let postId = findID(postItem,".postId").value;
    generatePostUpSection.show()
    document.body.classList.add("overflow-hidden")
    $.ajax({
        url: root + "layouts/post/postUp.php?postId="+postId,
        type: 'get',
        success: function (data) {
            $("#generatePostUp section").html(data);
            handle_video();
            handle_post_comment(".post-up");
            // $(postItem).find(".post-meta-like").find(".value").html(getLikePost(postId));
            $(".post-up-center").get(0).scrollTop+=postItem.offsetHeight-20;
        }
    })
})
$(document).on("click",".post-up .post-option-btn #comment",(e)=>{
    $(".post-up .comment-form input").focus();
})
$(document).on("input",".comment-form input",(e)=>{
    let commentField = e.target;
    const commentBtn = commentField.form.querySelector("button.comment-btn");
    if (commentField.value === ""){
        commentBtn.classList.remove("active")
    }else {
        commentBtn.classList.add("active")
    }
})



generatePostUpSection.on("click",(e)=>{
    if (!e.target.classList.contains("btn-option-comment-menu")
        && !e.target.classList.contains(".sub-options-container")){
        $(".btn-option-comment-menu").removeClass("active");
    }
})
$(document).on("click",".btn-option-comment-menu",(e)=>{
    $(e.target).toggleClass("active");
})

$(document).on("click","body",function (e){
    if(!$(e.target).hasClass("btn-more-option")){
        $(".btn-more-option").removeClass("active")
    }
    console.log($(e.target).id)
    if (e.target.id != "visible"){
        visibleBox.removeClass("active");
    }

})