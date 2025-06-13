<?php

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']) ?? '';

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>

  function redireccionar(url_redireccion) {
    url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
    window.location.href = url_click;
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Inicio</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"></li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <h6 class="mb-0 text-uppercase">dashboard</h6>
        <hr>

        <div class="row">
          <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text" title="Ultima actualizacion con SAP">Ultima actu. con SAP</span>
                <span class="info-box-number">
                   <?php echo date('Y-m-d H:i:s'); ?>
                </span>
              </div>
            </div>
          </div> -->
          <div class="col-6 col-sm-6 col-md-4" id="pnl_clases" onclick="redireccionar_('');">
            <div class="card radius-10 shadow-card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Controladoras</p>
                    <h4 class="my-1" id="lbl_clases">1</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-book-content'></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-sm-6 col-md-4" id="pnl_clases" onclick="redireccionar_('');">
            <div class="card radius-10 shadow-card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Antenas</p>
                    <h4 class="my-1" id="lbl_clases">1</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-book-content'></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h6 class="text-uppercase">Lectura de Portales</h6>
          <hr>

          <div class="row">
            <div class="col-xl-12 mx-auto">
              <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-3">

                  <section class="content pt-4">
                    <div class="container-fluid">
                     






                    
                    </div><!-- /.container-fluid -->
                  </section>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!--end row-->
</div>


<!-- Estilos para redireccionar -->
<script>
  $(document).ready(function() {
    $('.shadow-card').on('mouseover', function() {
      $(this).addClass('hoverEffect');
    });

    $('.shadow-card').on('mouseout', function() {
      $(this).removeClass('hoverEffect');
    });

    $('.shadow-card').on('click', function() {
      $(this).toggleClass('clickedEffect');
    });

    $(document).on('mouseout', '.shadow-card', function() {
      $(this).removeClass('clickedEffect');
    });

  });
</script>

<style>
  .card {
    cursor: pointer;
    transition: background-color 0.3s, box-shadow 0.3s;
  }

  .card.hoverEffect {
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
    background-color: rgba(45, 216, 34, 0.1);
  }

  .card.clickedEffect {
    background-color: rgba(128, 224, 122, 0.5);
  }
</style>