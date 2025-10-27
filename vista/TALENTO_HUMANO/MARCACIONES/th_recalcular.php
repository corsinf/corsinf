<script type="text/javascript">
    $(document).ready(function() {

    });

    function sincronizar_calculo_asistencia_fecha() {

        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        fecha_inicio = $('#txt_fecha_inicio').val();
        fecha_fin = $('#txt_fecha_fin').val();

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_reportesC.php?sincronizar_calculo_asistencia_fecha=true',
            type: 'post',
            dataType: 'json',
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
            },
            success: function(response) {
                console.log(response);
                Swal.close();
                Swal.fire('Sincornizado correctamente.', '', 'success');
            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Recalcular</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Recalcular
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

                        </div>

                        <div class="row mb-1">
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-footer pt-2" id="seccion_boton_consulta">

                                    <!-- <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button"><i class='bx bx-search'></i> Buscar</button> -->

                                    <button onclick="sincronizar_calculo_asistencia_fecha();"
                                        type="button" class="btn btn-primary btn-sm">
                                        <i class="bx bx-rotate-right"></i> Sincronizar Fecha
                                    </button>

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


<div class="modal" id="modal_Recalcular" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Recalcular <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>