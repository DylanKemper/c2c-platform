// This script handles form validation for the payment form. It uses the Validate object defined in validate.js to check user input before allowing form submission.
// The script listens for the form's submit event, validates each field, and prevents submission if any validation checks fail, providing feedback to the user on what needs to be corrected.
document
  .getElementById("payment-form")
  .addEventListener("submit", function (e) {
    let valid = true;

    // Card Fields
    const name = document.getElementById("cardholder-name").value.trim();
    if (!name) {
      Validate.showError("cardholder-name", "Cardholder name is required.");
      valid = false;
    } else {
      Validate.clearError("cardholder-name");
    }

    const cardNumber = document
      .getElementById("card-number")
      .value.replace(/\s/g, "");
    if (!/^\d{16}$/.test(cardNumber)) {
      Validate.showError("card-number", "Card number must be 16 digits.");
      valid = false;
    } else {
      Validate.clearError("card-number");
    }

    const expiry = document.getElementById("expiry").value.replace(/\s/g, "");
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
      Validate.showError("expiry", "Enter a valid expiry in MM/YY format.");
      valid = false;
    } else {
      const [month, year] = expiry.split("/");
      const exp = new Date(2000 + parseInt(year), parseInt(month) - 1);
      if (exp < new Date()) {
        Validate.showError("expiry", "Card has expired.");
        valid = false;
      } else {
        Validate.clearError("expiry");
      }
    }

    const cvv = document.getElementById("cvv").value;
    if (!/^\d{3,4}$/.test(cvv)) {
      Validate.showError("cvv", "CVV must be 3 or 4 digits.");
      valid = false;
    } else {
      Validate.clearError("cvv");
    }

    // ── Delivery fields ──────────────────────────────────────
    const firstName = document.getElementById("first-name").value.trim();
    if (!firstName) {
      Validate.showError("first-name", "First name is required.");
      valid = false;
    } else {
      Validate.clearError("first-name");
    }

    const lastName = document.getElementById("last-name").value.trim();
    if (!lastName) {
      Validate.showError("last-name", "Last name is required.");
      valid = false;
    } else {
      Validate.clearError("last-name");
    }

    const phone = document.getElementById("phone").value.trim();
    if (!/^\+?\d[\d\s]{7,14}$/.test(phone)) {
      Validate.showError("phone", "Enter a valid phone number.");
      valid = false;
    } else {
      Validate.clearError("phone");
    }

    const deliveryAddress = document
      .getElementById("delivery-address")
      .value.trim();
    if (!deliveryAddress) {
      Validate.showError("delivery-address", "Delivery address is required.");
      valid = false;
    } else {
      Validate.clearError("delivery-address");
    }

    const city = document.getElementById("city").value.trim();
    if (!city) {
      Validate.showError("city", "City is required.");
      valid = false;
    } else {
      Validate.clearError("city");
    }

    const postal = document.getElementById("postal-code").value.trim();
    if (!/^\d{4}$/.test(postal)) {
      Validate.showError("postal-code", "Enter a valid 4-digit postal code.");
      valid = false;
    } else {
      Validate.clearError("postal-code");
    }

    if (!valid) e.preventDefault();
  });
