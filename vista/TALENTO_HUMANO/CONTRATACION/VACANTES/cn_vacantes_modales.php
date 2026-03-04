<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<!-- Modal: Sin perfil de postulante -->
<div class="modal fade" id="modalSinPostulante" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div style="height:6px;background:linear-gradient(90deg,#0d6efd,#6610f2);"></div>
            <div class="modal-body text-center px-5 py-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:72px;height:72px;background:linear-gradient(135deg,#e8f0fe,#f3e8ff);">
                        <i class="bx bx-file-blank text-primary" style="font-size:2.2rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-2">¡Un paso antes de continuar!</h5>
                <p class="text-muted mb-1" style="line-height:1.6;">
                    Para postularte a cualquier plaza, primero necesitas
                    <strong class="text-dark">completar tu hoja de vida</strong>.
                </p>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Es rápido y solo debes hacerlo una vez. Con tu CV registrado podrás aplicar
                    a todas las oportunidades disponibles. 🚀
                </p>
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <div class="text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">1</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Llena tu<br>hoja de vida</p>
                    </div>
                    <div class="d-flex align-items-center pb-3">
                        <i class="bx bx-chevron-right text-muted fs-4"></i>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">2</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Accede a<br>las plazas</p>
                    </div>
                    <div class="d-flex align-items-center pb-3">
                        <i class="bx bx-chevron-right text-muted fs-4"></i>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-1 fw-bold"
                            style="width:34px;height:34px;font-size:.85rem;">3</div>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">¡Postúlate<br>y listo!</p>
                    </div>
                </div>
                <button onclick="irACompletarCV('<?= $modulo_sistema ?>')" class="btn btn-primary px-4 rounded-pill">
                    <i class="bx bx-edit me-2"></i>Completar mi hoja de vida
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Sin acceso -->
<div class="modal fade" id="modalSinAcceso" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div style="height:6px;background:linear-gradient(90deg,#dc3545,#fd7e14);"></div>
            <div class="modal-body text-center px-5 py-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:72px;height:72px;background:linear-gradient(135deg,#fde8e8,#ffe8d6);">
                        <i class="bx bx-lock-alt text-danger" style="font-size:2.2rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-2">Acceso restringido</h5>
                <p class="text-muted mb-1" style="line-height:1.6;">
                    Este apartado es exclusivo para <strong class="text-dark">postulantes</strong>.
                </p>
                <p class="text-muted small mb-4" style="line-height:1.6;">
                    Tu rol actual no tiene permitido visualizar ni interactuar con las plazas de postulación. 🔒
                </p>
                <button onclick="ir_inicio('<?= $modulo_sistema ?>')" class="btn btn-danger px-4 rounded-pill">
                    <i class="bx bx-arrow-back me-2"></i>Regresar
                </button>
            </div>
        </div>
    </div>
</div>