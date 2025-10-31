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

        // Manejo del tipo de búsqueda principal (departamento/persona)
        $('input[name="tipo_busqueda_principal"]').on('change', function() {
            let tipo = $(this).val();

            if (tipo === 'departamento') {
                // Mostrar contenedor de departamento
                $('#select_departamento_container').show();
                $('#select_persona_container').hide();
                $('#ddl_personas').val('').trigger('change');

                // Mostrar opciones de filtro y ordenamiento
                $('#pnl_filtro_departamento').slideDown(300);
                $('#pnl_ordenamiento').slideDown(300);

                // Resetear filtro a "todos"
                $('#radio_dept_todos').prop('checked', true);
                $('#pnl_personas_departamento').slideUp(300);
            } else {
                // Mostrar contenedor de persona
                $('#select_persona_container').show();
                $('#select_departamento_container').hide();
                $('#ddl_departamentos').val('').trigger('change');

                // Ocultar opciones de filtro y ordenamiento
                $('#pnl_filtro_departamento').slideUp(300);
                $('#pnl_ordenamiento').slideUp(300);
                $('#pnl_personas_departamento').slideUp(300);
            }
        });

        // Manejo del filtro dentro de departamento (todos/persona específica)
        $('input[name="filtro_departamento"]').on('change', function() {
            let filtro = $(this).val();

            if (filtro === 'persona_especifica') {
                $('#pnl_personas_departamento').slideDown(300);
                $('#pnl_ordenamiento').slideUp(300);
                // Cargar personas del departamento seleccionado
                let dept_id = $('#ddl_departamentos').val();
                if (dept_id) {
                    cargar_personas_departamento(dept_id);
                }
            } else {
                $('#pnl_personas_departamento').slideUp(300);
                $('#pnl_ordenamiento').slideDown(300);
                $('#ddl_personas_departamento').val('').trigger('change');
            }
        });

        // Cuando cambia el departamento, actualizar personas si está en modo persona específica
        $('#ddl_departamentos').on('change', function() {
            let dept_id = $(this).val();
            let filtro = $('input[name="filtro_departamento"]:checked').val();

            if (filtro === 'persona_especifica' && dept_id) {
                cargar_personas_departamento(dept_id);
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


        $("#btn_buscar").on("click", function(e) {
            if (!validar_rango_mes()) {
                e.preventDefault();
            }
        });

        $("#txt_fecha_inicio, #txt_fecha_fin").on("blur", validar_fechas_basicas);

        function validar_fechas_basicas() {
            const inicioInput = document.getElementById('txt_fecha_inicio');
            const finInput = document.getElementById('txt_fecha_fin');

            // valueAsDate es null si no hay fecha seleccionada
            const inicio_date = inicioInput.valueAsDate;
            const fin_date = finInput.valueAsDate;

            if (!inicio_date || !fin_date) {
                $("#btn_buscar").prop("disabled", true);
                return;
            }

            // Comparación
            if (inicio_date > fin_date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fechas inválidas',
                    text: 'La fecha de inicio no puede ser mayor que la fecha final.'
                });
                $("#btn_buscar").prop("disabled", true);
                return;
            }

            // Validar max 31 días
            const diffTiempo = Math.abs(fin_date - inicio_date);
            const diffDias = Math.ceil(diffTiempo / (1000 * 60 * 60 * 24));
            if (diffDias > 31) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rango inválido',
                    text: 'El rango de fechas no puede ser mayor a 31 días.'
                });
                $("#btn_buscar").prop("disabled", true);
                return;
            }

            $("#btn_buscar").prop("disabled", false);
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
                            dataSrc: function(json) {
                                if (!json || json.length === 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Sin resultados',
                                        text: 'No existe datos de esa persona o no está asignada a ningún departamento',
                                        confirmButtonColor: '#3085d6'
                                    });
                                    // Resetear contadores
                                    $('#contador_sin_marcacion').text(0);
                                    $('#contador_ausentes').text(0);
                                    return json;
                                }

                                // Contar registros
                                let sinMarcacion = 0;
                                let ausentes = 0;

                                json.forEach(row => {
                                    // Contar "SIN MARCACION" en el campo Atrasos
                                    if (row.Atrasos === "SIN MARCACION") {
                                        sinMarcacion++;
                                    }

                                    // Contar ausentes (Ausente: "SI")
                                    if (row.Ausente === "SI") {
                                        ausentes++;
                                    }
                                });

                                // Actualizar los contadores en el HTML
                                $('#contador_sin_marcacion').text(sinMarcacion);
                                $('#contador_ausentes').text(ausentes);

                                console.log(`Sin Marcación: ${sinMarcacion}, Ausentes: ${ausentes}`);

                                return json;
                            }
                        },
                        columns: columns
                    }));

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
        // Cargar select de personas con opción "Todos los Departamentos"
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const $select = $('#ddl_personas');
                $select.empty();
                $select.append('<option value="">-- Seleccione --</option>');

                if (response.length > 0) {
                    $.each(response, function(index, item) {
                        $select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.text
                            })
                        );
                    });
                }

                // Inicializar select2
                $select.select2({
                    placeholder: '-- Seleccione --',
                    allowClear: true
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar personas:', error);
            }
        });

        // Inicializar select de personas por departamento con opción "Todos"
        $('#ddl_personas_departamento').select2({
            placeholder: '-- Seleccione --',
            allowClear: true
        });
    }

    function cargar_personas_departamento(id_departamento) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?buscar_departamento=true',
            type: 'GET',
            dataType: 'json',
            data: {
                id_departamento: id_departamento
            },
            success: function(response) {
                const $select = $('#ddl_personas_departamento');
                $select.empty();
                $select.append('<option value="" selected hidden>-- Seleccione --</option>');
                if (response.length > 0) {
                    $.each(response, function(index, item) {
                        $select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.text
                            })
                        );
                    });
                } else {
                    $select.append('<option disabled>No hay personas disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar personas:', error);
            }
        });
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

                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body p-3">

                                <div class="row g-2 mb-3">
                                    <!-- Tipo de Búsqueda -->
                                    <div class="col-md-4">
                                        <div class="card card-filter h-100 mb-0">
                                            <div class="card-body p-2">
                                                <h6 class="text-primary mb-2 fw-bold">
                                                    <i class="bx bx-filter-alt me-1"></i> Tipo de Búsqueda
                                                </h6>
                                                <div class="d-flex flex-column">
                                                    <div class="form-check form-check-custom mb-1">
                                                        <input class="form-check-input" type="radio" name="tipo_busqueda_principal"
                                                            id="radio_departamento" value="departamento" checked>
                                                        <label class="form-check-label" for="radio_departamento">
                                                            <i class="bx bx-building me-1"></i> Por Departamento
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom mb-1">
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

                                    <!-- Filtrar Por -->
                                    <div class="col-md-4" id="pnl_filtro_departamento">
                                        <div class="card card-filter h-100 mb-0">
                                            <div class="card-body p-2">
                                                <h6 class="text-primary mb-2 fw-bold">
                                                    <i class="bx bx-search-alt me-1"></i> Filtrar Por
                                                </h6>
                                                <div class="d-flex flex-column">
                                                    <div class="form-check form-check-custom mb-1">
                                                        <input class="form-check-input" type="radio" name="filtro_departamento"
                                                            id="radio_dept_todos" value="todos" checked>
                                                        <label class="form-check-label" for="radio_dept_todos">
                                                            <i class="bx bx-group me-1"></i> Todas las Personas
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom mb-1">
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

                                    <!-- Ordenamiento -->
                                    <div class="col-md-4" id="pnl_ordenamiento">
                                        <div class="card card-filter h-100 mb-0">
                                            <div class="card-body p-2">
                                                <h6 class="text-primary mb-2 fw-bold">
                                                    <i class="bx bx-sort me-1"></i> Ordenamiento
                                                </h6>
                                                <div class="d-flex flex-column">
                                                    <div class="form-check form-check-custom mb-1">
                                                        <input class="form-check-input" type="radio" name="tipo_ordenamiento"
                                                            id="radio_sin_ordenar" value="sin_ordenar" checked>
                                                        <label class="form-check-label" for="radio_sin_ordenar">
                                                            <i class="bx bx-list-ul me-1"></i> Sin Organizar
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom mb-1">
                                                        <input class="form-check-input" type="radio" name="tipo_ordenamiento"
                                                            id="radio_ordenado" value="ordenado">
                                                        <label class="form-check-label" for="radio_ordenado">
                                                            <i class="bx bx-sort-alt-2 me-1"></i> Organizado por nombre
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Fechas y Selects Dinámicos -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="txt_fecha_inicio" class="form-label fw-bold">
                                            <i class="bx bx-calendar me-1"></i> Fecha Inicio
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="txt_fecha_inicio" name="txt_fecha_inicio">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_fecha_fin" class="form-label fw-bold">
                                            <i class="bx bx-calendar me-1"></i> Fecha Fin
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm"
                                            id="txt_fecha_fin" name="txt_fecha_fin">
                                    </div>

                                    <!-- Departamento -->
                                    <div class="col-md-6" id="select_departamento_container">
                                        <label for="ddl_departamentos" class="form-label fw-bold">
                                            <i class="bx bx-building me-1"></i> Departamento
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm select2-validation"
                                            id="ddl_departamentos" name="ddl_departamentos">
                                            <option value="">-- Seleccione un Departamento --</option>
                                        </select>
                                    </div>

                                    <!-- Persona Individual -->
                                    <div class="col-md-6" id="select_persona_container" style="display: none;">
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



                                <!-- Persona Específica del Departamento -->
                                <div class="row g-3 mb-3" id="pnl_personas_departamento" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="ddl_personas_departamento" class="form-label fw-bold">
                                            <i class="bx bx-user me-1"></i> Persona del Departamento
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm"
                                            id="ddl_personas_departamento" name="ddl_personas_departamento">
                                            <option value="">-- Seleccione una Persona --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <!-- Contenedor para los contadores -->
                                            <div class="d-flex gap-3">
                                                <div class="badge bg-warning text-dark fs-6">
                                                    <i class='bx bx-time-five me-1'></i>
                                                    Atrasos: <span id="contador_sin_marcacion">0</span>
                                                </div>
                                                <div class="badge bg-danger fs-6">
                                                    <i class='bx bx-user-x me-1'></i>
                                                    Ausentes: <span id="contador_ausentes">0</span>
                                                </div>
                                            </div>

                                            <!-- Botones -->
                                            <div class="d-flex gap-2">
                                                <button id="btn_exportar_excel" class="btn btn-success btn-sm"
                                                    onclick="exportar_excel();" type="button">
                                                    <i class='bx bx-file me-1'></i> Exportar Excel
                                                </button>

                                                <button id="btn_buscar" class="btn btn-primary btn-sm"
                                                    onclick="buscar_fechas();" type="button">
                                                    <i class='bx bx-search me-1'></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Tabla de Resultados -->
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive" id="tbl_reporte" style="width:100%">
                                        <thead id="thead_reporte"></thead>
                                        <tbody></tbody>
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