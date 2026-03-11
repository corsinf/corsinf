$(document).ready(function () {
    DCTipoPago();
});

function cambio_serie(tipo,serie)
{
     var parametros = 
    {
        'serie':serie,
        'tipo':tipo,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/funcionesSistema.php?serie_selected=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#txt_serie').text(response.Serie);
            $('#txt_NoFac').text(response.numero);
            $('#txt_autorizacion').text(response.autorizacion);
            $('#btn_guardar_serie').css('display','initial');
            $('#btn_recargar').css('display','initial');

            console.log(response);         
        }
      });

}
function cargar_facturas()
{
    var query = $('#txt_query').val();
    var num = $('#txt_num_fac').val();
    var desde = $('#txt_desde').val();
    var hasta = $('#txt_hasta').val();
    var serie = $('#ddl_serie').val();

    var parametros = 
    {
        'query':query,
        'numfac':num,
        'desde':desde,
        'hasta':hasta,
        'serie':serie,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?lista_facturas=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#lista_facturas').html(response);          
        }
      });

}

function DCTipoPago() {
    var opcion = '<option value="">Seleccione tipo de pago</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/FACTURACION/lista_facturaC.php?DCTipoPago=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            //console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
            })
            $('#DCTipoPago').html(opcion);
          $('#DCTipoPago').val("01");
                    // console.log(response);
      }
    }); 
}

function series()
{
    
    $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?lista_serie=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#ddl_serie').html(response);          
        }
      });

}
function cargar_detalle(id,estado)
{
    location.href="inicio.php?mod="+modulo+"&acc=detalle_factura&id="+id+"&estado="+estado;
}

//----------------------------detalle factura------------------------

function detalle_factura(id)
{
      $.ajax({
        data:  {id:id},
        url:   '../controlador/FACTURACION/lista_facturaC.php?detalle_factura=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            console.log(response);
            $('#txt_nombre').html(response.factura.nombre);
            $('#txt_NoFac').text(response.factura.num_factura);
            $('#txt_autorizacion').text(response.factura.Autorizacion);
            $('#txt_serie').text(response.factura.serie);
            $('#txt_ci_ruc').text(response.factura.ci_ruc);
            $('#txt_telefono').val(response.factura.telefono);
            $('#txt_telefono2').val(response.factura.telefono);
            $('#txt_email').val(response.factura.mail);
            $('#txt_email_p').val(response.factura.mail);
            $('#txt_fecha').val(response.factura.fecha);
            $('#txt_direccion').val(response.factura.direccion);
            if(response.factura.Tipo_pago!='' && response.factura.Tipo_pago!=null && response.factura.Tipo_pago!='.')
            {
                $('#DCTipoPago').val(response.factura.Tipo_pago);
            }
            $('#txt_datos_adicionales').val(response.factura.datos_adicionales);
            if(response.factura.estado_factura?.trim() =='A')
            {
                 $('#txt_estado').text('Factura Autorizada');
                 $('#txt_estado').css('background','greenyellow');
                 $('#form_add_producto').css('display','none');                 
                  $('#btn_anular').css('display','initial');
                 $('#btn_eliminar').css('display','none');   
                $('#txt_estado').css('color','black');
            }else if(response.factura.estado_factura?.trim() =='R')
            {
                 $('#txt_estado').text('Factura rechazada');
                  $('#txt_estado').css('background','coral');
                  $('#txt_estado').css('color','white');
                  $('#btn_sri_error').css('display','initial');
                  $('#btn_eliminar').css('display','none');
            }else if(response.factura.estado_factura?.trim() =='AN'){

                 $('#btn_autorizar').css('display','none');
                 $('#form_add_producto').css('display','none');
                 $('#lbl_anulado').css('display','initial');
            }else
            {
                 $('#txt_estado').text('Factura pendiente');
            }
            if(response.factura.estado_pago == 1)
            {
                $('#btn_marcar_pagado').css('display','none');
                $('#txt_estadoP').text('Factura cobrada');
                $('#txt_estadoP').css('background','greenyellow');
                $('#txt_estadoP').css('color','black');

            }
            $("#lbl_totDcto").text(response.des);  
            $("#lbl_totIva").text(response.iva);   
            $("#lbl_subtot").text(response.sub);     
            $("#lbl_tot").text(response.total);

            $('#tbl_lineas').html(response.tr);    
            if(response.factura.estado_factura=='A')
            {
                $('#btn_autorizar').css('display','none');
                $('#opciones').css('display','none');
            }
            // detalle_guia(response.factura.serie,response.factura.num_factura);  
            lista_series('FA','txt_serie');      
        }
      });
}

