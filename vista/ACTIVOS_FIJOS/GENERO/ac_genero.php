<?php
include(dirname(__DIR__, 3) . '/cabeceras/header2.php');
?>

<script>
  $(document).ready(function() {
    tbl_generos = $('#tbl_generos').DataTable($.extend({}, configuracion_datatable('Generos', 'generos'), {
      reponsive: true,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
      },
      ajax: {
        url: 'controlador/ACTIVOS_FIJOS/generoC.php?lista=true',
        dataSrc: ''
      },
      columns: [{
          data: 'ID_GENERO',
        }, {
          data: 'CODIGO',
        },
        {
          data: null,
          render: function(data, type, item) {
            href = `vista/ACTIVOS_FIJOS/GENERO/ac_genero_detalle.php?id=${item.ID_GENERO}`;
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

<section class="content p-2">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <div class="card-title d-flex align-items-center">

          <div class="" id="btn_nuevo">
            <a href="vista/ACTIVOS_FIJOS/GENERO/ac_genero_detalle.php"
              type="button" class="btn btn-success btn-sm ">
              <i class="bx bx-plus me-0 pb-1"></i> Nuevo
            </a>
          </div>

        </div>
      </div>

      <div class="col-12 col-md-6  text-md-end text-start">
        <div id="contenedor_botones"></div>
      </div>

    </div>

    <hr>

    <div class="table-responsive">
      <table class="table table-striped responsive " id="tbl_generos" style="width:100%">
        <thead>
          <tr>
            <th width="5%">ID</th>
            <th width="5%">Codigo</th>
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</section>