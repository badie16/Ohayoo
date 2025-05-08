function _(e) {
	return document.querySelector(e);
}
function _All(e) {
	return document.querySelectorAll(e);
}
const dateD = _("#dateD");
const dateM = _("#dateM");
const dateY = _("#dateY");
function intellectualiseOption(select, min, max) {
	for (i = min; i <= max; i++) {
		let option = document.createElement("option");
		option.value = i;
		option.innerHTML = i;
		select.append(option);
	}
}
intellectualiseOption(dateD, 1, 31);
intellectualiseOption(dateY, 1970, new Date().getFullYear());
//dateD.children[new Date().getDate()].selected = true;
dateM.children[new Date().getMonth() + 1].selected = true;
dateY.children[dateY.children.length - 1].selected = true;

const form = _(".form form");
const errorText = _(".error-text");
let data = {
	type:null,
	msg:null,
	code:null,
}
form.onsubmit = (e) => {
	e.preventDefault();
	let xhr = new XMLHttpRequest();
	xhr.onload = () => {
		if (xhr.readyState === 4 && xhr.status === 200) {
			console.log(xhr.response);
			data = JSON.parse(xhr.response);

			if (data.code === 100 && data.type === "s") {
				errorText.classList.remove("active");
				location.href = "login.php";
			} else {
				if (data.code === 2){
					_("#email").classList.add("error");
					_("#email").focus();
				}else if((data.code === 3)){
					_("#userName").classList.add("error");
					_("#userName").focus();
				}else if(data.code === 0){
					for (let e of _All(".input input")) {
						if (e.value === ""){
							e.classList.add("error");
						}
					}
				}
				errorText.innerHTML = data.msg;
				errorText.classList.add("active");
				setTimeout(() => {
					errorText.classList.remove("active");
				}, 5000);
			}
		}
	};
	xhr.open("POST", "inc/signup.inc.php", true);
	let dataForm = new FormData(form);
	xhr.send(dataForm);
};

_All(".input input").forEach((e)=>{
	e.addEventListener("input",(ele)=>{
		e.classList.remove("error")
	})
})

const btnNext = _(".btn.next");
btnNext.onclick = ()=>{
	let arr = ["one","two","tree"]
	let n = parseInt(btnNext.dataset.next);
	form.classList=[arr[++n]]
	btnNext.dataset.next = n;
}

function mobileSignUp(e){
}
window.onresize= (e)=>{
	if(innerWidth <= 450){
		mobileSignUp(true);
	}
}
