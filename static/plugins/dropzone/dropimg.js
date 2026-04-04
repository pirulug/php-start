(function () {
  /**
   * DropImg - A modern, lightweight image upload previewer.
   * Author: Antigravity AI
   */
  
  const ICONS = {
    upload: '<i class="fa-solid fa-cloud-arrow-up"></i>',
    change: '<i class="fa-solid fa-rotate"></i>',
    remove: '<i class="fa-solid fa-xmark"></i>',
    error: '<i class="fa-solid fa-triangle-exclamation"></i>'
  };

  function initDropImg() {
    document.querySelectorAll("[data-dropimg]").forEach((input) => {
      if (input.dataset.dropimgInit) return;

      // Configuration
      const width = parseInt(input.getAttribute("data-width")) || 300;
      const height = parseInt(input.getAttribute("data-height")) || 200;
      const defaultImg = input.getAttribute("data-default") || null;
      const aspect = input.getAttribute("data-aspect") || "square";
      const maxSizeMB = parseFloat(input.getAttribute("data-max-size")) || 2;
      const acceptedAttr = input.getAttribute("accept") || "image/*";
      const accepted = acceptedAttr.split(",").map((ext) => ext.trim().toLowerCase());
      const isWildcard = accepted.some(a => a === "image/*");

      // Encapsulation Container
      const container = document.createElement("div");
      container.className = "dropimg-content";

      // Main Drop Zone
      const zone = document.createElement("div");
      zone.className = "dropimg-zone";
      if (aspect === "circle") zone.classList.add("dropimg-circle");
      
      zone.style.aspectRatio = aspect === "circle" ? "1 / 1" : `${width} / ${height}`;

      if (aspect === "circle") {
        zone.style.width = width + "px";
        zone.style.height = width + "px";
        zone.style.minWidth = width + "px";
        zone.classList.add("flex-shrink-0");
      } else {
        zone.style.maxWidth = width + "px";
        zone.style.width = "100%";
      }

      try {
        // Internal Elements
        const preview = document.createElement("div");
        preview.className = "dropimg-preview";
        
        const errorMsg = document.createElement("small");
        errorMsg.className = "dropimg-error";
        errorMsg.style.display = "none";

        const removeBtn = document.createElement("div");
        removeBtn.className = "dropimg-remove";
        removeBtn.innerHTML = ICONS.remove;
        removeBtn.title = "Eliminar imagen";

        // UI Update Helper
        const updatePreviewUI = (bg = null) => {
          if (bg) {
            preview.style.backgroundImage = `url(${bg})`;
            preview.innerHTML = `<div class="dropimg-overlay"><span class="dropimg-text">${ICONS.change} <span>CAMBIAR</span></span></div>`;
            container.appendChild(removeBtn);
          } else {
            preview.style.backgroundImage = "none";
            preview.innerHTML = `<span class="dropimg-text">${ICONS.upload} <span>SUBIR IMAGEN</span></span>`;
            if (removeBtn.parentNode) removeBtn.parentNode.removeChild(removeBtn);
          }
        };

        // File Validation Helper
        const validateFile = (file) => {
          if (!file) return;
          const ext = "." + file.name.split(".").pop().toLowerCase();
          const isValidExt = accepted.includes(ext);
          const isImageType = file.type.startsWith("image/");
          
          if (!isWildcard && !isValidExt && !isImageType) {
            showError(`Formato no válido. Use: ${accepted.join(", ")}`);
            return;
          }
          if (isWildcard && !isImageType) {
            showError("El archivo debe ser una imagen.");
            return;
          }
          if (file.size > maxSizeMB * 1024 * 1024) {
            showError(`Imagen muy pesada. Máximo: ${maxSizeMB}MB`);
            return;
          }
          hideError();
          showPreview(file);
        };

        const showPreview = (file) => {
          const reader = new FileReader();
          reader.onload = (e) => updatePreviewUI(e.target.result);
          reader.readAsDataURL(file);
        };

        const showError = (msg) => {
          errorMsg.innerHTML = `${ICONS.error} ${msg}`;
          errorMsg.style.display = "block";
          input.value = "";
          updatePreviewUI(defaultImg);
        };

        const hideError = () => errorMsg.style.display = "none";

        // Event Listeners
        removeBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          input.value = "";
          updatePreviewUI(null);
        });

        zone.addEventListener("click", () => input.click());

        ["dragenter", "dragover"].forEach(name => {
          zone.addEventListener(name, (e) => {
            e.preventDefault(); e.stopPropagation();
            zone.classList.add("drag-over");
          });
        });

        ["dragleave", "drop"].forEach(name => {
          zone.addEventListener(name, (e) => {
            e.preventDefault(); e.stopPropagation();
            zone.classList.remove("drag-over");
          });
        });

        zone.addEventListener("drop", (e) => {
          if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            validateFile(e.dataTransfer.files[0]);
          }
        });

        input.addEventListener("change", () => {
          if (input.files.length) validateFile(input.files[0]);
        });

        // Form Validation
        const form = input.closest("form");
        if (form && !form.dataset.dropimgReqInit) {
          form.dataset.dropimgReqInit = true;
          form.addEventListener("submit", (e) => {
            form.querySelectorAll("[data-dropimg]").forEach(inp => {
              if (inp.hasAttribute("data-required") && !inp.files.length && !inp.getAttribute("data-default")) {
                e.preventDefault();
                const err = inp.closest(".dropimg-content")?.querySelector(".dropimg-error");
                if (err) {
                  err.textContent = "Debes seleccionar una imagen.";
                  err.style.display = "block";
                }
              }
            });
          });
        }

        // Assembly & Replacement
        zone.appendChild(preview);
        container.appendChild(zone);

        if (!input.hasAttribute("data-no-recommend")) {
          const recommend = document.createElement("small");
          recommend.className = "dropimg-recommend";
          const formatsText = isWildcard ? "CUALQUIER IMAGEN" : accepted.join(", ").toUpperCase().replace(/\./g, "");
          recommend.innerHTML = `<div>RECOMENDADO: <b>${width} x ${height}px</b></div>
                                 <div style="margin-top: 4px; opacity: 0.8;">FORMATOS: <b>${formatsText}</b> (Máx: ${maxSizeMB}MB)</div>`;
          container.appendChild(recommend);
        }
        container.appendChild(errorMsg);

        // Success: Replace DOM
        const parent = input.parentNode;
        if (parent) {
          parent.replaceChild(container, input);
          zone.appendChild(input);
          input.style.display = "none";
          input.dataset.dropimgInit = "true";
          updatePreviewUI(defaultImg);
        }

      } catch (err) {
        console.error("DropImg Init failed:", err);
        input.style.display = "block"; // Fallback to normal input
      }
    });
  }

  window.DropImg = { init: initDropImg };
  document.addEventListener("DOMContentLoaded", initDropImg);
})();


