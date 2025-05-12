// Interactive Enhancements
document.addEventListener("DOMContentLoaded", () => {
  // Add subtle animations and interactions
  const listItems = document.querySelectorAll("li");

  listItems.forEach((item) => {
    // Accessibility: Add role and tabindex for keyboard navigation
    item.setAttribute("role", "article");
    item.setAttribute("tabindex", "0");

    // Hover and focus effects
    item.addEventListener("mouseenter", () => {
      item.style.transform = "translateY(-5px)";
    });

    item.addEventListener("mouseleave", () => {
      item.style.transform = "translateY(0)";
    });

    // Keyboard interaction support
    item.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        // Optional: Toggle more details or highlight
        item.classList.toggle("focused");
      }
    });
  });

  // Print-friendly functionality
  window.addEventListener("beforeprint", () => {
    listItems.forEach((item) => {
      item.style.transform = "none";
    });
  });
});
