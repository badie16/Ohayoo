const generatePosts = function (){
    limitPost = $("#limitPost").val();
    $(".posts").append(generatePostLoadingInfo());
    let userProfile = $(".userOfP").val();
    $.ajax({
        url: root + "api/post/getPosts.php",
        type: 'post',
        data: {
            limitPost: limitPost,
            userProfile:userProfile
        },
        success: function (rep) {
            let data = JSON.parse(rep);
            if (data.type === "s") {
                if (data.msg !== "-1") {
                    $("#limitPost").val(data.code);
                    $(".posts").append(data.msg);
                } else {
                    morePost = false;
                    $(".MorePostsScroll").addClass("d-none");
                    $(".NoMorePostsDiv").removeClass("d-none");
                }
                handlePost()
                handlePost()
                $(".PostLoadingInfo").remove();
            } else {
                console.log("error in posts");
            }
        }
    })
}
$(".btn-show-edit-profile-view").click(function (){
    $("body").toggleClass("overflow-hidden");
    $(".edit-profile-view").toggleClass("open");
})
$(".viewer .close-view ").click(function (){
    $("body").removeClass("overflow-hidden");
    $(".edit-profile-view").removeClass("open");
})

$("#save-profile-edits-form").submit(function (e){
    e.preventDefault();
    let form = this;
    $(form).find(".btnSave").hide();
    $(form).find(".btnLanding").show();
    let fromDta = new FormData(form);
    $.ajax({
        url: root + "api/user/update_info_profile.php",
        type: 'post',
        contentType: false,
        processData: false,
        data: fromDta
        ,
        success: function (rep) {
            console.log(rep)
            let data = JSON.parse(rep);
            if (data.success){
                if(data.cover !== "")

                    $(".cover-photo").get(0).src = data.cover;
                if (data.picture !== "")
                    $(".profile-picture").get(0).src = data.picture;
            }
            $(form).find(".btnSave").show();
            $(form).find(".btnLanding").hide();
        }
    })
})


$("#change-avatar").change(function (e){
    // $(".former-picture-dim").get(0).src=$(this).val()
    console.log(e);
    let selectedFile = e.target.files[0]
    let imgTag = $(".former-picture-dim");
    let reader = new FileReader();
    reader.onload = function(e) {
        imgTag.attr("src", e.target.result)
        // Here we adjust the image in center and choose height if width is greather and width if height is greather
        if(imgTag.height() >= imgTag.width()) {
            imgTag.width("100%");
        } else {
            imgTag.height("100%");
        }
    };

    reader.readAsDataURL(selectedFile);
})
$("#change-cover").change(function (e){
    // $(".former-picture-dim").get(0).src=$(this).val()
    console.log(e);
    let selectedFile = e.target.files[0]
    let imgTag = $("#cover-changer-dim");
    let reader = new FileReader();
    reader.onload = function(e) {
        imgTag.attr("src", e.target.result)
        // Here we adjust the image in center and choose height if width is greather and width if height is greather
        if(imgTag.height() >= imgTag.width()) {
            imgTag.width("100%");
        } else {
            imgTag.height("100%");
        }
    };
    reader.readAsDataURL(selectedFile);
})
window.addEventListener('scroll', function() {
    if(window.innerHeight + window.scrollY +2 >= document.body.offsetHeight){
        if (morePost){
            generatePosts();
        }
    }
})
generatePosts();