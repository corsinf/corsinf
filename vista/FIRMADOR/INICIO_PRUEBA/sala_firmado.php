<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Nueva Vista</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Sala de Firmado</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <input id="image-uploadify" type="file" accept=".p12,.pfx" multiple>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-12 col-lg-12">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <section class="bg-secondary bg-opacity-25 mb-2">
                                            <div class="p-3">
                                                <label for="txt_nombreSala" class="form-label form-label-sm">
                                                    <h6><strong>2) Nombre de sala:</strong></h6>
                                                </label>
                                                <input type="text" class="form-control" name="txt_nombreSala" id="txt_nombreSala" value="" placeholder="" required>
                                            </div>
                                        </section>
                                        <section class="bg-secondary bg-opacity-25 mb-2">
                                            <div class="p-3">
                                                <label for="txt_descripcionSala" class="form-label form-label-sm">
                                                    <h6><strong>3) Descripción de la sala:</strong></h6>
                                                </label>
                                                <input type="text" class="form-control" name="txt_descripcionSala" id="txt_descripcionSala" value="" placeholder="" required>
                                            </div>
                                        </section>
                                        <section class="bg-secondary bg-opacity-25 mb-4">
                                            <div class="d-grid gap-2 p-3">
                                                <label for="btn_descripcionSala" class="form-label form-label-sm">
                                                    <h6><strong>4) Firmantes:</strong></h6>
                                                </label>
                                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal_firmantes"><i class='bx bxs-user-plus'></i>Añadir Firmantes</button required>
                                            </div>
                                        </section>
                                        <section class="bg-secondary bg-opacity-25">
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn" id="btn_generarSala" disabled><i class='bx bxs-save'></i>Generar Sala</button>
                                            </div>
                                        </section>
                                        <section class="py-3">
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-white border border-dark" id="btn_cancelar">Cancelar</button>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-lg" id="modal_firmantes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregar firmante</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-success position-relative" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">Añadir por correo electrónico</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">Añadir un contacto</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">Añadir un grupo</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                    <div class="">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">

                                </div>
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>