<?php start_block("title"); ?>
SweetAlert2
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes'],
  ['label' => 'SweetAlert2']
]) ?>
<?php end_block(); ?>

<div class="row">
  <div class="col-md-6 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="card-title">Confirmación de Acción (Link)</h5>
      </div>
      <div class="card-body">
        <p class="text-muted small">Utiliza <code>ActionBtn::delete()</code> para acciones críticas que requieren confirmación antes de navegar.</p>
        <?php 
          echo ActionBtn::delete(admin_route('componentes/sweetalert'))
              ->saTitle('¿Deseas reiniciar la demo?')
              ->saText('Esta acción simulará una redirección de eliminación.')
              ->text('Prueba de Link/Eliminación')
              ->render();
        ?>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="card-title">Confirmación de Formulario</h5>
      </div>
      <div class="card-body">
        <p class="text-muted small">Intercepta el envío de un formulario usando el atributo <code>sa-form-id</code>.</p>
        
        <form id="demoForm" action="<?= admin_route('componentes/sweetalert') ?>" method="POST">
           <button type="button" 
                   class="btn btn-primary"
                   sa-title="¿Enviar Formulario?"
                   sa-text="Se procesarán los datos del formulario tras tu confirmación."
                   sa-icon="question"
                   sa-form-id="demoForm">
               <i class="fa fa-paper-plane me-1"></i> Enviar con Confirmación
           </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title">Tipos de Alerta e Interacción</h5>
      </div>
      <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
          <!-- Alerta de Éxito -->
          <button type="button" class="btn btn-success" 
                  sa-title="¡Buen trabajo!" 
                  sa-text="La operación se realizó con éxito." 
                  sa-icon="success">
            <i class="fa fa-check me-1"></i> Éxito
          </button>

          <!-- Alerta de Error -->
          <button type="button" class="btn btn-danger" 
                  sa-title="Error detectado" 
                  sa-text="No se pudo completar la solicitud." 
                  sa-icon="error">
            <i class="fa fa-times me-1"></i> Error
          </button>

          <!-- Alerta de Advertencia -->
          <button type="button" class="btn btn-warning" 
                  sa-title="Atención" 
                  sa-text="Revisa los datos antes de continuar." 
                  sa-icon="warning">
            <i class="fa fa-exclamation-triangle me-1"></i> Advertencia
          </button>
          
          <!-- Alerta con Timer -->
          <button type="button" class="btn btn-info" 
                  sa-title="Auto-cierre" 
                  sa-text="Esta ventana se cerrará en 3 segundos." 
                  sa-icon="info"
                  sa-timer="3000"
                  sa-show-confirm-btn="false">
            <i class="fa fa-clock me-1"></i> Con Timer
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Demo de Alerta Automática (Flash)</h5>
        <p class="small">Al hacer clic en el siguiente botón se inyectará un elemento <code>data-sa-flash</code> al DOM para demostrar la detección automática del script <code>sa.js</code>.</p>
        <button type="button" class="btn btn-dark" onclick="simulateFlash()">
            Simular Mensaje de Sesión (Flash)
        </button>
      </div>
    </div>
  </div>
</div>

<?php start_block("js"); ?>
<script>
/**
 * Simulación de mensaje flash inyectado dinámicamente
 */
function simulateFlash() {
    const div = document.createElement('div');
    div.setAttribute('data-sa-flash', '');
    div.setAttribute('data-sa-type', 'success');
    div.setAttribute('data-sa-title', 'Sincronización Exitosa');
    div.setAttribute('data-sa-text', 'El script detectó este nuevo elemento y disparó la alerta global.');
    document.body.appendChild(div);
    
    // Disparar la comprobación manual para la demo
    if (typeof PiruSA !== 'undefined') {
        PiruSA.checkFlashMessages();
    }
    div.remove();
}
</script>
<?php end_block(); ?>
