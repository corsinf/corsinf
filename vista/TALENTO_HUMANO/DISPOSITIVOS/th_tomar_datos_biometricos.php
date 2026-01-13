<script type="text/javascript">
    $(document).ready(function() {

    });

    function cambiar(finger)
    {
        $('.btn-outline-primary').removeClass('active');
        $('#img_palma').attr('src','../img/de_sistema/palma'+finger+'.gif');
        $('#btn_finger_'+finger).addClass('active');
        $('#txt_dedo_num').val(finger);
    }

    function leerDedo()
    {
        $('#myModal_espera').modal('show');
        var parametros = 
        {
            'dispostivos':$('#ddl_dispositivos option:selected').text(),
            'dispostivospwd':$('#ddl_dispositivos').val(),
            'usuario':$('#ddl_usuario').val(),
            'dedo':$('#txt_dedo_num').val(),
        }
        $.ajax({
            data:  {parametros:parametros},
            url:   '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?CapturarFinger=true',
            type:  'post',
            dataType: 'json',
            success:  function (response) { 
                $('#myModal_espera').modal('hide');

                if(response.respuesta.resp==1)
                {
                    Swal.fire("Huella capturada",response.respuesta.patch,"success");
                }
                console.log(response);
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
                            Vista habiltiado solo para DB
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
                    		<div class="col-sm-6">
                                <select class="form-select" id="ddl_dispositivos">
                                    <option value="" >Seleccione Dispositivo</option>
                                    <option value="Data12/*">192.168.100.132</option>
                                </select>
                                 <select class="form-select" id="ddl_usuario">
                                    <option value="" >Seleccione usuario</option>
                                    <option value="1">usuario 1</option>
                                </select>
                    			<br>
                    			<button type="button" class="btn btn-primary">Detectar Finger</button>
                                <table class="table table-hover">
                                    <thead>
                                        <th>Numero de Dedo</th>
                                        <th>Dato</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                    		</div>
                    		<div class="col-sm-6">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="col">
                                            <div class="btn-group" role="group" aria-label="First group">
                                                <button type="button" id="btn_finger_1" class="btn btn-sm btn-outline-primary active" onclick="cambiar(1)">Dedo 1</button>
                                                <button type="button" id="btn_finger_2" class="btn btn-sm btn-outline-primary " onclick="cambiar(2)">Dedo 2</button>
                                                <button type="button" id="btn_finger_3" class="btn btn-sm btn-outline-primary " onclick="cambiar(3)">Dedo 3</button>
                                                <button type="button" id="btn_finger_4" class="btn btn-sm btn-outline-primary " onclick="cambiar(4)">Dedo 4</button>
                                                <button type="button" id="btn_finger_5" class="btn btn-sm btn-outline-primary " onclick="cambiar(5)">Dedo 5</button>
                                                <input type="hidden" name="txt_dedo_num" id="txt_dedo_num">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="leerDedo()">Comenzar Lectura</button>     
                                    </div>
                                </div>

                                <img id="img_palma" src="../img/de_sistema/palma1.gif">                        
                    		</div>
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


  <?php 

//                                 $file = 'C:\Users\usuario\Downloads\EN-HCNetSDK\sdkexample\example\C# demo\8-ACS_Optimization_ALL\FingerPrintManagement\FingerPrintManagement\bin\capFingerPrint.dat';

// // Leer los datos binarios del archivo
// $data = file_get_contents($file);
// if ($data === false) {
//     die('No se pudo leer el archivo.');
// }


// // Tamaño total de los datos
// $dataSize = strlen($data);

// // Intentamos encontrar dimensiones compatibles (ejemplo: 32x16, 64x8, etc.)
// $width = 16;  // Ajusta según sea necesario
// $height = intdiv($dataSize, $width); 

// if ($width * $height !== $dataSize) {
//     die('Las dimensiones no coinciden con el tamaño del archivo.');
// }

// // Crear una imagen en blanco y negro
// $image = imagecreate($width, $height);

// // Rellenar la paleta de grises
// $colors = [];
// for ($i = 0; $i < 256; $i++) {
//     $colors[$i] = imagecolorallocate($image, $i, $i, $i);
// }

// // Dibujar los píxeles en la imagen
// for ($y = 0; $y < $height; $y++) {
//     for ($x = 0; $x < $width; $x++) {
//         $gray = ord($data[$y * $width + $x]);
//         imagesetpixel($image, $x, $y, $colors[$gray]);
//     }
// }

// // Guardar la imagen como PNG
// imagepng($image, 'huella.png');

// // Liberar memoria
// imagedestroy($image);

// echo "Imagen generada exitosamente.\n";

                                ?>