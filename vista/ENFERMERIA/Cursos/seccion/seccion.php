<?php include('../../cabeceras/header2.php'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var genero = '';

        $.ajax({
            data: {
                id: id
            },
            url: '../../controlador/seccionC.php?lista=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    console.log(item);
                    genero +=
                        '<tr>' +
                        '<td>' + item.sa_sec_id + '</td>' +
                        '<td><a href="genero_detalle.php?id=' + item.sa_sec_nombre + '"><u>' + item.sa_sec_estado + '</u></a></td>' +
                        '<td></td>' +
                        '</tr>';
                });

                $('#tbl_datos').html(genero);
            }
        });
    }

    function buscar(buscar) {
        var genero = '';

        $.ajax({
            data: {
                buscar: buscar
            },
            url: '../../controlador/seccionC.php?buscar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    console.log(item);
                    genero +=
                        '<tr>' +
                        '<td>' + item.sa_sec_id + '</td>' +
                        '<td><a href="seccion_detalle.php?id=' + item.sa_sec_nombre + '"><u>' + item.sa_sec_estado + '</u></a></td>' +
                        '<td> </td>' +
                        '</tr>';
                });
                $('#tbl_datos').html(genero);
            }
        });
    }

    function limpiar() {
        $('#codigo').val('');
        $('#descripcion').val('');
        $('#id').val('');
        $('#titulo').text('Nueva Sección');
        $('#op').text('Guardar');
    }
</script>
<div class="content">
    <!-- Content Header (Page header) -->
    <br>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12" id="btn_nuevo">
                    <a href="genero_detalle.php" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                    <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_seccion" title="Informe en excel del total de Secciones"><i class="bx bx-file"></i> Total Secciones</a>
                </div>

            </div>

            <br>

            <div>
                <div class="col-sm-8">
                    <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar genero">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbl_datos">

                    </tbody>
                </table>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="titulo">Nueva Sección</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" class="form-control" hidden="">
                Codigo <br>
                <input type="input" name="codigo" id="codigo" class="form-control">
                Descripcion <br>
                <input type="input" name="descripcion" id="descripcion" class="form-control">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="op" onclick="editar_insertar()">Guardar</button>
            </div>
        </div>
    </div>
</div>