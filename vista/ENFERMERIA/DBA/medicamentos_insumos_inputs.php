<?php


?>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_datos_v_medicamentos();
        cargar_datos_v_insumos();
        cargar_datos_v_ingresoStock();
    });

    function cargar_datos_v_medicamentos() {
        $.ajax({
            url: '../controlador/v_med_insC.php?listar_v_medicamentos=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_medicamentos').empty();

                // Verificar si la respuesta contiene datos
                if (response && response.length > 0) {
                    response.forEach(function(medicamento) {
                        // Crear el HTML para cada medicamento
                        var isChecked = medicamento.sa_vmi_estado == 1 ? 'checked' : '';
                        var htmlMedicamento = '<div class="col-md-12">';
                        htmlMedicamento += '<input type="checkbox" class="medicamento-checkbox" name="medicamento[]" id="' + medicamento.sa_vmi_id_input + '" value="' + medicamento.sa_vmi_id + '" ' + isChecked + '> ';
                        htmlMedicamento += '<label>' + medicamento.sa_vmi_descripcion + '</label>';
                        htmlMedicamento += '</div>';

                        // Agregar el HTML generado al div
                        $('#pnl_medicamentos').append(htmlMedicamento);
                    });

                    // Agregar evento change a los checkboxes generados
                    $('.medicamento-checkbox').change(function() {
                        var sa_vmi_id = $(this).val();
                        var sa_vmi_estado = $(this).is(':checked') ? 1 : 0;
                        insertar(sa_vmi_id, sa_vmi_estado);
                    });
                }
            },
            error: function() {
                // Manejo de errores
                $('#pnl_medicamentos').append('<p>Error al cargar los medicamentos.</p>');
            }
        });
    }

    function cargar_datos_v_insumos() {
        $.ajax({
            url: '../controlador/v_med_insC.php?listar_v_insumos=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_insumos').empty();

                // Verificar si la respuesta contiene datos
                if (response && response.length > 0) {
                    response.forEach(function(medicamento) {
                        // Crear el HTML para cada medicamento
                        var isChecked = medicamento.sa_vmi_estado == 1 ? 'checked' : '';
                        var htmlMedicamento = '<div class="col-md-12">';
                        htmlMedicamento += '<input type="checkbox" class="medicamento-checkbox" name="insumos[]" id="' + medicamento.sa_vmi_id_input + '" value="' + medicamento.sa_vmi_id + '" ' + isChecked + '> ';
                        htmlMedicamento += '<label>' + medicamento.sa_vmi_descripcion + '</label>';
                        htmlMedicamento += '</div>';

                        // Agregar el HTML generado al div
                        $('#pnl_insumos').append(htmlMedicamento);
                    });

                    // Agregar evento change a los checkboxes generados
                    $('.medicamento-checkbox').change(function() {
                        var sa_vmi_id = $(this).val();
                        var sa_vmi_estado = $(this).is(':checked') ? 1 : 0;
                        insertar(sa_vmi_id, sa_vmi_estado);
                    });
                }
            },
            error: function() {
                // Manejo de errores
                $('#pnl_insumos').append('<p>Error al cargar los insumos.</p>');
            }
        });
    }

    function cargar_datos_v_ingresoStock() {
        $.ajax({
            url: '../controlador/v_med_insC.php?listar_v_ingresoStock=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_ingresoStock').empty();

                // Verificar si la respuesta contiene datos
                if (response && response.length > 0) {
                    response.forEach(function(valor) {
                        // Crear el HTML para cada valor
                        var isChecked = valor.sa_vmi_estado == 1 ? 'checked' : '';
                        var htmlValor = '<div class="col-md-12">';
                        htmlValor += '<input type="checkbox" class="valor-checkbox" name="insumos[]" id="' + valor.sa_vmi_id_input + '" value="' + valor.sa_vmi_id + '" ' + isChecked + '> ';
                        htmlValor += '<label>' + valor.sa_vmi_descripcion + '</label>';
                        htmlValor += '</div>';

                        // Agregar el HTML generado al div
                        $('#pnl_ingresoStock').append(htmlValor);
                    });

                    // Agregar evento change a los checkboxes generados
                    $('.valor-checkbox').change(function() {
                        var sa_vmi_id = $(this).val();
                        var sa_vmi_estado = $(this).is(':checked') ? 1 : 0;
                        insertar(sa_vmi_id, sa_vmi_estado);
                    });
                }
            },
            error: function() {
                // Manejo de errores
                $('#pnl_ingresoStock').append('<p>Error al cargar los valores.</p>');
            }
        });
    }

    function insertar(sa_vmi_id, sa_vmi_estado) {

        var parametros = {
            'sa_vmi_id': sa_vmi_id,
            'sa_vmi_estado': sa_vmi_estado
        };

        //console.log(parametros);

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/v_med_insC.php?vista_mod=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                } else if (response == -2) {
                    Swal.fire('', 'Código ya registrado', 'error');
                } else {
                    Swal.fire('', 'Algo salió mal, intente nuevamente.', 'error');
                }
            },
            error: function() {
                Swal.fire('', 'Error en la conexión con el servidor.', 'error');
            }
        });
    }
</script>

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
                            Configuración
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Configuración - Vistas Medicamentos, Insumos</h5>

                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <h6>Vista de Medicamentos</h6>
                            <div class="row" id="pnl_medicamentos">

                            </div>

                            <br><hr>

                            <h6>Vista de Insumos</h6>
                            <div class="row" id="pnl_insumos">

                            </div>

                            <br><hr>

                            <h6>Vista de Ingresar Stock</h6>
                            <div class="row" id="pnl_ingresoStock">

                            </div>

                            



                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>