const form = document.querySelector(".form form");
const errorText = document.querySelector(".error-text");
form.onsubmit = (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "inc/chickLogin.inc.php", true);
    xhr.onload = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let data = xhr.response;
            console.log(data);
            if(data === "success"){
                // errorText.classList.remove("active");
                location.href = "home.php"
            }else if(data === "reload"){
                location.reload();
            }else{
                errorText.innerHTML = data;
                errorText.classList.add("active");
                setTimeout(()=>{
                    errorText.classList.remove("active");
                },3000)
            }
        }
    };
    xhr.send(new FormData(form));
};
