
function solicitudes()
{
	 var soli = $('#pnl_solicitudes').html();   
	 var soli_num = $('#pnl_soli').text();        
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/prestamos_bienesC.php?lista_solicitudes=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {   
       // console.log(response); 
       	html ="";
       	response.forEach(function(item,i){
       		html+='<a class="dropdown-item" href="inicio.php?acc=ingresar_proceso&id='+item.id_solicitud+'&estado='+item.estado+'#step-1">'+
											'<div class="d-flex align-items-center">'+
													'<div class="notify bg-light-primary text-primary">'+
															'<i class="bx bx-group"></i>'+
													'</div>'+
												'<div class="flex-grow-1">'+
													'<h6 class="msg-name">Solicitud de salida de bienes <span class="msg-time float-end">5 sec ago</span></h6>'+
													'<p style="font-size:11px" class="msg-info">'+item.PERSON_NOM+'</p>'+
												'</div>'+
											'</div>'+
										'</a>';

						soli_num = parseInt(soli_num)+1;
       	
       	});       

       	if(soli_num>0){$('#pnl_soli').css('display','flex')}         		
        $('#lbl_soli').text(soli_num);

        $('#pnl_solicitudes').html(html+soli);
     }
   });
}
function notificaciones()
{
	 var noti = $('#pnl_notificaciones').html();
	 var noti_num = $('#lbl_noti').text();

	 $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/prestamos_bienesC.php?lista_notificaciones=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {   
       console.log(response); 
       	html ="";
       	response.forEach(function(item,i){
       		html+='<a class="dropdown-item" href="ingresar_proceso.php?id='+item.id_solicitud+'&estado='+item.estado+'#step-'+item.paso+'">'+
										'<div class="d-flex align-items-center">'+
											'<div class="notify bg-light-primary text-danger"><i class="bx bx-box"></i>'+
											'</div>'+
											'<div class="flex-grow-1">'+
												'<h6 class="msg-name">Devolucion de bien <span class="msg-time float-end">'+item.fecha_regreso.date.substr(0,10)+'</span></h6>'+
												'<p style="font-size:11px" class="msg-info">'+item.PERSON_NOM+'</p>'+
											'</div>'+
										'</div>'+
									'</a>';
						noti_num = parseInt(noti_num)+1;
       	});    
       	if(noti_num>0){$('#pnl_noti').css('display','flex')}  
        $('#pnl_notificaciones').html(html+noti);
        $('#lbl_noti').text(noti_num);
     }
   });
	 console.log(noti);
}