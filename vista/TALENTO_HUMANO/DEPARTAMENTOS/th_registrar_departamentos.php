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

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successDepartament" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Jerarquia Departamento</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successPostulacion" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Postulación de Trabajo</div>
                                        </div>
                                    </a>
                                </li>
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

                                <div class="tab-pane fade" id="successDepartament" role="tabpanel">

                                    <div class="container-fluid py-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <h1 class="text-center mb-4 text-primary">
                                                    <i class="fas fa-sitemap me-3"></i>
                                                    Organigrama Empresarial
                                                </h1>

                                                <div class="text-center mb-4">
                                                    <button class="btn btn-primary" onclick="toggleExpandirTodo()">
                                                        <i class="fas fa-expand-arrows-alt me-2"></i>
                                                        <span id="btnText">Expandir Todo</span>
                                                    </button>
                                                </div>

                                                <div id="organigramaContent"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="successPostulacion" role="tabpanel">
                                    <h1 class="text-center mb-4 text-primary">
                                        Postular un puesto de trabajo
                                    </h1>
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
    // Array estático jerárquico
    const organigramaData = {
        id: 'jefe',
        nombre: "María González",
        cargo: "Jefe de Departamento",
        foto: "https://images.unsplash.com/photo-1494790108755-2616b612b3e5?w=150&h=150&fit=crop&crop=face",
        esJefe: true,
        subordinados: [{
                id: 'ing_redes',
                nombre: "Carlos Rodríguez",
                cargo: "Ingeniero en Redes",
                foto: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face",
                subordinados: [{
                        id: 'tec_redes_1',
                        nombre: "Luis Herrera",
                        cargo: "Técnico en Redes Sr.",
                        foto: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face",
                        subordinados: [{
                            id: 'asist_redes_1',
                            nombre: "Miguel Santos",
                            cargo: "Asistente de Redes",
                            foto: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=150&h=150&fit=crop&crop=face"
                        }]
                    },
                    {
                        id: 'tec_redes_2',
                        nombre: "Patricia Silva",
                        cargo: "Técnica en Seguridad",
                        foto: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&h=150&fit=crop&crop=face"
                    }
                ]
            },
            {
                id: 'ing_software',
                nombre: "Ana López",
                cargo: "Ingeniera en Software",
                foto: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face",
                subordinados: [{
                        id: 'dev_frontend',
                        nombre: "Roberto Vega",
                        cargo: "Desarrollador Frontend",
                        foto: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=150&h=150&fit=crop&crop=face",
                        subordinados: [{
                            id: 'junior_frontend',
                            nombre: "Carmen Jiménez",
                            cargo: "Desarrolladora Junior",
                            foto: "https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=150&h=150&fit=crop&crop=face"
                        }]
                    },
                    {
                        id: 'dev_backend',
                        nombre: "Diego Morales",
                        cargo: "Desarrollador Backend",
                        foto: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face"
                    }
                ]
            },
            {
                id: 'ing_devops',
                nombre: "Andrea Torres",
                cargo: "Ingeniera DevOps",
                foto: "https://images.unsplash.com/photo-1494790108755-2616b612b3e5?w=150&h=150&fit=crop&crop=face",
                subordinados: [{
                    id: 'tec_sistemas',
                    nombre: "Fernando Castro",
                    cargo: "Técnico de Sistemas",
                    foto: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face"
                }]
            }
        ]
    };

    // Función para obtener colores por departamento (índice del departamento)
    function obtenerColoresDepartamento(indiceDepartamento) {
        const colores = [{
                cardClass: 'border-success bg-light text-dark',
                textClass: 'text-success',
                textSecondaryClass: 'text-muted',
                iconClass: 'text-success',
                borderColor: 'border-success'
            },
            {
                cardClass: 'border-info bg-light text-dark',
                textClass: 'text-info',
                textSecondaryClass: 'text-muted',
                iconClass: 'text-info',
                borderColor: 'border-info'
            },
            {
                cardClass: 'border-warning bg-light text-dark',
                textClass: 'text-warning',
                textSecondaryClass: 'text-muted',
                iconClass: 'text-warning',
                borderColor: 'border-warning'
            },
            {
                cardClass: 'border-danger bg-light text-dark',
                textClass: 'text-danger',
                textSecondaryClass: 'text-muted',
                iconClass: 'text-danger',
                borderColor: 'border-danger'
            },
            {
                cardClass: 'border-purple bg-light text-dark',
                textClass: 'text-purple',
                textSecondaryClass: 'text-muted',
                iconClass: 'text-purple',
                borderColor: 'border-purple'
            }
        ];

        return colores[indiceDepartamento % colores.length];
    }

    // Función para crear tarjeta de empleado con Bootstrap
    function crearTarjetaEmpleado(empleado, nivel = 0, indiceDepartamento = 0) {
        const tieneSubordinados = empleado.subordinados && empleado.subordinados.length > 0;
        const esJefe = empleado.esJefe;

        // Determinar el tamaño de la card según el nivel
        const cardSize = esJefe ? 'col-12 col-md-6 col-lg-4' : 'col-12 col-sm-6 col-md-4 col-lg-3';

        const equipoInfo = tieneSubordinados ? `
                <small class="text-muted">
                    <i class="fas fa-users me-1"></i>
                    Equipo: ${contarSubordinados(empleado)} personas
                </small>
            ` : '';

        const clickable = tieneSubordinados ? `data-bs-toggle="collapse" data-bs-target="#collapse-${empleado.id}" aria-expanded="false" aria-controls="collapse-${empleado.id}" role="button"` : '';

        // Determinar colores según nivel
        let cardClass, textClass, textSecondaryClass, iconClass;

        if (esJefe) {
            // Jefe principal
            cardClass = 'border-primary bg-primary text-white';
            textClass = 'text-white';
            textSecondaryClass = 'text-white-50';
            iconClass = 'text-white';
        } else if (nivel >= 2) {
            // A partir de la tercera capa (nivel 2), aplicar colores por departamento
            const colores = obtenerColoresDepartamento(indiceDepartamento);
            cardClass = colores.cardClass;
            textClass = colores.textClass;
            textSecondaryClass = colores.textSecondaryClass;
            iconClass = colores.iconClass;
        } else {
            // Primeros dos niveles (jefes de departamento)
            cardClass = 'border-secondary bg-light';
            textClass = 'text-primary';
            textSecondaryClass = 'text-muted';
            iconClass = 'text-primary';
        }

        const shadowClass = esJefe ? 'shadow-lg' : 'shadow-sm';

        return `
                <div class="${cardSize} mb-3">
                    <div class="card ${cardClass} ${shadowClass} h-100 ${tieneSubordinados ? 'card-clickable' : ''}" 
                         ${clickable} 
                         data-id="${empleado.id}">
                        <div class="card-body text-center p-3">
                            <img src="${empleado.foto}" 
                                 alt="${empleado.nombre}" 
                                 class="rounded-circle mb-2" 
                                 width="60" height="60"
                                 style="object-fit: cover;">
                            <h6 class="card-title mb-1 ${textClass}">${empleado.nombre}</h6>
                            <p class="card-text small mb-2 ${textSecondaryClass}">${empleado.cargo}</p>
                            ${equipoInfo}
                            ${tieneSubordinados ? `<i class="fas fa-chevron-down mt-2 ${iconClass}"></i>` : ''}
                        </div>
                    </div>
                </div>
            `;
    }

    // Función para contar subordinados
    function contarSubordinados(empleado) {
        if (!empleado.subordinados) return 0;
        let count = empleado.subordinados.length;
        empleado.subordinados.forEach(sub => {
            count += contarSubordinados(sub);
        });
        return count;
    }

    // Función para generar nivel
    function generarNivel(empleados, nivel = 0) {
        if (!empleados || empleados.length === 0) return '';

        let html = '';

        // Crear nivel actual
        html += `<div class="row justify-content-center mb-3">`;
        empleados.forEach((empleado, index) => {
            html += crearTarjetaEmpleado(empleado, nivel, index);
        });
        html += `</div>`;

        // Crear subniveles colapsables
        empleados.forEach((empleado, index) => {
            if (empleado.subordinados && empleado.subordinados.length > 0) {
                // Determinar color del borde según el índice del departamento
                let borderColor = 'border-primary';
                if (nivel >= 1) {
                    const colores = obtenerColoresDepartamento(index);
                    borderColor = colores.borderColor;
                }

                html += `
                        <div class="collapse mt-3" id="collapse-${empleado.id}">
                            <div class="container-fluid">
                                <div class="border-start border-3 ${borderColor} ps-4 ms-3">
                                    ${generarNivel(empleado.subordinados, nivel + 1)}
                                </div>
                            </div>
                        </div>
                    `;
            }
        });

        return html;
    }

    // Función principal
    function generarOrganigrama() {
        return generarNivel([organigramaData], 0);
    }

    // Variables globales
    let todoExpandido = false;

    // Expandir/Colapsar todo
    function toggleExpandirTodo() {
        const btn = document.querySelector('.btn-primary');
        const icon = btn.querySelector('i');
        const text = document.getElementById('btnText');

        if (todoExpandido) {
            // Colapsar todo
            document.querySelectorAll('.collapse').forEach(collapse => {
                const bsCollapse = new bootstrap.Collapse(collapse, {
                    toggle: false
                });
                bsCollapse.hide();
            });

            icon.className = 'fas fa-expand-arrows-alt me-2';
            text.textContent = 'Expandir Todo';
            todoExpandido = false;
        } else {
            // Expandir todo
            document.querySelectorAll('.collapse').forEach(collapse => {
                const bsCollapse = new bootstrap.Collapse(collapse, {
                    toggle: false
                });
                bsCollapse.show();
            });

            icon.className = 'fas fa-compress-arrows-alt me-2';
            text.textContent = 'Colapsar Todo';
            todoExpandido = true;
        }
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('organigramaContent').innerHTML = generarOrganigrama();

        // Añadir efectos hover para las cards clickeables
        document.querySelectorAll('.card-clickable').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'transform 0.2s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
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