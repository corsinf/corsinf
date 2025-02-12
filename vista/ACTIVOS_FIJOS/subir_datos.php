<?php include('./header.php'); ?>
  <script type="text/javascript">
    $( document ).ready(function() {  
     $("#btn_carga").on('click', function() {
      var id = $('#ddl_opcion').val();
      $('#txt_opcion').val(id);
      var fi = $('#file').val();
        if(id != '' && fi != '')
          {

        var formData = new FormData(document.getElementById("form_img"));
        var files = $('#file')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../controlador/subir_datosC.php?actualizar_excel=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
              if(response == 1)
              {
               cargar_datos();
              }else
              {
                Swal.fire( '',
                  'Formato del archivo incorrecto',
                  'error');

              }
            }
        });
      }else
      {
         Swal.fire( '',
                  'Destino o archivo no seleccionados',
                  'error');
      }
    });
  });
  
</script>
<script type="text/javascript">
	function cargar_datos()
	{
		var id = $('#ddl_opcion').val();
     var parametros=
     {      
      'id':id,
      };
		if(id != '')
		{
		 $.ajax({
          data:  {parametros:parametros},
         url:   '../lib/carga_datos.php?plantilla=true',
         type:  'post',
         dataType: 'json',
         beforeSend: function () {   
         	   // $('#myModal').modal('show');     
              var spiner = '<div class="text-center"><img src="../img/de_sistema/loader_puce.gif" width="100" height="100">SUBIENDO DATOS</div>';
            $('#cargar').html(spiner);
         },
           success:  function (response) {  
            console.log(response);
           if (response==1) 
           {
              $('#myModal').modal('hide'); 
           } 
          } 
          
       });
	}else
	{
		 Swal.fire( '',
                  'Seleccione una tabla.',
                  'error');
	}

	}
function cargar_datos_csv()
  {
     
     $.ajax({
         // data:  {parametros:parametros},
         url:   '../lib/carga_datos.php?cargar_csv=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
            console.log(response);
           console.log(response);
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
            <h1 class="m-0 text-dark">Subir datos</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row"> 
      		<div class="col-sm-6">
            <form enctype="multipart/form-data" id="form_img" method="post"> 
             <input type="hidden" id="txt_opcion" name="txt_opcion">     
              <input type="file" name="file" id="file" class="form-control">
            </form>            
      		    <!-- <input type="file" name="file" id="file" class="form-control"> -->
      	    </div>
      	    <div class="col-sm-3">
      		    <select class="form-control" id="ddl_opcion">
      			    <option value="">Seleccione destino de datos</option>
      			    <option value="1">Cargar Activos</option>
      		    </select>
      	    </div>
      	     <div class="col-sm-3">
      		    <button class="btn btn-primary" id="btn_carga">subir archivos</button>
      	    </div>
      	</div>
        <div class="row">
            <button class="btn btn-primary" id="btn_carga_csv" onclick="cargar_datos_csv()">subir archivos csv</button>          
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body" id="cargar">
        
      </div>
    </div>
  </div>
</div>

<?php include('./footer.php'); ?>
