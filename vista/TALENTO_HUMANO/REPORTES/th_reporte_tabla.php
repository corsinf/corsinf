<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_selects2();

        //Validacion para las fechas
        $("input[name='txt_fecha_fin']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio', 'txt_fecha_fin')) return;
        });

        // Manejo del tipo de búsqueda principal (departamento/persona)
        $('input[name="tipo_busqueda_principal"]').on('change', function() {
            let tipo = $(this).val();

            if (tipo === 'departamento') {
                $('#pnl_ordenamiento').slideDown(300);
                $('#pnl_departamentos').slideDown(300);
                $('#pnl_filtro_departamento').slideDown(300);
                $('#pnl_personas').slideUp(300);
                $('#ddl_personas').val('').trigger('change');

                // Resetear el filtro de departamento a "todos"
                $('#radio_dept_todos').prop('checked', true);
                $('#pnl_personas_departamento').slideUp(300);
            } else {
                $('#pnl_ordenamiento').slideUp(300);
                $('#pnl_personas').slideDown(300);
                $('#pnl_departamentos').slideUp(300);
                $('#pnl_filtro_departamento').slideUp(300);
                $('#pnl_personas_departamento').slideUp(300);
                $('#ddl_departamentos').val('').trigger('change');
            }
        });

        // Manejo del filtro dentro de departamento (todos/persona específica)
        $('input[name="filtro_departamento"]').on('change', function() {
            let filtro = $(this).val();

            if (filtro === 'persona_especifica') {
                $('#pnl_personas_departamento').slideDown(300);
                // Cargar personas del departamento seleccionado
                let dept_id = $('#ddl_departamentos').val();
                if (dept_id) {
                    cargar_personas_por_departamento(dept_id);
                }
            } else {
                $('#pnl_personas_departamento').slideUp(300);
                $('#ddl_personas_departamento').val('').trigger('change');
            }
        });

        // Cuando cambia el departamento, actualizar personas si está en modo persona específica
        $('#ddl_departamentos').on('change', function() {
            let dept_id = $(this).val();
            let filtro = $('input[name="filtro_departamento"]:checked').val();

            if (filtro === 'persona_especifica' && dept_id) {
                cargar_personas_por_departamento(dept_id);
            }
        });

        // Manejo del tipo de ordenamiento
        $('input[name="tipo_ordenamiento"]').on('change', function() {
            if (typeof tbl_reporte !== 'undefined' && tbl_reporte) {
                let fechas_validas = $('#txt_fecha_inicio').val() && $('#txt_fecha_fin').val();
                let filtro_valido = validar_filtros();

                if (fechas_validas && filtro_valido) {
                    buscar_fechas();
                }
            }
        });

        let hoy = new Date();
        let hoyStr = hoy.toISOString().split('T')[0];

        $("#txt_fecha_fin").attr("max", hoyStr);
        $("#txt_fecha_fin").prop("disabled", false);
        $("#btn_buscar").prop("disabled", false);
        $("#btn_exportar_excel").prop("disabled", false);

        $("#txt_fecha_inicio, #txt_fecha_fin").on("change", function() {
            validar_fechas_basicas();
        });

        $("#btn_buscar").on("click", function(e) {
            if (!validar_rango_mes()) {
                e.preventDefault();
            }
        });

        function validar_fechas_basicas() {
            let fecha_inicio = $("#txt_fecha_inicio").val();
            let fecha_fin = $("#txt_fecha_fin").val();

            if (!fecha_inicio || !fecha_fin) {
                $("#btn_buscar").prop("disabled", true);
                return;
            }

            let inicio_date = new Date(fecha_inicio);
            let fin_date = new Date(fecha_fin);

            if (inicio_date > fin_date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fechas inválidas',
                    text: 'La fecha de inicio no puede ser mayor que la fecha final.'
                });
                $("#btn_buscar").prop("disabled", true);
            } else {
                $("#btn_buscar").prop("disabled", false);
            }
        }

        function validar_rango_mes() {
            let fecha_inicio = $("#txt_fecha_inicio").val();
            let fecha_fin = $("#txt_fecha_fin").val();

            let inicio_date = new Date(fecha_inicio);
            let fin_date = new Date(fecha_fin);

            const diff_tiempo = Math.abs(fin_date - inicio_date);
            const diff_dias = Math.ceil(diff_tiempo / (1000 * 60 * 60 * 24));

            if (diff_dias > 31) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rango inválido',
                    text: 'El rango de fechas para buscar no puede ser mayor a 31 días.'
                });
                return false;
            }
            return true;
        }
    });

    function validar_filtros() {
        let tipo_busqueda = $('input[name="tipo_busqueda_principal"]:checked').val();

        if (tipo_busqueda === 'departamento') {
            let dept = $('#ddl_departamentos').val();
            if (!dept) return false;

            let filtro = $('input[name="filtro_departamento"]:checked').val();
            if (filtro === 'persona_especifica') {
                return $('#ddl_personas_departamento').val() ? true : false;
            }
            return true;
        } else {
            return $('#ddl_personas').val() ? true : false;
        }
    }

    function cargar_personas_por_departamento(dept_id) {
        let url = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true&departamento=' + dept_id;

        $('#ddl_personas_departamento').empty().append('<option value="">-- Seleccione --</option>');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                response.forEach(function(persona) {
                    $('#ddl_personas_departamento').append(
                        $('<option></option>').val(persona.id).text(persona.text)
                    );
                });
                $('#ddl_personas_departamento').trigger('change');
            }
        });
    }

    function exportar_excel() {
        let txt_fecha_inicio = $('#txt_fecha_inicio').val();
        let txt_fecha_fin = $('#txt_fecha_fin').val();
        let tipo_busqueda = $('input[name="tipo_busqueda_principal"]:checked').val();
        let tipo_ordenamiento = $('input[name="tipo_ordenamiento"]:checked').val();

        let ddl_departamentos = '';
        let ddl_personas = '';

        if (tipo_busqueda === 'departamento') {
            ddl_departamentos = $('#ddl_departamentos').val();
            let filtro = $('input[name="filtro_departamento"]:checked').val();
            if (filtro === 'persona_especifica') {
                ddl_personas = $('#ddl_personas_departamento').val();
            }
        } else {
            ddl_personas = $('#ddl_personas').val();
        }

        if (!txt_fecha_inicio || !txt_fecha_fin) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, complete las fechas obligatorias.'
            });
            return;
        }

        if (tipo_busqueda === 'departamento' && !ddl_departamentos) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, seleccione un departamento.'
            });
            return;
        }

        if (tipo_busqueda === 'persona' && !ddl_personas) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, seleccione una persona.'
            });
            return;
        }

        if (tipo_busqueda === 'departamento') {
            let filtro = $('input[name="filtro_departamento"]:checked').val();
            if (filtro === 'persona_especifica' && !ddl_personas) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Por favor, seleccione una persona del departamento.'
                });
                return;
            }
        }

        const url = '../controlador/TALENTO_HUMANO/th_control_acceso_calculosC.php?descargar_excel=true' +
            '&txt_fecha_inicio=' + encodeURIComponent(txt_fecha_inicio) +
            '&txt_fecha_fin=' + encodeURIComponent(txt_fecha_fin) +
            '&tipo_busqueda=' + encodeURIComponent(tipo_busqueda) +
            '&ddl_departamentos=' + encodeURIComponent(ddl_departamentos || '') +
            '&ddl_personas=' + encodeURIComponent(ddl_personas || '') +
            '&tipo_ordenamiento=' + encodeURIComponent(tipo_ordenamiento) +
            '&_id=' + encodeURIComponent('<?= $_id ?>');

        window.location.href = url;
    }

    function cargar_reporte_atributos(id, parametros) {
        console.log('cargar_reporte_atributos: ', parametros);

        $.ajax({
            data: {
                id: id,
            },
            url: '../controlador/TALENTO_HUMANO/th_reporte_camposC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log("Encabezados recibidos:", response);

                if (response.length > 0) {
                    let thead = $("#thead_reporte");
                    let columns = [];

                    response.sort((a, b) => a.orden - b.orden);

                    thead.empty();
                    let headerRow = "<tr>";
                    headerRow += `<th style="width:1%; white-space:nowrap;">Acción</th>`;
                    response.forEach(item => {
                        if (item.nombre_encabezado) {
                            headerRow += `<th>${item.nombre_encabezado}</th>`;
                            columns.push({
                                data: item.nombre_encabezado
                            });
                        }
                    });
                    headerRow += "</tr>";
                    thead.append(headerRow);

                    columns.unshift({
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            let id = row.id_marcacion ?? '';
                            return `<a type="button" class="btn btn-primary btn-xs" onclick="informacion_marcacion('${id}');"><i class='bx bx-info-circle fs-7 me-0 fw-bold'></i></a>`;
                        }
                    });

                    tbl_reporte = $('#tbl_reporte').DataTable($.extend({}, configuracion_datatable('Reporte', 'reporte'), {
                        destroy: true,
                        responsive: false,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                        },
                        ajax: {
                            url: '../controlador/TALENTO_HUMANO/th_control_acceso_calculosC.php?reporte=true',
                            type: 'POST',
                            data: function(d) {
                                d.parametros = parametros;
                            },
                            dataSrc: ''
                        },
                        columns: columns
                    }));

                    $("#btn_exportar_excel").show();
                } else {
                    console.error("No se encontraron encabezados.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }

    function buscar_fechas() {
        let txt_fecha_inicio = $('#txt_fecha_inicio').val();
        let txt_fecha_fin = $('#txt_fecha_fin').val();
        let tipo_busqueda = $('input[name="tipo_busqueda_principal"]:checked').val();
        let tipo_ordenamiento = $('input[name="tipo_ordenamiento"]:checked').val();

        let ddl_departamentos = '';
        let ddl_personas = '';

        if (tipo_busqueda === 'departamento') {
            ddl_departamentos = $('#ddl_departamentos').val();
            let filtro = $('input[name="filtro_departamento"]:checked').val();
            if (filtro === 'persona_especifica') {
                ddl_personas = $('#ddl_personas_departamento').val();
            }
        } else {
            ddl_personas = $('#ddl_personas').val();
        }

        if (!txt_fecha_inicio || !txt_fecha_fin) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, complete las fechas obligatorias.'
            });
            return;
        }

        let inicio_date = new Date(txt_fecha_inicio);
        let fin_date = new Date(txt_fecha_fin);

        const diff_tiempo = Math.abs(fin_date - inicio_date);
        const diff_dias = Math.ceil(diff_tiempo / (1000 * 60 * 60 * 24));

        if (diff_dias > 31) {
            Swal.fire({
                icon: 'warning',
                title: 'Rango inválido',
                text: 'El rango de fechas no puede ser mayor a 31 días.'
            });
            return;
        }


        if (tipo_busqueda === 'departamento') {
            let filtro = $('input[name="filtro_departamento"]:checked').val();
            if (filtro === 'persona_especifica' && !ddl_personas) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Por favor, seleccione una persona del departamento.'
                });
                return;
            }
        }

        let parametros = {
            'txt_fecha_inicio': txt_fecha_inicio,
            'txt_fecha_fin': txt_fecha_fin,
            'tipo_busqueda': tipo_busqueda,
            'ddl_departamentos': ddl_departamentos,
            'ddl_personas': ddl_personas,
            'tipo_ordenamiento': tipo_ordenamiento
        };

        cargar_reporte_atributos('<?= $_id ?>', parametros);
    }

    function informacion_marcacion(id_marcacion) {
        $('#modal_informacion_marcacion').modal('show');

        $.ajax({
            data: {
                id_marcacion: id_marcacion
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_calculosC.php?informacion_marcacion=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#lbl_informacion_marcacion').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }

    function cargar_selects2() {
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar_departamento=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);

        url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
        cargar_select2_url('ddl_personas', url_personasC);

        // Inicializar select de personas por departamento
        $('#ddl_personas_departamento').select2({
            placeholder: '-- Seleccione --',
            allowClear: true
        });

        $('#pnl_ordenamiento').slideDown(300);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reporte</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte Control de Acceso</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                        <div class="card-title d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Reporte Control de Acceso</h5>
                            </div>
                            <div>
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_reportes" class="btn btn-outline-dark btn-sm">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body p-3">

                                <!-- Tipo de Búsqueda Principal -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="card bg-light border-0">
                                            <div class="card-body p-3">
                                                <label class="form-label fw-bold mb-3">
                                                    <i class="bx bx-filter-alt me-1"></i> Tipo de Búsqueda
                                                </label>
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipo_busqueda_principal"
                                                            id="radio_departamento" value="departamento" checked>
                                                        <label class="form-check-label" for="radio_departamento">
                                                            <i class="bx bx-building me-1"></i> Por Departamento
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipo_busqueda_principal"
                                                            id="radio_persona" value="persona">
                                                        <label class="form-check-label" for="radio_persona">
                                                            <i class="bx bx-user me-1"></i> Por Persona
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fechas -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="txt_fecha_inicio" class="form-label fw-bold">
                                            <i class="bx bx-calendar me-1"></i> Fecha Inicio
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="txt_fecha_inicio" name="txt_fecha_inicio">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="txt_fecha_fin" class="form-label fw-bold">
                                            <i class="bx bx-calendar me-1"></i> Fecha Fin
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="txt_fecha_fin" name="txt_fecha_fin">
                                    </div>
                                </div>

                                <!-- Panel Departamentos -->
                                <div id="pnl_departamentos">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="ddl_departamentos" class="form-label fw-bold">
                                                <i class="bx bx-building me-1"></i> Departamento
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select form-select-sm select2-validation"
                                                id="ddl_departamentos" name="ddl_departamentos">
                                                <option value="">-- Seleccione un Departamento --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Filtro dentro de Departamento -->
                                    <div class="row mb-3" id="pnl_filtro_departamento">
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body p-3">
                                                    <label class="form-label fw-bold mb-3">
                                                        <i class="bx bx-search-alt me-1"></i> Filtrar por
                                                    </label>
                                                    <div class="d-flex gap-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="filtro_departamento"
                                                                id="radio_dept_todos" value="todos" checked>
                                                            <label class="form-check-label" for="radio_dept_todos">
                                                                <i class="bx bx-group me-1"></i> Todas las Personas del Departamento
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="filtro_departamento"
                                                                id="radio_dept_persona" value="persona_especifica">
                                                            <label class="form-check-label" for="radio_dept_persona">
                                                                <i class="bx bx-user-pin me-1"></i> Persona Específica
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Select de Persona cuando se elige "Persona Específica" en Departamento -->
                                    <div class="row mb-3" id="pnl_personas_departamento" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="ddl_personas_departamento" class="form-label fw-bold">
                                                <i class="bx bx-user me-1"></i> Persona
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select form-select-sm"
                                                id="ddl_personas_departamento" name="ddl_personas_departamento">
                                                <option value="">-- Seleccione una Persona --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Panel Personas (búsqueda directa por persona) -->
                                <div class="row mb-3" id="pnl_personas" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="ddl_personas" class="form-label fw-bold">
                                            <i class="bx bx-user me-1"></i> Persona
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm select2-validation"
                                            id="ddl_personas" name="ddl_personas">
                                            <option value="">-- Seleccione una Persona --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tipo de Ordenamiento -->
                                <div class="row mb-3" id="pnl_ordenamiento" style="display: none;">
                                    <div class="col-12">
                                        <div class="card bg-light border-0">
                                            <div class="card-body p-3">
                                                <label class="form-label fw-bold mb-3">
                                                    <i class="bx bx-sort me-1"></i> Tipo de Ordenamiento
                                                </label>
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipo_ordenamiento"
                                                            id="radio_sin_ordenar" value="sin_ordenar" checked>
                                                        <label class="form-check-label" for="radio_sin_ordenar">
                                                            <i class="bx bx-list-ul me-1"></i> Sin Ordenar (Por Fecha)
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipo_ordenamiento"
                                                            id="radio_ordenado" value="ordenado">
                                                        <label class="form-check-label" for="radio_ordenado">
                                                            <i class="bx bx-sort-alt-2 me-1"></i> Ordenado (Por Persona y Fecha)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button id="btn_exportar_excel" class="btn btn-success btn-sm px-4"
                                                onclick="exportar_excel();" type="button" style="display: none;">
                                                <i class='bx bx-file'></i> Exportar Excel
                                            </button>
                                            <button id="btn_buscar" class="btn btn-primary btn-sm px-4"
                                                onclick="buscar_fechas();" type="button">
                                                <i class='bx bx-search'></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="my-4">

                        <section class="content">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_reporte" style="width:100%">
                                        <thead id="thead_reporte"></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Información Marcación -->
<div class="modal" id="modal_informacion_marcacion" tabindex="-1" aria-modal="true" role="dialog"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información de Marcación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="lbl_informacion_marcacion">.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>