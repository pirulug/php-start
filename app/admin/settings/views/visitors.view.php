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
      <thead >
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
      <thead >
        <tr>
          <th>IP</th>
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

  async function loadAnalytics() {
    const res = await fetch('<?= SITE_URL ?>/ajax/visitors');
    const data = await res.json();

    // Actualizar totales
    document.getElementById('totalVisitors').textContent = data.totals.visitors.toLocaleString();
    document.getElementById('totalPages').textContent = data.totals.pages.toLocaleString();
    document.getElementById('totalSessions').textContent = data.totals.sessions.toLocaleString();
    document.getElementById('usersOnline').textContent = data.totals.online.toLocaleString();

    // Actualizar chart de páginas
    chartPages.data.labels = data.topPages.map(p => p.title);
    chartPages.data.datasets[0].data = data.topPages.map(p => p.views);
    chartPages.update();

    // Actualizar chart de países
    chartCountries.data.labels = data.countries.map(c => c.country);
    chartCountries.data.datasets[0].data = data.countries.map(c => c.total);
    chartCountries.update();

    // Actualizar tablas
    const sessionsBody = document.querySelector('#tableSessions tbody');
    sessionsBody.innerHTML = data.recentSessions.map(s => `
        <tr>
          <td>${s.visitor_country ?? '-'}</td>
          <td>${s.visitor_browser}</td>
          <td>${s.visitor_platform}</td>
          <td>${s.visitor_sessions_start_page}</td>
          <td>${s.visitor_sessions_start_time}</td>
        </tr>`).join('');

    const onlineBody = document.querySelector('#tableOnline tbody');
    onlineBody.innerHTML = data.onlineUsers.map(o => `
        <tr>
          <td>${o.visitor_useronline_ip}</td>
          <td>${o.visitor_pages_title ?? '—'}</td>
          <td>${o.visitor_useronline_last_activity}</td>
        </tr>`).join('');
  }

  // Cargar al inicio y cada 30 segundos
  loadAnalytics();
  setInterval(loadAnalytics, 30000);
</script>