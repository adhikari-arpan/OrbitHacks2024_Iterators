document.addEventListener("DOMContentLoaded", () => {
  const loginFormContainer = document.querySelector(".form-container");
  const signupFormContainer = document.getElementById("signup-form-container");
  const showSignupLink = document.getElementById("show-signup");
  const showLoginLink = document.getElementById("show-login");
  const signupOptions = document.querySelectorAll(".signup-option");
  const clientForm = document.getElementById("client-form");
  const counselorForm = document.getElementById("counselor-form");

  // Toggle between login and signup
  showSignupLink.addEventListener("click", (e) => {
    e.preventDefault();
    loginFormContainer.classList.add("hidden");
    signupFormContainer.classList.remove("hidden");
  });

  showLoginLink.addEventListener("click", (e) => {
    e.preventDefault();
    signupFormContainer.classList.add("hidden");
    loginFormContainer.classList.remove("hidden");
  });

  // Handle signup option selection
  signupOptions.forEach((option) => {
    option.addEventListener("click", () => {
      const targetForm = option.getAttribute("data-target");
      signupOptions.forEach((opt) => opt.classList.remove("active"));
      option.classList.add("active");

      clientForm.classList.add("hidden");
      counselorForm.classList.add("hidden");

      document.getElementById(targetForm).classList.remove("hidden");
    });
  });

  // Form validation
  function validateForm(form) {
    const inputs = form.querySelectorAll(
      "input[required], select[required], textarea[required]"
    );
    let isValid = true;

    inputs.forEach((input) => {
      if (!input.value.trim()) {
        isValid = false;
        input.style.borderColor = "red";
      } else {
        input.style.borderColor = "#ddd";
      }
    });

    return isValid;
  }

  // Client Form Submission
  clientForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (validateForm(clientForm)) {
      clientForm.submit();
    }
  });

  // Counselor Form Submission
  counselorForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (validateForm(counselorForm)) {
      counselorForm.submit();
    }
  });

  // Optional: Password validation
  const passwordFields = document.querySelectorAll('input[type="password"]');
  passwordFields.forEach((field) => {
    field.addEventListener("input", () => {
      const password = field.value;
      const strengthIndicator = document.createElement("div");
      strengthIndicator.className = "password-strength";

      let strength = 0;
      strength += password.length >= 8 ? 1 : 0;
      strength += /[A-Z]/.test(password) ? 1 : 0;
      strength += /[a-z]/.test(password) ? 1 : 0;
      strength += /[0-9]/.test(password) ? 1 : 0;
      strength += /[^A-Za-z0-9]/.test(password) ? 1 : 0;

      strengthIndicator.innerHTML = `Password Strength: ${"★".repeat(
        strength
      )}${"☆".repeat(5 - strength)}`;

      // Remove existing indicator and append new one
      const existingIndicator = field.nextElementSibling;
      if (
        existingIndicator &&
        existingIndicator.classList.contains("password-strength")
      ) {
        existingIndicator.remove();
      }
      field.after(strengthIndicator);
    });
  });
});
