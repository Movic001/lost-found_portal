// Toggle sidebar function
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const navbar = document.getElementById("navbar");
  const container = document.getElementById("container");
  const footer = document.getElementById("footer");
  const overlay = document.getElementById("overlay");

  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");

  // Check if we're on mobile
  if (window.innerWidth <= 1024) {
    // Mobile behavior
    overlay.classList.toggle("active");
  } else {
    // Desktop behavior
    navbar.classList.toggle("full-width");
    container.classList.toggle("full-width");
    footer.classList.toggle("full-width");
  }
}

// Check screen size on load and resize
function checkScreenSize() {
  const sidebar = document.getElementById("sidebar");
  const navbar = document.getElementById("navbar");
  const container = document.getElementById("container");
  const footer = document.getElementById("footer");

  if (window.innerWidth <= 1024) {
    // Mobile view - sidebar starts hidden
    sidebar.classList.remove("active");
    navbar.classList.add("full-width");
    container.classList.add("full-width");
    footer.classList.add("full-width");
  } else {
    // Desktop view - sidebar starts visible
    sidebar.classList.add("active");
    navbar.classList.remove("full-width");
    container.classList.remove("full-width");
    footer.classList.remove("full-width");
  }
}

// Initialize on page load
window.addEventListener("load", checkScreenSize);
window.addEventListener("resize", checkScreenSize);

// Ensure links work properly
document.querySelectorAll(".action-btn").forEach((button) => {
  if (button.parentElement.tagName === "A") {
    button.addEventListener("click", function (e) {
      window.location.href = this.parentElement.getAttribute("href");
    });
  }
});
