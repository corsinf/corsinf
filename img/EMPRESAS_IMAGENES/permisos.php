<?php
$directorio = '//CORS001/Share/ImagenesProyectos/ACTIVOS_DEMO/';

if (is_readable($directorio)) {
    echo "PHP tiene permisos para leer la carpeta.";
    $archivos = scandir($directorio);
    echo "<pre>";
    print_r($archivos);
    echo "</pre>";
} else {
    echo "PHP NO tiene permisos para leer la carpeta.";
}
