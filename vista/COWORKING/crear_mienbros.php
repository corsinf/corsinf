<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><strong>Formulario</strong></div>
            <div class="breadcrumb-separator"></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bi bi-house"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <strong>Registro Miembros</strong>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <hr>
                <div class="card">
                    <div class="card-body">
                        <h1 class="titulo mb-4">Oficina 5</h1>

                        <form id="formulario_miembro" class="mb-4">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="txt_nombre" class="form-label"><strong>Nombre:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_nombre" id="txt_nombre" placeholder="Nombre" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_correo" class="form-label"><strong>Correo:</strong></label>
                                    <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" placeholder="Correo electrónico" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_cedula" class="form-label"><strong>Cédula:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_cedula" id="txt_cedula" placeholder="Cédula" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_numero_celular" class="form-label"><strong>Número Celular:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_numero_celular" id="txt_numero_celular" placeholder="Número" required>
                                </div>
                            </div>
                            <button type="button" onclick="enviardatos()"class="btn btn-primary" id="btn_registrar_miembro"><strong>Registrar Miembro</strong></button>
                        </form>

                        <h2 class="mb-4">Miembros Registrados</h2>
                        <table class="table table-bordered table-striped" id="tbl_miembros">
                            <thead class="table-header">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Cédula</th>
                                    <th>Número</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body">
                                
                            </tbody>
                        </table>

                        <div class="modal fade" id="modal_registrar_compra" tabindex="-1" aria-labelledby="modal_registrar_compra_label" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal_registrar_compra_label"><strong>Registrar Compra</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formulario_compras">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="txt_producto" class="form-label"><strong>Producto:</strong></label>
                                                    <select class="form-control" id="ddl_producto" name="ddl_producto" required>

                                                    </select>

                                            </div>
                                                <div class="col-md-3">
                                                    <label for="txt_cantidad" class="form-label"><strong>Cantidad:</strong></label>
                                                    <input type="number" class="form-control" id="txt_cantidad" name="txt_cantidad" value="1" min="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_precio" class="form-label"><strong>Precio:</strong></label>
                                                    <input type="text" class="form-control" id="txt_precio" name="txt_precio" readonly>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped mt-4" id="tbl_compras">
                                                <thead class="table-header">
                                                    <tr>
                                                        <th>Miembro</th>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbl_boby">
                                                    
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary" id="btn_agregar_compra">
                                                <i class="bi bi-bag-plus-fill"></i> <strong>Agregar</strong>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            <strong>Cerrar</strong> <i class="bi bi-file-x"></i>
                                        </button>
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


<script>
    $(document).ready(function() {
        lista_usuario()
        lista_compra()
        })
    
    function lista_usuario()
    {       
        $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/COWORKING/crear_mienbrosC.php?lista_mienbro=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
            $('#tbl_body').html(response);
            
            console.log(response);
          }       
       });
      }

      function lista_compra() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?lista_compra=true',
            type: 'post',
            dataType: 'json ', 
            success: function(response) {  
                $('#tbl_boby').html(response);
                console.log(response);
            }       
        });
    }

      function enviardatos()
      {
        var parametros = 
        {
           'nombre': $('#txt_nombre').val(),
           'correo': $('#txt_correo').val(),
           'cedula': $('#txt_cedula').val(),
           'numero': $('#txt_numero_celular').val(),
        }
        //var form = ('#').serialize();
        //console.Log(form);
        $.ajax({
            data:{data:parametros},
            url: '../controlador/COWORKING/crear_mienbrosC.php?add=true',
            type: 'post',
            dataType: 'json ', 
             success: function(response) {  
               if(response==1)
               {
                    alert('Ingresado')
                }
                else{
                    alert('Error')
                }
                
            }       
        });
      }



      function select_productos()
    {       
        $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/COWORKING/crear_mienbrosC.php?listar_productos=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response ) {  


            $('#ddl_producto').html(response);
            

            console.log(response);
          }       
       });
      }


</script>
