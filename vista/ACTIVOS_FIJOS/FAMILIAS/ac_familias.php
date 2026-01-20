<?php
include(dirname(__DIR__, 3) . '/cabeceras/header2.php');
?>

<script type="text/javascript">
  $(document).ready(function() {
    tbl_familias = $('#tbl_familias').DataTable($.extend({}, configuracion_datatable('Familias', 'familias', 'contenedor_botones'), {
      reponsive: false,
      language: {
        url: '../assets/plugins/datatable/spanish.json'
      },
      ajax: {
        url: 'controlador/ACTIVOS_FIJOS/familiasC.php?lista=true',
        dataSrc: ''
      },
      columns: [{
          data: 'id_familia',
        },
        {
          data: null,
          render: function(data, type, item) {
            href = `vista/ACTIVOS_FIJOS/FAMILIAS/ac_familia_detalle.php?id=${item.id_familia}`;
            return `<a href="${href}"><u>${item.detalle_familia}</u></a>`;
          }
        },
      ],
      order: [
        [0, 'asc']
      ]
    }));

    tbl_subfamilias = $('#tbl_subfamilias').DataTable($.extend({}, configuracion_datatable('Subfamilias', 'subfamilias', 'contenedor_botones_2'), {
      reponsive: false,
      language: {
        url: '../assets/plugins/datatable/spanish.json'
      },
      ajax: {
        url: 'controlador/ACTIVOS_FIJOS/familiasC.php?lista_subfamilias=true',
        dataSrc: ''
      },
      columns: [{
          data: 'idF',
        },
        {
          data: 'detalle_familia',
        },
        {
          data: null,
          render: function(data, type, item) {
            href = `vista/ACTIVOS_FIJOS/FAMILIAS/ac_subfamilia_detalle.php?id=${item.idF}`;
            return `<a href="${href}"><u>${item.detalle_familia_sub}</u></a>`;
          }
        },
      ],
      order: [
        [0, 'asc']
      ]
    }));
  });

  function consultar_datos1() {
    var colores = '';
    var parametros = {
      'id': '',
      'query': $('#txt_query1').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../../controlador/ACTIVOS_FIJOS/familiasC.php?subfamilia=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          console.log(item);
          colores += '<tr><td>' + item.id_familia + '</td><td>' + item.familia + '</td><td><a href="detalle_subfamilia.php?id=' + item.id_familia + '"><u>' + item.detalle_familia + '</u></a></td></tr>';
        });
        $('#tbl_datos1').html(colores);
      }
    });
  }
</script>

<section class="content p-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col-6">
        <div class="row">

          <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card-title d-flex align-items-center">

              <div class="" id="btn_nuevo">
                <a href="vista/ACTIVOS_FIJOS/FAMILIAS/ac_familia_detalle.php"
                  type="button" class="btn btn-success btn-sm ">
                  <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                </a>
              </div>

            </div>
          </div>

          <div class="col-12 col-md-8  text-md-end text-start">
            <div id="contenedor_botones"></div>
          </div>

        </div>

        <hr>

        <div class="table-responsive">
          <table class="table table-striped responsive " id="tbl_familias" style="width:100%">
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th>Descripción</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>

      <div class="col-6">
        <div class="row">

          <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card-title d-flex align-items-center">

              <div class="" id="">
                <a href="vista/ACTIVOS_FIJOS/FAMILIAS/ac_subfamilia_detalle.php"
                  type="button" class="btn btn-success btn-sm ">
                  <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                </a>
              </div>

            </div>
          </div>

          <div class="col-12 col-md-8  text-md-end text-start">
            <div id="contenedor_botones_2"></div>
          </div>

        </div>

        <hr>

        <div class="table-responsive">
          <table class="table table-striped responsive " id="tbl_subfamilias" style="width:100%">
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th>Familia</th>
                <th>Subfamilia</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>


  </div>
</section>