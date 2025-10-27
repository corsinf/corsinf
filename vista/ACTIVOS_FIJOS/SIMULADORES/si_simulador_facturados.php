<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    function cargarRFIDS() {
        let url_simuladorC = '../controlador/ACTIVOS_FIJOS/SIMULADORES/si_simulador_facturadosC.php?buscar=true';
        cargar_select2_url('ddl_rfids', url_simuladorC);
    }

    // Asegurarte de llamarla cuando el DOM ya está listo
    document.addEventListener("DOMContentLoaded", function() {
        cargarRFIDS();
        console.log("cargando rfid");
    });

    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById('txt_tags_encontrados');

        // Función para emitir beep
        function playBeep(duration = 120, frequency = 1000, volume = 0.1) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                const ctx = new AudioContext();
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();

                oscillator.type = 'sine';
                oscillator.frequency.value = frequency;
                gainNode.gain.value = volume;

                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);

                oscillator.start();

                setTimeout(() => {
                    oscillator.stop();
                    ctx.close();
                }, duration);
            } catch (e) {
                console.warn("Audio API no disponible:", e);
            }
        }

        // Función para mostrar mensaje con SweetAlert
        function showSuccessToast(message = 'Lectura realizada correctamente') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1200,
                timerProgressBar: true
            });
        }

        let lastTrigger = 0;
        const COOLDOWN_MS = 300;

        // Detectar Enter o Ctrl+Enter
        textarea.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter') {
                const now = Date.now();
                if (now - lastTrigger < COOLDOWN_MS) return;
                lastTrigger = now;

                setTimeout(() => {
                    playBeep();

                    // Obtener la última línea del textarea
                    const lines = textarea.value.split(/\r?\n/).filter(l => l.trim() !== "");
                    const lastLine = lines[lines.length - 1]; // Última línea con contenido

                    if (lastLine) {
                        // Llamar a la función para buscar por SKU
                        buscar_articulo_sku(lastLine);
                    }

                    // Mensaje de éxito
                    showSuccessToast(ev.ctrlKey ? 'Ctrl + Enter detectado' : 'Enter detectado: lectura realizada');
                }, 10);
            }
        });



        function buscar_articulo_sku(id) {
            $.ajax({
                data: {
                    search_value: id
                },
                url: '../controlador/ACTIVOS_FIJOS/SIMULADORES/si_simulador_facturadosC.php?lista_cr=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    // Revisar si se recibió data
                    if (!response || response.length === 0) {
                        // No existe el tag
                        Swal.fire({
                            icon: 'error',
                            title: 'Tag no encontrado',
                            text: `El tag "${id}" no existe en el sistema.`,
                            timer: 2000,
                            showConfirmButton: true, // ahora se muestra botón
                            allowOutsideClick: false, // evita cerrar clic fuera
                            allowEscapeKey: true
                        });
                        return;
                    }

                    // Si hay data
                    let data = response[0];

                    Swal.fire({
                        icon: 'success',
                        title: 'Tag encontrado',
                        html: `<b>Tag:</b> ${data.RFID}<br><b>Descripción:</b> ${data.nom}`,
                        timer: 2000,
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: true
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX: ", status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo consultar el tag.',
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                }
            });
        }

    });
</script>



<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Buscador de Tags</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Buscador de Tags
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


                        <div class="row">
                            <!-- Columna 1: Tags encontrados -->
                            <div class="col-sm-6">
                                <div class="card border-primary">
                                    <div class="card-header text-primary">
                                        Tags encontrados
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="txt_tags_encontrados" class="form-label">Tags encontrados</label>
                                            <!-- mantuve el orden: type -> class -> name -> id -> maxlength -->
                                            <textarea type="text" class="form-control form-control-sm" name="txt_tags_encontrados" id="txt_tags_encontrados" maxlength="500" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna 2: Tags del sistema -->
                            <div class="col-sm-6">
                                <div class="card border-primary">
                                    <div class="card-header text-primary">
                                        Tags del Sistema
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="ddl_rfids" class="form-label">RFIDS </label>
                                            <select class="form-select form-select-sm select2-validation" id="ddl_rfids" name="ddl_rfids">
                                                <option selected disabled>-- Seleccione --</option>
                                            </select>
                                            <label class="error" style="display: none;" for="ddl_rfids"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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