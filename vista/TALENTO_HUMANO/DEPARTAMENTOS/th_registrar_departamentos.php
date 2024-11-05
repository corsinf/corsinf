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
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
            cargar_personas_departamentos();
        <?php } ?>


        /**
         * 
         * Datatable
         */

        //Para seleccionar a cada persona
        $('#tbl_personas tbody').on('change', '.cbx_dep_per', function() {
            var id = $(this).val();

            if (this.checked) {
                if (!personas_seleccionadas.includes(id)) {
                    personas_seleccionadas.push(id);
                }
            } else {
                // Eliminar el ID si el checkbox no está seleccionado
                personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
            }

            console.log('Seleccionados:', personas_seleccionadas);
        });

        // Evento para detectar cuando se cambia de página
        $('#tbl_personas').on('page.dt', function() {
            // Aquí colocas la acción que quieres realizar al cambiar de página
            $('#cbx_per_dep_all').prop('checked', false);
            console.log('Página cambiada');
        });

    });

    /**
     * 
     * Departamentos
     * 
     */

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_nombre').val(response[0].nombre);
            }
        });
    }

    function editar_insertar() {
        var txt_nombre = $('#txt_nombre').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': txt_nombre,
        };

        if ($("#form_departamento").valid()) {
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
            url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del departamento ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del departamento ya está en uso.');
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
            url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos';
                    });
                }
            }
        });
    }

    /**
     * 
     * Acerca de la relacion personas_departamentos en vista
     * 
     */

    function cargar_personas_departamentos() {
        tbl_departamento_personas = $('#tbl_departamento_personas').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    return {
                        id: '<?= $_id ?>',
                    };
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        nombres_completos = item.primer_apellido + ' ' + item.segundo_apellido + ' ' + item.primer_nombre + ' ' + item.segundo_nombre;
                        return `<a href="#"><u>${nombres_completos}</u></a>`;
                    }
                },
                {
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-danger btn-xs" onclick="delete_datos_personas_departamentos('${item._id}')"><i class="bx bx-trash fs-7 me-0 fw-bold"></i></button>`;
                    }
                }
            ],
            order: [
                [1, 'asc']
            ],
        });
    }

    function delete_datos_personas_departamentos(id) {
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
                eliminar_personas_departamentos(id);
            }
        })
    }

    function eliminar_personas_departamentos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        tbl_departamento_personas.ajax.reload();
                        if ($.fn.DataTable.isDataTable('#tbl_personas')) {
                            tbl_personas.ajax.reload();
                        }
                    });
                }
            }
        });
    }

    /**
     * 
     * Acerca de la relacion personas_departamentos en modal
     * 
     */

    let personas_seleccionadas = []; //Array de personas seleccionadas
    function cargar_personas() {

        tbl_personas = $('#tbl_personas').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?listar_personas_modal=true',
                type: 'POST',
                data: function(d) {
                    return {
                        id: '<?= $_id ?>',
                    };
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return `<a href="#"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'cedula'
                },

                {
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<div class="form-check">
                                <input class="form-check-input cbx_dep_per" type="checkbox" value="${item._id}" name="cbx_dep_per_${item._id}" id="cbx_dep_per_${item._id}">
                                <label class="form-label" for="cbx_dep_per_${item._id}">Seleccionar</label>
                            </div>`;
                    },
                    orderable: false
                }
            ],
            order: [
                [1, 'asc']
            ],

        });

    }

    // Función para marcar/desmarcar todos los cbx_per_dep_all
    function marcar_cbx_modal_departamentos_personas(source) {
        var cbx_per_dep_all = document.querySelectorAll('.cbx_dep_per');

        cbx_per_dep_all.forEach(function(cbx) {
            cbx.checked = source.checked; // Marca o desmarca todos
            var id = cbx.value;

            // Actualiza el array de personas seleccionadas
            if (source.checked) {
                if (!personas_seleccionadas.includes(id)) {
                    personas_seleccionadas.push(id);
                }
            } else {
                personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
            }
        });

        console.log('Seleccionados:', personas_seleccionadas);
    }

    function insertar_editar_personas_departamentos() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'personas_seleccionadas': personas_seleccionadas,
            'txt_visitor': ''
        };

        insertar_personas_departamentos(parametros);

        //console.log(parametros);
    }

    function insertar_personas_departamentos(parametros) {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        $('#modal_personas').modal('hide');
                        tbl_departamento_personas.ajax.reload();
                        tbl_personas.ajax.reload();
                        personas_seleccionadas = [];
                        $('#cbx_per_dep_all').prop('checked', false);
                        Swal.close();
                    });

                } else if (response == -2 || response == null) {
                    Swal.fire('', 'Seleccione personas', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function abrir_modal_personas() {
        $('#modal_personas').modal('show');
        // Solo llama a cargar_personas si la tabla no ha sido inicializada
        if (!$.fn.DataTable.isDataTable('#tbl_personas')) {
            cargar_personas();
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departamentos</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Departamento
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
                                    echo 'Registrar Departamento';
                                } else {
                                    echo 'Modificar Departamento';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>

                                <?php if (isset($_GET['_id'])) { ?>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Personas</div>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>

                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="successhome" role="tabpanel">

                                    <form id="form_departamento">

                                        <div class="row pt-3 mb-col">
                                            <div class="col-md-12">
                                                <label for="txt_nombre" class="form-label">Nombre </label>
                                                <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                                <span id="error_txt_nombre" class="text-danger"></span>
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

                                <div class="tab-pane fade" id="successprofile" role="tabpanel">

                                    <div class="row pt-3">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <button type="button" class="btn btn-success btn-sm" onclick="abrir_modal_personas();"><i class="bx bx-plus"></i> Agregar Personas</button>
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
                                                        <th>Acción</th>
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

<!-- Modal para abrir una tabla con las personas registradas -->
<div class="modal" id="modal_personas" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <!-- <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div> -->

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row pt-3">

                    <div class="table-responsive">
                        <table class="table table-striped responsive" id="tbl_personas" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>
                                        <div class="form-check" style="display: block;">
                                            <input class="form-check-input" type="checkbox" id="cbx_per_dep_all" onchange="marcar_cbx_modal_departamentos_personas(this)">
                                            <label class="form-check-label" for="cbx_per_dep_all">
                                                Todo
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn btn-success btn-sm px-4 m-1" onclick="insertar_editar_personas_departamentos();" type="button"><i class="bx bx-plus me-0"></i> Agregar</button>
                    <button class="btn btn-secondary btn-sm px-4 m-1" data-bs-dismiss="modal" type="button"><b>X </b> Cancelar</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label
        agregar_asterisco_campo_obligatorio('txt_nombre');

        $("#form_departamento").validate({
            rules: {
                txt_nombre: {
                    required: true,
                },
            },
            messages: {
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
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