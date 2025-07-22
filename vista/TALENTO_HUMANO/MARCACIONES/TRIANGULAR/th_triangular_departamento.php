<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        tbl_triangulacion_departamento = $('#tbl_triangulacion_departamento').DataTable($.extend({}, configuracion_datatable('Departamentos', 'Departamentos'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_triangular_departamento_personaC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        salida = `${item.th_dep_nombre}`
                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = `${item.th_tri_nombre}`
                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-danger btn-xs" onclick="delete_datos(${item.th_tdp_id})">
                                    <i class="bx bx-trash fs-7 me-0 fw-bold"></i>
                                </button>`;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ]
        }));

        cargar_selects2();
    });

    function cargar_selects2() {
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC, '', '#modal_agregar');


    }

    // Event listener para cuando cambie el departamento
    $(document).ready(function() {
        $('#ddl_departamentos').on('change', function() {
            cargar_triangulacion_departamento();
        });
    });

    // Función para cargar la triangulación filtrada por departamento
    function cargar_triangulacion_departamento() {
        var id_departamento = $('#ddl_departamentos').val();

        if (!id_departamento) {
            // Limpiar el select2 de triangulación
            $('#ddl_triangulacion').empty().trigger('change');
            return;
        }

        // URL para obtener los datos filtrados
        let url_triangulacion = "../controlador/TALENTO_HUMANO/th_triangular_departamento_personaC.php?buscar=true&filtrar=true";

        // Cargar el select2 con los datos filtrados
        cargar_select2_filtrado('ddl_triangulacion', url_triangulacion, id_departamento, '#modal_agregar');
    }

    // Nueva función para cargar select2 con filtros
    function cargar_select2_filtrado(id_select, url, id_departamento, modal) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                parametros: {
                    id_departamento: id_departamento
                }
            },
            success: function(response) {
                // Limpiar el select primero
                $('#' + id_select).empty();

                // Agregar opción por defecto
                $('#' + id_select).append('<option value="">Seleccione una opción</option>');

                // Verificar si hay datos
                if (response && Array.isArray(response) && response.length > 0) {
                    // Agregar las opciones al select
                    $.each(response, function(index, item) {
                        $('#' + id_select).append('<option value="' + item.th_tri_id + '">' + item.th_tri_nombre + '</option>');
                    });
                } else {
                    $('#' + id_select).append('<option value="">No hay opciones disponibles</option>');
                }

                // Refrescar el select2
                $('#' + id_select).trigger('change');

                // Si estás usando Select2, reiniciarlo
                if ($.fn.select2) {
                    $('#' + id_select).select2({
                        dropdownParent: $(modal),
                        width: '100%'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error al cargar los datos: ' + xhr.responseText, 'error');

                // En caso de error, limpiar el select
                $('#' + id_select).empty().append('<option value="">Error al cargar</option>');
            }
        });
    }




    function insertar(parametros) {
        var ddl_departamentos = $('#ddl_departamentos').val();
        var ddl_triangulacion = $('#ddl_triangulacion').val();

        var parametros = {
            'ddl_departamentos': ddl_departamentos,
            'ddl_triangulacion': ddl_triangulacion,
        };
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_triangular_departamento_personaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_triangulacion_departamento.ajax.reload();
                        limpiar_selects();
                        $('#modal_agregar').modal('hide');
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Error', 'warning');
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

    function limpiar_selects() {
        // Limpiar departamentos
        $('#ddl_departamentos').val('').trigger('change');

        // Limpiar triangulación
        $('#ddl_triangulacion').empty().trigger('change');

        // Si estás usando Select2, refrescar
        if ($.fn.select2) {
            $('#ddl_departamentos').trigger('change.select2');
            $('#ddl_triangulacion').trigger('change.select2');
        }
    }

    function delete_datos(id) {
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
            url: '../controlador/TALENTO_HUMANO/th_triangular_departamento_personaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        tbl_triangulacion_departamento.ajax.reload();
                    });
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Marcaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Asignar Triangulación al departamento
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

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_agregar"><i class="bx bx-plus"></i> Nuevo</button>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_triangulacion_departamento" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Departamento</th>
                                                <th>Triangulación</th>
                                                <th>Acción</th>
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


<div class="modal" id="modal_agregar" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label for="">Departamento <label class="text-danger">*</label></label>
                        <select name="ddl_departamentos" id="ddl_departamentos" class="form-select form-select-sm">
                            <option disabled>Seleccione el Departamento</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="">Triangulación <label class="text-danger">*</label></label>
                        <select name="ddl_triangulacion" id="ddl_triangulacion" class="form-select form-select-sm">
                            <option disabled>Seleccione la Triangulación</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="insertar();"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>