<?php include('./header.php');include('../controlador/punto_ventaC.php'); $num=''; $doc='';$estado = 'P'; if(isset($_GET['numfac']))
{
  $num = $_GET['numfac'];
}if(isset($_GET['doc'])){$doc = $_GET['doc'];} if(isset($_GET['est'])){$estado = $_GET['est'];} ?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script type="text/javascript">
	$('body').addClass('sidebar-mini layout-fixed sidebar-collapse');
	
</script>
<script type="text/javascript">
	 $( document ).ready(function() {
     autocoplet_tipo_pago();
    var num = '<?php echo $num;?>';
    var doc = '<?php echo $doc;?>';
    var est = '<?php echo $estado; ?>'
    botones();
    if(num=='')
    {
      $('#cliente_facturar').modal('show');
    }
    if(num!='' && doc!='')
    {
       datos_factura();
    }
    if(est=='F')
    {
      finalizar_factura(num);  
    }
     $('#txt_num_fac').val(num);
     cargar_pedido();
     cargar_pedido_f();
     autocoplet_cliente();


        $( "#txt_referencia" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "../controlador/punto_ventaC.php?search",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#txt_producto').val(ui.item.label); // display the selected text
                $('#txt_referencia').val(ui.item.value); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $( "#txt_referencia" ).val( ui.item.value );
                return false;
            },
        });
        // ________________________________________________________
        // ________________________________________________________

        $( "#txt_producto" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "../controlador/punto_ventaC.php?search",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#txt_producto').val(ui.item.nombre); // display the selected text
                $('#txt_referencia').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $('#txt_producto').val(ui.item.nombre); // display the selected text
                $('#txt_referencia').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
               
                return false;
            },
        });
    });

   function datos_factura()
   {

    var parametros = 
    {
      'id':'<?php echo $num;?>',
      'doc':'<?php echo $doc;?>',
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/punto_ventaC.php?datos_cliente=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
           if (response) 
           {
            $('#cliente').text(response.nombre);
            $('#txt_nombre_cli').val(response.nombre);
            $('#numfac').text(response.fac);
            $('#txt_ci_cli').val(response.ci);
            $('#txt_telefono_cli').val(response.tel);
            $('#txt_email_cli').val(response.email);
            $('#txt_fecha_fac').val( response.fecha.date.substring(0,10));
            $('#txt_fecha_exp').val( response.fecha_ven.date.substring(0,10));


            $('#nombre_f').text(response.nombre);
            $('#ci_f').text(response.ci);
            $('#fecha_emi_f').text(response.fecha.date.substring(0,10));
            $('#fecha_ven_f').text(response.fecha_ven.date.substring(0,10));
            $('#telefono_f').text(response.tel);
            $('#emial_f').text(response.email);
            $('#direccion_f').text(response.dir);
            $('#numfac_f').text(response.fac);
           } 
          } 
          
       });


   }

   function autocoplet_tipo_pago(){
      $('#ddl_tipo_pago').select2({
        placeholder: 'Seleccione una familia',
        width:'90%',
        ajax: {
          url:   '../controlador/cuentas_x_cobrarC.php?tipo_pago=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }


   function autocoplet_cliente(){
    let tipo = $('input:radio[name=rbl_tipo]:checked').val(); 
    console.log(tipo);
      $('#ddl_cliente').select2({
        placeholder: 'Seleccione cliente',
        width:'90%',
        ajax: {
          url:   "../controlador/punto_ventaC.php?search_cliente&tipo="+tipo,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

   function finalizar_factura(num)
   {  
    
    $.ajax({
         data:  {num:num},
         url:   '../controlador/punto_ventaC.php?finalizar_factura=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
           
           } 
          } 
          
       });

   }

  function cargar_pedido_f()
   {  
    var parametros = 
    {
      'id':$('#txt_num_fac').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/punto_ventaC.php?cargar_pedido_f=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
            console.log(response);
             existen_registros (response.tabla);
            $('#tbl_pedido_f').html(response.tabla);
            $('#txt_subtotal_fa_fin').text(response.subtotal);
            $('#txt_dcto_fa_fin').text(response.dcto);
            $('#txt_iva_fa_fin').text(response.iva);
            $('#txt_total_fa_fin').text(response.total);


           } 
          } 
          
       });

   }



   function cargar_pedido()
   {  
    var parametros = 
    {
      'id':$('#txt_num_fac').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/punto_ventaC.php?cargar_pedido=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
            console.log(response);
             existen_registros (response.tabla);
            $('#tbl_pedido').html(response.tabla);
            $('#txt_subtotal_fa').val(response.subtotal);
            $('#txt_dcto_fa').val(response.dcto);
            $('#txt_iva_fa').val(response.iva);
            $('#txt_total_fa').val(response.total);


           } 
          } 
          
       });

   }

   function existen_registros (tabla) {
    let filas = $(tabla).find('tbody tr').length;
    if(filas > 0) {
      $('#txt_tr').val(1);
      // return 1;
      }
      else {
      $('#txt_tr').val(0);
      }
    }


    function crear_documento()
    {
     var idC = $('#ddl_cliente').val();
     var tid = $('#ddl_documento').val();
     var nuf = $('#txt_num_fac').val();
     var tip = $('input:radio[name=rbl_tipo]:checked').val();                
     var datos = 
     {
       'cli':idC,
       'tip':tip,
       'doc':tid,
       'nuf':nuf,
       'fefa':$('#txt_fecha_fac').val(),
       'feex':$('#txt_fecha_exp').val(),
     }
     if(idC=='')
     {
      Swal.fire('','Asegurese primero de Seleccionar una cliente.','info');
       return false;
     }

    $.ajax({
         data:  {datos:datos},
         url:   '../controlador/punto_ventaC.php?crear_documento=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!=-1) 
           {
             var url="punto_venta.php?numfac="+response.id+'&doc='+response.tipo;
             $(location).attr('href',url);
           } 
          } 
          
       });
     

    }

   function agregar()
   {
     var can = $('#txt_cantidad').val();
     var pre = $('#txt_precio').val();
     var des = $('#txt_descuento').val();
     var tot = $('#txt_total').val();
     var pro = $('#txt_producto').val();
     var idf = $('#txt_num_fac').val();  
     var fefa = $('#txt_fecha_fac').val();  
     var feex = $('#txt_fecha_exp').val();  
     var ref = $('#txt_referencia').val();  
     if(pro=='')
     {
      Swal.fire('', 'Seleccione un articulo', 'info');
       return false;
     }             
     var datos = 
     {
       'pro':pro,
       'can':can,
       'pre':pre,
       'des':des,
       'tot':tot,
       'idf':idf,
       'fefa':fefa,
       'feex':feex,
       'ref':ref,
     }

    $.ajax({
         data:  {datos:datos},
         url:   '../controlador/punto_ventaC.php?add_pedido=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!=-1) 
           {            
              cargar_pedido();
           } 
          } 
          
       });
     

   }
  function calcular()
  {
    var cant = $('#txt_cantidad').val();
    var prec = $('#txt_precio').val();
    var desc = $('#txt_descuento').val();
    var iva = 0;
    if(cant=='')
    {
      $('#txt_cantidad').val(0);cant = $('#txt_cantidad').val();      
    }
    if(prec=='')
    {
      $('#txt_precio').val(0);prec = $('#txt_precio').val();
    }
    if(desc=='')
    {
      $('#txt_descuento').val(0);desc = $('#txt_descuento').val();
    }
    // if(iva=='')
    // {
    //   $('#txt_iva').val(0);iva = $('#txt_iva').val();
    // }

    var sub = cant*prec;
    var val_des = (desc*sub)/100;

    if(iva==0)
    {
      console.log('sin iva');
      var total = sub-val_des;
      $('#txt_total').val(total.toFixed(4));
      $('#txt_subtotal').val((sub-val_des).toFixed(4));
    }else
    {
      console.log('con iva');
      var total = (sub-val_des)*1.12;
      var iva = total-(sub-val_des);
      $('#txt_total').val(total);
      $('#txt_subtotal').val(sub-val_des);
    }

   

    // var cant = $('#').val();
  }

  function new_usuario()
  {
    if($('#txt_nombre_new').val()=='' || $('#txt_ci_new').val()=='' || $('#txt_telefono').val()=='' || $('#txt_emial').val()=='' || $('#txt_dir').val()=='')
    {
        Swal.fire('','Llene todo los campos.','info');
      return false;
    }

     var datos = $('#form_usuario_new').serialize();
    $.ajax({
         data:  datos,
         url:   '../controlador/punto_ventaC.php?new_usuario=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if (response==1) 
            {
               Swal.fire('','Nuevo cliente registrado.','success');
            }else
            {
              Swal.fire('', 'UPs aparecio un problema', 'success');
            }          
           
          } 
          
       });
  }

   function Eliminar(id)
  {
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
         data:  {id:id},
         url:   '../controlador/punto_ventaC.php?eliminar_linea=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success');
            cargar_pedido();
           }else
           {
            Swal.fire('','No se pudo elimnar.','info')
           }
          } 
          
       });}
      });
   }


   function botones()
   {
     var est = '<?php echo $estado;?>';
    if(est=='P')
    {
      $('#finalizado_page').css('display','none');
      $('#pendiente_page').css('display','block');
      $('#btn_editar').css('display','none');
      $('#btn_fin').css('display','initial');
      $('#btn_abono').css('display','none');
    }else
    {
      $('#finalizado_page').css('display','block');
      $('#pendiente_page').css('display','none');
      $('#btn_editar').css('display','initial');
      $('#btn_fin').css('display','none');
      $('#btn_abono').css('display','initial');
    }
   }

  function Agregar_Abono(id)
  {
    $('#nuevo_abono').modal('show');
    abonos_a_factura(id);
  }
  function abonos_a_factura(id)
  {
    var parametros = 
    {
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/cuentas_x_cobrarC.php?abonos_tabla=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            // console.log(response);
           $('#tbl_abonos').html(response.tabla);
           $('#txt_total_abono').val(response.total_abono);     
           $('#txt_total_factura').val(response.total);     
           $('#txt_restante_factura').val(response.faltante);           
          } 
          
       });
  }

    function habilitar_cheq_comp()
  {
    var tip = $('#ddl_tipo_pago').val();
    var t = tip.split('_');
    if(t[1]==0)
    {
      $('#txt_cheq_comp').attr('readonly',true);
      $('#txt_cheq_comp').val('');
    }else
    {
      $('#txt_cheq_comp').attr('readonly',false);
    }
  }

  function ingresar_abono()
  {
    var total_abo = $('#txt_total_abono').val();     
    var total_fac = $('#txt_total_factura').val();     
    var total_res = $('#txt_restante_factura').val();  
    var tip = $('#ddl_tipo_pago').val();
    var mon = $('#txt_monto').val();
    var comp = $('#txt_cheq_comp').val();
    var fec = $('#txt_fecha_abono').val();
    var id = $('#id_fac').val();
     if(mon=='' || !is_numeric(mon))
    {
      Swal.fire('','Monto invalido.','info');
      return false;
    }
    var t = tip.split('_');
    if(t[1]=='1' && comp=='')
    {
      Swal.fire('','Ingrese numero de comprobante o cheque.','info');
      return false;
    }

    if(parseFloat(mon)>parseFloat(total_fac))
    {
      Swal.fire('','El monto no debe superaral total de la factura.','info');
      return false;
    }
    if(tip=='')
    {
      Swal.fire('','Seleccione tipo de pago.','info');
      return false;
    }
   

     var parametros = 
    {
      'fecha':fec,
      'monto':mon,
      'cheqcomp':comp,
      'pago':$('#ddl_tipo_pago option:selected').text(),
      'fac':id,
      'falt': total_res-mon,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/cuentas_x_cobrarC.php?add_abono=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if (response==1) 
            {
              Swal.fire('','Abono agregado.','success');
              $('#txt_monto').val('');
              $('#txt_cheq_comp').val('');
              $('#ddl_tipo_pago').empty();
              $('#txt_cheq_comp').attr('readonly',true);
              abonos_a_factura(id);
            }else if(response == 2)
            {              
              Swal.fire('','Factura cancelada en su totalidad.','success');
              $('#txt_monto').val('');
              $('#txt_cheq_comp').val('');
              $('#ddl_tipo_pago').empty();
              abonos_a_factura(id);
              $('#nuevo_abono').modal('hide');
            }else
            {
              Swal.fire('','No se pudo agregar.','error');
            }
          } 
          
       });
  }

   function factura_imprimir()
  {

     var datos =  '<?php echo $num;?>';
    var url='../controlador/punto_ventaC.php?factura_pdf=true&fac='+datos;
    window.open(url, '_blank');
     $.ajax({
         data:  datos,
         url:   url,
         type:  'post',
         dataType: 'json',
         success:  function (response) {  
          
          } 
       });
  }

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1 class="m-0 text-dark">Punto de venta normal</h1> -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row">
          <div class="col-sm-6">
            <button class="btn btn-success btn-sm" id="btn_">Nuevo</button>
            <button class="btn btn-default btn-sm" id="btn_" onclick="factura_imprimir()">Imprimir</button>
            <a href="punto_venta.php?numfac=<?php echo $num;?>&doc=<?php echo $doc;?>&est=F" class="btn btn-warning btn-sm" id="btn_fin">Finalizar</a>
            <button class="btn btn-primary btn-sm" id="btn_abono" onclick="Agregar_Abono('<?php echo $num?>')">Añadir abono</button>
          </div>
          <div class="col-sm-6 text-right">
            <a href="punto_venta.php?numfac=<?php echo $num;?>&doc=<?php echo $doc;?>&est=P" class="btn btn-primary btn-sm" id="btn_editar">Editar</a>
            <button class="btn btn-danger btn-sm" id="btn_">Anular Factura</button>
          </div>
      	</div>
        <div id="pendiente_page">
        <div class="row">
          <div class="col-sm-3">
            <b>Cliente:</b>
              <h2 id="cliente"></h2>
          </div> 
          <div class="col-md-7">
            <div class="row">
              <div class="col-sm-3">
                <b>Fecha factura</b>                
                <input type="date" name="txt_fecha_fac" id="txt_fecha_fac" class="form-control form-control-sm"  value="<?php echo date('Y-m-d');?>">
              </div>
              <div class="col-sm-3">
                <b>Fecha Exp</b>
                <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm" value="<?php echo date('Y-m-d');?>">
              </div>
              <div class="col-sm-4">
                <b>Forma de pago</b>
               <!--  <select class="form-control form-control-sm" id="ddl_tipo_pago">
                  <option value="">Seleccione forma de pago</option>
                </select> -->
              </div>              
            </div>        
          </div>         
          <div class="col-sm-2 text-center">
            <input type="hidden" name="txt_num_fac" id="txt_num_fac">
            <b>Num.Factura:</b><h2 id="numfac"></h2>
          </div>
        </div>  
        <hr>
        <div class="row">          
          <div class="col-sm-2">
            <b>Referencia</b>
            <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm">        
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="txt_producto_id" id="txt_producto_id" class="form-control form-control-sm">
            <b>Producto</b>
            <input type="text" name="txt_producto" id="txt_producto" class="form-control form-control-sm">        
          </div>
          <div class="col-sm-1">
            <b>Bodega</b>
            <input type="text" name="txt_bodega" id="txt_bodega" class="form-control form-control-sm" readonly="">
          </div>
          <div class="col-sm-1">
            <b>Cant</b>
            <input type="text" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" value="1" onblur="calcular()">
          </div>
          <div class="col-sm-1">
            <b>Precio</b>
            <input type="text" name="txt_precio" id="txt_precio" class="form-control form-control-sm" value="0" onblur="calcular()">
          </div>
          <div class="col-sm-1">
            <b>% Desc</b>
            <input type="text" name="txt_descuento" id="txt_descuento" class="form-control form-control-sm" value="0" onblur="calcular()">
          </div>
          <div class="col-sm-1">
            <b>Subtotal</b>
            <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control form-control-sm" value="0" readonly="">
          </div>
          <!-- <div class="col-sm-1">
            <b>Iva</b>
            <input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm" value="0" readonly="">
          </div> -->
          <div class="col-sm-1">
            <b>Total</b>
            <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" value="0" readonly="">
          </div>
          <div class="col-sm-1">
            <br>
            <button class="btn btn-primary btn-sm" onclick="agregar()"><i class="fas fa-shopping-cart nav-icon"></i> Agregar</button>
          </div>
        </div>  
        <hr> 
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#todas" data-toggle="tab">Items</a></li>
                  <li class="nav-item"><a class="nav-link" href="#finalizadas" data-toggle="tab">Datos Cliente</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#pendientes" data-toggle="tab">Pendientes</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body" style="padding: 3px;">
                <div class="tab-content">
                  <div class="tab-pane active" id="todas">
                     <input type="hidden" name="txt_tr" id="txt_tr" value="0">
                        <div class="col-sm-12" id="tbl_pedido"></div>
                        <div class="col-sm-12">
                          <table class="table table-bordered table table-sm table-active">
                              <tr>
                                <td colspan="5"></td>
                                <td class="text-right"> 
                                  <h5>Subtotal:</h5>
                                  <h5>Descuento:</h5>
                                  <h5>Iva:</h5>
                                  <h5>Total:</h5>
                                </td>
                                <td style="width: 150px">
                                  <input type="text" name="txt_subtotal_fa" id="txt_subtotal_fa" class="form-control-sm form-control text-right">
                                  <input type="text" name="txt_dcto_fa" id="txt_dcto_fa" class="form-control-sm form-control text-right">
                                  <input type="text" name="txt_iva_fa" id="txt_iva_fa" class="form-control-sm form-control text-right">
                                  <input type="text" name="txt_total_fa" id="txt_total_fa" class="form-control-sm form-control text-right">
                                </td>
                                <td></td>
                              </tr>
                          </table>
                        </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="finalizadas">
                    <b>Datos personales</b><br>
                      <hr>
                    <div class="row">

                      <div class="col-sm-3">
                        <b>Nombre</b>
                        <input type="text" name="txt_nombre_cli" id="txt_nombre_cli" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-2">
                        <b>CI / RUC</b>
                        <input type="text" name="txt_nombre_cli" id="txt_ci_cli" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-2">
                        <b>Email</b>
                        <input type="text" name="txt_nombre_cli" id="txt_email_cli" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-2">
                        <b>Telefono</b>
                        <input type="text" name="txt_nombre_cli" id="txt_telefono_cli" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-3">
                        <b>Direccion</b>
                        <!-- <input type="text" name="xt_nombre_cli" id="xt_nombre_cli" class="form-control form-control-sm"> -->
                        <textarea name="txt_direccion_cli" id="txt_direccion_cli" class="form-control form-control-sm" style="resize: none;"></textarea>
                      </div>
                    </div>
                     <b>Otros Datos</b><br>
                      <hr>
                    <div class="row">
                      <div class="col-sm-12">
                        
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="" id="btn_opcion">Guardar</button>
                    </div>
                  </div>                 
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>          
        </div>
      </div>

