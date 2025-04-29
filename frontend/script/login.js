const popup = document.getElementById("popup");
const login = document.getElementById("loginbtn");
const loginForm = document.getElementById("loginForm");

// Add event listener to the form
loginForm.addEventListener("submit", loginButton);

// Get the input fields for email and password
const userEmail = document.getElementById("userEmail");
const userPassword = document.getElementById("userPassword");

// Store the user data in an array of objects
const users = [{ email: "admin@gmail.com", password: "admin" }];

function showPopup(message) {
  popup.textContent = message;
  popup.classList.remove("hidden");
  popup.classList.add("visible");

  setTimeout(() => {
    popup.classList.remove("visible");
    popup.classList.add("hidden");
  }, 2000); // Hide the popup after 2 seconds
}

function loginButton() {
  const email = userEmail.value;
  const password = userPassword.value;

  if (email === "" || password === "") {
    showPopup("Please fill in all fields!");
    return;
  }
  // Check if the user exists in the array
  const user = users.find(
    (user) => user.email === email && user.password === password
  );
  // If the user exists, show a success message and redirect to the dashboard page

  if (user) {
    showPopup("Login successful!");
    setTimeout(() => {
      window.location.href = "../../frontend/pages/dashboard.html"; // Redirect to the dashboard page
    }, 2000); // Redirect after 3 seconds
  }
  // If the user does not exist, show an error message and redirect to the login page
  else {
    showPopup("Invalid Email or password.");
    setTimeout(() => {
      window.location.href = "../../frontend/pages/login.html"; // Redirect to the login page
    }, 2000); // Redirect after 3 seconds
  }
}
