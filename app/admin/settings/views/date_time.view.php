<form action="" method="POST">

  <div class="card ">
    <div class="card-body">

      <!-- Timezone -->
      <div class="mb-3">
        <label for="timezone" class="form-label fw-semibold">Timezone</label>
        <select class="form-select" id="site_timezone" name="site_timezone">
          <?php echo timezone_select($optionsRaw['site_timezone'] ?? 'UTC'); ?>
        </select>
      </div>

      <!-- Date Format -->
      <div class="mb-3">
        <label for="date_format" class="form-label fw-semibold">
          Date Format
        </label>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#datetimeFormatModal">
          <i class="fa fa-info-circle"></i>
        </button>

        <input type="text" class="form-control" id="date_format" name="date_format"
          value="<?= $optionsRaw["date_format"] ?? "" ?>" placeholder="Ejm: d F, Y">
        <div class="form-text">-</div>
      </div>

      <!-- Time Format -->
      <div class="mb-3">
        <label for="time_format" class="form-label fw-semibold">Time Format</label>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#datetimeFormatModal">
          <i class="fa fa-info-circle"></i>
        </button>

        <input type="text" class="form-control" id="time_format" name="time_format"
          value="<?= $optionsRaw["time_format"] ?? "" ?>" placeholder="Ejm: h:m a">
        <div class="form-text">-</div>
      </div>

      <!-- DateTime Format -->
      <div class="">
        <label for="datetime_format" class="form-label fw-semibold">DateTime Format</label>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#datetimeFormatModal">
          <i class="fa fa-info-circle"></i>
        </button>

        <input type="text" class="form-control" id="datetime_format" name="datetime_format"
          value="<?= $optionsRaw["datetime_format"] ?? "" ?>" placeholder="Ejm: h:m a - d M, Y">
        <div class="form-text">-</div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between bg-body p-3 rounded mt-3">
    <a href="<?= url_admin('users') ?>" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary" type="submit" name="save">Actualizar</button>
  </div>

</form>

<!-- Modal Date Time Format -->
<div class="modal fade" id="datetimeFormatModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">
          Date & Date Time Formats
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          Puedes usar los caracteres disponibles en la función <code>date()</code> de PHP.
          Más información en la documentación oficial:
          <a href="https://www.php.net/manual/es/function.date.php"
            target="_blank">https://www.php.net/manual/es/function.date.php</a>
        </div>
        <!-- =================== -->
        <!-- SECCIÓN: FECHA -->
        <!-- =================== -->
        <h5 class="fw-semibold mt-4">Formatos de Fecha</h5>
        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Carácter</th>
                <th>Descripción</th>
                <th>Ejemplo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><code>d</code></td>
                <td>Día del mes (con ceros iniciales)</td>
                <td>01–31</td>
              </tr>
              <tr>
                <td><code>j</code></td>
                <td>Día del mes (sin ceros)</td>
                <td>1–31</td>
              </tr>
              <tr>
                <td><code>l</code></td>
                <td>Nombre completo del día</td>
                <td>Lunes</td>
              </tr>
              <tr>
                <td><code>D</code></td>
                <td>Abreviatura del día</td>
                <td>Lun</td>
              </tr>
              <tr>
                <td><code>F</code></td>
                <td>Nombre completo del mes</td>
                <td>Octubre</td>
              </tr>
              <tr>
                <td><code>M</code></td>
                <td>Abreviatura del mes</td>
                <td>Oct</td>
              </tr>
              <tr>
                <td><code>m</code></td>
                <td>Mes numérico (con ceros)</td>
                <td>01–12</td>
              </tr>
              <tr>
                <td><code>n</code></td>
                <td>Mes numérico (sin ceros)</td>
                <td>1–12</td>
              </tr>
              <tr>
                <td><code>Y</code></td>
                <td>Año completo</td>
                <td>2025</td>
              </tr>
              <tr>
                <td><code>y</code></td>
                <td>Año corto</td>
                <td>25</td>
              </tr>
              <tr>
                <td><code>z</code></td>
                <td>Día del año</td>
                <td>0–365</td>
              </tr>
              <tr>
                <td><code>W</code></td>
                <td>Número de semana ISO-8601</td>
                <td>1–52</td>
              </tr>
            </tbody>
          </table>
        </div>

        <h6 class="mt-3 fw-bold">Ejemplo:</h6>
        <pre><code>$fecha = date("d/m/Y");
