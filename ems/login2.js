const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', () => {
    if(passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.innerHTML = '<i class="fa-regular fa-eye"></i>';
    }    
    else {
        passwordInput.type = 'password';
        togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'
    }
});


registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});


// FOR CAPTCHA //

(function(){ 
    const fonts = ["cursive", "sans-serif", "serif", "monospace"]; // fixed typo: 'san-serif' ➝ 'sans-serif'
    let captchaValue = "";

    function generateCaptcha(){
        let value = btoa(Math.random()*1000000000);
        value = value.substr(0, 5 + Math.floor(Math.random() * 5));
        captchaValue = value;
    }

    function setCaptcha(){
        const html = captchaValue.split("").map((char)=>{
            const rotate = -20 + Math.trunc(Math.random()*30);
            const font = Math.trunc(Math.random()*fonts.length);
            return `<span
                style="
                    transform: rotate(${rotate}deg);
                    font-family: ${fonts[font]};
                "
            >${char}</span>`;
        }).join("");
        
        document.querySelector(".captcha .preview").innerHTML = html;
    }

    function initCaptcha(){
        document.querySelector(".captcha .captcha-refresh").addEventListener("click", function(){
            generateCaptcha();
            setCaptcha();
        });
        generateCaptcha();
        setCaptcha();
    }

    initCaptcha();

    document.querySelector("form.login").addEventListener("submit", function(e){
        let inputCaptchaValue = document.querySelector("#captcha-input").value;
        if(inputCaptchaValue !== captchaValue){
            e.preventDefault(); // STOP form submission
            alert("Invalid Captcha. Please try again.");
        }
    });
})();




