<?php
// Ruta completa al archivo (ajusta esta ruta)

$empresa = $_GET['emp'] ?? '';
$dir = $_GET['dir'] ?? '';
$nombre_imagen = $_GET['nombre'] ?? '';

// print_r($empresa); exit(); die();

// $url = 'C:/Users/Jaime/Pictures/fotos';
$url = '//CORS001/Share/ImagenesProyectos';


// Definir la ruta base según el directorio
$ruta_base = match ($dir) {
   'activos'    => "$url/$empresa/ACTIVOS",
   'personas'  => "$url/$empresa/PERSONAS",
   // 'ejemplo'  => "$url/$empresa/ejemplo",
   default      => null,
};

// Si no se reconoce el directorio, mostrar imagen por defecto
if (!$ruta_base || empty($nombre_imagen)) {
   header('Content-Type: image/gif');
   readfile('sin_imagen.gif');
   exit;
}

// Ruta completa al archivo
$archivo = "$ruta_base/$nombre_imagen";

// Validar si existe el archivo
if (file_exists($archivo)) {
   $mime = obtener_mime_tipo($archivo);
   header("Content-Type: $mime");
   readfile($archivo);
} else {
   header('Content-Type: image/gif');
   readfile('sin_imagen.gif');
}



function obtener_mime_tipo($archivo)
{
   $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

   return match ($extension) {
      'jpg', 'jpeg' => 'image/jpeg',
      'png'         => 'image/png',
      'gif'         => 'image/gif',
      'bmp'         => 'image/bmp',
      'webp'        => 'image/webp',
      default       => 'application/octet-stream'
   };
}
