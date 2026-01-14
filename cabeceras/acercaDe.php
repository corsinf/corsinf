<?php
$imagen_acerca = '../img/de_sistema/apudata_acerca_de_general.jpg';
// if (($_SESSION['INICIO']['ACERCA_DE']) == '.' || $_SESSION['INICIO']['ACERCA_DE'] == '' || $_SESSION['INICIO']['ACERCA_DE'] == null) {
//     $imagen_acerca;
// } else {
//     $imagen_acerca = $_SESSION['INICIO']['ACERCA_DE'];
// }

//print_r($_SESSION['INICIO']);
?>

<div class="modal fade" id="myModal_acerca_de" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="text-center"><img src="<?= $imagen_acerca; ?>" style="width: 100%;"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>