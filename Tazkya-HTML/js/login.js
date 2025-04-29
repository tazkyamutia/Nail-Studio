document.addEventListener("DOMContentLoaded", function() {
    const formContainer = document.querySelector(".form_container");
    const loginForm = document.querySelector(".login_form");
    const signupForm = document.querySelector(".signup_form");
    const formOpen = document.getElementById("form-open");
    const formClose = document.querySelector(".form_close");
  
    const signupLink = document.getElementById("signup");
    const loginLink = document.getElementById("login");
  
    const passwordFields = document.querySelectorAll('.input_box input[type="password"]');
    const passwordToggleIcons = document.querySelectorAll('.input_box i.pw_hide');
  
    // Show the form container when login button is clicked
    formOpen.addEventListener("click", () => {
      formContainer.classList.add("active");
      document.querySelector(".home").classList.add("show");
    });
  
    // Close the form container when the close icon is clicked
    formClose.addEventListener("click", () => {
      formContainer.classList.remove("active");
      document.querySelector(".home").classList.remove("show");
    });
  
    // Switch to the sign-up form
    signupLink.addEventListener("click", (e) => {
      e.preventDefault();
      loginForm.style.display = "none";
      signupForm.style.display = "block";
    });
  
    // Switch to the login form
    loginLink.addEventListener("click", (e) => {
      e.preventDefault();
      signupForm.style.display = "none";
      loginForm.style.display = "block";
    });
  
    // Toggle password visibility
    passwordToggleIcons.forEach(icon => {
      icon.addEventListener("click", (e) => {
        const input = e.target.closest('.input_box').querySelector('input[type="password"]');
        const isPasswordVisible = input.type === "text";
        input.type = isPasswordVisible ? "password" : "text";
        e.target.classList.toggle('uil-eye-slash');
        e.target.classList.toggle('uil-eye');
      });
    });
  });
  