<script type="text/javascript">
    $(document).ready(function() {
        lista_categoria();
        lista_tabla();
    });

    function lista_categoria() {
        $.ajax({
            //data:  {parametros:parametros},
            url: '../controlador/PASANTES/02_ADRIAN/ejemplo.php?categoria=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#ddl_segundo_apellido').html(response)
            }

        });
    }

    function lista_tabla() {
        $.ajax({
            //data:  {parametros:parametros},
            url: '../controlador/PASANTES/02_ADRIAN/ejemplo.php?ingreso=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#tbl_insertar').html(response)
            }

        });
    }

    function enviar_datos() {
        var parametros = 
        {
            'primer_apellido': $('#txt_primer_apellido').val(),
            'segundo_apellido': $('#ddl_segundo_apellido').val(),
            'primer_nombre': $('#txt_primer_nombre').val(),
            'segundo_nombre': $('#txt_segundo_nombre').val(),
            'telefono': $('#txt_telefono').val(),
        }
        $.ajax({
            data:  {data:parametros},
            url: '../controlador/PASANTES/02_ADRIAN/ejemplo.php?add=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response==1){
                    alert('Ingresado')
                }else{
                    alert('No ingresado')
                }
            }

        });
        console.log(parametros);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pasantes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Adrian
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

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="txt_primer_apellido" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_primer_apellido" name="txt_primer_apellido" required>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="ddl_segundo_apellido" class="form-label">Segundo Apellido<label style="color: red;">*</label> </label>
                                        <select class="form-select" name="ddl_segundo_apellido" id="ddl_segundo_apellido" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="txt_primer_nombre" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_primer_nombre" name="txt_primer_nombre" required>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="txt_segundo_nombre" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control form-control-sm" id="txt_segundo_nombre" name="txt_segundo_nombre" required>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <label for="txt_telefono" class="form-label">Telefono <label style="color: red;">*</label> </label>
                                        <input type="number" class="form-control form-control-sm" id="txt_telefono" name="txt_telefono" required>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success btn-sm px-4 mt-3" onclick="enviar_datos();"><i class="bx bx-save"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="py-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Primer Apellido</th>
                                            <th>Segundo Apellido</th>
                                            <th>Primer Nombre</th>
                                            <th>Segundo Nombre</th>
                                            <th>Guardar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_insertar">
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