function abrir_productos(de)
{
    if($('#txt_idpro').val()=='')
    {
        $('#myModal_productos').modal('show');
    }
    if(de=='ref')
    {
        $('#txt_ref').focus()
    }else
    {
        $('#txt_query').focus()
    }
    lista_articulos();
//     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
//   Open modal
// </button>
}

function lista_articulos()
{
    var parametros = 
    {
        'query':$('#txt_query').val(),
        'ref':$('#txt_ref').val(),
        'cate':$('#ddl_categoria').val(),
        'tipo':$('input:radio[name=opc]:checked').val()
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?buscar_articulo=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#tbl_productos').html(response);          
        }
      });
}

function usar(id,ref,det,pvp,iva)
{
    $('#myModal_productos').modal('hide');
    $('#txt_can').val(1);
    $('#txt_codigo').val(ref);
    $('#txt_articulo').val(det);
    $('#txt_pvp').val(pvp);
    $('#txt_idpro').val(id);
    $('#txt_can').focus();

    $('#txt_query').val('');
    $('#txt_ref').val('');
    $('#ddl_categoria').val(0);
    $('#txt_llevaiva').val(iva);
}

function categorias()
  {
    $('#ddl_categoria').select2({
      // width: 'resolve',
      placeholder: 'categoria',
      ajax: {
        url:   '../controlador/FACTURACION/lista_facturaC.php?categorias=true',  
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

function calcular()
{
    var cant = $('#txt_can').val();
    var por_iva = $('#lbl_iva').text();

    var lleva = $('#txt_llevaiva').val();

    if(cant=='' || cant==0)
    {
        $('#txt_can').val(1);
    }
    var cant = $('#txt_can').val();
    var dcto = $('#txt_dcto').val();
    if(dcto=='')
    {
        $('#txt_dcto').val(0);
    }    
    var dcto = $('#txt_dcto').val();
    var pvp = $('#txt_pvp').val();
    if(pvp=='')
    {
        $('#txt_pvp').val(0);
    }
    var pvp = $('#txt_pvp').val();
    var pvp = parseFloat(pvp);

    console.log(pvp)

    var sub = cant*pvp;
    if(lleva==1)
    {
      var iva = parseFloat((sub*parseFloat(por_iva))/100);
    }else
    {
        iva = 0;
        $('#txt_iva').attr('readonly');   
    }
    var des = parseFloat((sub*parseFloat(dcto))/100);
    var total = sub-des+iva;

    $('#txt_sub').val(sub.toFixed(2));
    $('#txt_iva').val(iva.toFixed(2));    
    $('#txt_total').val(total.toFixed(2));
}


function limpiar()
{
    $('#txt_total').val('0.00');
    $('#txt_articulo').val('')
    $('#txt_sub').val('0.00');
    $('#txt_dcto').val('0.00');
    $('#txt_can').val('1');
    $('#txt_pvp').val('0.00');
    $('#txt_iva').val('0.00');
    $('#txt_idpro').val('');
    $('#txt_codigo').val('')
}

function agregar_factura()
{
    var id = $('#txt_fac').val();
    var parametros = 
    {
        'total':$('#txt_total').val(),
        'articulo':$('#txt_articulo').val(),
        'subto':$('#txt_sub').val(),
        'desc':$('#txt_dcto').val(),
        'cant':$('#txt_can').val(),
        'pvp':$('#txt_pvp').val(),
        'iva':$('#txt_iva').val(),
        'fac':$('#txt_fac').val(),
        'id':$('#txt_idpro').val(),
        'tipopago':$('#DCTipoPago').val(),
        'fecha':$('#txt_fecha').val(),
        'detalle':$('#txt_detalle').val(),
        'adicionales':$('#txt_datos_adicionales').val(),
    }
    if($('#txt_idpro').val()=='')
    {
        Swal.fire('Seleccione un producto','','info');
        return false
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?add_articulo=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
            Swal.fire('Agregado','','success');
            limpiar();
            console.log(id);
            detalle_factura(id);
         }        
        }
      });
}

function Eliminar(id)
{      
  var id = id;
    Swal.fire({
        title: 'Esta seguro de eliminar?',
        text: "Esta a punto de borrar un registro",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
        }).then((result) => {
            if (result.isConfirmed) {
              delete_linea(id);
            }
        })
}

function delete_linea(id)
{     
  var fac = $('#txt_fac').val();
  var parametros=
  {
    'id':id,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/FACTURACION/lista_facturaC.php?delete_linea=true',      
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
     if(response==1)
     {
      Swal.fire('','Linea Eliminada', 'success');
       detalle_factura(fac);
     }               
    }
  });

}

