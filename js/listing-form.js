console.log("listing-form.js loaded");

// This script handles form validation for the listing form. It uses the Validate object defined in validate.js to check user input before allowing form submission.
// The script listens for the form's submit event, validates each field, and prevents submission if any validation checks fail, providing feedback to the user on what needs to be corrected.
document
  .getElementById("listing-form")
  .addEventListener("submit", function (e) {
    console.log("form submit fired");
    let valid = true;

    // Validate the title field to ensure it's not empty
    if (!Validate.notEmpty(document.getElementById("listing-title").value)) {
      Validate.showError("listing-title", "Title is required.");
      valid = false;
    } else {
      Validate.clearError("listing-title");
    }

    // Validate the price field to ensure it's a positive number
    if (
      !Validate.isPositiveNumber(document.getElementById("listing-price").value)
    ) {
      Validate.showError("listing-price", "Enter a valid price.");
      valid = false;
    } else {
      Validate.clearError("listing-price");
    }

    // Validate the description field to ensure it's at least 10 characters long
    if (
      !Validate.minLength(
        document.getElementById("listing-description").value,
        10,
      )
    ) {
      Validate.showError(
        "listing-description",
        "Description must be at least 10 characters.",
      );
      valid = false;
    } else {
      Validate.clearError("listing-description");
    }

    // Stops form submitting if anything failed
    if (!valid) e.preventDefault();
  });

// This handles the image upload and preview functionality.
// It listens for changes to the file input, validates the selected file's type and size, and if valid, displays a preview of the image.
// If the file is invalid, it shows an error message and resets the input.
document.getElementById("image").addEventListener("change", function () {
  const file = this.files[0];
  const preview = document.getElementById("image-preview");

  // If no file is selected, hide the preview and return
  if (!file) {
    preview.src = "";
    preview.style.display = "none";
    return;
  }

  // Validate file type and size
  const allowed = ["image/jpeg", "image/png", "image/webp"];
  if (!allowed.includes(file.type)) {
    Validate.showError("image", "Only JPG, PNG, or WEBP files are allowed.");
    this.value = "";
    return;
  }

  // Validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    Validate.showError("image", "File must be under 5MB.");
    this.value = "";
    return;
  }

  // Clear any previous errors
  Validate.clearError("image");

  // Use FileReader to read the selected file and display it as a preview
  const reader = new FileReader();
  reader.onload = (e) => {
    preview.src = e.target.result;
    preview.style.display = "block";
    document.getElementById("preview-no-img").style.display = "none";
  };
  reader.readAsDataURL(file);
});