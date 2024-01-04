<?php /*include('../cabeceras/header.php'); */ $id = '';if(isset($_GET['id'])){$id=$_GET['id'];} ?>
<script type="text/javascript">
  $(document).ready(function() {

    inputs_asignacion_seguros();

    var id ='<?php echo $id;?>';
    if(id)
    {
      cargar_datos_seguro(id);
      $('#txt_id').val(id);
      Articulo_contrato_lista();
    }
    $('#ddl_siniestros').select2();
    lista_cobertura();
    lista_proveedor();
    lista_articulos();
    forma_pago();
});
</script>
<script type="text/javascript">


   function inputs_asignacion_seguros()
   {
       var inputs = '';
       var tablas = '<?php echo $_SESSION['INICIO']['ASIGNAR_SEGUROS'];?>';
       if(tablas!='')
       {
        // console.log(tablas)
         var obj = JSON.parse(tablas);
         // console.log(obj);
          obj.forEach(function(item,i){
            if(i==0)
            {
              inputs+='<label><input type="radio" name="rbl_seguro_busqueda" onclick="lista_articulos()" value="'+item.tabla+'" checked ><b>'+item.tabla+'</b></label>  ';
            }else
            {
              inputs+='<label><input type="radio" name="rbl_seguro_busqueda" onclick="lista_articulos()" value="'+item.tabla+'" ><b>'+item.tabla+'</b></label>  ';              
            }
          })
       }else
       {
         inputs+='<input type="radio" name="rbl_seguro_busqueda" value="ACTIVO" checked style="display:none">';
       }

       $('#pnl_asociacion_seguros').html(inputs);
   }

   function lista_cobertura()
   {
      $('#ddl_cobertura').select2({
        placeholder: 'Seleccione Cobertura',
        width:'85%',
        ajax: {
          url:   '../controlador/contratoC.php?lista_cobertura=true',
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

   function lista_siniestros()
   {
    $('#ddl_siniestros').empty();
      var cob = $('#ddl_cobertura').val();
      $('#ddl_siniestros').select2({
        placeholder: 'Seleccione Siniestros',
        width:'80%',
        ajax: {
          url:   '../controlador/contratoC.php?lista_siniestros=true&cob='+cob,
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
  function lista_proveedor()
   {
      $('#ddl_proveedor').select2({
        placeholder: 'Seleccione Proveedor',
        width:'87%',
        ajax: {
          url:   '../controlador/contratoC.php?lista_proveedores=true',
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

   function lista_articulos()
   {
      var tabla = $('input[name="rbl_seguro_busqueda"]:checked').val();
      $('#ddl_articulos').select2({
        placeholder: 'Seleccione Proveedor',
        width:'100%',
        ajax: {
          url:   '../controlador/contratoC.php?lista_articulos=true&tabla='+tabla,
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

  function guardar_contrato()
  {
    var id = $('#txt_id').val();
    var pro = $('#ddl_proveedor').val();
    var cob = $('#ddl_cobertura').val();
    var sin = $('#ddl_siniestros').val();
    var des = $('#txt_desde').val();
    var has = $('#txt_hasta').val();
    var pri = $('#txt_prima').val();
    var val = $('#txt_valor_seguro').val();

    var plan = $('#txt_plan').val();
    var vig = $('#txt_vigencia').val();
    var forma = $('#ddl_forma_pago').val();
    var cove = $('#txt_cobertura_por').val();
    var dedu = $('#txt_deducible').val();
    var reno  = $('input[name="rbl_renovacion"]:checked').val();
    var tel = $('#txt_telefono').val();
    var asesor = $('#txt_asesor').val();
    var email = $('#txt_email').val();

    if(pro =='' || cob =='' || sin =='' || des =='' || has =='' || pri =='' || val =='' || plan =='' || vig =='' || forma =='' || cove =='' ||  reno =='' || tel =='' || asesor =='' || email =='')
    {
      Swal.fire('Llene todo los campos','','info');
      return false;
    }

    var parametros = 
    {
      'id':$('#txt_id').val(),
      'proveedor':pro,
      'cobertura':cob,
      'siniestro':sin,
      'desde':des,
      'hasta':has,
      'prima':pri,
      'valor':val,

      'plan':plan,
      'vigencia':vig,
      'forma_pago':forma,
      'cobertura_porce':cove,
      'deducible':dedu,
      'renovacion':reno,
      'telefono':tel,
      'asesor':asesor,
      'email':email,
    }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?seguroSave=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
              if(response==1)
              {
                Swal.fire('Contrato guardada','','success').then(function(){
                  location.href = 'inicio.php?acc=contratos&id='+response;
                })
              }else
              {
                Swal.fire('Algo sucedio','','error')
              }
          }
        });
  }

  function cobertura()
  {
    nom =  $('#txt_cobertura').val();

    var parametros = 
    {
      'nombre':nom,
    }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?coberturaSave=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
              if(response==1)
              {
                $('#txt_cobertura').val('');
                $('#myModal_cobertura').modal('hide');
                Swal.fire('Cobertura guardada','','success');

              }
          }
        });
  }

  function tipo_pago_save()
  {
    nom =  $('#txt_tipo_pago').val();

    var parametros = 
    {
      'nombre':nom,
    }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?tipo_pago_save=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
              if(response==1)
              {
                $('#txt_tipo_pago').val('');
                $('#myModal_tipo_pago').modal('hide');
                Swal.fire('Tipo pago guardado','','success');
                forma_pago();
              }
          }
        });
  }


  function forma_pago()
  {

        $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?forma_pago=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {
              var  op = '<option value="">Seleccione tipo de pago</option>';
             response.forEach(function(item,i)
             {
               op+='<option value="'+item.id+'">'+item.nombre+'</option>';
             })

             $('#ddl_forma_pago').html(op);
          }
        });
  }

  function cargar_datos_seguro(id)
  {
    var parametros = 
    {
      'id':id,
    }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?cargar_datos_seguro=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
              console.log(response)

              $('#ddl_proveedor').html(response.proveedor);
              $('#ddl_cobertura').html(response.cobertura); 
              lista_siniestros();
              $('#ddl_siniestros').html(response.siniestro).trigger('change');

              
             // $('#ddl_localizacion').append($('<option>',{value: response[0].id_loc, text: response[0].DENOMINACION,selected: true }));

              // $('#ddl_siniestros').html(response.siniestros);
              $('#txt_desde').val(formatoDate(response.datos[0].hasta.date));
              $('#txt_hasta').val(formatoDate(response.datos[0].desde.date));
              $('#txt_prima').val(response.datos[0].prima);
              $('#txt_valor_seguro').val(response.datos[0].suma_asegurada);

              $('#txt_plan').val(response.datos[0].plan_seguro);
              $('#txt_vigencia').val(response.datos[0].vigencia);
              $('#ddl_forma_pago').val(response.datos[0].forma_pago);
              $('#txt_cobertura_por').val(response.datos[0].cobertura_porce);
              $('#txt_deducible').val(response.datos[0].Dedusible);
              $('#rbl_renovacion_'+response.datos[0].renovacion).prop('checked',true);
              $('#txt_telefono').val(response.datos[0].telefono_asesor);
              $('#txt_asesor').val(response.datos[0].asesor);
              $('#txt_email').val(response.datos[0].email_asesor);


          }
        });
  }
  function siniestros()
  {
    var nom = $('#txt_nombre').val();
    var det = $('#txt_detalle_siniestro').val();
    var cob = $('#ddl_cobertura').val();
    if(cob=='')
    {
      Swal.fire('Seleccione una Cobertura','','info');
      return false;
    }
    if(nom=='' || det =='')
    {
      Swal.fire('','Llene todo los campos','info');
      return false;
    }
      var parametros = 
      {
        'nombre':nom,
        'detalle':det,
        'cobertura':cob,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?siniestroSave=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
              $('#txt_detalle_siniestro').val('');
              $('#txt_nombre').val('');
              $('#myModal_siniestro').modal('hide');
              Swal.fire('Siniestro guardado','','success');
            }
          }
        });
  }

  function editar_insertar()
  {
    var nom = $('#txt_proveedorNew').val();
    var ci = $('#txt_ciNew').val();
    var tel = $('#txt_telefonoNew').val();
    var ema = $('#txt_emailNew').val();
    var dir = $('#txt_direccionNew').val();
    
    if(nom=='' || ci =='' || tel =='' || ema =='' || dir =='')
    {
      Swal.fire('','Llene todo los campos','info');
      return false;
    }
      var parametros = 
      {
        'nombre':nom,
        'ci':ci,
        'tel':tel,
        'ema':ema,
        'dir':dir,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?proveSave=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
             
               $('#txt_proveedor').val('');
               $('#txt_ci').val('');
               $('#txt_telefono').val('');
               $('#txt_email').val('');
               $('#txt_direccion').val('');
              $('#myModal_proveedor').modal('hide');
              Swal.fire('Proveedor guardado','','success');
            }
          }
        });

  }

  function agregar_a_contrato()
  {
    var id = '<?php echo $id; ?>';
    var art = $('#ddl_articulos').val();
    var mod = '<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>';
    var tabla = $('input[name="rbl_seguro_busqueda"]:checked').val();
    if(id==''){
      Swal.fire('No se pudo agregar','Guarde primero los datos del contrato','info');
      return false;
    }
    if(art=='')
    {
      Swal.fire('Seleccione un articulo','','info');
      return false;
    }

     var parametros = 
      {
        'contrato':id,
        'articulo':art,
        'modulo':mod,
        'tabla':tabla,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?Articulo_contrato_Save=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               $('#ddl_articulos').empty();
              Swal.fire('Articulo agregado al seguro','','success');
              Articulo_contrato_lista();
            }else if(response==-2)
            {
              Swal.fire('El articulo ya esta agregado','','warning');
            }else if(response==-3)
            {
              Swal.fire('El articulo ya esta en otro seguro','','warning');
            }
          }
        });
  }
  function Articulo_contrato_lista()
  {
    var id = '<?php echo $id; ?>';   
    var art = '';
     var parametros = 
      {
        'contrato':id,
        'articulo':art,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contratoC.php?Articulo_contrato_lista=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) {
            if(response!='')
            {
             $('#tbl_art').html(response);             
            }
          }
        });
  }

  function eliminar_art(id)
  {
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
          eliminar(id)
        }
      })

  }
  function eliminar(id)
  {
    $.ajax({
      data:  {id:id},
      url:   '../controlador/contratoC.php?Articulo_contrato_delete=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        if(response==1)
        {
          Swal.fire('Articulo eliminado','','success');
          Articulo_contrato_lista();
        }
      }
    });
  }
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Seguros</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Contratos de seguros</li>
              </ol>
            </nav>
          </div>         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">


            <div class="card">
          <div class="card-body p-4">
            <div class="row">
              <div class="col">
                 <a href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA'];?>&acc=lista_contratos" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
              </div>
              
            </div>
            <hr>
            <div class="form-body mt-4">
              <div class="row">

               <div class="col-lg-5">
              <div class="border border-3 p-4 rounded">
                <div class="row g-2">
                  <div class="col-md-12">
                    <input type="hidden" name="txt_id" id="txt_id">
                    <b>Proveedor</b>
                    <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm" id="ddl_proveedor" name="ddl_proveedor">
                          <option value="">Proveedor 1</option>
                        </select>
                          <button type="button" class="btn btn-outline-secondary btn-sm" title="Nuevo proveedor" onclick="nuevo_proveedor()"><i class="bx bx-user-plus"></i></button>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <b>Asesor</b>            
                    <input type="" name="txt_asesor" id="txt_asesor" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6">
                    <b>Telefono</b>            
                    <input type="" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm">
                  </div>                
                  <div class="col-sm-6">
                    <b>Email</b>            
                    <input type="" name="txt_email" id="txt_email" class="form-control form-control-sm">
                  </div>
                  <hr>
                  <div class="col-sm-6">
                    <b>Fecha de contrato</b>
                    <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6">
                    <b>Fin de contrato</b>
                    <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6">
                    <b>Vigencia</b>            
                    <input type="" name="txt_vigencia" id="txt_vigencia" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6" style="display: none;">
                    <b>Precio prima</b>
                    <input type="" name="txt_prima" id="txt_prima" class="form-control form-control-sm" value="0">
                  </div>
                  <div class="col-sm-6">
                    <b>Nombre Plan</b>            
                    <input type="" name="txt_plan" id="txt_plan" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6">
                    <b>Suma asegurada</b>
                    <input type="" name="txt_valor_seguro" id="txt_valor_seguro"  class="form-control form-control-sm">
                  </div>          
                  <div class="col-sm-6">
                    <b>Forma de pago</b>    
                    <div class="input-group input-group-sm">        
                      <select class="form-select form-select-sm" name="ddl_forma_pago" id="ddl_forma_pago">
                        <option value="">Seleccione</option>
                      </select>
                       <button type="button" class="btn btn-outline-secondary btn-sm" title="Nuevo tipo de pago" onclick="nuevo_tipo_pago()"><i class="bx bx-plus"></i></button>
                     </div>
                  </div>
                  <div class="col-sm-6">
                    <b>Cobertura %</b>            
                    <input type="" placeholder="10%" name="txt_cobertura_por" id="txt_cobertura_por" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6">
                    <b>Dedusible</b>            
                    <input type="" name="txt_deducible" id="txt_deducible" class="form-control form-control-sm" readonly value="0">
                  </div>
                  <div class="col-sm-6">
                    <b>Tipo de renovacion</b>            
                    <label><input type="radio" name="rbl_renovacion" id="rbl_renovacion_A" value="A" checked>Automatica</label>
                    <label><input type="radio" name="rbl_renovacion" id="rbl_renovacion_M" value="M">Manual</label>
                  </div>
                  <div class="col-sm-12">
                    <b>Cobertuta</b>
                    <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm" id="ddl_cobertura" name="ddl_cobertura" onchange="lista_siniestros()"> 
                          <option value="">Seleccione cobertura</option>                    
                        </select>
                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Nueva Cobertuta" onclick="nueva_cobertura()"><i class="bx bx-clinic"></i></button>                  
                    </div>            
                  </div>
                   <div class="col-sm-12">
                    <b>Siniestro</b>
                     <div class="input-group">
                          <select class="multiple-select" name="ddl_siniestros[]" multiple="multiple" id="ddl_siniestros" style="width:80%" data-placeholder="Choose anything">
                            <option value="">Seleccione Siniestros</option>                        
                          </select>
                          <button type="button" class="btn btn-outline-secondary btn-sm" title="Nuevo Siniestro" onclick="nuevo_siniestro()"><i class="bx bx-list-plus"></i></button>
                      </div>            
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                        <button class="btn btn-sm btn-primary" id="btn_editar" name="btn_editar" onclick="guardar_contrato()" >Guardar</button>  
                    </div>
                  </div>
                </div> 
              </div>
              </div>
               <div class="col-lg-7">
                  <div class="border border-3 p-4 rounded">                    
                    <div class="row">
                      <div class="col-sm-12" id="pnl_asociacion_seguros">
                      <?php //print_r($_SESSION['INICIO']);?>
                      </div>
                      <div class="col-sm-10">
                        <b>Aplicar seguro a:</b>
                              <select class="form-select form-select-sm" name="ddl_articulos" id="ddl_articulos" >
                                <option value="">Seleccione Articulo</option>                    
                              </select>
                      </div>
                      <div class="col-sm-2 text-end">
                        <br>
                          <button type="button" class="btn btn-primary btn-sm" title="Nuevo Siniestro" onclick="agregar_a_contrato()"> Agregar<i class="bx bx-down-arrow-circle me-0"></i></button>                  
                      </div>
                    </div>
                    <div class="row table-responsive">
                      <div class="col-sm-12">
                        <table class="table table-striped table-bordered dataTable">
                          <thead>
                            <th></th>
                            <th>Item asegurado</th>
                          </thead>
                          <tbody id="tbl_art">
                            <tr>
                              <td colspan="6">No se encontro articulos</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
               </div>
             </div><!--end row-->
          </div>
          </div>
        </div>


          </div>
        </div>
        <!--end row-->
      </div>
    </div>

  <script type="text/javascript">
    function nuevo_siniestro()
    {
      $('#myModal_siniestro').modal('show');
    }
     function nueva_cobertura()
    {
      $('#myModal_cobertura').modal('show');
    }
     function nuevo_proveedor()
    {
      $('#myModal_proveedor').modal('show');
    }
    function nuevo_tipo_pago()
    {
      $('#myModal_tipo_pago').modal('show');
    }
  </script>

