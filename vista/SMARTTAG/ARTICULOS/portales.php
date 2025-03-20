<script type="text/javascript">
 $( document ).ready(function() {
  cargar_datos();  
  cargar_datos_controladora();
 })

let intervalo;
let intervalo2;
function cargar_datos_controladora() {
   
    $.ajax({
      // data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?lista=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        tr = '';
        response.forEach(function(item,i){
          tr+=`<option value="`+item.id+`">`+item.nombre+`</option>`
        })
        $('#dll_conroladoras').html(tr);       
      }

    });
 }


 function comenzar_lectura()
 {
    controladora =  $('#dll_conroladoras').val();  
    parametros = 
    {
      'id':controladora,
    }
    $.ajax({
        data: {parametros: parametros},
        url: '../controlador/PORTALES/portalesC.php?comenzar_lectura_log=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.resp=='-1')
          {
            Swal.fire("No se pudo conectar",response.msj,'error').then(function(){
              $('#modal_respuesta').modal('hide');    
            })

          }
        }
    });
 }

 function iniciar()
 {
    $('#dll_conroladoras').prop('disabled',true);
    $('#btn_iniciar').addClass('d-none');
    $('#btn_detener').removeClass('d-none'); 


    intervalo = setInterval(comenzar_lectura, 2000);
    intervalo2 = setInterval(cargar_datos, 2500);
    console.log("Ejecución iniciada.");
 }

 function detener()
 {
   clearInterval(intervalo);
   if (intervalo) { // Verificar si el intervalo está activo
        clearInterval(intervalo);
        clearInterval(intervalo2);
        intervalo = null; // Limpiar la variable
        intervalo2 = null;
        Swal.fire("Deteccion Detenida","Podria existir un tiempo de latencia","info")

        $('#dll_conroladoras').prop('disabled',false);
        $('#btn_iniciar').removeClass('d-none');
        $('#btn_detener').addClass('d-none'); 
    }
 }

 function cargar_datos() {
   
    $.ajax({
      // data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?lista_log=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
        console.log(response)
        tr = '';
        response.forEach(function(item,i){
          color = '';
          if(item.descripcion!='' && item.descripcion!=null)
          {
            color = 'style="background: chartreuse;"';
          }
          tr+=`<tr `+color+`>
              <td>`+(i+1)+`</td>
              <td>`+item.rfid+`</td>
              <td>`+item.fecha+`</td>
              <td>`+item.antena+`</td>
              <td>`+item.controladora+`</td>
              <td>`+item.descripcion+`</td>
            </tr>`
        })
        $('#tbl_body').html(tr);       
      }

    });
 }


</script>
<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Portales</div>
      <?php
      // print_r($_SESSION['INICIO']);die();

      ?>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Portales
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-body">
           <div class="row">
             <div class="col-12">
              <div class="input input-group">
                <select class="form-select" id="dll_conroladoras" onchange="detener()"></select>
                <button class="btn btn-primary"  onclick="iniciar()" id="btn_iniciar"><i class="bx bx-play"></i></button>
                <button class="btn btn-danger d-none" onclick="detener()" id="btn_detener" ><i class="bx bx-stop"></i></button>
              </div>     
            </div>
            <div class="col-12">              
              <b>Lista de Etiquetas detectadas</b>
              <table class="table table-hover">
                <thead>
                  <th>#</th>
                  <th>RFID</th>
                  <th>Fecha</th>
                  <th>Antena</th>
                  <th>Controladora</th>
                  <th>Articulos</th>
                </thead>
                <tbody id="tbl_body">
                  
                </tbody>                
              </table>
            </div>
          </div>
          
        </div>
      </div> 
    </div>
  </div>
</div>