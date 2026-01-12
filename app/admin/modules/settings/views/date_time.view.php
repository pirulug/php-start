<?php start_block("title"); ?>
Date Time
<?php end_block(); ?>

<form action="" method="POST" id="settingsForm">

  <div class="card">
    <div class="card-header   d-flex justify-content-between align-items-center py-3">
      <h5 class="card-title mb-0 d-flex align-items-center gap-2">
        <i class="fa-regular fa-clock text-primary"></i>
        Configuración Regional
      </h5>

    </div>

    <div class="card-body">

      <div class="mb-4">
        <label for="site_timezone" class="form-label fw-bold text-body-emphasis">Zona Horaria del Sistema</label>
        <div class="input-group">
          <span class="input-group-text "><i class="fa-solid fa-earth-americas text-muted"></i></span>
          <select class="form-select" id="site_timezone" name="site_timezone">
            <?php echo timezone_select($optionsRaw['site_timezone'] ?? 'UTC'); ?>
          </select>
        </div>
        <div class="form-text text-body-secondary">
          Esta configuración afecta cómo se guardan y muestran las fechas en todo el sistema.
        </div>
      </div>

      <hr class="border-secondary-subtle my-4">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-body-emphasis mb-0">Formatos de Fecha y Hora</h6>
        <button type="button" class="btn btn-outline-info btn-sm border-0" data-bs-toggle="modal"
          data-bs-target="#datetimeFormatModal">
          <i class="fa-solid fa-circle-question me-1"></i> Ver códigos de formato
        </button>
      </div>

      <div class="row g-3">

        <div class="col-md-6">
          <label for="date_format" class="form-label small text-muted text-uppercase fw-bold">Fecha</label>
          <div class="input-group">
            <span class="input-group-text "><i class="fa-regular fa-calendar text-muted"></i></span>
            <input type="text" class="form-control font-monospace" id="date_format" name="date_format"
              value="<?= $optionsRaw["date_format"] ?? "" ?>" placeholder="d F, Y">
          </div>
          <div class="mt-2 p-2  rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_date" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>

        <div class="col-md-6">
          <label for="time_format" class="form-label small text-muted text-uppercase fw-bold">Hora</label>
          <div class="input-group">
            <span class="input-group-text "><i class="fa-regular fa-clock text-muted"></i></span>
            <input type="text" class="form-control font-monospace" id="time_format" name="time_format"
              value="<?= $optionsRaw["time_format"] ?? "" ?>" placeholder="h:i a">
          </div>
          <div class="mt-2 p-2  rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_time" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>

        <div class="col-12">
          <label for="datetime_format" class="form-label small text-muted text-uppercase fw-bold">Fecha y Hora
            Completa</label>
          <div class="input-group">
            <span class="input-group-text "><i class="fa-solid fa-calendar-days text-muted"></i></span>
            <input type="text" class="form-control font-monospace" id="datetime_format" name="datetime_format"
              value="<?= $optionsRaw["datetime_format"] ?? "" ?>" placeholder="d M, Y - h:i a">
          </div>
          <div class="mt-2 p-2  rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_datetime" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>

      </div>
    </div>

    <div class="card-footer text-end">
      <a href="<?= admin_route('users') ?>" class="btn btn-secondary">
        <i class="fa fa-arrow-left me-1"></i>
        Cancelar
      </a>
      <button class="btn btn-primary" type="submit" name="save">
        <i class="fa-solid fa-save me-1"></i> Guardar
      </button>
    </div>

  </div>
</form>

<div class="modal fade" id="datetimeFormatModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header ">
        <h5 class="modal-title">
          <i class="fa-solid fa-code text-primary me-2"></i>Códigos de Formato PHP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="alert alert-light  m-0 rounded-0">
          <small><i class="fa-solid fa-info-circle me-1"></i> Estos caracteres son estándar de la función
            <code>date()</code> de PHP.</small>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle text-sm">
            <thead class="table-light sticky-top">
              <tr>
                <th style="width: 50px;">Cod</th>
                <th>Descripción</th>
                <th>Ejemplo</th>
              </tr>
            </thead>
            <tbody class="font-monospace">
              <tr>
                <td colspan="3" class="-secondary fw-bold text-uppercase px-3 py-1 font-sans-serif">Días</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">d</td>
                <td>Día del mes (01-31)</td>
                <td>01</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">j</td>
                <td>Día del mes (1-31)</td>
                <td>1</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">l</td>
                <td>Nombre del día completo</td>
                <td>Lunes</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">D</td>
                <td>Nombre del día corto</td>
                <td>Lun</td>
              </tr>

              <tr>
                <td colspan="3" class="-secondary fw-bold text-uppercase px-3 py-1 font-sans-serif">Meses</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">m</td>
                <td>Mes numérico (01-12)</td>
                <td>01</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">M</td>
                <td>Nombre del mes corto</td>
                <td>Ene</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">F</td>
                <td>Nombre del mes completo</td>
                <td>Enero</td>
              </tr>

              <tr>
                <td colspan="3" class="-secondary fw-bold text-uppercase px-3 py-1 font-sans-serif">Años</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">Y</td>
                <td>Año 4 dígitos</td>
                <td>2025</td>
              </tr>
              <tr>
                <td class="text-primary fw-bold">y</td>
                <td>Año 2 dígitos</td>
                <td>25</td>
              </tr>

              <tr>
                <td colspan="3" class="-secondary fw-bold text-uppercase px-3 py-1 font-sans-serif">Hora</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">H</td>
                <td>Hora 24h (00-23)</td>
                <td>14</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">h</td>
                <td>Hora 12h (01-12)</td>
                <td>02</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">i</td>
                <td>Minutos (00-59)</td>
                <td>05</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">s</td>
                <td>Segundos (00-59)</td>
                <td>09</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">a</td>
                <td>am / pm</td>
                <td>pm</td>
              </tr>
              <tr>
                <td class="text-danger fw-bold">A</td>
                <td>AM / PM</td>
                <td>PM</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>

<script>
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
     * Nota: Mantuve tu lógica intacta, solo me aseguro que funcione visualmente.
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
      const tz = tzSelect.value || 'UTC';
      const now = getDateInTimezone(tz);

      inputs.forEach(item => {
        if (item.input && item.preview) {
          const val = item.input.value;
          // Si está vacío, mostramos placeholder visual
          if (!val) {
            item.preview.innerHTML = '<span class="text-muted fst-italic">Escribe un formato...</span>';
          } else {
            item.preview.textContent = phpDate(val, now);
          }
        }
      });
    }

    // Event Listeners
    tzSelect.addEventListener("change", updateExamples);
    inputs.forEach(item => {
      if (item.input) {
        item.input.addEventListener("input", updateExamples);
      }
    });

    // Iniciar
    updateExamples();
  });
</script>