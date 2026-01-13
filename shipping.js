document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("shippingForm");

  form.addEventListener("submit", function(event) {
    let valid = true;

    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const phone = document.getElementById("phone");
    const street = document.getElementById("street");
    const house = document.getElementById("house");
    const zip = document.getElementById("zip");
    const city = document.getElementById("city");

    document.querySelectorAll(".error-msg").forEach(e => e.remove());

    function showError(input, message) {
      valid = false;
      const error = document.createElement("div");
      error.className = "error-msg";
      error.style.color = "red";
      error.style.fontSize = "0.85em";
      error.textContent = message;
      input.parentNode.appendChild(error);
    }

    if (firstName.value.trim() === "") showError(firstName, "First name is required.");
    if (lastName.value.trim() === "") showError(lastName, "Last name is required.");
    if (phone.value.trim() === "") {
      showError(phone, "Phone number is required.");
    } else {
      
      const phonePattern = /^\+383\s\d{2}\s\d{3}\s\d{3,4}$/; 
      if (!phonePattern.test(phone.value.trim())) {
        showError(phone, "Phone number format: +383 XXX XXX XXX");
      }
    }
    if (street.value.trim() === "") showError(street, "Street name is required.");
    if (house.value.trim() === "") showError(house, "House number is required.");
    if (zip.value.trim() === "") showError(zip, "ZIP code is required.");
    if (city.value.trim() === "") showError(city, "City is required.");

    if (!valid) {
      event.preventDefault();
    }
  });
});
