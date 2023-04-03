<?php
// Si son demasiados archivos colocamos el tiempo limite en 0 (sin limite)
set_time_limit(0);
 /*
  @autor : Arthusu
  @fecha : 15/03/2015
  @descripcion : Compresor de todos los archivos del directorio
  @archivo : compresor.php
 */

// print_r(__DIR__);die();
 // definimos las variables (las podemos modificar segun sea nuestro caso)
 $nombre_del_archivo_comprimido = "../descargas/comprimido.zip"; // el nombre del archivo a generar
 if(file_exists($nombre_del_archivo_comprimido))
 {
   unlink($nombre_del_archivo_comprimido);
 }

 $ruta_relativa = "../descargas/"; // el directorio que nos encontremos. Ejemplo: images/algo/ para generar un link correcto

 if(phpversion() >= "5.2.0"){ // verificamos que contengan la version correcta para crear archivos zip
  $zip = new ZipArchive(); // Creamos un nuevo objeto
  if($zip->open($nombre_del_archivo_comprimido,ZIPARCHIVE::CREATE) === TRUE){ // creamos un archivo
   $iterator = new RecursiveDirectoryIterator(dirname(__DIR__)."/img/"); // recorremos el directorio
   $recursiveIterator = new RecursiveIteratorIterator($iterator); // recorremos los directorios dentro de otros directorios y asi sucesivamente

   foreach($recursiveIterator as $entry){ // recorremos los archivos
     if(is_file($entry->getRealPath())){ // verificamos que sea archivo
      // debug
      // echo $entry->getRealPath()."\n";
      $ruta = explode('\\',$entry->getRealPath());
      $num = count($ruta);

      // print_r($ruta);die();
      if($ruta[$num-1]!='.gif')
      {
         $zip->addFile($entry->getRealPath(),'imagenes/'.$ruta[$num-1]); // lo agregamos al zip
      }
    }
   }
   // mostramos un enlace con los archivos
   echo $zip->numFiles .' archivos comprimidos: <a href="http://'.strip_tags($_SERVER["HTTP_HOST"]. "/" . $ruta_relativa . $nombre_del_archivo_comprimido).'">Descargar Archivo</a>';
  }else{
   // si no se crea el archivo mostramos el error
   echo "Ha ocurrido un error: ".$zip->getStatusString();
  }
  // cerramos el archivo zip
  $zip->close();
 }else{
  // si la version no es correcta mostramos un mensaje
  echo "Su version de PHP debe ser 5.2.0 o superior";
 }
?>