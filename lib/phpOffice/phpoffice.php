
<?php

// include(dirname(__DIR__,1).'/pdf2word/vendor/ottosmops/pdftotext/src/Extract.php');
require 'vendor/autoload.php';
use Ottosmops\Pdftotext\Pdftotext;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$pdfFilePath = dirname(__DIR__,2).'/TEMP/ACTA_SALIDA_DE_BIENES_DEL_CAMPUS.pdf'; // Ruta al archivo PDF
$wordFilePath = dirname(__DIR__,2).'/TEMP/ACTA_SALIDA_DE_BIENES_DEL_CAMPUS.docx'; // Ruta para guardar el 

// Extraer texto del PDF
$pdftotext = new Pdftotext();
$text = $pdftotext->textFromPdfFile($pdfFilePath);

// Crear un nuevo documento Word
$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText($text);

// Guardar el documento Word
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($wordFilePath);

echo "Conversión completa.";
?>
