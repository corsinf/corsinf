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
        cargar_reporte_atributos('<?= $_id ?>');
        // control_acceso_reporte();
    });

    function cargar_reporte_atributos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_reporte_camposC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log("Encabezados recibidos:", response);

                if (response.length > 0) {
                    let thead = $("#thead_reporte");
                    let tbody = $("#tbl_reporte tbody");
                    let columns = [];

                    // Ordenar encabezados según el orden de la consulta SQL
                    response.sort((a, b) => a.orden - b.orden);

                    // Construir las columnas (th) con nombre_encabezado
                    thead.empty();
                    let headerRow = "<tr>";
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

                    // Inicializar DataTable con los datos
                    tbl_reporte = $('#tbl_reporte').DataTable($.extend({}, configuracion_datatable('Reporte', 'reporte'), {
                        destroy: true,
                        reponsive: true,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                        },
                        ajax: {
                            url: '../controlador/TALENTO_HUMANO/th_reportesC.php?pruebas=true',
                            dataSrc: ''
                        },
                        columns: columns,
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

    function control_acceso_reporte() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_reportesC.php?pruebas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar los datos:", error);
            }
        });
    }
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="modal-footer pt-2" id="seccion_boton_consulta">
                                            <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button"><i class='bx bx-search'></i> Buscar</button>
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