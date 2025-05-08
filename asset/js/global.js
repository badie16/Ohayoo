
function  getParent(ele,classOfParent){
    let container = ele;

    while(!container.classList.contains(classOfParent)) {
        container = container.parentElement;
    }
    return container;
}
function findID(ele , selector){
    return ele.querySelector(selector);
}



$(document).on("click",".follow-button",function(event) {
    event.preventDefault();
    event.stopPropagation();
    let form = $(this.form);
    console.log(this.form)
    $.ajax({
        type: "POST",
        url: root + "api/follow/toggle.php",
        data: form.serialize(),
        success: function(response)
        {
            let data =JSON.parse(response);
            console.log(data);
            if (data.success) {
                if (data.code === 1) {
                    $(".follow-user-btn-container").html(data.html);
                    $(".follow-label").html('' +
                        '<i class="fa fa-heart-broken"></i>' +
                        '<input type="submit" form="follow-form" hidden="" class="">' +
                        'Unfollow' +
                    '');
                } else {
                    $(".follow-user-btn-container").html(data.html);
                    $(".follow-label").html('' +
                        '<i class="fal fa-heart"></i>' +
                        '<input type="submit" form="follow-form" hidden="" class="">' +
                        'Follow' +
                    '');
                }
            }
        }
    });
});

$(".sendOrCancelRequestContainer").on("click",".add-user",function(event) {
    event.preventDefault();
    event.stopPropagation();
    let addButton = $(this);
    if (addButton.prop("tagName") === "LABEL"){
        addButton = $(this).find("input");
    }
    console.log(addButton.prop("tagName"))
    let form = $(getParent(this,"relation-form"));
    $.ajax({
        type: "POST",
        url: root + "api/user_relation/toggle_send_request.php",
        data: form.serialize(),
        success: function(response)
        {
            let data = JSON.parse(response);
            console.log(data);
            if (data.success){
                if (data.code === 1 || data.code === 0) {
                    $(".sendOrCancelRequestContainer").html(data.html);
                }
            }else {
                //cas of error
            }
        }
    });
})

$(".accept-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    let unfriend = $(this);
    let form = $(getParent(this, "relation-form"));
    $.ajax({
        type: "POST",
        url: root + "api/user_relation/accept_request.php",
        data: form.serialize(),
        success: function (response) {
            let data = JSON.parse(response);
            console.log(data);
            if (data.success) {
                location.reload();
            }
        }
    })
})

$(".unfriend").click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    console.log(this);
    let form = $(".relation-form")
    $.ajax({
        type: "POST",
        url: root + "api/user_relation/unfriend_relation.php",
        data: form.serialize(),
        success: function(response)
        {
            let data = JSON.parse(response);
            console.log(data)
            if(data.success) {
                location.reload();
            }
        }
    });
})

$(document).on("click",".close-menu-V2",function (e){
    console.log("d")
        $(".menu-option-event-V2").removeClass("open").removeClass("active");
    $(this).parent().removeClass('open');
})