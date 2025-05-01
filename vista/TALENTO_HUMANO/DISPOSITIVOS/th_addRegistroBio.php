<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        listaTarjetas();
        dispositivos();
        dispositivosSync();
        listaHuella()
        <?php if (isset($_GET['_id'])) { ?>
         datos_col(<?= $_id ?>);
        <?php } ?>
    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);


                $('#txt_primer_apellido').val(response[0]['primer_apellido']);
                $('#txt_segundo_apellido').val(response[0]['segundo_apellido']);
                $('#txt_primer_nombre').val(response[0]['primer_nombre']);
                $('#txt_segundo_nombre').val(response[0]['segundo_nombre']);
                $('#txt_cedula').val(response[0]['cedula']);
                $('#ddl_sexo').val(response[0]['sexo']);
                $('#txt_fecha_nacimiento').val(response[0]['fecha_nacimiento']);
                $('#txt_correo').val(response[0]['correo']);
                $('#txt_telefono_1').val(response[0]['telefono_1']);
                $('#txt_telefono_2').val(response[0]['telefono_2']);
                $('#cbx_admin').prop('checked', (response[0]['es_admin'] == 1));
                $('#cbx_habilitado').prop('checked', (response[0]['habiltado'] == 1));
                $('#ddl_estado_civil').val(response[0]['estado_civil']);
                $('#txt_postal').val(response[0]['postal']);
                $('#txt_direccion').val(response[0]['direccion']);
                $('#txt_fecha_aut_inicio').val(response[0]['fecha_aut_inicio']);
                $('#txt_fecha_aut_limite').val(response[0]['fecha_aut_limite']);
                $('#txt_fecha_admision').val(response[0]['fecha_admision']);
                $('#txt_cargo').val(response[0]['cargo']);
                $('#txt_observaciones').val(response[0]['observaciones']);
                $('#txt_CardNumero').val(response[0]['biometria']['th_bio_card']);

                // //$('#txt_foto_url').val(response[0]['foto_url']);

                calcular_edad('txt_edad', response[0]['fecha_nacimiento']);

            }
        });
    }

    function editar_insertar() {

        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_cedula = $('#txt_cedula').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var txt_edad = $('#txt_edad').val();
        var txt_correo = $('#txt_correo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var cbx_admin = $('#cbx_admin').prop('checked') ? 1 : 0;
        var cbx_habilitado = $('#cbx_habilitado').prop('checked') ? 1 : 0;
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var txt_postal = $('#txt_postal').val();
        var txt_direccion = $('#txt_direccion').val();
        var txt_fecha_aut_inicio = $('#txt_fecha_aut_inicio').val();
        var txt_fecha_aut_limite = $('#txt_fecha_aut_limite').val();
        var txt_fecha_admision = $('#txt_fecha_admision').val();
        var txt_cargo = $('#txt_cargo').val();
        var txt_observaciones = $('#txt_observaciones').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_cedula': txt_cedula,
            'ddl_sexo': ddl_sexo,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'txt_edad': txt_edad,
            'txt_correo': txt_correo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'cbx_admin': cbx_admin,
            'cbx_habilitado': cbx_habilitado,
            'ddl_estado_civil': ddl_estado_civil,
            'txt_postal': txt_postal,
            'txt_direccion': txt_direccion,
            'txt_fecha_aut_inicio': txt_fecha_aut_inicio,
            'txt_fecha_aut_limite': txt_fecha_aut_limite,
            'txt_fecha_admision': txt_fecha_admision,
            'txt_cargo': txt_cargo,
            'txt_observaciones': txt_observaciones,
        };

        if ($("#form_persona").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_cedula).addClass('is-invalid');
                    $('#error_txt_cedula').text('La cédula ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_cedula').on('input', function() {
            $('#error_txt_cedula').text('');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
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

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas';
                    });
                }
            }
        });
    }

    function cambiar(finger)
    {
        $('.btn-outline-primary').removeClass('active');
        $('#img_palma').attr('src','../img/de_sistema/palma'+finger+'.gif');
        $('#btn_finger_'+finger).addClass('active');
        $('#txt_dedo_num').val(finger);
    }

    function leerDedo()
    {
        // $('#myModal_espera').modal('show');
        var parametros = 
        {
            'iddispostivos':$('#ddl_dispositivos').val(),
            'idPerson': $('#txt_id').val(), //usuario id
            'dedo':$('#txt_dedo_num').val(),
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_personasC.php?CapturarFinger=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
                $('#myModal_espera').modal('hide');
                if(response.resp==1)
                {
                    Swal.fire("Huella dactilar Guardada",response.patch,"success");
                    $('#file-name_bio').text(response.patch);
                }else
                {
                    Swal.fire("Huella dactilar",response.msj,"info");
                }

               
                     tbl_dispositivos.ajax.reload(null, false);

            } ,
              error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }         
        });
    }

     function dispositivos() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item,i){
                    op+='<option value="'+item._id+'">'+item.nombre+'</option>';
                })
                $('#ddl_dispositivos').html(op);
               
            },  error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function dispositivosSync() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item,i){
                    op+='<option value="'+item._id+'">'+item.nombre+'</option>';
                })
                $('#ddl_dispositivosSync').html(op);
               
            },  error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function eliminarfinger(id) {
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
                eliminarFing(id);
            }
        })
    }

    function eliminarFing(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?eliminarFing=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        tbl_dispositivos.ajax.reload(null, false);
                    });
                }
            }
        });
    }


    function syncronizarPersona()
    {
        $('#sync_biometrico').modal('show');

    }

    function syncronizarPersonaBio()
    {
        if($('#txt_CardNumero').val()=='')
        {
            Swal.fire("Debe tener numero de tarjea en biometria","","info");
            return false;
        }
        id = '<?= $_id ?>';
        parametros = 
        {
            'id':id,
            'device':$('#ddl_dispositivosSync').val(),
            'card':$('#txt_CardNumero').val(),
        }
          $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?syncronizarPersona=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                Swal.fire('', response.msj, 'success')            
            }
        });

    }

    function nuevaTarjeta()
    {
        $('#nuevaTarjeta').modal('show');
        generar_CardNo();
    }

    function listaTarjetas()
    {
        if ($.fn.DataTable.isDataTable('#tbl_cards')) {
            $('#tbl_cards').DataTable().destroy();
        }
        tbl_dispositivos = $('#tbl_cards').DataTable($.extend({},{
            reponsive: true,
            searching: false,  // Desactiva el buscador
            paging: false,     // Desactiva la paginación
            info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                type:'POST',
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaTarjetas=true',
                data: function (d) {
                   
                     var parametros = {
                      id:$('#txt_id').val(), // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                 { data: null,
                        render: function(data, type, item) {
                        return `<div class="d-flex align-items-center">
                                    <div class="">
                                        <img src="../img/de_sistema/card.png" width="46" height="46" alt="">
                                    </div>
                                    <div class="ms-2">
                                        <h6 class="mb-1 font-14"><b>${item.th_cardNo}</b></h6>
                                    </div>
                                </div>`;
                        }
                    },                
                    { data: null,
                        render: function(data, type, item) {
                         return ` <div class="list-inline d-flex customers-contacts ms-auto"> 
                                    <a href="javascript:;" class="list-inline-item bg-danger" onclick="deteleTarjeta('${item.th_cardNo}')"><i class="bx bxs-trash"></i></a>
                                </div>`;
                        }
                    },        

               
            ],
            order: [
                [1, 'asc']
            ],
        }));

    }

    function addTarjetaBio()
    {
        var id = $('#txt_id').val(); 
        var parametros = 
        {
            'device':$('#ddl_dispositivos').val(),
            'idPerson':id,
            'CardNo':$('#txt_CardNumero').val(),
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?addTarjetaBio=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response['resp']==1)
                {
                    Swal.fire("Tarjeta registrada","","success").then(function(){
                        $('#nuevaTarjeta').modal('hide');
                         listaTarjetas();
                    })
                }
                // $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }

    function deteleTarjeta(CardNo)
    {

        var id = $('#txt_id').val(); 
        Swal.fire({
          title: 'Quiere eliminar este registro?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                 var parametros = 
                {
                    'device':$('#ddl_dispositivos').val(),
                    'idPerson':id,
                    'CardNo':CardNo,
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?DeleteTarjetaBio=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response['resp']==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                listaTarjetas();
                            })
                        }
                    }
                });
            }
        })
    }

    function nuevahuellaBio()
    {
    	$('#nuevahuella').modal('show');
    }

    function listaHuella()
    {
        if ($.fn.DataTable.isDataTable('#tbl_bio_finger')) {
            $('#tbl_bio_finger').DataTable().destroy();
        }
        tbl_dispositivos = $('#tbl_bio_finger').DataTable($.extend({},{
            reponsive: true,
            searching: false,  // Desactiva el buscador
            paging: false,     // Desactiva la paginación
            info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                type:'POST',
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?listaHuellas=true',
                data: function (d) {
                   
                     var parametros = {
                      id:$('#txt_id').val(), // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                 { data: null,
                        render: function(data, type, item) {
                        return `<div class="d-flex align-items-center">
                                    <div class="">
                                        <img src="../img/de_sistema/finger_huella.png" width="46" height="46" alt="">
                                    </div>
                                    <div class="ms-2">
                                        <h6 class="mb-1 font-14"><b>Dedo No:${item.th_finger_numero}</b></h6>
                                    </div>
                                </div>`;
                        }
                    },   
                     { data: 'th_cardNo'},                
                    { data: null,
                        render: function(data, type, item) {
                         return ` <div class="list-inline d-flex customers-contacts ms-auto"> 
                                    <a href="javascript:;" class="list-inline-item bg-danger" onclick="deteleHuella('${item.th_cardNo}','${item._id}','${item.th_finger_numero}')"><i class="bx bxs-trash"></i></a>
                                </div>`;
                        }
                    },        

               
            ],
            order: [
                [1, 'asc']
            ],
        }));

    }

    function addHuellaBio()
    {
        var formData = new FormData();
        var archivo = $('#file_huella')[0].files[0]; // obtener el archivo
        var device = $('#ddl_dispositivos').val();
        var idPerson =  $('#txt_id').val(); 
        var detectado = $('#file-name_bio').text();
        var numfinger = $('#txt_dedo_num').val();

        // console.log(archivo)

        if(detectado=='' && archivo==undefined)
        {
            Swal.fire("No sea encontrado huella valida","","info")
            return false;
        }

        formData.append('huella', archivo); // adjuntar al formData
        formData.append('iddispostivos', device);
        formData.append('idPerson', idPerson);
        formData.append('detectado', detectado);
        formData.append('NumFinger', numfinger);

        $.ajax({
           url: '../controlador/TALENTO_HUMANO/th_personasC.php?addHuellaBio=true',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response)
                if(response.resp==1)
                {
                    Swal.fire("Huella digital agregada","","error");
                }else
                {
                    Swal.fire("","","info")
                }
            },
            error: function () {
                console.error('Error al subir el archivo');
            }
        });
    }

    function deteleHuella(CardNo,idHuella,item)
    {

        var id = $('#txt_id').val(); 
        Swal.fire({
          title: 'Quiere eliminar este registro?',
          text: "Esta seguro de eliminar este registro!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                 var parametros = 
                {
                    'device':$('#ddl_dispositivos').val(),
                    'idPerson':id,
                    'CardNo':CardNo,
                    'NumFinger':item,
                    'idHuella':idHuella
                }
                $.ajax({
                    data: { parametros: parametros },
                    url: '../controlador/TALENTO_HUMANO/th_personasC.php?deteleHuella=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if(response==1)
                        {
                            Swal.fire("Eliminado","","success").then(function(){
                                listaHuella();
                            })
                        }
                    }
                });
            }
        })
    }
    
    function nuevofacial()
    {
        if($('#txt_tarjeta').val()=='')
        {
            Swal.fire("Ingrese una tarjeta primero","","info");
            return false;
        }
    	$('#nuevofacial').modal('show');
    }

    function generar_CardNo()
    {
        $.ajax({
            // data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?generar_CardNo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_CardNumero').val(response[0]['NUMERO'])
            }
        });
    }
  
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Persona
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                    	<div class="row">
                    		
                    		<div class="col-12 col-xl-6 d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-2">Datos Biometricos</h5>
									</div>									
								</div>
								<div class="row">
									<div class="col-sm-12">
										Biometrico
                                        <select class="form-select" id="ddl_dispositivos" name="ddl_dispositivos">
                                            <option value="" >Seleccione Dispositivo</option>
                                        </select>                                                                    
                                    </div>
                                    <input type="" name="txt_id" id="txt_id" class="form-control form-control-sm" readonly value="11">							
								</div>
							</div>
							<div class="customers-list p-3 mb-3 ps ps--active-y">
                                <div class="d-none customers-list-item d-flex align-items-center border-top border-bottom border-1 p-3 cursor-pointer">
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <div class="card radius-10"> -->
                                                <!-- <div class="card-body"> -->
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h4 class="my-1">Nueva Tarjeta</h4>
                                                        </div>
                                                        <div class="text-success ms-auto font-35" onclick="nuevaTarjeta()"><i class="bx bx-plus"></i>
                                                        </div>
                                                    </div>
                                                <!-- </div> -->
                                            <!-- </div> -->
                                        </div>
                                        <div class="col-12">
                                            <table class="table table-hover" id="tbl_cards" style="width:100%">
                                                <thead>
                                                    <th>Numero Tarjeta</th>
                                                    <th>Acción</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table> 
                                        </div> 
                                    </div>
                                </div>								
								<div class="customers-list-item d-flex align-items-center border-top border-bottom border-1 p-3 cursor-pointer">
									<div class="row">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h4 class="my-1">Nueva Huella digital</h4>
                                                </div>
                                                <div class="text-success ms-auto font-35" onclick="nuevahuellaBio()"><i class="bx bx-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table table-hover" id="tbl_bio_finger" style="width:100%">
                                                <thead>
                                                    <th>Numero de Dedo</th>
                                                    <th>Tarjeta Asociada</th>
                                                    <th>Acción</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
									   </div>
								    </div>
                                </div>
								
								<div class="customers-list-item d-flex align-items-center border-top border-bottom border-1 p-3 cursor-pointer">
									<div class="ms-2">
										<h4 class="mb-1"><b>Datos Facial</b></h4>
										<p class="mb-0 font-13 text-secondary">laura_01@xyz.com</p>
										<img src="assets/images/avatars/avatar-23.png" class="rounded-circle" width="46" height="46" alt="">
									</div>
									<div class="list-inline d-flex customers-contacts ms-auto">	
										<a href="javascript:;" class="list-inline-item bg-danger"><i class="bx bxs-trash"></i></a>
										<a href="javascript:;" class="list-inline-item" onclick="nuevofacial()"><i class="bx bx-plus"></i></a>
										<a href="javascript:;" class="list-inline-item bg-primary"><i class="bx bx-save"></i></a>
									</div>

									</div>
								</div>
								
							
						</div>
					</div>
                    		
                    	</div>
                       

                                    

                                       

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<div class="modal" id="nuevaTarjeta" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Nueva tarjeta</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-12">
                        <b>Numero de tarjeta</b>
                        <input type="text" name="txt_CardNumero" id="txt_CardNumero" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="addTarjetaBio()">Enviar al biometrico</button>  
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal" id="nuevahuella" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Nueva Huella</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <div class="col">
                            <div class="btn-group" role="group" aria-label="First group">
                                <button type="button" id="btn_finger_1" class="btn btn-sm btn-outline-primary active" onclick="cambiar(1)">Dedo 1</button>
                                <button type="button" id="btn_finger_2" class="btn btn-sm btn-outline-primary " onclick="cambiar(2)">Dedo 2</button>
                                <button type="button" id="btn_finger_3" class="btn btn-sm btn-outline-primary " onclick="cambiar(3)">Dedo 3</button>
                                <button type="button" id="btn_finger_4" class="btn btn-sm btn-outline-primary " onclick="cambiar(4)">Dedo 4</button>
                                <button type="button" id="btn_finger_5" class="btn btn-sm btn-outline-primary " onclick="cambiar(5)">Dedo 5</button>
                                <input type="hidden" name="txt_dedo_num" value="1" id="txt_dedo_num">
                            </div>
                        </div>                                                   
                    </div>
                   
                    <div class="col-sm-12">                                                   
                        <img id="img_palma" src="../img/de_sistema/palma1.gif" style="width:50%">
                    </div>
                     <div class="col-sm-12">
                        <div class="row">
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="leerDedo()">Iniciar lectura</button>
                                <span id="file-name_bio"></span>   
                            </div>
                            <div class="col-6  text-start">
                                <label for="file_huella" class="btn btn-outline-dark btn-sm">Seleccionar Huella</label>
                                <input id="file_huella" type="file"/><br>
                                <span id="file-name"></span>     
                            </div>
                            
                        </div>   	
                    </div>
                </div>
                   
                
                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="addHuellaBio()">Enviar al biometrico</button>  
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal" id="nuevofacial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Nueva facial</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-sm-4">                                                   
                        <img id="img_palma" src="../img/de_sistema/palma1.gif" style="width:50%">
                    </div>
                     <div class="col-sm-8">
                     	<div class="row">
                     		<div class="col-12">
                     			<button type="button" class="btn btn-sm btn-primary">Capturar</button> 
                     			<button type="button" class="btn btn-sm btn-primary">Buscar</button>
                     		</div> 
                     		<div class="">
                     			
                     		</div>                    		
                     	</div>
                                                                       
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="leerDedo()">Enviar al biometrico</button>  
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- 

<div class="modal" id="sync_biometrico" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h3>Syncronizar a biometrico</h3>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">
                        <b>Biometrico a syncronizar</b>
                        <select class="form-select" id="ddl_dispositivosSync"></select>
                    </div>
                </div>
                   
                
                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="syncronizarPersonaBio()"><i class="bx bx-sync"></i> Enviar a biometrico</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> -->


<script>
  document.getElementById("file_huella").addEventListener("change", function () {
    const fileNameSpan = document.getElementById("file-name");
    const file = this.files[0];
    fileNameSpan.textContent = file ? file.name : "Ningún archivo seleccionado";
  });
</script>


<style>
  .custom-file-upload {
    display: inline-block;
    padding: 8px 15px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 14px;
    border: none;
    transition: background-color 0.3s ease;
  }

  .custom-file-upload:hover {
    background-color: #0056b3;
  }

  input[type="file"] {
    display: none;
  }

  #file-name {
    margin-left: 10px;
    font-style: italic;
  }
</style>