function autorizar(fac=false)
{
    if(fac)
    {
        var f = fac;
    }else
    {
        var f = $('#txt_fac').val();
    } 
    if($('#txt_serie').text()=='')
    {
        Swal.fire('','Seleccione una Serie','info');
        return false;
    }
    if($('#btn_guardar_serie').is(':visible'))
    {
        Swal.fire('','Guarde la serie seleccionada','info');
        return false;
    }

    $('#alertas').modal('show');
    var parametros = 
    {
        'fac':f,
        'tipopago':$('#DCTipoPago').val(),
        'fecha':$('#txt_fecha').val(),
        'telefono':$('#txt_telefono').val(),
        'email':$('#txt_email_p').val(),
        'adicionales':$('#txt_datos_adicionales').val(),
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?facturar=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          $('#alertas').modal('hide');
          console.log(response);
          if(response[0]==1 || response[0]==3)
          {
            Swal.fire({
              title: 'Factura autorizada',
              text: 'Factura enviada al sri',
              icon: 'success',
              allowOutsideClick: false
            }).then(function() 
                {
                    modal_email(1);
                    enviar_email();
                    location.reload()
                }
            );

            imprimir(factura);
            limpiar_mesa(mesa);
           

          }else if(response[0]==4)
          {            
            Swal.fire({
              title: 'Factura generado',
              text: '',
              icon: 'success',
              allowOutsideClick: false
            }).then(function(){
                    modal_email(1);
                    enviar_email();
                    location.reload()
                }
            );

            imprimir(factura);
            limpiar_mesa(mesa);
          }else
          {
             Swal.fire({
                  title: response[2],
                  text: response[3],
                  icon: 'info',
                  allowOutsideClick: false
                }).then(() => location.reload());             
          }

          // // limpiar_cliente();
          // if(response==2){
          //   Swal.fire('','XML devuelto', 'error').then(function(){
          //     location.reload();
          //   });  
          // }else if(response==3)
          // {                
          //   Swal.fire('','Este documento electronico ya esta autorizado', 'info').then(function(){
          //     location.reload();
          //   });                      
          // }else if(response==1)
          // {           
          //   Swal.fire('','Factura autorizada', 'success').then(function(){
          //     location.reload();
          //   });  
          //   imprimir(factura);
          //   limpiar_mesa(mesa);
          // }else if(response==4)
          // {
          //   Swal.fire('','Factura Generada', 'success').then(function(){
          //     location.reload();
          //   }); 
          //   imprimir(factura);
          //   limpiar_mesa(mesa);
          // }else if(response.trim()=='XML firmado no encontrado')
          // {
          //   Swal.fire('','Firma digital no encontrada','info')
          // }
          // else{
          //   Swal.fire('','Conexion con SRI inestable','info')
          // }
        },
        error: function(xhr, textStatus, error){
            $('#alertas').modal('hide');
              $('#lbl_mensaje').text(xhr.statusText);
              alert(xhr.statusText);
              alert(textStatus);
              alert(error);
            }
      });

}

