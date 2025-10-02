<style>
  /* Bloqueo visual del contenido */
  .demo-locked {
    filter: blur(2px) grayscale(35%);
    pointer-events: none;
    user-select: none;
  }
  /* Tarjeta elegante estilo “demo” */
  .demo-card {
    border-radius: 1rem;
    background: #121317;
    color: #e9ecef;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 10px 40px rgba(0,0,0,0.35);
  }
  .demo-ribbon {
    position: absolute;
    top: 14px;
    right: -44px;
    transform: rotate(35deg);
    background: linear-gradient(90deg, #dc3545, #ff6b6b);
    color: #fff;
    padding: 6px 70px;
    font-weight: 700;
    letter-spacing: .05em;
    box-shadow: 0 4px 12px rgba(220,53,69,.35);
    border-radius: .25rem;
    font-size: .8rem;
  }
  .demo-svg {
    width: 108px; height: 108px;
  }
  .badge-soft {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #dee2e6;
  }
  .btn-outline-light:hover {
    color: #000;
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Demo</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Vista bloqueada (Demo)</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <!-- Tarjeta DEMO -->
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <div class="position-relative demo-card p-4 p-md-5 mb-4">
          <span class="demo-ribbon">DEMO</span>

          <div class="text-center d-flex flex-column align-items-center gap-3">
            <!-- Candado SVG -->
            <svg class="demo-svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke="url(#g1)" stroke-width="1.6" stroke-linecap="round"/>
              <rect x="4" y="10" width="16" height="10" rx="2.5" stroke="url(#g2)" stroke-width="1.6"/>
              <circle cx="12" cy="15" r="1.7" fill="#e9ecef"/>
              <path d="M12 16.7v2" stroke="#e9ecef" stroke-width="1.6" stroke-linecap="round"/>
              <defs>
                <linearGradient id="g1" x1="7" y1="7" x2="17" y2="7">
                  <stop stop-color="#0d6efd"/><stop offset="1" stop-color="#20c997"/>
                </linearGradient>
                <linearGradient id="g2" x1="4" y1="10" x2="20" y2="20">
                  <stop stop-color="#6f42c1"/><stop offset="1" stop-color="#dc3545"/>
                </linearGradient>
              </defs>
            </svg>

            <div>
              <h2 class="h4 h-md-3 mb-2">Esta vista no está disponible en modo demo</h2>
              <p class="mb-0 text-secondary">
                Para proteger datos reales y operaciones sensibles, el acceso está temporalmente restringido.
              </p>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center">
              <span class="badge rounded-pill badge-soft px-3 py-2"><i class="bx bx-shield-quarter me-1"></i> Seguridad</span>
              <span class="badge rounded-pill badge-soft px-3 py-2"><i class="bx bx-lock-alt me-1"></i> Datos protegidos</span>
              <span class="badge rounded-pill badge-soft px-3 py-2"><i class="bx bx-time me-1"></i> Versión demo</span>
            </div>

            <div class="d-flex gap-2 flex-wrap justify-content-center">
              <button type="button" class="btn btn-primary" disabled>
                <i class="bx bx-lock-alt me-1"></i> Solicitar acceso
              </button>
              <a class="btn btn-outline-light" href="">
                <i class="bx bx-arrow-back me-1"></i> Volver al inicio
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenido real (bloqueado visualmente) -->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary demo-locked">
          <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
              <h5 class="mb-0 text-primary">Gestión</h5>
              <div class="row mx-0 ms-auto">
                <div class="col-sm-12" id="btn_nuevo">
                  <button type="button" class="btn btn-success btn-sm" disabled>
                    <i class="bx bx-plus"></i> Nuevo
                  </button>
                </div>
              </div>
            </div>

            <section class="content pt-2">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped" id="tbl_blank" style="width:100%">
                    <thead class="table-dark">
                      <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>RFID</th>
                        <th>Stock</th>
                        <th>Inv</th>
                        <th width="10px">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                          Contenido oculto en modo demo
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div><!-- /.container-fluid -->
            </section>
          </div>
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>
