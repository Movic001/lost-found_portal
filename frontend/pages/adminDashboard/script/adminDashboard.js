document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("toggleBtn");
  const closeBtn = document.getElementById("closeBtn");

  // Function to toggle sidebar
  function toggleSidebar() {
    sidebar.classList.toggle("collapsed");
    toggleBtn.classList.toggle("collapsed");
  }

  // Event listeners
  toggleBtn.addEventListener("click", toggleSidebar);
  closeBtn.addEventListener("click", toggleSidebar);

  // Handle responsive behavior
  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      sidebar.classList.add("collapsed");
      toggleBtn.classList.add("collapsed");
    } else {
      sidebar.classList.remove("collapsed");
      toggleBtn.classList.remove("collapsed");
    }
  }

  // Check window size on load and resize
  window.addEventListener("resize", checkWindowSize);
  checkWindowSize();

  // Add event listeners for active navigation
  const navItems = document.querySelectorAll(".nav-item");
  navItems.forEach((item) => {
    item.addEventListener("click", function () {
      navItems.forEach((nav) => nav.classList.remove("active"));
      this.classList.add("active");

      // On mobile, close sidebar after selection
      if (window.innerWidth <= 768) {
        sidebar.classList.add("collapsed");
        toggleBtn.classList.add("collapsed");
      }
    });
  });

  // Add event listeners for action buttons
  const actionBtns = document.querySelectorAll(".action-btn");
  actionBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();

      // You can add actual functionality here
      const action = this.classList.contains("approve-btn")
        ? "approved"
        : "rejected";
      const row = this.closest("tr");
      const item = row.cells[0].textContent;

      // For demo purposes, just show an alert
      alert(`Item "${item}" has been ${action}`);
    });
  });
});