<br>
        <div id="finalizado_page" style="display: none;">
        <div class="row"><br>
         <div class="col-sm-4">
           <b>Nombre:</b><p id="nombre_f">javier farinango</p>
         </div>
          <div class="col-sm-2">
           <b>CI:</b><p id="ci_f"></p>
         </div>
          <div class="col-sm-2">
           <b>Fecha Emision:</b><p id="fecha_emi_f"></p>
         </div>
          <div class="col-sm-2">
           <b>Fecha Vencimiento:</b><p id="fecha_ven_f"></p>
         </div>
          <div class="col-sm-2">
           <b>Telefono:</b><p id="telefono_f"></p>
         </div>
          <div class="col-sm-2">
           <b>Email:</b><p id="emial_f"></p>
         </div>
          <div class="col-sm-4">
           <b>Direccion:</b><p id="direccion_f"></p>
         </div>
         <div class="col-sm-4">
           <b>Num. factura:</b><p id="numfac_f"></p>
         </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12" id="tbl_pedido_f"></div>
        </div>
        <div class="modal-footer">
             <b>SUBTOTAL:</b><p id="txt_subtotal_fa_fin"></p><br>
             <b>DESCUENTO:</b><p id="txt_dcto_fa_fin"></p><br>
             <b>IVA:</b><p id="txt_iva_fa_fin"></p><br>
             <b>TOTAL:</b><p id="txt_total_fa_fin"></p>
          </div>
       </div>
                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>



