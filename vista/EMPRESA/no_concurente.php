<script type="text/javascript">
 $( document ).ready(function() {
      cargar_tablas();
      lista_no_concurente();
  
 })
  
function cargar_tablas()
{
  	 
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/no_concurenteC.php?tabla_no_concurente=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
        // console.log(response);
        var op= '<option value="">Seleccione Tabla</option>';
        response.forEach(function(item,i){
           op+='<option value="'+item.TABLE_NAME+'">'+item.TABLE_NAME+'</option>';
        })
        $('#ddl_tablas').html(op);
     }
   });
}


function campos_tabla_noconcurente()
{
  var parametros = 
  {
    'tabla':$('#ddl_tablas').val(),
  }
   $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/no_concurenteC.php?campos_tabla_noconcurente=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
        // console.log(response);
        var op= '<option value="">Seleccione Tabla</option>';
        response.forEach(function(item,i){
           op+='<option value="'+item.campo+'">'+item.campo+'</option>';
        })
        $('#ddl_usuario').html(op);
        $('#ddl_pass').html(op);
     }
   });
}

function lista_no_concurente()
{
     
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/no_concurenteC.php?lista_no_concurente=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
        console.log(response);
        var op='';
        response.forEach(function(item,i){
          op+='<tr><td>'+item.Total+'</td><td>'+item.Tabla+'</td><td>'+item.Campo_usuario+'</td><td>'+item.Campo_pass+'</td>'+
          '<td>'+
          '<button type="button" class="btn btn-danger btn-sm" onclick="eliminar_no_concurente(\''+item.Tabla+'\')"><i class="bx bx-trash me-0"></i></button>'+
          '</td>'+
          '</tr>';
        })
       
        $('#tbl_lista_no_concurentes').html(op);
     }
   });
}

function add_no_concurente()
{
  
  if($('#ddl_tablas').val()=='')
  {
    Swal.fire('','Seleccione una tabla','info');
     return false;
  }
  if($('#ddl_usuario').val() == $('#ddl_pass').val())
  {
     Swal.fire('','Asegurese que los campos de usuario y password sean distintos','info');
     return false;
  }
  var parametros = {
    'tabla':$('#ddl_tablas').val(),
    'usuario':$('#ddl_usuario').val(),
    'pass':$('#ddl_pass').val()
  }
  $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/no_concurenteC.php?add_no_concurente=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {  
        if(response==-2)
        {
          Swal.fire('','Tabla ya asignada a no concurentes','error');
        }else if(response==1)
        {
          Swal.fire('','Agregado a no concurrentes','success');   
          lista_no_concurente()
        }
        // console.log(response);
     }
   });
}


function eliminar_no_concurente(tabla)
{
     Swal.fire({
      title: 'Eliminar Registro?',
      text: "Esta seguro de eliminar este registro?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        eliminar(tabla);    
      }
    })
}

function eliminar(tabla)
{
  var parametros = {
    'tabla':tabla,
  }
  $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/no_concurenteC.php?delete_no_concurente=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {  
        if(response==1)
        {
          Swal.fire('','eliminado de no concurentes','success');  
          lista_no_concurente(); 
        }
     }
   });

}


</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Usarios no concurentes</div>
          <div class="ps-3">
           <!--  <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
              </ol>
            </nav> -->
          </div>         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <!-- <h6 class="mb-0 text-uppercase">Form Wizard</h6> -->
            <hr>
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                      <b>Tablas asociadas</b>
                      <select class="form-select form-select-sm" id="ddl_tablas" name="ddl_tablas" onchange="campos_tabla_noconcurente()">
                        <option value="">Seleccione tabla</option>
                      </select>
                    </div> 
                    <div class="col-sm-3">
                      <b>Validar Usuario con</b>
                      <select class="form-select form-select-sm" id="ddl_usuario" name="ddl_usuario">
                        <option value="">Seleccione Usuario</option>
                      </select>
                    </div> 
                    <div class="col-sm-3">
                      <b>Validar Password con</b>
                      <select class="form-select form-select-sm" id="ddl_pass" name="ddl_pass">
                        <option value="">Seleccione password</option>
                      </select>
                    </div> 

                    <div class="col-sm-2">
                      <br>
                      <button type="button" class="btn btn-primary btn-sm" onclick="add_no_concurente()">Agregas</button>
                    </div>        
                                               
                </div>
                <hr>
                <div class="row">
                	<div class="table-responsive">
                		<table class="table table-hover">
	                		<thead>
                        <th>Total Asociados</th>
                        <th>Tabla</th>
                        <th>Campo Usuario</th>
                        <th>Campo Password</th>
	                			<th></th>               			
	                		</thead>
	                		<tbody id="tbl_lista_no_concurentes">
	                			
	                		</tbody>	                		
	                	</table>
                	</div>
                </div>
               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

