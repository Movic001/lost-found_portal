const adminpopup = document.getElementById("admin_popup_message");
const adminloginForm = document.getElementById("admin-login-form");

// Get the input fields for email and password
const adminEmail = document.getElementById("adminEmail");
const adminPassword = document.getElementById("adminPassword");

// Store the user data in an array of objects
const adminusers = [{ email: "admin@campus.edu", password: "admin" }];

adminloginForm.addEventListener("submit", handleAdminLogin);

function showPopup(message) {
  adminpopup.textContent = message;
  adminpopup.classList.remove("hidden");
  adminpopup.classList.add("visible");

  setTimeout(() => {
    adminpopup.classList.remove("visible");
    adminpopup.classList.add("hidden");
  }, 2000);
}

function handleAdminLogin(event) {
  event.preventDefault();

  const email = adminEmail.value;
  const password = adminPassword.value;

  if (email === "" || password === "") {
    showPopup("Please fill in all fields!");
    return;
  }

  const admin = adminusers.find(
    (user) => user.email === email && user.password === password
  );

  if (admin) {
    showPopup("Login successful!");
    setTimeout(() => {
      window.location.href =
        "../../frontend/pages/adminDashboard/pages/adminDashboard.html";
    }, 2000);
  } else {
    showPopup("Invalid Email or password.");
    setTimeout(() => {
      window.location.href = "../../frontend/pages/login.html";
    }, 2000);
  }
}
