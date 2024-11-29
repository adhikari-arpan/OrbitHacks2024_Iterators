document.addEventListener("DOMContentLoaded", () => {
    // Steps
    const initialOptions = document.getElementById("initialOptions");
    const roleSelection = document.getElementById("roleSelection");
    const formsContainer = document.getElementById("formsContainer");
  
    // Buttons
    const signUpBtn = document.getElementById("signUpBtn");
    const signInBtn = document.getElementById("signInBtn");
    const clientBtn = document.getElementById("clientBtn");
    const counselorBtn = document.getElementById("counselorBtn");
    const backToInitialBtn = document.getElementById("backToInitialBtn");
    const backToRoleBtn = document.getElementById("backToRoleBtn");
  
    // Forms
    const clientSignUpForm = document.getElementById("clientSignUpForm");
    const clientSignInForm = document.getElementById("clientSignInForm");
    const counselorSignUpForm = document.getElementById("counselorSignUpForm");
    const counselorSignInForm = document.getElementById("counselorSignInForm");
  
    // Utility to hide all steps
    const hideAllSteps = () => {
      initialOptions.classList.add("hidden");
      roleSelection.classList.add("hidden");
      formsContainer.classList.add("hidden");
    };
  
    // Utility to hide all forms
    const hideAllForms = () => {
      clientSignUpForm.classList.add("hidden");
      clientSignInForm.classList.add("hidden");
      counselorSignUpForm.classList.add("hidden");
      counselorSignInForm.classList.add("hidden");
    };
  
    // Step 1: Initial Options
    signUpBtn.addEventListener("click", () => {
      hideAllSteps();
      roleSelection.classList.remove("hidden");
      document.getElementById("roleSelectionTitle").innerText = "Sign Up As";
    });
  
    signInBtn.addEventListener("click", () => {
      hideAllSteps();
      roleSelection.classList.remove("hidden");
      document.getElementById("roleSelectionTitle").innerText = "Sign In As";
    });
  
    // Step 2: Role Selection
    clientBtn.addEventListener("click", () => {
      hideAllSteps();
      formsContainer.classList.remove("hidden");
      hideAllForms();
      if (document.getElementById("roleSelectionTitle").innerText.includes("Sign Up")) {
        clientSignUpForm.classList.remove("hidden");
      } else {
        clientSignInForm.classList.remove("hidden");
      }
    });
  
    counselorBtn.addEventListener("click", () => {
      hideAllSteps();
      formsContainer.classList.remove("hidden");
      hideAllForms();
      if (document.getElementById("roleSelectionTitle").innerText.includes("Sign Up")) {
        counselorSignUpForm.classList.remove("hidden");
      } else {
        counselorSignInForm.classList.remove("hidden");
      }
    });
  
    // Back Buttons
    backToInitialBtn.addEventListener("click", () => {
      hideAllSteps();
      initialOptions.classList.remove("hidden");
    });
  
    backToRoleBtn.addEventListener("click", () => {
      hideAllSteps();
      roleSelection.classList.remove("hidden");
    });
  });
  