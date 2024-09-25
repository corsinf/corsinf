<script>
    function cambiar_foto() {
        var btn_elegir_foto = $('#btn_elegir_foto')
        var input_elegir_foto = $('#txt_elegir_foto')

        btn_elegir_foto.click(function() {
            input_elegir_foto.click();
        });
    }



    function insertar_editar_foto() {
        var txt_elegir_foto = $('#txt_elegir_foto').val();

        var parametro_foto = {
            'txt_elegir_foto': txt_elegir_foto
        }

        console.log(parametro_foto)
    }
</script>

<img src="../img\usuarios\2043.jpeg" alt="Admin" class="rounded-circle p-1 bg-primary mb-2" width="110">


<!-- Modal para cambiar la foto-->
<div class="modal" id="modal_cambiar_foto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Foto de Perfil</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body needs-validation">
                <div class="text-center">
                    <img src="../img\usuarios\2043.jpeg" alt="Admin" class="img-fluid mb-3 p-2 bg-secondary" width="240">
                </div>
                <div class="mb-4 d-flex justify-content-center">
                    <input type="button" class="btn btn-primary " name="btn_elegir_foto" id="btn_elegir_foto" value="Elegir otra foto">
                    <input type="file" id="txt_elegir_foto" accept="image/*" style="display: none;">
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <input type="button" class="btn btn-success" name="btn_confirmar_foto" id="btn_confirmar_foto" value="Confirmar" onclick="insertar_editar_foto();">
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>