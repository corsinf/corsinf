<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pasantes</title>
</head>
<body>
    <script type="text/javascript">
        $(document).ready(function() {
            lista_categorias()
            lista_Ingresos()
        });

    function enviar_datos(){
        var primer_nombre = $('#txt_primer_nombre').val();
        var segundo_nombre = $('#txt_segundo_nombre').val();
        var select_costo = $('#categoriaEspacio').val();
        var parametros = {
            'primer_nombre': primer_nombre,
            'segundo_nombre': segundo_nombre,
            'costo': select_costo
        }

        $.ajax({
            data: {data:parametros,data1: 'hola'},
            url: '../controlador/COWORKING/claseEjemplo.php?add=true',
            type: 'post',
            dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#tbl_body').html(response);
                }
        });
    }
    
    function lista_categorias()
    {
        
        $.ajax({
            // data:  {parametros:parametros},
            url:   '../controlador/COWORKING/claseEjemplo.php?categoria=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) {  
                    console.log(response);
                    $('#categoriaEspacio').html(response);
            } 
        });
    }

    function lista_Ingresos()
    {    
        $.ajax({
            // data:  {parametros:parametros},
            url:   '../controlador/COWORKING/claseEjemplo.php?listaIngresos=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) {  
                    console.log(response);
                    $('#tbl_body').html(response);
            } 
        });
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
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sebasti&aacute;n</li>
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
                                    <div class="col-sm-12" id="btn_nuevo"></div>
                                </div>
                            </div>

                            <section class="content pt-2">
                                <div class="container-fluid">
                                    <div class="row pt-3">
                                        <table class="table">
                                            <tbody id="tbl_body">

                                            </tbody>
                                        </table>
                                        <div class="col-md-6">
                                            <label for="select_costo" class="form-label">Costo <label style="color: red;">*</label></label>
                                            <select class="form-control form-control-sm" id="categoriaEspacio" name="select_costo">
                                                <option value="" disabled selected>Selecciona un costo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <label for="txt_primer_nombre" class="form-label">Primer Nombre <label style="color: red;">*</label></label>
                                            <input type="text" class="form-control form-control-sm" id="txt_primer_nombre" name="txt_primer_nombre">
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <label for="txt_segundo_nombre" class="form-label">Segundo Nombre <label style="color: red;">*</label></label>
                                            <input type="text" class="form-control form-control-sm" id="txt_segundo_nombre" name="txt_segundo_nombre">
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-success btn-sm px-4" onclick="enviar_datos();"><i class="bx bx-save"></i> Guardar</button>
                                            </div>
                                        </div>
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
</body>
</html>
