<script type="text/javascript">
  $('body').addClass('sidebar-mini layout-fixed sidebar-collapse');
</script>
<script type="text/javascript">
  $(document).ready(function() {
    bodegas_punto();
    autocoplet_tipo_pago();
    cheques_pos();

    var num = '<?php echo $num; ?>';
    var doc = '<?php echo $doc; ?>';
    var est = '<?php echo $estado; ?>'
    var pun = '<?php echo $punto; ?>';

    if (est == 'F') {
      cheques_cruzar(num);
    }
    botones();
    if (num == '') {
      // $('#cliente_facturar').modal('show');
    }
    if (num != '' && doc != '') {
      $('#ddl_pasar').css('display', 'initial');
      datos_factura();
    }
    if (est == 'F') {
      finalizar_factura(num);
    }
    if (pun != '') {
      buscar_punto_venta(pun);
      buscar_punto_bodega(pun);
    }
    $('#txt_num_fac').val(num);
    cargar_pedido();
    cargar_pedido_f();
    autocoplet_tipo_pago_1();


    $("#txt_referencia").autocomplete({
      source: function(request, response) {

        $.ajax({
          url: "../controlador/punto_ventaC.php?search",
          type: 'post',
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {
            console.log(data);
            response(data);
          }
        });
      },
      select: function(event, ui) {
        $('#txt_producto').val(ui.item.producto); // display the selected text
        $('#txt_referencia').val(ui.item.value); // save selected id to input
        $('#txt_precio').val(ui.item.precio); // save selected id to input
        $('#txt_bodega').empty();
        $('#txt_bodega').append($('<option>', {
          value: ui.item.bodega,
          text: ui.item.bodega_nom,
          selected: true
        }));
        return false;
      },
      focus: function(event, ui) {
        $("#txt_referencia").val(ui.item.value);
        $('#txt_producto').val(ui.item.producto);
        $('#txt_bodega').empty();
        $('#txt_bodega').append($('<option>', {
          value: ui.item.bodega,
          text: ui.item.bodega_nom,
          selected: true
        }));
        return false;
      },
    });
    // ________________________________________________________
    // ________________________________________________________

    $("#txt_producto").autocomplete({
      source: function(request, response) {

        $.ajax({
          url: "../controlador/punto_ventaC.php?search",
          type: 'post',
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
      select: function(event, ui) {
        $('#txt_producto').val(ui.item.nombre); // display the selected text
        $('#txt_referencia').val(ui.item.value); // save selected id to input
        $('#txt_bodega').val(ui.item.bodega); // save selected id to input
        $('#txt_precio').val(ui.item.precio); // save selected id to input
        $('#txt_bodega').append($('<option>', {
          value: ui.item.bodega,
          text: ui.item.bodega_nom,
          selected: true
        }));
        return false;
      },
      focus: function(event, ui) {
        $('#txt_producto').val(ui.item.nombre); // display the selected text
        $('#txt_referencia').val(ui.item.value); // save selected id to input
        $('#txt_bodega').val(ui.item.bodega); // save selected id to input
        $('#txt_bodega').empty();
        $('#txt_bodega').append($('<option>', {
          value: ui.item.bodega,
          text: ui.item.bodega_nom,
          selected: true
        }));

        return false;
      },
    });

    // ------------------------------------------------------

    $("#txt_nombre_cli").autocomplete({
      source: function(request, response) {

        $.ajax({
          url: "../controlador/facturacionC.php?search_client=true",
          type: 'post',
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
      select: function(event, ui) {
        $('#btn_cliente').html('<i class="fa fa-edit"></i><br>Editar');
        $('#txt_nombre_cli').val(ui.item.label); // display the selected text
        $('#txt_ci_cli').val(ui.item.ci); // save selected id to input
        $('#txt_email_cli').val(ui.item.email); // display the selected text
        $('#txt_telefono_cli').val(ui.item.tel); // save selected id to input
        $('#txt_direccion_cli').val(ui.item.dir); // display the selected text
        $('#txt_id_cli').val(ui.item.value); // display the selected text
        $('#txt_credito').val(ui.item.credito.toFixed(2)); // save selected id to input
        return false;
      },
      focus: function(event, ui) {
        $("#txt_nombre_cli").val(ui.item.label);
        return false;
      },
    });


    // ------------------------------------------
    // ------------------------------------------------------

    $("#txt_ci_cli").autocomplete({
      source: function(request, response) {

        $.ajax({
          url: "../controlador/facturacionC.php?search_client=true",
          type: 'post',
          dataType: "json",
          data: {
            searchCI: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
      select: function(event, ui) {
        $('#btn_cliente').html('<i class="fa fa-edit"></i><br>Editar');
        $('#txt_nombre_cli').val(ui.item.nom); // display the selected text
        $('#txt_ci_cli').val(ui.item.label); // save selected id to input
        $('#txt_email_cli').val(ui.item.email); // display the selected text
        $('#txt_telefono_cli').val(ui.item.tel); // save selected id to input
        $('#txt_direccion_cli').val(ui.item.dir); // display the selected text
        $('#txt_id_cli').val(ui.item.value); // display the selected text
        return false;
      },
      focus: function(event, ui) {
        $("#txt_ci_cli").val(ui.item.label);
        return false;
      },
    });


    // ------------------------------------------
  });


  function buscar_punto_venta(id) {

    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/facturacionC.php?buscar_punto=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        if (response) {
          $('#txt_nom_punto').text(response[0].nombre); // display the selected text
          $('#txt_id_punto').val(response[0].id); // save selected id to input

        }
      }

    });

  }

  function buscar_punto_bodega(id) {

    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/facturacionC.php?buscar_bodegas_punto=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
      }

    });

  }

  function datos_cliente_nuevo(ci = false, id = false) {
    var parametros = {
      'ci': ci,
      'query': '',
      'id': id,
    }

    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?datos_cliente_nuevo=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        if (response) {
          $('#txt_nombre_cli').val(response[0].nombre); // display the selected text
          $('#txt_ci_cli').val(response[0].ci); // save selected id to input
          $('#txt_email_cli').val(response[0].email); // display the selected text
          $('#txt_telefono_cli').val(response[0].telefono); // save selected id to input
          $('#txt_direccion_cli').val(response[0].direccion); // display the selected text
          $('#txt_id_cli').val(response[0].id); // display the selected text
          $('#cliente_nuevo').modal('hide');
        }
      }

    });

  }



  function datos_factura() {

    var parametros = {
      'id': '<?php echo $num; ?>',
      'doc': '<?php echo $doc; ?>',
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?datos_cliente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        if (response) {
          $('#txt_id_cli').val(response.id);
          $('#cliente').text(response.nombre);
          $('#txt_nombre_cli').val(response.nombre);
          $('#numfac').text(response.fac);
          $('#txt_ci_cli').val(response.ci);
          $('#txt_telefono_cli').val(response.tel);
          $('#txt_email_cli').val(response.email);
          $('#txt_fecha_fac').val(response.fecha.date.substring(0, 10));
          $('#txt_direccion_cli').val(response.dir);
          $('#txt_credito').val(response.credito.toFixed(2));
          $('#txt_modal_credito').val(response.credito);


          $('#nombre_f').text(response.nombre);
          $('#ci_f').text(response.ci);
          $('#fecha_emi_f').text(response.fecha.date.substring(0, 10));
          $('#telefono_f').text(response.tel);
          $('#emial_f').text(response.email);
          $('#direccion_f').text(response.dir);
          $('#numfac_f').text(response.fac);
        }
      }

    });

  }

  function add_cheque() {

    var parametros = {
      'id': $('#txt_num_fac').val(),
      'num': $('#txt_che_pos').val(),
      'ban': $('#txt_ban_pos').val(),
      'fec': $('#txt_fec_co').val(),
      'mon': $('#txt_mon_pos').val(),
    }
    if ($('#txt_num_fac').val() == '' || $('#txt_che_pos').val() == '' || $('#txt_ban_pos').val() == '' || $('#txt_fec_co').val() == '' || $('#txt_mon_pos').val() == '' || $('#txt_mon_pos').val() == 0) {

      Swal.fire('', 'Llene todo los campos.', 'info');
      return false;
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?add_cheque=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response) {
          cheques_pos();
          Swal.fire('', 'Cheque Agregado.', 'success');
          $('#txt_che_pos').val('');
          $('#txt_ban_pos').val('');
          $('#txt_mon_pos').val('');
        }
      }

    });

  }

  function autocoplet_tipo_pago() {
    $('#ddl_tipo_pago').select2({
      placeholder: 'Seleccione una familia',
      width: '90%',
      ajax: {
        url: '../controlador/cuentas_x_cobrarC.php?tipo_pago=true',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function autocoplet_tipo_pago_1() {
    $('#ddl_tipo_pago_1').select2({
      placeholder: 'Seleccione una familia',
      width: '100%',
      ajax: {
        url: '../controlador/cuentas_x_cobrarC.php?tipo_pago=true',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }


  function finalizar_factura(num) {

    $.ajax({
      data: {
        num: num
      },
      url: '../controlador/punto_ventaC.php?finalizar_factura=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response) {

        }
      }

    });

  }

  function cargar_pedido_f() {
    var parametros = {
      'id': $('#txt_num_fac').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/punto_ventaC.php?cargar_pedido_f=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response) {
          console.log(response);
          existen_registros(response.tabla);
          $('#tbl_pedido_f').html(response.tabla);
          $('#txt_subtotal_fa_fin').text(response.subtotal);
          $('#txt_dcto_fa_fin').text(response.dcto);
          $('#txt_iva_fa_fin').text(response.iva);
          $('#txt_total_fa_fin').text(response.total);


        }
      }

    });

  }



  function cargar_pedido() {
    var parametros = {
      'id': $('#txt_num_fac').val(),
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/punto_ventaC.php?cargar_pedido=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response) {
          console.log(response);
          existen_registros(response.tabla);
          $('#tbl_pedido').html(response.tabla);
          $('#txt_subtotal_fa').val(response.subtotal);
          $('#txt_dcto_fa').val(response.dcto);
          $('#txt_iva_fa').val(response.iva);
          $('#txt_total_fa').val(response.total);


        }
      }

    });

  }

  function existen_registros(tabla) {
    let filas = $(tabla).find('tbody tr').length;
    if (filas > 0) {
      $('#txt_tr').val(1);
      // return 1;
    } else {
      $('#txt_tr').val(0);
    }
  }

  function crear_presupuesto() {
    if ($('#txt_num_fac').val() == 0) {
      crear_documento();
    } else {
      var url = '';
      var fac = $('#txt_num_fac').val();
      agregar(url, fac);
    }
  }


  function crear_documento() {
    var idC = $('#txt_id_cli').val();
    var nuf = $('#txt_num_fac').val();
    var tip = $('input:radio[name=rbl_tipo]:checked').val();
    var datos = {
      'cli': idC,
      'doc': 'FA',
      'nuf': nuf,
      'fefa': $('#txt_fecha_fac').val(),
    }
    if (idC == '') {
      Swal.fire('', 'Asegurese primero de Seleccionar una cliente.', 'info');
      return false;
    }

    $.ajax({
      data: {
        datos: datos
      },
      url: '../controlador/facturacionC.php?crear_documento=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response != -1) {
          var url = "Facturacion.php?numfac=" + response.id + '&doc=' + response.tipo;
          agregar(url, response.id);

        }
      }

    });


  }

  function cheques_pos() {
    var nuf = $('#txt_num_fac').val();
    var parametros = {
      'factura': nuf,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?cheques_pos=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        $('#tbl_cheques_pos').html(response.tabla);
        $('#num_c').html(response.reg);
        $('#tbl_cheq_abono').html(response.tabla);
      }

    });
  }

  function cheques_cruzar(nuf) {
    // var nuf = $('#txt_num_fac').val();   
    var parametros = {
      'factura': nuf,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?cheques_cruzar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response);
        $('#tbl_cheque_cruzar').html(response.tabla);
      }

    });


  }

  function agregar(url, fac) {
    var can = $('#txt_cantidad').val();
    var pre = $('#txt_precio').val();
    var des = $('#txt_descuento').val();
    var tot = $('#txt_total').val();
    var pro = $('#txt_producto').val();
    var idf = fac;
    var fefa = $('#txt_fecha_fac').val();
    var feex = $('#txt_fecha_exp').val();
    var ref = $('#txt_referencia').val();
    var bod = $('#txt_bodega').val();

    if (idf == 0) {

    }
    if (pro == '') {
      Swal.fire('', 'Seleccione un articulo', 'info');
      return false;
    }
    var datos = {
      'pro': pro,
      'can': can,
      'pre': pre,
      'des': des,
      'tot': tot,
      'idf': idf,
      'fefa': fefa,
      'feex': feex,
      'ref': ref,
      'bod': bod,
    }

    $.ajax({
      data: {
        datos: datos
      },
      url: '../controlador/punto_ventaC.php?add_pedido=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        if (response != -1) {
          if (url == '') {
            cargar_pedido();
          } else {
            $(location).attr('href', url);
          }

        }
      }

    });


  }

  function calcular() {
    var cant = $('#txt_cantidad').val();
    var prec = $('#txt_precio').val();
    var desc = $('#txt_descuento').val();
    var iva = 0;
    if (cant == '') {
      $('#txt_cantidad').val(0);
      cant = $('#txt_cantidad').val();
    }
    if (prec == '') {
      $('#txt_precio').val(0);
      prec = $('#txt_precio').val();
    }
    if (desc == '') {
      $('#txt_descuento').val(0);
      desc = $('#txt_descuento').val();
    }
    // if(iva=='')
    // {
    //   $('#txt_iva').val(0);iva = $('#txt_iva').val();
    // }

    var sub = cant * prec;
    var val_des = (desc * sub) / 100;

    if (iva == 0) {
      console.log('sin iva');
      var total = sub - val_des;
      $('#txt_total').val(total.toFixed(4));
      $('#txt_subtotal').val((sub - val_des).toFixed(4));
    } else {
      console.log('con iva');
      var total = (sub - val_des) * 1.12;
      var iva = total - (sub - val_des);
      $('#txt_total').val(total);
      $('#txt_subtotal').val(sub - val_des);
    }



    // var cant = $('#').val();
  }

  function new_usuario() {
    if ($('#txt_nombre_new').val() == '' || $('#txt_ci_new').val() == '' || $('#txt_telefono').val() == '' || $('#txt_emial').val() == '' || $('#txt_dir').val() == '') {
      Swal.fire('', 'Llene todo los campos.', 'info');
      return false;
    }

    var datos = $('#form_usuario_new').serialize();
    $.ajax({
      data: datos,
      url: '../controlador/punto_ventaC.php?new_usuario=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Nuevo cliente registrado.', 'success');
          datos_cliente_nuevo($('#txt_ci_new').val());
        } else {
          Swal.fire('', 'UPs aparecio un problema', 'success');
        }

      }

    });
  }

  function Eliminar(id) {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {

        $.ajax({
          data: {
            id: id
          },
          url: '../controlador/punto_ventaC.php?eliminar_linea=true',
          type: 'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
          success: function(response) {
            if (response == 1) {
              Swal.fire('', 'Registro eliminado.', 'success');
              cargar_pedido();
            } else {
              Swal.fire('', 'No se pudo elimnar.', 'info')
            }
          }

        });
      }
    });
  }


  function botones() {
    var est = '<?php echo $estado; ?>';
    if (est == 'P') {
      $('#finalizado_page').css('display', 'none');
      $('#pendiente_page').css('display', 'block');
      $('#btn_editar').css('display', 'none');
      $('#btn_fin').css('display', 'initial');
      $('#btn_abono').css('display', 'none');
    } else {
      $('#finalizado_page').css('display', 'block');
      $('#pendiente_page').css('display', 'none');
      $('#btn_editar').css('display', 'initial');
      $('#btn_fin').css('display', 'none');
      $('#btn_abono').css('display', 'initial');
    }
  }

  function Agregar_Abono(id) {
    $('#nuevo_abono').modal('show');
    abonos_a_factura(id);
  }

  function abonos_a_factura(id) {
    var parametros = {
      'id': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/cuentas_x_cobrarC.php?abonos_tabla=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        $('#tbl_abonos').html(response.tabla);
        $('#tbl_cuotas').html(response.tabla_cuotas);
        $('#txt_total_abono').val(response.total_abono);
        $('#txt_total_factura').val(response.total);
        $('#txt_restante_factura').val(response.faltante);
      }

    });
  }

  function habilitar_cheq_comp() {
    var tip = $('#ddl_tipo_pago').val();
    var t = tip.split('_');
    if (t[1] == 0) {
      $('#txt_cheq_comp').attr('readonly', true);
      $('#txt_cheq_comp').val('');
    } else {
      $('#txt_cheq_comp').attr('readonly', false);
    }
  }

  function ingresar_abono() {
    var total_abo = $('#txt_total_abono').val();
    var total_fac = $('#txt_total_factura').val();
    var total_res = $('#txt_restante_factura').val();
    var tip = $('#ddl_tipo_pago').val();
    var mon = $('#txt_monto').val();
    var comp = $('#txt_cheq_comp').val();
    var fec = $('#txt_fecha_abono').val();
    var id = $('#id_fac').val();
    var ban = $('#txt_banco_abono').val();
    if (mon == '' || !is_numeric(mon)) {
      Swal.fire('', 'Monto invalido.', 'info');
      return false;
    }
    var t = tip.split('_');
    if (t[1] == '1' && comp == '') {
      Swal.fire('', 'Ingrese numero de comprobante o cheque.', 'info');
      return false;
    }

    if (parseFloat(mon) > parseFloat(total_fac)) {
      Swal.fire('', 'El monto no debe superaral total de la factura.', 'info');
      return false;
    }
    if (tip == '') {
      Swal.fire('', 'Seleccione tipo de pago.', 'info');
      return false;
    }


    var parametros = {
      'fecha': fec,
      'monto': mon,
      'cheqcomp': comp,
      'pago': $('#ddl_tipo_pago option:selected').text(),
      'fac': id,
      'falt': total_res - mon,
      'banco': ban,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/cuentas_x_cobrarC.php?add_abono=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Abono agregado.', 'success');
          $('#txt_monto').val('');
          $('#txt_cheq_comp').val('');
          $('#ddl_tipo_pago').empty();
          $('#txt_cheq_comp').attr('readonly', true);
          abonos_a_factura(id);
        } else if (response == 2) {
          Swal.fire('', 'Factura cancelada en su totalidad.', 'success');
          $('#txt_monto').val('');
          $('#txt_cheq_comp').val('');
          $('#ddl_tipo_pago').empty();
          abonos_a_factura(id);
          $('#nuevo_abono').modal('hide');
        } else {
          Swal.fire('', 'No se pudo agregar.', 'error');
        }
      }

    });
  }

  function Cruzar_cheque(id) {

    var num = '<?php echo $num; ?>';
    var parametros = {
      'cheque_pos': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/cuentas_x_cobrarC.php?cruzar_cheque=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Cheque Cruzado.', 'success');
          abonos_a_factura(num);
          cheques_pos();
          cheques_cruzar(num);

        } else {
          Swal.fire('', 'No se pudo agregar.', 'error');
        }
      }

    });
  }

  function factura_imprimir1() {

    var datos = '<?php echo $num; ?>';
    var url = '../controlador/punto_ventaC.php?factura_pdf=true&fac=' + datos;
    window.open(url, '_blank');
    $.ajax({
      data: datos,
      url: url,
      type: 'post',
      dataType: 'json',
      success: function(response) {

      }
    });
  }

  function factura_imprimir() {

    var datos = '<?php echo $num; ?>';
    var url = '../controlador/punto_ventaC.php?piezas_compradas=true&fac=' + datos;
    window.open(url, '_blank');
    $.ajax({
      data: datos,
      url: url,
      type: 'post',
      dataType: 'json',
      success: function(response) {

      }
    });
  }

  function editar_datos_cliente() {
    if ($('#txt_id_cli').val() == '') {
      Swal.fire('', 'Seleccione un cliente primero.', 'info');
      return false;
    }
    var no = $('#txt_nombre_cli').val();
    var ci = $('#txt_ci_cli').val();
    var em = $('#txt_email_cli').val();
    var te = $('#txt_telefono_cli').val();
    var di = $('#txt_direccion_cli').val();
    var id = $('#txt_id_cli').val();
    var parametros = {
      'nom': no,
      'ci': ci,
      'ema': em,
      'tel': te,
      'dir': di,
      'id': id,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?update_cliente=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Datos de cliente actualizado.', 'success');
          datos_cliente_nuevo('', id);
        }
      }

    });
  }

  function limpiar_datos_cliente() {
    $('#txt_nombre_cli').val('');
    $('#txt_ci_cli').val('');
    $('#txt_email_cli').val('');
    $('#txt_telefono_cli').val('');
    $('#txt_direccion_cli').val('');
    $('#txt_id_cli').val('');
  }

  function bodegas_punto() {

    var punto = '<?php echo $punto; ?>';
    if (punto == '') {
      $('#modal_punto_venta').modal('show');
      $.ajax({
        // data:  {num:num},
        url: '../controlador/facturacionC.php?punto_venta=true',
        type: 'post',
        dataType: 'json',
        /*beforeSend: function () {   
             var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
           $('#tabla_').html(spiner);
        },*/
        success: function(response) {
          $('#ddl_puntos').html(response);
        }

      });
    }
  }

  function cargar_punto() {

    var id = $('#ddl_puntos').val();
    var nom = $('select[name="ddl_puntos"] option:selected').text();
    $('#txt_id_punto').val(id);
    $('#txt_nom_punto').text(nom);
    var URLactual = window.location;
    $(location).attr('href', URLactual + '?pnt=' + id);
  }

  function pasar_a_factura() {

    var num = '<?php echo $num; ?>';
    var ddl = $('#ddl_pasar').val();
    var num = '<?php echo $num; ?>';
    var est = '<?php echo $estado; ?>'
    var pun = '<?php echo $punto; ?>';
    if (ddl == '') {
      return false;
    }

    var parametros = {
      'fac': num,
      'tip': ddl,
    }


    Swal.fire({
      title: 'Quiere pasar Esta cotizacion a Factura?',
      text: "Esta seguro de querer pasar a factura!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {

        $.ajax({
          data: {
            parametros: parametros
          },
          url: '../controlador/presupuestosC.php?pasar_a_factura=true',
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if (response) {
              if (ddl == 'PR') {
                var url = 'presupuestos.php?numfac=' + num + '&doc=PR&est=' + est + '&pnt=' + pun;
                $(location).attr('href', url);
              } else {
                var url = 'facturacion.php?numfac=' + num + '&doc=FA&est=' + est + '&pnt=' + pun;
                $(location).attr('href', url);
              }
            }
          }
        });
      } else {
        $('#ddl_pasar').val('');
      }
    });
  }

  function tipo_pago() {
    $('#txt_interes').val(0);
    calcular_int();
    var tot = $('#txt_total_fa').val();
    // $('#txt_modal_pago_total').val(tot);
    $('#txt_val_fac').val(tot);
    var pago = $('#ddl_tipo_pago_1').val();
    var b = pago.split('_');
    if (b[1] == 1) {
      $('#txt_modal_pago_num_cheq').prop('readonly', false);
      $('#txt_banco').prop('readonly', false);
      $('#txt_fecha_efec').prop('readonly', false);
    } else {
      $('#txt_modal_pago_num_cheq').prop('readonly', true);
      $('#txt_banco').prop('readonly', true);
      $('#txt_fecha_efec').prop('readonly', true);
    }
    if (b[2] == 1) {
      $('#txt_interes').prop('readonly', false);
      $('#txt_interes').val(0);
    } else {
      $('#txt_interes').prop('readonly', true);
    }
  }

  function calcular_int() {
    var int = $('#txt_interes').val();
    var fac = $('#txt_val_fac').val();
    var tot_in = (int * fac) / 100;
    var tot = parseFloat(fac) + tot_in;
    // tot = parseFloat(tot).toFixed(2);
    $('#txt_val_int').val(tot_in.toFixed(2));
    $('#txt_modal_pago_total').val(tot.toFixed(2));
    // $('#txt_modal_monto').val(0);
  }

  function calcular_cuotas() {
    $('#tbl_b').html('');
    var m = $('#txt_meses').val();
    var mo = $('#txt_modal_monto').val();
    var f1 = $('#txt_modal_fecha').val();
    var t = $('#txt_modal_pago_total').val();
    var cre = $('#txt_credito').val();
    $('#txt_modal_credito').val(cre);
    var cre_mo = $('#txt_modal_credito').val();
    var f = new Date(f1);
    var fn = new Date(new Date(f).setMonth(f.getMonth())).toLocaleDateString();
    if (parseFloat(mo) > parseFloat(t)) {
      console.log(mo);
      console.log(t);
      Swal.fire('', 'El monto es mayor al total de la factura', 'info');
      $('#txt_modal_monto').val(0);
      return false;
    }
    if (cre == 0 && m > 1) {
      Swal.fire('', 'Credito insifuciente para realizar varios pagos', 'info');
      $('#txt_meses').val(1);
      return false;
    }
    if (cre == 0 && m == 1 && mo < t) {
      Swal.fire('', 'El monto debe ser el total de la factura por que su credito es insuficiente', 'info');
      $('#txt_modal_monto').val(t);
      return false;
    }

    if ((mo < t) && m == 1) {
      Swal.fire('', 'El monto debe ser el total de la factura si requiere hacer un pago', 'info');
      $('#txt_modal_monto').val(t);
      return false;
    }

    if (mo == '') {
      mo = 0;
    }
    if (m == '') {
      m = 1;
      $('#txt_meses').val(1)
    }
    var v = (t - mo) / m;
    var tr = '<tr><td>' + fn + '</td><td>' + mo + '</td></tr>';
    var ii = 1;
    if (m == 1) {
      m = 0;
      cre_mo = parseFloat(cre_mo) - parseFloat(v);
    }

    console.log(cre_mo);
    for (var i = 1; i <= m; i++) {
      var fn = new Date(new Date(f).setMonth(f.getMonth() + ii)).toLocaleDateString();
      tr += '<tr><td>' + fn + '</td><td>' + v.toFixed(2) + '</td></tr>';
      cre_mo = parseFloat(cre_mo) - parseFloat(v);
      ii += 1;
    }
    console.log();
    $('#txt_modal_credito').val(cre_mo.toFixed(2));
    $('#tbl_b').html(tr);
  }

  function generar_pagos() {
    var cre_mo = $('#txt_modal_credito').val();
    var m = $('#txt_meses').val();
    var mo = $('#txt_modal_monto').val();
    var f1 = $('#txt_modal_fecha').val();
    var t = $('#txt_modal_pago_total').val();
    var c = $('#txt_modal_pago_num_cheq').val();
    var fac = $('#txt_num_fac').val();
    var tp = $('select[name="ddl_tipo_pago_1"] option:selected').text();
    var tp_id = $('#ddl_tipo_pago_1').val();
    var tp_id = tp_id.split('_');
    var cre = $('#txt_credito').val();
    var int = $('#txt_interes').val();
    var ban = $('#txt_banco').val();
    var fe_efec = $('#txt_fecha_efec').val();

    if (int == '') {
      Swal.fire('', 'Interes vacio.', 'info');
      return false;
    }

    if (cre_mo < 0) {
      Swal.fire('', 'Su monto de credito es mayor al asignado para el cliente.', 'info');
      return false;
    }
    if (m == '' || mo == '') {
      Swal.fire('', 'Llene todo los campos.', 'info');
      return false;
    }
    if (tp_id[1] == '1' && c == '') {
      Swal.fire('', 'Llene todo los campos.', 'info');
      return false;
    }
    console.log(t);
    console.log(cre);
    console.log(m);


    var parametros = {
      'monto': mo,
      'meses': m,
      'total': t,
      'fecha': f1,
      'cheque': c,
      'factura': fac,
      'tipo': tp,
      'credito': cre_mo,
      'banco': ban,
      'fecha_efec': fe_efec,
    }
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/facturacionC.php?cuotas=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {

        if (response == 1) {
          var url = "facturacion.php?numfac=<?php echo $num; ?>&doc=<?php echo $doc; ?>&est=F";
          $(location).attr('href', url);

        } else if (response == 2) {
          Swal.fire('', 'Factura pagada en su totalidad.', 'success');
          var url = "facturacion.php?numfac=<?php echo $num; ?>&doc=<?php echo $doc; ?>&est=F";
          $(location).attr('href', url);

        }
      }
    });
  }

  function Eliminar_abono(id) {
    var idfac = $('#id_fac').val();
    Swal.fire({
      title: 'Desea eliminar este abono',
      text: "Esta seguro de eliminar el abono!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          data: {
            id: id
          },
          url: '../controlador/cuentas_x_cobrarC.php?eliminar_abono=true',
          type: 'post',
          dataType: 'json',
          success: function(response) {
            abonos_a_factura(idfac);
            facturas_pagagadas();
            facturas_por_pagar();
          }

        });
      }
    });
  }

  function cerrar_pago() {
    $('#ddl_tipo_pago_1').val('').trigger('change');
  }

  function finalizar() {
    cheques_pos();
    $('#modal_forma_pago').modal('show');
  }

  function Eliminar_cheque(id) {
    Swal.fire({
      title: 'Desea eliminar este cheque',
      text: "Esta seguro de eliminar!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          data: {
            id: id
          },
          url: '../controlador/facturacionC.php?eliminar_cheque=true',
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if (response == 1) {
              Swal.fire('', 'Cheque eliminado.', 'success');

            } else {
              Swal.fire('', 'Cheque no eliminado.', 'error');

            }
            cheques_pos();
          }

        });
      }
    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Facturación</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Blank
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
            <div class="card-title d-flex align-items-center">

              <h5 class="mb-0 text-primary">Facturación</h5>

            </div>


            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-6">
                    <a href="Facturacion.php" class="btn btn-success btn-sm">Nuevo</a>
                    <button class="btn btn-secondary btn-sm" onclick="factura_imprimir()">Imprimir</button>
                    <button class="btn btn-warning btn-sm" onclick="finalizar()">Finalizar</button>
                    <button class="btn btn-primary btn-sm" onclick="Agregar_Abono('<?php echo $num ?>');cheques_pos();cheques_cruzar('<?php echo $num ?>')">
                      Añadir abono
                    </button>
                  </div>
                  <div class="col-sm-6 text-end">
                    <a href="Facturacion.php?numfac=<?php echo $num; ?>&doc=<?php echo $doc; ?>&est=P" class="btn btn-primary btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm">Anular Factura</button>
                  </div>
                </div>

                <div id="pendiente_page">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col-sm-6">
                          <h2>FACTURACIÓN</h2>
                        </div>
                        <div class="col-sm-6 text-end">
                          <input type="hidden" name="txt_id_punto" id="txt_id_punto">
                          <p>Punto de venta: <b id="txt_nom_punto">Principal</b></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">Datos Personales</h3>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-5">
                              <label class="form-label"><b>Nombre</b></label>
                              <div class="input-group">
                                <input type="hidden" name="txt_id_cli" id="txt_id_cli">
                                <input type="text" name="txt_nombre_cli" id="txt_nombre_cli" class="form-control" onkeyup="solo_mayusculas(this.id,this.value);">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cliente_nuevo">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <label class="form-label"><b>CI / RUC</b></label>
                              <input type="text" name="txt_ci_cli" id="txt_ci_cli" class="form-control">
                            </div>
                            <div class="col-sm-3">
                              <label class="form-label"><b>Email</b></label>
                              <input type="text" name="txt_email_cli" id="txt_email_cli" class="form-control">
                            </div>
                            <div class="col-sm-3">
                              <label class="form-label"><b>Teléfono</b></label>
                              <input type="text" name="txt_telefono_cli" id="txt_telefono_cli" class="form-control" onkeyup="num_caracteres(this.id,10)">
                            </div>
                            <div class="col-sm-7">
                              <label class="form-label"><b>Dirección</b></label>
                              <input type="text" name="txt_direccion_cli" id="txt_direccion_cli" class="form-control">
                            </div>
                            <div class="col-sm-2">
                              <label class="form-label"><b>Fecha factura</b></label>
                              <input type="date" name="txt_fecha_fac" id="txt_fecha_fac" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                              <label class="form-label"><b>Monto de crédito</b></label>
                              <input type="text" name="txt_credito" id="txt_credito" class="form-control" value="0" readonly>
                            </div>
                            <div class="col-sm-2">
                              <label class="form-label"><b>Num. Factura</b></label>
                              <h2 id="numfac">0</h2>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                      <label for="txt_referencia" class="form-label"><b>Referencia</b></label>
                      <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                      <input type="hidden" name="txt_producto_id" id="txt_producto_id">
                      <label for="txt_producto" class="form-label"><b>Producto</b></label>
                      <input type="text" name="txt_producto" id="txt_producto" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                      <label for="txt_bodega" class="form-label"><b>Bodega</b></label>
                      <select class="form-select form-select-sm" name="txt_bodega" id="txt_bodega">
                        <option value="">Seleccione Bodega</option>
                      </select>
                    </div>
                    <div class="col-md-1">
                      <label for="txt_cantidad" class="form-label"><b>Cant</b></label>
                      <input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" value="1" onblur="calcular()">
                    </div>
                    <div class="col-md-1">
                      <label for="txt_precio" class="form-label"><b>Precio</b></label>
                      <input type="number" name="txt_precio" id="txt_precio" class="form-control form-control-sm" value="0" onblur="calcular()">
                    </div>
                    <div class="col-md-1">
                      <label for="txt_descuento" class="form-label"><b>% Desc</b></label>
                      <input type="number" name="txt_descuento" id="txt_descuento" class="form-control form-control-sm" value="0" onblur="calcular()">
                    </div>
                    <div class="col-md-1">
                      <label for="txt_subtotal" class="form-label"><b>Subtotal</b></label>
                      <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control form-control-sm" value="0" readonly>
                    </div>
                    <div class="col-md-1">
                      <label for="txt_total" class="form-label"><b>Total</b></label>
                      <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" value="0" readonly>
                    </div>
                    <div class="col-md-1 d-grid">
                      <button class="btn btn-primary btn-sm" onclick="crear_presupuesto();">
                        <i class="fas fa-shopping-cart"></i> Agregar
                      </button>
                    </div>
                  </div>

                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header p-2">
                          <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#todas" data-bs-toggle="tab">Items</a></li>
                          </ul>
                        </div>
                        <div class="card-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="todas">
                              <div id="tbl_pedido"></div>
                              <table class="table table-bordered table-sm">
                                <tr>
                                  <td colspan="5"></td>
                                  <td class="text-end">
                                    <h5>Subtotal:</h5>
                                    <h5>Descuento:</h5>
                                    <h5>IVA:</h5>
                                    <h5>Total:</h5>
                                  </td>
                                  <td>
                                    <input type="text" name="txt_subtotal_fa" id="txt_subtotal_fa" class="form-control text-end">
                                    <input type="text" name="txt_dcto_fa" id="txt_dcto_fa" class="form-control text-end">
                                    <input type="text" name="txt_iva_fa" id="txt_iva_fa" class="form-control text-end">
                                    <input type="text" name="txt_total_fa" id="txt_total_fa" class="form-control text-end">
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div id="finalizado_page" style="display: none;">
                  <div class="row">
                    <div class="col-sm-4"><b>Nombre:</b>
                      <p id="nombre_f">javier farinango</p>
                    </div>
                    <div class="col-sm-2"><b>CI:</b>
                      <p id="ci_f"></p>
                    </div>
                    <div class="col-sm-2"><b>Fecha Emisión:</b>
                      <p id="fecha_emi_f"></p>
                    </div>
                    <div class="col-sm-2"><b>Fecha Vencimiento:</b>
                      <p id="fecha_ven_f"></p>
                    </div>
                    <div class="col-sm-2"><b>Teléfono:</b>
                      <p id="telefono_f"></p>
                    </div>
                    <div class="col-sm-2"><b>Email:</b>
                      <p id="emial_f"></p>
                    </div>
                    <div class="col-sm-4"><b>Dirección:</b>
                      <p id="direccion_f"></p>
                    </div>
                    <div class="col-sm-4"><b>Num. Factura:</b>
                      <p id="numfac_f"></p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12" id="tbl_pedido_f"></div>
                  </div>
                  <div class="modal-footer">
                    <b>SUBTOTAL:</b>
                    <p id="txt_subtotal_fa_fin"></p>
                    <b>DESCUENTO:</b>
                    <p id="txt_dcto_fa_fin"></p>
                    <b>IVA:</b>
                    <p id="txt_iva_fa_fin"></p>
                    <b>TOTAL:</b>
                    <p id="txt_total_fa_fin"></p>
                  </div>
                </div>
              </div>
            </section>

          </div>
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="row">
          <div class="col-12">
            <label for="">Tipo de <label class="text-danger">*</label></label>
            <select name="" id="" class="form-select form-select-sm" onchange="">
              <option value="">Seleccione el </option>
            </select>
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12">
            <label for="">Blank <label class="text-danger">*</label></label>
            <select name="" id="" class="form-select form-select-sm">
              <option value="">Seleccione el </option>
            </select>
          </div>
        </div>

        <div class="row pt-3">
          <div class="col-12 text-end">
            <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>