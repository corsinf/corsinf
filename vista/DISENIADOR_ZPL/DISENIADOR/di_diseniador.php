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
    var zplCode;
    $(document).ready(function() {
        canvasDesigner = new com.logicpartners.labelDesigner('labelDesigner', 1.75, 0.75);
         canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.size(canvasDesigner));
        canvasDesigner.labelInspector.addTool(new com.logicpartners.labelControl.generatezpl(canvasDesigner));

       
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.text());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.rectangle());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.barcode());
        canvasDesigner.toolbar.addTool(new com.logicpartners.designerTools.image());

        // canvasDesigner.addRectangle(50, 50, 300, 50);
        // canvasDesigner.addRectangle(100, 100, 50, 50);
        size = $('#pnl_size')
        detalle = $('#pnl_detalles')
        tools = $('#pnl_tools')

         $('#pnl_new_size').append(size)
         $('#pnl_new_detalles').append(detalle)
         $('#pnl_new_tools').append(tools)

        // $('#pnl_size').empty()



    });
</script>


<script>

    function imprimir()
    {
        rbl = $('#rbl_rfid').prop('checked')
        if(rbl==true && $('#txt_rfid').val()=='')
        {
            Swal.fire("","El texto del rfid vacio","info")
            return false;
        }
        $('#btn_zpl').click();

        var parametros = 
        {
            'code':zplCode,
            'RFID': $('#txt_rfid').val(),
            'RFIDOp':rbl,
        }

         $.ajax({
          data:  {parametros:parametros},
          url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?imprimirTag=true',
          type:  'post',
          dataType: 'json',
          /*beforeSend: function () {   
               var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
             $('#tabla_').html(spiner);
          },*/
            success:  function (response) {
             
               $('#ddl_informes').html(response); 
          }
        });

    }

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

        <div class="row p-2">
            <div class="col-sm-12">
                <button class="btn-sm btn-success btn" onclick="imprimir()"><i class="bx bx-print"></i> Imprimir</button>
            </div>    
        </div>

        <div class="row">
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6" id="pnl_new_tools">
                                
                            </div> 
                            <div class="col-6">
                                <label><input type="checkbox" name="rbl_rfid" id="rbl_rfid"> RFID</label>
                                <br>
                                <b>Texto para RFID</b>
                                <input type="" name="txt_rfid" id="txt_rfid" class="form-control form-control-sm">
                            </div>                          
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <canvas id="labelDesigner" tabindex="1" width="800" height="900"
                                style="border: 1px solid #000000;">
                                </canvas>                                
                            </div>
                             <div class="col-12" id="pnl_new_size">
                                
                            </div>
                            
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-sm-3" > 
                <div class="card">
                    <div class="card-body">
                        <div class="row" id="pnl_new_detalles">

                        </div>
                        <div class="row" id="pnl_boton">

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