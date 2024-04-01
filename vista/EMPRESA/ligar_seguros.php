<script type="text/javascript">
 $( document ).ready(function() {
      cargar_tablas();
      lista_tabla_seguros();
    var count_c = 1;
    $(document).on('click', '#agregarFila_medicamentos', function() {
        count_c++;

         var htmlFila ='<div class="col-sm-4">'+
                            '<select class="form-select form-select-sm " id="ddl_campo'+count_c+'" name="ddl_campo'+count_c+'">'+
                              '<option value="">Seleccione Usuario</option>'+
                            '</select>'+                       
                        '</div>';
        $('#pnl_campos').append(htmlFila);
        autocoplet_tipo(count_c)
    });
 });
  
function cargar_tablas()
{
  // console.log('dddd');
  	 
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/ligar_segurosC.php?tabla_no_concurente=true',
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
        $('#ddl_tabla').html(op);
     }
   });
}


function autocoplet_tipo(id=1){
    var tabla = $('#ddl_tabla').val();
    $('#ddl_campo'+id).select2({
      placeholder: 'Seleccione una tipo de usuario',
      ajax: {
        url:   '../controlador/ligar_segurosC.php?campos_tabla=true&tbl='+tabla,
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


function campos_tabla()
{
  autocoplet_tipo()  
}

function lista_tabla_seguros()
{
     
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/ligar_segurosC.php?lista_tabla_seguros=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
        console.log(response);
        
        $('#tbl_lista').html(response);
     }
   });
}

function add()
{
  
  if($('#ddl_tabla').val()=='')
  {
    Swal.fire('','Seleccione una tabla','info');
     return false;
  }
  if($('#ddl_campo1').val()=='')
  {
     Swal.fire('','Seleccione un campo','info');
     return false;
  }
  var campos = $('#form_campos').serialize();
  var parametros = {
    'tabla':$('#ddl_tabla').val(),
    'campos':campos,
  }
  $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/ligar_segurosC.php?add=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {  

        if(response==1)
        {
          Swal.fire('','Tabla asociada a seguro agregada','success')
          lista_tabla_seguros();
        }else if(response==-2)
        {
          Swal.fire('','Esta tabla ya esta asociada','info')
        }
        
     }
   });
}


function eliminar_tbl_seguro(tabla)
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
     url:   '../controlador/ligar_segurosC.php?delete_tbl_seguro=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {  
        if(response==1)
        {
          Swal.fire('','eliminado de no concurentes','success');  
          lista_tabla_seguros(); 
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
                <form id="form_campos">
                <div class="row">
                    <div class="col-sm-3">
                      <b>Tablas asociadas</b>
                      <select class="form-select form-select-sm" id="ddl_tabla" name="ddl_tabla" onchange="campos_tabla()">
                        <option value="">Seleccione tabla</option>
                      </select>
                    </div> 
                    <div class="col-sm-8">
                      <div class="row" id="pnl_campos">
                        <b>Campos Asociado</b>
                        <div class="col-sm-4">
                          <div class="input-group">
                            <span>
                              <button class="btn btn-success btn-sm mb-0" title="Agregar Campo" id="agregarFila_medicamentos" type="button"><i class='bx bx-plus me-0'></i></button>
                            </span>
                             <select class="form-select form-select-sm " id="ddl_campo1" name="ddl_campo1" style="width:80%">
                              <option value="">Seleccione Usuario</option>
                            </select>                                  
                          </div>                            
                        </div>
                      </div>
                    </div> 
                   
                    <div class="col-sm-1">
                      <br>
                      <button type="button" class="btn btn-primary btn-sm" onclick="add()">Agregar</button>
                    </div>        
                                               
                </div>
                </form>
                <hr>
                <div class="row">
                	<div class="table-responsive">
                		<table class="table table-hover" id="tbl_lista">
	                		         		
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

