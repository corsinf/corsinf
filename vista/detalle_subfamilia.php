<?php include('./header2.php'); $id = ''; if(isset($_GET['id'])){$id=$_GET['id'];} ?>
<script type="text/javascript">
$( document ).ready(function() {
	var id = '<?php echo $id; ?>';
  consultar_datos();
  if(id!='')
  {
	  datos_col(id);
  }

});

  function datos_col(id)
  { 
     var parametros = 
    {
      'id':id,
      'query':'',
    }

    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/familiasC.php?subfamilia=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          console.log(response);
           $('#ddl_familia').val(response[0].idF);
           $('#descripcion').val(response[0].detalle_familia);
           $('#id').val(response[0].id_familia); 
      }
    });
  }



  function editar_insertar()
  {
     var familia = $('#ddl_familia').val();
     var descri = $('#descripcion').val();
     var id = $('#id').val();
    
      var parametros = {
        'fam':familia,
        'des':descri,
        'id':id,
      }
      if(id=='')
        {
          if(familia =='' || descri == '')
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
           if(familia =='' || descri == '')
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
      url:   '../controlador/familiasC.php?insertar_sub=true',
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
          location.href = 'familias.php';
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
      url:   '../controlador/familiasC.php?eliminar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
       if(response == 1)
        {
         Swal.fire('Eliminado!','Registro Eliminado.','success').then(function(){          
          location.href = 'familias.php';
         });
        }  
               
      }
    });

  }

  function consultar_datos()
  { 
    var opt='<option value="">Seleccione familia</option>';
    var parametros = 
    {
      'id':'',
      'query':'',
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/familiasC.php?lista=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          // console.log(item);
          opt+='<option value="'+item.id_familia+'">'+item.detalle_familia+'</option>';
        });  

        console.log(opt)     
        $('#ddl_familia').html(opt);        
      }
    });
  }


</script>

<div class="row">
  <div class="col-xl-12 mx-auto">
    <div class="card">
      <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <a href="familias.php" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5">
            <input type="hidden" name="id" id="id" class="form-control" hidden="">
            <b>Familia</b>
           <select class="form-select form-control-sm" name="ddl_familia" id="ddl_familia">
             <option value="">Seleccione familia</option>
           </select>
           <b>Descripcion de subfamilia<b><br>
           <input type="input" name="descripcion" id="descripcion" class="form-control form-control-sm"> 
            </div>
          </div>
           <div class="modal-footer">
              <button class="btn btn-primary btn-sm" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
              <button class="btn btn-danger btn-sm" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
          </div>  
       
      </div>
    </div>
  </div>
</div>

