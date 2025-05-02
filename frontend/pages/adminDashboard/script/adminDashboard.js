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

//display full details of the item in a modal
// Get the modal element
function openModal(item) {
  document.getElementById("modalItemName").textContent = item.item_name;
  document.getElementById("modalItemImage").src = "../uploads/" + item.image;
  document.getElementById("modalItemLocation").textContent = item.location;
  document.getElementById("modalItemDate").textContent = item.date_found;
  document.getElementById("modalItemDescription").textContent =
    item.description;
  document.getElementById("modalContactName").textContent = item.contact_name;
  document.getElementById("modalContactEmail").textContent = item.contact_email;

  document.getElementById("itemModal").style.display = "block";
}

function closeModal() {
  document.getElementById("itemModal").style.display = "none";
}

// function openModal(
//   itemName,
//   status,
//   location,
//   date,
//   imagePath,
//   personName,
//   contact
// ) {
//   document.getElementById("modalItemName").innerText = itemName;
//   document.getElementById("modalStatus").innerText = status;
//   document.getElementById("modalLocation").innerText = location;
//   document.getElementById("modalDate").innerText = date;
//   document.getElementById("modalImage").src = `../../../uploads/${imagePath}`;
//   document.getElementById(
//     "modalContact"
//   ).innerText = `${personName} (${contact})`;

//   document.getElementById("itemModal").style.display = "flex";
// }

// function closeModal() {
//   document.getElementById("itemModal").style.display = "none";
// }
