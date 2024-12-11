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
        dispositivos();
        cargar_tabla()
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
        $('#myModal_espera').modal('show');
        var parametros = 
        {
            'iddispostivos':$('#ddl_dispositivos').val(),
            'Idusuario':<?php echo $_id ; ?>,
            'dedo':$('#txt_dedo_num').val(),
            'usuario':$('#txt_primer_apellido').val()+' '+$('#txt_segundo_apellido').val()+' '+$('#txt_primer_nombre').val()+' '+$('#txt_segundo_nombre').val(),
            'CardNo':$('#txt_CardNumero').val(),
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?CapturarFinger=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
                $('#myModal_espera').modal('hide');
                if(response.resp==1)
                {
                    Swal.fire("Huella dactilar Guardada",response.patch,"success");
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


    function cargar_tabla()
    {
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
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?registros_biometria=true',
                data: function (d) {
                   
                     var parametros = {
                      id:'<?php echo $_id; ?>', // Parámetro personalizado
                  };
                  return { parametros: parametros };
                },
                dataSrc: ''
            },
            columns: [
                    {data: 'detalle' },                   
                    { data: null,
                        render: function(data, type, item) {
                        return `<button type="button" class="btn btn-danger btn-xs" onclick="eliminarfinger('${item.id}')"><i class="bx bx-trash fs-7 me-0 fw-bold"></i></button>`;
                    }
                },        
               
            ],
            order: [
                [1, 'asc']
            ],
        }));
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
                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Persona';
                                } else {
                                    echo 'Modificar Persona';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#datos" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tarjetas" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-credit-card font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Biometría</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#departamentos" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bxs-school font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Departamentos</div>
                                        </div>
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="datos" role="tabpanel">

                                    <form id="form_persona">

                                        <div class="accordion" id="acordeon_persona">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button bg-primary bg-gradient  text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <i class='bx bx-user-pin font-18 me-1'></i> Datos Generales
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                                    <div class="accordion-body">
                                                        <div>
                                                            <div class="row pt-3 mb-col">
                                                                <div class="col-md-3">
                                                                    <label for="txt_primer_apellido" class="form-label">Primer Apellido </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_apellido" id="txt_primer_apellido"  maxlength="30">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_segundo_apellido" class="form-label">Segundo Apellido </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_segundo_apellido" name="txt_segundo_apellido" maxlength="30">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_primer_nombre" class="form-label">Primer Nombre </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_primer_nombre" name="txt_primer_nombre" maxlength="30">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_segundo_nombre" class="form-label">Segundo Nombre </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_segundo_nombre" name="txt_segundo_nombre" maxlength="30">
                                                                </div>
                                                            </div>

                                                            <div class="row mb-col">
                                                                <div class="col-md-3">
                                                                    <label for="txt_cedula" class="form-label">Cédula de Identidad </label>
                                                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_cedula" name="txt_cedula" maxlength="15">
                                                                    <span id="error_txt_cedula" class="text-danger"></span>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="ddl_sexo" class="form-label">Sexo </label>
                                                                    <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                                                        <option selected disabled>-- Seleccione --</option>
                                                                        <option value="Femenino">Femenino</option>
                                                                        <option value="Masculino">Masculino</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_fecha_nacimiento" class="form-label">Fecha de Nacimiento </label>
                                                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value);">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_edad" class="form-label">Edad </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_edad" name="txt_edad" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-col">
                                                                <div class="col-md-6">
                                                                    <label for="txt_correo" class="form-label">Correo </label>
                                                                    <input type="email" class="form-control form-control-sm" id="txt_correo" name="txt_correo" maxlength="100">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_telefono_1" class="form-label">Teléfono 1 </label>
                                                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_telefono_1" name="txt_telefono_1" maxlength="15">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_telefono_2" class="form-label">Teléfono 2 </label>
                                                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_telefono_2" name="txt_telefono_2" maxlength="15">
                                                                </div>


                                                            </div>

                                                            <div class="row mb-col">
                                                                <div class="col-auto">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="cbx_admin" name="cbx_admin">
                                                                        <label class="form-label" for="cbx_admin">Administrador </label>
                                                                    </div>
                                                                    <label class="error" style="display: none;" for="cbx_admin"></label>
                                                                </div>

                                                                <div class="col-auto">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="cbx_habilitado" name="cbx_habilitado">
                                                                        <label class="form-label" for="cbx_habilitado">Habilitado </label>
                                                                    </div>
                                                                    <label class="error" style="display: none;" for="cbx_habilitado"></label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button bg-primary bg-gradient text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        <i class='bx bx-user-pin font-18 me-1'></i> Datos Adicionales
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                                                    <div class="accordion-body">
                                                        <div>
                                                            <div class="row pt-3 mb-col">
                                                                <div class="col-md-3">
                                                                    <label for="ddl_estado_civil" class="form-label">Estado Civíl </label>
                                                                    <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil">
                                                                        <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                                                                        <option value="Soltero">Soltero/a</option>
                                                                        <option value="Casado">Casado/a</option>
                                                                        <option value="Divorciado">Divorciado/a</option>
                                                                        <option value="Viudo">Viudo/a</option>
                                                                        <option value="Union">Unión de hecho</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="txt_postal" class="form-label">Cod. Postal </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_postal" name="txt_postal" maxlength="20">
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="txt_direccion" class="form-label">Dirección </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_direccion" name="txt_direccion" maxlength="400">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <h6 class="fw-bold"><i class='bx bx-credit-card font-18 me-0'></i> Autorización</h6>

                                                        <div>
                                                            <div class="row pt-3 mb-col">
                                                                <div class="col-md-3">
                                                                    <label for="txt_fecha_aut_inicio" class="form-label">Fecha de Inicio </label>
                                                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_aut_inicio" name="txt_fecha_aut_inicio">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="txt_fecha_aut_limite" class="form-label">Fecha de finalización </label>
                                                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_aut_limite" name="txt_fecha_aut_limite">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <br>
                                                        <h6 class="fw-bold"><i class='bx bx-credit-card font-18 me-0'></i> Empresa</h6>

                                                        <div>
                                                            <div class="row pt-3 mb-col">
                                                                <div class="col-md-3">
                                                                    <label for="txt_fecha_admision" class="form-label">Fecha de Admisión </label>
                                                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_admision" name="txt_fecha_admision">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="txt_cargo" class="form-label">Cargo </label>
                                                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_cargo" name="txt_cargo" maxlength="100">
                                                                </div>
                                                            </div>

                                                            <div class="row mb-col">
                                                                <div class="col-md-12">
                                                                    <label for="txt_observaciones" class="form-label">Observaciones </label>
                                                                    <textarea class="form-control form-control-sm no_caracteres" name="txt_observaciones" id="txt_observaciones" rows="3" maxlength="200"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-2">

                                            <?php if ($_id == '') { ?>
                                                <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                                <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Editar</button>
                                                <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                            <?php } ?>
                                        </div>
                                    </form>

                                </div>

                                <div class="tab-pane fade" id="tarjetas" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <b>Numero de tarjeta</b>
                                            <input type="text" name="txt_CardNumero" id="txt_CardNumero" class="form-control form-control-sm">
                                            <b>Registro de facial</b>
                                            <input type="text" name="" id="" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-sm-6">
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
                                                <div class="col-sm-4">
                                                     <select class="form-select" id="ddl_dispositivos" name="ddl_dispositivos">
                                                        <option value="" >Seleccione Dispositivo</option>
                                                    </select>                                                                    
                                                </div>
                                                 <div class="col-2 text-end">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="leerDedo()">Iniciar Lectura</button>     
                                                </div>
                                           
                                            <div class="row text-center">
                                                <div class="col-sm-6">                                                   
                                                    <img id="img_palma" src="../img/de_sistema/palma1.gif" style="width:100%">
                                                </div>
                                                <div class="col-sm-6">
                                                    <table class="table table-hover" id="tbl_bio_finger" style="width:100%">
                                                        <thead>
                                                            <th>Numero de Dedo</th>
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
                                       
                                    </div>
                                   
