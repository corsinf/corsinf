<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Firmador</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                        Firmador
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div>
            <?php //print_r($_SESSION) ?>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0 text-primary"></h5>
                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalFormulario">
                                        <i class="bx bx-plus"></i> Persona Natural
                                    </button>
                                    <div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalFormularioLabel">Formulario de Persona Natural</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formPersonaNatural">
                                                        <div class="mb-3">
                                                            <label for="nombresCompletos" class="form-label">Nombres Completos</label>
                                                            <input type="text" class="form-control" id="nombresCompletos" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="numeroRUC" class="form-label">N&uacute;mero de RUC</label>
                                                            <input type="text" class="form-control" id="numeroRUC" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="direccionDomicilio" class="form-label">Direcci&oacute;n Domicilio</label>
                                                            <input type="text" class="form-control" id="direccionDomicilio" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="provincia" class="form-label">Provincia</label>
                                                            <input type="text" class="form-control" id="provincia" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="ciudad" class="form-label">Ciudad</label>
                                                            <input type="text" class="form-control" id="ciudad" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="correoElectronico" class="form-label">Direcci&oacute;n Correo Electr&oacute;nico V&aacute;lido</label>
                                                            <input type="email" class="form-control" id="correoElectronico" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="celular" class="form-label">No. Celular (Poner C&oacute;digo de Pa&iacute;s)</label>
                                                            <input type="tel" class="form-control" id="celular" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="fijo" class="form-label">No. Fijo (Poner C&oacute;digo de Pa&iacute;s)</label>
                                                            <input type="tel" class="form-control" id="fijo" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_pacientes"><i class="bx bx-plus"></i> Persona Natural con RUC</button>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalFormularioJuridica">
                                        <i class="bx bx-plus"></i> Persona Jurídica
                                    </button>
                                    <div class="modal fade" id="modalFormularioJuridica" tabindex="-1" aria-labelledby="modalFormularioJuridicaLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="modalFormularioJuridicaLabel">Formulario de Persona Jurídica</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <form id="formPersonaJuridica">
                                                <div class="mb-3">
                                                <label for="razonSocial" class="form-label">Razón Social</label>
                                                <input type="text" class="form-control" id="razonSocial" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="ruc" class="form-label">R.U.C.</label>
                                                <input type="text" class="form-control" id="ruc" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="nombresCompletosRepresentante" class="form-label">Nombres Completos del Representante Legal</label>
                                                <input type="text" class="form-control" id="nombresCompletosRepresentante" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="cedulaPasaporte" class="form-label">Número de Cédula o Pasaporte</label>
                                                <input type="text" class="form-control" id="cedulaPasaporte" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="direccionRUC" class="form-label">Dirección como está en el RUC</label>
                                                <input type="text" class="form-control" id="direccionRUC" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="provincia" class="form-label">Provincia</label>
                                                <input type="text" class="form-control" id="provincia" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="ciudad" class="form-label">Ciudad</label>
                                                <input type="text" class="form-control" id="ciudad" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="correoElectronico" class="form-label">Dirección Correo Electrónico Empresarial Válido</label>
                                                <input type="email" class="form-control" id="correoElectronico" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="celular" class="form-label">No. Celular (Poner Código de País)</label>
                                                <input type="tel" class="form-control" id="celular" required>
                                                </div>
                                                <div class="mb-3">
                                                <label for="fijo" class="form-label">No. Fijo (Poner Código de País)</label>
                                                <input type="tel" class="form-control" id="fijo" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Enviar</button>
                                            </form>
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
        </div>
        <!--end row-->
    </div>
</div>