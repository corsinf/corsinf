<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
        consultar_datos_1();
        consultar_datos_2();
        //examen()
    });

    function consultar_datos(id = '') {
        $('#tbl_t_examen').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/cat_t_consultasC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: 't_con_id'
                },
                {
                    data: 't_con_descripcion'
                },
                {
                    data: 't_con_fecha_creacion'
                },

            ],
            order: [
                [1, 'asc']
            ],
        });
    }

    function consultar_datos_1(id = '') {
        $('#tbl_examen').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/cat_examenesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'ex_id'
                },
                {
                    data: 'ex_descripcion'
                },
                {
                    data: 'ex_name_input'
                },

            ],
            order: [
                [1, 'asc']
            ],
        });
    }

    function consultar_datos_2() {
        $('#tbl_itee').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/interm_t_examen_examenC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'itee_id'
                },
                {
                    data: 't_ex_descripcion'
                },
                {
                    data: 'ex_descripcion'
                },

            ],
            order: [
                [1, 'asc']
            ],
        });
    }

    

    function examen() {
        $.ajax({
            data: {
                id: ''
            },
            url: '../controlador/RED_CONSULTORIOS/cat_examenesC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                console.log(response);

            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Configuración</div>
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
                            <h5 class="mb-0 text-primary">Configuración</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registrar_seccion" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                        </div>

                                    </div>

                                    <br>

                                    <h2>Tipos de Consultas</h2>

                                    <div class="table-responsive">
                                        <table class="table table-striped responsive" id="tbl_t_examen" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Nombre</th>
                                                    <th>Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>

                                    <h2>Triage</h2>

                                    <div class="table-responsive">
                                        <table class="table table-striped responsive" id="tbl_examen" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Nombre</th>
                                                    <th>Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>

                                    <h2>Tipo de Consulta y Triage</h2>

                                    <div class="table-responsive">
                                        <table class="table table-striped" id="tbl_itee" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Nombre</th>
                                                    <th>Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </div><!-- /.container-fluid -->
                            </section>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