<!-- 
                                    <div class="row pt-4">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tbl_departamento_personas" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Cédula</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Teléfono</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div> -->

                                </div>

                                <div class="tab-pane fade" id="departamentos" role="tabpanel">

                                    <div class="row pt-3">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <button type="button" class="btn btn-success btn-sm" onclick="abrir_modal_personas();"><i class="bx bx-plus"></i> Agregar Departamentos</button>
                                        </div>
                                    </div>

                                    <div class="row pt-4">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tbl_departamento_personas" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Cédula</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Teléfono</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
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

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        agregar_asterisco_campo_obligatorio('txt_segundo_nombre');

        $("#form_persona").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    required: true,
                },
                txt_cedula: {
                    required: true,
                    minlength: 10,
                },
                ddl_sexo: {
                    required: true,
                },
                txt_fecha_nacimiento: {
                    required: true,
                },
                txt_correo: {
                    required: true,
                },
                txt_telefono_1: {
                    required: true,
                    minlength: 10,
                    digits: true
                },
                txt_telefono_2: {
                    //required: true,
                },
                cbx_admin: {
                    //required: true,
                },
                cbx_habilitado: {
                    //required: true,
                },
                ddl_estado_civil: {
                    //required: true,
                },
                txt_postal: {
                    //required: true,
                },
                txt_direccion: {
                    //required: true,
                },
                txt_fecha_aut_inicio: {
                    //required: true,
                },
                txt_fecha_aut_limite: {
                    //required: true,
                },
                txt_fecha_admision: {
                    //required: true,
                },
                txt_cargo: {
                    //required: true,
                },
                txt_observaciones: {
                    //required: true,
                },
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
</script>