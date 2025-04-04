<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_dispositivos = $('#tbl_dispositivos').DataTable($.extend({}, configuracion_datatable('Dispostivos', 'dispostivos'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
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
                    data: 'modelo'
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-danger btn-xs" onclick="eliminar_device(${item._id})">
                                    <i class="bx bx-trash fs-7 me-0 fw-bold"></i>
                                </button>
                            <button type="button" class="btn btn-primary btn-xs" onclick=""><i class="lni lni-spinner-arrow fs-7 me-0 fw-bold"></i></button>`;
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
                url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?BuscarDevice=true&brodcast='+$('#txt_brodcast').val()+'&brodcast_port='+$('#txt_brodcast_port').val(),
                dataSrc: ''
            },
            columns: [
                { 
                    data: null,
                    render: function(data, type, item) {
                        butons = `<button type="button" class="btn btn-primary btn-xs" title="Guardar Dispositivo" onclick="registrar_device('${item.IPv4Gateway}','${item.CommandPort}','${item.MAC}')">
                                    <i class="bx bx-save fs-7 me-0 fw-bold"></i>
                            </button>`;

                        return butons;
                    }
                },
                { data: 'N' },                   
                { data: 'DeviceDescription' }, // Tipo dispositivo
                // { data: 'Tipo' }, // Estado
                { data: 'IPv4Gateway' }, // IPV4
                { data: 'CommandPort' }, // Puerto
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
                    <div class="col-md-9">
                        <b>Brodcast</b>
                        <input type="text" class="form-control form-control-sm" name="txt_brodcast" id="txt_brodcast">
                    </div>
                     <div class="col-md-9">
                        <b>Puerto</b>
                        <input type="text" class="form-control form-control-sm" name="txt_brodcast_port" id="txt_brodcast_port">
                    </div>
                    <div class="col-3">
                        <button class="btn btn-primary btn-sm" type="button" onclick="inicializarTablaDispositivos()"><i class="bx bx-search"></i>Buscar</button>                        
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