function pdf_factura()
  {
    var id= $('#txt_fac').val();
    var pago = $('#DCTipoPago').val();
    var parametros = 
    {
        'empresa':empresa,
        'fac':id,
        'usu':usu,
        'pago':pago,
    }
     var url1= '../controlador/FACTURACION/lista_facturaC.php?reporte_factura=true&empresa='+empresa+'&fac='+id+'&usu='+usu+'&pago='+pago;
     window.open(url1,'_blank');
   
  }

  

  function modal_email(envio_automatico = false)
  {
    if(!envio_automatico)
    {
        $('#myModal_email').modal('show');
    }
    $('#emails-input').html('<div class="emails emails-input"><span role="email-chip" class="email-chip"><span>'+$('#txt_email_p').val()+'</span><a href="#" class="remove">×</a></span><input type="text" role="emails-input" placeholder="añadir email ...">       </div>')
    $('#txt_to').val($('#txt_email_p').val()+',');
    $('#txt_fac_ema').val($('#txt_fac').val());
  
  }

  function enviar_email()
  {
    var to = $('#emails-input').val();
    var cuerpo = $('#txt_texto').val();
    var pdf_fac = $('#cbx_factura').prop('checked');
    var adjunto =  new FormData(document.getElementById("form_img"));

    console.log(to);
    parametros = 
    {
        'to':to,
        'cuerpo':cuerpo,
        'pdf_fac':pdf_fac,
        'adjunto':adjunto,
    }
    $('#myModal_email').modal('show');
     $.ajax({
        data: adjunto,
        url:   '../controlador/facturar.php?enviar_email_detalle=true',
        contentType: false,
        processData: false,
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    $('#myModal_email').modal('hide');
                })
            }else
            {
                Swal.fire('Email no enviado','Revise que sea un correo valido','info');
                 $('#myModal_email').modal('hide');
            }
         
        },
        error: function (error) {
          console.error('Error en numero_comprobante:', error);
          // Puedes manejar el error aquí si es necesario
           $('#myModal_email').modal('hide');
        },
      });

  }


  function modal_guia()
  {
    if(estadofac=='A')
    {
         numero_guia();
         DCCiudadI()
         DCCiudadF()
         AdoPersonas()
         DCEmpresaEntrega()
         $('#myModal_guia').modal('show');
    }else
    {
        Swal.fire('Asegurese de que la Factura este autorizada');
    }
  }


  function modal_guia_editar()
  {
    $('#myModal_guia').modal('show');
     DCCiudadI()
     DCCiudadF()
     AdoPersonas()
     DCEmpresaEntrega()
    serie = $('#txt_serie').text();
    factura = $('#txt_NoFac').text();
     parametros = 
    {
        'serie':serie,
        'factura':factura,
    }
    $.ajax({
        data: {parametros,parametros},
        url:   '../controlador/FACTURACION/facturar.php?detalle_guia=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 

           
                $('#btn_guia').css('display','block');
                $('#btn_modal_guia').css('display','none');

                $('#LblSerieGuiaR').attr('readonly',false);
                $('#LblGuiaR').attr('readonly',false);


                $('#txt_idGR').text(response[0].ID);
                $('#lbl_fechagr').val(response[0].FechaGRE);
                $('#LblSerieGuiaR').val(response[0].Serie_GR);
                $('#LblGuiaR').val(response[0].Remision);
                $('#LblAutGuiaRem').text(response[0].Autorizacion_GR);
                $('#MBoxFechaGRI').val(response[0].FechaGRI);
                $('#MBoxFechaGRF').val(response[0].FechaGRF);
                
                $('#DCCiudadI').append($('<option>',{value: response[0].CiudadGRI, text: response[0].CiudadGRI,selected: true }));
                $('#DCCiudadF').append($('<option>',{value: response[0].CiudadGRF, text: response[0].CiudadGRF,selected: true }));
                 
                $('#DCRazonSocial').append($('<option>',{value: response[0].CIRUC_Comercial, text:response[0].Comercial,selected: true }));
                $('#DCEmpresaEntrega').append($('<option>',{value: response[0].CIRUC_Entrega, text:response[0].Entrega,selected: true }));
                               
                $('#TxtPlaca').val(response[0].Placa_Vehiculo);
                $('#TxtPedido').val(response[0].Pedido);
                $('#TxtZona').val(response[0].Zona);
                $('#TxtLugarEntrega').val(response[0].Lugar_Entrega);
                
                console.log(response); 
            }
            // $('#LblGuiaR').val(response);     
      });
    
  }


  function  numero_guia()
  {
    $.ajax({
        // data: adjunto,
        url:   '../controlador/facturar.php?numero_guia=true',
        contentType: false,
        processData: false,
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            console.log(response); 
            $('#LblGuiaR').val(response);     
        }
      });
  }



