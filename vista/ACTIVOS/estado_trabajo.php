<?php include('./header.php'); include('../controlador/estado_trabajoC.php');?>
<script type="text/javascript">
   $( document ).ready(function() {
     lista_trabajos();
     autocoplet_estado();
    });
    function lista_trabajos()
   {
   	// var parametros = {}
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/estado_trabajoC.php?trabajos=true',
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

    function autocoplet_estado(id=false){
      $('#ddl_estado').select2({
        placeholder: 'Seleccione una bodega',
        width:'100%',
        ajax: {
          url:   '../controlador/estado_trabajoC.php?estado_trabajo=true',
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

  function guardar_observacion()
  {
    var parametros = {
      'estado':$('#ddl_estado').val(),
      'obser':$('#txt_obs').val(),
      'id':$('#txt_id').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/estado_trabajoC.php?add_ob=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response==1) 
           {
              Swal.fire('','observacion añadida.','success');
              lista_trabajos();
            // $('#tbl_trabajos').html(response);
           } 
          } 
          
       });
  }

  function reporte_trabajo(id)
  {
  	 // var datos = $('#form_usuario_new').serialize();

   	$('#informe_trabajo').modal('show');
  	var url='../controlador/estado_trabajoC.php?reporte=true&id='+id;
    // window.open(url, '_blank');
    $('#informe_pdf').html("<iframe src="+url+'#zoom=90'+" width='100%' height='500px' frameborder='0' allowfullscreen></iframe>");

  }
  function observaciones(id)
  {    
    $('#trabajo_observaciones').modal('show');
    $('#txt_id').val(id);
     var parametros = {
      'id':$('#txt_id').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/estado_trabajoC.php?lista_trabajos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           $('#tbl_obse').html(response);
            
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
            <h1 class="m-0 text-dark">Lista de trabajos</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      <div class="table-responsive">
      	<table class="table table-hover">
      		<thead>
      			<th>Fecha de ingreso</th>
      			<th>Nombre de cliente</th>
      			<th>Jolla</th>
      			<th>Trabajo a realizar</th>
      			<th>estado</th>
            <!-- <th>observaciones</th>             -->
      			<th></th>
      		</thead>
      		<tbody id="tbl_trabajos">
      			
      		</tbody>
      	</table>      	
      </div>                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
<!-- Modal nueva categoria-->
<div class="modal fade" id="informe_trabajo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Informe de trabajo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="informe_pdf">
          
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="ingresar_categoria();">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="trabajo_observaciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">OBSERVACIONES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-5">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" name="txt_id" id="txt_id">
                <b>Estado de trabajo</b>
                <select class="form-control" id="ddl_estado">
                  <option>Seleccione etado</option>
                </select>
             </div>
             <div class="col-sm-12">
               <b>Observaciones</b>
               <textarea class="form-control" style="resize: none;" id="txt_obs"></textarea>
             </div>              
            </div>
          </div>
          <div class="col-sm-7 table-responsive">
            <b>LISTA DE OBSERVACIONES</b>
            <table class="table table-hover">
              <thead>
                <th>Fecha</th>
                <th>Estado</th>
                <th>observacion</th>
              </thead>
              <tbody id="tbl_obse">
              </tbody>
            </table>
          </div>  
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_observacion()">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<?php include('./footer.php'); ?>
