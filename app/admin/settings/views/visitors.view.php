<!-- Librería de banderas -->
<link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" rel="stylesheet">

<!-- Tarjetas resumen -->
<div class="row g-4 mb-4" id="summary-cards">
  <div class="col-md-3">
    <div class="card text-center bg-primary text-white">
      <div class="card-body">
        <h4 id="totalVisitors">...</h4>
        <p>Visitantes Totales</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-success text-white">
      <div class="card-body">
        <h4 id="totalPages">...</h4>
        <p>Páginas Monitorizadas</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-info text-white">
      <div class="card-body">
        <h4 id="totalSessions">...</h4>
        <p>Sesiones Totales</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-warning text-dark">
      <div class="card-body">
        <h4 id="usersOnline">...</h4>
        <p>Usuarios Online</p>
      </div>
    </div>
  </div>
</div>

<!-- Gráficos -->
<div class="row g-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header  fw-bold">Top 5 Páginas Más Visitadas</div>
      <div class="card-body chart-container"><canvas id="chartPages"></canvas></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header  fw-bold">Visitantes por País</div>
      <div class="card-body chart-container"><canvas id="chartCountries"></canvas></div>
    </div>
  </div>
</div>

<!-- Tablas -->
<div class="card mt-3">
  <div class="card-header fw-bold ">Últimas Sesiones</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0" id="tableSessions">
      <thead>
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

