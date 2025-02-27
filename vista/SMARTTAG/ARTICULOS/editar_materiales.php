<script type="text/javascript">
    $(document).ready(function() {
        materiales();
        materiales_inactivos();
        //  // restriccion();
        // Lista_clientes();
        // Lista_procesos();

    });

    function materiales() {
        $.ajax({
            // data:  {id,id},
            url: '../controlador/materialesC.php?lista=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#tbl_body').html(response);
            }

        });
    }

    function materiales_inactivos(query = '') {
        $.ajax({
            data: {
                query: query
            },
            url: '../controlador/materialesC.php?inactivo=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#materiales_ina').html(response);
                }
            }

        });
    }

    function editar(id) {
        var nom = $('#txt_nombre_' + id).val();
        var parametros = {
            'id': id,
            'nom': nom,
        }
        $.ajax({
            data: {
                parametros,
                parametros
            },
            url: '../controlador/materialesC.php?editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Registro editado.', 'success');
                    materiales();
                    materiales_inactivos();
                } else {
                    Swal.fire('', 'UPs Algo salio mal.', 'error');
                }
            }

        });

    }

    function eliminar(id) {
        $.ajax({
            data: {
                eliminar,
                eliminar
            },
            url: '../controlador/materialesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    materiales();
                    materiales_inactivos();
                }
            }

        });

    }

    function eliminar(id) {
        Swal.fire({
            title: 'Quiere eliminar este registro?',
            text: "Esta seguro de eliminar este registro!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    data: {
                        id,
                        id
                    },
                    url: '../controlador/materialesC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Material eliminado', 'success');
                            materiales();
                            materiales_inactivos();
                        } else if (response == -2) {
                            Swal.fire({
                                title: 'Este material esta asignada aun producto y no se podra eliminar',
                                text: "Desea inhabilitado a esta categoria?",
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Si!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    inhabilitar_usuario(id);
                                }
                            })
                            // Swal.fire('','El Usuario esta ligado a uno o varios registros y no se podra eliminar.','error')
                        } else {
                            Swal.fire('', 'No se pudo eliminar', 'info');
                        }
                    }

                });
            }
        });

    }

    function inhabilitar_usuario(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/materialesC.php?estado=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    materiales();
                    materiales_inactivos();
                    Swal.fire('El material se a inhabilitado!', 'El material no podra ser usado en futuras asignaciones', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function Activar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/materialesC.php?activar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    materiales();
                    materiales_inactivos();
                    Swal.fire('El material  se a habilitado!', '', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function add_materiales() {
        var nombre = $('#txt_nombre').val();
        if (nombre == '') {
            Swal.fire('', 'Llene el campo de nombre', 'info');
            return false;
        }
        $.ajax({
            data: {
                nombre: nombre
            },
            url: '../controlador/materialesC.php?add=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    materiales();
                    $('#txt_nombre').val('');
                    materiales_inactivos();
                    Swal.fire('Material  Registrado!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El nombre del material ya esta registrado', 'error');
                }

            }

        });

    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Materiales</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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


                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="materiales-tab" data-bs-toggle="pill" href="#materiales" role="tab" aria-controls="materiales" aria-selected="true">MATERIALES</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="materiales_ina-tab" data-bs-toggle="pill" href="#materiales_ina" role="tab" aria-controls="materiales_ina" aria-selected="false">MATERIALES INACTIVAS</a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="materiales" role="tabpanel" aria-labelledby="materiales-tab">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre de materiales</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="txt_nombre" id="txt_nombre" class="form-control-sm form-control">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" type="button" onclick="add_materiales()"><i class="fa fa-save"></i> Nuevo</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody id="tbl_body">
                                                <!-- Dynamic content goes here -->
                                            </tbody>
                                        </table>
                                    </div><!-- /.tab-pane -->

                                    <div class="tab-pane" id="materiales_ina" role="tabpanel" aria-labelledby="materiales_ina-tab">
                                        <!-- Inactive materiales content goes here -->
                                    </div><!-- /.tab-pane -->

                                </div><!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <label for="">Blank <label class="text-danger">*</label></label>
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