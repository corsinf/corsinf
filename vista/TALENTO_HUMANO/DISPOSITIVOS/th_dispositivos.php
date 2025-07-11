<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    let intervaloID;
    $(document).ready(function() {
        tbl_dispositivos = $('#tbl_dispositivos').DataTable($.extend({}, configuracion_datatable('Dispostivos', 'dispostivos'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listarAll=true',
                dataSrc: '',
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_dispositivos&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'host'
                },
                {
                    data: 'port'
                },
                {
                    data: 'usuario'
                },

                {
                    data: 'modelo'
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-danger btn-xs" onclick="eliminar_device(${item._id})">
                                    <i class="bx bx-trash fs-7 me-0 fw-bold"></i>
                                </button>
                            <button type="button" class="btn btn-primary btn-xs" onclick="modal_data('${item._id}','${item.nombre}')"><i class="lni lni-database fs-7 me-0 fw-bold"></i></button>`;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ],
        }));

          $("#form_dispositivo").validate({
            rules: {
              
                txt_nombre: {
                    required: true,
                },
                txt_pass: {
                    required : true
                },
                txt_usuario: {
                    required : true
                }
            },
            messages: {                
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
                },
                txt_pass: {
                    required: "El campo 'Contraseña' es obligatorio",
                },
                txt_usuario: {
                    digits: "El campo 'Usuario' permite solo números",
                }
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });

    });

    function inicializarTablaDispositivos() 
    {
         var tipo = $('input[name="rbl_tipoBusqueda"]:checked').val();
         if(tipo == 1 && $('#txt_vlans').val()=='')
         {
            Swal.fire("Ingrese todos los datos","","info");
            return false;
         }

        $('#myModal_espera').modal('show');
        // Verificar si ya existe una instancia de DataTable para destruirla antes de crear una nueva
        if ($.fn.DataTable.isDataTable('#tbl_dispositivos_red')) {
            $('#tbl_dispositivos_red').DataTable().destroy();
        }
        $('#tbl_dispositivos_red').DataTable($.extend({},{
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?BuscarDevice=true&vlans='+$('#txt_vlans').val()+'&tipoBusqueda='+$('input[name="rbl_tipoBusqueda"]:checked').val(),
                dataSrc: ''
            },
            columns: [
                { 
                    data: null,
                    render: function(data, type, item) {
                        butons = `<button type="button" class="btn btn-primary btn-xs" title="Guardar Dispositivo" onclick="registrar_device('${item.ipv4}','${item.puerto}','${item.MAC}')">
                                    <i class="bx bx-save fs-7 me-0 fw-bold"></i>
                            </button>`;

                        return butons;
                    }
                },
                { data: 'item' },                   
                { data: 'tipo' }, // Tipo dispositivo
                // { data: 'Tipo' }, // Estado
                { data: 'ipv4' }, // IPV4
                { data: 'puerto' }, // Puerto
                // { data: 'CommandPort' }, // Serial
                { data: 'MAC' }, // MAC Address
                
            ],
            order: [
                [1, 'asc']
            ],
        })).on('xhr', function (e, settings, json, xhr) {
        // Esta función se ejecuta cuando los datos se cargan correctamente
            if (json.length > 0) {
                $('#tbl_dispositivos_red').DataTable().columns.adjust().draw();
                $('#myModal_espera').modal('hide'); // Asegúrate de que #miModal sea el ID del modal
            } else {
                $('#tbl_dispositivos_red').DataTable().columns.adjust().draw();
                $('#myModal_espera').modal('hide');
            }
        });    

        // $('#myModal_espera').modal('hide');
    }

    function abrir_modal()
    {
        $('#lbl_msj_espera').text("Buscando Dispositivos en red");
        $('#detectar_device').modal('show'); 
    }

    function registrar_device(ip,port,serial)
    {        
        $('#detectar_device').modal('hide');
        $('#txt_host').val(ip);
        $('#txt_puerto').val(port);
        $('#txt_serial').val(serial);
        $('#registrar_dispositivo').modal('show');
    }

    function cancelar_registro()
    {
        $('#detectar_device').modal('show');
        $('#txt_host').val("");
        $('#txt_puerto').val("");
        $('#txt_serial').val("");
        $('#txt_nombre').val("");
        $('#txt_usuario').val("");
        $('#txt_pass').val("");
        $('#registrar_dispositivo').modal('hide');
    }

     function editar_insertar() {
        var ddl_modelo = 1; //$('#ddl_modelo').val();
        var txt_nombre = $('#txt_nombre').val();
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_serial = $('#txt_serial').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        var cbx_ssl = $('#cbx_ssl').prop('checked') ? 1 : 0;

        var parametros = {
            '_id': '',
            'ddl_modelo': ddl_modelo,
            'txt_nombre': txt_nombre,
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_serial': txt_serial,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
            'cbx_ssl': cbx_ssl,
        };

        if ($("#form_dispositivo").valid()) {
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
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_dispositivos';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'El dispositivo con esa IP ya esta registrado', 'warning');
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });
    }
    function probar_conexion()
    {
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        if(txt_pass=='' || txt_usuario=='' || txt_host=='')
        {
            Swal.fire("Ingrese todos los datos","","info")
            return false;
        }

        var parametros = {
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
        };

        $('#myModal_espera').modal('show');
        $('#lbl_msj_espera').text("Probando conexion");
         $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?ProbarConexion=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                $('#myModal_espera').modal('hide');
                if (response.resp == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                } else{
                    Swal.fire('No se pudo conectar', response.msj, 'error')
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }
        });


    }

    function eliminar_device(id)
    {
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
                eliminar_dispositivo(id);            
            }
        });
    }

    function eliminar_dispositivo(id)
    {
         $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?eliminar2=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.reload()
                    });
                } else {
                    Swal.fire('', 'No se pudo eliminar el dispositivo', 'warning');
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });
    }

    function vlas_search()
    {
        var tipo = $('input[name="rbl_tipoBusqueda"]:checked').val();
        console.log(tipo);
        if(tipo==1)
        {
            $('#pnl_vlan_especifico').removeClass('d-none');

        }else
        {
            $('#pnl_vlan_especifico').addClass('d-none');
        }
    }

    function modal_data(id,nombre)
    {
        $('#txt_id_dispositivo').val(id)
        $('#txt_nombre_bio').val(nombre)
        $('#modal_data_biometrico').modal('show');
    }

    function importar_data()
    {
        var card = 0 ,face = 0,finger = 0;
        if($('#cbx_nom_card').prop('checked')){ card = 1}
        if($('#cbx_finger').prop('checked')){ finger = 1}
        if($('#cbx_face').prop('checked')){ face = 1}

        var parametros = 
        {
            'dispositivos': $('#txt_id_dispositivo').val(),
            'nombreCard': card,
            'huellas':finger,
            'facial':face,
        }
        $('#myModal_espera').modal('show');
        $.ajax({
            data: {parametros:parametros },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?importar_datos=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                descargar_datos();              
            },
            
            error: function(xhr, status, error) {

                $('#myModal_espera').modal('hide');

                Swal.fire('', 'Error existio un error', 'error');
                // console.log('Status: ' + status); 
                // console.log('Error: ' + error); 
                // console.log('XHR Response: ' + xhr.responseText); 

                // Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });

    }

    function descargar_datos()
    {
        var count = 0;
        var texto = ["Descarga iniciada..",'Obteniendo de biometrico','Descargando Huellas','Descargando Faciales','Empaquetando usuario'];
       intervaloID =  setInterval(() => {
            descargar_zip()
            $('#lbl_msj_espera').text(texto[count]);
            count++;
            if(count>4){count=0;}
          }, 10000); // Cada 3 segundos

    }

    function descargar_zip()
    {  
        var parametros = 
        {
            'nombre':$('#txt_nombre_bio').val(),
        }
        $('#myModal_espera').modal('show');
        $.ajax({
            data: {parametros:parametros },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?descargar_zip=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if(response.resp==1)
                {
                    $('#myModal_espera').modal('hide');
                    if (intervaloID) {
                      clearInterval(intervaloID);
                      intervaloID = null;
                    }

                     Swal.fire('Datos Importados',"", 'success').then(function() {
                        const link = document.createElement("a");
                        link.href = response.link; // Ruta al archivo .zip
                        link.download = response.nombre;       // Nombre sugerido para guardar
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                    });
                }

            },
            
            error: function(xhr, status, error) {

                $('#myModal_espera').modal('hide');
                Swal.fire('', 'Error existio un error', 'error');
            }
        });

    }



</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dispositivos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Dispositivos
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
                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_dispositivos"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm"   onclick="abrir_modal()">
                                            <i class="bx bx-broadcast me-0 pb-1"></i> Detectar dispositivos
                                        </button>
                                       
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_dispositivos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Host</th>
                                                <th>Puerto</th>
                                                <th>Usuario</th>
                                                <th>Modelo</th>
                                                <th width="10px">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<div class="modal fade" id="detectar_device" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dispositivos en red</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="me-2" onclick="vlas_search()"><input type="radio" name="rbl_tipoBusqueda" id="rbl_default" checked value="0"> Por default</label>
                        <label class="me-2" onclick="vlas_search()"><input type="radio" name="rbl_tipoBusqueda" id="rbl_vlan" value="1"> Vlan Especifica</label>
                    </div>            
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary btn-sm" type="button" onclick="inicializarTablaDispositivos()"><i class="bx bx-search"></i>Buscar</button>                        
                    </div>
                     <div class="col-md-6 d-none" id="pnl_vlan_especifico">
                        <b>vlan Especifica</b>
                        <input type="text" class="form-control form-control-sm" name="txt_vlans" id="txt_vlans" placeholder="192.168.1">
                        <span style="color:red;font-size:10px">* Si es mas de una vlan separar con coma (,)</span>
                    </div>  
                </div>
               <div class="row" style="overflow-x: scroll;">
                   <div class="col-sm-12">
                            <table class="table table-striped" id="tbl_dispositivos_red">
                                <thead>
                                    <tr>                                
                                        <th width="10px">Acción</th>
                                        <th>No.</th>
                                        <th>Tipo dispositivo</th>
                                        <!-- <th>Estado</th> -->
                                        <th>IPV4</th>
                                        <th>Puerto</th>
                                        <!-- <th>Serie</th> -->
                                        <th>MAC</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="registrar_dispositivo" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2>Cambio de clave</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                    <form id="form_dispositivo">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-8">
                                    <label for="txt_nombre" class="form-label">Nombre <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_host" class="form-label">IP/Host </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_host" name="txt_host" maxlength="50" oninput="texto_minusculas(this);" readonly>
                                </div>

                                <div class="col-md-2 ">
                                    <label for="txt_puerto" class="form-label">Puerto </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_puerto" name="txt_puerto" maxlength="4" readonly>
                                </div>

                                <div class="col-md-6 ">
                                    <label for="txt_serial" class="form-label">Número de Serie </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_serial" name="txt_serial" maxlength="100" readonly>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_usuario" class="form-label">Usuario <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_usuario" name="txt_usuario" maxlength="50">
                                    <span id="error_txt_usuario" class="text-danger"></span>
                                </div>

                                <div class="col-md-8 ">
                                    <label for="txt_pass" class="form-label">Contraseña <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm" id="txt_pass" name="txt_pass" maxlength="50">                                    
                                    <span id="error_txt_pass" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="cbx_ssl" id="cbx_ssl">
                                        <label class="form-label" for="cbx_ssl">SSL </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm px-4" onclick="probar_conexion()" type="button"><i class="lni lni-play fs-6 me-0"></i> Probar conexión</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">

                            <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="cancelar_registro()">Cerrar</button>
                        </div>


                    </form>

            </div>
        </div>
    </div>
</div>


<div class="modal" id="modal_data_biometrico" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2>Importar Informacion</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="txt_id_dispositivo" id="txt_id_dispositivo">
                        <label><input type="checkbox" name="cbx_nom_card" id="cbx_nom_card" checked disabled>Nombre y No Tarjeta</label><br>
                        <label><input type="checkbox" name="cbx_finger" id="cbx_finger">Huellas Digitales</label><br>
                        <label><input type="checkbox" name="cbx_face" id="cbx_face">Imagen Facial</label><br>
                        <input type="hidden" name="txt_nombre_bio" id="txt_nombre_bio">
                    </div>
                </div>  
            </div>
            <div class="modal-footer">

                <button class="btn btn-primary btn-sm px-4 m-0" onclick="importar_data()" type="button"><i class="bx bx-save"></i> Guardar</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
                
            </div>
        </div>
    </div>
</div>

