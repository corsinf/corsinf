<?php

$id = '';

if (isset($_POST['sa_doc_id'])) {
    $id = $_POST['sa_doc_id'];
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var id = '<?php echo $id; ?>';
        //alert(id)

        if (id != '') {
            datos_col(id);
        }
    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/SALUD_INTEGRAL/docentesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {

                $('#sa_doc_id').val(response[0].sa_doc_id);
                $('#sa_doc_primer_apellido').val(response[0].sa_doc_primer_apellido);
                $('#sa_doc_segundo_apellido').val(response[0].sa_doc_segundo_apellido);
                $('#sa_doc_primer_nombre').val(response[0].sa_doc_primer_nombre);
                $('#sa_doc_segundo_nombre').val(response[0].sa_doc_segundo_nombre);

                $('#sa_doc_cedula').val(response[0].sa_doc_cedula);
                select_genero(response[0].sa_doc_sexo, '#sa_doc_sexo');

                $('#sa_doc_fecha_nacimiento').val((response[0].sa_doc_fecha_nacimiento));
                $('#sa_doc_edad').val(calcular_edad_fecha_nacimiento(response[0].sa_doc_fecha_nacimiento));

                $('#sa_doc_correo').val(response[0].sa_doc_correo);
                $('#sa_doc_telefono_1').val(response[0].sa_doc_telefono_1);
                $('#sa_doc_telefono_2').val(response[0].sa_doc_telefono_2);

            }
        });
    }

    function editar_insertar() {
        var sa_doc_id = $('#sa_doc_id').val();
        var sa_doc_primer_apellido = $('#sa_doc_primer_apellido').val();
        var sa_doc_segundo_apellido = $('#sa_doc_segundo_apellido').val();
        var sa_doc_primer_nombre = $('#sa_doc_primer_nombre').val();
        var sa_doc_segundo_nombre = $('#sa_doc_segundo_nombre').val();
        var sa_doc_cedula = $('#sa_doc_cedula').val();
        var sa_doc_sexo = $('#sa_doc_sexo').val();
        var sa_doc_fecha_nacimiento = $('#sa_doc_fecha_nacimiento').val();
        var sa_doc_telefono_1 = $('#sa_doc_telefono_1').val();
        var sa_doc_telefono_2 = $('#sa_doc_telefono_2').val();
        var sa_doc_correo = $('#sa_doc_correo').val();

        var parametros = {
            'sa_doc_id': sa_doc_id,
            'sa_doc_primer_apellido': sa_doc_primer_apellido,
            'sa_doc_segundo_apellido': sa_doc_segundo_apellido,
            'sa_doc_primer_nombre': sa_doc_primer_nombre,
            'sa_doc_segundo_nombre': sa_doc_segundo_nombre,
            'sa_doc_cedula': sa_doc_cedula,
            'sa_doc_sexo': sa_doc_sexo,
            'sa_doc_fecha_nacimiento': sa_doc_fecha_nacimiento,
            'sa_doc_correo': sa_doc_correo,
            'sa_doc_telefono_1': sa_doc_telefono_1,
            'sa_doc_telefono_2': sa_doc_telefono_2,
        };

        if (sa_doc_id == '') {
            if (
                sa_doc_primer_apellido === '' ||
                sa_doc_segundo_apellido === '' ||
                sa_doc_primer_nombre === '' ||
                sa_doc_segundo_nombre === '' ||
                sa_doc_cedula === '' ||
                sa_doc_sexo == null ||
                sa_doc_fecha_nacimiento === '' ||
                validar_email(sa_doc_correo) == false ||
                sa_doc_telefono_1 === '' ||
                sa_doc_telefono_2 === ''
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Asegurese de llenar todos los campos',
                })
            } else {
                //console.log(parametros);
                insertar(parametros)
            }
        } else {
            if (
                sa_doc_primer_apellido === '' ||
                sa_doc_segundo_apellido === '' ||
                sa_doc_primer_nombre === '' ||
                sa_doc_segundo_nombre === '' ||
                sa_doc_cedula === '' ||
                sa_doc_sexo == null ||
                sa_doc_fecha_nacimiento === '' ||
                validar_email(sa_doc_correo) == false ||
                sa_doc_telefono_1 === '' ||
                sa_doc_telefono_2 === ''
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Asegurese de llenar todos los campos',
                })
            } else {
                //console.log(parametros);
                insertar(parametros);
            }
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/docentesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=docentes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada', 'warning');
                }
            }
        });
    }

    function delete_datos() {
        var id = '<?php echo $id; ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/SALUD_INTEGRAL/docentesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=docentes';
                    });
                    //location.href = '../vista/inicio.php?mod=7&acc=docentes';
                }
            }
        });
    }

    /////////////////////////////////////////////////////////////////////
    function edad_normal(fecha_nacimiento) {
        $('#sa_doc_edad').val(calcular_edad_fecha_nacimiento(fecha_nacimiento));
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
                            <?php
                            if ($id == '') {
                                echo 'Registrar Docentes';
                            } else {
                                echo 'Modificar Docentes';
                            }
                            ?>
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
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($id == '') {
                                    echo 'Registrar Docentes';
                                } else {
                                    echo 'Modificar Docentes';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=7&acc=docentes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        
                        <hr>

                        <form action="" method="post">

                            <input type="hidden" id="sa_doc_id" name="sa_doc_id">

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_primer_apellido" name="sa_doc_primer_apellido">
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Segundo Apellido <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_segundo_apellido" name="sa_doc_segundo_apellido">
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_primer_nombre" name="sa_doc_primer_nombre">
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_segundo_nombre" name="sa_doc_segundo_nombre">
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Cédula de Identidad <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_cedula" name="sa_doc_cedula" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Sexo <label style="color: red;">*</label> </label>
                                    <select class="form-select form-select-sm" id="sa_doc_sexo" name="sa_doc_sexo">
                                        <option selected disabled>-- Seleccione --</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Masculino">Masculino</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Fecha de Nacimiento <label style="color: red;">*</label> </label>
                                    <input type="date" class="form-control form-control-sm" id="sa_doc_fecha_nacimiento" name="sa_doc_fecha_nacimiento" onchange="edad_normal(this.value);">
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Edad <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_edad" name="sa_doc_edad" readonly>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Teléfono 1 <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_telefono_1" name="sa_doc_telefono_1">
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Teléfono 2 <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_doc_telefono_2" name="sa_doc_telefono_2">
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Correo <label style="color: red;">*</label> </label>
                                    <input type="email" class="form-control form-control-sm" id="sa_doc_correo" name="sa_doc_correo">
                                </div>
                            </div>

                            <div class="modal-footer pt-4">
                                <?php if ($id == '') { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>