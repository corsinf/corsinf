<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>
<!--  -->
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    tbl_clase_movimiento = $('#tbl_clase_movimiento').DataTable($.extend({}, configuracion_datatable('Clase Movimiento', 'clase_movimiento'), {
      reponsive: true,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?listar=true',
        dataSrc: ''
      },
      columns: [{
          data: 'ID_MOVIMIENTO'
        },
        {
          data: 'CODIGO'
        },
        {
          data: null,
          render: function(data, type, item) {
            href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=detalle_clase_movimiento&_id=${item.ID_MOVIMIENTO}`;
            return `<a href="${href}"><u>${item.DESCRIPCION}</u></a>`;
          }
        },
      ],
      order: [
        [0, 'asc']
      ]
    }));
  });
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Clase Movimiento</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Clase Movimiento
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
          <div class="card-body p-5">
            <div class="row">

              <div class="col-12 col-md-6">
                <div class="card-title d-flex align-items-center">

                  <div class="" id="btn_nuevo">
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=detalle_clase_movimiento"
                      type="button" class="btn btn-success btn-sm ">
                      <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                    </a>
                  </div>

                </div>
              </div>

              <div class="col-12 col-md-6 text-md-end text-start">
                <div id="contenedor_botones"></div>
              </div>

            </div>

            <hr>

            <section class="content pt-2">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped responsive " id="tbl_clase_movimiento" style="width:100%">
                    <thead>
                      <tr>
                        <th width='5%'>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                      </tr>
                    </thead>
                    <tbody>

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