<script type="text/javascript">
   $( document ).ready(function() {
   	lista_kardex();
     //  // restriccion();
     // Lista_clientes();
     // Lista_procesos();

    });

   function lista_kardex()
   {
   	var parametros = 
   	{
   		'query':$('#txt_query').val(),
      'tipo':$('#cbx_M').prop('checked'),
   	}
    $.ajax({
         data:  {parametros,parametros},
         url:   '../controlador/inventario_kardexC.php?lista_kardex=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           	// console.log(response);
           	$('#tbl_kardex').html(response);
          } 
          
       });
   }

   function excel()
   {

     // var datos =  $("#form_filtro").serialize();
     var query = $('#txt_query').val();
     var tipo = $('#cbx_M').prop('checked');
   	 var url='../controlador/inventario_kardexC.php?excel_kardex=true&query='+query+'&tipo='+tipo;
     window.open(url, '_blank');
   }

    function pdf()
   {

     // var datos =  $("#form_filtro").serialize();
     var query = $('#txt_query').val();
     var tipo = $('#cbx_M').prop('checked');
     var url='../controlador/inventario_kardexC.php?pdf_kardex=true&query='+query+'&tipo='+tipo;
     window.open(url, '_blank');
   }

    function excel_existencias()
   {

     // var datos =  $("#form_filtro").serialize();
     var query = $('#txt_query').val();
     var url='../controlador/inventario_kardexC.php?excel_existencias=true&query='+query;
     window.open(url, '_blank');
   }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Kardex de productos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Productos
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

                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Kardex de productos</h1>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Reporte de movimientos
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="pdf()">Reporte PDF</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="excel()">Reporte Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div><br>

                                <div class="row">
                                    <form id="form_filtros" class="col-sm-12" enctype="multipart/form-data" method="post">
                                        <div class="col-sm-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cbx_tipo" id="cbx_M" checked onclick="lista_kardex()">
                                                <label class="form-check-label" for="cbx_M">Materia prima</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cbx_tipo" id="cbx_P" onclick="lista_kardex()">
                                                <label class="form-check-label" for="cbx_P">Producto terminado</label>
                                            </div>
                                            <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm mt-2" onkeyup="lista_kardex()" placeholder="Producto">
                                        </div>
                                    </form>
                                </div> <br>

                                <div class="row">
                                    <div class="col-sm-12" id="tbl_kardex">
                                        <!-- Kardex table content goes here -->
                                    </div>
                                </div>
                            </div><!-- /.container-fluid -->
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