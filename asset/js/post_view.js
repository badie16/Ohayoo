function handle_post_View_assets(){
    $(".post-media-item img").each(function (k,e){
        if($(e).height() > $(e).width()){
            $(e).css("height","100%");
            $(e).css("width","auto");
        }else {
            $(e).css("width","100%");
            $(e).css("height","auto");
        }
    })
    $(".post-view .carousel-item").removeClass("active");
    $($(".post-view .carousel-item").get(0)).addClass("active");
}
handle_post_View_assets()