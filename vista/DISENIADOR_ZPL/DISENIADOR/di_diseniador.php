<script type="text/javascript">
    $(document).ready(function() {

    });
</script>


<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css">
<!-- <script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

<link rel="stylesheet" href="../lib/ZPL/css/designer.css">
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_propertyInspector.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_toolbar.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/designer_labelInspector.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/labelControls/LabelSize.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/labelControls/GenerateZPL.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/lindell-barcode/JsBarcode.all.min.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/rectangle.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/barcode.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/text.js"></script>
<script language="javascript" type="text/javascript" src="../lib/ZPL/js/tools/image.js"></script>

<script>
    var canvasDesigner = null;
    $(document).ready(function() {
        canvasDesigner = new com.logicpartners.labelDesigner('labelDesigner', 1.75, 0.75);
        canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.size(canvasDesigner));
        canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.generatezpl(canvasDesigner));
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.text());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.rectangle());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.barcode());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.image());
        //canvasDesigner.addRectangle(50, 50, 300, 50);
        //canvasDesigner.addRectangle(100, 100, 50, 50);
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <!-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_blank"><i class="bx bx-plus"></i> Nuevo</button> -->

                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">

                                <canvas id="labelDesigner" tabindex="1" width="800" height="900"
                                    style="margin-left: 100px; margin-top: 50px; border: 1px solid #000000;">
                                </canvas>

                                
                            </div><!-- /.container-fluid -->
                        </section>

                        <br><br><br><br><br><br><br><br><br><br><br><br>

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