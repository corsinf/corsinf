<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_cambiar_foto(<?= $id ?>);
        <?php } ?>

    });


    function cargar_datos_cambiar_foto(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_cambiar_foto').html(response);
            }
        });
    }

    function cargar_datos_modal_cambiar_foto(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_cambiar_foto_id').val(response[0]._id);
                $('#txt_ruta_guardada_cambiar_foto').val(response[0].th_cambiar_foto);

            }
        });
    }



    function cambiar_foto() {
        var btn_elegir_foto = $('#btn_elegir_foto')
        var input_elegir_foto = $('#txt_elegir_foto')

        btn_elegir_foto.click(function() {
            input_elegir_foto.click();
        });
    }



   
    function insertar_editar_cambiar_foto() {
    var form_data = new FormData(document.getElementById("form_cambiar_foto"));
    var txt_id_cambiar_foto = $('#txt_cambiar_foto_id').val();

    if ($('#txt_copia_cambiar_foto').val() === '' && txt_id_cambiar_foto != '') {
        var txt_copia_cambiar_foto = $('#txt_ruta_guardada_cambiar_foto').val();
        $('#txt_copia_cambiar_foto').rules("remove", "required");
    } else {
        var txt_copia_cambiar_foto = $('#txt_copia_cambiar_foto').val();
        $('#txt_copia_cambiar_foto').rules("add", { required: true });
    }

    if ($("#form_cambiar_foto").valid()) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_cambiar_fotoC.php?insertar=true',
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response == -1) {
                    mostrarAlerta('Algo extraño ha ocurrido, intente más tarde.', 'error');
                } else if (response == -2) {
                    mostrarAlerta('Asegúrese de que el archivo subido sea un PDF.', 'error');
                } else if (response == 1) {
                    mostrarAlerta('Operación realizada con éxito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_cambiar_foto(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_cambiar_foto();
                    $('#modal_agregar_cambiar_foto').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en insertar_editar_cambiar_foto:', error);
                alert('Error al insertar/editar la foto. Intente nuevamente.');
            }
        });
    }
}

        //console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        //return;

      
    //Funcion para editar el registro de referencias laborales
    function abrir_modal_cambiar_foto(id) {
        cargar_datos_modal_cambiar_foto(id);

        $('#modal_agregar_cambiar_foto').modal('show');

        $('#lbl_titulo_cambiar_foto').html('Editar su referencia');
        $('#btn_guardar_cambiar_foto').html('Guardar');

    }

    function delete_datos_cambiar_foto() {
        var id = $('#txt_cambiar_foto_id').val();
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
                eliminar_cambiar_foto(id);
            }
        })
    }

    function eliminar_cambiar_foto(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_cambiar_fotoC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_cambiar_foto(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_cambiar_foto();
                    $('#modal_agregar_cambiar_foto').modal('hide');
                }
            }
        });
    }
</script>

<div id="pnl_cambiar_foto">

</div>

<!-- Modal para cambiar la foto-->
<div class="modal" id="modal_agregar_cambiar_foto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h6><small class="text-body-secondary fw-bold" id="lbl_titulo_cambiar_foto">Foto de Perfil</small></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form id="form_cambiar_foto" enctype="multipart/form-data" method="post" style="width: inherit;">

                <div class="modal-body">

                    <input type="hidden" name="txt_cambiar_foto_id" id="txt_cambiar_foto_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_copia_cambiar_foto" class="form-label form-label-sm">Foto de Perfil </label>
                            <input type="file" class="form-control form-control-sm" name="txt_copia_cambiar_foto" id="txt_copia_cambiar_foto" accept=".pdf">
                            <!-- <div class="pt-2"></div> -->
                            <input type="text" class="form-control form-control-sm" name="txt_ruta_guardada_cambiar_foto" id="txt_ruta_guardada_cambiar_foto" hidden>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_cambiar_foto" onclick="insertar_editar_cambiar_foto();">Agregar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion" onclick="delete_datos_cambiar_foto();">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_copia_cambiar_foto');      

    }
</script>
