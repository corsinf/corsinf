<style>
    /* ---------- Grid de cards ---------- */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 14px;
    }

    /* ---------- Card base ---------- */
    .media-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        background: #1a1a2e;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        transition: transform .18s, box-shadow .18s;
        cursor: pointer;
    }

    .media-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .22);
    }

    /* ---------- Thumbnail ---------- */
    .media-thumb {
        width: 100%;
        height: 130px;
        object-fit: cover;
        display: block;
        background: #2d2d44;
    }

    /* ---------- Overlay de acciones ---------- */
    .media-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 0;
        transition: opacity .18s;
    }

    .media-card:hover .media-overlay {
        opacity: 1;
    }

    /* ---------- Footer de card ---------- */
    .media-footer {
        padding: 6px 8px;
        background: #fff;
        border-top: 1px solid #eee;
    }

    .media-footer small {
        font-size: .72rem;
        color: #666;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ---------- Badge principal ---------- */
    .badge-principal {
        position: absolute;
        top: 7px;
        left: 7px;
        font-size: .65rem;
        padding: 3px 7px;
        border-radius: 20px;
        background: #f59e0b;
        color: #fff;
        font-weight: 600;
        letter-spacing: .3px;
        pointer-events: none;
        z-index: 2;
    }

    /* ---------- Video thumb placeholder ---------- */
    .video-thumb-placeholder {
        width: 100%;
        height: 130px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #94a3b8;
    }

    .video-thumb-placeholder i {
        font-size: 2.2rem;
        color: #64748b;
    }

    .video-thumb-placeholder span {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ---------- Sección separadora ---------- */
    .media-section-title {
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }

    .media-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    /* ---------- Empty state ---------- */
    .empty-media {
        text-align: center;
        padding: 30px 0;
        color: #9ca3af;
    }

    .empty-media i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 8px;
    }

    .empty-media p {
        font-size: .85rem;
        margin: 0;
    }

    /* ---------- Drag-to-preview video ---------- */
    .modal-video-player {
        width: 100%;
        max-height: 420px;
        border-radius: 8px;
        background: #000;
    }

    /* ---------- Upload drop-zone ---------- */
    .drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color .18s, background .18s;
        background: #f8fafc;
    }

    .drop-zone:hover,
    .drop-zone.drag-over {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .drop-zone i {
        font-size: 2rem;
        color: #94a3b8;
        display: block;
        margin-bottom: 6px;
    }

    .drop-zone p {
        margin: 0;
        font-size: .82rem;
        color: #64748b;
    }

    /* ---------- Preview carga ---------- */
    #upload_preview_wrap {
        display: none;
        margin-top: 12px;
    }

    #upload_preview_img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
    }

    #upload_preview_vid {
        width: 100%;
        max-height: 200px;
        border-radius: 8px;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-3 pt-1">
    <div>
        <button class="btn btn-success btn-sm" onclick="abrir_modal_media()">
            <i class="bx bx-plus"></i> Nuevo archivo
        </button>
    </div>
    <small class="text-muted" id="lbl_contador_media"></small>
</div>

<!-- Sección FOTOS -->
<div id="seccion_fotos" class="mb-4">
    <div class="media-section-title">
        <i class="bx bx-image-alt"></i> Fotos
    </div>
    <div id="grid_fotos" class="media-grid">
        <div class="empty-media">
            <i class="bx bx-image"></i>
            <p>Sin imágenes cargadas</p>
        </div>
    </div>
</div>

