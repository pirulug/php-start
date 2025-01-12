<!-- Dark & Ligth-->
<div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle z-3">
  <button class="btn btn-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
    aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
    <span class="theme-icon-active mr-1" id="bd-theme-icon"><i class="fa fa-sun"></i></span>
    <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
  </button>
  <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
    <li>
      <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="light"
        aria-pressed="false">
        <i class="fa fa-sun opacity-50 me-2"></i>
        Light
        <i class="pr-check fa fa-check ms-auto d-none"></i>
      </button>
    </li>
    <li>
      <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="dark"
        aria-pressed="false">
        <i class="fa fa-moon opacity-50 me-2"></i>
        Dark
        <i class="pr-check fa fa-check ms-auto d-none"></i>
      </button>
    </li>
    <li>
      <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="auto"
        aria-pressed="true">
        <i class="fa fa-circle-half-stroke opacity-50 me-2"></i>
        Auto
        <i class="pr-check fa fa-check ms-auto d-none"></i>
      </button>
    </li>
  </ul>
</div>