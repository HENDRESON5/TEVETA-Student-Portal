// ============================================================
// SHARED STUDENT SCRIPT
// Handles the mobile sidebar toggle for every student page.
// Page-specific scripts (like password show/hide, mismatch
// checks) live in their own page, not here, to keep this file
// generic and reusable across the whole student section.
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuToggle = document.getElementById('menuToggle');

  if (!sidebar || !overlay || !menuToggle) return;

  function openSidebar(){
    sidebar.classList.add('open');
    overlay.classList.add('show');
    menuToggle.setAttribute('aria-expanded', 'true');
  }

  function closeSidebar(){
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    menuToggle.setAttribute('aria-expanded', 'false');
  }

  menuToggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });

  overlay.addEventListener('click', closeSidebar);

  document.querySelectorAll('.menu a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 900) closeSidebar();
    });
  });
});