<!-- Sección VIDEOS -->
<div id="seccion_videos">
    <div class="media-section-title">
        <i class="bx bx-video"></i> Videos
    </div>
    <div id="grid_videos" class="media-grid">
        <div class="empty-media">
            <i class="bx bx-video-off"></i>
            <p>Sin videos cargados</p>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_media" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header">
                <h6 class="modal-title fw-bold">
                    <i class="bx bx-upload me-1"></i> Subir imagen o video
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form_media" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_espacio" value="<?= $_id ?>">

                    <!-- Drop zone -->
                    <div class="drop-zone" id="drop_zone" onclick="$('#archivo_media').click()">
                        <i class="bx bx-cloud-upload"></i>
                        <p class="fw-semibold mb-1">Haga clic o arrastre su archivo aquí</p>
                        <p>Imágenes: JPG, PNG, WEBP · máx. 5 MB</p>
                        <p>Videos: MP4, WEBM · máx. 50 MB</p>
                    </div>
                    <input type="file" id="archivo_media" name="archivo"
                        accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.ogv"
                        style="display:none;">

                    <!-- Preview -->
                    <div id="upload_preview_wrap">
                        <hr class="my-3">
                        <div class="text-center">
                            <img id="upload_preview_img" src="" alt="" style="display:none;">
                            <video id="upload_preview_vid" controls style="display:none;"></video>
                        </div>
                        <p id="upload_preview_nombre" class="text-center mt-2 mb-0">
                            <small class="text-muted"></small>
                        </p>
                    </div>

                    <!-- Barra de progreso -->
                    <div id="upload_progress_wrap" style="display:none; margin-top:14px;">
                        <div class="progress" style="height:6px; border-radius:10px;">
                            <div id="upload_progress_bar"
                                class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                style="width:0%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block text-center">Subiendo…</small>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <small class="text-muted" id="lbl_tipo_archivo"></small>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-sm px-4"
                            id="btn_guardar_media" onclick="guardar_media()" disabled>
                            <i class="bx bx-save me-1"></i>Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_preview_img" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <span id="lbl_img_nombre" class="text-white-50 small"></span>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="img_preview_full"
                    src=""
                    alt=""
                    style="max-width:100%; max-height:70vh; object-fit:contain; border-radius:8px;">
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button class="btn btn-warning btn-sm" id="btn_set_principal_preview"
                    onclick="set_principal_desde_preview()">
                    <i class="bx bx-star me-1"></i>Marcar como principal
                </button>
                <button class="btn btn-danger btn-sm" id="btn_eliminar_preview"
                    onclick="eliminar_desde_preview()">
                    <i class="bx bx-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_preview_vid" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <span id="lbl_vid_nombre" class="text-white-50 small"></span>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <video id="vid_preview_full" controls class="modal-video-player">
                    <source id="vid_preview_src" src="" type="video/mp4">
                    Tu navegador no soporta la reproducción de video.
                </video>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button class="btn btn-danger btn-sm" id="btn_eliminar_vid_preview"
                    onclick="eliminar_desde_preview_vid()">
                    <i class="bx bx-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    var _media_preview_id = null;
    var _media_preview_espacio = '<?= $_id ?>';


    function cargar_media() {
        $.ajax({
            url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php?listar=true',
            type: 'post',
            data: {
                id_espacio: '<?= $_id ?>'
            },
            dataType: 'json',
            success: function(data) {
                if (!data) data = [];

                var fotos = data.filter(function(m) {
                    return m.tipo === 'imagen';
                });
                var videos = data.filter(function(m) {
                    return m.tipo === 'video';
                });

                renderizar_grid('#grid_fotos', fotos, 'imagen');
                renderizar_grid('#grid_videos', videos, 'video');

                var total = data.length;
                $('#lbl_contador_media').text(
                    total === 0 ? '' : total + ' archivo' + (total !== 1 ? 's' : '')
                );
            }
        });
    }

    function renderizar_grid(selector, items, tipo) {
        var $grid = $(selector);
        $grid.empty();

        if (items.length === 0) {
            var icono = tipo === 'imagen' ? 'bx-image' : 'bx-video-off';
            var texto = tipo === 'imagen' ? 'Sin imágenes cargadas' : 'Sin videos cargados';
            $grid.html(
                '<div class="empty-media">' +
                '<i class="bx ' + icono + '"></i>' +
                '<p>' + texto + '</p></div>'
            );
            return;
        }

        items.forEach(function(m) {
            var card = tipo === 'imagen' ?
                card_imagen(m) :
                card_video(m);
            $grid.append(card);
        });
    }

    function card_imagen(m) {
        var principal = m.es_principal == 1 ?
            '<span class="badge-principal"><i class="bx bxs-star" style="font-size:.65rem;"></i> Principal</span>' :
            '';

        var tam = formatear_bytes(m.tamanio_bytes);

        return '<div class="media-card" data-id="' + m._id + '">' +
            principal +
            '<img class="media-thumb" src="' + m.url_archivo + '?' + Date.now() + '" alt="' + m.nombre_archivo + '" loading="lazy">' +
            '<div class="media-overlay">' +
            '<button class="btn btn-light btn-sm rounded-circle" style="width:34px;height:34px;" ' +
            'onclick="preview_imagen(' + m._id + ',\'' + escape_attr(m.url_archivo) + '\',\'' + escape_attr(m.nombre_archivo) + '\')">' +
            '<i class="bx bx-expand-alt"></i>' +
            '</button>' +
            (m.es_principal != 1 ?
                '<button class="btn btn-warning btn-sm rounded-circle" style="width:34px;height:34px;" ' +
                'title="Marcar principal" onclick="set_principal(' + m._id + ',event)">' +
                '<i class="bx bx-star"></i>' +
                '</button>' : '') +
            '<button class="btn btn-danger btn-sm rounded-circle" style="width:34px;height:34px;" ' +
            'onclick="eliminar_media(' + m._id + ',event)">' +
            '<i class="bx bx-trash"></i>' +
            '</button>' +
            '</div>' +
            '<div class="media-footer"><small class="text-muted">' + tam + '</small></div>' +
            '</div>';
    }

    function card_video(m) {
        var tam = formatear_bytes(m.tamanio_bytes);

        return '<div class="media-card" data-id="' + m._id + '">' +
            '<div class="video-thumb-placeholder" onclick="preview_video(' + m._id + ',\'' + escape_attr(m.url_archivo) + '\',\'' + escape_attr(m.nombre_archivo) + '\')">' +
            '<i class="bx bx-play-circle"></i>' +
            '<span>' + m.formato.toUpperCase() + '</span>' +
            '</div>' +
            '<div class="media-overlay">' +
            '<button class="btn btn-light btn-sm rounded-circle" style="width:34px;height:34px;" ' +
            'onclick="preview_video(' + m._id + ',\'' + escape_attr(m.url_archivo) + '\',\'' + escape_attr(m.nombre_archivo) + '\')">' +
            '<i class="bx bx-play"></i>' +
            '</button>' +
            '<button class="btn btn-danger btn-sm rounded-circle" style="width:34px;height:34px;" ' +
            'onclick="eliminar_media(' + m._id + ',event)">' +
            '<i class="bx bx-trash"></i>' +
            '</button>' +
            '</div>' +
            '<div class="media-footer"><small>' + m.nombre_archivo + '</small>' +
            '<small class="text-muted">' + tam + '</small></div>' +
            '</div>';
    }


    function abrir_modal_media() {
        $('#form_media')[0].reset();
        $('#upload_preview_wrap').hide();
        $('#upload_preview_img').hide().attr('src', '');
        $('#upload_preview_vid').hide().attr('src', '');
        $('#upload_preview_nombre small').text('');
        $('#lbl_tipo_archivo').text('');
        $('#btn_guardar_media').prop('disabled', true);
        $('#upload_progress_wrap').hide();
        $('#upload_progress_bar').css('width', '0%');
        $('#modal_media').modal('show');
    }


    $('#archivo_media').on('change', function() {
        mostrar_preview_archivo(this.files[0]);
    });

    var dropZone = document.getElementById('drop_zone');

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });
    dropZone.addEventListener('dragleave', function() {
        dropZone.classList.remove('drag-over');
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        var file = e.dataTransfer.files[0];
        if (!file) return;

        // Asignar al input
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('archivo_media').files = dt.files;

        mostrar_preview_archivo(file);
    });

    function mostrar_preview_archivo(file) {
        if (!file) return;

        var imagenesMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        var videosMime = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
        var mime = file.type;
        var tam = file.size;

        // Limpiar
        $('#upload_preview_img').hide();
        $('#upload_preview_vid').hide().attr('src', '');
        $('#lbl_tipo_archivo').text('');
        $('#btn_guardar_media').prop('disabled', true);

        if (imagenesMime.indexOf(mime) !== -1) {
            if (tam > 5 * 1024 * 1024) {
                Swal.fire('', 'La imagen supera el límite de 5 MB.', 'warning');
                $('#archivo_media').val('');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(ev) {
                $('#upload_preview_img').attr('src', ev.target.result).show();
            };
            reader.readAsDataURL(file);
            $('#lbl_tipo_archivo').html('<i class="bx bx-image me-1 text-success"></i>Imagen · ' + formatear_bytes(tam));
            $('#btn_guardar_media').prop('disabled', false);

        } else if (videosMime.indexOf(mime) !== -1) {
            if (tam > 50 * 1024 * 1024) {
                Swal.fire('', 'El video supera el límite de 50 MB.', 'warning');
                $('#archivo_media').val('');
                return;
            }
            var url = URL.createObjectURL(file);
            var $vid = $('#upload_preview_vid');
            $vid.attr('src', url).show();
            $('#lbl_tipo_archivo').html('<i class="bx bx-video me-1 text-primary"></i>Video · ' + formatear_bytes(tam));
            $('#btn_guardar_media').prop('disabled', false);

        } else {
            Swal.fire('', 'Formato no permitido. Use JPG, PNG, WEBP, MP4 o WEBM.', 'error');
            $('#archivo_media').val('');
            return;
        }

        $('#upload_preview_nombre small').text(file.name);
        $('#upload_preview_wrap').show();
    }


    function guardar_media() {
        var archivos = document.getElementById('archivo_media').files;
        if (!archivos || archivos.length === 0) {
            Swal.fire('', 'Seleccione un archivo.', 'warning');
            return;
        }

        var form_data = new FormData(document.getElementById('form_media'));
        $('#upload_progress_wrap').show();
        $('#btn_guardar_media').prop('disabled', true);

        $.ajax({
            url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php?insertar=true',
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var pct = Math.round((e.loaded / e.total) * 100);
                        $('#upload_progress_bar').css('width', pct + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(r) {
                $('#upload_progress_wrap').hide();
                $('#btn_guardar_media').prop('disabled', false);

                if (r == 1) {
                    Swal.fire('', 'Archivo guardado con éxito.', 'success');
                    $('#modal_media').modal('hide');
                    cargar_media();
                } else if (r == -2) {
                    Swal.fire('', 'Formato de archivo no permitido.', 'error');
                } else if (r == -3) {
                    Swal.fire('', 'El video supera el límite de 50 MB.', 'warning');
                } else if (r == -4) {
                    Swal.fire('', 'La imagen supera el límite de 5 MB.', 'warning');
                } else {
                    Swal.fire('', 'Error al guardar el archivo.', 'error');
                }
            },
            error: function(xhr) {
                $('#upload_progress_wrap').hide();
                $('#btn_guardar_media').prop('disabled', false);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function preview_imagen(id, url, nombre) {
        _media_preview_id = id;
        $('#img_preview_full').attr('src', url + '?' + Date.now());
        $('#lbl_img_nombre').text(nombre);
        $('#modal_preview_img').modal('show');
    }

    function set_principal_desde_preview() {
        set_principal(_media_preview_id, null, function() {
            $('#modal_preview_img').modal('hide');
        });
    }

    function eliminar_desde_preview() {
        $('#modal_preview_img').modal('hide');
        eliminar_media(_media_preview_id);
    }

    function preview_video(id, url, nombre) {
        _media_preview_id = id;
        var $vid = $('#vid_preview_full');
        $('#vid_preview_src').attr('src', url);
        $vid[0].load();
        $('#lbl_vid_nombre').text(nombre);
        $('#modal_preview_vid').modal('show');
    }

    // Detener video al cerrar modal
    $('#modal_preview_vid').on('hidden.bs.modal', function() {
        var $vid = $('#vid_preview_full');
        $vid[0].pause();
        $vid[0].currentTime = 0;
    });

    function eliminar_desde_preview_vid() {
        $('#modal_preview_vid').modal('hide');
        eliminar_media(_media_preview_id);
    }

    function set_principal(id, evento, callback) {
        if (evento) evento.stopPropagation();

        $.ajax({
            url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php?set_principal=true',
            type: 'post',
            data: {
                id: id,
                id_espacio: '<?= $_id ?>'
            },
            dataType: 'json',
            success: function(r) {
                if (r == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Imagen principal actualizada',
                        timer: 1200,
                        showConfirmButton: false
                    });
                    cargar_media();
                    if (typeof callback === 'function') callback();
                }
            }
        });
    }

    function eliminar_media(id, evento) {
        if (evento) evento.stopPropagation();

        Swal.fire({
            title: '¿Eliminar archivo?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_mediaC.php?eliminar=true',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(r) {
                        if (r == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                timer: 1000,
                                showConfirmButton: false
                            });
                            cargar_media();
                        }
                    }
                });
            }
        });
    }

    function formatear_bytes(bytes) {
        if (!bytes || bytes === 0) return '—';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function escape_attr(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }
    $(document).ready(function() {
        cargar_media();
    });
</script>