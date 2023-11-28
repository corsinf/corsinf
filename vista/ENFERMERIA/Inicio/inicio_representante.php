<script type="text/javascript">
    $(document).ready(function() {
        var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>';
        console.log(id);
        if (id != '') {
            Editar(id)
        }

    });

    function Editar(id) {
        // $('#nuevo_tipo_usuario').modal('show');
        // $('#btn_opcion').text('Editar');
        // $('#exampleModalLongTitle').text('Editar tipo de usuario');
        var noconcurente = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';
        var parametros = {
            'id': id,
            'query': '',
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?datos_usuarios=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {

                console.log(response);
                $('#txt_nombre').val(response[0].nombres);
                $('#txt_apellido').val(response[0].ape);
                $('#txt_ci').val(response[0].ci);
                $('#txt_telefono').val(response[0].tel);
                $('#txt_emial').val(response[0].email);
                $('#txt_emial_2').val(response[0].email);
                // $('#ddl_tipo_usuario').append($('<option>',{value: response[0].idt, text:response[0].tipo,selected: true }));;
                $('#txt_nick').val(response[0].nick);
                $('#txt_pass').val(response[0].pass);
                var passlen = response[0].pass.length;
                $('#pass').text('*'.repeat(passlen));

                $('#txt_dir').val(response[0].dir);
                $('#txt_id').val(response[0].id);
                if (response[0].foto != '' && response[0].foto != null) {
                    $('#img_foto').attr('src', response[0].foto);
                }
                $('#txt_link_web').text(response[0].web);
                $('#txt_link_tw').text(response[0].tw);
                $('#txt_link_in').text(response[0].ins);
                $('#txt_link_fb').text(response[0].fb);

                if (noconcurente != '') {
                    $('#panel_apellido').css('display', 'none');
                }

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
                            Inicio
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-success" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#inicio" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Inicio</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#estudiantes" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Estudiantes</div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="inicio" role="tabpanel">

                                <div class="row">

                                    <div class="col-6 mx-5">
                                        <table class="table mb-0" style="width:100%">
                                            <tbody>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Cédula:</th>
                                                    <td><p id="txt_nombre">1</p> <i class='bx bxs-id-card'></i></td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Nombres:</th>
                                                    <td>Mark Ryden</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Apellidos:</th>
                                                    <td>Tipan Páez</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Sexo:</th>
                                                    <td>Masculino <i class='bx bx-female'></i> <i class='bx bx-male'></i></td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Fecha de Nacimiento:</th>
                                                    <td>25 de mayo 2006</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Edad Actual:</th>
                                                    <td>17 años</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Correo Electrónico:</th>
                                                    <td>mark@mail.com <i class='bx bx-envelope'></i></td>
                                                </tr>
                                                <tr>
                                                    <th style="width:40%" class="table-success text-end">Teléfono:</th>
                                                    <td>0999865412 <i class='bx bxs-phone'></i></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>



                            </div>
                            <div class="tab-pane fade" id="estudiantes" role="tabpanel">
                                <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>