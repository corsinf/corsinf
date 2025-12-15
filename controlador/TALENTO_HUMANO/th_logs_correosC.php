<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_logs_correosM.php');
include_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

$controlador = new th_logs_correosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['enviar_correo'])) {
    echo json_encode($controlador->enviar_correo($_POST['parametros']));
}

if (isset($_GET['buscar'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}

class th_logs_correosC
{
    private $modelo;
    private $email;
    private $personas;
    private $codigo_globales;
    
    function __construct()
    {
        $this->modelo = new th_logs_correosM();
         $this->email = new enviar_emails();
         $this->personas = new th_personasM();
         $this->codigo_globales = new codigos_globales();
    }

    

    function enviar_correo($parametros)
{
    // Validar parámetros requeridos
    if (!isset($parametros['per_id']) || !isset($parametros['enviar_credenciales'])) {
        return [
            'total'     => 0,
            'enviados'  => 0,
            'fallidos'  => 0,
            'detalle'   => [],
            'error'     => 'Parámetros faltantes'
        ];
    }

    $enviados = 0;
    $fallidos = 0;
    $detalle = [];
    
    // Validar variables de sesión
    $empresa       = $_SESSION['INICIO']["RAZON_SOCIAL"] ?? 'Sistema';
    $support       = $_SESSION['INICIO']["EMAIL"] ?? 'soporte@corsinf.com';
    $id_remitente  = $_SESSION['INICIO']["ID_USUARIO"] ?? null;
    
    $id = $parametros['per_id']; 
    $loginUrl = 'https://corsinf.com:447/corsinf/login.php';
    $logoUrl  = 'https://corsinf.com:447/corsinf/img/Firmas/banner_2.jpg';
    
    try {
        $personas_correos = $this->personas->listar_personas_correos($id);
        
        if (empty($personas_correos)) {
            return [
                'total'     => 0,
                'enviados'  => 0,
                'fallidos'  => 0,
                'detalle'   => [],
                'mensaje'   => 'No se encontraron personas para enviar correos'
            ];
        }

        foreach ($personas_correos as $value) {
            
            $correo = trim($value['th_per_correo'] ?? '');
            $usuario = trim($value['nombre_completo'] ?? '');
            $password = $this->codigo_globales->desenciptar_clave(trim($value['PASS'] ?? ''));


            $id_destinatario = $value['th_per_id'] ?? null;
            
            // Determinar asunto y cuerpo según el tipo de envío
            if ($parametros['enviar_credenciales'] == 0) {
                $asunto = $parametros['asunto'] ?? 'Notificación';
                $descripcion = $parametros['descripcion'] ?? '';
                $titulo_correo = $asunto;
                
                $htmlBody = $this->crearPlantillaMensajeHTML(
                    $empresa, 
                    $asunto, 
                    $descripcion, 
                    $usuario, 
                    $logoUrl,
                    $support
                );
            } else {
                $titulo_correo = "Bienvenido a $empresa";
                
                $htmlBody = $this->crearPlantillaCredencialesHTML(
                    $empresa, 
                    $correo, 
                    $password, 
                    $usuario, 
                    $loginUrl, 
                    $logoUrl, 
                    $support
                );
            }

            // Validar correo
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || empty($correo)) {
                $fallidos++;
                $estado_envio = 0;
                $detalle_log = 'Correo inválido o vacío';
                
                $detalle[] = [
                    'correo' => $correo ?: 'Sin correo',
                    'estado' => 'ERROR',
                    'mensaje' => $detalle_log
                ];
            } else {
                try {
                    $envio = $this->email->enviar_email(
                        $correo,
                        $htmlBody,
                        $titulo_correo,
                        $support,
                        false,
                        $empresa,
                        true
                    );

                    if ($envio) {
                        $enviados++;
                        $estado_envio = 1;
                        $detalle_log = 'Correo enviado correctamente';
                        
                        $detalle[] = [
                            'correo' => $correo,
                            'estado' => 'OK',
                            'mensaje' => $detalle_log
                        ];
                    } else {
                        $fallidos++;
                        $estado_envio = 0;
                        $detalle_log = 'Error al enviar correo';
                        
                        $detalle[] = [
                            'correo' => $correo,
                            'estado' => 'ERROR',
                            'mensaje' => $detalle_log
                        ];
                    }
                } catch (Exception $e) {
                    $fallidos++;
                    $estado_envio = 0;
                    $detalle_log = 'Excepción: ' . $e->getMessage();
                    
                    $detalle[] = [
                        'correo' => $correo,
                        'estado' => 'ERROR',
                        'mensaje' => $detalle_log
                    ];
                }
            }
            try {
                $this->insertar_editar([
                    'correo_destino'      => $correo,
                    'asunto'              => $titulo_correo,
                    'detalle'             => $detalle_log,
                    'id_remitente'        => $id_remitente,
                    'tabla_remitente'     => 'USUARIOS',
                    'id_destinatario'     => $id_destinatario,
                    'tabla_destinatario'  => 'th_personas',
                    'enviado'             => $estado_envio,
                    'estado'              => 1
                ]);
            } catch (Exception $e) {
                error_log("Error al registrar log de correo: " . $e->getMessage());
            }
        }

    } catch (Exception $e) {
        return [
            'total'     => 0,
            'enviados'  => 0,
            'fallidos'  => 0,
            'detalle'   => [],
            'error'     => 'Error crítico: ' . $e->getMessage()
        ];
    }

    return [
        'total'     => $enviados + $fallidos,
        'enviados'  => $enviados,
        'fallidos'  => $fallidos,
        'detalle'   => $detalle
    ];
}




 
    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar(); // lista todos
        } else {
            $datos = $this->modelo->where('th_log_id', $id)->listar();
        }
        return $datos;
    }

   
    function insertar_editar($parametros)
    {
       $datos = array(
          array('campo' => 'th_log_correo_destino', 'dato' => $parametros['correo_destino']),
          array('campo' => 'th_log_asunto', 'dato' => $parametros['asunto']),
          array('campo' => 'th_log_detalle', 'dato' => $parametros['detalle']),
          array(
              'campo' => 'th_log_enviado',
              'dato' => isset($parametros['enviado']) ? ($parametros['enviado'] ? 1 : 0) : 0
          ),
          array(
              'campo' => 'th_log_estado',
              'dato' => isset($parametros['estado']) ? intval($parametros['estado']) : 1
          ),
          array(
              'campo' => 'id_usu_per_remitente',
              'dato' => isset($parametros['id_remitente']) ? intval($parametros['id_remitente']) : null
          ),
          array(
              'campo' => 'tabla_remitente',
              'dato' => $parametros['tabla_remitente'] ?? null
          ),
          array(
              'campo' => 'id_usu_per_destinatario',
              'dato' => isset($parametros['id_destinatario']) ? intval($parametros['id_destinatario']) : null
          ),
          array(
              'campo' => 'tabla_destinatario',
              'dato' => $parametros['tabla_destinatario'] ?? null
          )
      );
       
        if (empty($parametros['_id'])) {
            $datos[] = array('campo' => 'th_log_fecha_creada', 'dato' => date('Y-m-d H:i:s'));

            $insertId = $this->modelo->insertar_id($datos); 
            return $insertId ?: false;
        } else {
            $where[0]['campo'] = 'th_log_id';
            $where[0]['dato'] = $parametros['_id'];

            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }

    
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_log_estado', 'dato' => 0),
            array('campo' => 'th_log_fecha_modificada', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_log_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

    
    function buscar($parametros)
    {
        $lista = array();
        $query = $parametros['query'] ?? '';

        // buscar por correo o asunto
        $concat = "th_log_correo_destino, th_log_asunto";
        $datos = $this->modelo->like($concat, $query);

        foreach ($datos as $value) {
            $text = ($value['th_log_correo_destino'] ?? '') . ' - ' . ($value['th_log_asunto'] ?? '');
            $lista[] = array('id' => $value['th_log_id'], 'text' => $text, 'data' => $value);
        }

        return $lista;
    }


function crearPlantillaCredencialesHTML($empresa, $usuario, $password, $nombreCompleto = '', $loginUrl = '', $logoUrl = '', $supportEmail = 'soporte@corsinf.com', $fecha_solicitud = '') {
    
    if (empty($fecha_solicitud)) {
        date_default_timezone_set('America/Guayaquil');
        $fecha_solicitud = date('d/m/Y H:i');
    }
    
    // Logo elegante y profesional
    $logoHtml = $logoUrl
        ? "<img src=\"" . htmlspecialchars($logoUrl) . "\" alt=\"" . htmlspecialchars($empresa) . " logo\" style=\"width:180px;max-width:45%;height:auto;display:block;margin:0 auto 20px;\" />"
        : "<div style=\"width:120px;height:50px;border-radius:6px;background:#2c3e50;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-weight:600;font-size:22px;letter-spacing:1.5px;\">"
          . mb_substr(htmlspecialchars($empresa), 0, 2) . "</div>";

    // Botón elegante y profesional
    $btn = $loginUrl
        ? "<a href=\"" . htmlspecialchars($loginUrl) . "\" target=\"_blank\" style=\"display:inline-block;padding:14px 40px;border-radius:6px;background:#2c3e50;color:#ffffff;text-decoration:none;font-weight:600;font-size:15px;letter-spacing:0.5px;box-shadow:0 2px 4px rgba(44,62,80,0.2);transition:all 0.3s;\">Acceder</a>"
        : "";

    // Saludo personalizado
    $saludo = $nombreCompleto 
        ? "<div style=\"background:#f8f9fa;border-left:4px solid #2c3e50;padding:18px 45px;margin-bottom:30px;\">
             <p style=\"margin:0;color:#5a6c7d;font-size:14px;font-weight:400;\">Estimado/a</p>
             <p style=\"margin:4px 0 0;color:#2c3e50;font-size:18px;font-weight:600;letter-spacing:0.3px;\">" . htmlspecialchars($nombreCompleto) . "</p>
           </div>"
        : "";

    $html = '
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Credenciales de Acceso - ' . htmlspecialchars($empresa) . '</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; margin:0; padding:0; background:#f5f5f5;">
  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td align="center" style="padding:40px 20px;">
        <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:2px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
          
          <!-- Header -->
          <tr>
            <td style="padding:45px 40px 40px;text-align:center;background:#2c3e50;">
              ' . $logoHtml . '
              <h1 style="margin:0 0 8px;font-size:24px;color:#ffffff;font-weight:600;letter-spacing:0.8px;">' . htmlspecialchars($empresa) . '</h1>
              <p style="margin:0;color:rgba(255,255,255,0.9);font-size:14px;font-weight:400;letter-spacing:0.3px;">Talento Humano</p>
            </td>
          </tr>

          <!-- Contenido -->
          <tr>
            <td style="padding:45px 45px 40px;">
              
              ' . $saludo . '
              
              <h2 style="margin:0 0 25px;font-size:20px;color:#2c3e50;font-weight:600;letter-spacing:0.3px;">Credenciales de Acceso</h2>
              
              <p style="margin:0 0 30px;color:#5a6c7d;font-size:15px;line-height:1.7;">
                Se han generado tus credenciales para acceder al sistema. Por tu seguridad, te recomendamos cambiar la contraseña después del primer inicio de sesión.
              </p>

              <!-- Credenciales -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fa;border:1px solid #e1e5e9;border-radius:6px;margin-bottom:30px;">
                <tr>
                  <td style="padding:28px 30px;">
                    
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="padding-bottom:20px;">
                          <p style="margin:0 0 8px;font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Usuario</p>
                          <p style="margin:0;font-size:16px;color:#2c3e50;font-weight:600;letter-spacing:0.2px;">' . htmlspecialchars($usuario) . '</p>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding-bottom:20px;border-top:1px solid #dee2e6;padding-top:20px;">
                          <p style="margin:0 0 8px;font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Contraseña</p>
                          <p style="margin:0;font-size:16px;color:#2c3e50;font-weight:600;font-family:\'Courier New\', monospace;letter-spacing:0.5px;">' . htmlspecialchars($password) . '</p>
                        </td>
                      </tr>
                      <tr>
                        <td style="border-top:1px solid #dee2e6;padding-top:20px;">
                          <p style="margin:0 0 8px;font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Fecha de Solicitud</p>
                          <p style="margin:0;font-size:14px;color:#5a6c7d;font-weight:500;">' . htmlspecialchars($fecha_solicitud) . '</p>
                        </td>
                      </tr>
                    </table>
                    
                  </td>
                </tr>
              </table>

              ' . ($btn ? '<div style="text-align:center;margin-bottom:30px;">' . $btn . '</div>' : '') . '

              ' . ($loginUrl ? '
              <div style="background:#fff9e6;border-left:4px solid #ffc107;padding:16px 22px;margin-bottom:30px;border-radius:2px;">
                <p style="margin:0 0 8px;color:#856404;font-size:12px;font-weight:600;letter-spacing:0.3px;">Enlace de Acceso</p>
                <p style="margin:0;color:#856404;font-size:13px;word-break:break-all;font-family:\'Courier New\', monospace;line-height:1.5;">' . htmlspecialchars($loginUrl) . '</p>
              </div>
              ' : '') . '

              <!-- Recomendaciones de seguridad -->
              <div style="background:#fff5f5;border-left:4px solid #dc3545;padding:16px 22px;margin-bottom:30px;border-radius:2px;">
                <p style="margin:0 0 12px;color:#721c24;font-size:12px;font-weight:600;letter-spacing:0.3px;">Recomendaciones de Seguridad</p>
                <ul style="margin:0;padding-left:20px;color:#721c24;font-size:13px;line-height:1.8;">
                  <li style="margin-bottom:6px;">Cambia tu contraseña al iniciar sesión por primera vez</li>
                  <li style="margin-bottom:6px;">No compartas tus credenciales con terceros</li>
                  <li>Cierra sesión al finalizar</li>
                </ul>
              </div>

              <hr style="border:none;border-top:1px solid #e9ecef;margin:30px 0;">

              <p style="margin:0;color:#6c757d;font-size:13px;line-height:1.6;text-align:center;">
                ¿Necesitas ayuda? Comunícate con nosotros<br>
                <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color:#2c3e50;text-decoration:none;font-weight:600;">' . htmlspecialchars($supportEmail) . '</a>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px 40px;background:#f8f9fa;text-align:center;border-top:1px solid #e9ecef;">
              <p style="margin:0 0 5px;color:#6c757d;font-size:12px;font-weight:500;">
                ' . date('Y') . ' APUDATA. Todos los derechos reservados.
              </p>
              <p style="margin:0;color:#adb5bd;font-size:11px;">
                Este mensaje contiene información confidencial.
              </p>
            </td>
          </tr>
        </table>

        <p style="margin:18px 0 0;color:#6c757d;font-size:11px;max-width:600px;text-align:center;">
          Este es un correo automático. Por favor no responder.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
    ';

    return $html;
}


function crearPlantillaMensajeHTML(
    $empresa,
    $asunto,
    $descripcion,
    $nombreCompleto = '',
    $logoUrl = '',
    $supportEmail = 'soporte@corsinf.com',
    $fecha_envio = ''
) {
    if (empty($fecha_envio)) {
        date_default_timezone_set('America/Guayaquil');
        $fecha_envio = date('d/m/Y H:i:s');
    }

    // Logo elegante
    $logoHtml = $logoUrl
        ? "<img src=\"" . htmlspecialchars($logoUrl) . "\" alt=\"Logo\" style=\"width:180px;max-width:45%;height:auto;display:block;margin:0 auto 20px;\" />"
        : "<div style=\"width:120px;height:50px;border-radius:6px;background:#2c3e50;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-weight:600;font-size:22px;letter-spacing:1.5px;\">"
          . mb_substr(htmlspecialchars($empresa), 0, 2) . "</div>";

    // Saludo personalizado
    $saludo = $nombreCompleto 
        ? "<div style=\"background:#f8f9fa;border-left:4px solid #2c3e50;padding:18px 45px;margin-bottom:25px;\">
             <p style=\"margin:0;color:#5a6c7d;font-size:14px;font-weight:400;\">Estimado/a</p>
             <p style=\"margin:4px 0 0;color:#2c3e50;font-size:18px;font-weight:600;letter-spacing:0.3px;\">" . htmlspecialchars($nombreCompleto) . "</p>
           </div>"
        : "";

    $html = '
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>' . htmlspecialchars($asunto) . ' - ' . htmlspecialchars($empresa) . '</title>
</head>

<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; margin:0; padding:0; background:#f5f5f5;">
  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td align="center" style="padding:40px 20px;">

        <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:2px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="padding:45px 40px 40px;text-align:center;background:#2c3e50;">
              ' . $logoHtml . '
              <h1 style="margin:0 0 8px;font-size:24px;color:#ffffff;font-weight:600;letter-spacing:0.8px;">' . htmlspecialchars($empresa) . '</h1>
              <p style="margin:0;color:rgba(255,255,255,0.9);font-size:14px;font-weight:400;letter-spacing:0.3px;">Notificación</p>
            </td>
          </tr>

          <!-- Contenido -->
          <tr>
            <td style="padding:40px 45px;">
              
              ' . $saludo . '

              <!-- Asunto -->
              <div style="background:#f8f9fa;border-left:4px solid #2c3e50;padding:18px 24px;margin-bottom:25px;border-radius:2px;">
                <p style="margin:0;color:#2c3e50;font-size:17px;font-weight:600;letter-spacing:0.3px;">' . htmlspecialchars($asunto) . '</p>
              </div>
              
              <p style="margin:0 0 25px;color:#5a6c7d;font-size:15px;line-height:1.8;">
                ' . nl2br(htmlspecialchars($descripcion)) . '
              </p>

              <!-- Fecha -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fa;border:1px solid #e1e5e9;border-radius:6px;margin-bottom:30px;">
                <tr>
                  <td style="padding:16px 22px;">
                    <p style="margin:0 0 6px;font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Fecha de Notificación</p>
                    <p style="margin:0;font-size:14px;color:#2c3e50;font-weight:500;">' . htmlspecialchars($fecha_envio) . '</p>
                  </td>
                </tr>
              </table>

              <hr style="border:none;border-top:1px solid #e9ecef;margin:30px 0;">

              <!-- Soporte -->
              <div style="background:#fff9e6;border-left:4px solid #ffc107;padding:16px 22px;border-radius:2px;">
                <p style="margin:0 0 8px;color:#856404;font-size:12px;font-weight:600;letter-spacing:0.3px;">¿Necesitas Ayuda?</p>
                <p style="margin:0;color:#856404;font-size:13px;">
                  Contáctanos en 
                  <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color:#856404;text-decoration:underline;font-weight:600;">' 
                  . htmlspecialchars($supportEmail) . '</a>
                </p>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px 40px;background:#f8f9fa;text-align:center;border-top:1px solid #e9ecef;">
              <p style="margin:0 0 5px;color:#6c757d;font-size:12px;font-weight:500;">
                ' . date('Y') . ' APUDATA. Todos los derechos reservados.
              </p>
              <p style="margin:0;color:#adb5bd;font-size:11px;">
                Este mensaje es informativo y confidencial.
              </p>
            </td>
          </tr>

        </table>

        <p style="margin:18px 0 0;color:#6c757d;font-size:11px;max-width:600px;text-align:center;">
          Este es un correo automático. Por favor no responder.
        </p>

      </td>
    </tr>
  </table>
</body>
</html>
    ';

    return $html;
}



}