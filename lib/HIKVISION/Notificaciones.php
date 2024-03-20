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

    private $clave_partner;
    private $clave_secreta;

    private $Artemis;

    public function __construct($clave_partner, $clave_secreta)
    {
        $this->clave_partner = $clave_partner;
        $this->clave_secreta = $clave_secreta;

        $this->Artemis = new Artemis($this->clave_partner, $this->clave_secreta);
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

        return json_decode($this->Artemis->respuesta_Json($url_API, $body), true);
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

        //Se crea el evento general
        $this->crear_Eveto_General($generalEventRuleName, 0, 0, $expression, $regularExpression);

        //Para crear el evento normal con el ultimo evento general ingresado.
        $valor_arr = $this->lista_Evento_Ultimo();
        $valor = $valor_arr['data']['list'][0]['generalEventRuleIndexCode'];

        $generalEventRuleIndexCodes = $valor;
        $description = 'default';

        //$this->combinar_Eveto_General_Normal($generalEventRuleIndexCodes, $description, $prioridad);

        $code = json_decode($this->combinar_Eveto_General_Normal($generalEventRuleIndexCodes, $description, $prioridad));

        $code = $code->code;

        return $code;
    }

    function llamadaTCP($mensaje_TCP)
    {
        $tcp = new HIK_TCP();

        $tcp->TCP_enviar($mensaje_TCP);

    }

}
