<script>
    $(document).ready(function() {
        // lista_kit();
    });

    function lista_kit() {
        var parametros = {
            'activo': $('#txt_id').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/articulosC.php?lista_kit=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                // console.log(response);
                kit = '';
                $.each(response, function(i, item) {
                    kit += '<tr>' +
                        '<td>' + item.DESCRIPT + '</td>' +
                        '<td>' + item.CARACTERISTICA + '</td>' +
                        '<td>' + item.OBSERVACION + '</td>' +
                        '<td><button class="btn btn-danger btn-sm" onclick="eliminar_kit(' + item.id_plantilla + ')"><i class="bx bx-trash"></i></button></td>' +
                        '</tr>';

                });
                $('#tbl_kit').html(kit);
            }
        });
    }

    function eliminar_kit(id) {
        Swal.fire({
            title: 'Eliminar de kit?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                kit_eliminar(id);
            }
        })

    }

    function kit_eliminar(id) {
        var parametros = {
            'id': id,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/articulosC.php?delete_kit=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    lista_kit();
                }
            }
        });
    }

    function guardar_kit() {

        if ($('#txt_nombre_kit').val() == '' || $('#txt_identificador_kit').val() == '' || $('#txt_observacion_kit').val() == '') {
            Swal.fire('Llene todo los campos', '', 'info');
            return false;
        }
        var parametros = {
            'activo': $('#txt_id_A').val(),
            'nombre': $('#txt_nombre_kit').val(),
            'identificador': $('#txt_identificador_kit').val(),
            'observacion': $('#txt_observacion_kit').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/articulosC.php?guardar_kit=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Elemento añadido a kit', '', 'success');
                    $('#txt_nombre_kit').val('');
                    $('#txt_identificador_kit').val('');
                    $('#txt_observacion_kit').val('');
                    lista_kit();
                }
            }
        });
    }
</script>



<div class="tab-pane fade" id="tab_detalle_kit_interno" role="tabpanel">
    <table class="table table-striped table-bordered dataTable">
        <thead>
            <th>Nombre</th>
            <th>Identificador</th>
            <th>Observacion</th>
            <th></th>
        </thead>
        <tr>
            <td><input type="text" class="form-control form-control-sm" name="txt_nombre_kit" id="txt_nombre_kit"></td>
            <td><input type="text" class="form-control form-control-sm" name="txt_identificador_kit" id="txt_identificador_kit"></td>
            <td><input type="text" class="form-control form-control-sm" name="txt_observacion_kit" id="txt_observacion_kit"></td>
            <td><button class="btn btn-sm btn-primary" onclick="guardar_kit()">Añadir</button></td>
        </tr>
        <tbody id="tbl_kit">

        </tbody>
    </table>
</div>