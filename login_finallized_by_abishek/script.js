// Get the elements for login and signup forms
const loginFormContainer = document.getElementById("login-form-container");
const signupFormContainer = document.getElementById("signup-form-container");

// Get the links for switching forms
const showSignupLink = document.getElementById("show-signup");
const showLoginLink = document.getElementById("show-login");

// Add event listener for the "Sign up" link (to show the signup form)
showSignupLink.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent the default link behavior
  loginFormContainer.classList.add("hidden"); // Hide the login form
  signupFormContainer.classList.remove("hidden"); // Show the signup form
});

// Add event listener for the "Sign in" link (to show the login form)
showLoginLink.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent the default link behavior
  signupFormContainer.classList.add("hidden"); // Hide the signup form
  loginFormContainer.classList.remove("hidden"); // Show the login form
});
