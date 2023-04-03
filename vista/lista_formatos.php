<?php include('./header.php'); ?>
<script type="text/javascript">
  $( document ).ready(function() {
    cargar_formatos();
});

  function cargar_formatos()
  {   
  	lineas = '';
     $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/formato_tagsC.php?formato=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) {
         	$.each(response,function(i,item){
         		 lineas+='<tr><td>'+item.id_formato_eti+'</td><td>'+item.nombre_etiqueta+'</td><td><button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.id_formato_eti+'\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button><a class="btn btn-primary" tittle="Editar" href="formato_tags.php?forma='+item.id_formato_eti+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a><a href="#" class="btn btn-default btn-md" data-toggle="modal" data-target="#myModal" onclick="generar_vista(\''+item.id_formato_eti+'\')"><span class="glyphicon glyphicon-eye-open" aria-hidden="true" title="Vista previa"></span></a></td></tr>';
         	})
         	$('#tbl_datos').html(lineas);
         
         }
   
         });
  }
  function generar_vista(id_f)
{
  id = $('#ddl_formato').val();
  if(id!="")
  {
   var parametros = {
    'id_formato':id_f,
    'id_art':1,
  }
  codigos ='';    
  $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/impresion_tagC.php?generarT=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
          console.log(response);
          $.each(response[1].IMAGEN, function(i,item){
            console.log(item);
            codigos+=item.imagen;
          })
          $('#codes').html(codigos);
          
          modificar_canvas(response[2].DATOS,response[0]);
      }
    });
  }else
  {
    Swal.fire( '',
                  'Seleccione Un formato.',
                  'error');

  }

}     

  function modificar_canvas(dimenciones,texto)
  {
    console.log(dimenciones);
    console.log(texto.texto);

    if ( $("#img_qr").length) {
         $('#img_qr').width(dimenciones.qr_w);
         $('#img_qr').height(dimenciones.qr_h);
         $('#img_qr').css('top',dimenciones.qr_y+'px');
         $('#img_qr').css('left',dimenciones.qr_x+'px');
     }
    if ( $("#img_br").length ) {
        $('#img_br').width(dimenciones.br_w);
        $('#img_br').height(dimenciones.br_h);
        $('#img_br').css('top',dimenciones.br_y+'px');
        $('#img_br').css('left',dimenciones.br_x+'px');
      }

    if($('#txt_alternativo')!='')
    {
      $("#lbl_alternativo").remove();
      var label = "<label id='lbl_alternativo' style='font-size:"+dimenciones.tamano_texto+"px; position:relative; left:"+dimenciones.texto_x+"px; top:"+dimenciones.texto_y+"px'>"+texto.texto+"</laber>";
      $('#codes').append(label);
    }
    
  }
  function delete_datos(id)
  {
    Swal.fire({
  title: 'Esta usted seguro?',
  text: "Esta seguro de eliminar este registro!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si!'
}).then((result) => {
  if (result.value) {
   $.ajax({
      data:  {id:id},
      url:   '../controlador/impresion_tagC.php?delete=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
         if(response ==1)
         {
           Swal.fire('Eliminado!','Registro Eliminado.',  'success' );
           cargar_formatos();
         }
      }
    })

  }
})

  }
</script>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Formatos de tag</h1>
          <div class="row">
            <div class="col-sm-9">
              <a href="formato_tags.php" class="btn btn-success btn-md"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Nuevo</a>
            </div>
            <div class="row">
              <div class="col-sm-9">
                <div class="input-group">
                  <br>
                  <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control" placeholder="Buscar">                  
                </div>
              </div>              
            </div>
          </div>
          <div class="table-responsive">          	
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Nombre del formato</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody id="tbl_datos">
               
              </tbody>
            </table>
          </div>
        </div>

        <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Generando etiqueta</h3>
      </div>
      <div class="modal-body">
        <b>vista previa<b>          
          <div id="codes" style="border:1px solid #d3d3d3; height: 198px ; width: 397px"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="crearimagen">Guardar</button>
        <button type="button" class="btn btn-primary" id="abrir_imagen" onclick=" abrir_impre()">abrir impresora</button>
      </div>
    </div>
  </div>
</div>

        <?php include('./footer.php'); ?>
     