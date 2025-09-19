(function () {
  function getIconSVG() {
    // 📷 Ícono de cámara
    return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7 9H5l3-3 3 3H9v5H7V9zm5-4c0-.44-.91-3-4.5-3C5.08 2 3 3.92 3 6 1.02 6 0 7.52 0 9c0 1.53 1 3 3 3h3v-1.3H3c-1.62 0-1.7-1.42-1.7-1.7 0-.17.05-1.7 1.7-1.7h1.3V6c0-1.39 1.56-2.7 3.2-2.7 2.55 0 3.13 1.55 3.2 1.8v1.2H12c.81 0 2.7.22 2.7 2.2 0 2.09-2.25 2.2-2.7 2.2h-2V12h2c2.08 0 4-1.16 4-3.5C16 6.06 14.08 5 12 5z"/></svg>`;
  }

  function initDropImg() {
    document.querySelectorAll("[data-dropimg]").forEach((input) => {
      if (input.dataset.dropimgInit) return;
      input.dataset.dropimgInit = true;

      const width = parseInt(input.getAttribute("data-width")) || 300;
      const height = parseInt(input.getAttribute("data-height")) || 200;
      const defaultImg = input.getAttribute("data-default") || null;

      // 📌 Crear contenedor principal
      const zone = document.createElement("div");
      zone.className = "dropimg-zone";
      zone.style.aspectRatio = `${width} / ${height}`;
      zone.style.maxWidth = width + "px";
      zone.style.width = "100%";

      // 📌 Crear preview
      const preview = document.createElement("div");
      preview.className = "dropimg-preview";
      preview.innerHTML = `<span class="dropimg-text">SELECCIONAR ARCHIVO</span>`;
      zone.appendChild(preview);

      // 📌 Reemplazar input
      input.parentNode.replaceChild(zone, input);
      zone.appendChild(input);
      input.style.display = "none";

      // 📌 Texto de recomendación
      const recommend = document.createElement("small");
      recommend.className = "dropimg-recommend";
      recommend.innerHTML = `TAMAÑO RECOMENDADO: <b>${width} x ${height} píxeles</b>`;
      zone.insertAdjacentElement("afterend", recommend);

      // 📌 Centrar dentro del padre
      const parent = zone.parentNode;
      parent.style.display = "flex";
      parent.style.alignItems = "center";
      parent.style.justifyContent = "center";
      parent.style.flexDirection = "column";

      // 📌 Ajuste de clases según tamaño
      if (width <= 150 || height <= 150) {
        zone.classList.add("small");
      }
      if (width <= 100 || height <= 100) {
        zone.classList.add("tiny");
        preview.innerHTML = `<span class="dropimg-text">${getIconSVG()}</span>`;
      }

      // 📌 Imagen por defecto
      if (defaultImg) {
        preview.style.backgroundImage = `url(${defaultImg})`;
        if (zone.classList.contains("tiny")) {
          preview.innerHTML = `<span class="dropimg-text">${getIconSVG()}</span>`;
        } else {
          preview.innerHTML = `<span class="dropimg-text">CAMBIAR ARCHIVO</span>`;
        }
      }

      // 📌 Eventos
      zone.addEventListener("click", () => input.click());

      zone.addEventListener("dragover", (e) => {
        e.preventDefault();
        zone.style.borderColor = "#3498db";
      });

      zone.addEventListener("dragleave", () => {
        zone.style.borderColor = "#bbb";
      });

      zone.addEventListener("drop", (e) => {
        e.preventDefault();
        zone.style.borderColor = "#bbb";
        input.files = e.dataTransfer.files;
        mostrarPreview(input.files[0]);
      });

      input.addEventListener("change", () => {
        if (input.files.length > 0) {
          mostrarPreview(input.files[0]);
        }
      });

      function mostrarPreview(file) {
        if (!file.type.startsWith("image/")) return;
        const reader = new FileReader();
        reader.onload = (e) => {
          preview.style.backgroundImage = `url(${e.target.result})`;
          if (zone.classList.contains("tiny")) {
            preview.innerHTML = `<span class="dropimg-text">${getIconSVG()}</span>`;
          } else {
            preview.innerHTML = `<span class="dropimg-text">CAMBIAR ARCHIVO</span>`;
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // 📌 Exportar librería global
  window.DropImg = { init: initDropImg };
})();
