let generatePosts = function (){
    limitPost = $("#limitPost").val();
    $(".posts").append(generatePostLoadingInfo());
    let pos
    $.ajax({
        url: root + "api/post/getVideos.php",
        type: 'post',
        data: {
            limitPost: limitPost,
        },
        success: function (rep) {
            let data = JSON.parse(rep);
            console.log(data);
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
window.addEventListener('scroll', function() {
    if(window.innerHeight + window.scrollY +2 >= document.body.offsetHeight){
        if (morePost){
            generatePosts();
        }
    }
})
$(document).ready(function (){
    generatePosts()
})