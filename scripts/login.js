let loginHeader = document.querySelector(".main-wrapper .login-signup-wrapper .header-wrapper .login-header");
let signUpHeader = document.querySelector(".main-wrapper .login-signup-wrapper .header-wrapper .sign-up-header");
let loginFormWrapper = document.querySelector(".main-wrapper .login-signup-wrapper .login-form-wrapper");
let loginUsernameBox = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .login-username");
let loginPasswordBox = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .login-password");
let loginButton = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .login-button");
let signUpFormWrapper = document.querySelector(".main-wrapper .login-signup-wrapper .sign-up-form-wrapper");
let signUpUsernameBox = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .sign-up-username");
let signUpPasswordBox1 = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .sign-up-password-1");
let signUpPasswordBox2 = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .sign-up-password-2");
let signUpButton = document.querySelector(".main-wrapper .login-signup-wrapper .form-wrapper .sign-up-button");
let noSpaceBoxes = document.querySelectorAll(".main-wrapper .login-signup-wrapper .form-wrapper .no-space-box");

loginHeader.addEventListener("click",()=>{
    signUpFormWrapper.style.display = "none";
    signUpFormWrapper.classList.remove("selected-form-design");
    signUpHeader.classList.remove("selected-form-design");
    loginFormWrapper.style.display = "flex";
    loginFormWrapper.classList.add("selected-form-design");
    loginHeader.classList.add("selected-form-design");
});
signUpHeader.addEventListener("click",()=>{
    signUpFormWrapper.style.display = "flex";
    signUpFormWrapper.classList.add("selected-form-design");
    signUpHeader.classList.add("selected-form-design");
    loginFormWrapper.style.display = "none";
    loginFormWrapper.classList.remove("selected-form-design");
    loginHeader.classList.remove("selected-form-design");
});


// Error dialog box styles...........
let errDialogWrapper = document.querySelector(".error-dialog-wrapper");
let errDialogOkButton = document.querySelector(".error-dialog-wrapper .error-dialog .ok-button");
errDialogOkButton.addEventListener("click",()=>{
    errDialogWrapper.style.display = "none";
})
noSpaceBoxes.forEach(box=>{
    box.addEventListener("keyup",event=>{
        if ( event.target.value.includes(" ") ) {
            event.target.value = event.target.value.trim();
            errDialogWrapper.style.display = "flex";
        }
    });
});