<div class="container d-flex flex-column">
  <div class="row vh-100">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
      <div class="d-table-cell align-middle">
        <div class="card">
          <div class="card-body">
            <div class="m-sm-3">
              <h2 class="m-0">Sign In</h2>
              <p class="text-muted">Enter your username and password to login</p>
              <?= $notifier->showBootstrap(); ?>
              <form method="post">
                <div class="mb-3">
                  <label class="form-label">User</label>
                  <input class="form-control" type="text" name="user-name" placeholder="Enter your username">
                </div>
                <div class="mb-3">
                  <label class="form-label">Password</label>
                  <div class="input-group" id="show_hide_password">
                    <input class="form-control border-end-0" id="inputChoosePassword" type="password"
                      name="user-password" value="" placeholder="Enter your password">
                    <button class="input-group-text bg-transparent" id="togglePassword" type="button">
                      <i class="fa fa-eye-slash"></i>
                    </button>
                  </div>
                </div>
                <div>
                  <div class="form-check align-items-center">
                    <input class="form-check-input" id="customControlInline" type="checkbox" value="remember-me"
                      name="remember-me" checked="">
                    <label class="form-check-label text-small" for="customControlInline">Remember me</label>
                  </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                  <button class="btn btn-primary">Sign in</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const togglePassword = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("inputChoosePassword");
  const icon = togglePassword.querySelector("i");

  togglePassword.addEventListener("click", () => {
    // guardar selección actual
    let start = passwordInput.selectionStart;
    let end = passwordInput.selectionEnd;

    // cambiar tipo
    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";

    // asegurar foco
    passwordInput.focus();

    // si no hay selección previa, mover al final
    if (start === 0 && end === 0) {
      const len = passwordInput.value.length;
      passwordInput.setSelectionRange(len, len);
    } else {
      // restaurar selección previa
      passwordInput.setSelectionRange(start, end);
    }

    // alternar ícono
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
  });
</script>