<div class="card mt-3">
  <div class="card-header fw-bold ">Usuarios Online</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0" id="tableOnline">
      <thead>
        <tr>
          <th>IP</th>
          <th>País</th>
          <th>Navegador</th>
          <th>Plataforma</th>
          <th>Página</th>
          <th>Última Actividad</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // ============================================================
  // Configuración de gráficos
  // ============================================================
  const chartPages = new Chart(document.getElementById('chartPages'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Visitas', data: [], backgroundColor: 'rgba(13,110,253,0.6)' }] },
    options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
  });

  const chartCountries = new Chart(document.getElementById('chartCountries'), {
    type: 'doughnut',
    data: { labels: [], datasets: [{ data: [], backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#20c997', '#6f42c1', '#fd7e14', '#0dcaf0', '#adb5bd', '#6610f2'] }] },
    options: { plugins: { legend: { position: 'bottom' } } }
  });

  // ============================================================
  // Mapas auxiliares
  // ============================================================
  // Mapa aproximado país → código ISO2
  const countryToCode = {
    // América del Sur
    'argentina': 'ar', 'bolivia': 'bo', 'brasil': 'br', 'brazil': 'br', 'chile': 'cl',
    'colombia': 'co', 'ecuador': 'ec', 'guyana': 'gy', 'paraguay': 'py',
    'peru': 'pe', 'suriname': 'sr', 'uruguay': 'uy', 'venezuela': 've',
    'french guiana': 'gf',

    // América del Norte y Central
    'canada': 'ca', 'estados unidos': 'us', 'eeuu': 'us', 'usa': 'us', 'united states': 'us', 'mexico': 'mx',
    'belize': 'bz', 'costa rica': 'cr', 'el salvador': 'sv', 'guatemala': 'gt', 'honduras': 'hn',
    'nicaragua': 'ni', 'panama': 'pa', 'bahamas': 'bs', 'barbados': 'bb', 'cuba': 'cu',
    'dominican republic': 'do', 'republica dominicana': 'do', 'haiti': 'ht', 'jamaica': 'jm',
    'puerto rico': 'pr', 'trinidad y tobago': 'tt', 'trinidad and tobago': 'tt',

    // Europa Occidental
    'alemania': 'de', 'germany': 'de', 'austria': 'at', 'belgica': 'be', 'belgium': 'be',
    'dinamarca': 'dk', 'denmark': 'dk', 'espana': 'es', 'spain': 'es',
    'finlandia': 'fi', 'finland': 'fi', 'francia': 'fr', 'france': 'fr',
    'irlanda': 'ie', 'ireland': 'ie', 'italia': 'it', 'italy': 'it',
    'luxemburgo': 'lu', 'luxembourg': 'lu', 'paises bajos': 'nl', 'netherlands': 'nl',
    'noruega': 'no', 'norway': 'no', 'portugal': 'pt', 'suiza': 'ch', 'switzerland': 'ch',
    'reino unido': 'gb', 'uk': 'gb', 'united kingdom': 'gb', 'inglaterra': 'gb',
    'escocia': 'gb', 'gales': 'gb', 'islandia': 'is', 'iceland': 'is',

    // Europa del Este
    'albania': 'al', 'andorra': 'ad', 'armenia': 'am', 'azerbaiyan': 'az', 'azerbaijan': 'az',
    'bielorrusia': 'by', 'belarus': 'by', 'bosnia y herzegovina': 'ba', 'bulgaria': 'bg',
    'croacia': 'hr', 'croatia': 'hr', 'eslovaquia': 'sk', 'slovakia': 'sk', 'eslovenia': 'si', 'slovenia': 'si',
    'estonia': 'ee', 'georgia': 'ge', 'grecia': 'gr', 'greece': 'gr',
    'hungria': 'hu', 'hungary': 'hu', 'letonia': 'lv', 'latvia': 'lv', 'lituania': 'lt', 'lithuania': 'lt',
    'moldavia': 'md', 'moldova': 'md', 'montenegro': 'me', 'macedonia': 'mk', 'polonia': 'pl', 'poland': 'pl',
    'republica checa': 'cz', 'czech republic': 'cz', 'rumania': 'ro', 'romania': 'ro',
    'rusia': 'ru', 'russia': 'ru', 'serbia': 'rs', 'ucrania': 'ua', 'ukraine': 'ua',

    // África
    'argelia': 'dz', 'algeria': 'dz', 'angola': 'ao', 'benin': 'bj', 'botswana': 'bw',
    'burkina faso': 'bf', 'burundi': 'bi', 'cabo verde': 'cv', 'cape verde': 'cv',
    'camerun': 'cm', 'cameroon': 'cm', 'chad': 'td', 'comoras': 'km', 'comoros': 'km',
    'congo': 'cg', 'congo republic': 'cg', 'republica democratica del congo': 'cd', 'democratic republic of the congo': 'cd',
    'costa de marfil': 'ci', 'ivory coast': 'ci', 'djibouti': 'dj', 'egipto': 'eg', 'egypt': 'eg',
    'eritrea': 'er', 'etiopia': 'et', 'ethiopia': 'et', 'gabon': 'ga', 'gambia': 'gm',
    'ghana': 'gh', 'guinea': 'gn', 'guinea ecuatorial': 'gq', 'equatorial guinea': 'gq',
    'kenia': 'ke', 'kenya': 'ke', 'lesoto': 'ls', 'liberia': 'lr', 'libia': 'ly', 'madagascar': 'mg',
    'malawi': 'mw', 'mali': 'ml', 'mauritania': 'mr', 'mauricio': 'mu', 'mauritius': 'mu',
    'mozambique': 'mz', 'namibia': 'na', 'niger': 'ne', 'nigeria': 'ng',
    'ruanda': 'rw', 'rwanda': 'rw', 'santo tome y principe': 'st', 'sao tome and principe': 'st',
    'senegal': 'sn', 'seychelles': 'sc', 'sierra leona': 'sl', 'somalia': 'so',
    'sudafrica': 'za', 'south africa': 'za', 'sudan': 'sd', 'sudan del sur': 'ss', 'south sudan': 'ss',
    'tanzania': 'tz', 'togo': 'tg', 'tunisia': 'tn', 'uganda': 'ug', 'zambia': 'zm', 'zimbabue': 'zw', 'zimbabwe': 'zw',

    // Asia
    'afganistan': 'af', 'afghanistan': 'af', 'arabia saudita': 'sa', 'saudi arabia': 'sa',
    'armenia': 'am', 'azerbaijan': 'az', 'bahrein': 'bh', 'bahrain': 'bh', 'bangladesh': 'bd',
    'brunei': 'bn', 'camboya': 'kh', 'cambodia': 'kh', 'china': 'cn', 'chipre': 'cy', 'cyprus': 'cy',
    'corea del norte': 'kp', 'north korea': 'kp', 'corea del sur': 'kr', 'south korea': 'kr',
    'emiratos arabes unidos': 'ae', 'uae': 'ae', 'filipinas': 'ph', 'philippines': 'ph',
    'india': 'in', 'indonesia': 'id', 'iran': 'ir', 'iraq': 'iq', 'israel': 'il', 'japan': 'jp', 'japan': 'jp',
    'jordania': 'jo', 'kazajistan': 'kz', 'kazakhstan': 'kz', 'kirguistan': 'kg', 'kyrgyzstan': 'kg',
    'kuwait': 'kw', 'laos': 'la', 'libano': 'lb', 'lebanon': 'lb', 'malasia': 'my', 'malaysia': 'my',
    'maldivas': 'mv', 'maldives': 'mv', 'mongolia': 'mn', 'myanmar': 'mm', 'nepal': 'np',
    'oman': 'om', 'pakistan': 'pk', 'qatar': 'qa', 'singapur': 'sg', 'singapore': 'sg',
    'siria': 'sy', 'syria': 'sy', 'sri lanka': 'lk', 'tailandia': 'th', 'thailand': 'th',
    'timor oriental': 'tl', 'east timor': 'tl', 'turquia': 'tr', 'turkey': 'tr', 'vietnam': 'vn', 'yemen': 'ye',

    // Oceanía
    'australia': 'au', 'fiyi': 'fj', 'fiji': 'fj', 'kiribati': 'ki', 'islas marshall': 'mh', 'micronesia': 'fm',
    'nauru': 'nr', 'nueva zelanda': 'nz', 'new zealand': 'nz', 'palaos': 'pw', 'papua nueva guinea': 'pg', 'papua new guinea': 'pg',
    'samoa': 'ws', 'salomon': 'sb', 'solomon islands': 'sb', 'tonga': 'to', 'tuvalu': 'tv', 'vanuatu': 'vu',

    // Otros / territorios
    'hong kong': 'hk', 'macao': 'mo', 'taiwan': 'tw', 'palestina': 'ps', 'palestine': 'ps',
    'vaticano': 'va', 'holy see': 'va',

    // Casos especiales
    'localhost': 'xx', 'desconocido': 'xx', 'unknown': 'xx'
  };

  function getFlagFromCountry(name = '') {
    const code = countryToCode[name.toLowerCase()] || 'xx';
    return `<span class="fi fi-${code}"></span>`;
  }

  // ============================================================
  // Iconos Font Awesome
  // ============================================================
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
    ios: 'fa-solid fa-mobile-screen-button text-secondary',
    android: 'fa-brands fa-android text-success',
    linux: 'fa-brands fa-linux text-secondary',
  };

  function getBrowserIcon(browserName = '') {
    browserName = browserName.toLowerCase();
    const key = Object.keys(browserIcons).find(k => browserName.includes(k));
    const icon = key ? browserIcons[key] : 'fa-solid fa-globe text-muted';
    return `<i class="${icon}"></i>`;
  }

  function getOSIcon(platform = '') {
    platform = platform.toLowerCase();
    const key = Object.keys(osIcons).find(k => platform.includes(k));
    const icon = key ? osIcons[key] : 'fa-solid fa-desktop text-muted';
    return `<i class="${icon}"></i>`;
  }

  // ============================================================
  // Carga de datos
  // ============================================================
  async function loadAnalytics() {
    const res = await fetch('<?= SITE_URL ?>/ajax/visitors');
    const data = await res.json();

    // Totales
    document.getElementById('totalVisitors').textContent = data.totals.visitors.toLocaleString();
    document.getElementById('totalPages').textContent = data.totals.pages.toLocaleString();
    document.getElementById('totalSessions').textContent = data.totals.sessions.toLocaleString();
    document.getElementById('usersOnline').textContent = data.totals.online.toLocaleString();

    // Chart páginas
    chartPages.data.labels = data.topPages.map(p => p.title);
    chartPages.data.datasets[0].data = data.topPages.map(p => p.views);
    chartPages.update();

    // Chart países
    chartCountries.data.labels = data.countries.map(c => c.country);
    chartCountries.data.datasets[0].data = data.countries.map(c => c.total);
    chartCountries.update();

    // Tabla sesiones
    const sessionsBody = document.querySelector('#tableSessions tbody');
    sessionsBody.innerHTML = data.recentSessions.map(s => `
        <tr>
          <td>${getFlagFromCountry(s.visitor_country)} ${s.visitor_country ?? '-'}</td>
          <td>${getBrowserIcon(s.visitor_browser)} ${s.visitor_browser}</td>
          <td>${getOSIcon(s.visitor_platform)} ${s.visitor_platform}</td>
          <td>${s.visitor_sessions_start_page}</td>
          <td>${s.visitor_sessions_start_time}</td>
        </tr>`).join('');

    // Tabla usuarios online
    const onlineBody = document.querySelector('#tableOnline tbody');
    onlineBody.innerHTML = data.onlineUsers.map(o => `
    <tr>
      <td>${o.visitor_useronline_ip}</td>
      <td>${getFlagFromCountry(o.visitor_country)} ${o.visitor_country ?? '-'}</td>
      <td>${getBrowserIcon(o.visitor_browser)} ${o.visitor_browser ?? '-'}</td>
      <td>${getOSIcon(o.visitor_platform)} ${o.visitor_platform ?? '-'}</td>
      <td>${o.visitor_pages_title ?? '—'}</td>
      <td>${o.visitor_useronline_last_activity}</td>
    </tr>`).join('');
  }

  loadAnalytics();
  setInterval(loadAnalytics, 30000);
</script>