<div class="modal fade" id="cliente_facturar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Datos personales</h5>
      </div>
      <div class="modal-body">
        <form id="form_elegir">
        <div class="row">
          <div class="col-sm-12">
             <label class="radio-inline"><input type="radio" name="rbl_tipo" id="rbl_C" checked="" value="C" onchange="autocoplet_cliente()"> Cliente</label>
             <label class="radio-inline"><input type="radio" name="rbl_tipo" id="rbl_B" value="B" onchange="autocoplet_cliente()"> Entre paños</label>
          </div>
          <div class="col-sm-12">
            <b>NOMBRE DE CLIENTE</b>
            <div class="input-group input-group-sm">
              <select class="form-control form-control-sm" id="ddl_cliente">
                 <option value="">Seleccione usuario</option>
              </select> 
              <span class="input-group-append">
                  <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#cliente_nuevo"><i class="fa fa-plus"></i></button>
              </span>
            </div>
          </div>
          <div class="col-sm-12">
            <b>TIPO DE DOCUMENTO</b>
            <select class="form-control form-control-sm" name="ddl_documento" id="ddl_documento">
              <option value="">Seleccione tipo de documento</option>
              <option value="FA">Factura</option>
              <option value="PR">Presupuesto</option>
              <option value="PF">Pre Factura</option>
              <option value="PE">Pedidos</option>
            </select>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="crear_documento();" id="btn_opcion">Continuar</button>
          <button type="button" class="btn btn-default" onclick="salir();" id="btn_opcion">Cancelar</button>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="cliente_nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo cliente</h5>
      </div>
      <div class="modal-body">
        <form id="form_usuario_new">
        <div class="row">
          <div class="col-sm-12">
            <b>NOMBRE DE CLIENTE</b>
            <input type="text" name="txt_nombre_new" id="txt_nombre_new" class="form-control-sm form-control">          
          </div>
           <div class="col-sm-6">
            <b>CI / RUC  </b>          
            <input type="text"  class="form-control form-control-sm" name="txt_ci_new" id="txt_ci_new" required="" onblur="validar_cedula('txt_ci_new','CP')" onkeyup=" solo_numeros('txt_ci_new');num_caracteres('txt_ci_new',10)">
          </div>
          <div class="col-sm-6">
            <b>TELEFONO</b>
            <input type="text"  class="form-control form-control-sm" name="txt_telefono" id="txt_telefono" required="" onkeyup=" solo_numeros('txt_telefono');num_caracteres('txt_telefono',10)">
          </div>
          <div class="col-sm-12">
            <b>EMAIL   </b>         
            <input type="text"  class="form-control form-control-sm" name="txt_emial" id="txt_emial" required="">
            <b>DIRECCION</b>
            <textarea style="resize:none;" class="form-control" id="txt_dir" name="txt_dir" required=""></textarea>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="new_usuario();" id="btn_opcion">Solo Guardar</button>
          <button type="button" class="btn btn-primary" onclick="new_usuario();" id="btn_opcion">Guardar y continuar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
    </div>
  </div>
