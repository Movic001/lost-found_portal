document.addEventListener("DOMContentLoaded", function () {
  const userProfile = document.querySelector(".user-profile");

  userProfile.addEventListener("click", function () {
    this.classList.toggle("active");
  });
});
