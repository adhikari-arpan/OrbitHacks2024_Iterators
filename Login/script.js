const loginForm = document.getElementById("login-form");
const signupForm = document.getElementById("signup-form");
const signupFormContainer = document.getElementById("signup-form-container");
const switchFormLinks = document.querySelectorAll("#switch-form a");

switchFormLinks.forEach((link) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    loginForm.parentElement.classList.toggle("hidden");
    signupFormContainer.classList.toggle("hidden");
  });
});

loginForm.addEventListener("submit", (event) => {
  event.preventDefault();
  // Add login logic here
});

signupForm.addEventListener("submit", (event) => {
  event.preventDefault();
  // Add sign-up logic here
});
