<?php include('./header.php'); include('../controlador/trabajosC.php');?>
<script type="text/javascript">
   $( document ).ready(function() {
     //  // restriccion();
     // Lista_clientes();
    lista_trabajos();
     autocoplet_material();
     autocoplet_cate();
     autocoplet_bodegas();
     autocoplet_estado_joya();
     autocoplet_cliente();

    });

   function lista_trabajos()
   {
   	// var parametros = {}
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/trabajosC.php?trabajos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
            $('#tbl_trabajos').html(response);
           } 
          } 
          
       });
   }

    function autocoplet_bodegas(){
      $('#ddl_bodega').select2({
        placeholder: 'Seleccione una bodega',
        width:'100%',
        ajax: {
          url:   '../controlador/articulosC.php?bodegas=true',
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

     function autocoplet_estado_joya(){
      $('#ddl_estado_joya').select2({
        placeholder: 'Seleccione estado joya',
        // width:'100%',
        ajax: {
          url:   '../controlador/trabajosC.php?estado_joya=true',
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



     function autocoplet_cate(){
      $('#ddl_tipo_joya').select2({
        placeholder: 'Seleccione tipo de joya',
        // width:'90%',
        ajax: {
          url:   '../controlador/articulosC.php?categoria=true',
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


   	function autocoplet_material(){
      $('#ddl_material').select2({
        placeholder: 'Seleccione material',
        // width:'90%',
        ajax: {
          url:   '../controlador/trabajosC.php?material=true',
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


   function ingresar_trabajo()
   {
    var datos = $('#form_trabajo').serialize();
     $.ajax({
         data:  datos,
         url:   '../controlador/trabajosC.php?trabajos_ingreso=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response==1) 
           {
           	lista_trabajos();
           } 
          } 
          
       });

   }
    function autocoplet_cliente(){
    let tipo = 'C'; 
    console.log(tipo);
      $('#txt_proveedor').select2({
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

   function new_usuario()
  {
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

 
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Trabajo con joyas</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<form id="form_trabajo" method="post">
          
        <div class="row">
          <div class="col-sm-4">
              <b>Nombre / proveedor</b>
              <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" name="txt_proveedor" id="txt_proveedor">
                   <option value="">Seleccione usuario</option>
                </select> 
                <span class="input-group-append">
                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#cliente_nuevo"><i class="fa fa-plus"></i></button>
                </span>
              </div>
            </div>          
          <div class="col-sm-2">
            <b>Tipo de joya</b>
            <select class="form-control form-control-sm" name="ddl_tipo_joya" id="ddl_tipo_joya">
              <option>Seleccione bodega</option>
            </select>
          </div>
          <div class="col-sm-1">
            <b>PVP</b>
            <input type="text" name="txt_pvp" id="txt_pvp" class="form-control form-control-sm">  
          </div>
          <!-- <div class="col-sm-3">
            <b>ESTADO DE LA JOYA</b>
            <select class="form-control form-control-sm">
              <option>Seleccione bodega</option>
            </select>
          </div> -->
          <div class="col-sm-3">
            <b>ESTADO DE LA JOYA PROVEEDOR</b>
            <select class="form-control form-control-sm" name="ddl_estado_joya" id="ddl_estado_joya">
              <option>Seleccione bodega</option>
            </select>           
          </div>

          <div class="col-sm-2">
            <b>Fecha de ingreso</b>           
            <input type="date" name="txt_fecha_ing" id="txt_fecha_ing" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">  
          </div>
        </div>
      	<div class="row">
      		<div class="col-sm-2">
      			<b>Codigo</b>
      			<input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm">
      		</div>

      		<div class="col-sm-2">
      			<b>Codigo de joya</b>
      			<input type="text" name="txt_cod_joya" id="txt_cod_joya" class="form-control form-control-sm">      			
      		</div>
      		<div class="col-sm-3">
      			<b>Nombre de articulo</b>
      			<input type="text" name="txt_nom_art" id="txt_nom_art" class="form-control form-control-sm">      			
      		</div>
      		<div class="col-sm-2">
      			<b>Codigo coleccion</b>
      			<input type="text" name="txt_cod_coleccion" id="" class="form-control form-control-sm">
      			
      		</div>
      		<div class="col-sm-1">
      			<b>PESO (g)</b>
      			<input type="text" name="txt_peso" id="txt_peso" class="form-control form-control-sm">      			
      		</div>
      		<div class="col-sm-2">
      			<b>MATERIAL</b>
      			<select class="form-control form-control-sm" name="ddl_material" id="ddl_material">
      				<option value="">Seleccione bodega</option>
      			</select>      			
      		</div>
          <div class="col-sm-2">
            <b>Punto de venta</b><br>
            <label id="txt_punto"><?php echo $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM']; ?></label>
          </div>
      		<div class="col-sm-3">
      			<b>Bodega</b>
      			<select class="form-control form-control-sm" name="ddl_bodega" id="ddl_bodega">
      				<option>Seleccione bodega</option>
      			</select>
      		</div>
          <div class="col-sm-7">
            <b>DESCRIPCION DE TRABAJO</b>
            <textarea class="form-control-sm form-control" style="resize: none;" name="txt_descripcion" id="txt_descripcion"></textarea>
          </div>

      	</div>
      	<!-- <div class="row">
      		<div class="col-sm-12">
      			<b>DESCRIPCION DE TRABAJO</b>
      			<textarea class="form-control-sm form-control" style="resize: none;" name="txt_descripcion" id="txt_descripcion"></textarea>
      		</div>
      	</div> -->
      </form>
      	<br>
      	<div class="row">
      		<div class="col-sm-12 text-right">
      			<button class="btn btn-primary btn-sm" onclick="ingresar_trabajo()">INGRESAR <i class="fas fa-save nav-icon"></i></button>
      		</div>
      		
      	</div>
      	<div class="row">
      		<div class="table-responsive" id="tbl_trabajos">
      			
      		</div>
      	</div>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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


<?php include('./footer.php'); ?>