<div class="modal fade" id="myModal_siniestro">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Nuevo siniestro</h3>
      </div>
      <div class="modal-body">       
        Nombre de siniestro <br>
        <input type="input" name="txt_nombre" id="txt_nombre" class="form-control form-control-sm">
        Detalle de siniestro <br>
        <textarea class="form-control form-control-sm" style="resize:none"  rows="2" id="txt_detalle_siniestro" name="txt_detalle_siniestro"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="op" onclick="siniestros()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal_cobertura">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Nueva Cobertura</h3>
      </div>
      <div class="modal-body">       
        Nombre de cobertura <br>
        <input type="input" name="txt_cobertura" id="txt_cobertura" class="form-control form-control-sm">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="op" onclick="cobertura()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal_tipo_pago">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Nuevo tipo de pago</h3>
      </div>
      <div class="modal-body">       
        Nombre de Tipo de pago <br>
        <input type="input" name="txt_tipo_pago" id="txt_tipo_pago" class="form-control form-control-sm">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="op" onclick="tipo_pago_save()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal_proveedor">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Nuevo Proveedor</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
             Nombre <br>
             <input type="input" name="txt_proveedorNew" id="txt_proveedorNew" class="form-control form-control-sm">           
          </div>
          <div class="col-sm-12">
            CI /RUC <br>
            <input type="input" name="txt_ciNew" id="txt_ciNew" class="form-control form-control-sm">            
          </div>
           <div class="col-sm-6">
            Email <br>
            <input type="input" name="txt_emailNew" id="txt_emailNew" class="form-control form-control-sm">            
          </div>
           <div class="col-sm-6">
            Telefono <br>
            <input type="input" name="txt_telefonoNew" id="txt_telefonoNew" class="form-control form-control-sm">            
          </div>
           <div class="col-sm-12">
            Direccion <br>
            <textarea class="form-control form-control-sm " id="txt_direccionNew" name="txt_direccionNew" style="resize:none" rows="2"></textarea>          
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="op" onclick="editar_insertar()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php //include('../cabeceras/footer.php'); ?>
