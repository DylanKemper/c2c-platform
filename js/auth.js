// This file contains JavaScript code for handling form validation on the login and registration pages.
// It uses a Validate object to perform basic validation checks and display error messages to the user.
// The code listens for form submission events, validates the input fields, and prevents form submission if any validation errors are found.
// This ensures that users provide valid data before it is sent to the server for processing.

// Register validation
document
  .getElementById("registerForm")
  .addEventListener("submit", function (e) {
    let valid = true;

    // Validate username is not empty and at least 3 characters long
    const username = document.getElementById("reg_username").value;
    if (!Validate.notEmpty(username)) {
      Validate.showError("reg_username", "Username is required.");
      valid = false;
    } else if (!Validate.minLength(username, 3)) {
      Validate.showError(
        "reg_username",
        "Username must be at least 3 characters.",
      );
      valid = false;
    } else {
      Validate.clearError("reg_username");
    }

    // Validate email is not empty and in a valid format
    const email = document.getElementById("reg_email").value;
    if (!Validate.notEmpty(email)) {
      Validate.showError("reg_email", "Email is required.");
      valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      Validate.showError("reg_email", "Enter a valid email address.");
      valid = false;
    } else {
      Validate.clearError("reg_email");
    }

    // Validate password is not empty and at least 8 characters long
    const password = document.getElementById("reg_password").value;
    if (!Validate.notEmpty(password)) {
      Validate.showError("reg_password", "Password is required.");
      valid = false;
    } else if (!Validate.minLength(password, 8)) {
      Validate.showError(
        "reg_password",
        "Password must be at least 8 characters.",
      );
      valid = false;
    } else {
      Validate.clearError("reg_password");
    }

    if (!valid) e.preventDefault();
  });

// Login validation
document.getElementById("loginForm").addEventListener("submit", function (e) {
  let valid = true;

  // Validate email is not empty and in a valid format
  const email = document.getElementById("login_email").value;
  if (!Validate.notEmpty(email)) {
    Validate.showError("login_email", "Email is required.");
    valid = false;
  } else {
    Validate.clearError("login_email");
  }

  // Validate password is not empty and at least 8 characters long
  const password = document.getElementById("login_password").value;
  if (!Validate.notEmpty(password)) {
    Validate.showError("login_password", "Password is required.");
    valid = false;
  } else if (!Validate.minLength(password, 8)) {
    Validate.showError(
      "login_password",
      "Password must be at least 8 characters.",
    );
    valid = false;
  } else {
    Validate.clearError("login_password");
  }

  if (!valid) e.preventDefault();
});
