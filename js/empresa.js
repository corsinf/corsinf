// tipo_usuario();
$(document).ready(function () {

    tipo_usuario();
	empresa();	
  //subir certificados

    $("#btn_certificados").on('click', function() {
     var fileInput = $('#file_certificado').get(0).files[0];  
      if(fileInput=='')
      {
        Swal.fire('','Seleccione el certificado','warning');
        return false;
      }

        var formData = new FormData(document.getElementById("form_certi"));
         $.ajax({
            url: '../controlador/empresaC.php?cargar_certi=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea un archivo p12.',
                  'error')
               }else
               {
                $('#file_certificado').empty();
                empresa();                
               } 
            }
        });
    });
});



function subir_imagen()
{

       
     var fileInput = $('#file_img').get(0).files[0];

     var file = $('#file_img').val();
  
      if(file=='')
      {
        return false;
      }

        var formData = new FormData(document.getElementById("form_img"));
         $.ajax({
            url: '../controlador/empresaC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }else
               {
                $('#file_img').empty();
               empresa();                
               } 
            }
        });
}

function disparar_noti()
{
	// setInterval(notificaciones,10000);
	// notificaciones()
}

function empresa()
{	
    $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/empresaC.php?empresa_dato=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            console.log(response); 
                if(response[0].Ambiente==1)
                {
                    $('#rbl_ambiente_1').prop('checked',true);
                }else
                {                    
                    $('#rbl_ambiente_2').prop('checked',true);
                }
                 if(response[0].obligadoContabilidad=='0')
                {
                    $('#rbl_conta_no').prop('checked',true);
                }else
                {                    
                    $('#rbl_conta_si').prop('checked',true);
                }

                if(response[0].facturacion_electronica==0)
                {
                    $('#rbl_fac_no').prop('checked',true);
                }else
                {                    
                    $('#rbl_fac_si').prop('checked',true);
                }
                if(response[0].procesar_automatico==0)
                {
                    $('#rbl_proce_no').prop('checked',true);
                }else
                {                    
                    $('#rbl_proce_si').prop('checked',true);
                }

                $("#txt_nom_comercial").val(response[0].Nombre_Comercial)
                $("#txt_razon").val(response[0].Razon_Social)
                $("#txt_ci_ruc").val(response[0].Ruc)
                $("#txt_direccion").val(response[0].Direccion)
                $("#txt_telefono").val(response[0].Telefono)
                $("#txt_Email").val(response[0].Email)
                $("#txt_titulo_pesta").val(response[0].titulo_pestania)
                $("#txt_host").val(response[0].smtp_host)


                if (response[0].smtp_host.includes('gmail')) {
                    $('#rbl_tipo_smtp_gmail').prop('checked',true);
                    $('#txt_host').prop('readonly',true);
                } else if(response[0].smtp_host.includes('office365')) {
                    $('#rbl_tipo_smtp_oficce').prop('checked',true);
                    $('#txt_host').prop('readonly',true);
                }


                $("#txt_usuario").val(response[0].smtp_usuario)
                $("#txt_pass").val(response[0].smtp_pass)
                $("#txt_puerto").val(response[0].smtp_port)
                if(response[0].smtp_port=='465')
                {
                    $('#rbl_puerto_465').prop('checked',true);
                    $('#txt_puerto').prop('readonly',true);
                    $('#txt_secure').prop('readonly',true);

                }else if(response[0].smtp_port=='587')
                {
                    $('#rbl_puerto_587').prop('checked',true);
                    $('#txt_puerto').prop('readonly',true);
                    $('#txt_secure').prop('readonly',true);
                }

                $("#txt_secure").val(response[0].smtp_secure)

                $("#txt_db_host").val(response[0].Ip_host)
                $("#txt_db_usuario").val(response[0].Usuario_db)
                $("#txt_db_pass").val(response[0].Password_db)
                $("#txt_db_puerto").val(response[0].Puerto_db)
                $("#txt_db").val(response[0].Base_datos)
                $("#txt_iva").val(response[0].Valor_iva)
                $("#txt_mesas").val(response[0].N_MESAS)
                $("#ddl_tipo_usuario").val(response[0].encargado_envios)


                $('#txt_Ip_dir').val(response[0].ip_directory);
                $('#txt_puerto_dir').val(response[0].puerto_directory);
                $('#txt_base_dir').val(response[0].basedn_directory);
                $('#txt_usuario_dir').val(response[0].usuario_directory);
                $('#txt_pass_dir').val(response[0].password_directory);
                $('#txt_dominio_dir').val(response[0].dominio_directory);

                $('#txt_nom_img').val(response[0].Ruc);
                if(response[0].Logo !=null)
                {
                    console.log(response[0].Logo);
                    $("#img_foto").attr('src',response[0].Logo+'?'+Math.random())
                }
                var t = '<tr><td colspan="4">Sin certificados </td></tr>';

                if(response[0]['Ruta_Certificado']!='')
                {
                    var t = '<tr><td>'+response[0]['Ruta_Certificado']+'</td><td>'+response[0]['Clave_Certificado']+'</td><td><button class="btn btn-sm btn-danger" onclick="eliminar_cert()"><i class="fa fa-trash"></i></button></td></tr>';  
                }
                $('#tbl_certificados').html(t)

                $('#txt_url_api_idukay').val(response[0].url_api_idukay);
                $('#txt_token_idukay').val(response[0].token_idukay);
                $('#txt_anio_lectivo_idukay').val(response[0].anio_lectivo_idukay);
        }
      });

}

