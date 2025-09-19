class PirulugDropzone {
  constructor(input) {
    this.input = input;
    this.multiple = input.getAttribute("data-pdz-multiple") === "true";
    this.maxFiles = parseInt(input.getAttribute("data-pdz-max")) || 5;
    this.thumbWidth =
      parseInt(input.getAttribute("data-pdz-thumb-width")) || 50;
    this.thumbHeight =
      parseInt(input.getAttribute("data-pdz-thumb-height")) || 50;

    this.defaultSrc = input.getAttribute("data-pdz-default") || "";
    this.width = input.getAttribute("data-pdz-width") || 200;
    this.height = input.getAttribute("data-pdz-height") || 160;

    // 👇 Primero intentamos con el atributo nativo accept
    const rawAccept =
      input.getAttribute("accept") ||
      input.getAttribute("data-pdz-accept") ||
      "";

    this.accept = rawAccept
      ? rawAccept
          .split(",")
          .map(
            (s) => s.trim().replace(/^\./, "").toLowerCase() // quita el punto inicial si existe
          )
          .filter(Boolean)
      : [];

    this.buildUI();

    if (this.defaultSrc) {
      if (!this.multiple) {
        this.preview.src = this.defaultSrc;
        this.preview.style.display = "block";
        this.preview.style.width = this.width + "px";
        this.preview.style.height = this.height + "px";
        // 👇 dejamos visibles uploadIcon y uploadText
        this.uploadIcon.style.display = "block";
        this.uploadText.style.display = "block";
      }
    }

    this.bindEvents();
  }

  static icons = {
    upload: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7 9H5l3-3 3 3H9v5H7V9zm5-4c0-.44-.91-3-4.5-3C5.08 2 3 3.92 3 6 1.02 6 0 7.52 0 9c0 1.53 1 3 3 3h3v-1.3H3c-1.62 0-1.7-1.42-1.7-1.7 0-.17.05-1.7 1.7-1.7h1.3V6c0-1.39 1.56-2.7 3.2-2.7 2.55 0 3.13 1.55 3.2 1.8v1.2H12c.81 0 2.7.22 2.7 2.2 0 2.09-2.25 2.2-2.7 2.2h-2V12h2c2.08 0 4-1.16 4-3.5C16 6.06 14.08 5 12 5z"/></svg>`,
    pdf: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#ffebee}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#f44336"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#ff8a80"/><path d="M7.89 19.13a.45.45 0 0 1-.51-.51v-2.93a.45.45 0 0 1 .5-.51.45.45 0 0 1 .5.43.78.78 0 0 1 .35-.32 1.07 1.07 0 0 1 .51-.12 1.17 1.17 0 0 1 .64.18 1.2 1.2 0 0 1 .43.51 2 2 0 0 1 0 1.57 1.2 1.2 0 0 1-1.56.57.86.86 0 0 1-.35-.3v.91a.5.5 0 0 1-.13.38.52.52 0 0 1-.38.14Zm1-1.76a.48.48 0 0 0 .38-.18.81.81 0 0 0 .14-.55.82.82 0 0 0-.14-.55.5.5 0 0 0-.38-.17.51.51 0 0 0-.39.17.89.89 0 0 0-.14.55.87.87 0 0 0 .14.55.48.48 0 0 0 .42.18ZM12.17 18.11a1.1 1.1 0 0 1-.63-.17 1.22 1.22 0 0 1-.44-.51 2 2 0 0 1 0-1.57 1.22 1.22 0 0 1 .44-.51 1.11 1.11 0 0 1 .63-.18 1.06 1.06 0 0 1 .5.12.91.91 0 0 1 .35.28v-1.09a.45.45 0 0 1 .51-.51.49.49 0 0 1 .37.13.5.5 0 0 1 .13.38v3.11a.5.5 0 0 1-1 .08.76.76 0 0 1-.34.32 1.14 1.14 0 0 1-.52.12Zm.33-.74a.48.48 0 0 0 .38-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.5.5 0 0 0-.38-.17.49.49 0 0 0-.38.17.82.82 0 0 0-.15.55.8.8 0 0 0 .15.55.46.46 0 0 0 .38.18ZM15.52 18.1a.46.46 0 0 1-.51-.51V16h-.15a.34.34 0 0 1-.39-.38c0-.25.13-.37.39-.37H15a1.2 1.2 0 0 1 .34-.87 1.52 1.52 0 0 1 .92-.36h.17a.39.39 0 0 1 .29 0 .35.35 0 0 1 .15.17.55.55 0 0 1 0 .22.38.38 0 0 1-.09.19.27.27 0 0 1-.18.1h-.08a.66.66 0 0 0-.41.12.41.41 0 0 0-.11.31v.09h.32c.26 0 .39.12.39.37a.34.34 0 0 1-.39.38H16v1.6a.45.45 0 0 1-.48.53Z" class="cls-3"/></svg>`,
    doc: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#e3f2fd}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#2196f3"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#82b1ff"/><path d="M8.3 18.11a1.16 1.16 0 0 1-.64-.17 1.2 1.2 0 0 1-.43-.51 2 2 0 0 1 0-1.57 1.2 1.2 0 0 1 .43-.51 1.17 1.17 0 0 1 .64-.18 1 1 0 0 1 .49.12.84.84 0 0 1 .35.28v-1.09a.45.45 0 0 1 .51-.48.52.52 0 0 1 .38.13.5.5 0 0 1 .13.38v3.11a.45.45 0 0 1-.5.51.44.44 0 0 1-.5-.43.78.78 0 0 1-.35.32 1.07 1.07 0 0 1-.51.09Zm.33-.74a.46.46 0 0 0 .37-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.48.48 0 0 0-.37-.17.51.51 0 0 0-.39.17.82.82 0 0 0-.15.55.8.8 0 0 0 .15.55.48.48 0 0 0 .39.18ZM12.32 18.11a1.74 1.74 0 0 1-.82-.17 1.27 1.27 0 0 1-.54-.51 1.64 1.64 0 0 1 0-1.57 1.27 1.27 0 0 1 .54-.51 1.74 1.74 0 0 1 .82-.18 1.8 1.8 0 0 1 .82.18 1.33 1.33 0 0 1 .54.51 1.72 1.72 0 0 1 0 1.57 1.33 1.33 0 0 1-.54.51 1.8 1.8 0 0 1-.82.17Zm0-.74a.45.45 0 0 0 .37-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.47.47 0 0 0-.37-.17.51.51 0 0 0-.39.17.82.82 0 0 0-.14.55.81.81 0 0 0 .14.55.48.48 0 0 0 .39.18ZM15.92 18.11a1.78 1.78 0 0 1-.83-.17 1.35 1.35 0 0 1-.55-.51 1.54 1.54 0 0 1-.19-.79 1.52 1.52 0 0 1 .19-.79 1.27 1.27 0 0 1 .55-.5 1.78 1.78 0 0 1 .83-.18 1.5 1.5 0 0 1 .35 0 1.4 1.4 0 0 1 .37.11.38.38 0 0 1 .19.16.36.36 0 0 1 .05.23.38.38 0 0 1-.07.21.26.26 0 0 1-.16.13.3.3 0 0 1-.24 0 .88.88 0 0 0-.38-.09.6.6 0 0 0-.47.18.77.77 0 0 0-.17.52.76.76 0 0 0 .17.52.57.57 0 0 0 .47.19h.18l.2-.07a.3.3 0 0 1 .24 0 .28.28 0 0 1 .16.13.48.48 0 0 1 .07.21.59.59 0 0 1-.05.23.41.41 0 0 1-.2.16 1.92 1.92 0 0 1-.37.11Z" class="cls-3"/></svg>`,
    ppt: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#fff3e0}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#ff9800"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#ffd180"/><path d="M7.83 19.13a.45.45 0 0 1-.5-.51v-2.93a.45.45 0 0 1 .5-.51.45.45 0 0 1 .5.43.76.76 0 0 1 .34-.32 1.13 1.13 0 0 1 .51-.12 1.14 1.14 0 0 1 .64.18 1.22 1.22 0 0 1 .44.51 2 2 0 0 1 0 1.57 1.2 1.2 0 0 1-.43.51 1.17 1.17 0 0 1-.65.17A1.12 1.12 0 0 1 8.7 18a.8.8 0 0 1-.35-.3v.91a.47.47 0 0 1-.52.51Zm1-1.76a.46.46 0 0 0 .38-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.49.49 0 0 0-.38-.17.5.5 0 0 0-.38.17.82.82 0 0 0-.15.55.8.8 0 0 0 .15.55.48.48 0 0 0 .41.18ZM11.52 19.13a.45.45 0 0 1-.5-.51v-2.93a.5.5 0 0 1 1-.08.76.76 0 0 1 .34-.32 1.14 1.14 0 0 1 .52-.12 1.11 1.11 0 0 1 .63.18 1.22 1.22 0 0 1 .44.51 2 2 0 0 1 0 1.57 1.2 1.2 0 0 1-.43.51 1.16 1.16 0 0 1-.64.17 1.13 1.13 0 0 1-.49-.1.86.86 0 0 1-.35-.3v.91a.5.5 0 0 1-.13.38.53.53 0 0 1-.39.13Zm1-1.76a.46.46 0 0 0 .38-.18.8.8 0 0 0 .15-.55.82.82 0 0 0-.15-.55.49.49 0 0 0-.38-.17.5.5 0 0 0-.38.17.82.82 0 0 0-.15.55.8.8 0 0 0 .15.55.48.48 0 0 0 .41.18ZM16.16 18.11a1.32 1.32 0 0 1-.95-.29 1.19 1.19 0 0 1-.31-.9V16h-.19a.34.34 0 0 1-.39-.38c0-.25.13-.37.39-.37h.19v-.36a.5.5 0 0 1 .1-.39.52.52 0 0 1 .38-.13.45.45 0 0 1 .51.51v.36h.47c.26 0 .39.12.39.37a.34.34 0 0 1-.39.38h-.47v.89a.37.37 0 0 0 .42.42H16.6a.2.2 0 0 1 .15.06s0 .13 0 .27a.65.65 0 0 1 0 .29.3.3 0 0 1-.18.17 1 1 0 0 1-.22 0 .94.94 0 0 1-.19.02Z" class="cls-3"/></svg>`,
    xls: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#e8f5e9}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#4caf50"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#b9f6ca"/><path d="M8.44 18.1a.45.45 0 0 1-.31-.1.33.33 0 0 1-.13-.27.49.49 0 0 1 .13-.32l.66-.81-.59-.72a.47.47 0 0 1-.13-.33.35.35 0 0 1 .11-.26.45.45 0 0 1 .31-.11.66.66 0 0 1 .3.07.77.77 0 0 1 .23.19l.35.46.36-.46a.64.64 0 0 1 .22-.19.66.66 0 0 1 .3-.07.45.45 0 0 1 .31.11.35.35 0 0 1 .11.26.51.51 0 0 1-.13.33l-.6.72.67.81a.44.44 0 0 1 .13.32.31.31 0 0 1-.12.26.45.45 0 0 1-.31.11.65.65 0 0 1-.31-.1.7.7 0 0 1-.23-.2l-.43-.54-.34.58a.75.75 0 0 1-.22.19.56.56 0 0 1-.34.07ZM12.53 18.11a1 1 0 0 1-.81-.29 1.33 1.33 0 0 1-.26-.89v-2.45A.45.45 0 0 1 12 14a.52.52 0 0 1 .38.13.5.5 0 0 1 .13.38v2.39a.52.52 0 0 0 .08.31.29.29 0 0 0 .22.09H13a.15.15 0 0 1 .14.07.66.66 0 0 1 .05.31.51.51 0 0 1-.08.3.43.43 0 0 1-.25.13h-.13ZM14.69 18.11a3.12 3.12 0 0 1-.52 0 1.64 1.64 0 0 1-.46-.13.38.38 0 0 1-.24-.2.39.39 0 0 1 0-.26.35.35 0 0 1 .16-.2.28.28 0 0 1 .27 0 2.18 2.18 0 0 0 .43.12 1.69 1.69 0 0 0 .38 0 .56.56 0 0 0 .3-.06.18.18 0 0 0 .09-.16.15.15 0 0 0-.06-.13.38.38 0 0 0-.17-.06l-.63-.11a.91.91 0 0 1-.55-.25.74.74 0 0 1-.2-.53.85.85 0 0 1 .17-.51 1.07 1.07 0 0 1 .46-.33 1.83 1.83 0 0 1 .68-.12 3.3 3.3 0 0 1 .47 0 2 2 0 0 1 .41.13.33.33 0 0 1 .2.2.39.39 0 0 1 0 .26.35.35 0 0 1-.17.19.3.3 0 0 1-.28 0 2.19 2.19 0 0 0-.34-.11 1.47 1.47 0 0 0-.27 0 .62.62 0 0 0-.33.07.18.18 0 0 0-.1.16c0 .1.07.16.21.19l.64.11a1 1 0 0 1 .56.24.7.7 0 0 1 .2.52.83.83 0 0 1-.36.72 1.56 1.56 0 0 1-.95.25Z" class="cls-3"/></svg>`,
    txt: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#efebe9}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#795548"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#d7ccc8"/><path d="M9.49 18.11a1.3 1.3 0 0 1-1-.29 1.19 1.19 0 0 1-.31-.9V16H8a.34.34 0 0 1-.38-.38.33.33 0 0 1 .38-.38h.2v-.36a.46.46 0 0 1 .51-.51.45.45 0 0 1 .51.51v.36h.47c.26 0 .39.12.39.37a.34.34 0 0 1-.39.38h-.44v.89a.37.37 0 0 0 .42.42H9.96a.2.2 0 0 1 .15.06.5.5 0 0 1 .05.27.65.65 0 0 1-.05.29.3.3 0 0 1-.18.17h-.22a1 1 0 0 1-.22.02ZM11 18.1a.41.41 0 0 1-.3-.11.34.34 0 0 1-.12-.26.49.49 0 0 1 .13-.32l.67-.81-.6-.72a.51.51 0 0 1-.13-.33.32.32 0 0 1 .12-.26.41.41 0 0 1 .3-.11.66.66 0 0 1 .53.26l.35.46.36-.46a.66.66 0 0 1 .53-.26.44.44 0 0 1 .3.11.4.4 0 0 1 .12.26.53.53 0 0 1-.14.33l-.59.72.67.81a.49.49 0 0 1 .13.32.35.35 0 0 1-.13.26.42.42 0 0 1-.3.11.67.67 0 0 1-.3-.06.7.7 0 0 1-.23-.2L12 17.3l-.43.54a.64.64 0 0 1-.22.19.51.51 0 0 1-.35.07ZM15.54 18.11a1.34 1.34 0 0 1-.95-.29 1.23 1.23 0 0 1-.31-.9V16h-.19a.33.33 0 0 1-.38-.38c0-.25.12-.37.38-.37h.19v-.36a.51.51 0 0 1 .14-.38.48.48 0 0 1 .37-.13.45.45 0 0 1 .51.51v.36h.47c.26 0 .39.12.39.37a.34.34 0 0 1-.39.38h-.47v.89a.37.37 0 0 0 .42.42H16.01a.2.2 0 0 1 .15.06.41.41 0 0 1 .06.27.84.84 0 0 1 0 .29.35.35 0 0 1-.19.17.87.87 0 0 1-.21 0 1.06 1.06 0 0 1-.28.01Z" class="cls-3"/></svg>`,
    csv: `<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><defs><style>.cls-3{fill:#e8f5e9}</style></defs><path d="M16.5 22h-9a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6.59a1 1 0 0 1 .7.29l4.42 4.42a1 1 0 0 1 .29.7V19a3 3 0 0 1-3 3Z" style="fill:#4caf50"/><path d="M18.8 7.74h-3.6a1.5 1.5 0 0 1-1.5-1.5v-3.6a.55.55 0 0 1 .94-.39l4.55 4.55a.55.55 0 0 1-.39.94Z" style="fill:#b9f6ca"/><path d="M9.19 18.11a1.77 1.77 0 0 1-.82-.17 1.29 1.29 0 0 1-.55-.51 1.54 1.54 0 0 1-.2-.79 1.52 1.52 0 0 1 .2-.79 1.21 1.21 0 0 1 .55-.5 1.77 1.77 0 0 1 .82-.18 1.45 1.45 0 0 1 .35 0 1.47 1.47 0 0 1 .38.11.38.38 0 0 1 .19.16.43.43 0 0 1 .05.23.48.48 0 0 1-.07.21.27.27 0 0 1-.17.13.27.27 0 0 1-.23 0 .92.92 0 0 0-.39-.09.61.61 0 0 0-.47.18.77.77 0 0 0-.16.52.75.75 0 0 0 .16.52.58.58 0 0 0 .47.19h.19l.2-.07a.27.27 0 0 1 .23 0 .26.26 0 0 1 .16.13.38.38 0 0 1 .07.21.37.37 0 0 1-.05.23.38.38 0 0 1-.19.16 2.15 2.15 0 0 1-.37.11ZM11.79 18.11a2.92 2.92 0 0 1-.51 0 1.62 1.62 0 0 1-.47-.13.38.38 0 0 1-.24-.2.39.39 0 0 1 0-.26.35.35 0 0 1 .16-.2.3.3 0 0 1 .28 0 1.93 1.93 0 0 0 .42.12 1.69 1.69 0 0 0 .38 0 .56.56 0 0 0 .3-.06.18.18 0 0 0 .09-.16.15.15 0 0 0-.06-.13A.38.38 0 0 0 12 17l-.63-.11a.91.91 0 0 1-.55-.25.74.74 0 0 1-.2-.53.85.85 0 0 1 .17-.51 1.07 1.07 0 0 1 .46-.33 1.83 1.83 0 0 1 .68-.12 3.17 3.17 0 0 1 .47 0 2 2 0 0 1 .41.13.33.33 0 0 1 .2.2.39.39 0 0 1 0 .26.35.35 0 0 1-.17.19.3.3 0 0 1-.28 0 2.19 2.19 0 0 0-.34-.11 1.36 1.36 0 0 0-.27 0 .62.62 0 0 0-.33.07.18.18 0 0 0-.1.16c0 .1.07.16.21.19l.64.11a1 1 0 0 1 .56.24.7.7 0 0 1 .2.52.83.83 0 0 1-.36.72 1.56 1.56 0 0 1-.98.28ZM14.84 18.1a.59.59 0 0 1-.57-.4l-.82-1.87a.51.51 0 0 1 0-.45.46.46 0 0 1 .44-.2.44.44 0 0 1 .29.09.63.63 0 0 1 .21.32l.48 1.24.51-1.25a.72.72 0 0 1 .2-.31.53.53 0 0 1 .32-.09.4.4 0 0 1 .27.09.36.36 0 0 1 .13.24.53.53 0 0 1-.05.32l-.84 1.88a.58.58 0 0 1-.57.39Z" class="cls-3"/></svg>`,
    default: `<svg viewBox="0 0 24 24"><path fill="#555" d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6c-1.1 0-2-.9-2-2V4a2 2 0 0 1 2-2z"/></svg>`,
  };

  buildUI() {
    this.dropZone = document.createElement("div");
    this.dropZone.className = "pirulug-dropzone";

    this.uploadIcon = document.createElement("div");
    this.uploadIcon.innerHTML = PirulugDropzone.icons.upload;

    this.uploadText = document.createElement("small");
    this.uploadText.textContent = "Arrastra o haz clic para subir";

    this.counter = document.createElement("div");
    this.counter.className = "counter";
    this.counter.textContent = this.multiple ? `0/${this.maxFiles}` : "";

    this.errorMsg = document.createElement("div");
    this.errorMsg.className = "error-msg";

    this.dropZone.appendChild(this.uploadIcon);
    this.dropZone.appendChild(this.uploadText);
    if (this.multiple) this.dropZone.appendChild(this.counter);
    this.dropZone.appendChild(this.errorMsg);

    if (this.multiple) {
      this.fileList = document.createElement("div");
      this.fileList.className = "pdz-file-list";
      this.dropZone.appendChild(this.fileList);
      this.files = [];
    } else {
      this.preview = document.createElement("img");
      this.preview.src = this.defaultSrc;
      this.preview.style.width = this.width * 0.7 + "px";
      this.preview.style.height = this.height * 0.7 + "px";
      this.preview.style.objectFit = "cover";
      this.preview.style.display = "none";

      this.fileName = document.createElement("div");
      this.fileName.className = "file-name";

      this.removeBtn = document.createElement("button");
      this.removeBtn.type = "button";
      this.removeBtn.className = "pdz-remove-btn";
      this.removeBtn.textContent = "×";
      this.removeBtn.style.display = "none";

      this.dropZone.appendChild(this.preview);
      this.dropZone.appendChild(this.fileName);
      this.dropZone.appendChild(this.removeBtn);
    }

    this.input.style.display = "none";
    this.input.parentNode.insertBefore(this.dropZone, this.input);
  }

  bindEvents() {
    this.dropZone.addEventListener("click", () => this.input.click());
    this.input.addEventListener("change", (e) =>
      this.handleFiles(e.target.files)
    );

    ["dragenter", "dragover"].forEach((ev) =>
      this.dropZone.addEventListener(ev, (e) => {
        e.preventDefault();
        this.dropZone.classList.add("dragover");
      })
    );
    ["dragleave", "drop"].forEach((ev) =>
      this.dropZone.addEventListener(ev, (e) => {
        e.preventDefault();
        this.dropZone.classList.remove("dragover");
      })
    );

    this.dropZone.addEventListener("drop", (e) => {
      this.handleFiles(e.dataTransfer.files);
      this.input.files = e.dataTransfer.files;
    });

    if (!this.multiple) {
      this.removeBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.resetPreview();
      });
    }
  }

  handleFiles(fileList) {
    if (!fileList.length) return;
    if (this.multiple) {
      [...fileList].forEach((file) => this.addFile(file));
      this.updateCounter();
    } else {
      this.showPreview(fileList[0]);
    }
  }

  addFile(file) {
    if (this.files.length >= this.maxFiles) {
      this.showError(`Máximo ${this.maxFiles} archivos permitidos.`);
      return;
    }

    const ext = (file.name.split(".").pop() || "").toLowerCase();
    if (this.accept.length && !this.accept.includes(ext)) {
      this.showError(`Archivo no permitido: ${file.name}`);
      return;
    }

    this.clearError();

    const item = document.createElement("div");
    item.className = "pdz-file-item";

    const thumb = document.createElement("div");
    thumb.className = "pdz-file-thumb";
    thumb.style.width = this.thumbWidth + "px";
    thumb.style.height = this.thumbHeight + "px";

    if (file.type.startsWith("image/")) {
      const img = document.createElement("img");
      const reader = new FileReader();
      reader.onload = (e) => (img.src = e.target.result);
      reader.readAsDataURL(file);
      thumb.appendChild(img);
    } else {
      thumb.innerHTML = this.getDocIcon(ext);
    }

    const name = document.createElement("div");
    name.className = "pdz-file-name";
    name.textContent = file.name;

    const remove = document.createElement("button");
    remove.type = "button";
    remove.className = "pdz-remove-btn";
    remove.textContent = "×";
    remove.addEventListener("click", (e) => {
      e.stopPropagation();
      this.fileList.removeChild(item);
      this.files = this.files.filter((f) => f !== file);
      this.updateCounter();
    });

    item.appendChild(thumb);
    item.appendChild(name);
    item.appendChild(remove);

    this.fileList.appendChild(item);
    this.files.push(file);
    this.updateCounter();
  }

  updateCounter() {
    if (this.multiple && this.counter) {
      this.counter.textContent = `${this.files.length}/${this.maxFiles}`;
    }
  }

  showPreview(file) {
    if (!file) return;

    const ext = (file.name.split(".").pop() || "").toLowerCase();

    if (this.accept.length && !this.accept.includes(ext)) {
      this.resetPreview();
      this.showError(`Extensión no permitida: .${ext}`);
      return;
    }

    this.fileName.textContent = file.name;
    this.fileName.style.display = "block";
    this.removeBtn.style.display = "flex";
    this.uploadIcon.style.display = "none";
    this.uploadText.style.display = "none";
    this.clearError();

    if (file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.preview.src = e.target.result;
        this.preview.style.display = "block";
        this.preview.style.width = this.width + "px";
        this.preview.style.height = this.height + "px";
      };
      reader.readAsDataURL(file);
    } else {
      this.preview.style.display = "none";
      this.preview.insertAdjacentHTML("afterend", this.getDocIcon(ext));
    }
  }

  resetPreview() {
    if (this.defaultSrc) {
      // 👇 Restaurar la imagen actual del usuario
      this.preview.src = this.defaultSrc;
      this.preview.style.display = "block";
      this.preview.style.width = this.width + "px";
      this.preview.style.height = this.height + "px";
      this.uploadIcon.style.display = "block";
      this.uploadText.style.display = "block";
      this.fileName.style.display = "none";
      this.removeBtn.style.display = "none";
      this.fileName.textContent = "";
      this.input.value = "";
    } else {
      // 👇 Comportamiento por defecto (dejar en blanco)
      this.preview.src = "";
      this.preview.style.display = "none";
      this.uploadIcon.style.display = "block";
      this.uploadText.style.display = "block";
      this.fileName.style.display = "none";
      this.removeBtn.style.display = "none";
      this.fileName.textContent = "";
      this.input.value = "";
    }
  }

  getDocIcon(ext) {
    if (ext === "pdf") return PirulugDropzone.icons.pdf;
    if (ext === "doc" || ext === "docx") return PirulugDropzone.icons.doc;
    if (ext === "xls" || ext === "xlsx") return PirulugDropzone.icons.xls;
    if (ext === "ppt" || ext === "pptx") return PirulugDropzone.icons.ppt;
    if (ext === "csv") return PirulugDropzone.icons.csv;
    if (ext === "txt") return PirulugDropzone.icons.txt;
    return PirulugDropzone.icons.default;
  }

  showError(msg) {
    this.errorMsg.textContent = msg;
    this.errorMsg.style.display = "block";
  }

  clearError() {
    this.errorMsg.textContent = "";
    this.errorMsg.style.display = "none";
  }
}
// ---