<?php include('../../cabeceras/header2.php'); ?>
<script type="text/javascript">
  $( document ).ready(function() {
    consultar_datos();
    consultar_datos1()
});
     
  function consultar_datos()
  { 
    var colores='';
    var parametros = 
    {
      'id':'',
    	'query':$('#txt_query').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/familiasC.php?lista=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          console.log(item);
         colores+='<tr><td>'+item.id_familia+'</td><td><a href="detalle_familia.php?id='+item.id_familia+'"><u>'+item.detalle_familia+'</u></a></td><td></td></tr>';
        });       
        $('#tbl_datos').html(colores);        
      }
    });
  }

  function consultar_datos1()
  { 
    var colores='';
    var parametros = 
    {
      'id':'',
      'query':$('#txt_query1').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/familiasC.php?subfamilia=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          console.log(item);
         colores+='<tr><td>'+item.id_familia+'</td><td>'+item.familia+'</td><td><a href="detalle_subfamilia.php?id='+item.id_familia+'"><u>'+item.detalle_familia+'</u></a></td></tr>';
        });       
        $('#tbl_datos1').html(colores);        
      }
    });
  }

function limpiar()
{
      $('#codigo').val('');
      $('#descripcion').val('');
      $('#id').val('');
       $('#titulo').text('Nuevo color');
        $('#op').text('Guardar');
           

}
</script>
<div class="content"><br>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          	<div class="col-sm-6" id="btn_nuevo">
              	<a href="detalle_familia.php" class="btn btn-success btn-sm"><i class="bx bx-plus"></i>Nuevo</a>
              	<!-- <a href="#" class="btn btn-default btn-sm" id="excel_colores" title="Informe en excel del total de Colores"><i class="far fa-file-excel"></i> Total Colores</a> -->
            </div>
            <div class="col-sm-6">
                  <a href="detalle_subfamilia.php" class="btn btn-success btn-sm"><i class="bx bx-plus"></i>Nuevo</a>              
            </div>
          </div>         
          <div class="row">
          	<div class="col-sm-6">
              <h5>Familias</h5>
          		<input type="" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Buscar familia" onkeyup="consultar_datos()">
          		<table class="table table-striped">
	          		<thead>
	          			<th>Codigo</th>
	          			<th>Familia</th>
	          		</thead>
	          		<tbody id="tbl_datos">
	          			<tr>
	          				<td></td>
	          				<td></td>
	          			</tr>
	          		</tbody>
	          	</table>          		
          	</div>
            <div class="col-sm-6">              
              <h5>SubFamilias</h5>
              <input type="" name="txt_query1" id="txt_query1" class="form-control form-control-sm" placeholder="Buscar sub familia" onkeyup="consultar_datos1()">
              <table class="table table-striped">
                <thead>
                  <th>Codigo</th>
                  <th>Familia</th>
                  <th>Sub Familia</th>
                </thead>
                <tbody id="tbl_datos1">
                  <tr>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>  
              
            </div>          	
          </div>
      </div>
  </section>
</div>
