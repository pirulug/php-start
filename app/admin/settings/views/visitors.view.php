<link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  .icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.2rem;
  }

  .bg-soft-primary {
    background-color: var(--primary-soft);
    color: #0d6efd;
  }

  .bg-soft-success {
    background-color: var(--success-soft);
    color: #198754;
  }

  .bg-soft-warning {
    background-color: var(--warning-soft);
    color: #ffc107;
  }

  .bg-soft-info {
    background-color: var(--info-soft);
    color: #0dcaf0;
  }

  /* Tablas Minimalistas */
  .table-modern thead th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8898aa;
    font-weight: 600;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
  }

  .table-modern td {
    vertical-align: middle;
    padding: 12px 8px;
    font-size: 0.9rem;
    border-bottom: 1px solid #f3f3f3;
  }

  .table-modern tr:last-child td {
    border-bottom: none;
  }

  /* Ajuste de Gráficos */
  .chart-container {
    position: relative;
    height: 250px;
    /* Altura uniforme */
    width: 100%;
  }

  /* Utilidad para textos pequeños */
  .text-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
  }
</style>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0 text-dark">Dashboard Analítico</h4>
      <p class="text-muted small mb-0">Vista general del rendimiento en tiempo real</p>
    </div>
    <button class="btn btn-primary  border" onclick="loadAnalytics()">
      <i class="fa-solid fa-rotate me-2"></i> Actualizar
    </button>
  </div>

  <div class="row g-4 mb-4" id="summary-cards">
    <div class="col-md-3 col-sm-6">
      <div class="card h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-box bg-soft-primary me-3">
            <i class="fa-solid fa-users"></i>
          </div>
          <div>
            <h3 class="fw-bold mb-0" id="totalVisitors">...</h3>
            <span class="text-label">Visitantes Totales</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-box bg-soft-success me-3">
            <i class="fa-regular fa-file-lines"></i>
          </div>
          <div>
            <h3 class="fw-bold mb-0" id="totalPages">...</h3>
            <span class="text-label">Páginas Vistas</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-box bg-soft-info me-3">
            <i class="fa-solid fa-stopwatch"></i>
          </div>
          <div>
            <h3 class="fw-bold mb-0" id="totalSessions">...</h3>
            <span class="text-label">Sesiones Totales</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-box bg-soft-warning me-3">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div>
            <h3 class="fw-bold mb-0" id="usersOnline">...</h3>
            <span class="text-label text-success">● En Línea Ahora</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Resumen de Tráfico</span>
          <i class="fa-solid fa-chart-pie text-muted"></i>
        </div>
        <div class="card-body">
          <div class="text-center mb-4 py-3 bg-light rounded-3 border border-light">
            <span class="d-block text-muted small mb-1">Visitantes Activos</span>
            <span class="display-4 fw-bold text-dark" id="summaryOnline">0</span>
          </div>
          <div class="table-responsive">
            <table class="table table-modern table-hover align-middle">
              <thead>
                <tr>
                  <th>Periodo</th>
                  <th class="text-center">Visitantes</th>
                  <th class="text-end">Visitas</th>
                </tr>
              </thead>
              <tbody id="summaryTraffic">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header">
          Evolución de Visitas
        </div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-12">
              <p class="text-label mb-2">Este Mes (Por Días)</p>
              <div class="chart-container" style="height: 200px;"><canvas id="chartMonthDays"></canvas></div>
            </div>
            <div class="col-md-6">
              <p class="text-label mb-2">Este Año (Por Meses)</p>
              <div class="chart-container" style="height: 180px;"><canvas id="chartYearMonths"></canvas></div>
            </div>
            <div class="col-md-6">
              <p class="text-label mb-2">Histórico (Últimos 10 Años)</p>
              <div class="chart-container" style="height: 180px;"><canvas id="chartYears"></canvas></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header">Tendencia (Últimos 30 días)</div>
        <div class="card-body chart-container">
          <canvas id="chartTrend"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header">Top 5 Páginas</div>
        <div class="card-body chart-container">
          <canvas id="chartPages"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header">Países</div>
        <div class="card-body chart-container position-relative">
          <canvas id="chartCountries"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header">Navegadores</div>
        <div class="card-body chart-container position-relative">
          <canvas id="chartBrowsers"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header">Sistemas Operativos</div>
        <div class="card-body chart-container position-relative">
          <canvas id="chartPlatforms"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">Dispositivos</div>
        <div class="card-body chart-container">
          <canvas id="chartDevices"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">Fuentes de Tráfico</div>
        <div class="card-body chart-container">
          <canvas id="chartReferers"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Usuarios Online (Detalle)</span>
          <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Live</span>
        </div>
        <div class="table-responsive">
          <table class="table table-modern table-hover mb-0" id="tableOnline">
            <thead class="bg-light">
              <tr>
                <th>IP</th>
                <th>País</th>
                <th>Navegador</th>
                <th>Plataforma</th>
                <th>Página Actual</th>
                <th>Última Actividad</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-header">Últimas Sesiones</div>
        <div class="table-responsive">
          <table class="table table-modern table-hover mb-0" id="tableSessions">
            <thead class="bg-light">
              <tr>
                <th>País</th>
                <th>Navegador</th>
                <th>Plataforma</th>
                <th>Página Inicial</th>
                <th>Hora</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>