function guardar_guia()
{    
    datos = $('#form_guia').serialize();
    razon = $('#DCRazonSocial option:selected').text();
    entrega = $('#DCEmpresaEntrega option:selected').text();
    datos = datos+'&RazonSocial='+razon+'&entrega='+entrega;
    $.ajax({
        data: datos+'&fac='+$('#txt_fac').val(),
        url:   '../controlador/facturar.php?guardar_guia=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {
                Swal.fire({
                title: 'Guia de remision Guardada',
                // text: "Desea autorizar con sri",
                icon: 'success',
                showCancelButton: false,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Autorizar sri',
                }).then((result) => {
                    if (result.isConfirmed) {

                        $('#myModal_guia').modal('hide');
                        autorizar_guia();
                    }
                })

            }else
            {
                Swal.fire('Se producio un error inesperado','','error');
            }
        }
      });
}


function detalle_guia(serie,factura)
{
    parametros = 
    {
        'serie':serie,
        'factura':factura,
    }
    $.ajax({
        data: {parametros,parametros},
        url:   '../controlador/facturar.php?detalle_guia=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 

            if(response.length>0)
            {
                $('#btn_guia').css('display','block');
                $('#btn_modal_guia').css('display','none');



                $('#txt_idGR').text(response[0].ID);
                $('#lbl_fechagr').text(response[0].FechaGRE);
                $('#lbl_seriegr').text(response[0].SerieGR);
                $('#lbl_numeroguia').text(response[0].Remision);
                $('#lbl_autorizaciongr').text(response[0].Autorizacion_GR);
                $('#lbl_fechaini').text(response[0].FechaGRI);
                $('#lbl_fechafin').text(response[0].FechaGRF);
                $('#lbl_ciudadini').text(response[0].CiudadGRI);
                $('#lbl_ciudadfin').text(response[0].CiudadGRF);
                $('#lbl_razon').text(response[0].Comercial);
                $('#lbl_entrega').text(response[0].Entrega);
                $('#lbl_placa').text(response[0].Placa_Vehiculo);
                $('#lbl_pedido').text(response[0].Pedido);
                $('#lbl_zona').text(response[0].Zona);
                $('#lbl_lugar').text(response[0].Lugar_Entrega);
                if(response[0].Estado_SRI_GR=='P')
                {
                    $('#alerta_error').css('display','block')
                    $('#alerta_no_auto').css('display','block')
                    $('#alerta_success').css('display','none')
                }else
                {
                    $('#alerta_success').css('display','block')
                    $('#alerta_no_auto').css('display','none')
                    $('#alerta_error').css('display','none')
                }
                console.log(response); 
            }
            // $('#LblGuiaR').val(response);     
        }
      });
}

