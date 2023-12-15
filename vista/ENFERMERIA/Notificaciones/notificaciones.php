<script>

    

</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería </div>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Notificación Consultas del Estudiante por </li>
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
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Notificación Consultas del Estudiante</h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">



                                    <h3>Notificacion de Atención al Estudiante</h3>
                                    <div class="row m-1">
                                        <div class="col-sm-12">
                                            <label style="display:none;"><input type="checkbox" id="rbl_notificacion"> Se ha notificado correctamente</label>
                                            <div class="card">
                                                <div class="card-header bg-dark text-white py-2 cursor-pointer">
                                                    <div class="d-flex align-items-center">
                                                        <div class="compose-mail-title">Notificación</div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <div class="email-form">
                                                        <div class="mb-1">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Para:" id="txt_to">
                                                        </div>
                                                        <div class="mb-1">
                                                            <input type="text" class="form-control form-control-sm" value="Notificacion para Consultas en Salud" placeholder="Ausnto:" id="txt_subjet">
                                                        </div>
                                                        <div class="mb-1">
                                                            <textarea class="form-control" placeholder="Mensaje" rows="5" cols="10" id="mensaje">
                                                                PARA ATENCION NORMAL
                                                                FECHA: 
                                                                CERTIFICO QUE EL(LA) ESTUDIANTE: ****************
                                                                DEL GRADO "A", SE ENCOENTRÓ EN LE 
                                                                DEPAPARTAMENTO MÉDICO DESDE 08:30 HASTA 09:30 


                                                                PARA CERTIFICADO
                                                                HOY, *******************
                                                                CERTIFICO QUE REPRESENTANTE DE ANGELA DEL GRADO X PARALELO A ENTREGA
                                                                CERTIFICADO MÉDICO DE REPRESENTADO CON DIAGNÓSTICO
                                                                A001 - COLERA 

                                                                

															</textarea>
                                                            <div class="col-sm-12 text-center" id="div_mensaje">
                                                                <style>
                                                                    tableBorder {
                                                                        border: 1px solid black;
                                                                        border-collapse: collapse;
                                                                    }

                                                                    th,
                                                                    td {
                                                                        border: 1px solid black;
                                                                        padding: 8px;
                                                                    }
                                                                </style>
                                                                <table class="table">
                                                                    <thead>
                                                                        <th>Consulta</th>
                                                                        <th>Consulta</th>
                                                                        <th>Consulta</th>
                                                                        <th>Consulta</th>
                                                                    </thead>
                                                                    <tbody id="tbl_lineas">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="mb-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <div class="btn-group">
                                                                        <button type="button" id="btn_enviar" class="btn btn-primary btn-sm"><i class="bx bx-send"></i>Enviar</button>

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
                        </div><!-- /.container-fluid -->
                        </section>
                        <!-- /.content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>