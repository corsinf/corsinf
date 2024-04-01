
function cargar_detalle_activo(id)
{
	$.ajax({
      data:  {id:id},
      url:   '../controlador/detalle_articuloC.php?cargar_detalle_activo=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
	        if(response!='')
	        {
	        	$('#lbl_nombre').text(response[0].nom);
				$('#lbl_nombre2').text(response[0].nom);
				$('#lbl_catacteristicas').text(response[0].CARACTERISTICA);
				$('#lbl_asset').text(response[0].tag_s);
				$('#lbl_antiguo').text(response[0].ant);
				$('#lbl_rfid').text(response[0].rfid);
				$('#lbl_modelo').text(response[0].MODELO)
				$('#lbl_serie').text(response[0].SERIE)
				$('#lbl_marca').text(response[0].marca)
				$('#lbl_emplazamiento').text(response[0].DENOMINACION)
				$('#lbl_color').text(response[0].color);
				if(response[0].IMAGEN!='' && response[0].IMAGEN != null)
				{
					$('#img_producto').prop('src','../img/'+response[0].IMAGEN);
				}
	        	console.log(response);

	        }   
        }
    })
}