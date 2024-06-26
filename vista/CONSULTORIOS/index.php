<script>
    function redireccionar(url_redireccion) {
        url_click = "inicio.php?mod=7&acc=" + url_redireccion;
        window.location.href = url_click;
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inicio</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <!-- <button class="btn btn btn-primary" onclick="tcp() ">Envio tcp</button> -->
            <div class="col-xl-12 mx-auto">


                <?php if (
                    $_SESSION['INICIO']['TIPO'] == 'DBA'
                ) { ?>
                    <h6 class="mb-0 text-uppercase">DASHBOARD</h6>
                    <hr>

                    <div class="row">

                        <div class="col-6 col-sm-6 col-md-4" id="pnl_pacientes" onclick="redireccionar('con_pacientes');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Pacientes</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-group'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4" id="pnl_pacientes" onclick="redireccionar('consulta_nutricion');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Consulta Nutrción</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-file' ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4" id="pnl_pacientes" onclick="redireccionar('config_examen');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Configuración Exámen</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-cog' ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4" id="pnl_pacientes" onclick="redireccionar('config_triage');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Configuración Triage</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-cog' ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       


                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>



<!-- Estilos para redireccionar -->
<script>
    $(document).ready(function() {
        $('.shadow-card').on('mouseover', function() {
            $(this).addClass('hoverEffect');
        });

        $('.shadow-card').on('mouseout', function() {
            $(this).removeClass('hoverEffect');
        });

        $('.shadow-card').on('click', function() {
            $(this).toggleClass('clickedEffect');
        });

        $(document).on('mouseout', '.shadow-card', function() {
            $(this).removeClass('clickedEffect');
        });

    });
</script>

<style>
    .card {
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .card.hoverEffect {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        background-color: rgba(45, 216, 34, 0.1);
    }

    .card.clickedEffect {
        background-color: rgba(128, 224, 122, 0.5);
    }
</style>
<!-- End -->