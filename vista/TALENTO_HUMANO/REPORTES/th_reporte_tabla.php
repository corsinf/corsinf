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
    });

    function exportar_excel() {
        let txt_fecha_inicio = $('#txt_fecha_inicio').val();
        let txt_fecha_fin = $('#txt_fecha_fin').val();
        let ddl_departamentos = $('#ddl_departamentos').val();

        if (!txt_fecha_inicio || !txt_fecha_fin || !ddl_departamentos) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, complete todos los campos obligatorios.'
            });
            return;
        }

        const url = '../controlador/TALENTO_HUMANO/th_control_acceso_calculosC.php?descargar_excel=true' +
            '&txt_fecha_inicio=' + encodeURIComponent(txt_fecha_inicio) +
            '&txt_fecha_fin=' + encodeURIComponent(txt_fecha_fin) +
            '&ddl_departamentos=' + encodeURIComponent(ddl_departamentos) +
            '&_id=' + encodeURIComponent(<?= $_id ?>);

        window.location.href = url; // Esto hace que el navegador descargue el Excel directamente
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

                    // Ordenar encabezados según el orden de la consulta SQL
                    response.sort((a, b) => a.orden - b.orden);

                    // Construir las columnas (th) con nombre_encabezado
                    thead.empty();
                    let headerRow = "<tr>";
                    headerRow += `<th style="width:1%; white-space:nowrap;">Acción</th>`; // columna fija
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

                    // Aumentar la columna de botón a 'columns'
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
        let ddl_departamentos = $('#ddl_departamentos').val();

        if (!txt_fecha_inicio || !txt_fecha_fin || !ddl_departamentos) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, complete todos los campos obligatorios.'
            });
            return;
        }

        // Validar rango máximo de 31 días
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
            return; // Evita ejecutar la búsqueda
        }

        // Si pasa la validación, ejecuta la búsqueda
        let parametros = {
            'txt_fecha_inicio': txt_fecha_inicio,
            'txt_fecha_fin': txt_fecha_fin,
            'ddl_departamentos': ddl_departamentos,
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
                // console.log(response);
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
    }
</script>

<script>
    $(document).ready(function() {
        let hoy = new Date();
        let hoyStr = hoy.toISOString().split('T')[0];

        // Limitar que fecha fin no pueda ser mayor a hoy
        $("#txt_fecha_fin").attr("max", hoyStr);

        // Inicialmente habilitados
        $("#txt_fecha_fin").prop("disabled", false);
        $("#btn_buscar").prop("disabled", false);
        $("#btn_exportar_excel").prop("disabled", false); // siempre habilitado

        // Validar al cambiar fechas
        $("#txt_fecha_inicio, #txt_fecha_fin").on("change", function() {
            validar_fechas_basicas();
        });

        // Evento click en Buscar
        $("#btn_buscar").on("click", function(e) {
            if (!validar_rango_mes()) {
                e.preventDefault();
            }
        });

        // Validación básica (solo que inicio <= fin)
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

        // Validar que rango sea máximo 1 mes
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
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reporte</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Reporte
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">

                    <div class="card-body p-4">
                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Reporte';
                                } else {
                                    echo 'Reporte';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_reportes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <div class="card">


                            <div class="card-body p-2">

                                <div class="row pt-1">
                                    <div class="col-md-3">
                                        <label for="txt_fecha_inicio" class="form-label fw-bold">Fecha Inicio <label style="color: red;">*</label> </label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_fecha_fin" class="form-label fw-bold">Fecha Fin <label style="color: red;">*</label> </label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="ddl_departamentos" class="form-label">Departamentos </label>
                                        <select class="form-select form-select-sm select2-validation" id="ddl_departamentos" name="ddl_departamentos">
                                            <option selected disabled>-- Seleccione --</option>
                                        </select>
                                        <label class="error" style="display: none;" for="ddl_departamentos"></label>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modal-footer pt-2 d-flex gap-2" id="seccion_boton_consulta">
                                            <button class="btn btn-success btn-sm px-3" onclick="exportar_excel();" type="button">
                                                <i class='bx bx-file'></i> Exportar Excel
                                            </button>
                                            <button id="btn_buscar" class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button">
                                                <i class='bx bx-search'></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">

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
                                    <table class="table table-striped responsive " id="tbl_reporte" style="width:100%">
                                        <thead id="thead_reporte">
                                            <!-- <tr>
                                                <th>Nombre</th>
                                            </tr> -->
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

<div class="modal" id="modal_informacion_marcacion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <div id="lbl_informacion_marcacion">.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>