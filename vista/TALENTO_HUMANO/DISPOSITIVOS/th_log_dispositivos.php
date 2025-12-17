<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        dispositivos();
    });

    function dispositivos() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item,i){
                    op+='<option value="'+item._id+'">'+item.nombre+'</option>';
                })
                $('#ddl_dispositivos').html(op);
               
            },  error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function probar_conexion()
    {
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        if(txt_pass=='' || txt_usuario=='' || txt_host=='')
        {
            Swal.fire("Ingrese todos los datos","","info")
            return false;
        }

        var parametros = {
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
        };

         $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?ProbarConexion=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response.respuesta.resp == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                } else{
                    Swal.fire('No se pudo conectar', response.respuesta.msj, 'error')
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function detectar()
    {
        // setInterval(
            DeviceLog()
            // , 5000);
      
    }
    function DeviceLog()
    {
        var parametros = {
            'dispostivos': $('#ddl_dispositivos').val(),
        };

         $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?DetectarEventos=true',
            type: 'post',
            dataType: 'json',

            success: function(response, status, xhr) {
            // Verificar si el estatus HTTP es 200
          
                if(response==-1)
                {
                    Swal.fire("dispositivo no encontrado","","info");
                }else
                {
                    if (xhr.status === 200) {
                        Swal.fire("Detección activada", "", "success");
                        // console.log("Respuesta del servidor: ", response); // Opcional para depuración
                    }           
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

    }

    function Buscar_log()
    {

        if ($.fn.DataTable.isDataTable('#tbl_marcaciones_all')) {
            $('#tbl_marcaciones_all').DataTable().destroy();
        }

        var parametros = {
            'dispostivos': $('#ddl_dispositivos').val(),
            'desde':$('#txt_desde').val(),
            'hasta':$('#txt_hasta').val(),
        };

        $('#myModal_espera').modal('show');

         $.ajax({
            data: {parametros: parametros},
            url: '../controlador/TALENTO_HUMANO/th_log_dispositivosC.php?Buscar_log=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response)

                $('#myModal_espera').modal('hide');
                var tr = '';
                response.forEach(function(item,i){
                    var linea = JSON.parse(item);
                    tr+=`<tr>
                            <td>`+(i+1)+`</td>
                            <td>`+linea[0].ip+`</td>
                            <td>`+linea[0].Puerto+`</td>
                            <td>`+linea[0]['Card Number']+`</td>
                            <td>`+linea[0].fecha+`</td>
                         </tr>`;
                })

                $('#tbl_marcaciones').html(tr);
                $('#tbl_marcaciones_all').DataTable({
                      scrollX: true,
                      searching: false,
                      responsive: false,
                  // paging: false,   
                      info: false,   
                      autoWidth: false,  
                  order: [[1, 'asc']], // Ordenar por la segunda columna
                      /*autoWidth: false,
                      responsive: true,*/
                      language: {
                      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    // columnDefs: [
                    //     { targets: 2, width: "200px" },
                    //     { targets: 3, width: "500px" },
                    // ],
                  });
            },
            
            error: function(xhr, status, error) {
                $('#myModal_espera').modal('hide');
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

    }

    function Buscar_save()
    {
        var parametros = {
            'dispostivos': $('#ddl_dispositivos').val(),
            'desde':$('#txt_desde').val(),
            'hasta':$('#txt_hasta').val(),
        };

        $('#myModal_espera').modal('show');

         $.ajax({
            data: {parametros: parametros},
            url: '../controlador/TALENTO_HUMANO/th_log_dispositivosC.php?Buscar_save=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if(response.respuesta==1)
                {
                    Swal.fire("Se Guardaron "+response.cantidad+" registros","","success")
                }else
                {
                    Swal.fire("No se pudo guardar","","error"),
                }
                // console.log(response)
                // var tr = '';
                // response.forEach(function(item,i){
                //     $('#myModal_espera').modal('hide');
                //     var linea = JSON.parse(item);
                //     tr+=`<tr>
                //             <td>`+(i+1)+`</td>
                //             <td>`+linea[0].ip+`</td>
                //             <td>`+linea[0].Puerto+`</td>
                //             <td>`+linea[0]['Card Number']+`</td>
                //             <td>`+linea[0].fecha+`</td>
                //          </tr>`;
                //     // console.log(item);
                //     // console.log(linea);
                //     $('#tbl_marcaciones').html(tr);
                // })
            },
            
            error: function(xhr, status, error) {
                $('#myModal_espera').modal('hide');
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

    }



</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dispositivos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            log de Dispositivos
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
                        <div class="row">
                            <div class="col-2 col-md-2">
                                <b>Desde</b>
                                <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-2 col-md-2">
                                <b>Hasta</b>
                                <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-5 col-md-5">
                                <br>
                                <select class="form-select form-select-sm" id="ddl_dispositivos" name="ddl_dispositivos">
                                    <option>Seleccione dispositivo</option>
                                </select>
                            </div>
                            <div class="col-3 col-md-3">
                                <br>
                                <button type="button" class="btn btn-success btn-sm" onclick="Buscar_log()">
                                    <i class="bx bx-search me-0 pb-1"></i> Buscar
                                </button>
                                 <button type="button" class="btn btn-outline-dark btn-sm" onclick="Buscar_save()">
                                    <i class="bx bx-save me-0 pb-1"></i> Guardar
                                </button>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_marcaciones_all" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Ip</th>
                                                <th>Puerto</th>                                                
                                                <th>Card No</th>
                                                <th>Fecha y hora</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_marcaciones">

                                        </tbody>
                                    </table>
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

<div class="modal" id="cambio_clave" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2>Cambio de clave</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-8">
                        <label for="">Ip<label class="text-danger">*</label></label>
                        <input class="form-control form-control-sm" name="txt_ipv4" id="txt_ipv4" readonly>
                    </div>
                     <div class="col-4">
                        <label for="">Puerto <label class="text-danger">*</label></label>                        
                        <input class="form-control form-control-sm" name="txt_ipv4_port" id="txt_ipv4_port" readonly>
                    </div>
                </div>
                   
                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Antigua Contraseña <label class="text-danger">*</label></label>                        
                        <input class="form-control form-control-sm" name="txt_ipv4_pass_ant" id="txt_ipv4_pass_ant">
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Nueva Contraseña <label class="text-danger">*</label></label>                        
                        <input class="form-control form-control-sm" name="txt_ipv4_pass" id="txt_ipv4_pass">
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="cambiar_pass()"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal" id="registrar_dispositivo" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2>Cambio de clave</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                    <form id="form_dispositivo" novalidate="novalidate">

                            <div class="row pt-3 mb-col">
                               <!--  <div class="col-md-4">
                                    <label for="ddl_modelo" class="form-label">Modelo <label style="color: red;">*</label></label>
                                    <select class="form-select form-select-sm is-valid" id="ddl_modelo" name="ddl_modelo" aria-invalid="false">
                                        <option selected="" disabled="">-- Seleccione --</option>
                                        <option value="1">HIK</option>
                                        <option value="2">Vision</option>
                                    </select>
                                </div> -->

                                <div class="col-md-8">
                                    <label for="txt_nombre" class="form-label">Nombre <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_host" class="form-label">IP/Host </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_host" name="txt_host" maxlength="50" oninput="texto_minusculas(this);" readonly>
                                </div>

                                <div class="col-md-2 ">
                                    <label for="txt_puerto" class="form-label">Puerto </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_puerto" name="txt_puerto" maxlength="4" readonly>
                                </div>

                                <div class="col-md-6 ">
                                    <label for="txt_serial" class="form-label">Número de Serie </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_serial" name="txt_serial" maxlength="100" readonly>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_usuario" class="form-label">Usuario <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_usuario" name="txt_usuario" maxlength="50">
                                    <span id="error_txt_usuario" class="text-danger"></span>
                                </div>

                                <div class="col-md-8 ">
                                    <label for="txt_pass" class="form-label">Contraseña <label style="color: red;">*</label></label>
                                    <input type="text" class="form-control form-control-sm" id="txt_pass" name="txt_pass" maxlength="50">                                    
                                    <span id="error_txt_pass" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="cbx_ssl" id="cbx_ssl">
                                        <label class="form-label" for="cbx_ssl">SSL </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">

                                    <button class="btn btn-primary btn-sm px-4" onclick="probar_conexion()" type="button"><i class="lni lni-play fs-6 me-0"></i> Probar conexión</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">

                            <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                        </div>


                    </form>

            </div>
        </div>
    </div>
</div>


