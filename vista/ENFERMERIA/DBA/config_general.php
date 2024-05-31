<?php


?>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_datos_v_config();
    });

    function cargar_datos_v_config() {
        $.ajax({
            url: '../controlador/cat_configuracionGC.php?listar_config_general=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_config_general').empty();

                // Verificar si la respuesta contiene datos
                if (response && response.length > 0) {
                    response.forEach(function(config) {
                        // Crear el HTML para cada config
                        var isChecked = config.sa_config_estado == 1 ? 'checked' : '';
                        var htmlconfig = '<div class="col-md-12">';
                        htmlconfig += '<input type="checkbox" class="config-checkbox" name="config[]" id="' + config.sa_config_validar + '" value="' + config.sa_config_id + '" ' + isChecked + '> ';
                        htmlconfig += '<label>' + config.sa_config_descripcion + '</label>';
                        htmlconfig += '</div>';

                        // Agregar el HTML generado al div
                        $('#pnl_config_general').append(htmlconfig);
                    });

                    // Agregar evento change a los checkboxes generados
                    $('.config-checkbox').change(function() {
                        var sa_config_id = $(this).val();
                        var sa_config_estado = $(this).is(':checked') ? 1 : 0;
                        insertar(sa_config_id, sa_config_estado);
                    });
                }
            },
            error: function() {
                // Manejo de errores
                $('#pnl_config_general').append('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function insertar(sa_config_id, sa_config_estado) {

        var parametros = {
            'sa_config_id': sa_config_id,
            'sa_config_estado': sa_config_estado
        };

        //console.log(parametros);

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cat_configuracionGC.php?vista_mod=true',
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
                            <h5 class="mb-0 text-primary">Configuración - General</h5>

                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <h6>Configuración General</h6>
                            <div class="row" id="pnl_config_general">

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