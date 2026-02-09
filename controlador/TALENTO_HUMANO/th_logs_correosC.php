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

        $empresa       = $_SESSION['INICIO']["RAZON_SOCIAL"] ?? 'Sistema';
        $support       = 'soporte@corsinf.com';
        $id_remitente  = $_SESSION['INICIO']["ID_USUARIO"] ?? null;

        $id_dep = $parametros['id_dep'] ?? '';
        $per_id = $parametros['per_id'];
        $loginUrl = 'https://corsinf.com:447/corsinf/login.php';
        $logoUrl  = 'https://corsinf.com:447/corsinf/img/Firmas/banner_2.jpg';

        try {

            if ($parametros['personas'] == 'nomina') {
                $personas_correos =  $this->personas->listar_personas_departamentos($id_dep, $per_id);

                $clave = $this->codigo_globales->generar_clave_digitos();
                $clave_enc = $this->codigo_globales->enciptar_clave($clave);

                $datos = array(
                    array('campo' => 'POLITICAS_ACEPTACION', 'dato' => '0'),
                    array('campo' => 'PASS', 'dato' => $clave_enc),
                );

                $where = array(
                    array('campo' => 'th_per_id', 'dato' => $per_id)
                );

                $this->personas->editar($datos, $where);

                $personas_correos =  $this->personas->listar_personas_departamentos($id_dep, $per_id);
            } else {
                $personas_correos = [];
            }

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
                        $envio = $this->email->enviar_email_errores(
                            $correo,
                            $htmlBody,
                            $titulo_correo,
                            $support,
                            false,
                            $empresa,
                            true
                        );

                        $envio_exitoso = is_array($envio) ? $envio['status'] : $envio;
                        $error_mensaje = is_array($envio) && isset($envio['error']) ? $envio['error'] : '';

                        if ($envio_exitoso) {
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
                            $detalle_log = !empty($error_mensaje)
                                ? substr($error_mensaje, 0, 500)
                                : 'Error desconocido al enviar correo';

                            $detalle[] = [
                                'correo' => $correo,
                                'estado' => 'ERROR',
                                'mensaje' => $detalle_log
                            ];
                        }
                    } catch (Exception $e) {
                        $fallidos++;
                        $estado_envio = 0;
                        $detalle_log = substr('Excepción: ' . $e->getMessage(), 0, 500);

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


    function crearPlantillaCredencialesHTML(
        $empresa,
        $usuario,
        $password,
        $nombreCompleto = '',
        $loginUrl = '',
        $logoUrl = '',
        $supportEmail = 'soporte@corsinf.com',
        $fecha_solicitud = ''
    ) {

        if (empty($fecha_solicitud)) {
            date_default_timezone_set('America/Guayaquil');
            $fecha_solicitud = date('d/m/Y H:i');
        }

        $logoHtml = $logoUrl
            ? "<img src=\"" . htmlspecialchars($logoUrl) . "\" alt=\"" . htmlspecialchars($empresa) . " logo\" style=\"width:160px;max-width:45%;height:auto;display:block;margin:0 auto 12px;\" />"
            : "<div style=\"width:110px;height:46px;border-radius:6px;background:#2c3e50;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-weight:600;font-size:20px;letter-spacing:1.2px;\">"
            . mb_substr(htmlspecialchars($empresa), 0, 2) . "</div>";

        $btn = $loginUrl
            ? "<a href=\"" . htmlspecialchars($loginUrl) . "\" target=\"_blank\" style=\"display:inline-block;padding:12px 32px;border-radius:6px;background:#2c3e50;color:#ffffff;text-decoration:none;font-weight:600;font-size:14px;box-shadow:0 2px 4px rgba(44,62,80,0.2);\">Acceder</a>"
            : "";

        $saludo = $nombreCompleto
            ? "<div style=\"background:#f8f9fa;border-left:4px solid #2c3e50;padding:14px 30px;margin-bottom:18px;\">
                <p style=\"margin:0;color:#5a6c7d;font-size:13px;\">Estimado/a</p>
                <p style=\"margin:4px 0 0;color:#2c3e50;font-size:17px;font-weight:600;\">" . htmlspecialchars($nombreCompleto) . "</p>
           </div>"
            : "";

        $html =
            '<!doctype html>
            <html>
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <title>Credenciales de Acceso</title>
            </head>

            <body style="font-family:Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#f5f5f5;">
            <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td align="center" style="padding:25px 15px;">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

            <tr>
            <td style="padding:30px 30px 26px;text-align:center;background:#2c3e50;">
            ' . $logoHtml . '
            <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:600;">' . htmlspecialchars($empresa) . '</h1>
            <p style="margin:4px 0 0;color:#ecf0f1;font-size:13px;">Talento Humano</p>
            </td>
            </tr>

            <tr>
            <td style="padding:30px 35px;">

            ' . $saludo . '

            <h2 style="margin:0 0 15px;font-size:18px;color:#2c3e50;">Credenciales de Acceso</h2>

            <p style="margin:0 0 18px;color:#5a6c7d;font-size:14px;line-height:1.6;">
            Se han generado sus credenciales para acceder al sistema. Por seguridad, cambie su contraseña en el primer inicio de sesión.
            </p>

            <table width="100%" style="background:#f8f9fa;border:1px solid #e1e5e9;border-radius:6px;margin-bottom:18px;">
            <tr>
            <td style="padding:18px 22px;">

            <p style="margin:0 0 4px;font-size:11px;color:#6c757d;font-weight:600;">USUARIO</p>
            <p style="margin:0 0 12px;font-size:15px;font-weight:600;color:#2c3e50;">' . htmlspecialchars($usuario) . '</p>

            <hr style="border:none;border-top:1px solid #dee2e6;margin:12px 0;">

            <p style="margin:0 0 4px;font-size:11px;color:#6c757d;font-weight:600;">CONTRASEÑA</p>
            <p style="margin:0 0 12px;font-size:15px;font-weight:600;font-family:Courier New,monospace;color:#2c3e50;">' . htmlspecialchars($password) . '</p>

            <hr style="border:none;border-top:1px solid #dee2e6;margin:12px 0;">

            <p style="margin:0 0 4px;font-size:11px;color:#6c757d;font-weight:600;">FECHA</p>
            <p style="margin:0;font-size:13px;color:#5a6c7d;">' . htmlspecialchars($fecha_solicitud) . '</p>

            </td>
            </tr>
            </table>

            ' . ($btn ? '<div style="text-align:center;margin-bottom:18px;">' . $btn . '</div>' : '') . '

            <div style="background:#fff5f5;border-left:4px solid #dc3545;padding:12px 18px;margin-bottom:18px;">
            <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:#721c24;">Recomendaciones de Seguridad</p>
            <ul style="margin:0;padding-left:18px;font-size:12px;color:#721c24;line-height:1.6;">
            <li>Cambie su contraseña al primer ingreso</li>
            <li>No comparta sus credenciales</li>
            <li>Cierre sesión al finalizar</li>
            </ul>
            </div>

            <hr style="border:none;border-top:1px solid #e9ecef;margin:20px 0;">

            <p style="margin:0;text-align:center;font-size:12px;color:#6c757d;">
            Soporte: <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color:#2c3e50;font-weight:600;">' . htmlspecialchars($supportEmail) . '</a>
            </p>

            </td>
            </tr>

            <tr>
            <td style="padding:14px 30px;background:#f8f9fa;text-align:center;border-top:1px solid #e9ecef;">
            <p style="margin:0;font-size:11px;color:#6c757d;">' . date('Y') . ' APUDATA. Todos los derechos reservados.</p>
            </td>
            </tr>

            </table>

            <p style="margin:12px 0 0;font-size:11px;color:#6c757d;text-align:center;">Correo automático, no responder.</p>

            </td>
            </tr>
            </table>
            </body>
            </html>';

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
