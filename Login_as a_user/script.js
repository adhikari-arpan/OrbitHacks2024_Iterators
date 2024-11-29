const loginForm = document.getElementById("login-form");
const signupForm = document.getElementById("signup-form-container");
const signupOptions = document.querySelectorAll(".signup-option");
const signupForms = document.querySelectorAll(".signup-form");
const showSignupLink = document.getElementById("show-signup");
const showLoginLink = document.getElementById("show-login");

showSignupLink.addEventListener("click", () => {
  loginForm.parentElement.classList.add("hidden");
  signupForm.classList.remove("hidden");
});

showLoginLink.addEventListener("click", () => {
  loginForm.parentElement.classList.remove("hidden");
  signupForm.classList.add("hidden");
});

signupOptions.forEach((option) => {
  option.addEventListener("click", () => {
    const targetForm = document.getElementById(option.dataset.target);
    signupOptions.forEach((opt) => opt.classList.remove("active"));
    option.classList.add("active");
    signupForms.forEach((form) => form.classList.add("hidden"));
    targetForm.classList.remove("hidden");
  });
});

loginForm.addEventListener("submit", (event) => {
  event.preventDefault();
  // Add login logic here
});

signupForms.forEach((form) => {
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    // Add sign-up logic here
  });
});