echo $fecha; // Resultado: 30/10/2025</code></pre>

        <pre><code>$fecha = date("l, d F Y");
echo $fecha; // Resultado: Jueves, 30 Octubre 2025</code></pre>

        <!-- =================== -->
        <!-- SECCIÓN: HORA -->
        <!-- =================== -->
        <h5 class="fw-semibold mt-5">Formatos de Hora</h5>
        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Carácter</th>
                <th>Descripción</th>
                <th>Ejemplo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><code>H</code></td>
                <td>Hora en formato 24h (con ceros)</td>
                <td>00–23</td>
              </tr>
              <tr>
                <td><code>G</code></td>
                <td>Hora en formato 24h (sin ceros)</td>
                <td>0–23</td>
              </tr>
              <tr>
                <td><code>h</code></td>
                <td>Hora en formato 12h (con ceros)</td>
                <td>01–12</td>
              </tr>
              <tr>
                <td><code>g</code></td>
                <td>Hora en formato 12h (sin ceros)</td>
                <td>1–12</td>
              </tr>
              <tr>
                <td><code>i</code></td>
                <td>Minutos (con ceros)</td>
                <td>00–59</td>
              </tr>
              <tr>
                <td><code>s</code></td>
                <td>Segundos (con ceros)</td>
                <td>00–59</td>
              </tr>
              <tr>
                <td><code>a</code></td>
                <td>am / pm en minúsculas</td>
                <td>am</td>
              </tr>
              <tr>
                <td><code>A</code></td>
                <td>AM / PM en mayúsculas</td>
                <td>PM</td>
              </tr>
              <tr>
                <td><code>e</code></td>
                <td>Zona horaria</td>
                <td>America/Lima</td>
              </tr>
              <tr>
                <td><code>T</code></td>
                <td>Abreviatura de zona horaria</td>
                <td>PET</td>
              </tr>
            </tbody>
          </table>
        </div>

        <h6 class="mt-3 fw-bold">Ejemplo:</h6>
        <pre><code>$hora = date("H:i:s");
echo $hora; // Resultado: 16:45:32</code></pre>

        <pre><code>$hora = date("h:i A");
echo $hora; // Resultado: 04:45 PM</code></pre>

        <!-- =================== -->
        <!-- SECCIÓN: COMBINADOS -->
        <!-- =================== -->
        <h5 class="fw-semibold mt-5">Ejemplos de Fecha y Hora Combinadas</h5>
        <pre><code>$fechaHora = date("Y-m-d H:i:s");
echo $fechaHora; // Resultado: 2025-10-30 16:45:32</code></pre>

        <pre><code>echo date("l, d F Y - h:i A");
// Resultado: Jueves, 30 Octubre 2025 - 04:45 PM</code></pre>

        <pre><code>echo date("d/m/Y g:i a");
// Resultado: 30/10/2025 4:45 pm</code></pre>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const tzSelect = document.getElementById("site_timezone");
    const dateInput = document.getElementById("date_format");
    const timeInput = document.getElementById("time_format");
    const datetimeInput = document.getElementById("datetime_format");

    const dateExample = dateInput.nextElementSibling;
    const timeExample = timeInput.nextElementSibling;
    const datetimeExample = datetimeInput.nextElementSibling;

    /**
     * Devuelve la fecha/hora actual ajustada a la zona horaria seleccionada
     */
    function getDateInTimezone(timezone) {
      const now = new Date();
      const tzDateStr = now.toLocaleString('en-US', { timeZone: timezone });
      return new Date(tzDateStr);
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
     * Actualiza los ejemplos dinámicos
     */
    function updateExamples() {
      const tz = tzSelect.value || 'UTC';
      const now = getDateInTimezone(tz);

      const dateFormat = dateInput.value || 'd F, Y';
      const timeFormat = timeInput.value || 'H:i:s';
      const datetimeFormat = datetimeInput.value || 'd F, Y H:i';

      dateExample.textContent = 'Ejemplo: ' + phpDate(dateFormat, now);
      timeExample.textContent = 'Ejemplo: ' + phpDate(timeFormat, now);
      datetimeExample.textContent = 'Ejemplo: ' + phpDate(datetimeFormat, now);
    }

    // Escucha cambios en los campos
    [tzSelect, dateInput, timeInput, datetimeInput].forEach(el => {
      el.addEventListener("input", updateExamples);
      el.addEventListener("change", updateExamples);
    });

    // Actualiza inicialmente
    updateExamples();
  });
</script>