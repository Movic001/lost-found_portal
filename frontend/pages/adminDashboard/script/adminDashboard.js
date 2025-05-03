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
});

// Function to fetch and display items
function openModal(item) {
  document.getElementById("modalItemName").textContent = item.item_name || "";
  // document.getElementById("modalItemImage").src = item.image_path
  //   ? `/frontend/uploads/${item.image_path}`
  //   : "";
  document.getElementById("modalItemImage").src = item.image_path
    ? `/../../../../frontend/uploads/${item.image_path}`
    : "";

  document.getElementById("modalItemLocation").textContent =
    item.location_found || "";
  document.getElementById("modalItemDate").textContent = item.date_found || "";
  document.getElementById("modalItemDescription").textContent =
    item.description || "";
  document.getElementById("modalContactName").textContent =
    item.person_name || "";
  document.getElementById("modalContactEmail").textContent =
    item.contact_info || "";

  document.getElementById("modalUniqueQuestion").textContent =
    item.unique_question || "";

  document.getElementById("itemModal").style.display = "block";
}

function closeModal() {
  document.getElementById("itemModal").style.display = "none";
}

// Optional: Close modal when clicking outside
window.onclick = function (event) {
  const modal = document.getElementById("itemModal");
  if (event.target === modal) {
    closeModal();
  }
};
