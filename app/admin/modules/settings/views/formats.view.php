<?php start_block('title') ?>
Formats
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Settings'],
  ['label' => 'Formats']
]) ?>
<?php end_block(); ?>

<?php start_block('css') ?>
<?php end_block() ?>

<?php start_block('js') ?>
<?= url_script_admin('settings', 'formats') ?>
<?php end_block() ?>

<form action="" method="POST" id="settingsForm">

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0 d-flex align-items-center gap-2">
        <i class="fa-regular fa-clock text-primary"></i>
        Configuración Regional
      </h5>

    </div>

    <div class="card-body">
      <div class="mb-3">
        <label for="site_timezone" class="form-label fw-bold text-body-emphasis">Zona Horaria del Sistema</label>
        <div class="input-group">
          <select class="form-select" id="site_timezone" name="site_timezone">
            <?php echo select_timezone($config->site_timezone ?? 'UTC'); ?>
          </select>
        </div>
        <div class="form-text text-body-secondary">
          Esta configuración afecta cómo se guardan y muestran las fechas en todo el sistema.
        </div>
      </div>

      <hr class="border-secondary-subtle my-3">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-body-emphasis mb-0">Formatos de Fecha y Hora</h6>
        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
          data-bs-target="#datetimeFormatModal">
          <i class="fa-solid fa-circle-question me-1"></i> Ver códigos de formato
        </button>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label for="date_format" class="form-label">Fecha</label>
          <input type="text" class="form-control font-monospace" id="date_format" name="date_format"
            value="<?= $config->date_format ?>" placeholder="d F, Y">
          <div class="mt-2 p-2 rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_date" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>

        <div class="col-md-6">
          <label for="time_format" class="form-label">Hora</label>
          <input type="text" class="form-control font-monospace" id="time_format" name="time_format"
            value="<?= $config->time_format ?>" placeholder="h:i a">
          <div class="mt-2 p-2 rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_time" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>

        <div class="col-12">
          <label for="datetime_format" class="form-label">Fecha y Hora Completa</label>
          <input type="text" class="form-control font-monospace" id="datetime_format" name="datetime_format"
            value="<?= $config->datetime_format ?>" placeholder="d M, Y - h:i a">
          <div class="mt-2 p-2 rounded border small text-muted d-flex align-items-center">
            <span class="badge bg-secondary me-2">Vista Previa</span>
            <span id="preview_datetime" class="fw-medium text-body-emphasis">Cargando...</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-header">
      <h5 class="card-title mb-0 d-flex align-items-center gap-2">
        <i class="fa-solid fa-hashtag text-primary"></i>
        Formato de Números
      </h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label for="number_decimal_sep" class="form-label">Separador Decimal</label>
          <select class="form-select" id="number_decimal_sep" name="number_decimal_sep">
            <option value="." <?= $config->get('number_decimal_sep', '.') === '.' ? 'selected' : '' ?>>Punto (.)</option>
            <option value="," <?= $config->get('number_decimal_sep', '.') === ',' ? 'selected' : '' ?>>Coma (,)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="number_thousand_sep" class="form-label">Separador Miles</label>
          <select class="form-select" id="number_thousand_sep" name="number_thousand_sep">
            <option value=" " <?= $config->get('number_thousand_sep', ' ') === ' ' ? 'selected' : '' ?>>Espacio ( )</option>
            <option value="." <?= $config->get('number_thousand_sep', ' ') === '.' ? 'selected' : '' ?>>Punto (.)</option>
            <option value="," <?= $config->get('number_thousand_sep', ' ') === ',' ? 'selected' : '' ?>>Coma (,)</option>
            <option value="'" <?= $config->get('number_thousand_sep', ' ') === "'" ? 'selected' : '' ?>>Apóstrofe (')</option>
            <option value="" <?= $config->get('number_thousand_sep', ' ') === '' ? 'selected' : '' ?>>Ninguno</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="number_decimals" class="form-label">Cantidad Decimales</label>
          <select class="form-select" id="number_decimals" name="number_decimals">
            <?php for($i=0; $i<=4; $i++): ?>
              <option value="<?= $i ?>" <?= (int)$config->get('number_decimals', 2) === $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Vista Previa (Inteligente)</label>
          <div class="p-2 rounded border fw-bold text-body-emphasis" id="preview_number">
            <?= format_number(1250.50) ?>
          </div>
          <div class="form-text small">Función <code>format_number()</code>: decimales solo si existen.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Vista Previa (Forzar Decimal)</label>
          <div class="p-2 rounded border fw-bold text-body-emphasis" id="preview_number_decimal">
            <?= format_number_decimal(1250) ?>
          </div>
          <div class="form-text small">Función <code>format_number_decimal()</code>: siempre muestra decimales.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-header">
      <h5 class="card-title mb-0 d-flex align-items-center gap-2">
        <i class="fa-solid fa-coins text-primary"></i>
        Formato de Moneda
      </h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label for="currency_symbol" class="form-label">Símbolo</label>
          <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
            value="<?= htmlspecialchars($config->get('currency_symbol', '$')) ?>">
        </div>
        <div class="col-md-4">
          <label for="currency_position" class="form-label">Posición del Símbolo</label>
          <select class="form-select" id="currency_position" name="currency_position">
            <option value="before" <?= $config->get('currency_position', 'before') === 'before' ? 'selected' : '' ?>>Antes (<?= htmlspecialchars($config->get('currency_symbol', '$')) ?> 100)</option>
            <option value="after" <?= $config->get('currency_position', 'before') === 'after' ? 'selected' : '' ?>>Después (100 <?= htmlspecialchars($config->get('currency_symbol', '$')) ?>)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="currency_decimals" class="form-label">Decimales</label>
          <select class="form-select" id="currency_decimals" name="currency_decimals">
            <?php for($i=0; $i<=4; $i++): ?>
              <option value="<?= $i ?>" <?= (int)$config->get('currency_decimals', 2) === $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="currency_decimal_sep" class="form-label">Separador Decimal</label>
          <select class="form-select" id="currency_decimal_sep" name="currency_decimal_sep">
            <option value="." <?= $config->get('currency_decimal_sep', '.') === '.' ? 'selected' : '' ?>>Punto (.)</option>
            <option value="," <?= $config->get('currency_decimal_sep', '.') === ',' ? 'selected' : '' ?>>Coma (,)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="currency_thousand_sep" class="form-label">Separador Miles</label>
          <select class="form-select" id="currency_thousand_sep" name="currency_thousand_sep">
            <option value="," <?= $config->get('currency_thousand_sep', ',') === ',' ? 'selected' : '' ?>>Coma (,)</option>
            <option value="." <?= $config->get('currency_thousand_sep', ',') === '.' ? 'selected' : '' ?>>Punto (.)</option>
            <option value=" " <?= $config->get('currency_thousand_sep', ',') === ' ' ? 'selected' : '' ?>>Espacio ( )</option>
            <option value="'" <?= $config->get('currency_thousand_sep', ',') === "'" ? 'selected' : '' ?>>Apóstrofe (')</option>
            <option value="" <?= $config->get('currency_thousand_sep', ',') === '' ? 'selected' : '' ?>>Ninguno</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Vista Previa</label>
          <div class="p-2 rounded border fw-bold text-primary" id="preview_currency">
            <?= format_money(1250.50) ?>
          </div>
          <div class="form-text small">Función <code>format_money()</code>: aplica símbolo, posición y separadores.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-body mt-3 p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <a href="<?= admin_route('dashboard') ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
      <i class="fa-solid fa-arrow-left me-2"></i>
      Cancelar
    </a>
    <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
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