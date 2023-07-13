<?php include('../../cabeceras/header.php'); ?> 
  <script type="text/javascript">
  $( document ).ready(function() {
    })
  </script>
  
<div class="page-wrapper">
  <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
          <div class="breadcrumb-title pe-3">Actas</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"></li>
              </ol>
            </nav>
          </div>          
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <hr>
              <div class="card">
                <div class="card-body">
                <div class="row">
                  <div class="col-sm-5">
                    <b>Seleccione un archivo</b>
                    <form id="form_file">
                      <input type="file" name="file" id="file" class="form-control form-control-sm">
                    </form>   
                  </div>
                   <div class="col-sm-2">
                    <br>
                    <button type="button" class="btn btn-primary btn-sm" onclick="subir_archivo()">Leer archivo</button>                  
                  </div>
                   <div class="col-sm-4">
                      <b>Tipo de documento</b><br>
                      <select id="ddl_tipo" name="ddl_tipo" class="form-select form-select-sm">
                        <option value="">Seleccione documento</option>
                      </select>                           
                  </div>
                   <div class="col-sm-1">
                    <br>
                      <button type="button" class="btn btn-primary btn-sm" onclick="filtrar_documentos()">Buscar</button>
                  </div>
                </div>                    
                  <hr><br>
                  <div class="row" style="overflow-x:scroll;">
                    <table border="1px solid black;" class="table table-hover">
                    <tbody id="tbl_datos">      
                      
                    </tbody>
                  </table>
                    
                  </div>                  
                </div>
              </div>
          </div>
        
         
        </div>
  </div>
</div>

<script type="text/javascript">
  function  subir_archivo()
  {
    $('#tbl_datos').html('<tr><td><img src="img/ZZ5H.gif"></td></tr>')
    var fi = $('#file').val();
      if(fi != '')
      {

            var formData = new FormData(document.getElementById("form_file"));
            $.ajax({
                url: '../../controlador/calcularATS.php?subir_archivo_server=true',
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
                    calcular()
                  }else
                  {
                    Alert( 'Formato del archivo incorrecto','asegurese que el archivo sea (.cvs)','error');

                  }
                }
            });
      }else
      {
         Swal.fire( '','Destino o archivo no seleccionados','error');
      }
  } 
  function calcular()
  {
    var parametros = {'f':'d'}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/calcularATS.php?calcularexcel=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) { 
            // console.log(response);
            
            $('#tbl_datos').html(response.tr);

            // console.log(response.tipo);
           var opt = '';
            response.tipo.forEach(function(item,i){
            // console.log(item);
              opt+= '<option value="'+item+'">'+item+'</option>'
           })

            $('#ddl_tipo').html(opt);
          
          } 
          
       });
  }

  function filtrar_documentos()
  {
    var parametros = {'tipo':$('#ddl_tipo').val(),}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/calcularATS.php?filtrar_doc=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) { 
            // console.log(response);
            
            $('#tbl_datos').html(response.tr);

            // console.log(response.tipo);
           var opt = '';
            response.tipo.forEach(function(item,i){
            // console.log(item);
              opt+= '<option value="'+item+'">'+item+'</option>'
           })

            $('#ddl_tipo').html(opt);
          
          } 
          
       });
  }
</script>

  
<?php include('../../cabeceras/footer.php'); ?>