<?php include('../../cabeceras/header2.php'); $id = ''; if(isset($_GET['id'])){$id=$_GET['id'];} ?>
<script type="text/javascript">
$( document ).ready(function() {
	var id = '<?php echo $id; ?>';
  if(id!='')
  {
	  datos_col(id);
  }

});

  function datos_col(id)
  { 
    $('#titulo').text('Editar Estado');
    $('#op').text('Editar');
    var estado='';

    $.ajax({
      data:  {id:id},
      url:   '../../controlador/estadoC.php?lista=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
           $('#codigo').val(response[0].CODIGO); 
           $('#descripcion').val(response[0].DESCRIPCION);
           $('#id').val(response[0].ID_ESTADO); 
      }
    });
  }


  function editar_insertar()
  {
     var codigo = $('#codigo').val();
     var descri = $('#descripcion').val();
     var id = $('#id').val();
    
      var parametros = {
        'cod':codigo,
        'des':descri,
        'id':id,
      }
      if(id=='')
        {
          if(codigo == '' || descri == '')
            {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Asegurese de llenar todo los campos',
               })
            }else
            {
             insertar(parametros)
          }
        }else
        {
           if(codigo == '' || descri == '')
            {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Asegurese de llenar todo los campos',
               })
            }else
            {
              insertar(parametros);
            }
        }
  }

  function insertar(parametros)
  {
     $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/estadoC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
         if(response == 1)
        {
          Swal.fire('','Operacion realizada con exito.','success').then(function(){          
          location.href = 'estado.php';
         });
        }else if(response==-2)
        {
          Swal.fire('','codigo ya regitrado','info');
        }  
               
      }
    });

  }

  
   function delete_datos()
  {
    var id = '<?php echo $id; ?>';
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
    eliminar(id);    
  }
})

  }

   function eliminar(id)
  {
     $.ajax({
      data:  {id:id},
      url:   '../../controlador/estadoC.php?eliminar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {  
        if(response == 1)
        {
         Swal.fire('Eliminado!','Registro Eliminado.','success').then(function(){          
          location.href = 'estado.php';
         });
        }  
               
      }
    });
  }
</script>

<div class="row">
  <div class="col-xl-12 mx-auto">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <a href="estado.php" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i>Regresar</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="hidden" name="id" id="id" class="form-control form-control-sm" hidden="">
               Codigo estado<br>
               <input type="input" name="codigo" id="codigo" class="form-control form-control-sm">  
               Descripcion estado<br>
               <input type="input" name="descripcion" id="descripcion" class="form-control form-control-sm">   
                      
            </div>
            <div class="col-sm-6">
              

            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary btn-sm" onclick="editar_insertar()" type="button" id="btn_editar"><i class="bx bx-save"></i> Guardar</button>
            <button class="btn btn-danger btn-sm" onclick="delete_datos()" type="button" id="btn_eliminar"><i class="bx bx-trash"></i> Eliminar</button>          
        </div>
       
      </div>
    </div>
  </div>
</div>

