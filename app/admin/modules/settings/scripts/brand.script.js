document.addEventListener('DOMContentLoaded', function () {
  if (typeof DropImg !== 'undefined') {
    DropImg.init();
  }
});

function toggleSim(id, originalClass, newClass) {
  const el = document.getElementById(id);
  if (!el) return;

  if (el.classList.contains(originalClass)) {
    el.classList.remove(originalClass);
    el.classList.add(newClass);
  } else {
    el.classList.remove(newClass);
    el.classList.add(originalClass);
  }
}