<script>
  // =============================================
  // CONFIGURACIÓN ESTÉTICA GLOBAL (UI/UX)
  // =============================================
  Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
  Chart.defaults.color = '#8898aa';
  Chart.defaults.scale.grid.color = '#f3f3f3';

  // Opciones compartidas para limpiar los gráficos (Minimalismo)
  const commonOptions = {
    responsive: true,
    maintainAspectRatio: false, // Se adapta al contenedor CSS
    plugins: {
      legend: { display: false }, // Ocultamos leyenda por defecto (más limpio)
      tooltip: {
        backgroundColor: '#1e293b',
        padding: 12,
        titleFont: { size: 13 },
        bodyFont: { size: 13 },
        cornerRadius: 8,
        displayColors: true
      }
    },
    scales: {
      x: {
        grid: { display: false, drawBorder: false }, // Sin líneas verticales
        ticks: { font: { size: 11 } }
      },
      y: {
        beginAtZero: true,
        grid: { borderDash: [3, 3], drawBorder: false }, // Líneas horizontales punteadas suaves
        ticks: { padding: 10, font: { size: 11 } }
      }
    },
    elements: {
      line: { tension: 0.4, borderWidth: 3 }, // Curvas suaves (Smooth)
      point: { radius: 0, hoverRadius: 6 }, // Ocultar puntos hasta hacer hover
      bar: { borderRadius: 4 } // Barras redondeadas
    }
  };

  // Opciones específicas para gráficos circulares (Donut/Pie)
  const pieOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '75%', // Donut más fino
    plugins: {
      legend: { position: 'right', labels: { boxWidth: 10, usePointStyle: true, font: { size: 11 } } }
    },
    layout: { padding: 0 }
  };

  // =============================================
  // INICIALIZACIÓN DE GRÁFICOS
  // =============================================

  // 1. Tendencia (Lineal Suave)
  const ctxTrend = document.getElementById('chartTrend').getContext('2d');
  // Crear gradiente para efecto visual "Wow"
  const gradientTrend = ctxTrend.createLinearGradient(0, 0, 0, 400);
  gradientTrend.addColorStop(0, 'rgba(13, 110, 253, 0.2)');
  gradientTrend.addColorStop(1, 'rgba(13, 110, 253, 0)');

  const chartTrend = new Chart(ctxTrend, {
    type: 'line',
    data: {
      labels: [],
      datasets: [
        {
          label: 'Visitantes',
          data: [],
          borderColor: '#0d6efd',
          backgroundColor: gradientTrend,
          fill: true, // Relleno con gradiente
          tension: 0.4
        },
        {
          label: 'Visitas',
          data: [],
          borderColor: '#198754',
          borderDash: [5, 5], // Línea punteada para diferenciar
          tension: 0.4,
          fill: false
        }
      ]
    },
    options: {
      ...commonOptions,
      plugins: { ...commonOptions.plugins, legend: { display: true, align: 'end', labels: { usePointStyle: true, boxWidth: 8 } } },
      interaction: { mode: 'index', intersect: false }
    }
  });

  // 2. Páginas (Barras)
  const chartPages = new Chart(document.getElementById('chartPages'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: '#0d6efd' }] },
    options: commonOptions
  });

  // 3. Países (Donut)
  const chartCountries = new Chart(document.getElementById('chartCountries'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: generateColors(10), borderWidth: 0 }] },
    options: pieOptions
  });

  // 4. Navegadores (Pie - ajustado a Donut para consistencia visual)
  const chartBrowsers = new Chart(document.getElementById('chartBrowsers'), {
    type: 'doughnut', // Cambiado a doughnut para que se vea más moderno
    data: { labels: [], datasets: [{ data: [], backgroundColor: generateColors(8), borderWidth: 0 }] },
    options: pieOptions
  });

  // 5. Plataformas (Pie - ajustado a Donut)
  const chartPlatforms = new Chart(document.getElementById('chartPlatforms'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: generateColors(8), borderWidth: 0 }] },
    options: pieOptions
  });

  // 6. Dispositivos (Barra Vertical)
  const chartDevices = new Chart(document.getElementById('chartDevices'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Dispositivos', data: [], backgroundColor: '#ffc107', borderRadius: 4 }] },
    options: commonOptions
  });

  // 7. Referencias (Barra Horizontal)
  const chartReferers = new Chart(document.getElementById('chartReferers'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Referencias', data: [], backgroundColor: '#dc3545', borderRadius: 4 }] },
    options: {
      ...commonOptions,
      indexAxis: 'y', // Barra horizontal
    }
  });

  // --- GRÁFICOS DE TIEMPO (NUEVOS) ---

  const chartMonthDays = new Chart(document.getElementById('chartMonthDays'), {
    type: 'line',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], borderColor: '#0d6efd', tension: 0.4, fill: false }] },
    options: commonOptions
  });

  const chartYearMonths = new Chart(document.getElementById('chartYearMonths'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: '#0d6efd', borderRadius: 4 }] },
    options: commonOptions
  });

  const chartYears = new Chart(document.getElementById('chartYears'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: '#ffc107', borderRadius: 4 }] },
    options: commonOptions
  });


  // =============================================
  // FUNCIONES AUXILIARES
  // =============================================
  function generateColors(n) {
    // Genera colores pastel más agradables
    return Array.from({ length: n }, (_, i) => `hsl(${i * 40}, 65%, 65%)`);
  }

  const browserIcons = {
    chrome: 'fa-brands fa-chrome text-danger',
    firefox: 'fa-brands fa-firefox text-warning',
    safari: 'fa-brands fa-safari text-info',
    edge: 'fa-brands fa-edge text-primary',
    opera: 'fa-brands fa-opera text-danger',
    brave: 'fa-solid fa-shield-halved text-warning',
    'internet explorer': 'fa-brands fa-internet-explorer text-primary'
  };
  const osIcons = {
    windows: 'fa-brands fa-windows text-primary',
    mac: 'fa-brands fa-apple text-dark',
    ios: 'fa-solid fa-mobile-screen-button text-dark',
    android: 'fa-brands fa-android text-success',
    linux: 'fa-brands fa-linux text-secondary'
  };

  // --- Mapa país → código ISO2 ---
  const countryToCode = {
    // América del Sur
    'argentina': 'ar', 'bolivia': 'bo', 'brasil': 'br', 'brazil': 'br', 'chile': 'cl',
    'colombia': 'co', 'ecuador': 'ec', 'guyana': 'gy', 'paraguay': 'py',
    'peru': 'pe', 'suriname': 'sr', 'uruguay': 'uy', 'venezuela': 've', 'french guiana': 'gf',
    // América del Norte y Central
    'canada': 'ca', 'estados unidos': 'us', 'eeuu': 'us', 'usa': 'us', 'united states': 'us', 'mexico': 'mx',
    'belize': 'bz', 'costa rica': 'cr', 'el salvador': 'sv', 'guatemala': 'gt', 'honduras': 'hn',
    'nicaragua': 'ni', 'panama': 'pa', 'bahamas': 'bs', 'barbados': 'bb', 'cuba': 'cu',
    'dominican republic': 'do', 'republica dominicana': 'do', 'haiti': 'ht', 'jamaica': 'jm',
    'puerto rico': 'pr', 'trinidad y tobago': 'tt', 'trinidad and tobago': 'tt',
    // Europa Occidental
    'alemania': 'de', 'germany': 'de', 'austria': 'at', 'belgica': 'be', 'belgium': 'be',
    'dinamarca': 'dk', 'denmark': 'dk', 'espana': 'es', 'spain': 'es', 'finlandia': 'fi', 'finland': 'fi',
    'francia': 'fr', 'france': 'fr', 'irlanda': 'ie', 'ireland': 'ie', 'italia': 'it', 'italy': 'it',
    'luxemburgo': 'lu', 'luxembourg': 'lu', 'paises bajos': 'nl', 'netherlands': 'nl', 'noruega': 'no', 'norway': 'no',
    'portugal': 'pt', 'suiza': 'ch', 'switzerland': 'ch', 'reino unido': 'gb', 'uk': 'gb', 'united kingdom': 'gb',
    'inglaterra': 'gb', 'escocia': 'gb', 'gales': 'gb', 'islandia': 'is', 'iceland': 'is',
    // Europa del Este
    'albania': 'al', 'andorra': 'ad', 'armenia': 'am', 'azerbaiyan': 'az', 'azerbaijan': 'az',
    'bielorrusia': 'by', 'belarus': 'by', 'bosnia y herzegovina': 'ba', 'bulgaria': 'bg',
    'croacia': 'hr', 'croatia': 'hr', 'eslovaquia': 'sk', 'slovakia': 'sk', 'eslovenia': 'si', 'slovenia': 'si',
    'estonia': 'ee', 'georgia': 'ge', 'grecia': 'gr', 'greece': 'gr', 'hungria': 'hu', 'hungary': 'hu',
    'letonia': 'lv', 'latvia': 'lv', 'lituania': 'lt', 'lithuania': 'lt', 'moldavia': 'md', 'moldova': 'md',
    'montenegro': 'me', 'macedonia': 'mk', 'polonia': 'pl', 'poland': 'pl', 'republica checa': 'cz', 'czech republic': 'cz',
    'rumania': 'ro', 'romania': 'ro', 'rusia': 'ru', 'russia': 'ru', 'serbia': 'rs', 'ucrania': 'ua', 'ukraine': 'ua',
    // África
    'argelia': 'dz', 'algeria': 'dz', 'angola': 'ao', 'benin': 'bj', 'botswana': 'bw', 'burkina faso': 'bf',
    'burundi': 'bi', 'cabo verde': 'cv', 'cape verde': 'cv', 'camerun': 'cm', 'cameroon': 'cm', 'chad': 'td',
    'comoras': 'km', 'comoros': 'km', 'congo': 'cg', 'congo republic': 'cg', 'republica democratica del congo': 'cd',
    'democratic republic of the congo': 'cd', 'costa de marfil': 'ci', 'ivory coast': 'ci', 'djibouti': 'dj',
    'egipto': 'eg', 'egypt': 'eg', 'eritrea': 'er', 'etiopia': 'et', 'ethiopia': 'et', 'gabon': 'ga', 'gambia': 'gm',
    'ghana': 'gh', 'guinea': 'gn', 'guinea ecuatorial': 'gq', 'equatorial guinea': 'gq', 'kenia': 'ke', 'kenya': 'ke',
    'lesoto': 'ls', 'liberia': 'lr', 'libia': 'ly', 'madagascar': 'mg', 'malawi': 'mw', 'mali': 'ml', 'mauritania': 'mr',
    'mauricio': 'mu', 'mauritius': 'mu', 'mozambique': 'mz', 'namibia': 'na', 'niger': 'ne', 'nigeria': 'ng',
    'ruanda': 'rw', 'rwanda': 'rw', 'santo tome y principe': 'st', 'sao tome and principe': 'st', 'senegal': 'sn',
    'seychelles': 'sc', 'sierra leona': 'sl', 'somalia': 'so', 'sudafrica': 'za', 'south africa': 'za', 'sudan': 'sd',
    'sudan del sur': 'ss', 'south sudan': 'ss', 'tanzania': 'tz', 'togo': 'tg', 'tunisia': 'tn', 'uganda': 'ug',
    'zambia': 'zm', 'zimbabue': 'zw', 'zimbabwe': 'zw',
    // Asia
    'afganistan': 'af', 'afghanistan': 'af', 'arabia saudita': 'sa', 'saudi arabia': 'sa', 'armenia': 'am',
    'azerbaijan': 'az', 'bahrein': 'bh', 'bahrain': 'bh', 'bangladesh': 'bd', 'brunei': 'bn', 'camboya': 'kh',
    'cambodia': 'kh', 'china': 'cn', 'chipre': 'cy', 'cyprus': 'cy', 'corea del norte': 'kp', 'north korea': 'kp',
    'corea del sur': 'kr', 'south korea': 'kr', 'emiratos arabes unidos': 'ae', 'uae': 'ae', 'filipinas': 'ph',
    'philippines': 'ph', 'india': 'in', 'indonesia': 'id', 'iran': 'ir', 'iraq': 'iq', 'israel': 'il', 'japan': 'jp',
    'jordania': 'jo', 'kazajistan': 'kz', 'kazakhstan': 'kz', 'kirguistan': 'kg', 'kyrgyzstan': 'kg', 'kuwait': 'kw',
    'laos': 'la', 'libano': 'lb', 'lebanon': 'lb', 'malasia': 'my', 'malaysia': 'my', 'maldivas': 'mv', 'maldives': 'mv',
    'mongolia': 'mn', 'myanmar': 'mm', 'nepal': 'np', 'oman': 'om', 'pakistan': 'pk', 'qatar': 'qa', 'singapur': 'sg',
    'singapore': 'sg', 'siria': 'sy', 'syria': 'sy', 'sri lanka': 'lk', 'tailandia': 'th', 'thailand': 'th',
    'timor oriental': 'tl', 'east timor': 'tl', 'turquia': 'tr', 'turkey': 'tr', 'vietnam': 'vn', 'yemen': 'ye',
    // Oceanía
    'australia': 'au', 'fiyi': 'fj', 'fiji': 'fj', 'kiribati': 'ki', 'islas marshall': 'mh', 'micronesia': 'fm',
    'nauru': 'nr', 'nueva zelanda': 'nz', 'new zealand': 'nz', 'palaos': 'pw', 'papua nueva guinea': 'pg',
    'papua new guinea': 'pg', 'samoa': 'ws', 'salomon': 'sb', 'solomon islands': 'sb', 'tonga': 'to', 'tuvalu': 'tv',
    'vanuatu': 'vu',
    // Otros / territorios
    'hong kong': 'hk', 'macao': 'mo', 'taiwan': 'tw', 'palestina': 'ps', 'palestine': 'ps', 'vaticano': 'va',
    'holy see': 'va',
    // Casos especiales
    'localhost': 'xx', 'desconocido': 'xx', 'unknown': 'xx'
  };

  function getBrowserIcon(b) {
    b = b?.toLowerCase() || '';
    const key = Object.keys(browserIcons).find(k => b.includes(k));
    return `<i class="${key ? browserIcons[key] : 'fa-solid fa-globe text-muted'}"></i>`;
  }
  function getOSIcon(p) {
    p = p?.toLowerCase() || '';
    const key = Object.keys(osIcons).find(k => p.includes(k));
    return `<i class="${key ? osIcons[key] : 'fa-solid fa-desktop text-muted'}"></i>`;
  }
  function getFlag(country) {
    if (!country) return '<span class="fi fi-xx  rounded-1"></span>';
    const code = countryToCode[country.toLowerCase()] || 'xx';
    return `<span class="fi fi-${code}  rounded-1"></span>`;
  }

  // =============================================
  // CARGA DE DATOS
  // =============================================
  async function loadAnalytics() {
    try {
      const res = await fetch('<?= SITE_URL ?>/ajax/visitors');
      const data = await res.json();

      // 1. KPIs
      document.getElementById('totalVisitors').textContent = data.totals.visitors.toLocaleString();
      document.getElementById('totalPages').textContent = data.totals.pages.toLocaleString();
      document.getElementById('totalSessions').textContent = data.totals.sessions.toLocaleString();
      document.getElementById('usersOnline').textContent = data.totals.online.toLocaleString();
      document.getElementById('summaryOnline').textContent = data.totals.online ?? 0;

      // 2. Gráficos
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

      // 3. Tablas (Usando template literals limpios)
      const sessionsBody = document.querySelector('#tableSessions tbody');
      if (data.recentSessions.length > 0) {
        sessionsBody.innerHTML = data.recentSessions.map(s => `
            <tr>
                <td><div class="d-flex align-items-center gap-2">${getFlag(s.visitor_country)} <span class="fw-medium">${s.visitor_country ?? 'Desconocido'}</span></div></td>
                <td>${getBrowserIcon(s.visitor_browser)} <span class="text-muted ms-1">${s.visitor_browser ?? '-'}</span></td>
                <td>${getOSIcon(s.visitor_platform)} <span class="text-muted ms-1">${s.visitor_platform ?? '-'}</span></td>
                <td><span class="text-truncate d-inline-block" style="max-width: 150px;">${s.visitor_sessions_start_page ?? '-'}</span></td>
                <td class="text-end text-muted small">${s.visitor_sessions_start_time}</td>
            </tr>
            `).join('');
      } else {
        sessionsBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Sin datos recientes</td></tr>';
      }

      const onlineBody = document.querySelector('#tableOnline tbody');
      if (data.onlineUsers.length > 0) {
        onlineBody.innerHTML = data.onlineUsers.map(o => `
            <tr>
                <td><span class="font-monospace text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small">${o.visitor_useronline_ip}</span></td>
                <td>${getFlag(o.visitor_country)}</td>
                <td>${getBrowserIcon(o.visitor_browser)}</td>
                <td>${getOSIcon(o.visitor_platform)}</td>
                <td><span class="text-truncate d-inline-block" style="max-width: 200px;">${o.visitor_pages_title ?? '—'}</span></td>
                <td class="text-end text-success small">Hace un momento</td>
            </tr>
            `).join('');
      } else {
        onlineBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">No hay usuarios online</td></tr>';
      }

      // 4. Tabla Resumen Tráfico
      const resumen = [
        { label: 'Hoy', v: data.summary.today },
        { label: 'Ayer', v: data.summary.yesterday },
        { label: 'Esta Semana', v: data.summary.thisWeek },
        { label: 'Este Mes', v: data.summary.thisMonth },
        { label: 'Total Histórico', v: data.summary.total },
      ];

      const tbody = document.getElementById('summaryTraffic');
      tbody.innerHTML = resumen.map(r => `
            <tr>
                <td class="text-start fw-medium text-secondary">${r.label}</td>
                <td class="text-center fw-bold text-dark">${r.v.visitors ?? 0}</td>
                <td class="text-end text-muted">${r.v.visits ?? 0}</td>
            </tr>
        `).join('');

    } catch (error) {
      console.error("Error cargando analíticas:", error);
    }
  }

  // Carga inicial y refresco automático
  loadAnalytics();
  setInterval(loadAnalytics, 30000);
</script>