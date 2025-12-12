<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_logs_correosM.php');
include_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');

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
    function __construct()
    {
        $this->modelo = new th_logs_correosM();
         $this->email = new enviar_emails();
         $this->personas = new th_personasM();
    }

    function enviar_correo($parametros)
{
    echo "✔ La función enviar_correo() SI está entrando<br>";

    $id = $parametros['_id'];

    $empresa   = $_SESSION['INICIO']["RAZON_SOCIAL"]; // variable empresa
    $loginUrl  = 'https://corsinf.com:447/corsinf/login.php'; // o enlace de activación con token
    $logoUrl   = $_SESSION['INICIO']["LOGO"] ?? '';
    $support   = 'soporte@corsinf.com';


    $titulo_correo =  "Bienvenido a $empresa";
    $usuario   = 'usuario.prueba';
    $pass_prueba  =  'TempPass123!';
    $to_correo     =  'elvisfabian1296@gmail.com';

    if($id != null){
        $personas_correos = $this->personas->where('th_per_estado',1)->where('th_per_correo !',null)->listar();
    }else{
        $personas_correos = $this->personas->where('th_per_estado',1)->where('th_per_correo !',null)->where('th_per_id',$id)->listar();
    }


   foreach ($personas_correos as $key => $value) {

    $correo = trim($value['correo']);
    $password = trim($value['PASS']);
    $id = $value['_id'];


    $htmlBody = $this->crearPlantillaCredencialesHTML($empresa, $correo, $password, $loginUrl, $logoUrl, $support);
    
    if (filter_var($correo, FILTER_VALIDATE_EMAIL) && !empty($correo)) {

     
        $enviado = $this->email->enviar_email(
            $correo,
            $htmlBody,
            $titulo_correo,
            'soporte@corsinf.com',
            false,
            $empresa,
            true
        ); 
        $parametros = array(
            'correo_destino' => $correo,
            'asunto' => $titulo_correo,
            'detalle' => 'Correo enviado correctamente',
            'id_usuario' => $id ?? null,
            'enviado' => true,  // true si se envió, false si falló
            'estado' => 1
        );
        $resultado = $this->insertar_editar($parametros);
        
    } else {
        $parametros = array(
            'correo_destino' => $correo,
            'asunto' => 'Error al enviar correo',
            'detalle' => 'Correo con formato inválido - No enviado',
            'id_usuario' => $id ?? null,
            'enviado' => false,  
            'estado' => 1
        );
        
        $resultado = $this->insertar_editar($parametros);
    }
}
  return true;
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
            array('campo' => 'th_log_correo_destino', 'dato' => $parametros['correo_destino'] ?? null),
            array('campo' => 'th_log_asunto', 'dato' => $parametros['asunto'] ?? null),
            array('campo' => 'th_log_detalle', 'dato' => $parametros['detalle'] ?? null),
            array('campo' => 'id_usuario', 'dato' => isset($parametros['id_usuario']) ? intval($parametros['id_usuario']) : null),
            array('campo' => 'th_log_enviado', 'dato' => isset($parametros['enviado']) ? (bool)$parametros['enviado'] : null),
            array('campo' => 'th_log_estado', 'dato' => isset($parametros['estado']) ? intval($parametros['estado']) : 1),
            array('campo' => 'th_log_fecha_modificada', 'dato' => date('Y-m-d H:i:s')),
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


    function crearPlantillaCredencialesHTML($empresa, $usuario, $password, $loginUrl = '', $logoUrl = '', $supportEmail = 'soporte@corsinf.com') {
    $logoHtml = $logoUrl
        ? "<img src=\"" . htmlspecialchars($logoUrl) . "\" alt=\"" . htmlspecialchars($empresa) . " logo\" style=\"width:120px;max-width:30%;height:auto;display:block;margin:0 auto 12px;\" />"
        : "<div style=\"width:120px;height:48px;border-radius:6px;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-weight:700\">"
          . mb_substr(htmlspecialchars($empresa),0,2) . "</div>";

    $btn = $loginUrl
        ? "<a href=\"" . htmlspecialchars($loginUrl) . "\" target=\"_blank\" style=\"display:inline-block;padding:12px 22px;border-radius:8px;background:#0d6efd;color:#ffffff;text-decoration:none;font-weight:600;\">Iniciar sesión</a>"
        : "<span style=\"display:inline-block;padding:10px 18px;border-radius:8px;background:#6c757d;color:#ffffff;font-weight:600;\">Acceder</span>";

    $html = '
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial; margin:0; padding:0; background:#f4f6f8;">
  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td align="center" style="padding:24px 12px;">
        <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(20,24,40,0.08);">
          <tr>
            <td style="padding:28px 32px;text-align:center;border-bottom:1px solid #eef2f6;">
              ' . $logoHtml . '
              <h1 style="margin:6px 0 0;font-size:20px;color:#0b3b66;">' . htmlspecialchars($empresa) . '</h1>
              <p style="margin:6px 0 0;color:#6b7280;font-size:14px;">Credenciales de acceso</p>
            </td>
          </tr>

          <tr>
            <td style="padding:28px 36px 20px;">
              <p style="margin:0 0 12px;color:#334155;font-size:15px;">Hola,</p>

              <p style="margin:0 0 18px;color:#475569;font-size:14px;line-height:1.5;">
                Te enviamos tus credenciales para acceder a tu cuenta en <strong>' . htmlspecialchars($empresa) . '</strong>. Por favor, cambia tu contraseña la primera vez que ingreses.
              </p>

              <div style="background:#f8fafc;border:1px solid #e6eef8;padding:14px 16px;border-radius:8px;margin-bottom:18px;">
                <p style="margin:0 0 8px;font-size:13px;color:#0b3b66;font-weight:700;">Usuario</p>
                <p style="margin:0 0 10px;font-size:15px;color:#0b3b66;">' . htmlspecialchars($usuario) . '</p>

                <p style="margin:0 0 8px;font-size:13px;color:#0b3b66;font-weight:700;">Contraseña</p>
                <p style="margin:0;font-size:15px;color:#0b3b66;">' . htmlspecialchars($password) . '</p>
              </div>

              <div style="text-align:center;margin-bottom:18px;">' . $btn . '</div>

              <p style="margin:0 0 8px;color:#6b7280;font-size:13px;">Si el botón no funciona, copia y pega esta URL en tu navegador:</p>
              <p style="margin:0 0 18px;color:#0b3b66;font-size:13px;word-break:break-all;">' . ($loginUrl ? htmlspecialchars($loginUrl) : '<em>Sin enlace provisto</em>') . '</p>

              <hr style="border:none;border-top:1px solid #eef2f6;margin:18px 0;">

              <p style="margin:0;color:#6b7280;font-size:12px;line-height:1.5;">
                Si no solicitaste estas credenciales, ignora este mensaje o contacta a soporte: <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color:#0d6efd;text-decoration:none;">' . htmlspecialchars($supportEmail) . '</a>.
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:14px 20px;background:#fbfdff;text-align:center;color:#94a3b8;font-size:12px;">
              © ' . date('Y') . ' ' . htmlspecialchars($empresa) . ' — Todos los derechos reservados.
            </td>
          </tr>
        </table>

        <p style="margin:14px 0 0;color:#9aa4b2;font-size:12px;max-width:600px;text-align:center;">
          Este correo contiene información sensible. No compartas tus credenciales con nadie.
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