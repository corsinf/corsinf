<?php include('./header.php'); include('../controlador/cuentas_x_cobrarC.php');  ?>
<script type="text/javascript">
   $( document ).ready(function() {
     facturas_por_pagar();
     facturas_pagagadas();
     autocoplet_tipo_pago();
     autocoplet_cliente();
    });


   function autocoplet_cliente(){
      $('#ddl_clientes').select2({
        placeholder: 'Seleccione una familia',
        width:'90%',
        ajax: {
          url:   '../controlador/cuentas_x_cobrarC.php?search_cliente=true',
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

  function facturas_por_pagar()
  {
    var id = $('#ddl_clientes').val();
    var parametros = 
    {
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/cuentas_x_cobrarC.php?facturas_por_pagar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           $('#tbl_facturas').html(response);           
          } 
          
       });
  }

   function facturas_pagagadas()
  {
    var id = $('#ddl_clientes').val();
    var parametros = 
    {
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/cuentas_x_cobrarC.php?facturas_pagadas=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           $('#tbl_facturas_pagadas').html(response);           
          } 
          
       });
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
           $('#tbl_cuotas').html(response.tabla_cuotas);
           $('#txt_total_abono').val(response.total_abono);     
           $('#txt_total_factura').val(response.total);     
           $('#txt_restante_factura').val(response.faltante);           
          } 
          
       });
  }



  function limpiar_ddl_cli()
  {
    $('#ddl_clientes').empty();
    facturas_por_pagar();
    facturas_pagagadas();

  }
  function Agregar_Abono(id)
  {
    $('#nuevo_abono').modal('show');
    $('#id_fac').val(id);
    abonos_a_factura(id);
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
              abonos_a_factura(id);
              facturas_pagagadas();
              facturas_por_pagar();
            }else if(response == 2)
            {              
              Swal.fire('','Factura cancelada en su totalidad.','success');
              $('#txt_monto').val('');
              $('#txt_cheq_comp').val('');
              $('#ddl_tipo_pago').empty();
              abonos_a_factura(id);
              facturas_pagagadas();
              facturas_por_pagar();
              $('#nuevo_abono').modal('hide');
            }else
            {
              Swal.fire('','No se pudo agregar.','error');
            }
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
  function Eliminar_abono(id)
  {
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
               data:  {id:id},
               url:   '../controlador/cuentas_x_cobrarC.php?eliminar_abono=true',
               type:  'post',
               dataType: 'json',
                 success:  function (response) { 
                    abonos_a_factura(idfac);
                    facturas_pagagadas();
                    facturas_por_pagar();
                } 
          
             });
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
            <h1 class="m-0 text-dark">Cuentas por cobrar</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row">
      		<div class="col-sm-4">
            <b>Cliente</b>
             <div class="input-group input-group-sm">
                 <select class="form-control-sm form-control" id="ddl_clientes" onchange="facturas_por_pagar();facturas_pagagadas()">
                   <option value="">Seleccione cliente</option>              
                 </select>
                  <span class="input-group-append">
                    <button type="button" class="btn btn-primary btn-flat" onclick="limpiar_ddl_cli()"><i class="fas fa-times nav-icon"></i></button>
                  </span>
             </div>  
      		</div>
      		<div class="col-sm-2">
      			<!-- <input type="text" name="" class="form-control form-control-sm">      			 -->
      		</div>
      		<div class="col-sm-2">
      			<!-- <input type="text" name="" class="form-control form-control-sm">      			 -->
      		</div>
      		<div class="col-sm-2">
      			<!-- <input type="text" name="" class="form-control form-control-sm">      			 -->
      		</div>

      	</div>
        <hr>
        <div class="row">
          <div class="col-md-12"><br>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#todas" data-toggle="tab">Facturas por pagar</a></li>
                  <li class="nav-item"><a class="nav-link" href="#finalizadas" data-toggle="tab">Facturas pagadas</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#pendientes" data-toggle="tab">Facturas anuladas</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body" style="padding: 3px;">
                <div class="tab-content">
                  <div class="tab-pane active" id="todas">
                    <div id="tbl_facturas" class="col-sm-12"></div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="finalizadas">
                    <div id="tbl_facturas_pagadas" class="col-sm-12"></div>
                  </div>

                  <!-- <div class="tab-pane" id="pendientes">
                    
                  </div> -->
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>          
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
              <input type="hidden" name="id_fac" class="form-control-sm form-control" id="id_fac" value="">    
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
          <div class="col-sm-9">
            <b>Abonos realizados</b>
            <div id="tbl_abonos">
              
            </div>
            
          </div>
          <div class="col-sm-3">
            <b>cuotas restantes</b>
            <div id="tbl_cuotas">
              
            </div>
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>



<?php include('./footer.php'); ?>
