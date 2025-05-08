//start slider img
let isDragging, startX, startScrollLeft;
let checkDraggingStart;

function _(e){
    return document.querySelector(e);
}
let dragging = (p, e) => {
    if (!isDragging) return;
    p.scrollLeft = startScrollLeft - (e.pageX - startX);
};
let startDragging = (p, e) => {
    p.classList.add("dragging");
    isDragging = true;
    startX = e.pageX;
    startScrollLeft = p.scrollLeft;
};
let stopDragging = (p) => {
    p.classList.remove("dragging");
    isDragging = false;
};
let mouseleave = () => {
    checkDraggingStart = false;
};
let mouseenter = () => {
    checkDraggingStart = true;
};
let scrollInfine = (p) => {
    if (p.scrollLeft <= 0) {
        _(".btn-left-slider").classList.remove("active");
    }else {
        _(".btn-left-slider").classList.add("active");
    }
    if (Math.ceil(p.scrollLeft) >= p.scrollWidth - p.offsetWidth) {
        _(".btn-right-slider").classList.remove("active");
    }else {
        _(".btn-right-slider").classList.add("active");
    }

};
// slider collection
let sliderImg = document.querySelectorAll(".sliderStories");
sliderImg.forEach((slider) => {
    slider.addEventListener("mousemove", (e) => dragging(slider, e));
    slider.addEventListener("mousedown", (e) => startDragging(slider, e));
    document.addEventListener("mouseup", () => stopDragging(slider));
    slider.parentElement.addEventListener("mouseenter", () => mouseenter());
    slider.parentElement.addEventListener("mouseleave", () => mouseleave());
    slider.onscroll=()=>{scrollInfine(slider)}
    scrollInfine(slider)
});

function sliderMoveLeft(slider) {
    let e = document.querySelector(slider);
    e.scrollLeft -= e.children[0].clientWidth;
}
function sliderMoveRight(slider) {
    let e = document.querySelector(slider);
    e.scrollLeft += e.children[0].clientWidth;
}