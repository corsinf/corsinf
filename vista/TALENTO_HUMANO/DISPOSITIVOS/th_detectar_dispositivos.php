<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_dispositivos = $('#tbl_dispositivos').DataTable($.extend({}, configuracion_datatable('Dispostivos', 'dispostivos'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?BuscarDevice=true',
                dataSrc: ''
            },
            columns: [
                    {data: 'item' },                   
                    { data: 'tipo' }, // Tipo dispositivo
                    { data: 'Estado' }, // Estado
                    { data: 'ipv4' }, // IPV4
                    { data: 'puerto' }, // Puerto
                    { data: 'serie' }, // Serial
                    { data: 'MAC' }, // MAC Address
                    { data: null,
                        render: function(data, type, item) {
                        return `<button type="button" class="btn btn-primary btn-xs" onclick="cambiar_clave('${item.ipv4}','${item.puerto}')"><i class="bx bx-key fs-7 me-0 fw-bold"></i></button>`;
                    }
                },
               
            ],
            order: [
                [1, 'asc']
            ],
        }));
    });

    function detectar()
    {  
        setInterval(function() {
            $.ajax({
                 // data:  {parametros:parametros},
                 url:   '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?DetectarEventos=true',
                 type:  'post',
                 dataType: 'json',
                 success:  function (response) { 
                }          
            });
        }, 3000);  // Intervalo de 3 segundos
    }
    function cambiar_clave(ip,port)
    {
        $('#txt_ipv4').val(ip);
        $('#txt_ipv4_port').val(port);
        $('#cambio_clave').modal('show');
    }

    function cambiar_pass()
    {
        if($('#txt_ipv4_pass').val()=='')
        {
            Swal.fire("","Contraseña asignada no valida","error");
            return false;
        }
        var parametros = 
        {
            'ip':$('#txt_ipv4').val(),
            'pass':$('#txt_ipv4_pass').val(),
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?CambiarPass=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
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
                            Lista de Dispositivos
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
                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_dispositivos"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>
                                        <button type="button" class="btn btn-success btn-sm" onclick="detectar()">
                                            <i class="bx bx-plus me-0 pb-1"></i> detectar
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_dispositivos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Tipo dispositivo</th>
                                                <th>Estado</th>
                                                <th>IPV4</th>
                                                <th>Puerto</th>
                                                <th>Serial</th>
                                                <th>MAC Address</th>
                                                <th width="10px">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

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