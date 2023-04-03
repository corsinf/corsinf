<?php
if(isset($_POST['generateBarcode'])) {
$barcodeText = trim($_POST['barcodeText']);
$barcodeType=$_POST['barcodeType'];
$barcodeDisplay=$_POST['barcodeDisplay'];
$barcodeSize=$_POST['barcodeSize'];
$printText=$_POST['printText'];
if($barcodeText != '') {
echo '<h4>Barcode:</h4>';
echo '<img class="barcode" alt="'.$barcodeText.'" src="../lib/barcode.php?text='.$barcodeText.'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'"/>';
} else {
echo '<div class="alert alert-danger">Enter product name or number to generate barcode!</div>';
}
}
?>