document.addEventListener("DOMContentLoaded", function () {
  const html = document.documentElement;
  const logoLigth = document.getElementById("logo-ligth");
  const logoDark = document.getElementById("logo-dark");

  // Función para actualizar el logo según el tema
  function updateLogo() {
    const theme = html.getAttribute("data-bs-theme");

    if (theme === "dark") {
      // Si el tema es dark, muestra el logo dark y oculta el logo light
      logoLigth.classList.remove("d-none");
      logoDark.classList.add("d-none");
    } else {
      // Si el tema es light (o cualquier otro valor), muestra el logo light y oculta el logo dark
      logoLigth.classList.add("d-none");
      logoDark.classList.remove("d-none");
    }
  }

  // Observador de atributos para detectar cambios en data-bs-theme
  const observer = new MutationObserver(function (mutationsList) {
    for (let mutation of mutationsList) {
      if (
        mutation.type === "attributes" &&
        mutation.attributeName === "data-bs-theme"
      ) {
        updateLogo();
      }
    }
  });

  // Configura el observador para observar cambios en el atributo data-bs-theme de <html>
  observer.observe(html, { attributes: true });

  // Llama a la función inicialmente para establecer el logo correcto
  updateLogo();
});
