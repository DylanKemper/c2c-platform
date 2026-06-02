// Create a validate object to hold validation functions
const Validate = {
  // Check if a value is empty when removing leading and trailing whitespace
  isEmpty: function (value) {
    return value.trim() === "";
  },

  // Check if a value meets a minimum length requirement after trimming whitespace
  minLength: function (value, min) {
    return value.trim().length >= min;
  },

  // Check if a value is a positive number by ensuring it's not NaN and greater than 0
  isPositiveNumber: function (value) {
    return !isNaN(value) && parseFloat(value) > 0;
  },

  // Check if a value is a valid email address using a regular expression
  // Regular expression checks for a basic email format: 
  // one or more non-whitespace characters before the "@" symbol, 
  // followed by one or more non-whitespace characters, 
  // a dot, 
  // and one or more non-whitespace characters after the dot
  isEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
  },

  // Show an error message for a specific input field by adding the "is-invalid" class and displaying feedback
  showError: function (input, message) {
    const field = document.getElementById(fieldID);
    field.classList.add("is-invalid");
    let feedback = field.nextElementSibling;
    if (!feedback || !feedback.classList.contains("invalid-feedback")) {
      // If feedback element doesn't exist, create it
      feedback = document.createElement("div");
      feedback.classList.add("invalid-feedback");
      field.after(feedback);
    }
    feedback.textContent = message; // Set the error message text
  },

  // Remove the error state from an input field by removing the "is-invalid" class and clearing feedback text
  clearError: function (input) {
    const field = document.getElementById(fieldID);
    field.classList.remove("is-invalid"); // Remove the "is-invalid" class to clear the error state
    const feedback = field.nextElementSibling;
    if (feedback?.classList.contains("invalid-feedback")) {
      // If feedback element exists, clear its text content
      feedback.textContent = "";
    }
  },
};