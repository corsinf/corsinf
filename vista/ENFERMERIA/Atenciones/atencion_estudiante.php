<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

      function obtener_hora_formateada(hora) {
    var fechaActual = new Date(hora);
    var hora = fechaActual.getHours();
    var minutos = fechaActual.getMinutes();
    var segundos = fechaActual.getSeconds();

    // Formatear la hora como una cadena
    var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
      (minutos < 10 ? '0' : '') + minutos;

    return horaFormateada;
  }


     function consultar_datos(id = '') {
        var estudiantes = '';
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '<?php echo $url_general ?>/controlador/agendamientoC.php?cita_actual=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                // console.log(response);   
                var lista = '';
                $.each(response, function(i, item) {

                    console.log(item);
                    lista+= '<div class="col">'+
                    '<div class="card radius-15">'+
                        '<div class="card-body text-center">'+
                            '<div class="p-4 border radius-15">'+
                                '<img src="../assets/images/avatars/avatar-1.png" width="110" height="110" class="rounded-circle shadow" alt="">'+
                                '<h5 class="mb-0 mt-5">'+item.sa_conp_nombres+'</h5>'+
                                '<p class="mb-3">'+item.sa_conp_tipo_consulta+'</p>'+                                
                                '<div class="d-grid"> '+
                                    '<a class="btn btn-outline-success radius-15" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_ficha=' + item.sa_conp_id + '&id_estudiante=' +item.sa_fice_id + '&id_representante=' +item.sa_id_representante+ '&id_consulta=2&ver=0">Comenzar consulta</a>'+
                                    '<button class="btn btn-outline-primary radius-15" onclick="consultar_datos_h('+item.sa_fice_id+')">Historial</button>'+
                                '</div>'+
                            '</div>'+
                       ' </div>'+
                    '</div>'+
                '</div>';
                });

                $('#citas_actuales').html(lista);
            }
        });
    }


    function show_historial()
    {
        $('#myModal_historial').modal('show');
    }

    function consultar_datos_h(id_estudiante = '') {
    var consulta = '';
    var cont = 1;
    $.ajax({
      data: {
        id: id_estudiante
      },
      url: '<?php echo $url_general ?>/controlador/consultasC.php?listar=true',
      type: 'post',
      dataType: 'json',
      //Para el id representante tomar los datos con los de session
      success: function(response) {
        console.log(response);
        $.each(response, function(i, item) {
          //console.log(response);

          consulta +=
            '<tr>' +
            '<td>' + cont + '</td>' +
            '<td>' + formatoDate(item.sa_conp_fecha_ingreso.date) + '</td>' +
            '<td>' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date) + '</td>' +
            '<td><u>' + item.sa_conp_nombres + '</u></td>' +
            '<td>' + item.sa_conp_tipo_consulta + '</td>' +
             '<td><a class="btn btn-primary btn-sm" target="_blank"  title="Enviar Mensaje" href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_ficha=' + item.sa_conp_id + '&id_estudiante=' +item.sa_fice_id + '&id_representante=' +item.sa_id_representante+ '&id_consulta=2&ver=1">' + '<i class="bx bx-show-alt"></i>' + '</a></td>' +
            '</tr>';

          cont++;
        });

        $('#tbl_datos').html(consulta);

        show_historial();
      }
    });
  }

   
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Atenciones Estudiantes
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

           
           <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="citas_actuales">
                
                    
                    
                    









            <!-- <div class="col-12 col-lg-3">

                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                Atenciones
                            </h5>
                        </div>

                        <div class="fm-menu">
                            <div class="list-group list-group-flush">
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=atencion_estudiante" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Estudiantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=atencion_representante" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Representantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Docente</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Administrativo</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 -->
           <!--  <div class="col-12 col-lg-9">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Estudiantes</h5>
                        </div>
                        <hr>

                        <div class="content">

                            <section class="content">
                                <div class="container-fluid">

                                    <div>
                                        <div class="col-sm-8 pt-3">
                                            <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Estudiantes">
                                        </div>
                                    </div>
                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Foto</th>
                                                    <th>Nombre</th>
                                                    <th>Sección/Grado/Paralelo</th>
                                                    <th>Edad</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_datos">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="myModal_historial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Nueva consulta</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha</th>
                          <th>Hora</th>
                          <th>Estudiante</th>
                          <th>Tipo de Atención</th>
                        </tr>
                      </thead>
                      <tbody id="tbl_datos">

                      </tbody>
                    </table>
                  </div>
            </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>