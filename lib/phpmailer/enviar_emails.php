<?php 
require_once(dirname(__DIR__,2).'/modelo/empresaM.php');
/**
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class enviar_emails
{
	// private $mail;
  private $modelo;
	function __construct()
	{
    $this->modelo = new empresaM();
		
	}


	function enviar_email($to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='soporte@corsinf.com',$archivos=false,$nombre='Email envio',$HTML=false)
	{
     // print_r($empresa);die();
    $host = 'corsinf.com';  
    $port =  465;
    $pass = '62839300' ;
    $user =  'soporte@corsinf.com';
    $secure = 'ssl';
    $respuesta = true;
    $correo_respaldo = 'soporte@corsinf.com';

    $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    $empresa = $this->modelo->datos_empresa($id_empresa);
    if(count($empresa)>0)
    {
       // print_r($empresa);die();
        $host = $empresa[0]['smtp_host'];  
        $port =  $empresa[0]['smtp_port'];  
        $pass = $empresa[0]['smtp_pass'];  
        $user =  $empresa[0]['smtp_usuario'];  
        $secure = $empresa[0]['smtp_secure'];  
        $correo_respaldo = $empresa[0]['smtp_usuario'];  
    }

   

		$to =explode(';', $to_correo);
    // print_r($to);die();
     foreach ($to as $key => $value) {
  		   $mail = new PHPMailer();
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;    
         $mail->CharSet  = 'UTF-8';
         $mail->Encoding = 'base64';         
         $mail->isSMTP();                              
         $mail->Host       = $host;
         $mail->SMTPAuth   = true;                             
         $mail->SMTPSecure = $secure;      
         $mail->Port       = $port;  
         $mail->Username   = $user;   
	       $mail->Password   = $pass;
	       $mail->setFrom($correo_respaldo,$nombre);
         // print_r($value);print_r('2');
         $mail->addAddress($value);
          // $mail->addAddress('ejfc19omoshiroi@gmail.com');     //Add a recipient   
         $mail->Subject = $titulo_correo;
         if($HTML)
         {
          $mail->isHTML(true);
         }
         $mail->Body = $cuerpo_correo; // Mensaje a enviar
         
         if($archivos)
         {
          foreach ($archivos as $key => $value) {
            // print_r(dirname(__DIR__,2).'/TEMP/'.$value);die();
           if(file_exists(dirname(__DIR__,2).'/TEMP/'.$value))
            {
                $mail->AddAttachment(dirname(__DIR__,2).'/TEMP/'.$value);
            }          
          }         
        }

        // print_r($mail);die();
          if (!$mail->send()) 
          {
          	$respuesta = false;
     	    }
    } 

    return $respuesta;
  }

  function enviar_email_errores($to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='soporte@corsinf.com',$archivos=false,$nombre='Email envio',$HTML=false)
{
    echo "<hr>🔍 INICIANDO ENVÍO DE CORREO<br>";
    
    $host = 'corsinf.com';  
    $port = 465;
    $pass = '62839300';
    $user = 'soporte@corsinf.com';
    $secure = 'ssl';
    $respuesta = true;
    $correo_respaldo = 'soporte@corsinf.com';

    // Verificar sesión
    if (!isset($_SESSION['INICIO']['ID_EMPRESA'])) {
        echo "❌ ERROR: No existe ID_EMPRESA en sesión<br>";
        echo "📋 SESSION disponible: <pre>" . print_r($_SESSION, true) . "</pre><br>";
        return false;
    }

    $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    echo "✅ ID Empresa: $id_empresa<br>";
    
    $empresa = $this->modelo->datos_empresa($id_empresa);
    echo "📊 Registros encontrados: " . count($empresa) . "<br>";
    
    if(count($empresa) > 0)
    {
        echo "✅ Usando configuración SMTP de BD<br>";
        $host = $empresa[0]['smtp_host'];  
        $port = $empresa[0]['smtp_port'];  
        $pass = $empresa[0]['smtp_pass'];  
        $user = $empresa[0]['smtp_usuario'];  
        $secure = $empresa[0]['smtp_secure'];  
        $correo_respaldo = $empresa[0]['smtp_usuario'];
        
        echo "📧 Host: <strong>$host</strong><br>";
        echo "📧 Puerto: <strong>$port</strong><br>";
        echo "📧 Usuario: <strong>$user</strong><br>";
        echo "📧 Seguridad: <strong>$secure</strong><br>";
        echo "📧 Password: " . (empty($pass) ? "❌ VACÍO" : "✅ Configurado (" . strlen($pass) . " caracteres)") . "<br>";
    } else {
        echo "⚠️ Usando configuración SMTP por defecto (hardcoded)<br>";
    }

    $to = explode(';', $to_correo);
    echo "📬 Destinatarios: " . count($to) . " -> [" . implode(', ', $to) . "]<br><br>";
    
    foreach ($to as $key => $value) {
        $value = trim($value);
        
        if (empty($value)) {
            echo "⏩ Correo vacío, omitiendo...<br>";
            continue;
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>";
        echo "📤 Enviando a: <strong>$value</strong><br>";
        
        try {
            $mail = new PHPMailer(true); // Modo excepción
            
            // ⚠️ ACTIVAR DEBUG (QUITAR ESTO EN PRODUCCIÓN)
            $mail->SMTPDebug = 3; // 0=off, 1=client, 2=server, 3=full
            $mail->Debugoutput = function($str, $level) {
                echo "🔧 $str<br>";
            };
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = $secure;
            $mail->Port = $port;
            $mail->Username = $user;
            $mail->Password = $pass;
            
            // 🔧 Agregar opciones SSL para evitar errores de certificado
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mail->setFrom($correo_respaldo, $nombre);
            $mail->addAddress($value);
            $mail->Subject = $titulo_correo;
            
            if($HTML) {
                $mail->isHTML(true);
            }
            
            $mail->Body = $cuerpo_correo;
            
            if($archivos) {
                foreach ($archivos as $archivo) {
                    $ruta = dirname(__DIR__,2).'/TEMP/'.$archivo;
                    if(file_exists($ruta)) {
                        $mail->addAttachment($ruta);
                        echo "📎 Adjunto agregado: $archivo<br>";
                    } else {
                        echo "⚠️ Archivo no encontrado: $ruta<br>";
                    }
                }
            }

            echo "🚀 Intentando enviar...<br>";
            
            if (!$mail->send()) {
                echo "❌ <strong>FALLO:</strong> " . $mail->ErrorInfo . "<br>";
                $respuesta = false;
            } else {
                echo "✅ <strong>ÉXITO!</strong> Correo enviado<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ <strong>EXCEPCIÓN:</strong> " . $e->getMessage() . "<br>";
            if (isset($mail)) {
                echo "❌ <strong>ErrorInfo:</strong> " . $mail->ErrorInfo . "<br>";
            }
            $respuesta = false;
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>";
    }

    echo "🏁 Proceso finalizado. Resultado: " . ($respuesta ? "✅ ÉXITO" : "❌ FALLÓ") . "<br><hr>";
    return $respuesta;
}


  function enviar_email_prueba($parametros,$to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='soporte@corsinf.com',$archivos=false,$nombre='Email envio',$HTML=false)
  {
     // print_r($parametros);die();
    $host = $parametros['host'];  
    $port =  $parametros['puerto'];
    $pass = $parametros['pass'] ;
    $user =  $parametros['usuario'];
    $secure = $parametros['secure'];
    $respuesta = true;
    $correo_respaldo = $parametros['usuario'];

    $to =explode(';', $to_correo);
    // print_r($to);die();
     foreach ($to as $key => $value) {
         $mail = new PHPMailer();
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
         $mail->isSMTP();                                           
         $mail->Host       = $host;
         $mail->SMTPAuth   = true;                             
         $mail->SMTPSecure = $secure;      
         $mail->Port       = $port;  
         $mail->Username   = $user;   
         $mail->Password   = $pass;
         $mail->setFrom($correo_respaldo,$nombre);
         // print_r($value);print_r('2');
         $mail->addAddress($value);
          // $mail->addAddress('ejfc19omoshiroi@gmail.com');     //Add a recipient   
         $mail->Subject = $titulo_correo;
         if($HTML)
         {
          $mail->isHTML(true);
         }
         $mail->Body = $cuerpo_correo; // Mensaje a enviar
         
         if($archivos)
         {
          foreach ($archivos as $key => $value) {
            // print_r(dirname(__DIR__,2).'/TEMP/'.$value);die();
           if(file_exists(dirname(__DIR__,2).'/TEMP/'.$value))
            {
                $mail->AddAttachment(dirname(__DIR__,2).'/TEMP/'.$value);
            }          
          }         
        }

        // print_r($mail);die();
          if (!$mail->send()) 
          {
            $respuesta = false;
          }
    } 

    return $respuesta;
  }  


}
?>