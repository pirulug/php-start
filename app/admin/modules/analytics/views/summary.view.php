<?php start_block('title'); ?>
Resumen de Analítica
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Analítica']
]) ?>
<?php end_block(); ?>

<?php start_block("css"); ?>
<link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" rel="stylesheet">
<style>
  .kpi-card {
    transition: transform 0.3s ease;
  }
  .kpi-card:hover {
    transform: translateY(-5px);
  }
  .kpi-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 1.4rem;
    flex-shrink: 0;
  }
  
  .pulse-live {
    width: 10px;
    height: 10px;
    background: #2ecc71;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
    box-shadow: 0 0 0 rgba(46, 204, 113, 0.4);
    animation: pulse-green 2s infinite;
  }
  @keyframes pulse-green {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
  }

  .chart-card {
    overflow: hidden;
  }
  .chart-container {
    position: relative;
    height: 300px;
    width: 100%;
  }
  
  .premium-table thead th {
    background-color: transparent;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
  }
  [data-bs-theme="dark"] .premium-table thead th {
    border-bottom-color: rgba(255,255,255,0.05);
  }
</style>
<?php end_block(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">Dashboard de Analítica</h4>
    <p class="text-muted small mb-0">Visualiza el rendimiento de tu sitio en tiempo real.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-outline-secondary px-3 fw-bold text-uppercase small" onclick="resolverPaisesDesconocidos()">
      <i class="fa-solid fa-earth-americas me-2"></i> Paises
    </button>
    <button class="btn btn-primary px-3 fw-bold text-uppercase small" onclick="loadAnalytics()">
      <i class="fa-solid fa-rotate me-2"></i> Actualizar
    </button>
  </div>
</div>

<div class="row g-3 mb-3" id="summary-cards">
  <!-- Visitantes -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-icon mb-3" style="background-color: rgba(255, 0, 85, 0.1); color: #f05;">
          <i class="fa-solid fa-users"></i>
        </div>
        <h3 class="fw-bold mb-1" id="totalVisitors">...</h3>
        <span class="text-muted small fw-bold text-uppercase">Visitantes</span>
      </div>
    </div>
  </div>
  <!-- Vistas -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-icon mb-3" style="background-color: rgba(46, 204, 113, 0.1); color: #2ecc71;">
          <i class="fa-regular fa-file-lines"></i>
        </div>
        <h3 class="fw-bold mb-1" id="totalPages">...</h3>
        <span class="text-muted small fw-bold text-uppercase">Vistas</span>
      </div>
    </div>
  </div>
  <!-- Duración -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-icon mb-3" style="background-color: rgba(52, 152, 219, 0.1); color: #3498db;">
          <i class="fa-solid fa-stopwatch"></i>
        </div>
        <h3 class="fw-bold mb-1" id="avgDuration">...</h3>
        <span class="text-muted small fw-bold text-uppercase">Duración</span>
      </div>
    </div>
  </div>
  <!-- Rebote -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-icon mb-3" style="background-color: rgba(231, 76, 60, 0.1); color: #e74c3c;">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <h3 class="fw-bold mb-1" id="bounceRate">...</h3>
        <span class="text-muted small fw-bold text-uppercase">Rebote</span>
      </div>
    </div>
  </div>
  <!-- Online -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="kpi-icon" style="background: rgba(255,255,255,0.2); color: #fff;">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <span class="badge bg-success-subtle text-success fw-bold small">LIVE</span>
        </div>
        <h3 class="fw-bold mb-1 text-white" id="usersOnline">...</h3>
        <span class="small fw-bold text-uppercase text-white opacity-75">En Línea</span>
      </div>
    </div>
  </div>
  <!-- Sesiones -->
  <div class="col-md-2 col-sm-6">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-icon mb-3" style="background-color: rgba(241, 196, 15, 0.1); color: #f1c40f;">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <h3 class="fw-bold mb-1" id="totalSessions">...</h3>
        <span class="text-muted small fw-bold text-uppercase">Sesiones</span>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-4">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Resumen de Tráfico</h6>
      </div>
      <div class="card-body px-3">
        <div class="text-center mb-3 p-3 rounded" style="background-color: rgba(240, 5, 85, 0.05); border: 1px solid rgba(240, 5, 85, 0.1);">
          <span class="pulse-live"></span>
          <span class="text-muted small fw-bold text-uppercase">Visitantes Activos</span>
          <h1 class="display-4 fw-bold mb-0" style="color: #f05;" id="summaryOnline">0</h1>
        </div>
        <div class="table-responsive">
          <table class="table premium-table align-middle">
            <thead>
              <tr>
                <th>Periodo</th>
                <th class="text-center">Vence</th>
                <th class="text-end">Visitas</th>
              </tr>
            </thead>
            <tbody id="summaryTraffic" class="small">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3 d-flex justify-content-between">
        <h6 class="fw-bold mb-1">Evolución de Visitas</h6>
        <span class="badge bg-primary-subtle text-primary fw-bold">TIEMPO REAL</span>
      </div>
      <div class="card-body px-3">
        <div class="row g-3">
          <div class="col-12">
            <p class="text-muted small fw-bold text-uppercase mb-2">Este Mes (Por Días)</p>
            <div class="chart-container" style="height: 200px;"><canvas id="chartMonthDays"></canvas></div>
          </div>
          <div class="col-md-6">
            <p class="text-muted small fw-bold text-uppercase mb-2">Este Año</p>
            <div class="chart-container" style="height: 180px;"><canvas id="chartYearMonths"></canvas></div>
          </div>
          <div class="col-md-6">
            <p class="text-muted small fw-bold text-uppercase mb-2">Histórico (10 Años)</p>
            <div class="chart-container" style="height: 180px;"><canvas id="chartYears"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-8">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent px-3 pt-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Tendencia (30 días)</h6>
        <div class="d-flex gap-2 small fw-bold">
           <span class="text-primary"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> VISITANTES</span>
           <span style="color: #2ecc71;"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> VISITAS</span>
        </div>
      </div>
      <div class="card-body px-3 pb-3 chart-container">
        <canvas id="chartTrend"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Top 5 Páginas</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container">
        <canvas id="chartPages"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-4">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Geolocalización</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container position-relative">
        <canvas id="chartCountries"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Navegadores</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container position-relative">
        <canvas id="chartBrowsers"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Plataformas</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container position-relative">
        <canvas id="chartPlatforms"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Tecnología</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container">
        <canvas id="chartDevices"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card chart-card h-100">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0">Fuentes de Tráfico</h6>
      </div>
      <div class="card-body px-3 pb-3 chart-container">
        <canvas id="chartReferers"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-xl-7">
    <div class="card chart-card">
      <div class="card-header bg-transparent px-3 pt-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-bolt me-2 text-success"></i>Usuarios Online (Detalle)</h6>
        <span class="badge bg-success-subtle text-success fw-bold px-3">LIVE</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table premium-table align-middle mb-0" id="tableOnline">
            <thead>
              <tr>
                <th class="ps-3">IP</th>
                <th>País</th>
                <th>Browser</th>
                <th>OS</th>
                <th>Página Actual</th>
                <th class="text-end pe-3">Actividad</th>
              </tr>
            </thead>
            <tbody class="small"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-5">
    <div class="card chart-card">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Últimas Sesiones</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table premium-table align-middle mb-0" id="tableSessions">
            <thead>
              <tr>
                <th class="ps-3">País</th>
                <th>Browser</th>
                <th>Página Inicial</th>
                <th class="text-end pe-3">Hora</th>
              </tr>
            </thead>
            <tbody class="small"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php start_block("js"); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
  const colors = {
    primary: '#f05',
    success: '#2ecc71',
    info: '#3498db',
    warning: '#f1c40f',
    danger: '#e74c3c',
    text: isDark ? '#adb5bd' : '#8898aa',
    grid: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
    tooltipBg: '#1a1a1a'
  };

  Chart.defaults.font.family = '"Inter", -apple-system, sans-serif';
  Chart.defaults.color = colors.text;
  Chart.defaults.scale.grid.color = colors.grid;

  const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: colors.tooltipBg,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 10,
        displayColors: true
      }
    },
    scales: {
      x: { grid: { display: false } },
      y: { 
        beginAtZero: true, 
        grid: { borderDash: [3, 3] },
        ticks: { stepSize: 5 }
      }
    },
    elements: {
      line: { tension: 0.45, borderWidth: 3 },
      point: { radius: 0, hoverRadius: 6 },
      bar: { borderRadius: 6 }
    }
  };

  const pieOptions = {
    ...commonOptions,
    cutout: '80%',
    plugins: {
      legend: { display: true, position: 'bottom', labels: { boxWidth: 8, usePointStyle: true, padding: 20 } }
    }
  };

  // INICIALIZACIÓN DE GRÁFICOS
  const ctxTrend = document.getElementById('chartTrend').getContext('2d');
  const gradientTrend = ctxTrend.createLinearGradient(0, 0, 0, 400);
  gradientTrend.addColorStop(0, 'rgba(255, 0, 85, 0.15)');
  gradientTrend.addColorStop(1, 'rgba(255, 0, 85, 0)');

  const chartTrend = new Chart(ctxTrend, {
    type: 'line',
    data: {
      labels: [],
      datasets: [
        { label: 'Visitantes', data: [], borderColor: colors.primary, backgroundColor: gradientTrend, fill: true },
        { label: 'Visitas', data: [], borderColor: colors.success, borderDash: [5, 5], fill: false }
      ]
    },
    options: {
      ...commonOptions,
      plugins: { ...commonOptions.plugins, legend: { display: false } }
    }
  });

  const chartPages = new Chart(document.getElementById('chartPages'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Vistas', data: [], backgroundColor: colors.info }] },
    options: commonOptions
  });

  const chartCountries = new Chart(document.getElementById('chartCountries'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: ['#f05', '#2ecc71', '#3498db', '#f1c40f', '#e74c3c'], borderWidth: 0 }] },
    options: pieOptions
  });

  const chartBrowsers = new Chart(document.getElementById('chartBrowsers'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: ['#3498db', '#f1c40f', '#e74c3c', '#2ecc71', '#9b59b6'], borderWidth: 0 }] },
    options: pieOptions
  });

  const chartPlatforms = new Chart(document.getElementById('chartPlatforms'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: ['#2c3e50', '#7f8c8d', '#bdc3c7', '#2980b9', '#16a085'], borderWidth: 0 }] },
    options: pieOptions
  });

  const chartDevices = new Chart(document.getElementById('chartDevices'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Total', data: [], backgroundColor: colors.warning }] },
    options: commonOptions
  });

  const chartReferers = new Chart(document.getElementById('chartReferers'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Referencia', data: [], backgroundColor: colors.danger }] },
    options: { ...commonOptions, indexAxis: 'y' }
  });

  const chartMonthDays = new Chart(document.getElementById('chartMonthDays'), {
    type: 'line',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], borderColor: colors.primary, fill: false }] },
    options: commonOptions
  });

  const chartYearMonths = new Chart(document.getElementById('chartYearMonths'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: colors.info }] },
    options: commonOptions
  });

  const chartYears = new Chart(document.getElementById('chartYears'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: colors.warning }] },
    options: commonOptions
  });

  // FUNCIONES DE SOPORTE E ICONOS
  const countryToCode = {
    'argentina': 'ar', 'bolivia': 'bo', 'brasil': 'br', 'brazil': 'br', 'chile': 'cl', 'colombia': 'co', 'ecuador': 'ec', 'peru': 'pe', 'venezuela': 've',
    'canada': 'ca', 'estados unidos': 'us', 'usa': 'us', 'mexico': 'mx', 'espana': 'es', 'spain': 'es', 'alemania': 'de', 'germany': 'de', 'francia': 'fr',
    'reino unido': 'gb', 'uk': 'gb', 'italia': 'it', 'portugal': 'pt'
  };

  const icons = {
    chrome: 'fa-brands fa-chrome text-danger',
    firefox: 'fa-brands fa-firefox text-warning',
    safari: 'fa-brands fa-safari text-info',
    edge: 'fa-brands fa-edge text-primary',
    windows: 'fa-brands fa-windows text-primary',
    apple: 'fa-brands fa-apple',
    android: 'fa-brands fa-android text-success',
    linux: 'fa-brands fa-linux text-secondary'
  };

  function getIcon(val, type = 'browser') {
    val = val?.toLowerCase() || '';
    const key = Object.keys(icons).find(k => val.includes(k));
    const fallback = type === 'browser' ? 'fa-solid fa-globe' : 'fa-solid fa-desktop';
    return `<i class="${key ? icons[key] : fallback + ' text-muted'}"></i>`;
  }

  function getFlag(country) {
    if (!country) return '<span class="fi fi-xx rounded-circle"></span>';
    const code = countryToCode[country.toLowerCase()] || 'xx';
    return `<span class="fi fi-${code} rounded-circle"></span>`;
  }

  // CARGA DE DATOS
  async function loadAnalytics() {
    try {
      const res = await fetch('<?= APP_URL ?>/ajax/visitors');
      const data = await res.json();

      document.getElementById('totalVisitors').textContent = data.totals.visitors.toLocaleString();
      document.getElementById('totalPages').textContent = data.totals.pages.toLocaleString();
      document.getElementById('totalSessions').textContent = data.totals.sessions.toLocaleString();
      document.getElementById('usersOnline').textContent = data.totals.online.toLocaleString();
      document.getElementById('bounceRate').textContent = data.totals.bounce_rate + '%';
      document.getElementById('avgDuration').textContent = data.totals.avg_duration;
      document.getElementById('summaryOnline').textContent = data.totals.online ?? 0;

      // Actualizar gráficos
      chartTrend.data.labels = data.trend.map(d => d.date);
      chartTrend.data.datasets[0].data = data.trend.map(d => d.visitors);
      chartTrend.data.datasets[1].data = data.trend.map(d => d.visits);
      chartTrend.update();

      chartPages.data.labels = data.topPages.map(p => p.title);
      chartPages.data.datasets[0].data = data.topPages.map(p => p.views);
      chartPages.update();

      chartCountries.data.labels = data.countries.map(c => c.country);
      chartCountries.data.datasets[0].data = data.countries.map(c => c.total);
      chartCountries.update();

      chartBrowsers.data.labels = data.browsers.map(b => b.browser);
      chartBrowsers.data.datasets[0].data = data.browsers.map(b => b.total);
      chartBrowsers.update();

      chartPlatforms.data.labels = data.platforms.map(p => p.platform);
      chartPlatforms.data.datasets[0].data = data.platforms.map(p => p.total);
      chartPlatforms.update();

      chartDevices.data.labels = data.devices.map(d => d.device);
      chartDevices.data.datasets[0].data = data.devices.map(d => d.total);
      chartDevices.update();

      chartReferers.data.labels = data.referers.map(r => r.referer);
      chartReferers.data.datasets[0].data = data.referers.map(r => r.total);
      chartReferers.update();

      chartMonthDays.data.labels = data.monthDays.map(d => d.day);
      chartMonthDays.data.datasets[0].data = data.monthDays.map(d => d.visits);
      chartMonthDays.update();

      chartYearMonths.data.labels = data.yearMonths.map(d => d.month);
      chartYearMonths.data.datasets[0].data = data.yearMonths.map(d => d.visits);
      chartYearMonths.update();

      chartYears.data.labels = data.lastYears.map(d => d.year);
      chartYears.data.datasets[0].data = data.lastYears.map(d => d.visits);
      chartYears.update();

      // Tablas
      document.querySelector('#tableSessions tbody').innerHTML = data.recentSessions.map(s => `
        <tr>
          <td class="ps-3"><div class="d-flex align-items-center gap-2">${getFlag(s.visitor_country)} <span>${s.visitor_country || 'Desconocido'}</span></div></td>
          <td>${getIcon(s.visitor_browser)} <span class="ms-1">${s.visitor_browser || '-'}</span></td>
          <td><span class="text-truncate d-inline-block" style="max-width: 150px;">${s.visitor_sessions_start_page || '-'}</span></td>
          <td class="text-end pe-3 text-muted">${s.visitor_session_start_time}</td>
        </tr>`).join('') || '<tr><td colspan="4" class="text-center py-3">Sin datos</td></tr>';

      document.querySelector('#tableOnline tbody').innerHTML = data.onlineUsers.map(o => `
        <tr>
          <td class="ps-3"><span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3">${o.visitor_useronline_ip}</span></td>
          <td>${getFlag(o.visitor_country)}</td>
          <td>${getIcon(o.visitor_browser)}</td>
          <td>${getIcon(o.visitor_platform, 'os')}</td>
          <td><span class="text-truncate d-inline-block" style="max-width: 180px;">${o.visitor_pages_title || '—'}</span></td>
          <td class="text-end pe-3"><span class="badge bg-success bg-opacity-10 text-success fw-bold">Online</span></td>
        </tr>`).join('') || '<tr><td colspan="6" class="text-center py-3">No hay usuarios online</td></tr>';

      const resumen = [
        { l: 'HOY', v: data.summary.today },
        { l: 'AYER', v: data.summary.yesterday },
        { l: 'SEMANA', v: data.summary.thisWeek },
        { l: 'MES', v: data.summary.thisMonth },
        { l: 'TOTAL', v: data.summary.total }
      ];
      document.getElementById('summaryTraffic').innerHTML = resumen.map(r => `
        <tr>
          <td class="fw-bold text-muted">${r.l}</td>
          <td class="text-center fw-bold">${(r.v.visitors || 0).toLocaleString()}</td>
          <td class="text-end text-primary fw-bold">${(r.v.visits || 0).toLocaleString()}</td>
        </tr>`).join('');

    } catch (e) {
      console.error("Error Analítica:", e);
    }
  }

  function resolverPaisesDesconocidos() {
    fetch('/ajax/country', { method: 'POST', body: JSON.stringify({ limit: 50 }) })
      .then(() => loadAnalytics());
  }

  loadAnalytics();
  setInterval(loadAnalytics, 30000);
</script>
<?php end_block(); ?>