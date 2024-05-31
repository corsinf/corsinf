<?php

include('Artemis.php');
//include('HIK_TCP.php');

/*
$notificaciones = new Notificaiones('28519009', 'kTnwcJUu7OQEGHCVGSJQ');

if (isset($_GET['lista_eventos'])) {

    echo json_encode($notificaciones->lista_Eventos());
}

if (isset($_GET['crear_evento'])) {

    echo json_encode($notificaciones->crear_Evento_usuario($_POST['nombre_evento'], $_POST['mensaje_TCP'], $_POST['prioridad']));
}
*/

class NotificaionesHV
{

    private $ip_api_hikvision;
    private $tcp_puerto_hikvision;

    private $user_api_hikvision;
    private $key_api_hikvision;

    private $Artemis;

    public function __construct($user_api_hikvision, $key_api_hikvision, $ip_api_hikvision, $puerto_api_hikvision, $tcp_puerto_hikvision)
    {
        $this->user_api_hikvision = $user_api_hikvision;
        $this->key_api_hikvision = $key_api_hikvision;
        $this->ip_api_hikvision = $ip_api_hikvision;
        $this->tcp_puerto_hikvision = $tcp_puerto_hikvision;

        $this->Artemis = new Artemis($this->user_api_hikvision, $this->key_api_hikvision, $this->ip_api_hikvision, $puerto_api_hikvision);
    }


    function lista_Eventos()
    {
        $url_API = '/artemis/api/eventService/v1/generalEventRule/generalEventRuleList';
        $body = array("pageNo" => 1, "pageSize" => 10);

        return $this->Artemis->respuesta_Json($url_API, $body);
    }

    function lista_Evento_Ultimo()
    {
        $url_API = '/artemis/api/eventService/v1/generalEventRule/generalEventRuleList';
        $body = array("pageNo" => 1, "pageSize" => 1);

        return ($this->Artemis->respuesta_Json($url_API, $body));
    }

    function crear_Eveto_General($generalEventRuleName, $transportType = 0, $matchType = 0, $expression, $regularExpression)
    {
        $url_API = '/artemis/api/eventService/v1/generalEventRule/single/add';

        $body = array(
            "generalEventRuleName" => $generalEventRuleName,
            "transportType" => $transportType,
            "matchType" => $matchType,
            "expression" => "'$expression'",
            "regularExpression" => $regularExpression
        );

        return $this->Artemis->respuesta_Json($url_API, $body);
    }

    function editar_Eveto_General($generalEventRuleIndexCode, $generalEventRuleName, $expression, $regularExpression, $transportType = 0, $matchType = 0)
    {
        $url_API = '/artemis/api/eventService/v1/generalEventRule/single/update';

        $body = array(
            "generalEventRuleIndexCode" => $generalEventRuleIndexCode,
            "generalEventRuleName" => $generalEventRuleName,
            "transportType" => $transportType,
            "matchType" => $matchType,
            "expression" => "'$expression'",
            "regularExpression" => $regularExpression
        );

        return $this->Artemis->respuesta_Json($url_API, $body);
    }

    function combinar_Eveto_General_Normal($generalEventRuleIndexCodes, $description, $alarmPriority, $triggerPopupWindows = 1)
    {
        $alarmPriority_int = $alarmPriority;

        $url_API = '/artemis/api/eventService/v1/generalEventRule/triggerAlarm';

        $body = array(
            "generalEventRuleIndexCodes" => $generalEventRuleIndexCodes,
            "description" => $description,
            "alarmPriority" => intval($alarmPriority_int),
            "triggerPopupWindows" => $triggerPopupWindows
        );

        return $this->Artemis->respuesta_Json($url_API, $body);
    }


    function crear_Evento_usuario($nombre_evento, $mensaje_TCP, $prioridad)
    {
        $generalEventRuleName = $nombre_evento;
        $expression = $mensaje_TCP;
        $regularExpression = $mensaje_TCP;

        try {
            // Se crea el evento general
            $this->crear_Eveto_General($generalEventRuleName, 0, 0, $expression, $regularExpression);

            // Para crear el evento normal con el último evento general ingresado.
            $valor_arr = $this->lista_Evento_Ultimo();

            // Verificar si la respuesta es válida y tiene el código esperado
            if (isset($valor_arr['code']) && $valor_arr['code'] == 0) {
                if (isset($valor_arr['data']['list'][0]['generalEventRuleIndexCode'])) {
                    $valor = $valor_arr['data']['list'][0]['generalEventRuleIndexCode'];

                    $generalEventRuleIndexCodes = $valor;
                    $description = 'default';

                    // Llamar a combinar_Eveto_General_Normal y decodificar la respuesta JSON
                    $respuesta = $this->combinar_Eveto_General_Normal($generalEventRuleIndexCodes, $description, $prioridad);
                    $code = ($respuesta);

                    // Verificar si la decodificación JSON fue exitosa
                    if (json_last_error() === JSON_ERROR_NONE && isset($code['code'])) {
                        return $code['code'];
                    } else {
                        //throw new Exception("Error al decodificar la respuesta JSON de combinar_Eveto_General_Normal.");
                        return -10;
                    }
                } else {
                    //throw new Exception("No se encontró 'generalEventRuleIndexCode' en la respuesta de lista_Evento_Ultimo.");
                    return -10;
                }
            } else {
                //throw new Exception("Código de error no esperado en la respuesta de lista_Evento_Ultimo: " . json_encode($valor_arr));
                return -10;
            }
        } catch (Exception $e) {
            // Aquí puedes manejar el error de una manera específica o registrar el error
            //echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return -10;
        }
    }

    function llamadaTCP($mensaje_TCP)
    {
        $tcp = new HIK_TCP($this->ip_api_hikvision, $this->tcp_puerto_hikvision);

        $tcp->TCP_enviar($mensaje_TCP);
    }
}