</div>
</div>



<!-- Modal nueva categoria-->
<div class="modal fade" id="nuevo_abono" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Agregar abono</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
           <div class="row">
             <div class="col-sm-4">
              <input type="hidden" name="id_fac" class="form-control-sm form-control" id="id_fac" value="<?php echo $num;?>">    
              Total Abonado   
              <input type="text" name="" class="form-control-sm form-control" id="txt_total_abono" readonly="">            
             </div>
              <div class="col-sm-4">
                Restante
                <input type="text" name="" class="form-control-sm form-control" id="txt_restante_factura" readonly="">
               
             </div>
             <div class="col-sm-4">
                Total factura
                <input type="text" name="" class="form-control-sm form-control" id="txt_total_factura" readonly="">
               
             </div>
           </div>          
          </div>        
        </div>
        <div class="row">
          <div class="col-sm-6">
            Monto
            <input type="text"  class="form-control form-control-sm" name="txt_monto" id="txt_monto">            
          </div>
          <div class="col-sm-6">
            Forma de pago
            <select class="form-control form-control-sm" id="ddl_tipo_pago" onchange="habilitar_cheq_comp()">
              <option value="">Seleccione forma de pago</option>
            </select>           
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-6">
            Num cheque o comprobante
            <input type="text"  class="form-control form-control-sm" name="txt_cheq_comp" id="txt_cheq_comp" readonly="">            
          </div>
          <div class="col-sm-6">
            Fecha
            <input type="date"  class="form-control form-control-sm" name="txt_fecha_abono" id="txt_fecha_abono" value="<?php echo date('Y-m-d')?>">            
          </div>          
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-primary btn-sm" onclick="ingresar_abono();">Guardar</button>         
        </div>
        <div class="row">
          <div class="col-sm-12" id="tbl_abonos">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function salir()
  {
     var url="inicio.php";
     $(location).attr('href',url);
  }
</script>

<?php include('./footer.php'); ?>
