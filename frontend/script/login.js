const userTab = document.getElementById("user-tab");
const adminTab = document.getElementById("admin-tab");
const userForm = document.querySelector(".user-form");
const adminForm = document.querySelector(".admin-form");

// Switch to User Tab
userTab.addEventListener("click", () => {
  userTab.classList.add("active");
  adminTab.classList.remove("active");
  userForm.classList.add("active");
  adminForm.classList.remove("active");
});

// Switch to Admin Tab
adminTab.addEventListener("click", () => {
  adminTab.classList.add("active");
  userTab.classList.remove("active");
  adminForm.classList.add("active");
  userForm.classList.remove("active");
});
