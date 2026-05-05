document.addEventListener("DOMContentLoaded", function () {
  const tzSelect = document.getElementById("site_timezone");

  // Mapeo de Inputs con sus contenedores de vista previa
  const inputs = [
    { input: document.getElementById("date_format"), preview: document.getElementById("preview_date") },
    { input: document.getElementById("time_format"), preview: document.getElementById("preview_time") },
    { input: document.getElementById("datetime_format"), preview: document.getElementById("preview_datetime") }
  ];

  /**
   * Devuelve la fecha/hora actual ajustada a la zona horaria seleccionada
   */
  function getDateInTimezone(timezone) {
    try {
      const now = new Date();
      const tzDateStr = now.toLocaleString('en-US', { timeZone: timezone });
      return new Date(tzDateStr);
    } catch (e) {
      console.error("Timezone inválida, usando local", e);
      return new Date();
    }
  }

  /**
   * Emula parcialmente PHP date()
   */
  function phpDate(format, date) {
    const pad = (n, c = 2) => String(n).padStart(c, '0');
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const months = [
      'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
      'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];

    return format.replace(/([a-zA-Z])/g, (match) => {
      switch (match) {
        // Día
        case 'd': return pad(date.getDate());
        case 'j': return date.getDate();
        case 'D': return days[date.getDay()].substring(0, 3);
        case 'l': return days[date.getDay()];
        case 'N': return date.getDay() === 0 ? 7 : date.getDay();
        // Mes
        case 'm': return pad(date.getMonth() + 1);
        case 'n': return date.getMonth() + 1;
        case 'M': return months[date.getMonth()].substring(0, 3);
        case 'F': return months[date.getMonth()];
        // Año
        case 'Y': return date.getFullYear();
        case 'y': return String(date.getFullYear()).slice(2);
        // Hora
        case 'H': return pad(date.getHours());
        case 'G': return date.getHours();
        case 'h': return pad(date.getHours() % 12 || 12);
        case 'g': return date.getHours() % 12 || 12;
        case 'i': return pad(date.getMinutes());
        case 's': return pad(date.getSeconds());
        case 'a': return date.getHours() >= 12 ? 'pm' : 'am';
        case 'A': return date.getHours() >= 12 ? 'PM' : 'AM';
        default: return match;
      }
    });
  }

  /**
   * Actualiza los ejemplos
   */
  function updateExamples() {
    const tz = tzSelect ? tzSelect.value : 'UTC';
    const now = getDateInTimezone(tz);

    // Fechas
    inputs.forEach(item => {
      if (item.input && item.preview) {
        const val = item.input.value;
        if (!val) {
          item.preview.innerHTML = '<span class="text-muted fst-italic">Escribe un formato...</span>';
        } else {
          item.preview.textContent = phpDate(val, now);
        }
      }
    });

    // Números y Moneda
    const testValue = 1250.50;

    // Números
    const numDec = document.getElementById("number_decimal_sep");
    const numTho = document.getElementById("number_thousand_sep");
    const numDecCount = document.getElementById("number_decimals");
    const numPre = document.getElementById("preview_number");
    const numPreDec = document.getElementById("preview_number_decimal");

    if (numDec && numTho && numDecCount) {
      const d = numDec.value;
      const t = numTho.value;
      const c = parseInt(numDecCount.value) || 0;

      if (numPre) {
        // Inteligente: con decimales existentes
        numPre.textContent = formatNumberJS(1250.50, d, t, 2);
      }
      if (numPreDec) {
        // Forzado: incluso sin decimales originales
        numPreDec.textContent = formatNumberJS(1250, d, t, c);
      }
    }

    // Moneda
    const curSym = document.getElementById("currency_symbol");
    const curPos = document.getElementById("currency_position");
    const curDec = document.getElementById("currency_decimals");
    const curDecSep = document.getElementById("currency_decimal_sep");
    const curThoSep = document.getElementById("currency_thousand_sep");
    const curPre = document.getElementById("preview_currency");

    if (curPre && curSym && curPos && curDec && curDecSep && curThoSep) {
      const formatted = formatNumberJS(
        testValue,
        curDecSep.value,
        curThoSep.value,
        parseInt(curDec.value) || 0
      );
      
      const symbol = curSym.value || '$';
      const position = curPos.value || 'before';

      if (position === 'after') {
        curPre.textContent = formatted + " " + symbol;
      } else {
        curPre.textContent = symbol + " " + formatted;
      }
    }
  }

  /**
   * Formateador de números (emula number_format de PHP)
   */
  function formatNumberJS(number, decimalSep, thousandSep, decimals) {
    let parts = number.toFixed(decimals).split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSep);
    return parts.join(decimalSep);
  }

  // Event Listeners
  if (tzSelect) tzSelect.addEventListener("change", updateExamples);
  
  // Listeners para inputs de fecha
  inputs.forEach(item => {
    if (item.input) item.input.addEventListener("input", updateExamples);
  });

  // Listeners para inputs de números y moneda
  const extraInputs = [
    "number_decimal_sep", "number_thousand_sep", "number_decimals",
    "currency_symbol", "currency_position", "currency_decimals", "currency_decimal_sep", "currency_thousand_sep"
  ];

  extraInputs.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener("input", updateExamples);
  });

  // Iniciar
  updateExamples();
});
