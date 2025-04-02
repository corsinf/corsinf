<?php

/**
 * @deprecated Archivo dado de baja el 02/04/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

//include('../cabeceras/header.php');
$tipo_lista = 1;
if (isset($_SESSION['INICIO']["LISTA_ART"])) {
  $tipo_lista = $_SESSION['INICIO']["LISTA_ART"];
}


?>

<script type="text/javascript">
  $('body').addClass('sidebar-collapse');
  $(document).ready(function() {
    ddl_meses();
    informes();
    autocmpletar();
    autocmpletar_l();
    var fil1 = '<?php if (isset($_GET["fil1"])) {
                  echo $_GET["fil1"];
                } ?>';
    var fil2 = '<?php if (isset($_GET["fil2"])) {
                  echo $_GET["fil2"];
                } ?>';
    // console.log(fil1);
    // console.log(fil2);
    if (fil1 != 'null--null') {
      var loc = fil1.split('--');
      $('#ddl_localizacion').append($('<option>', {
        value: loc[0],
        text: loc[1],
        selected: true
      }));
    }
    if (fil2 != 'null--null') {
      var cus = fil2.split('--');
      $('#ddl_custodio').append($('<option>', {
        value: cus[0],
        text: cus[1],
        selected: true
      }));
    }

    buscar_art();


  });

  function informes() {

    $.ajax({
      // data:  {id:id},
      url: '../controlador/ACTIVOS_FIJOS/reportesC.php?informes_patrimoniales=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {

        $('#ddl_informes').html(response);
      }
    });

  }

  function autocmpletar() {
    $('#ddl_custodio').select2({
      placeholder: 'Seleccione una custodio',
      width: '90%',
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/custodioC.php?lista=true',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function autocmpletar_l() {
    $('#ddl_localizacion').select2({
      placeholder: 'Seleccione una localizacion',
      width: '90%',
      ajax: {
        url: '../controlador/ACTIVOS_FIJOS/localizacionC.php?lista=true',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function lista_articulos() {
    var parametros = {
      'query': $('#txt_buscar').val(),
      'localizacion': $('#ddl_localizacion').val(),
      'custodio': $('#ddl_custodio').val(),
      'pag': $('#txt_pag').val(),
      'exacto': $('input[type="radio"][name="rbl_exacto"]:checked').val(),
      'buscar_por': $('input[type="radio"][name="buscar_por"]:checked').val(),
      'multiple': $('input[type="radio"][name="rbl_multiple"]:checked').val(),
      'lista': '1',
      'desde': $('#txt_desde').val(),
      'hasta': $('#txt_hasta').val(),
    }
    var lineas = '';
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?lista_patrimoniales=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        // var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#pag').html('');
      },
      success: function(response) {
        console.log(response);
        var pag = $('#txt_pag1').val().split('-');
        var pag2 = $('#txt_pag').val().split('-');

        var pagi = '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
        if ($('#txt_numpag').val() == '') {
          $('#txt_numpag').val(response.cant / pag[1]);
        }
        if (response.cant > pag[1]) {
          var num = response.cant / pag[1];
          if (num > 10) {
            if (pag2[0] / pag[1] < 9) {
              // console.log(10);
              for (var i = 1; i < 11; i++) {
                var pos = pag[1]; //pag[1]*i;
                var ini = pag[0] + (pag[1] * i) - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="paginate_button page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="paginate_button page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            } else {
              pagi += '<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
              for (var i = pag2[0] / 25 + 1; i < (pag2[0] / 25) + 10; i++) {
                var pos = pag[1]; //pag[1]*i;
                var ini = pag[0] + (pag[1] * i) - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="paginate_button page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="paginate_button page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            }
            pagi += '<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
          } else {

            for (var i = 1; i < num + 1; i++) {
              var pos = pag[1]; //pag[1]*i;
              var ini = pag[0] + (pag[1] * i) - pag[1];
              var pa = ini + '-' + pos;
              if ($('#txt_pag').val() == pa) {
                pagi += '<li class="paginate_button page-item active"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              } else {
                pagi += '<li class="paginate_button page-item"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              }
            }
          }

          // <li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
          // </li>


          $('#pag').html(pagi);

        }
        $.each(response.datos, function(i, item) {
          baja = '';

          if (item.PATRIMONIALES == '1') {
            baja = '#f9d99a52';
          }
          if (item.RFID == null) {
            item.RFID = '';
          }
          lineas += '<tr style="background-color:' + baja + '"><td>' + item.id + '</td><td style="color: #1467e2; cursor: pointer;"  onclick="redireccionar(\'' + item.id + '\')"><u>' + item.tag + '</u></td><td>' + item.nom + '</td><td>' + item.modelo + '</td><td>' + item.serie + '</td><td>' + item.RFID + '</td><td>' + item.localizacion + '</td><td>' + item.custodio + '</td><td>' + item.marca + '</td><td>' + item.estado + '</td><td>' + item.genero + '</td><td>' + item.color + '</td><td>' + formato_fecha(item.fecha_in) + '</td><td>' + item.OBSERVACION + '</td></tr>';
          console.log(item.PATRIMONIALES);

        });
        $('#tbl_datos').html(lineas);
      },
      error: function(error) {
        alert(JSON.stringify(error));
      }
    });
  }

  function lista_articulos_grid() {
    var parametros = {
      'query': $('#txt_buscar').val(),
      'localizacion': $('#ddl_localizacion').val(),
      'custodio': $('#ddl_custodio').val(),
      'pag': $('#txt_pag').val(),
      'exacto': $('input[type="radio"][name="rbl_exacto"]:checked').val(),
      'buscar_por': $('input[type="radio"][name="buscar_por"]:checked').val(),
      'multiple': $('input[type="radio"][name="rbl_multiple"]:checked').val(),
      'lista': '0',
      'desde': $('#txt_desde').val(),
      'hasta': $('#txt_hasta').val(),
    }
    var lineas = '';
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?lista_patrimoniales=true',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        // var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#pag').html('');
      },
      success: function(response) {
        console.log(response);
        var pag = $('#txt_pag1').val().split('-');
        var pag2 = $('#txt_pag').val().split('-');

        var pagi = '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
        if ($('#txt_numpag').val() == '') {
          $('#txt_numpag').val(response.cant / pag[1]);
        }
        if (response.cant > pag[1]) {
          var num = response.cant / pag[1];
          if (num > 10) {
            if (pag2[0] / pag[1] < 9) {
              for (var i = 1; i < 11; i++) {
                var pos = pag[1]; //pag[1]*i;
                var ini = pag[0] + (pag[1] * i) - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="paginate_button page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="paginate_button page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            } else {

              pagi += '<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
              for (var i = pag2[0] / 25 + 1; i < (pag2[0] / 25) + 10; i++) {
                var pos = pag[1]; //pag[1]*i;
                var ini = pag[0] + (pag[1] * i) - pag[1];
                var pa = ini + '-' + pos;
                if ($('#txt_pag').val() == pa) {
                  pagi += '<li class="paginate_button page-item active" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                } else {
                  pagi += '<li class="paginate_button page-item" onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
                }
              }
            }
            pagi += '<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
          } else {

            for (var i = 1; i < num + 1; i++) {
              var pos = pag[1]; //pag[1]*i;
              var ini = pag[0] + (pag[1] * i) - pag[1];
              var pa = ini + '-' + pos;
              if ($('#txt_pag').val() == pa) {
                pagi += '<li class="paginate_button page-item active"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              } else {
                pagi += '<li class="paginate_button page-item"  onclick="paginacion(\'' + pa + '\')"><a class="page-link" href="#">' + i + '</a></li>';
              }
            }
          }

          // <li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
          // </li>


          $('#pag').html(pagi);

        }
        $.each(response.datos, function(i, item) {
          baja = '';
          tex = '';
          if (item.BAJAS == 1) {
            baja = 'text-danger';
            tex = 'BAJA';
          }
          if (item.PATRIMONIALES == 1) {
            baja = 'text-warning bg-light-warning';
            tex = 'PATRIMONIAL';
          }
          if (item.TERCEROS == 1) {
            baja = 'text-primary bg-light-primary';
            tex = 'TERCEROS';
          }

          if (item.estado == 'BUENO' || item.estado == 'bueno') {
            estado = '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>';

          } else if (item.estado == 'malo' || item.estado == 'MALO') {
            estado = '<i class="bx bxs-star text-secondary"></i>' +
              '<i class="bx bxs-star text-secondary"></i>' +
              '<i class="bx bxs-star text-secondary"></i>' +
              '<i class="bx bxs-star text-secondary"></i>' +
              '<i class="bx bxs-star text-secondary"></i>';
          } else {
            estado = '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-warning"></i>' +
              '<i class="bx bxs-star text-secondary"></i>' +
              '<i class="bx bxs-star text-secondary"></i>';
          }

          imagen = '../img/sin_imagen.jpg';
          if (item.IMAGEN != '' && item.IMAGEN != null) {
            imagen = item.IMAGEN;
          }

          lineas += '<div class="col">' +
            '<div class="card" onclick="redireccionar(\'' + item.id + '\')">' +
            '<img src="../img/' + imagen + '" class="card-img-top" alt="..." style="width: 100%;height: 200px;">' +
            '<div class="">'
          if (baja != '') {
            lineas += '<div class="position-absolute top-0 end-0 m-3"><span class="">' +
              '<div class="badge rounded-pill ' + baja + ' p-2 text-uppercase px-3">' + tex + '</div>' +
              '</span></div>';
          }
          lineas += '</div>' +
            '<div class="card-body">' +
            '<h6 class="card-title cursor-pointer">' + item.nom + '</h6>' +
            '<div class="clearfix">' +
            '<p class="mb-0 float-start"><strong>Asset</strong> ' + item.tag + '</p><br>' +
            '<p class="mb-0 float-start" style="font-size: 80%;"><strong>RFID:</strong> ' + item.RFID + '</p>' +
            '</div>' +
            '<div class="d-flex align-items-center mt-1 mb-1 fs-6 font-13">' +
            '<div class="cursor-pointer">Estado<br>' +
            estado +
            '</div>' +
            '<p class="mb-0 ms-auto font-13"><b>Fecha Inv.</b><br>' + item.fecha_in + '</p>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

          // lineas+= '<tr style="'+baja+'"  onclick="redireccionar(\''+item.id+'\')"><td>'+item.id+'</td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.modelo+'</td><td>'+item.serie+'</td><td>'+item.RFID+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.marca+'</td><td>'+item.estado+'</td><td>'+item.genero+'</td><td>'+item.color+'</td><td>'+item.fecha_in+'</td><td>'+item.OBSERVACION+'</td></tr>';
          // console.log(item);



        });
        $('#grilla_art').html(lineas);
      },
      error: function(error) {
        alert(JSON.stringify(error));
      }
    });
  }

  function buscar_art() {
    $.ajax({
      // data:  {id:id},
      url: '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?tipo_view=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          lista_articulos();
          lista();
        } else {
          lista_articulos_grid();
          grilla();
        }
      }
    });
  }

  function limpiar(ddl) {
    $('#' + ddl).val('').trigger('change');
  }

  function redireccionar(id) {
    var loc = 'null';
    var cus = 'null';
    if ($('#ddl_localizacion').val() != null) {
      loc = $('#ddl_localizacion').select2('data')[0].text;
    }
    if ($('#ddl_custodio').val() != null) {
      cus = $('#ddl_custodio').select2('data')[0].text;
    }
    window.location.href = "inicio.php?acc=detalle_articulo&id=" + id + '&fil1=' + $('#ddl_localizacion').val() + '--' + loc + '&fil2=' + $('#ddl_custodio').val() + '--' + cus;
  }

  function paginacion(num) {
    $('#txt_pag').val(num);
    var pag = $('#txt_pag').val().split('-');
    var pos = pag[1] / 25;

    var tipo_lista = '<?php echo $tipo_lista; ?>';
    if (tipo_lista == 1) {
      lista_articulos();
    } else {
      lista_articulos_grid();
    }


    // alert(pos);
  }

  function guias_pag(tipo) {

    var m1 = $('#txt_pag').val().split('-');
    var m = $('#txt_pag1').val().split('-');
    var pos = m1[1] / 25;
    if (tipo == '+') {
      if (pos >= 10) {
        var fin = m[1] * (pos + 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        lista_articulos();

      } else {
        var fin = m[1] * (pos + 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        lista_articulos();
      }

    } else {
      if (pos == 1) {
        alert('esta en el inicio');
      } else {
        var fin = m[1] * (pos - 1);
        var ini = fin - m[1];
        $('#txt_pag').val(ini + '-' + fin);
        lista_articulos();
      }
    }
  }

  function activar() {
    if (!$('#rbl_exacto').prop('checked')) {
      $('#rbl_aset').prop('checked', false);
      $('#rbl_aset_ori').prop('checked', false);
      $('#rbl_aset').prop('disabled', true);
      $('#rbl_aset_ori').prop('disabled', true);
      $('#rbl_rfid').prop('checked', false);
      $('#rbl_rfid').prop('disabled', true);
    } else {

      $('#rbl_aset').prop('disabled', false);
      $('#rbl_aset_ori').prop('disabled', false);
      $('#rbl_rfid').prop('disabled', false);
      $('#rbl_aset').prop('checked', true);
    }
    lista_articulos();
  }

  function ddl_meses() {
    var opcion = '<option value="">seleccione un mes</option>';
    $.ajax({
      // data:  {id:id},
      url: '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?meses=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);
        $.each(response, function(i, item) {
          opcion += "<option value='" + item.num + "'>" + item.mes + "</option>";
        })
        $('#ddl_meses').html(opcion);
      }
    });
  }

  function busqued_multiple() {
    check = $('#rbl_multiple').prop('checked');
    if (check) {
      Swal.fire('Asegurese que el separador sea una coma (,)', '', 'info')
      $('#rbl_exacto').prop('checked', true);
      $('#rbl_exacto').attr('disabled', true);
      // alert('actyivo');
    } else {

      $('#rbl_exacto').prop('checked', true);
      $('#rbl_exacto').attr('disabled', false);
      // alert('no act');
    }
    lista_articulos();
  }

  function grilla() {
    $('#lista_art').css('display', 'none');
    $('#grilla_art').css('display', 'contents');

    $('#btn_grid').css('display', 'none');
    $('#btn_lista').css('display', 'block');
    lista_articulos_grid();
  }

  function lista() {
    $('#grilla_art').css('display', 'none');
    $('#lista_art').css('display', 'block');

    $('#btn_lista').css('display', 'none');
    $('#btn_grid').css('display', 'block');
    lista_articulos();
  }

  function ver_informe_pdf(id) {
    var filtros = $('#pnl_filtros').serialize();
    var url = '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?ver_pdf=true&' + filtros + '&informe=' + id + '&pag=' + $('#txt_pag').val();
    window.open(url, '_blank');
  }

  function ver_informe_excel(id) {
    var filtros = $('#pnl_filtros').serialize();
    var url = '../controlador/ACTIVOS_FIJOS/patrimonialesC.php?ver_excel=true&' + filtros + '&informe=' + id + '&pag=' + $('#txt_pag').val();
    window.open(url, '_blank');
  }

  function mostrar_mas_filtros() {
    if ($('#mas_filtros').is(':visible')) {
      $('#mas_filtros').css('display', 'none');
      ocultar_mas_filtros();
    } else {
      $('#mas_filtros').css('display', 'flex');
    }
  }

  function ocultar_mas_filtros() {

    $('input[type="radio"][name="rbl_exacto"][value="0"]').prop('checked', true);
    $('input[type="radio"][name="rbl_multiple"][value="0"]').prop('checked', true);
    $('input[type="radio"][name="buscar_por"][value="0"]').prop('checked', true);

    $('#mas_filtros').css('display', 'none');
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->

    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">
        <div class="card">
          <div class="card-body">
            <div class="row row-cols-auto g-1">
              <div class="col-sm-12 text-end">
                <div class="dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Imprimir / Exportar</button>
                  <ul class="dropdown-menu" id="ddl_informes">
                  </ul>
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm">Opciones</button>
                    <button type="button" class="btn btn-primary btn-sm split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                      <button type="button" class="dropdown-item" id="btn_grid" onclick="grilla()"><i class="bx bx-grid-alt"></i> Grilla</button>
                      <button type="button" class="dropdown-item" id="btn_lista" onclick="lista()" style="display: none;"><i class="bx bx-list-ul"></i> Lista</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>

            <form id="pnl_filtros">
              <div class="row">
                <div class="col-sm-5">
                  <b>Buscar</b><br>
                  <div class="input-group">
                    <input type="" name="txt_buscar" id="txt_buscar" onkeyup="buscar_art();" class="form-control form-control-sm" placeholder="Buscar Descripcion o tag">
                    <button type="button" class="btn btn-outline-secondary btn-sm me-0 p-1" onclick="mostrar_mas_filtros()" title="Filtros de busqueda"><i class="bx bx-slider"></i></button>
                  </div>
                </div>
                <div class="col-sm-4">
                  <b>Busqueda por custodio</b>
                  <div class="input-group">
                    <select class="form-select" id="ddl_custodio" name="ddl_custodio" onchange="$('#txt_pag').val('0-25');buscar_art();/*lista_articulos()*/">
                      <option value="">Seleccione custodio</option>
                    </select>
                    <button type="button" style="padding:0px" class="btn btn-outline-secondary btn-sm" onclick="limpiar('ddl_custodio')"><i class="bx bx-x me-0"></i></button>
                  </div>
                </div>
                <div class="col-sm-3">
                  <b> Busqueda por Localizacion</b>
                  <div class="input-group">
                    <select class="form-select" id="ddl_localizacion" name="ddl_localizacion" onchange="$('#txt_pag').val('0-25');buscar_art();/*lista_articulos()*/">
                      <option value="">Seleccione custodio</option>
                    </select>
                    <button type="button" style="padding:0px" class="btn btn-outline-secondary btn-sm" onclick="limpiar('ddl_localizacion')" title="Limpiar localizacion"><i class="bx bx-x me-0"></i></button>
                  </div>
                </div>
              </div>

              <div class="row" id="mas_filtros" style="display: none;">
                <hr class="m-2">
                <div class="col-sm-2 border-end">
                  <b>Coincidencia:</b>
                  <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_exacto" id="rbl_aproximado" value="0" onclick="buscar_art()" checked> Aproximada </label>
                  <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_exacto" id="rbl_exacta" value="1" onclick="buscar_art()"> Exacta</label>
                </div>

                <div class="col-sm-2 border-end">
                  <b>Tipo de busqueda</b>
                  <br>
                  <label class="checkbox-inline" style="margin: 0px;"><input type="radio" value="0" name="rbl_multiple" onclick="buscar_art();" checked> Unica</label>
                  <br>
                  <label class="checkbox-inline" style="margin: 0px;"><input type="radio" value="1" name="rbl_multiple" onclick="buscar_art();"> Multiple</label>
                </div>
                <div class="col-sm-4 border-end">
                  <b>Busqueda por:</b><br>
                  <div class="row">
                    <div class="col-sm-6">
                      <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="buscar_por" value="0" onclick="buscar_art();" checked=""> Ninguno</label>
                      <br>
                      <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="buscar_por" value="1" onclick="buscar_art();"> Asset</label>
                    </div>
                    <div class="col-sm-6">
                      <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="buscar_por" value="2" onclick="buscar_art();"> Orig Asset</label>
                      <br>
                      <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="buscar_por" value="3" onclick="buscar_art();"> RFID</label>
                    </div>
                  </div>

                </div>
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-6">
                      <b>Desde</b>
                      <input type="date" class="form-control form-control-sm" id="txt_desde" name="txt_desde">
                    </div>
                    <div class="col-sm-6">
                      <b>Hasta</b>
                      <input type="date" class="form-control form-control-sm" id="txt_hasta" name="txt_hasta">
                    </div>
                  </div>
                  <p class="font-10">Fecha de Inventario</p>
                </div>
                <div class="col-sm-12 text-end">
                  <button type="button" class="btn btn-primary btn-sm" onclick="buscar_art()">Buscar</button>
                  <!-- <button type="button" class="btn btn-primary btn-sm" onclick="ocultar_mas_filtros()">Cerrar</button> -->
                </div>
              </div>


            </form>


            <hr>
            <div class="row">
              <div class="col-sm-6 col-md-8">

              </div>
              <div class="col-sm-6 col-md-4">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                  <ul class="pagination pagination-sm justify-content-end mb-0" id="pag">
                  </ul>
                </div>
              </div>
            </div>

            <div class="row">
              <input type="hidden" id="txt_pag" name="" value="0-25">
              <input type="hidden" id="txt_pag1" name="" value="0-25">
              <input type="hidden" id="txt_numpag" name="">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 product-grid" id="grilla_art" style="display: none;">

              </div>

              <div class="table-responsive" id="lista_art">
                <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                  <div class="col-sm-12">
                    <table id="example" class="table table-striped table-bordered dataTable" role="grid">
                      <thead>
                        <tr role="row">
                          <th>Id</th>
                          <th>Tag Serie</th>
                          <th>Descripcion</th>
                          <th>Modelo</th>
                          <th>Serie</th>
                          <th>RFID</th>
                          <th>Localizacion</th>
                          <th>Custodio</th>
                          <th>Marca</th>
                          <th>Estado</th>
                          <th>Genero</th>
                          <th>Color</th>
                          <th>Fecha Inv.</th>
                          <th>Observacion</th>
                      </thead>
                      <tbody id="tbl_datos">
                        <tr role="row" class="odd">
                          <td colspan="14">sin registros</td>
                        </tr>

                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Id</th>
                          <th>Tag Serie</th>
                          <th>Descripcion</th>
                          <th>Modelo</th>
                          <th>Serie</th>
                          <th>RFID</th>
                          <th>Localizacion</th>
                          <th>Custodio</th>
                          <th>Marca</th>
                          <th>Estado</th>
                          <th>Genero</th>
                          <th>Color</th>
                          <th>Fecha Inv.</th>
                          <th>Observacion</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--end row-->
    </div>
  </div>

  <!-- Modal -->

  <div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Articulos modificados por fecha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-4">
              <b style="font-size: 9px;">Desde:</b> <br>
              <input type="date" name="" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-4">
              <b style="font-size: 9px;">Hasta:</b> <br>
              <input type="date" name="" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-4"><br>
              <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Informes</button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#" id="imprimir_excel_sap">Informe excel para sap</a></li>
                  <li><a class="dropdown-item" href="#" id="imprimir_excel_bajas_sap">Informe excel bajas sap</a></li>
                  <li><a class="dropdown-item" href="#" id="imprimir_excel_cambios_rango">Informe excel cambios</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <?php //include('../cabeceras/footer.php'); 
  ?>