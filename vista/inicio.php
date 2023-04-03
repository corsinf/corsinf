<?php include('./header.php'); include('../controlador/inicioC.php');
if(isset($_GET['pnt']) && $_GET['pnt']!=''){$pnt = $_GET['pnt']; $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'] = $pnt;} 
if(isset($_GET['nom_pnt']) && $_GET['nom_pnt']!=''){$nom_pnt = $_GET['nom_pnt']; $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM'] = $nom_pnt;} 
$punto = $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];
?>
<script type="text/javascript">
   $( document ).ready(function() {
    bodegas_punto();
     var pun = '<?php echo $punto;?>';
   });
   function bodegas_punto()
   { 

    var punto = '<?php echo $punto;?>';
    if(punto=='')
    {
      $('#modal_punto_venta').modal('show'); $.ajax({
         // data:  {num:num},
         url:   '../controlador/facturacionC.php?punto_venta=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           $('#ddl_puntos').html(response);
          } 
          
       });
    }  
   }
    function cargar_punto()
   {

     var id =  $('#ddl_puntos').val();
     var nom =  $('select[name="ddl_puntos"] option:selected').text();
     $('#txt_id_punto').val(id);
     $('#txt_nom_punto').text(nom);
     var URLactual = window.location;
      $(location).attr('href',URLactual+'?pnt='+id+'&nom_pnt='+nom);
   }
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">INICIO</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <?php print_r( $_SESSION['INICIO']); ?>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>


<!-- modal de punto de venta seleccionar -->

<div class="modal fade" id="modal_punto_venta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Seleccione punto de venta</h5>
      </div>
      <div class="modal-body">
        <form id="form_usuario_new">
        <div class="row">
         <p>Este usuario tiene mas de un punto de venta asignado selecciopne uno</p>
         <select class="form-control form-control-sm" id="ddl_puntos" name="ddl_puntos" onchange="cargar_punto()">
         </select>
        </div>
        </form>
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="" id="btn_opcion">Guardar y continuar</button> -->
          <button type="button" class="btn btn-default" onclick="salir();" id="btn_opcion">Cancelar</button>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
  function salir()
  {
     var url="inicio.php";
     $(location).attr('href',url);
  }
</script>


<?php include('./footer.php'); ?>