function eliminar_cert()
{
    Swal.fire({
          title: 'Esta seguro',
          text: "Esta apunto de eliminar sus certificados electronicos",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continuar'
        }).then((result) => {
          if (result.value) {
            elim_certi()
          }
        })
}
function elim_certi()
{
    $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/empresaC.php?eli_certi=true',
      type:  'post',
      dataType: 'json',      
      success:  function (response) { 
        if(response==1)
        {
            Swal.fire('Certificados eliminados','','success').then(function(){
                empresa()
            })
        }

        }
    })
}

function guardar_datos()
{

Swal.fire({
  title: 'Esta segur de guardar los datos de empresa',
  text: "Al guardar debera iniciar session de nuevo",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Continuar'
}).then((result) => {
  if (result.value) {
     insertar();
  }
})

  }

 
  
  function insertar()
  {
    var parametros = 
    {                     
        'Ambi':$('input[name=rbl_ambi]:checked').val(),
        'conta':$('input[name=rbl_conta]:checked').val(),
        'fact':$('input[name=rbl_fac]:checked').val(),
        'proce':$('input[name=rbl_proce]:checked').val(),
    
        'nom':$("#txt_nom_comercial").val(),
        'raz':$("#txt_razon").val(),
        'ci':$("#txt_ci_ruc").val(),
        'dir':$("#txt_direccion").val(),
        'tel':$("#txt_telefono").val(),
        'ema':$("#txt_Email").val(),
        'titPes':$("#txt_titulo_pesta").val(),
        'host':$("#txt_host").val(),
        'usu':$("#txt_usuario").val(),
        'pass':$("#txt_pass").val(),
        'puesto':$("#txt_puerto").val(),
        'secure':$("#txt_secure").val(),

        'dbhost':$("#txt_db_host").val(),
        'dbusuario':$("#txt_db_usuario").val(),
        'dbpass':$("#txt_db_pass").val(),
        'dbpuerto':$("#txt_db_puerto").val(),
        'db':$("#txt_db").val(),
        'iva':$("#txt_iva").val(),
        'mesa':$("#txt_mesas").val(),
        'responsable_envios':$("#ddl_tipo_usuario").val(),

        //directory
        'ip_dir': $('#txt_Ip_dir').val(),
        'puerto_dir': $('#txt_puerto_dir').val(),
        'base_dir': $('#txt_base_dir').val(),
        'usu_dir': $('#txt_usuario_dir').val(),
        'pass_dir': $('#txt_pass_dir').val(),
        'dominio_dir': $('#txt_dominio_dir').val(),

        //Idukay
        'idukay_url': $('#txt_url_api_idukay').val(),
        'idukay_token': $('#txt_token_idukay').val(),
        'idukay_anio_lec': $('#txt_anio_lectivo_idukay').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/empresaC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        if(response == 1)
        {
          // $('#myModal').modal('hide');
         subir_imagen();
          Swal.fire(
            '',
            'Operacion realizada con exito.',
            'success'
          ).then(function(){cerrar_session();})

        }
        else if(response==-2)
        {
            Swal.fire('','CI repetido.','warning');
        }
        else
        {
            Swal.fire('','Usuario agregado.','success').then(function(){ location.href = 'detalle_usuario.php?id='+response});           
        }  
               
      }
    });

  }

  function tipo_usuario()
  {
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/empresaC.php?tipo_usuario=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            $('#ddl_tipo_usuario').html(response);  
        }
      });
  }

  function probar_directory()
  {
    var parametros = 
    {  
        //directory
        'ip_dir': $('#txt_Ip_dir').val(),
        'puerto_dir': $('#txt_puerto_dir').val(),
        'base_dir': $('#txt_base_dir').val(),
        'usu_dir': $('#txt_usuario_dir').val(),
        'pass_dir': $('#txt_pass_dir').val(),
        'dominio_dir': $('#txt_dominio_dir').val(),
    }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresaC.php?probar_conexion_dir=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {
                Swal.fire("Active Directory","conexion exitosa","success");
            }else
            {                
                Swal.fire("Active Directory","No se pudo conectar","error");
            }
            console.log(response)
        }
      });
  }

    function smtp_type()
    {
        var smtpType = $('input[name="rbl_tipo_smtp"]:checked').val();

        switch(smtpType)
        {
            case '1':
                $('#txt_host').val('smtp.office365.com')
                $('#txt_host').prop('readonly',true);
                break;

            case '2':               
                $('#txt_host').val('smtp.gmail.com')
                $('#txt_host').prop('readonly',true);
                break;

            case '3':               
                $('#txt_host').val('')
                $('#txt_host').prop('readonly',false);
                break;
        }

        console.log(smtpType);

    }

    function smtp_puerto()
    {
        var smtpType = $('input[name="rbl_puerto"]:checked').val();
        switch(smtpType)
        {
            case '1':
                $('#txt_puerto').val('465')
                $('#txt_secure').val('ssl')
                $('#txt_puerto').prop('readonly',true);
                $('#txt_secure').prop('readonly',true);
                break;

            case '2':               
                $('#txt_puerto').val('587')
                $('#txt_secure').val('tls')
                $('#txt_puerto').prop('readonly',true);
                $('#txt_secure').prop('readonly',true);
                break;

            case '3':               
                $('#txt_puerto').val('')
                $('#txt_secure').val('')
                $('#txt_puerto').prop('readonly',false);
                $('#txt_secure').prop('readonly',false);
                break;
        }
    }