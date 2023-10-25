<?php
require 'vendor/autoload.php'; // Si utilizaste Composer para instalar PHPWord

// Crea un nuevo objeto de PHPWord
$phpWord = new PhpOffice\PhpWord\PhpWord();

// Crea una sección en el documento
$section = $phpWord->addSection();

// Agrega contenido al documento
$section->addText('¡Hola, esto es un documento de Word generado desde PHP!');

// Guarda el documento en un archivo
$filename = 'documento_word.docx';
$phpWord->save($filename);

// Descarga el archivo
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
?>