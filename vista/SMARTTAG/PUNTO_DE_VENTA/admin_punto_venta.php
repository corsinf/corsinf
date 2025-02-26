<script type="text/javascript">
    $(document).ready(function() {
        bodegas();
        autocoplet_bodegas();
        autocoplet_punto_venta();
        bodegas_asignadas();
        autocoplet_usuario();
        usuario_punto();
        // $('#ddl_puntos_asignar').val([1,2,3]).change();
    });

    function autocoplet_bodegas() {
        $('#ddl_bodega_multi').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/admin_punto_ventaC.php?bodegas_multi=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function autocoplet_usuario() {
        $('#ddl_usuario').select2({
            placeholder: 'Seleccione una usuario',
            width: '90%',
            ajax: {
                url: '../controlador/admin_punto_ventaC.php?usuario=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }


    function autocoplet_punto_venta() {
        $('#ddl_puntos_asignar').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/admin_punto_ventaC.php?puntos_asignar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function bodegas() {
        $.ajax({
            // data:  {id,id},
            url: '../controlador/admin_punto_ventaC.php?lista=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#tbl_body').html(response);
            }

        });
    }

    function usuario_punto() {
        $.ajax({
            // data:  {id,id},
            url: '../controlador/admin_punto_ventaC.php?usuarios_punto=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#usuario_punto').html(response);
            }

        });
    }


    function bodegas_asignadas() {
        $.ajax({
            // data:  {id,id},
            url: '../controlador/admin_punto_ventaC.php?bodegas_asignadas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#tbl_asignar_bodegas').html(response);
            }

        });
    }

    function add_categoria() {
        var nombre = $('#txt_nombre').val();
        var num = $('#txt_num').val();
        if (nombre == '' || num == '') {
            Swal.fire('', 'Llene los campos', 'info');
            return false;
        }
        $.ajax({
            data: {
                nombre: nombre,
                num: num
            },
            url: '../controlador/admin_punto_ventaC.php?add=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    bodegas();
                    bodegas_asignadas();
                    $('#txt_nombre').val('');
                    Swal.fire('La Punto de venta  se a Registrado!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El nombre o numero de punto de venta ya esta registrada', 'error');
                }

            }

        });

    }


    function add_usuario() {
        var usuario = $('#ddl_usuario').val();
        var punto = $('#ddl_puntos_asignar').val();
        if (usuario == '' || punto == '') {
            Swal.fire('', 'Seleccione todos los campos', 'info');
            return false;
        }
        $.ajax({
            data: {
                usuario: usuario,
                punto: punto
            },
            url: '../controlador/admin_punto_ventaC.php?add_usu=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {

                    usuario_punto();
                    Swal.fire('Usuario agregado a Punto de venta!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El usuario ya esta registrada', 'error');
                }

            }

        });

    }

    function update_punto(id) {
        var nombre = $('#txt_nombre_' + id).val();
        if (nombre == '') {
            Swal.fire('', 'Llene los campos', 'info');
            return false;
        }
        $.ajax({
            data: {
                nombre: nombre,
                id: id
            },
            url: '../controlador/admin_punto_ventaC.php?updtate_punto=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    bodegas();
                    // $('#txt_nombre').val('');
                    bodegas_asignadas();
                    usuario_punto();
                    Swal.fire('Nombre de punto de venta editado!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El nombre o numero de punto de venta ya esta registrada', 'error');
                }

            }

        });

    }

    function editar_bode(id) {
        var bodegas = $('#ddl_bodega_multi_' + id).val();
        if (bodegas == '') {
            bodegas = '';
        }
        $.ajax({
            data: {
                bodegas: bodegas,
                id: id
            },
            url: '../controlador/admin_punto_ventaC.php?updtate_bodegas=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    bodegas_asignadas();
                    usuario_punto();
                    Swal.fire('Bodegas asignadas!', '', 'success');

                } else if (response == -2) {
                    Swal.fire('', 'El nombre o numero de punto de venta ya esta registrada', 'error');
                }

            }

        });

    }

    function eliminar_bode(id) {
        Swal.fire({
            title: 'Quiere eliminar este punto de venta y las bodegas asignadas?',
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
                    url: '../controlador/admin_punto_ventaC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Punto de venta Eliminado', 'success');
                            bodegas_asignadas();
                            bodegas();
                            usuario_punto();
                        } else if (response == -2) {
                            Swal.fire({
                                title: 'Este Punto de venta esta ligado a un usuario',
                                text: "Desea inhabilitado este punto de venta?",
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
            url: '../controlador/admin_punto_ventaC.php?estado_punto=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {

                    bodegas();
                    bodegas_asignadas();
                    Swal.fire('El punto de venta se a inabilitado!', 'Este punto de venta no podra ser usado', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function eliminar_usu(id) {
        Swal.fire({
            title: 'Quiere eliminar este usuario del punto de venta?',
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
                    url: '../controlador/admin_punto_ventaC.php?eliminar_usu=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Punto de venta Eliminado', 'success');
                            usuario_punto();
                        } else {
                            Swal.fire('', 'Eno se pudo eliminar', 'info');
                        }
                    }

                });
            }
        });

    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Administrador Punto de venta</div>
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
                        <section class="content">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#punto" data-bs-toggle="tab">Nuevo punto de venta</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#bodega" data-bs-toggle="tab">Añadir bodegas a punto de venta</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#add_usuario" data-bs-toggle="tab">Añadir usuario a punto de venta</a>
                                            </li>
                                        </ul>
                                    </div> <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="tab-content">
                                            <!-- TAB: Nuevo Punto de Venta -->
                                            <div class="tab-pane fade show active" id="punto">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre de bodega</th>
                                                            <th>Nombre</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="txt_num" class="form-control">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="txt_nombre" class="form-control">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" type="button" onclick="add_categoria()">
                                                                    <i class="fa fa-save"></i> Nuevo
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /#punto -->

                                            <!-- TAB: Añadir Bodegas -->
                                            <div class="tab-pane fade" id="bodega">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>PUNTO DE VENTA</th>
                                                                        <th>BODEGAS ASIGNADAS</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tbl_asignar_bodegas"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- /#bodega -->

                                            <!-- TAB: Añadir Usuario -->
                                            <div class="tab-pane fade" id="add_usuario">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="ddl_puntos_asignar" class="form-label"><b>Seleccione punto de venta</b></label>
                                                        <select id="ddl_puntos_asignar" class="form-select">
                                                            <option value="">Seleccione punto de venta</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="ddl_usuario" class="form-label"><b>Seleccione Usuario</b></label>
                                                        <select id="ddl_usuario" class="form-select">
                                                            <option value="">Seleccione Usuario</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button class="btn btn-primary btn-sm w-100" type="button" onclick="add_usuario()">
                                                            <i class="fa fa-save"></i> Añadir
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12" id="usuario_punto"></div>
                                                </div>
                                            </div> <!-- /#add_usuario -->

                                        </div> <!-- /.tab-content -->
                                    </div> <!-- /.card-body -->
                                </div> <!-- /.card -->
                            </div> <!-- /.container-fluid -->
                        </section>

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