
<script type="text/javascript">
    $(document).ready(function() {
        categorias();
        categorias_inactivos();
        //  // restriccion();
        // Lista_clientes();
        // Lista_procesos();

    });

    function categorias() {
        $.ajax({
            // data:  {id,id},
            url: '../controlador/categoriasC.php?lista=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#tbl_body').html(response);
            }

        });
    }

    function categorias_inactivos(query = '') {
        $.ajax({
            data: {
                query: query
            },
            url: '../controlador/categoriasC.php?inactivo=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#categorias_ina').html(response);
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
            url: '../controlador/categoriasC.php?editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Registro editado.', 'success');
                    categorias();
                    categorias_inactivos();
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
            url: '../controlador/categoriasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    categorias();
                    categorias_inactivos();
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
                    url: '../controlador/categoriasC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Registro eliminado', 'success');
                            categorias();
                            categorias_inactivos();
                        } else if (response == -2) {
                            Swal.fire({
                                title: 'Este registro esta asignada aun producto y no se podra eliminar',
                                text: "Desea inhabilitar a esta registro?",
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
                            Swal.fire('', 'Eno se pudo eliminar', 'info');
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
            url: '../controlador/categoriasC.php?estado=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    categorias();
                    categorias_inactivos();
                    Swal.fire('El registro se a inhabilitado!', 'El cliente no podra ser seleccionado en futuras compras o ventas', 'success');

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
            url: '../controlador/categoriasC.php?activar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    categorias();
                    categorias_inactivos();
                    Swal.fire('El registro  se a habilitado!', '', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function add_categoria() {
        var nombre = $('#txt_nombre').val();
        if (nombre == '') {
            Swal.fire('', 'Llene el campo de nombre', 'info');
            return false;
        }
        $.ajax({
            data: {
                nombre: nombre
            },
            url: '../controlador/categoriasC.php?add=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    categorias();
                    $('#txt_nombre').val('');
                    categorias_inactivos();
                    Swal.fire('Item  Registrado!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El nombre del item ya esta registrada', 'error');
                }

            }

        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">TIPO DE JOYA</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Editar joya
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
                                        <a class="nav-link active" id="categorias-tab" data-bs-toggle="pill" href="#categorias" role="tab" aria-controls="categorias" aria-selected="true">TIPO DE JOYAS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="categorias_ina-tab" data-bs-toggle="pill" href="#categorias_ina" role="tab" aria-controls="categorias_ina" aria-selected="false">TIPO DE JOYAS INACTIVAS</a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del tipo de joyas</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="txt_nombre" id="txt_nombre" class="form-control-sm form-control">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" type="button" onclick="add_categoria()"><i class="fa fa-save"></i> Nuevo</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody id="tbl_body">
                                                <!-- Dynamic content goes here -->
                                            </tbody>
                                        </table>
                                    </div><!-- /.tab-pane -->

                                    <div class="tab-pane" id="categorias_ina" role="tabpanel" aria-labelledby="categorias_ina-tab">
                                        <!-- Inactive categories content goes here -->
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