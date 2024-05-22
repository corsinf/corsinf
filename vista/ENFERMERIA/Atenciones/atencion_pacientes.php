<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var estudiantes = '';
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/agendamientoC.php?cita_actual=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                // console.log(response);   
                var lista = '';
                $.each(response, function(i, item) {

                    //console.log(item);

                    var tipo_consulta = '';
                    if (item.sa_conp_tipo_consulta == 'consulta') {
                        tipo_consulta = 'Atención Médica';
                    } else {
                        tipo_consulta = item.sa_conp_tipo_consulta;
                    }

                    lista += '<div class="col">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +
                        '<img src="../assets/images/avatars/avatar-1.png" width="110" height="110" class="rounded-circle shadow" alt="">' +
                        '<h5 class="mb-0 mt-5">' + item.nombres + '</h5>' +
                        '<p class="mb-3">' + tipo_consulta.toUpperCase() + '</p>' +
                        '<div class="d-grid"> ' +

                        '<a class="btn btn-outline-success radius-15 mb-1" href="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente&id_consulta=' + item.sa_conp_id + '&tipo_consulta=' + item.sa_conp_tipo_consulta + '&id_ficha=' + item.sa_fice_id + '&id_paciente=' + item.sa_pac_id + '&regresar=atencion_pac' + '"Comenzar title=" Consulta">Realizar Atención Médica</a>' +

                        '<button class="btn btn-outline-primary radius-15 mt-2" onclick="consultar_datos_h(' + item.sa_fice_id + ', \'' + item.nombres + '\')">Historial</button>' +
                        '</div>' +
                        '</div>' +
                        ' </div>' +
                        '</div>' +
                        '</div>';
                });

                $('#citas_actuales').html(lista);
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
                            Atenciones Médicas Pendientes
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="citas_actuales">

        </div>
        <!--end row-->
    </div>
</div>

<div class="modal" id="myModal_historial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Historial de consultas - <b id="title_nombre" class="text-primary"></b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped responsive text-center" id="tbl_consultas_pac" style="width:100%">

                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Tipo de Atención</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script src="../js/ENFERMERIA/consulta_medica.js"></script>