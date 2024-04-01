<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
<script src="../js/ENFERMERIA/pacientes.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        carga_tabla();
        consultar_datos_comunidad_tabla();
        consultar_tablas_datos('');
    });

    function carga_tabla() {
        tabla_pacientes = $('#tbl_pacientes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/pacientesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'sa_pac_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<div"><a href="../vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=' + item.sa_pac_id + '" " title="Historial de Consultas"><u>' + item.sa_pac_apellidos + ' ' + item.sa_pac_nombres + '</u></a></div>';
                    }
                },
                {
                    data: 'sa_pac_correo'
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        fecha_nacimiento = item.sa_pac_fecha_nacimiento;

                        salida = fecha_nacimiento ? calcular_edad_fecha_nacimiento(item.sa_pac_fecha_nacimiento) : '';

                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return (item.sa_pac_tabla).toUpperCase();
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        url = '../vista/inicio.php?mod=7&acc=pacientes';
                        return '<a title="Editar Ficha Médica" class="text-center btn btn-warning btn-sm" href="#" onclick="gestion_paciente_comunidad(' + item.sa_pac_id_comunidad + ', \'' + item.sa_pac_tabla + '\', \'' + url + '\');"><u>' + '<i class="bx bxs-edit-alt me-0"></i>' + '</u></a>';
                    }
                }
            ],

            order: []
        });
    }

    function consultar_tablas_datos(valor_seleccionar) {

        var valor_seleccionar = valor_seleccionar.split('-');
        var sa_tbl_pac_tabla = valor_seleccionar[0];
        var sa_tbl_pac_prefijo = valor_seleccionar[1];

        //alert(sa_tbl_pac_prefijo);

        $('#sa_pac_id_comunidad').select2({
            placeholder: 'Selecciona una opción',
            dropdownParent: $('#modal_pacientes'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            },
            minimumInputLength: 1,
            ajax: {
                url: '../controlador/' + sa_tbl_pac_tabla + 'C.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function(data, params) { // Agrega 'params' como parámetro
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function(filtered, item) {

                        var fullName = item['' + sa_tbl_pac_prefijo + '_cedula'] + " - " + item['' + sa_tbl_pac_prefijo + '_primer_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_primer_nombre'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_nombre'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['' + sa_tbl_pac_prefijo + '_id'],
                                text: fullName
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        });
    }

    function consultar_datos_comunidad_tabla() {
        var salida = '<option value="">Seleccione el Tipo de Paciente</option>';

        $.ajax({
            url: '../controlador/Comunidad_TablasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $.each(response, function(i, item) {
                    // Concatenar dos variables en el valor del atributo "value"
                    salida += '<option value="' + item.sa_tbl_pac_nombre + '-' + item.sa_tbl_pac_prefijo + '">' + item.sa_tbl_pac_nombre.toUpperCase() + '</option>';
                });

                $('#sa_pac_tabla').html(salida);
            }
        });
    }

    function gestion_pacientes() {
        var sa_pac_tabla = $('#sa_pac_tabla').val();
        var sa_pac_id_comunidad = $('#sa_pac_id_comunidad').val();

        gestion_paciente_comunidad_pacientes(sa_pac_id_comunidad, sa_pac_tabla);

        if (tabla_pacientes) {
            tabla_pacientes.destroy(); // Destruir la instancia existente del DataTable
        }

        $('#modal_pacientes').modal('hide');
        carga_tabla(); // Volver a cargar la tabla
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=ficha_medica_pacientes" method="post" style="display: none;">
    <input type="hidden" id="sa_pac_id" name="sa_pac_id" value="">
</form>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Comunidad Educativa
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_pacientes"><i class="bx bx-plus"></i> Nuevo Paciente</button>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_pacientes" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombres</th>
                                                <th>Correo</th>
                                                <th>Edad</th>
                                                <th>Tipo Paciente</th>
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


<div class="modal" id="modal_pacientes" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="sa_pac_tabla">Tipo de Paciente <label class="text-danger">*</label></label>
                        <select name="sa_pac_tabla" id="sa_pac_tabla" class="form-select form-select-sm" onchange="consultar_tablas_datos(this.value)">
                            <option value="">Seleccione el Tipo de Paciente</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="sa_pac_id_comunidad">Paciente <label class="text-danger">*</label></label>
                        <select name="sa_pac_id_comunidad" id="sa_pac_id_comunidad" class="form-select form-select-sm">
                            <option value="">Seleccione el Paciente</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="gestion_pacientes()"><i class="bx bx-save"></i> Agregar</button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>