function autorizar_guia(fac=false)
{
    $('#alertas').modal('show');    
    $('#tipo_alerta').text('Autorizando Guia de remision');
    if(fac)
    {
        var f = fac;
    }else
    {
        var f = $('#txt_fac').val();
    } 
    var parametros = 
    {
        'fac':f,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/facturar.php?autorizar_guia=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          $('#alertas').modal('hide');
          // console.log(response);
          // limpiar_cliente();
          if(response==2){
            Swal.fire('','XML devuelto', 'error').then(function(){
              location.reload();
            });  
          }else if(response==3)
          {                
            Swal.fire('','Este documento electronico ya esta autorizado', 'info');                    
          }else if(response==1)
          {           
            Swal.fire('','Guia de remision autorizada', 'success').then(function(){
              location.reload();
            });  
            imprimir(factura);
            limpiar_mesa(mesa);
          }else if(response==4)
          {
            Swal.fire('','Guia de remision Generada', 'success').then(function(){
              location.reload();
            }); 
            imprimir(factura);
            limpiar_mesa(mesa);
          }else{
            Swal.fire('','Conexion con SRI inestable','info')
          }
        },
        error: function(xhr, textStatus, error){
            $('#alertas').modal('hide');
              $('#lbl_mensaje').text(xhr.statusText);
              alert(xhr.statusText);
              alert(textStatus);
              alert(error);
            }
      });

}


function validar_campo()
{
    deta = $('#txt_articulo').val();
    if(deta.length==0)
    {
        $('#txt_idpro').val('');
        $('#txt_ref').val('');
        $('#myModal_productos').modal('show');
    }
}

function eliminar_factura(id)
{
    Swal.fire({
    title: 'Desea eliminar!',
    text: "Este proceso no se podra revertir",
    icon: 'warning',
    showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminar(id);
        }
    })
}


function eliminar(id)
{
    parametros = 
    {
        'id':id,
    }
    $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?eliminar_fac=true',
        dataType:'json',      
        type:  'post',
        success:  function (response) { 
            console.log(response); 
            if(response==1)
            {
                location.href ='../vista/inicio.php?mod='+modulo+'&acc=lista_facturas';
            }
        }
      });
}

function anular_factura(id)
{
    Swal.fire({
      title: 'esta seguro de anular este documento?',
      text: "Este proceso no se podra revertir",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.isConfirmed) {
         anular(id);        
      }
    })
}

function anular(id)
{
     $.ajax({
        data:  {id,id},
        url:   '../controlador/FACTURACION/lista_facturaC.php?anular_factura=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {                              
                location.href ='../vista/inicio.php?mod='+modulo+'&acc=lista_facturas';
            }
        }
      });
}

function guardar_serie()
{
    Swal.fire({
      title: 'Esta seguro de Cambiar la serie?',
      text: "Este proceso no se podra revertir",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.isConfirmed) {
         cambiar_serie();        
      }
    })
}

function cambiar_serie()
{
    parametros = {
        'idfac':$('#txt_fac').val(),
        'serie':$('#txt_serie').text(),
        'numero':$('#txt_NoFac').text(),
        'auto':$('#txt_autorizacion').text(),
    }
     $.ajax({
        data:  {parametros,parametros},
        url:   '../controlador/lista_facturaC.php?editar_serie=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {               
                location.reload();
            }
        }
      });

}

function pagada(id)
{
     parametros = {
        'id':id,
    }
     $.ajax({
        data:  {parametros,parametros},
        url:   '../controlador/FACTURACION/lista_facturaC.php?pagada=true',      
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {               
               Swal.fire("La factura fue marcada como pagada",'',"success").then(function(){
                    location.reload();
               })
            }
        }
      });

}

function lista_series(tipo,campo)
{
    actual = $('#'+campo).text();
    console.log(actual);
    var parametros = 
    {
        'tipo':tipo,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:    '../controlador/FACTURACION/lista_facturaC.php?lista_series=true',           
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            console.log(response)
            var option = '';
            response.forEach(function(item,i){
                if(item.Serie!=actual)
                {
                    option+='<button type="button" class="dropdown-item" onclick="cambio_serie(\''+tipo+'\',\''+item.Serie+'\',)">'+item.Serie+'</button>';
                }
            
            })
            // console.log(option)
            $('#opciones').html(option);
            console.log(response);           
        }
      });

}

