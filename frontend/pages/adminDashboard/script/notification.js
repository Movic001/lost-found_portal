// Mobile navbar toggle
document.addEventListener("DOMContentLoaded", function () {
  // Handle mobile navbar toggle
  const navbarToggle = document.getElementById("navbarToggle");
  const navbarMenu = document.getElementById("navbarMenu");

  if (navbarToggle && navbarMenu) {
    navbarToggle.addEventListener("click", function () {
      navbarMenu.classList.toggle("active");
    });
  }

  // Handle form submissions with visual feedback
  const claimForms = document.querySelectorAll(".claim-form");

  claimForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      // Get the parent card
      const card = this.closest(".claim-card");
      const action = e.submitter.value; // 'approved' or 'rejected'

      // Add visual feedback
      card.classList.add("pulse");

      // Show toast notification
      showToast(
        action === "approved"
          ? "Claim approved successfully!"
          : "Claim rejected",
        action === "approved" ? "success" : "error"
      );

      // Remove animation class after it completes
      setTimeout(() => {
        card.classList.remove("pulse");
      }, 300);

      // Note: The form will submit normally,
      // this is just for visual feedback before page reload
    });
  });

  // Toast notification function
  window.showToast = function (message, type = "success") {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.className = "toast";

    if (type === "error") {
      toast.classList.add("error");
    }

    // Show the toast
    setTimeout(() => {
      toast.classList.add("show");
    }, 10);

    // Hide after 3 seconds
    setTimeout(() => {
      toast.classList.remove("show");
    }, 3000);
  };

  // Add accessibility improvements for keyboard navigation
  const focusableElements = document.querySelectorAll("a, button, input");
  focusableElements.forEach((element) => {
    element.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.target.click();
      }
    });
  });
});
