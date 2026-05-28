/**
 * Osamu.js v2 - Editor WYSIWYG ligero, modular, multi-instancia y compatible con PHP.
 * Desarrollado con Javascript Vanilla y FontAwesome.
 */
class Osamu {

  // -----------------------------------------------------------------------------
  // SECCIÓN: CONTADORES Y PRESETS ESTÁTICOS
  // -----------------------------------------------------------------------------

  /**
   * Contador global de instancias para generar IDs únicos.
   * Garantiza que múltiples editores en la misma página no colisionen.
   */
  static get _count() {
    if (!Osamu.__count) Osamu.__count = 0;
    return Osamu.__count;
  }

  static set _count(val) {
    Osamu.__count = val;
  }

  /**
   * Presets de barra de herramientas para diferentes contextos de uso.
   * Permiten configurar el editor rápidamente sin listar todos los botones.
   *
   * @return {Object} Mapa de nombre de preset a array de herramientas.
   */
  static get PRESETS() {
    return {
      full: [
        "undo", "redo", "|",
        "formatBlock", "|",
        "bold", "italic", "underline", "strikeThrough", "|",
        "foreColor", "backColor", "|",
        "justifyLeft", "justifyCenter", "justifyRight", "justifyFull", "|",
        "insertUnorderedList", "insertOrderedList", "|",
        "quote", "codeBlock", "|",
        "link", "image", "youtube", "removeFormat", "|",
        "codeView"
      ],
      content: [
        "undo", "redo", "|",
        "formatBlock", "|",
        "bold", "italic", "underline", "|",
        "justifyLeft", "justifyCenter", "justifyRight", "|",
        "insertUnorderedList", "insertOrderedList", "|",
        "quote", "|",
        "link", "image", "removeFormat", "|",
        "codeView"
      ],
      basic: [
        "bold", "italic", "underline", "|",
        "insertUnorderedList", "insertOrderedList", "|",
        "link", "removeFormat"
      ],
      minimal: [
        "bold", "italic", "underline", "strikeThrough", "|",
        "removeFormat"
      ]
    };
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: CONSTRUCTOR E INICIALIZACIÓN
  // -----------------------------------------------------------------------------

  /**
   * Inicializa una nueva instancia de Osamu Editor.
   *
   * @param {string|HTMLTextAreaElement} target Selector CSS o el textarea directamente.
   * @param {Object} options Opciones de configuración.
   */
  constructor(target, options = {}) {
    this.textarea = typeof target === "string" ? document.querySelector(target) : target;
    if (!this.textarea || this.textarea.tagName !== "TEXTAREA") {
      console.error("Osamu.js: El elemento destino debe ser un <textarea> válido.");
      return;
    }

    // ID único para esta instancia: evita colisiones de IDs entre múltiples editores
    Osamu._count++;
    this._id = Osamu._count;

    // Debounce timer para syncTextarea
    this._syncTimer = null;

    // Referencia al handler de resize global para poder limpiarlo
    this._resizeHandler = null;

    // Opciones por defecto
    const defaultOptions = {
      height: "300px",
      minHeight: "200px",
      placeholder: "Escribe tu contenido aquí...",
      uploadUrl: null,
      liteYouTube: false,
      autoGrow: false,
      preset: null,
      toolbar: null
    };

    this.options = Object.assign({}, defaultOptions, options);

    // Resolver toolbar: preset tiene prioridad sobre toolbar manual
    if (this.options.preset && Osamu.PRESETS[this.options.preset]) {
      this.options.toolbar = Osamu.PRESETS[this.options.preset];
    } else if (!this.options.toolbar) {
      this.options.toolbar = Osamu.PRESETS.full;
    }

    this.isCodeActive = false;
    this.cmdButtons = {};
    this.selectedImage = null;
    this.resizer = null;
    this.savedRange = null;

    this._init();
  }

  /**
   * Inicializa la estructura completa del editor.
   */
  _init() {
    this._buildContainer();
    this._buildToolbar();
    this._buildEditableArea();
    this._buildInlineDialogs();
    this._buildResizer();
    this._buildInsertIndicator();

    // Cargar contenido inicial
    this.editorBody.innerHTML = this._convertVideosToWrappers(this.textarea.value) || "<p><br></p>";
    this._updatePlaceholder();

    this._bindEvents();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: CONSTRUCCIÓN DEL CONTENEDOR
  // -----------------------------------------------------------------------------

  /**
   * Crea el contenedor principal del editor e inserta antes del textarea.
   */
  _buildContainer() {
    this.container = document.createElement("div");
    this.container.className = "osamu-editor";
    this.container.dataset.osamuId = this._id;

    if (this.options.autoGrow) {
      this.container.classList.add("osamu-auto-grow");
      this.container.style.height = "auto";
      this.container.style.minHeight = this.options.minHeight;
    } else {
      this.container.style.height = this.options.height;
      this.container.style.minHeight = this.options.minHeight;
    }

    this.textarea.style.display = "none";
    this.textarea.parentNode.insertBefore(this.container, this.textarea);
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: BARRA DE HERRAMIENTAS
  // -----------------------------------------------------------------------------

  /**
   * Construye la barra de herramientas a partir del array de opciones.
   */
  _buildToolbar() {
    this.toolbar = document.createElement("div");
    this.toolbar.className = "osamu-toolbar";

    this.options.toolbar.forEach(item => {
      if (item === "|") {
        const sep = document.createElement("div");
        sep.className = "osamu-separator";
        this.toolbar.appendChild(sep);
        return;
      }
      if (item === "formatBlock") {
        this.toolbar.appendChild(this._buildFormatBlockSelect());
        return;
      }
      const btn = this._createToolbarButton(item);
      if (btn) {
        this.toolbar.appendChild(btn);
      }
    });

    this.container.appendChild(this.toolbar);
  }

  /**
   * Crea el selector de tipo de bloque (párrafo, encabezados, cita).
   *
   * @return {HTMLSelectElement} El elemento select construido.
   */
  _buildFormatBlockSelect() {
    const select = document.createElement("select");
    select.className = "osamu-select";
    select.title = "Estilo de Texto";

    const formats = [
      { value: "P", text: "Párrafo" },
      { value: "H1", text: "Título 1" },
      { value: "H2", text: "Título 2" },
      { value: "H3", text: "Título 3" },
      { value: "BLOCKQUOTE", text: "Cita" }
    ];

    formats.forEach(fmt => {
      const opt = document.createElement("option");
      opt.value = fmt.value;
      opt.textContent = fmt.text;
      select.appendChild(opt);
    });

    select.addEventListener("change", (e) => {
      this._exec("formatBlock", `<${e.target.value}>`);
    });

    return select;
  }

  /**
   * Crea un botón individual de la barra de herramientas.
   *
   * @param {string} name Nombre del botón según el mapa de definiciones.
   * @return {HTMLButtonElement|null} El botón creado o null si no se reconoce.
   */
  _createToolbarButton(name) {
    const defs = {
      bold: { icon: "fa-bold", title: "Negrita", cmd: "bold" },
      italic: { icon: "fa-italic", title: "Cursiva", cmd: "italic" },
      underline: { icon: "fa-underline", title: "Subrayado", cmd: "underline" },
      strikeThrough: { icon: "fa-strikethrough", title: "Tachado", cmd: "strikeThrough" },
      justifyLeft: { icon: "fa-align-left", title: "Alinear Izquierda", cmd: "justifyLeft" },
      justifyCenter: { icon: "fa-align-center", title: "Centrar", cmd: "justifyCenter" },
      justifyRight: { icon: "fa-align-right", title: "Alinear Derecha", cmd: "justifyRight" },
      justifyFull: { icon: "fa-align-justify", title: "Justificar", cmd: "justifyFull" },
      insertUnorderedList: { icon: "fa-list-ul", title: "Lista Desordenada", cmd: "insertUnorderedList" },
      insertOrderedList: { icon: "fa-list-ol", title: "Lista Ordenada", cmd: "insertOrderedList" },
      link: { icon: "fa-link", title: "Insertar Enlace", action: () => this._showDialog("link") },
      image: { icon: "fa-image", title: "Insertar Imagen", action: () => this._showDialog("image") },
      youtube: { icon: "fa-youtube", title: "Insertar Video de YouTube", action: () => this._showDialog("youtube") },
      quote: { icon: "fa-quote-right", title: "Insertar Cita", cmd: "formatBlock", value: "blockquote" },
      codeBlock: {
        icon: "fa-terminal", title: "Insertar Código", action: () => {
          this._exitCurrentBlock();
          const codeHtml = `<pre class="language-javascript" style="position:relative; padding-top:28px;"><div class="osamu-code-lang-selector" contenteditable="false" style="position:absolute; top:4px; right:10px; font-size:0.7rem; color:#6c757d; font-family:sans-serif; user-select:none; z-index:5; background:var(--osamu-toolbar-bg); border:1px solid var(--osamu-border); border-radius:4px; padding:2px 6px; display:inline-flex; align-items:center; gap:4px;"><span>Lang:</span><input class="osamu-code-lang-input-inline" type="text" value="javascript" style="background:transparent; border:none; color:inherit; font-size:inherit; font-family:inherit; outline:none; width:70px; font-weight:600;"></div><code class="language-javascript"><br></code></pre><p><br></p>`;
          this._exec("insertHTML", codeHtml);

          setTimeout(() => {
            this.editorBody.querySelectorAll(".osamu-code-lang-input-inline").forEach(inpEl => {
              if (!inpEl.dataset.listenerAttached) {
                inpEl.dataset.listenerAttached = "true";

                let debounceTimer = null;
                const updateLanguage = (e, immediate = false) => {
                  clearTimeout(debounceTimer);
                  const runUpdate = () => {
                    const preEl = e.target.closest("pre");
                    if (preEl) {
                      const codeEl = preEl.querySelector("code");
                      const lang = e.target.value.trim().toLowerCase() || "javascript";

                      // Limpiar clases viejas de lenguaje
                      const oldPreClasses = Array.from(preEl.classList).filter(c => c.startsWith("language-"));
                      oldPreClasses.forEach(c => preEl.classList.remove(c));
                      preEl.classList.add(`language-${lang}`);

                      if (codeEl) {
                        const oldCodeClasses = Array.from(codeEl.classList).filter(c => c.startsWith("language-"));
                        oldCodeClasses.forEach(c => codeEl.classList.remove(c));
                        codeEl.classList.add(`language-${lang}`);

                        // Limpiar el formateo y tokens HTML anteriores de Prism
                        codeEl.textContent = codeEl.textContent;

                        if (window.Prism) {
                          window.Prism.highlightElement(codeEl);
                        }
                      }
                      this._syncTextarea();
                    }
                  };

                  if (immediate) {
                    runUpdate();
                  } else {
                    debounceTimer = setTimeout(runUpdate, 500);
                  }
                };

                inpEl.addEventListener("input", (e) => updateLanguage(e, false));
                inpEl.addEventListener("blur", (e) => updateLanguage(e, true));
                inpEl.addEventListener("keydown", (e) => {
                  e.stopPropagation();
                  if (e.key === "Enter") {
                    e.preventDefault();
                    inpEl.blur();
                  }
                });
                inpEl.addEventListener("mousedown", (e) => {
                  e.stopPropagation();
                });
              }
            });

            if (window.Prism) {
              window.Prism.highlightAllUnder(this.editorBody);
            }
          }, 20);
        }
      },
      foreColor: { icon: "fa-font", title: "Color de Texto" },
      backColor: { icon: "fa-fill-drip", title: "Color de Resaltado" },
      undo: { icon: "fa-rotate-left", title: "Deshacer", cmd: "undo" },
      redo: { icon: "fa-rotate-right", title: "Rehacer", cmd: "redo" },
      removeFormat: { icon: "fa-eraser", title: "Limpiar Formato", cmd: "removeFormat" },
      codeView: { icon: "fa-code", title: "Ver Código HTML", action: () => this._toggleCodeView() }
    };

    const data = defs[name];
    if (!data) return null;

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = `osamu-btn osamu-btn-${name}`;
    btn.title = data.title;
    btn.innerHTML = `<i class="fa ${data.icon}"></i>`;

    if (data.cmd) {
      btn.addEventListener("click", () => {
        this._exec(data.cmd, data.value || null);
        this._updateToolbarStates();
      });
      if (data.cmd !== "formatBlock") {
        this.cmdButtons[data.cmd] = btn;
      }
    } else if (name === "foreColor" || name === "backColor") {
      this._attachColorPicker(btn, name);
    } else if (data.action) {
      btn.addEventListener("click", data.action);
    }

    return btn;
  }

  /**
   * Asocia un input[type=color] a un botón de la barra de herramientas.
   * Guarda la selección antes de abrir el picker para no perderla.
   *
   * @param {HTMLButtonElement} btn Botón contenedor.
   * @param {string} name "foreColor" o "backColor".
   */
  _attachColorPicker(btn, name) {
    btn.style.position = "relative";

    const picker = document.createElement("input");
    picker.type = "color";
    picker.className = "osamu-color-picker-input";
    Object.assign(picker.style, {
      position: "absolute",
      top: "0",
      left: "0",
      width: "100%",
      height: "100%",
      opacity: "0",
      cursor: "pointer",
      border: "none",
      padding: "0",
      margin: "0"
    });
    picker.value = name === "foreColor" ? "#1e293b" : "#fef08a";

    // Guardar selección justo antes de que el picker tome el foco
    picker.addEventListener("mousedown", () => {
      this._saveSelection();
    });

    picker.addEventListener("input", (e) => {
      this._restoreSelection();
      const cmd = name === "foreColor" ? "foreColor" : "backColor";
      this._exec(cmd, e.target.value);
    });

    btn.appendChild(picker);
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: ÁREA EDITABLE
  // -----------------------------------------------------------------------------

  /**
   * Crea el área de edición contentEditable.
   */
  _buildEditableArea() {
    this.editorBody = document.createElement("div");
    this.editorBody.className = "osamu-body";
    this.editorBody.contentEditable = "true";
    this.editorBody.setAttribute("data-placeholder", this.options.placeholder);
    this.container.appendChild(this.editorBody);
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: DIÁLOGOS MODALES
  // -----------------------------------------------------------------------------

  /**
   * Construye todos los diálogos modales internos del editor.
   * Usa IDs únicos por instancia para evitar conflictos entre múltiples editores.
   */
  _buildInlineDialogs() {
    const id = this._id;
    this.overlay = document.createElement("div");
    this.overlay.className = "osamu-overlay d-none";

    // -----------------------------------------------------------------------------
    // Diálogo: Enlace
    // -----------------------------------------------------------------------------
    this.linkDialog = document.createElement("div");
    this.linkDialog.className = "osamu-dialog d-none";
    this.linkDialog.innerHTML = `
      <div class="osamu-dialog-header">
        <span>Insertar Enlace</span>
        <button type="button" class="osamu-dialog-close" title="Cerrar">&times;</button>
      </div>
      <div class="osamu-dialog-body">
        <div class="mb-2">
          <label class="form-label small">URL del Enlace</label>
          <input type="text" class="form-control form-control-sm osamu-link-url" placeholder="https://example.com">
        </div>
        <div class="form-check">
          <input class="form-check-input osamu-link-target" type="checkbox" id="osamu-link-target-${id}" checked>
          <label class="form-check-label small" for="osamu-link-target-${id}">Abrir en nueva pestaña</label>
        </div>
      </div>
      <div class="osamu-dialog-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary osamu-cancel-dialog">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary osamu-confirm-link">Insertar</button>
      </div>
    `;

    // -----------------------------------------------------------------------------
    // Diálogo: Imagen
    // -----------------------------------------------------------------------------
    this.imageDialog = document.createElement("div");
    this.imageDialog.className = "osamu-dialog d-none";
    this.imageDialog.innerHTML = `
      <div class="osamu-dialog-header">
        <span>Insertar Imagen</span>
        <button type="button" class="osamu-dialog-close" title="Cerrar">&times;</button>
      </div>
      <div class="osamu-dialog-body">
        <ul class="nav nav-tabs mb-2" role="tablist">
          <li class="nav-item"><a class="nav-link active py-1 px-2 small osamu-tab-url" href="#" onclick="return false;">URL Externa</a></li>
          <li class="nav-item"><a class="nav-link py-1 px-2 small osamu-tab-file" href="#" onclick="return false;">Subir Local</a></li>
        </ul>
        <div class="osamu-tab-content-url">
          <label class="form-label small">Enlace de la Imagen</label>
          <input type="text" class="form-control form-control-sm osamu-img-url" placeholder="https://example.com/imagen.jpg">
        </div>
        <div class="osamu-tab-content-file d-none">
          <label class="form-label small">Seleccionar Imagen</label>
          <input type="file" class="form-control form-control-sm osamu-img-file" accept="image/*">
        </div>
      </div>
      <div class="osamu-dialog-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary osamu-cancel-dialog">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary osamu-confirm-image">Insertar</button>
      </div>
    `;

    // -----------------------------------------------------------------------------
    // Diálogo: YouTube
    // -----------------------------------------------------------------------------
    this.youtubeDialog = document.createElement("div");
    this.youtubeDialog.className = "osamu-dialog d-none";
    this.youtubeDialog.innerHTML = `
      <div class="osamu-dialog-header">
        <span>Insertar Video de YouTube</span>
        <button type="button" class="osamu-dialog-close" title="Cerrar">&times;</button>
      </div>
      <div class="osamu-dialog-body">
        <div class="mb-2">
          <label class="form-label small">Enlace del Video</label>
          <input type="text" class="form-control form-control-sm osamu-yt-url" placeholder="https://www.youtube.com/watch?v=...">
        </div>
      </div>
      <div class="osamu-dialog-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary osamu-cancel-dialog">Cancelar</button>
        <button type="button" class="btn btn-sm btn-danger osamu-confirm-youtube">Insertar</button>
      </div>
    `;



    this.overlay.appendChild(this.linkDialog);
    this.overlay.appendChild(this.imageDialog);
    this.overlay.appendChild(this.youtubeDialog);
    this.container.appendChild(this.overlay);

    this._bindDialogEvents();
  }

  /**
   * Enlaza todos los eventos de los diálogos modales.
   */
  _bindDialogEvents() {
    // Cerrar al hacer clic en Cancelar o en la X de la cabecera
    this.overlay.querySelectorAll(".osamu-cancel-dialog, .osamu-dialog-close").forEach(btn => {
      btn.addEventListener("click", () => this._hideDialog());
    });

    // Cerrar al hacer clic en el overlay (fuera del modal)
    this.overlay.addEventListener("click", (e) => {
      if (e.target === this.overlay) {
        this._hideDialog();
      }
    });

    // Cerrar al presionar la tecla Escape
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && !this.overlay.classList.contains("d-none")) {
        this._hideDialog();
      }
    });

    // Enlace
    this.linkDialog.querySelector(".osamu-confirm-link").addEventListener("click", () => {
      const url = this.linkDialog.querySelector(".osamu-link-url").value.trim();
      const newTab = this.linkDialog.querySelector(".osamu-link-target").checked;
      if (url) {
        this._restoreSelection();
        const sel = window.getSelection();
        const selectedText = sel && sel.toString() ? sel.toString() : url;
        if (newTab) {
          this._exec("insertHTML", `<a href="${url}" target="_blank" rel="noopener noreferrer">${selectedText}</a>`);
        } else {
          this._exec("createLink", url);
        }
      }
      this._hideDialog();
    });

    // Imagen: tabs
    const tabUrl = this.imageDialog.querySelector(".osamu-tab-url");
    const tabFile = this.imageDialog.querySelector(".osamu-tab-file");
    const contentUrl = this.imageDialog.querySelector(".osamu-tab-content-url");
    const contentFile = this.imageDialog.querySelector(".osamu-tab-content-file");

    tabUrl.addEventListener("click", () => {
      tabUrl.classList.add("active");
      tabFile.classList.remove("active");
      contentUrl.classList.remove("d-none");
      contentFile.classList.add("d-none");
    });

    tabFile.addEventListener("click", () => {
      tabFile.classList.add("active");
      tabUrl.classList.remove("active");
      contentFile.classList.remove("d-none");
      contentUrl.classList.add("d-none");
    });

    // Imagen: insertar
    this.imageDialog.querySelector(".osamu-confirm-image").addEventListener("click", () => {
      this._restoreSelection();
      if (tabUrl.classList.contains("active")) {
        const url = this.imageDialog.querySelector(".osamu-img-url").value.trim();
        if (url) {
          this._exec("insertImage", url);
        }
        this._hideDialog();
      } else {
        const fileInput = this.imageDialog.querySelector(".osamu-img-file");
        const file = fileInput.files[0];
        if (file) {
          this._uploadImage(file, fileInput);
        } else {
          this._hideDialog();
        }
      }
    });

    // YouTube: insertar
    this.youtubeDialog.querySelector(".osamu-confirm-youtube").addEventListener("click", () => {
      this._restoreSelection();
      const inputVal = this.youtubeDialog.querySelector(".osamu-yt-url").value.trim();
      if (inputVal) {
        const videoId = this._getYouTubeId(inputVal);
        if (videoId) {
          const html = `<p><br></p><div class="osamu-video-wrapper" contenteditable="false" style="position:relative;display:block;max-width:100%;margin:12px 0;width:560px;aspect-ratio:16/9;border-radius:8px;overflow:hidden;"><iframe src="https://www.youtube.com/embed/${videoId}" title="YouTube video player" frameborder="0" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture;web-share" allowfullscreen style="width:100%;height:100%;border:none;pointer-events:none;"></iframe><div class="osamu-video-overlay" style="position:absolute;top:0;left:0;width:100%;height:100%;cursor:pointer;background-color:rgba(0,0,0,0);z-index:1;"></div></div><p><br></p>`;
          this._exec("insertHTML", html);
        } else {
          alert("No se pudo detectar un ID de video de YouTube válido.");
        }
      }
      this._hideDialog();
    });

  }

  /**
   * Sube una imagen al servidor y la inserta en el editor.
   *
   * @param {File} file Archivo de imagen seleccionado.
   * @param {HTMLInputElement} fileInput Input file para limpiar tras la subida.
   */
  _uploadImage(file, fileInput) {
    if (this.options.uploadUrl) {
      const formData = new FormData();
      formData.append("image", file);
      const confirmBtn = this.imageDialog.querySelector(".osamu-confirm-image");
      const originalText = confirmBtn.textContent;
      confirmBtn.disabled = true;
      confirmBtn.textContent = "Subiendo...";

      fetch(this.options.uploadUrl, { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
          if (data.success && data.url) {
            this._exec("insertImage", data.url);
          } else {
            alert("Error al subir la imagen: " + (data.message || "Error desconocido"));
          }
        })
        .catch(() => {
          alert("Error de conexión al subir la imagen.");
        })
        .finally(() => {
          confirmBtn.disabled = false;
          confirmBtn.textContent = originalText;
          fileInput.value = "";
          this._hideDialog();
        });
    } else {
      const reader = new FileReader();
      reader.onload = (e) => {
        this._exec("insertImage", e.target.result);
        fileInput.value = "";
        this._hideDialog();
      };
      reader.readAsDataURL(file);
    }
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: GESTIÓN DE DIÁLOGOS
  // -----------------------------------------------------------------------------

  /**
   * Muestra un diálogo y guarda la selección actual.
   *
   * @param {string} type Tipo de diálogo: link|image|youtube|code.
   */
  _showDialog(type) {
    this._saveSelection();
    this.overlay.classList.remove("d-none");

    const dialogs = {
      link: this.linkDialog,
      image: this.imageDialog,
      youtube: this.youtubeDialog,
      code: this.codeDialog
    };

    // Mostrar solo el diálogo solicitado
    Object.keys(dialogs).forEach(key => {
      dialogs[key].classList.toggle("d-none", key !== type);
    });

    // Enfocar el primer input según el tipo
    if (type === "link") {
      const input = this.linkDialog.querySelector(".osamu-link-url");
      input.value = "";
      input.focus();
    } else if (type === "image") {
      this.imageDialog.querySelector(".osamu-img-url").value = "";
      this.imageDialog.querySelector(".osamu-img-file").value = "";
    } else if (type === "youtube") {
      const input = this.youtubeDialog.querySelector(".osamu-yt-url");
      input.value = "";
      input.focus();
    } else if (type === "code") {
      const text = this.codeDialog.querySelector(".osamu-code-text");
      text.value = "";
      text.focus();
    }
  }

  /**
   * Oculta todos los diálogos activos.
   */
  _hideDialog() {
    this.overlay.classList.add("d-none");
    [this.linkDialog, this.imageDialog, this.youtubeDialog, this.codeDialog].forEach(d => {
      d.classList.add("d-none");
    });
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: REDIMENSIONADOR
  // -----------------------------------------------------------------------------

  /**
   * Construye el redimensionador con controles para medios y para código.
   */
  _buildResizer() {
    this.resizer = document.createElement("div");
    this.resizer.className = "osamu-image-resizer";

    // Tiradores de redimensionamiento
    ["tl", "tr", "bl", "br", "t", "b", "l", "r"].forEach(h => {
      const handle = document.createElement("div");
      handle.className = `osamu-resizer-handle ${h}`;
      handle.addEventListener("mousedown", (e) => this._initResize(e, h));
      this.resizer.appendChild(handle);
    });

    // Barra de herramientas del resizer
    this.resizerToolbar = document.createElement("div");
    this.resizerToolbar.className = "osamu-resizer-toolbar";

    const mkBtn = (html, title, action) => this._createResizerBtn(html, title, action);

    // Controles universales: insertar párrafo antes o después del elemento
    this.navControls = document.createElement("div");
    this.navControls.className = "osamu-resizer-nav-controls";

    this.navControls.appendChild(mkBtn(
      `<i class="fa-solid fa-arrow-up"></i>`,
      "Insertar línea antes",
      () => this._insertParagraphBefore()
    ));
    this.navControls.appendChild(mkBtn(
      `<i class="fa-solid fa-arrow-down"></i>`,
      "Insertar línea después",
      () => this._insertParagraphAfter()
    ));

    // Separador entre controles de navegación y controles de tipo
    this.navDivider = document.createElement("div");
    this.navDivider.className = "divider";

    // Controles de medios
    this.mediaControls = document.createElement("div");
    this.mediaControls.className = "osamu-resizer-media-controls";

    this.mediaControls.appendChild(mkBtn(`<i class="fa-solid fa-align-left"></i>`, "Alinear Izquierda", () => this._alignSelected("left")));
    this.mediaControls.appendChild(mkBtn(`<i class="fa-solid fa-align-center"></i>`, "Centrar", () => this._alignSelected("center")));
    this.mediaControls.appendChild(mkBtn(`<i class="fa-solid fa-align-right"></i>`, "Alinear Derecha", () => this._alignSelected("right")));

    const divider = document.createElement("div");
    divider.className = "divider";
    this.mediaControls.appendChild(divider);

    [25, 50, 75, 100].forEach(p => {
      this.mediaControls.appendChild(mkBtn(`${p}%`, `Tamaño ${p}%`, () => this._resizeSelectedPercent(p)));
    });

    // Controles de código
    this.codeControls = document.createElement("div");
    this.codeControls.className = "osamu-resizer-code-controls";

    const langLabel = document.createElement("span");
    langLabel.className = "osamu-resizer-toolbar-label";
    langLabel.textContent = "Lenguaje:";

    this.langInput = document.createElement("input");
    this.langInput.type = "text";
    this.langInput.className = "osamu-resizer-lang-input";
    this.langInput.placeholder = "Ej: javascript, php...";

    this.langInput.addEventListener("mousedown", (e) => e.stopPropagation());
    this.langInput.addEventListener("keydown", (e) => {
      e.stopPropagation();
      if (e.key === "Enter") {
        e.preventDefault();
        this._applyCodeLanguage();
      }
    });

    const langBtn = document.createElement("button");
    langBtn.type = "button";
    langBtn.className = "osamu-resizer-lang-btn";
    langBtn.title = "Aplicar lenguaje";
    langBtn.innerHTML = `<i class="fa-solid fa-check"></i>`;
    langBtn.addEventListener("mousedown", (e) => {
      e.preventDefault();
      e.stopPropagation();
      this._applyCodeLanguage();
    });

    this.codeControls.appendChild(langLabel);
    this.codeControls.appendChild(this.langInput);
    this.codeControls.appendChild(langBtn);

    this.resizerToolbar.appendChild(this.navControls);
    this.resizerToolbar.appendChild(this.navDivider);
    this.resizerToolbar.appendChild(this.mediaControls);
    this.resizerToolbar.appendChild(this.codeControls);
    this.resizer.appendChild(this.resizerToolbar);
    this.container.appendChild(this.resizer);
  }

  /**
   * Construye el indicador de línea de inserción (estilo Word).
   * Al acercar el cursor al borde superior o inferior de un bloque no editable,
   * aparece una línea que al hacer clic inserta un párrafo en esa posición.
   */
  _buildInsertIndicator() {
    this._insertTarget = null;
    this._insertPosition = null;

    this.insertIndicator = document.createElement("div");
    this.insertIndicator.className = "osamu-insert-indicator";
    this.insertIndicator.innerHTML = `<div class="osamu-insert-indicator-line"></div>`;
    this.container.appendChild(this.insertIndicator);

    this.insertIndicator.addEventListener("mousedown", (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (this._insertTarget) {
        this._insertParagraphAtEl(this._insertTarget, this._insertPosition);
      }
      this._hideInsertIndicator();
    });
  }

  /**
   * Crea un botón para la barra de herramientas del redimensionador.
   *
   * @param {string} html Contenido HTML del botón.
   * @param {string} title Texto del tooltip.
   * @param {Function} action Función a ejecutar al hacer clic.
   * @return {HTMLButtonElement} Botón creado.
   */
  _createResizerBtn(html, title, action) {
    const btn = document.createElement("button");
    btn.type = "button";
    btn.innerHTML = html;
    btn.title = title;
    btn.addEventListener("mousedown", (e) => {
      e.preventDefault();
      e.stopPropagation();
      action();
    });
    return btn;
  }

  /**
   * Muestra el redimensionador posicionado sobre el elemento seleccionado.
   * Alterna entre controles de medios y de código según el tipo de elemento.
   *
   * @param {HTMLElement} el Elemento seleccionado (img, .osamu-video-wrapper, pre).
   */
  _showResizer(el) {
    this.selectedImage = el;
    this.resizer.style.display = "block";

    const handles = this.resizer.querySelectorAll(".osamu-resizer-handle");

    if (el.tagName === "PRE") {
      // Bloque de código: sin tiradores, solo antes/después
      handles.forEach(h => { h.style.display = "none"; });
      this.mediaControls.style.display = "none";
      this.codeControls.style.display = "none";
      this.navDivider.style.display = "none";
    } else if (el.tagName === "BLOCKQUOTE") {
      // Cita: sin tiradores ni controles de tipo, solo antes/después
      handles.forEach(h => { h.style.display = "none"; });
      this.mediaControls.style.display = "none";
      this.codeControls.style.display = "none";
      this.navDivider.style.display = "none";
    } else {
      // Imagen o video: tiradores visibles y controles de medios
      handles.forEach(h => { h.style.display = "block"; });
      this.mediaControls.style.display = "flex";
      this.codeControls.style.display = "none";
      this.navDivider.style.display = "block";
    }

    this._updateResizerPosition();
  }

  /**
   * Oculta el redimensionador y limpia la referencia al elemento seleccionado.
   */
  _hideResizer() {
    this.resizer.style.display = "none";
    this.selectedImage = null;
  }

  /**
   * Actualiza la posición y dimensiones del recuadro del redimensionador.
   */
  _updateResizerPosition() {
    if (!this.selectedImage) return;

    const elRect = this.selectedImage.getBoundingClientRect();
    const containerRect = this.container.getBoundingClientRect();

    this.resizer.style.top = `${elRect.top - containerRect.top + this.container.scrollTop}px`;
    this.resizer.style.left = `${elRect.left - containerRect.left}px`;
    this.resizer.style.width = `${elRect.width}px`;
    this.resizer.style.height = `${elRect.height}px`;

    // Determinar la alineación del toolbar según el ancho del elemento
    // El toolbar suele medir unos ~280px a ~330px de ancho con los botones.
    // Si el elemento es pequeño, lo anclamos a la esquina inferior izquierda (left: 10px, transform: none)
    // para evitar que se desborde o se oculte.
    const toolbarWidth = this.resizerToolbar.offsetWidth || 320;
    if (elRect.width < toolbarWidth + 20) {
      this.resizerToolbar.style.bottom = "10px";
      this.resizerToolbar.style.top = "auto";
      this.resizerToolbar.style.left = "10px";
      this.resizerToolbar.style.transform = "none";
    } else {
      this.resizerToolbar.style.bottom = "10px";
      this.resizerToolbar.style.top = "auto";
      this.resizerToolbar.style.left = "50%";
      this.resizerToolbar.style.transform = "translateX(-50%)";
    }
  }

  /**
   * Inicializa el arrastre para redimensionar un elemento.
   *
   * @param {MouseEvent} e Evento de mousedown en el tirador.
   * @param {string} handleName Identificador del tirador (tl, tr, bl, br, t, b, l, r).
   */
  _initResize(e, handleName) {
    e.preventDefault();
    e.stopPropagation();

    const startX = e.clientX;
    const startY = e.clientY;
    const startW = this.selectedImage.clientWidth;
    const startH = this.selectedImage.clientHeight;
    const ratio = startH > 0 ? startW / startH : 16 / 9;

    const onMove = (me) => {
      const dx = me.clientX - startX;
      const dy = me.clientY - startY;
      let w = startW;
      let h = startH;

      if (handleName === "r") { w = startW + dx; }
      else if (handleName === "l") { w = startW - dx; }
      else if (handleName === "b") { h = startH + dy; }
      else if (handleName === "t") { h = startH - dy; }
      else if (handleName === "br" || handleName === "tr") { w = startW + dx; h = w / ratio; }
      else if (handleName === "bl" || handleName === "tl") { w = startW - dx; h = w / ratio; }

      if (w < 30) w = 30;
      if (h < 30) h = 30;

      if (handleName === "r" || handleName === "l") {
        this.selectedImage.style.width = `${w}px`;
        this.selectedImage.style.removeProperty("height");
      } else if (handleName === "b" || handleName === "t") {
        this.selectedImage.style.height = `${h}px`;
        this.selectedImage.style.removeProperty("width");
      } else {
        this.selectedImage.style.width = `${w}px`;
        this.selectedImage.style.height = `${h}px`;
      }

      this._updateResizerPosition();
    };

    const onUp = () => {
      document.removeEventListener("mousemove", onMove);
      document.removeEventListener("mouseup", onUp);
      this._syncTextarea();
    };

    document.addEventListener("mousemove", onMove);
    document.addEventListener("mouseup", onUp);
  }

  /**
   * Alinea el elemento seleccionado horizontalmente.
   *
   * @param {string} align Dirección: left|center|right.
   */
  _alignSelected(align) {
    if (!this.selectedImage) return;

    this.selectedImage.style.display = "block";
    this.selectedImage.style.float = "none";

    if (align === "left") {
      this.selectedImage.style.marginLeft = "0";
      this.selectedImage.style.marginRight = "auto";
    } else if (align === "center") {
      this.selectedImage.style.marginLeft = "auto";
      this.selectedImage.style.marginRight = "auto";
    } else if (align === "right") {
      this.selectedImage.style.marginLeft = "auto";
      this.selectedImage.style.marginRight = "0";
    }

    this._syncTextarea();
    this._updateResizerPosition();
  }

  /**
   * Redimensiona el elemento seleccionado a un porcentaje del ancho del editor.
   *
   * @param {number} percent Porcentaje deseado (25, 50, 75, 100).
   */
  _resizeSelectedPercent(percent) {
    if (!this.selectedImage) return;

    this.selectedImage.style.width = `${percent}%`;
    this.selectedImage.style.removeProperty("height");

    if (this.selectedImage.classList.contains("osamu-video-wrapper")) {
      this.selectedImage.style.aspectRatio = "16/9";
    }

    this._syncTextarea();
    setTimeout(() => this._updateResizerPosition(), 50);
  }

  /**
   * Obtiene el lenguaje actual de un bloque de código.
   *
   * @param {HTMLElement} el Elemento pre.
   * @return {string} Nombre del lenguaje sin el prefijo "language-".
   */
  _getElementLanguage(el) {
    const cls = Array.from(el.classList).find(c => c.startsWith("language-"));
    return cls ? cls.replace("language-", "") : "";
  }

  /**
   * Aplica el lenguaje escrito en el input al bloque de código seleccionado
   * y re-renderiza el resaltado sintáctico de Prism.js.
   */
  _applyCodeLanguage() {
    if (!this.selectedImage || this.selectedImage.tagName !== "PRE") return;

    const lang = this.langInput.value.trim().toLowerCase();
    if (!lang) return;

    const el = this.selectedImage;
    const oldClasses = Array.from(el.classList).filter(c => c.startsWith("language-"));
    oldClasses.forEach(c => el.classList.remove(c));
    el.classList.add(`language-${lang}`);

    const codeEl = el.querySelector("code");
    if (codeEl) {
      const oldCodeClasses = Array.from(codeEl.classList).filter(c => c.startsWith("language-"));
      oldCodeClasses.forEach(c => codeEl.classList.remove(c));
      codeEl.classList.add(`language-${lang}`);

      // Resetear el HTML de Prism y re-renderizar
      codeEl.textContent = codeEl.textContent;
      if (window.Prism) {
        window.Prism.highlightElement(codeEl);
      }
    }

    this._syncTextarea();
    this._updateResizerPosition();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: EVENTOS
  // -----------------------------------------------------------------------------

  /**
   * Enlaza todos los listeners de eventos del editor.
   */
  _bindEvents() {
    // Sincronización en tiempo real con debounce
    this.editorBody.addEventListener("input", (e) => {
      this._ensureValidContent();
      this._syncTextareaDebounced();
      this._updatePlaceholder();

      // Coloreado en tiempo real para bloques de código pre
      if (window.Prism) {
        const pre = e.target.closest("pre");
        if (pre) {
          const codeEl = pre.querySelector("code");
          if (codeEl) {
            // Guardar posición del caret antes de re-colorear
            const sel = window.getSelection();
            let savedOffset = 0;
            if (sel && sel.rangeCount > 0) {
              const range = sel.getRangeAt(0);
              const preRange = document.createRange();
              preRange.selectNodeContents(codeEl);
              try {
                preRange.setEnd(range.startContainer, range.startOffset);
                savedOffset = preRange.toString().length;
              } catch (err) {
                savedOffset = codeEl.textContent.length;
              }
            }

            // Aplicar highlight
            window.Prism.highlightElement(codeEl);

            // Restaurar posición del caret de forma precisa
            if (sel && savedOffset >= 0) {
              const newRange = document.createRange();
              let charCount = 0;
              let nodeStack = [codeEl];
              let found = false;

              while (nodeStack.length > 0 && !found) {
                const node = nodeStack.pop();
                if (node.nodeType === Node.TEXT_NODE) {
                  const nextCharCount = charCount + node.length;
                  if (savedOffset <= nextCharCount) {
                    newRange.setStart(node, savedOffset - charCount);
                    newRange.collapse(true);
                    found = true;
                  }
                  charCount = nextCharCount;
                } else if (node.nodeName === "BR") {
                  if (savedOffset === charCount) {
                    newRange.setStartBefore(node);
                    newRange.collapse(true);
                    found = true;
                  }
                  charCount += 1; // Un BR cuenta como un caracter de salto de línea
                } else {
                  let i = node.childNodes.length;
                  while (i--) {
                    nodeStack.push(node.childNodes[i]);
                  }
                }
              }

              if (found) {
                sel.removeAllRanges();
                sel.addRange(newRange);
              } else {
                // Fallback al final si no se encontró coincidencia exacta
                try {
                  newRange.selectNodeContents(codeEl);
                  newRange.collapse(false);
                  sel.removeAllRanges();
                  sel.addRange(newRange);
                } catch (err) { }
              }
            }
          }
        }
      }
    });

    // Actualizar estados de toolbar en keyup sin re-serializar el DOM
    this.editorBody.addEventListener("keyup", () => {
      this._ensureValidContent();
      this._updatePlaceholder();
      this._updateToolbarStates();
    });

    this.editorBody.addEventListener("mouseup", () => {
      this._updateToolbarStates();
    });

    // Sincronizar al perder el foco (flush del debounce)
    this.editorBody.addEventListener("blur", () => {
      this._cancelSyncDebounce();
      this._syncTextarea();
      this._updatePlaceholder();
    });

    this.editorBody.addEventListener("focus", () => {
      this.editorBody.classList.remove("empty");
      try {
        document.execCommand("defaultParagraphSeparator", false, "p");
      } catch (e) {
        // Ignorar: comando obsoleto pero aún necesario en algunos navegadores
      }
      this._updateToolbarStates();
    });

    // Teclas especiales
    this.editorBody.addEventListener("keydown", (e) => {
      this._handleKeydown(e);
    });

    // Impedir que el toolbar robe el foco del editor
    this.toolbar.addEventListener("mousedown", (e) => {
      if (e.target.tagName !== "SELECT" && e.target.tagName !== "OPTION") {
        e.preventDefault();
      }
    });

    // Redimensionador: detectar clic en imagen, video, bloque de código o cita
    this.editorBody.addEventListener("click", (e) => {
      const img = e.target.closest("img");
      const video = e.target.closest(".osamu-video-wrapper");
      const pre = e.target.closest("pre");
      const blockquote = e.target.closest("blockquote");

      if (img) {
        this._showResizer(img);
      } else if (video) {
        this._showResizer(video);
      } else if (pre) {
        this._showResizer(pre);
      } else if (blockquote) {
        this._showResizer(blockquote);
      } else if (!e.target.closest(".osamu-image-resizer")) {
        this._hideResizer();
      }
    });

    // Actualizar posición del resizer al scrollear dentro del editor
    this.editorBody.addEventListener("scroll", () => {
      if (this.selectedImage) {
        this._updateResizerPosition();
      }
    });

    // Indicador de inserción estilo Word: detectar proximidad a bordes de bloques
    this.editorBody.addEventListener("mousemove", (e) => {
      this._handleInsertIndicator(e);
    });

    this.editorBody.addEventListener("mouseleave", () => {
      this._hideInsertIndicator();
    });

    // Listener de resize de ventana guardado como referencia para poder eliminarlo
    this._resizeHandler = () => {
      if (this.selectedImage) {
        this._updateResizerPosition();
      }
    };
    window.addEventListener("resize", this._resizeHandler);
  }

  /**
   * Detecta si el cursor está cerca del borde superior o inferior de un bloque
   * y muestra u oculta el indicador de inserción.
   *
   * @param {MouseEvent} e Evento mousemove.
   */
  _handleInsertIndicator(e) {
    // Si el resizer está activo no mostrar el indicador para no saturar la UI
    if (this.selectedImage) {
      this._hideInsertIndicator();
      return;
    }

    const THRESHOLD = 14; // píxeles desde el borde del bloque
    const BLOCK_SELECTORS = ["img", ".osamu-video-wrapper", "pre", "blockquote"];

    let blockEl = null;
    for (const selector of BLOCK_SELECTORS) {
      const found = e.target.closest(selector);
      if (found && this.editorBody.contains(found)) {
        blockEl = found;
        break;
      }
    }

    if (!blockEl) {
      // No ocultar si el cursor está directamente encima del propio indicador
      if (e.target.closest(".osamu-insert-indicator")) {
        return;
      }
      this._hideInsertIndicator();
      return;
    }

    const rect = blockEl.getBoundingClientRect();
    const containerRect = this.container.getBoundingClientRect();
    const mouseY = e.clientY;

    const distTop = mouseY - rect.top;
    const distBottom = rect.bottom - mouseY;

    // Ignorar bloques muy pequeños donde el umbral solaparía
    const blockHeight = rect.height;
    if (blockHeight < THRESHOLD * 2) {
      if (e.target.closest(".osamu-insert-indicator")) {
        return;
      }
      this._hideInsertIndicator();
      return;
    }

    if (distTop <= THRESHOLD) {
      const yPos = rect.top - containerRect.top + this.editorBody.scrollTop;
      this._showInsertIndicator(blockEl, "before", yPos);
    } else if (distBottom <= THRESHOLD) {
      const yPos = rect.bottom - containerRect.top + this.editorBody.scrollTop;
      this._showInsertIndicator(blockEl, "after", yPos);
    } else {
      if (e.target.closest(".osamu-insert-indicator")) {
        return;
      }
      this._hideInsertIndicator();
    }
  }

  /**
   * Posiciona y muestra el indicador de línea de inserción.
   *
   * @param {HTMLElement} el Elemento bloque de referencia.
   * @param {string} position "before" o "after".
   * @param {number} yPos Posición vertical en píxeles dentro del contenedor.
   */
  _showInsertIndicator(el, position, yPos) {
    this._insertTarget = el;
    this._insertPosition = position;

    this.insertIndicator.style.display = "flex";
    this.insertIndicator.style.top = `${yPos}px`;
  }

  /**
   * Oculta el indicador de línea de inserción y limpia el estado.
   */
  _hideInsertIndicator() {
    this.insertIndicator.style.display = "none";
    this._insertTarget = null;
    this._insertPosition = null;
  }

  /**
   * Inserta un párrafo vacío antes o después de un elemento dado y mueve el cursor ahí.
   *
   * @param {HTMLElement} el Elemento de referencia.
   * @param {string} position "before" o "after".
   */
  _insertParagraphAtEl(el, position) {
    const p = document.createElement("p");
    p.innerHTML = "<br>";

    if (position === "before") {
      el.parentNode.insertBefore(p, el);
    } else {
      el.parentNode.insertBefore(p, el.nextSibling);
    }

    const range = document.createRange();
    range.setStart(p, 0);
    range.collapse(true);
    const sel = window.getSelection();
    if (sel) {
      sel.removeAllRanges();
      sel.addRange(range);
    }

    this.editorBody.focus();
    this._syncTextarea();
  }

  /**
   * Inserta un párrafo vacío antes del elemento seleccionado en el resizer.
   */
  _insertParagraphBefore() {
    if (!this.selectedImage) return;
    this._insertParagraphAtEl(this.selectedImage, "before");
    this._hideResizer();
  }

  /**
   * Inserta un párrafo vacío después del elemento seleccionado en el resizer.
   */
  _insertParagraphAfter() {
    if (!this.selectedImage) return;
    this._insertParagraphAtEl(this.selectedImage, "after");
    this._hideResizer();
  }

  /**
   * Gestiona las teclas especiales dentro del editor.
   *
   * @param {KeyboardEvent} e Evento de teclado.
   */
  _handleKeydown(e) {
    if (e.key !== "Enter") return;

    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;

    const range = sel.getRangeAt(0);
    const node = range.startContainer;

    // Salir de blockquote con Enter en línea vacía
    const blockquote = node.nodeType === Node.TEXT_NODE
      ? (node.parentNode ? node.parentNode.closest("blockquote") : null)
      : (node.closest ? node.closest("blockquote") : null);

    if (blockquote) {
      // Si el blockquote está completamente vacío, o si estamos en una línea vacía al final de la cita.
      // Un truco robusto en contentEditable es verificar si el cursor está inmediatamente después de un <br> y no hay más texto.
      const anchorNode = sel.anchorNode;
      const offset = sel.anchorOffset;

      // Buscar si hay texto antes o después del cursor en la línea actual.
      // El navegador coloca <br> para las líneas vacías.
      let isLineEmpty = false;

      // Si el blockquote está completamente vacío (solo tiene un <br> para poder escribir)
      if (blockquote.textContent.trim() === "") {
        isLineEmpty = true;
      } else {
        // Obtenemos el texto anterior a la posición del cursor en el nodo de texto actual
        let textBefore = "";
        if (anchorNode.nodeType === Node.TEXT_NODE) {
          textBefore = anchorNode.textContent.substring(0, offset).trim();
        }

        // Si no hay texto en el nodo actual antes del cursor, verificamos si venimos de un salto de línea (<br>)
        if (textBefore === "") {
          // Si el nodo anterior es un BR y no hay más texto, o si el cursor está al final y la última línea está vacía.
          const rangeClone = range.cloneRange();
          rangeClone.selectNodeContents(blockquote);
          rangeClone.setStart(range.startContainer, range.startOffset);
          const textAfter = rangeClone.toString().trim();

          if (textAfter === "") {
            isLineEmpty = true;
          }
        }
      }

      if (isLineEmpty) {
        // Doble Enter: Salir de la cita y crear un párrafo normal abajo
        e.preventDefault();

        // Limpiar saltos de línea huérfanos del final de la cita
        const lastChild = blockquote.lastChild;
        if (lastChild && lastChild.nodeName === "BR") {
          lastChild.remove();
        }
        const secondLastChild = blockquote.lastChild;
        if (secondLastChild && secondLastChild.nodeName === "BR") {
          secondLastChild.remove();
        }

        const p = document.createElement("p");
        p.innerHTML = "<br>";
        blockquote.parentNode.insertBefore(p, blockquote.nextSibling);

        const newRange = document.createRange();
        newRange.setStart(p, 0);
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);

        this._syncTextarea();
      } else {
        // Un solo Enter: dejamos que el navegador inserte el salto de línea normal,
        // pero interceptamos para forzar que sea un <br> y no cree un nuevo blockquote anidado.
        e.preventDefault();
        const br = document.createElement("br");
        range.deleteContents();
        range.insertNode(br);

        const newRange = document.createRange();
        newRange.setStartAfter(br);
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);

        // Insertar un BR extra al final del blockquote si es necesario para que el cursor pueda posicionarse visualmente.
        // Esto solo se añade si el cursor queda al final absoluto del blockquote sin elementos después.
        let next = br.nextSibling;
        let hasContentAfter = false;
        while (next) {
          if (next.textContent.trim() !== "" || next.nodeName === "BR") {
            hasContentAfter = true;
            break;
          }
          next = next.nextSibling;
        }
        if (!hasContentAfter) {
          const extraBr = document.createElement("br");
          blockquote.appendChild(extraBr);
        }

        this._syncTextarea();
      }
      return;
    }

    // Soporte para cuadros de código (<pre>) con Enter y Doble Enter
    const pre = node.nodeType === Node.TEXT_NODE
      ? (node.parentNode ? node.parentNode.closest("pre") : null)
      : (node.closest ? node.closest("pre") : null);

    if (pre) {
      const anchorNode = sel.anchorNode;
      const offset = sel.anchorOffset;
      let isLineEmpty = false;

      if (pre.textContent.trim() === "") {
        isLineEmpty = true;
      } else {
        let textBefore = "";
        if (anchorNode.nodeType === Node.TEXT_NODE) {
          textBefore = anchorNode.textContent.substring(0, offset).trim();
        }

        if (textBefore === "") {
          const rangeClone = range.cloneRange();
          rangeClone.selectNodeContents(pre);
          rangeClone.setStart(range.startContainer, range.startOffset);
          const textAfter = rangeClone.toString().trim();

          if (textAfter === "") {
            isLineEmpty = true;
          }
        }
      }

      if (isLineEmpty) {
        // Doble Enter: Salir del cuadro de código y crear un párrafo normal abajo
        e.preventDefault();

        // Limpiar saltos de línea al final del cuadro de código
        const codeEl = pre.querySelector("code") || pre;
        const lastChild = codeEl.lastChild;
        if (lastChild && lastChild.nodeName === "BR") {
          lastChild.remove();
        }
        const secondLastChild = codeEl.lastChild;
        if (secondLastChild && secondLastChild.nodeName === "BR") {
          secondLastChild.remove();
        }

        const p = document.createElement("p");
        p.innerHTML = "<br>";
        pre.parentNode.insertBefore(p, pre.nextSibling);

        const newRange = document.createRange();
        newRange.setStart(p, 0);
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);

        this._hideResizer();
        this._syncTextarea();
      } else {
        // Un solo Enter: insertar un salto de línea limpio (<br>) sin cambiar el formato ni el color
        e.preventDefault();
        const br = document.createElement("br");
        range.deleteContents();
        range.insertNode(br);

        const newRange = document.createRange();
        newRange.setStartAfter(br);
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);

        // Asegurar que el cursor pueda posicionarse si está al final absoluto
        const codeEl = pre.querySelector("code") || pre;
        let next = br.nextSibling;
        let hasContentAfter = false;
        while (next) {
          if (next.textContent.trim() !== "" || next.nodeName === "BR") {
            hasContentAfter = true;
            break;
          }
          next = next.nextSibling;
        }
        if (!hasContentAfter) {
          const extraBr = document.createElement("br");
          codeEl.appendChild(extraBr);
        }

        this._syncTextarea();
      }
    }
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: EJECUCIÓN DE COMANDOS
  // -----------------------------------------------------------------------------

  /**
   * Ejecuta un comando HTML5 sobre el área editable.
   *
   * @param {string} cmd Nombre del comando (bold, italic, insertHTML, etc.).
   * @param {string|null} value Valor adicional para el comando, si aplica.
   */
  _exec(cmd, value = null) {
    if (this.isCodeActive) return;
    this.editorBody.focus();

    if (cmd === "removeFormat") {
      const sel = window.getSelection();
      if (sel && sel.rangeCount > 0) {
        const range = sel.getRangeAt(0);

        // 1) Ejecutar el removeFormat nativo primero
        document.execCommand(cmd, false, value);

        // 2) Limpieza profunda personalizada en los elementos contenidos en la selección
        const container = range.commonAncestorContainer;
        const parent = container.nodeType === Node.TEXT_NODE ? container.parentNode : container;

        if (this.editorBody.contains(parent)) {
          // Si el elemento padre mismo está seleccionado o tiene estilos
          const cleanEl = (el) => {
            if (!this.editorBody.contains(el)) return;
            // Eliminar estilos inline, incluyendo colores, fuentes, alineaciones
            el.removeAttribute("style");
            // Eliminar tags obsoletos o de formato inline que a veces persisten
            if (["FONT", "SPAN", "U", "STRIKE", "S", "B", "I", "STRONG", "EM"].includes(el.tagName)) {
              if (el.tagName === "SPAN" || el.tagName === "FONT") {
                const p = el.parentNode;
                if (p) {
                  while (el.firstChild) p.insertBefore(el.firstChild, el);
                  p.removeChild(el);
                }
              }
            }
          };

          // Limpiar hijos
          parent.querySelectorAll("*").forEach(el => {
            if (sel.containsNode(el, true)) {
              cleanEl(el);
            }
          });

          // Limpiar listas y convertirlas a párrafos sin perder contenido
          parent.querySelectorAll("ul, ol").forEach(list => {
            if (sel.containsNode(list, true)) {
              const p = list.parentNode;
              if (p) {
                const fragment = document.createDocumentFragment();
                list.querySelectorAll("li").forEach(li => {
                  const para = document.createElement("p");
                  while (li.firstChild) para.appendChild(li.firstChild);
                  fragment.appendChild(para);
                });
                p.replaceChild(fragment, list);
              }
            }
          });

          // Limpiar el ancestro directo si es aplicable
          if (parent !== this.editorBody && sel.containsNode(parent, true)) {
            cleanEl(parent);
          }
        }
      }
    } else {
      document.execCommand(cmd, false, value);
    }

    this._syncTextarea();
    this._updateToolbarStates();
  }



  /**
   * Actualiza el estado visual activo/inactivo de los botones de la toolbar.
   */
  _updateToolbarStates() {
    if (this.isCodeActive) return;

    for (const cmd in this.cmdButtons) {
      const btn = this.cmdButtons[cmd];
      try {
        btn.classList.toggle("active", document.queryCommandState(cmd));
      } catch (e) {
        // Ignorar comandos no soportados
      }
    }

    try {
      const blockVal = document.queryCommandValue("formatBlock");
      if (blockVal) {
        const select = this.toolbar.querySelector(".osamu-select");
        if (select) {
          const upper = blockVal.toUpperCase();
          if (Array.from(select.options).some(o => o.value === upper)) {
            select.value = upper;
          }
        }
      }
    } catch (e) {
      // Ignorar
    }
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: VISTA DE CÓDIGO (HTML)
  // -----------------------------------------------------------------------------

  /**
   * Alterna entre el modo visual y el modo de edición de código fuente HTML.
   */
  _toggleCodeView() {
    this._hideResizer();
    const btn = this.toolbar.querySelector(".osamu-btn-codeView");

    if (this.isCodeActive) {
      this.editorBody.innerHTML = this._convertVideosToWrappers(this.textarea.value);
      this.editorBody.style.display = "block";
      if (this.htmlEditor) {
        this.htmlEditor.remove();
        this.htmlEditor = null;
      }
      this.isCodeActive = false;
      btn.classList.remove("active");
      this._enableToolbarButtons(true);
    } else {
      this._syncTextarea();
      this.editorBody.style.display = "none";

      this.htmlEditor = document.createElement("textarea");
      this.htmlEditor.className = "osamu-code-editor";
      this.htmlEditor.value = this.textarea.value;
      this.container.appendChild(this.htmlEditor);

      this.htmlEditor.addEventListener("input", (e) => {
        this.textarea.value = e.target.value;
      });

      this.isCodeActive = true;
      btn.classList.add("active");
      this._enableToolbarButtons(false);
    }
  }

  /**
   * Habilita o deshabilita todos los botones de la toolbar excepto el de vista de código.
   *
   * @param {boolean} enable true para habilitar, false para deshabilitar.
   */
  _enableToolbarButtons(enable) {
    this.toolbar.querySelectorAll(".osamu-btn").forEach(btn => {
      if (!btn.classList.contains("osamu-btn-codeView")) {
        btn.disabled = !enable;
      }
    });
    const select = this.toolbar.querySelector(".osamu-select");
    if (select) select.disabled = !enable;
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: SINCRONIZACIÓN Y UTILIDADES INTERNAS
  // -----------------------------------------------------------------------------

  /**
   * Asegura que el área editable siempre tenga al menos un párrafo válido.
   */
  _ensureValidContent() {
    const html = this.editorBody.innerHTML.trim();
    if (!html || html === "<br>") {
      this.editorBody.innerHTML = "<p><br></p>";
    }
  }

  /**
   * Llama a syncTextarea con debounce de 150ms para evitar serialización excesiva.
   */
  _syncTextareaDebounced() {
    clearTimeout(this._syncTimer);
    this._syncTimer = setTimeout(() => {
      this._syncTextarea();
    }, 150);
  }

  /**
   * Cancela el debounce pendiente de sincronización.
   */
  _cancelSyncDebounce() {
    clearTimeout(this._syncTimer);
  }

  /**
   * Serializa el contenido del editor al textarea original.
   * Convierte los wrappers de video de vuelta a su formato final (iframe o lite-youtube).
   */
  _syncTextarea() {
    if (this.isCodeActive) return;

    // Crear clon para no afectar el DOM interactivo en pantalla durante la limpieza
    const clone = this.editorBody.cloneNode(true);

    // Remover selectores y leyendas temporales del cuadro de código
    clone.querySelectorAll(".osamu-code-lang-selector").forEach(el => el.remove());

    let content = clone.innerHTML;
    if (content === "<p><br></p>" || content === "<br>") {
      content = "";
    }

    if (content) {
      const temp = document.createElement("div");
      temp.innerHTML = content;
      let changed = false;

      temp.querySelectorAll(".osamu-video-wrapper").forEach(wrapper => {
        const iframe = wrapper.querySelector("iframe");
        if (!iframe) return;

        const src = iframe.getAttribute("src") || "";
        const videoId = this._getYouTubeId(src);
        if (!videoId) return;

        let el;
        if (this.options.liteYouTube) {
          el = document.createElement("lite-youtube");
          el.setAttribute("videoid", videoId);
        } else {
          el = document.createElement("iframe");
          el.setAttribute("src", `https://www.youtube.com/embed/${videoId}`);
          el.setAttribute("title", "YouTube video player");
          el.setAttribute("frameborder", "0");
          el.setAttribute("allow", "accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture;web-share");
          el.setAttribute("allowfullscreen", "");
          el.setAttribute("width", "560");
          el.setAttribute("height", "315");
        }

        // Copiar estilos de layout del wrapper
        ["width", "height", "display", "float", "marginLeft", "marginRight", "aspectRatio"].forEach(prop => {
          if (wrapper.style[prop]) el.style[prop] = wrapper.style[prop];
        });

        el.style.maxWidth = "100%";
        el.style.borderRadius = "8px";
        if (!wrapper.style.marginTop && !wrapper.style.marginBottom) {
          el.style.marginTop = "12px";
          el.style.marginBottom = "12px";
        }

        wrapper.parentNode.replaceChild(el, wrapper);
        changed = true;
      });

      if (changed) content = temp.innerHTML;
    }

    this.textarea.value = content;
  }

  /**
   * Convierte elementos lite-youtube e iframe de YouTube al wrapper de edición interactiva.
   *
   * @param {string} html HTML a procesar.
   * @return {string} HTML con los wrappers de edición.
   */
  _convertVideosToWrappers(html) {
    if (!html) return html;

    const temp = document.createElement("div");
    temp.innerHTML = html;
    let changed = false;

    // Convertir lite-youtube
    temp.querySelectorAll("lite-youtube").forEach(lite => {
      const id = lite.getAttribute("videoid");
      if (id) {
        const w = this._createVideoWrapper(id, lite.style);
        lite.parentNode.replaceChild(w, lite);
        changed = true;
      }
    });

    // Convertir iframes
    temp.querySelectorAll("iframe").forEach(iframe => {
      const src = iframe.getAttribute("src") || "";
      const id = this._getYouTubeId(src);
      if (id && !iframe.closest(".osamu-video-wrapper")) {
        const w = this._createVideoWrapper(id, iframe.style);
        iframe.parentNode.replaceChild(w, iframe);
        changed = true;
      }
    });

    // Garantizar párrafos antes y después de cada wrapper
    temp.querySelectorAll(".osamu-video-wrapper").forEach(wrapper => {
      const next = wrapper.nextSibling;
      if (!next || (next.nodeType === Node.ELEMENT_NODE && next.tagName !== "P" && next.tagName !== "DIV")) {
        const p = document.createElement("p");
        p.innerHTML = "<br>";
        wrapper.parentNode.insertBefore(p, wrapper.nextSibling);
        changed = true;
      }
      const prev = wrapper.previousSibling;
      if (!prev || (prev.nodeType === Node.ELEMENT_NODE && prev.tagName !== "P" && prev.tagName !== "DIV")) {
        const p = document.createElement("p");
        p.innerHTML = "<br>";
        wrapper.parentNode.insertBefore(p, wrapper);
        changed = true;
      }
    });

    return changed ? temp.innerHTML : html;
  }

  /**
   * Crea el wrapper interactivo de video con overlay para capturar eventos.
   *
   * @param {string} videoId ID del video de YouTube.
   * @param {CSSStyleDeclaration} style Estilos a heredar del elemento original.
   * @return {HTMLDivElement} Elemento wrapper listo para insertar en el editor.
   */
  _createVideoWrapper(videoId, style) {
    const wrapper = document.createElement("div");
    wrapper.className = "osamu-video-wrapper";
    wrapper.setAttribute("contenteditable", "false");
    Object.assign(wrapper.style, {
      position: "relative",
      maxWidth: "100%",
      borderRadius: "8px",
      overflow: "hidden",
      display: style.display || "block",
      width: style.width || "560px",
      aspectRatio: style.height ? null : (style.aspectRatio || "16/9")
    });

    if (style.height) wrapper.style.height = style.height;
    if (style.float) wrapper.style.float = style.float;
    if (style.marginLeft) wrapper.style.marginLeft = style.marginLeft;
    if (style.marginRight) wrapper.style.marginRight = style.marginRight;

    if (!style.marginLeft && !style.marginRight && !style.float) {
      wrapper.style.marginTop = "12px";
      wrapper.style.marginBottom = "12px";
    }

    const iframe = document.createElement("iframe");
    iframe.setAttribute("src", `https://www.youtube.com/embed/${videoId}`);
    iframe.setAttribute("title", "YouTube video player");
    iframe.setAttribute("frameborder", "0");
    iframe.setAttribute("allow", "accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture;web-share");
    iframe.setAttribute("allowfullscreen", "");
    Object.assign(iframe.style, { width: "100%", height: "100%", border: "none", pointerEvents: "none" });

    const overlay = document.createElement("div");
    overlay.className = "osamu-video-overlay";
    Object.assign(overlay.style, {
      position: "absolute", top: "0", left: "0",
      width: "100%", height: "100%",
      cursor: "pointer", zIndex: "1",
      backgroundColor: "rgba(0,0,0,0)"
    });

    wrapper.appendChild(iframe);
    wrapper.appendChild(overlay);
    return wrapper;
  }

  /**
   * Mueve el cursor al nodo que sigue al bloque actual para insertar contenido fuera de él.
   * Evita que bloques de código queden anidados dentro de párrafos o citas.
   */
  _exitCurrentBlock() {
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;

    const range = sel.getRangeAt(0);
    let node = range.startContainer;

    // Buscar el bloque de nivel superior dentro del editor
    while (node && node !== this.editorBody) {
      const parent = node.parentNode;
      if (parent === this.editorBody) break;
      node = parent;
    }

    if (node && node !== this.editorBody) {
      // Posicionar el cursor después del bloque actual
      const newRange = document.createRange();
      newRange.setStartAfter(node);
      newRange.collapse(true);
      sel.removeAllRanges();
      sel.addRange(newRange);
    }
  }

  /**
   * Actualiza la visibilidad del placeholder según el contenido del editor.
   */
  _updatePlaceholder() {
    const hasContent = this.editorBody.textContent.trim() ||
      this.editorBody.querySelector("img, iframe, .osamu-video-wrapper");
    this.editorBody.classList.toggle("empty", !hasContent);
  }

  /**
   * Guarda la selección actual del cursor para restaurarla después de abrir diálogos.
   */
  _saveSelection() {
    const sel = window.getSelection();
    if (sel && sel.getRangeAt && sel.rangeCount) {
      this.savedRange = sel.getRangeAt(0).cloneRange();
    } else {
      this.savedRange = null;
    }
  }

  /**
   * Restaura la selección guardada previamente.
   */
  _restoreSelection() {
    if (!this.savedRange) return;
    const sel = window.getSelection();
    if (sel) {
      sel.removeAllRanges();
      sel.addRange(this.savedRange);
    }
  }

  /**
   * Extrae el ID de un video de YouTube desde una URL o código embebido.
   *
   * @param {string} url URL o código iframe de YouTube.
   * @return {string|null} ID del video de 11 caracteres, o null si no se detecta.
   */
  _getYouTubeId(url) {
    const regex = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regex);
    return (match && match[2].length === 11) ? match[2] : null;
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: API PÚBLICA
  // -----------------------------------------------------------------------------

  /**
   * Obtiene el contenido HTML actual del editor.
   *
   * @return {string} Contenido HTML limpio (el mismo que se envía por POST).
   */
  getValue() {
    this._syncTextarea();
    return this.textarea.value;
  }

  /**
   * Establece el contenido HTML del editor de forma programática.
   *
   * @param {string} html HTML a cargar en el editor.
   */
  setValue(html) {
    this.editorBody.innerHTML = this._convertVideosToWrappers(html || "") || "<p><br></p>";
    this.textarea.value = html || "";
    this._updatePlaceholder();

    if (window.Prism) {
      setTimeout(() => {
        window.Prism.highlightAllUnder(this.editorBody);
      }, 10);
    }
  }

  /**
   * Destruye la instancia del editor, limpia listeners globales y restaura el textarea original.
   */
  destroy() {
    // Limpiar listener global de resize
    if (this._resizeHandler) {
      window.removeEventListener("resize", this._resizeHandler);
      this._resizeHandler = null;
    }

    // Cancelar debounce pendiente
    this._cancelSyncDebounce();

    // Restaurar el textarea original
    this.textarea.style.display = "";

    // Eliminar el contenedor del editor del DOM
    if (this.container && this.container.parentNode) {
      this.container.parentNode.removeChild(this.container);
    }
  }
}
