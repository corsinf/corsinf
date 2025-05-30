<?php
// Ruta completa al archivo (ajusta esta ruta)

$nombre_imagen = $_GET['nombre'] ?? '';

 if ($nombre_imagen != '') {
	 $archivo = "//CORS001/Share/ImagenesProyectos/ACTIVOS_DEMO/$nombre_imagen";

     if (file_exists($archivo)) {
         header('Content-Type: image/gif');
         readfile($archivo);
     } else {
        header('Content-Type: image/gif');
        readfile('//CORS001/Share/ImagenesProyectos/ACTIVOS_DEMO/sin_imagen.gif');
     }
 } else{
    header('Content-Type: image/gif');
    readfile('//CORS001/Share/ImagenesProyectos/ACTIVOS_DEMO/sin_imagen.gif');
 }