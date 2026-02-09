<?php
require_once(dirname(__DIR__, 2) . '/modelo/empresaM.php');
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


    function enviar_email($to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $nombre = 'Email envio', $HTML = false)
    {
        // print_r($empresa);die();
        $host = 'corsinf.com';
        $port =  465;
        $pass = '62839300';
        $user =  'soporte@corsinf.com';
        $secure = 'ssl';
        $respuesta = true;

        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $empresa = $this->modelo->datos_empresa($id_empresa);
        if (count($empresa) > 0) {
            // print_r($empresa);die();
            $host = $empresa[0]['smtp_host'];
            $port =  $empresa[0]['smtp_port'];
            $pass = $empresa[0]['smtp_pass'];
            $user =  $empresa[0]['smtp_usuario'];
            $secure = $empresa[0]['smtp_secure'];
            $correo_respaldo = $empresa[0]['smtp_usuario'];
        }



        $to = explode(';', $to_correo);
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
            $mail->setFrom($correo_respaldo, $nombre);
            // print_r($value);print_r('2');
            $mail->addAddress($value);
            // $mail->addAddress('ejfc19omoshiroi@gmail.com');     //Add a recipient   
            $mail->Subject = $titulo_correo;
            if ($HTML) {
                $mail->isHTML(true);
            }
            $mail->Body = $cuerpo_correo; // Mensaje a enviar

            if ($archivos) {
                foreach ($archivos as $key => $value) {
                    // print_r(dirname(__DIR__,2).'/TEMP/'.$value);die();
                    if (file_exists(dirname(__DIR__, 2) . '/TEMP/' . $value)) {
                        $mail->AddAttachment(dirname(__DIR__, 2) . '/TEMP/' . $value);
                    }
                }
            }

            // print_r($mail);die();
            if (!$mail->send()) {
                $respuesta = false;
            }
        }

        return $respuesta;
    }



    function enviar_email_prueba($parametros, $to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $nombre = 'Email envio', $HTML = false)
    {
        // print_r($parametros);die();
        $host = $parametros['host'];
        $port =  $parametros['puerto'];
        $pass = $parametros['pass'];
        $user =  $parametros['usuario'];
        $secure = $parametros['secure'];
        $respuesta = true;

        $to = explode(';', $to_correo);
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
            $mail->setFrom($correo_respaldo, $nombre);
            // print_r($value);print_r('2');
            $mail->addAddress($value);
            // $mail->addAddress('ejfc19omoshiroi@gmail.com');     //Add a recipient   
            $mail->Subject = $titulo_correo;
            if ($HTML) {
                $mail->isHTML(true);
            }
            $mail->Body = $cuerpo_correo; // Mensaje a enviar

            if ($archivos) {
                foreach ($archivos as $key => $value) {
                    // print_r(dirname(__DIR__,2).'/TEMP/'.$value);die();
                    if (file_exists(dirname(__DIR__, 2) . '/TEMP/' . $value)) {
                        $mail->AddAttachment(dirname(__DIR__, 2) . '/TEMP/' . $value);
                    }
                }
            }

            // print_r($mail);die();
            if (!$mail->send()) {
                $respuesta = false;
            }
        }

        return $respuesta;
    }


    function enviar_email_errores($to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $nombre = 'Email envio', $HTML = false)
    {
        $host = 'corsinf.com';
        $port = 465;
        $pass = '62839300';
        $user = 'soporte@corsinf.com';
        $secure = 'ssl';

        // Obtener configuración SMTP de la empresa
        if (isset($_SESSION['INICIO']['ID_EMPRESA'])) {
            $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
            $empresa = $this->modelo->datos_empresa($id_empresa);

            if (count($empresa) > 0) {
                $host = $empresa[0]['smtp_host'];
                $port = $empresa[0]['smtp_port'];
                $pass = $empresa[0]['smtp_pass'];
                $user = $empresa[0]['smtp_usuario'];
                $secure = $empresa[0]['smtp_secure'];
                $correo_respaldo = $empresa[0]['smtp_usuario'];
            }
        }

        $to = explode(';', $to_correo);
        $respuesta = ['status' => true, 'error' => '', 'detalles' => []];

        foreach ($to as $key => $value) {
            $value = trim($value);

            // Omitir correos vacíos
            if (empty($value)) {
                continue;
            }

            try {
                $mail = new PHPMailer(true); // Modo excepción activado

                // Configuración del mailer
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';
                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = $secure;
                $mail->Port = $port;
                $mail->Username = $user;
                $mail->Password = $pass;

                // Opciones SSL para evitar errores de certificado
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Configurar remitente y destinatario
                $mail->setFrom($correo_respaldo, $nombre);
                $mail->addAddress($value);
                $mail->Subject = $titulo_correo;

                // Configurar HTML
                if ($HTML) {
                    $mail->isHTML(true);
                }

                $mail->Body = $cuerpo_correo;

                // Agregar archivos adjuntos
                if ($archivos) {
                    foreach ($archivos as $archivo) {
                        $ruta = dirname(__DIR__, 2) . '/TEMP/' . $archivo;
                        if (file_exists($ruta)) {
                            $mail->addAttachment($ruta);
                        }
                    }
                }

                // Intentar enviar
                if (!$mail->send()) {
                    $respuesta['status'] = false;
                    $respuesta['error'] = $mail->ErrorInfo;
                    $respuesta['detalles'][] = [
                        'correo' => $value,
                        'error' => $mail->ErrorInfo
                    ];
                } else {
                    $respuesta['detalles'][] = [
                        'correo' => $value,
                        'error' => null
                    ];
                }
            } catch (Exception $e) {
                $respuesta['status'] = false;

                // Capturar error completo
                $error_completo = "Exception: " . $e->getMessage();

                if (isset($mail) && !empty($mail->ErrorInfo)) {
                    $error_completo .= " | PHPMailer ErrorInfo: " . $mail->ErrorInfo;
                }

                $respuesta['error'] = $error_completo;
                $respuesta['detalles'][] = [
                    'correo' => $value,
                    'error' => $error_completo
                ];
            }
        }

        return $respuesta;
    }
}
