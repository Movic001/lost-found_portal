/**
 * Enhanced delete confirmation with item name
 * @param {Event} event - The click event
 * @param {String} itemName - The name of the item being deleted
 * @return {Boolean} - Whether to proceed with deletion
 */
function confirmDelete(event, itemName) {
  event.preventDefault();

  // Create custom confirmation dialog
  const overlay = document.createElement("div");
  overlay.style.position = "fixed";
  overlay.style.top = "0";
  overlay.style.left = "0";
  overlay.style.width = "100%";
  overlay.style.height = "100%";
  overlay.style.backgroundColor = "rgba(0,0,0,0.7)";
  overlay.style.display = "flex";
  overlay.style.alignItems = "center";
  overlay.style.justifyContent = "center";
  overlay.style.zIndex = "1000";

  const dialog = document.createElement("div");
  dialog.style.backgroundColor = "white";
  dialog.style.padding = "20px";
  dialog.style.borderRadius = "8px";
  dialog.style.maxWidth = "90%";
  dialog.style.width = "400px";
  dialog.style.boxShadow = "0 4px 6px rgba(0,0,0,0.1)";

  dialog.innerHTML = `
                <h3 style="margin-bottom:15px;">Confirm Deletion</h3>
                <p style="margin-bottom:20px;">Are you sure you want to delete the item: <strong>${itemName}</strong>?</p>
                <div style="display:flex;justify-content:flex-end;gap:10px;">
                    <button id="cancel-delete" style="padding:8px 16px;border:none;background:#95a5a6;color:white;border-radius:4px;cursor:pointer;">Cancel</button>
                    <button id="confirm-delete" style="padding:8px 16px;border:none;background:#e74c3c;color:white;border-radius:4px;cursor:pointer;">Delete</button>
                </div>
            `;

  overlay.appendChild(dialog);
  document.body.appendChild(overlay);

  // Handle dialog actions
  document
    .getElementById("cancel-delete")
    .addEventListener("click", function () {
      document.body.removeChild(overlay);
    });

  document
    .getElementById("confirm-delete")
    .addEventListener("click", function () {
      document.body.removeChild(overlay);
      window.location.href = event.target.closest("a").href;
    });

  // Prevent default link behavior
  return false;
}

/**
 * Add smooth animations and accessibility enhancements
 * to the posted items on page load
 */
document.addEventListener("DOMContentLoaded", function () {
  // Add animation to items
  const items = document.querySelectorAll("div:not(.empty-state)");

  // If there are items, animate them in sequence
  if (items.length > 0) {
    items.forEach((item, index) => {
      // Make items focusable for keyboard navigation
      item.setAttribute("tabindex", "0");
      item.setAttribute("role", "article");

      // Add staggered entrance animation
      item.style.opacity = "0";
      item.style.transform = "translateY(20px)";

      setTimeout(() => {
        item.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        item.style.opacity = "1";
        item.style.transform = "translateY(0)";
      }, 100 * index);

      // Add keyboard interaction for better accessibility
      item.addEventListener("keydown", function (e) {
        // If Enter key is pressed, toggle focus/selection visual
        if (e.key === "Enter") {
          this.classList.toggle("focused-item");
        }
      });
    });
  }
});
