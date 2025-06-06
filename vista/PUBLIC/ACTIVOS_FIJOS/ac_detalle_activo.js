
function cargar_detalle_activo(id, token) {
	$.ajax({
		data: { id: id, token: token },
		url: 'controlador/ACTIVOS_FIJOS/detalle_articuloC.php?cargar_detalle_activo=true',
		type: 'post',
		dataType: 'json',
		success: function (response) {
			if (response != '') {
				$('#lbl_nombre').text(response[0].nom);
				$('#lbl_nombre2').text(response[0].des);
				$('#lbl_catacteristicas').text(response[0].carac);
				$('#lbl_sku').text(response[0].tag_s);
				$('#lbl_antiguo').text(response[0].ant);
				$('#lbl_rfid').text(response[0].rfid);
				$('#lbl_modelo').text(response[0].mod)
				$('#lbl_serie').text(response[0].ser)
				$('#lbl_marca').text(response[0].marca)
				$('#lbl_localizacion').text(response[0].loc_nom)
				$('#lbl_color').text(response[0].color);
				$('#lbl_estado').text(response[0].estado);
				$('#lbl_genero').text(response[0].genero);

				if (response[0].ruta_imagen != '' && response[0].ruta_imagen !== null) {
					let url_sin_cache = response[0].ruta_imagen + '&v=' + new Date().getTime();
					$("#img_producto").attr("src", url_sin_cache);
				}

				console.log(response);

			}
		}
	})
}