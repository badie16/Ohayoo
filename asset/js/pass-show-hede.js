const passField = document.querySelector(".form input#pass");

toggleBtn = document.querySelector(".form i.eye");

toggleBtn.onclick = () => {
	if (passField.type == "password") {
		passField.type = "text";
	} else {
		passField.type = "password";
	}
	toggleBtn.classList.toggle("active");
};
