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

        cargar_reporte(1);

    });

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

        const url = '../controlador/TALENTO_HUMANO/th_control_acceso_calculosC.php?descargar_excel_general_=true' +
            '&txt_fecha_inicio=' + encodeURIComponent(txt_fecha_inicio) +
            '&txt_fecha_fin=' + encodeURIComponent(txt_fecha_fin) +
            '&tipo_busqueda=' + encodeURIComponent(tipo_busqueda) +
            '&ddl_departamentos=' + encodeURIComponent(ddl_departamentos || '') +
            '&ddl_personas=' + encodeURIComponent(ddl_personas || '') +
            '&tipo_ordenamiento=' + encodeURIComponent(tipo_ordenamiento) +
            '&_id=' + encodeURIComponent('<?= $_id ?>');

        console.log(url);
        window.location.href = url;
    }

    function cargar_reporte(parametros) {
        console.log(parametros);

        if (parametros == 1) {
            let fecha_hoy = new Date();
            hoy = fecha_formateada(fecha_hoy);
            parametros = {
                'txt_fecha_inicio': hoy,
                'txt_fecha_fin': hoy,
                'tipo_busqueda': 'departamento',
                'ddl_departamentos': 'todos',
                'ddl_personas': '',
                'tipo_ordenamiento': ''
            };
        }


        let columns = [{
                data: 'APELLIDOS',
                title: 'APELLIDOS'
            },
            {
                data: 'NOMBRES',
                title: 'NOMBRES'
            },
            {
                data: 'empleado',
                title: 'Empleado'
            },
            {
                data: 'Cedula',
                title: 'Cédula'
            },
            {
                data: 'Correo',
                title: 'Correo Institucional'
            },
            {
                data: 'Departamento',
                title: 'Departamento'
            },
            {
                data: 'dia',
                title: 'Día'
            },
            {
                data: 'Fecha',
                title: 'Fecha'
            },
            {
                data: 'Horario',
                title: 'Horario Contrato'
            },
            {
                data: 'turno',
                title: 'Turno'
            },
            {
                data: null,
                title: 'Entrada Inicio Turno',
                 render: function (data, type, row, meta) {
                    return minutosAHora(data.entrada_tiempo_marcacion_valida_inicio) 
                }
            },
            {
                data: null,
                title: 'Entrada Fin Turno',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.entrada_tiempo_marcacion_valida_fin) 
                }
            },
            {
                data: 'RegistroIng',
                title: 'Reg. Entrada'
            },
            {
                data: null,
                title: 'Hora Entrada',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.entrada_tiempo_marcacion_valida_inicio) 
                }
            },
            {
                data: null,
                title: 'Hora Ajustada',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.entrada_min)
                }
            },
            {
                data:null ,
                title: 'Atrasos',
                render: function (data, type, row, meta) {
                    if(data.Atrasos>0) { return 'SI'; }else{ return 'NO';}
                }
            },
            {
                data:'Atrasos' ,
                title: 'Atrasos min',
            },
            {
                data: 'Ausente',
                title: 'Ausente'
            },
            {
                data: null,
                title: 'Salida Inicio Turno',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.salida_tiempo_marcacion_valida_inicio) 
                }
            },
            {
                data: null,
                title: 'Salida Fin Turno',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.salida_tiempo_marcacion_valida_fin) 
                }
            },
            {
                data: 'Fecha',
                title: 'Reg. Salida'
            },
            {
                data: 'RegistroSalida',
                title: 'Hora Salida'
            },
            {
                data: null,
                title: 'Salidas Temprano',
                render: function (data, type, row, meta) {
                    if(data.TotalMarcaciones % 2 === 0 )
                    {
                        return "NO";

                    }else
                    {
                        return "SI";
                    }
                }
                
            },
            {
                data: null,
                title: 'Días Trabajados',
                 render: function (data, type, row, meta) {
                    return 1;
                }
            },
            {
                data: null,
                title: 'Cumplimiento Jornada (8h)',
                render: function (data, type, row, meta) {
                    if(data.Horas_trabajadas==0) { return 'SI'}else { return 'NO'; }
                }
            },
            {
                data: null,
                title: 'Horas Faltantes',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.Horas_faltantes) 
                }
            },
            {
                data: null,
                title: 'Horas Trabajadas',
                render: function (data, type, row, meta) {
                    return minutosAHora(data.Horas_trabajadas) 
                }
            },
            {
                data:null,
                title: 'Horas Excedentes',
                render: function (data, type, row, meta) {
                    var exce = data.RegistroSalida-data.salida_min;
                    if(exce>0) { return minutosAHora(exce) }else{ return '00:00'}
                }
            },
            {
                data: null,
                title: 'Salidas Temprano (Calc)',
                 render: function (data, type, row, meta) {
                    var exce = data.RegistroSalida-data.salida_min;
                    if(data.RegistroSalida < data.salida_tiempo_marcacion_valida_inicio && data.TotalMarcaciones % 2 === 0 )
                    {
                        return 'SI';
                    }else
                    {
                        return "NO";
                    }
                }
            },
            {
                data: 'Suplem',
                title: 'Suplem 25%'
            },
            {
                data: 'Extra',
                title: 'Extra 100%'
            },
            // {
            //     data: 'id_marcacion',
            //     title: 'ID Marcación'
            // }
        ];

        tbl_reporte = $('#tbl_reporte').DataTable({
            destroy: true,
            responsive: false,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_control_accesoC.php?reporte=true',
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
                        $('#contador_sin_marcacion').text(0);
                        $('#contador_ausentes').text(0);
                        return [];
                    }

                    let sin_marcacion = 0;
                    let ausentes = 0;

                    json.forEach(row => {
                        if (row.Atrasos === 'SIN MARCACION') sin_marcacion++;
                        if (row.Ausente === 'SI') ausentes++;
                    });

                    $('#contador_sin_marcacion').text(sin_marcacion);
                    $('#contador_ausentes').text(ausentes);

                    return json;
                }
            },
            columns: columns
        });
    }

    function minutosAHora(minutos) 
    {
        const horas = Math.floor(minutos / 60);
        const minutosRestantes = minutos % 60;
        // Formato HH:MM (con 2 dígitos)
        return `${horas.toString().padStart(2, '0')}:${minutosRestantes.toString().padStart(2, '0')}`;
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

        cargar_reporte(parametros);
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
        let option = new Option('Todos los departamentos', '0', true, true);
        $('#ddl_departamentos').append(option).trigger('change');

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

<!-- Funciones -->
<script>
    $(document).ready(function() {
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
    });

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
</script>



<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reportes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte General - Asistentecias</li>
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
                                <h5 class="mb-0 text-primary">Reporte General - Asistentecias</h5>
                            </div>

                        </div>

                        <div class="">
                            <div class="card-body p-3">

                                <div class="row g-2 mb-3">
                                    <!-- Tipo de Búsqueda -->
                                    <div class="col-md-4">
                                        <div class="card h-100 mb-0">
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
                                        <div class="card h-100 mb-0">
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
                                    <div class="col-md-4 d-none" id="pnl_ordenamiento">
                                        <div class="card h-100 mb-0">
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

                                      <!-- Departamento -->
                                    <div class="col-md-5" id="select_departamento_container">
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
                                    <div class="col-md-5" id="select_persona_container" style="display: none;">
                                        <label for="ddl_personas" class="form-label fw-bold">
                                            <i class="bx bx-user me-1"></i> Persona
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm select2-validation"
                                            id="ddl_personas" name="ddl_personas">
                                            <option value="">-- Seleccione una Persona --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_fecha_inicio" class="form-label fw-bold">
                                            <i class="bx bx-calendar me-1"></i> Seleccione
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm select2-validation"
                                            id="ddl_seleccion_mes" name="ddl_seleccion_mes">

                                            <option value="hoy" selected>Hoy</option>
                                            <option value="ayer">Ayer</option>
                                            <option value="semana_actual">Semana Actual</option>
                                            <option value="este_mes">Este Mes</option>
                                            <option value="mes_anterior">Mes Anterior</option>
                                            <option value="personalizado">Personalizado</option>
                                        </select>
                                    </div>

                                    <!-- Panel fechas -->
                                    <div class="col-md-4" id="pnl_fechas">
                                        <div class="row g-3">
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


                        <hr>

                        <!-- Tabla de Resultados -->
                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive" id="tbl_reporte" style="width:100%">
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

<script>
    $(document).ready(function() {

        inicializar_fechas_hoy();

        $('#ddl_seleccion_mes').on('change', function() {
            const opcion_seleccionada = $(this).val();
            const fecha_hoy = new Date();

            let fecha_inicio = null;
            let fecha_fin = null;

            switch (opcion_seleccionada) {

                case 'hoy':
                    fecha_inicio = fecha_hoy;
                    fecha_fin = fecha_hoy;
                    break;

                case 'ayer':
                    const fecha_ayer = new Date(fecha_hoy);
                    fecha_ayer.setDate(fecha_hoy.getDate() - 1);
                    fecha_inicio = fecha_ayer;
                    fecha_fin = fecha_ayer;
                    break;

                case 'semana_actual':
                    const inicio_semana = new Date(fecha_hoy);
                    inicio_semana.setDate(
                        fecha_hoy.getDate() - (fecha_hoy.getDay() === 0 ? 6 : fecha_hoy.getDay() - 1)
                    );

                    const fin_semana = new Date(inicio_semana);
                    fin_semana.setDate(inicio_semana.getDate() + 6);

                    fecha_inicio = inicio_semana;
                    fecha_fin = fin_semana;
                    break;

                case 'este_mes':
                    fecha_inicio = new Date(fecha_hoy.getFullYear(), fecha_hoy.getMonth(), 1);
                    fecha_fin = new Date(fecha_hoy.getFullYear(), fecha_hoy.getMonth() + 1, 0);
                    break;

                case 'mes_anterior':
                    fecha_inicio = new Date(fecha_hoy.getFullYear(), fecha_hoy.getMonth() - 1, 1);
                    fecha_fin = new Date(fecha_hoy.getFullYear(), fecha_hoy.getMonth(), 0);
                    break;

                case 'personalizado':
                    $('#txt_fecha_inicio, #txt_fecha_fin').val('');
                    return;
            }

            $('#txt_fecha_inicio').val(fecha_formateada(fecha_inicio));
            $('#txt_fecha_fin').val(fecha_formateada(fecha_fin));
        });

    });

    function inicializar_fechas_hoy() {
        let fecha_hoy = new Date();

        $('#ddl_seleccion_mes').val('hoy');

        $('#txt_fecha_inicio').val(fecha_formateada(fecha_hoy));
        $('#txt_fecha_fin').val(fecha_formateada(fecha_hoy));
    }
</script>