<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Blank</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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

                            <h5 class="mb-0 text-primary">Cotización</h5>

                        </div>
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="presupuestos.php" class="btn btn-success btn-sm">Nuevo</a>
                                        <button class="btn btn-secondary btn-sm" onclick="factura_imprimir()">Imprimir</button>
                                        <a href="presupuestos.php?numfac=<?php echo $num; ?>&doc=<?php echo $doc; ?>&est=F" class="btn btn-warning btn-sm">Finalizar</a>
                                        <button class="btn btn-primary btn-sm" onclick="Agregar_Abono('<?php echo $num ?>')">Añadir abono</button>
                                        <select class="form-select form-select-sm d-none" id="ddl_pasar" name="ddl_pasar" onchange="pasar_a_factura()">
                                            <option value="">Pasar cotización</option>
                                            <?php if ($doc == 'PR') {
                                                echo '<option value="FA">Pasar a facturar</option>';
                                            } else {
                                                echo '<option value="PR">Pasar a cotización</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="presupuestos.php?numfac=<?php echo $num; ?>&doc=<?php echo $doc; ?>&est=P" class="btn btn-primary btn-sm">Editar</a>
                                    </div>
                                </div>
                                <div id="pendiente_page">
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h2>Cotización</h2>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <input type="hidden" name="txt_id_punto" id="txt_id_punto">
                                            <p>Punto de venta: <b id="txt_nom_punto">Principal</b></p>
                                        </div>
                                    </div>
                                    <div class="card card-info">
                                        <div class="card-header py-2">
                                            <h3 class="card-title">Datos Personales</h3>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <label for="txt_nombre_cli" class="form-label"><b>Nombre</b></label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="hidden" name="txt_id_cli" id="txt_id_cli">
                                                        <input type="text" name="txt_nombre_cli" id="txt_nombre_cli" class="form-control form-control-sm" onkeyup="solo_mayusculas(this.id,this.value);">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cliente_nuevo"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="txt_ci_cli" class="form-label"><b>CI / RUC</b></label>
                                                    <input type="text" name="txt_ci_cli" id="txt_ci_cli" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_email_cli" class="form-label"><b>Email</b></label>
                                                    <input type="text" name="txt_email_cli" id="txt_email_cli" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_telefono_cli" class="form-label"><b>Teléfono</b></label>
                                                    <input type="text" name="txt_telefono_cli" id="txt_telefono_cli" class="form-control form-control-sm" onkeyup="num_caracteres(this.id,10)">
                                                </div>
                                                <div class="col-md-7">
                                                    <label for="txt_direccion_cli" class="form-label"><b>Dirección</b></label>
                                                    <input type="text" name="txt_direccion_cli" id="txt_direccion_cli" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="txt_fecha_fac" class="form-label"><b>Fecha Cotización</b></label>
                                                    <input type="date" name="txt_fecha_fac" id="txt_fecha_fac" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                                                    <label for="ddl_tipo_pago" class="form-label mt-2"><b>Forma de Pago</b></label>
                                                    <select class="form-select form-select-sm" id="ddl_tipo_pago" onchange="habilitar_cheq_comp()">
                                                        <option value="">Seleccione forma de pago</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <b>Num.Coti:</b>
                                                    <h2 id="numfac">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-3">
                                        <div class="col-md-2">
                                            <label for="txt_referencia" class="form-label"><b>Referencia</b></label>
                                            <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_producto" class="form-label"><b>Producto</b></label>
                                            <input type="text" name="txt_producto" id="txt_producto" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="txt_bodega" class="form-label"><b>Bodega</b></label>
                                            <select class="form-select form-select-sm" name="txt_bodega" id="txt_bodega">
                                                <option value="">Seleccione Bodega</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="txt_cantidad" class="form-label"><b>Cant</b></label>
                                            <input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" value="1" onblur="calcular()">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="txt_precio" class="form-label"><b>Precio</b></label>
                                            <input type="number" name="txt_precio" id="txt_precio" class="form-control form-control-sm" value="0" onblur="calcular()">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="txt_descuento" class="form-label"><b>% Desc</b></label>
                                            <input type="number" name="txt_descuento" id="txt_descuento" class="form-control form-control-sm" value="0" onblur="calcular()">
                                        </div>
                                        <div class="col-md-1 d-grid">
                                            <button class="btn btn-primary btn-sm" onclick="crear_presupuesto();"><i class="fas fa-shopping-cart"></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <section id="finalizado_page" style="display: none;" class="container-fluid">
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <b>Nombre:</b>
                                        <p id="nombre_f">Javier Farinango</p>
                                    </div>
                                    <div class="col-md-2">
                                        <b>CI:</b>
                                        <p id="ci_f"></p>
                                    </div>
                                    <div class="col-md-2">
                                        <b>Fecha Emisión:</b>
                                        <p id="fecha_emi_f"></p>
                                    </div>
                                    <div class="col-md-2">
                                        <b>Fecha Vencimiento:</b>
                                        <p id="fecha_ven_f"></p>
                                    </div>
                                    <div class="col-md-2">
                                        <b>Teléfono:</b>
                                        <p id="telefono_f"></p>
                                    </div>
                                    <div class="col-md-2">
                                        <b>Email:</b>
                                        <p id="emial_f"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Dirección:</b>
                                        <p id="direccion_f"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <b>Num. Factura:</b>
                                        <p id="numfac_f"></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12" id="tbl_pedido_f"></div>
                                </div>
                                <div class="modal-footer">
                                    <b>SUBTOTAL:</b>
                                    <p id="txt_subtotal_fa_fin"></p>
                                    <b>DESCUENTO:</b>
                                    <p id="txt_dcto_fa_fin"></p>
                                    <b>IVA:</b>
                                    <p id="txt_iva_fa_fin"></p>
                                    <b>TOTAL:</b>
                                    <p id="txt_total_fa_fin"></p>
                                </div>
                            </section>

                        </section>
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