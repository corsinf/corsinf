<?php //include('../cabeceras/header3.php'); 
?>

<style>
  .error-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .error-card {
    background: #fff;
    padding: 3rem;
    border-radius: 0.9rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: none;
    max-width: 600px;
    width: 90%;
    transition: transform 0.3s ease;
  }

  .error-card:hover {
    transform: translateY(-5px);
  }

  .icon-box {
    width: 100px;
    height: 100px;
    background: #fff5f5;
    color: #ff4d4d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    border-radius: 50%;
    margin: 0 auto 2rem;
    border: 2px solid #ffebeb;
  }

  .error-code {
    font-weight: 800;
    font-size: 5rem;
    line-height: 1;
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
  }

  .btn-custom {
    padding: 0.8rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <div class="error-container">
      <div class="error-card text-center">
        <div class="icon-box">
          <i class="bx bx-shield-x"></i>
        </div>

        <div class="error-code">403</div>

        <h2 class="fw-bold text-dark mb-3">Acceso Denegado</h2>
        <p class="text-muted mb-4 fs-5">
          No tienes permisos para acceder a esta página.
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
          <!-- <button onclick="window.history.back();" class="btn btn-light btn-custom border">
            <i class="bx bx-arrow-back me-1"></i> Volver atrás
          </button> -->

          <a href="index.php" class="btn btn-primary btn-custom shadow-sm">
            <i class="bx bx-home-alt me-1"></i> Ir al Inicio
          </a>
        </div>
        <hr>
        <div class="p-3 bg-light rounded-4 mt-4 text-center">
          <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="bx bx-help-circle fs-4 text-primary me-2"></i>
            <span class="small text-dark fw-bold">¿Necesitas ayuda técnica?</span>
          </div>

          <a href="mailto:soporte@corsinf.com?subject=Consulta%20Acceso%20Denegado"
            class="btn btn-outline-primary btn-sm px-3 rounded-pill mb-2">
            <i class="bx bx-envelope"></i> Enviar Correo a Soporte
          </a>

          <div class="small text-muted">
            O copia nuestro correo: <br>
            <code id="emailText" class="p-1 bg-white border rounded">soporte@corsinf.com</code>
            <button onclick="copyEmail()" class="btn btn-sm p-0 ms-1 text-primary" title="Copiar correo">
              <i class="bx bx-copy"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function copyEmail() {
    const email = "soporte@corsinf.com";
    navigator.clipboard.writeText(email).then(() => {
      alert("Correo copiado al portapapeles: " + email);
    });
  }
</script>

<?php //include('../cabeceras/footer.php'); 
?>