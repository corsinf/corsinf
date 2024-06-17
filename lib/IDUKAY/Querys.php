<?php

include('Idukay.php');

class Querys
{

    private $url_API;
    private $bearerToken;

    private $Idukay_API;

    public function __construct($url_API, $bearerToken)
    {
        $this->url_API = $url_API;
        $this->bearerToken = $bearerToken;

        $this->Idukay_API = new Idukay($this->url_API, $this->bearerToken);
    }

    //https://staging.idukay.net/api

    function lista_Estudiante()
    {
        //students?_id=5cdc79d6fcdc87a26653992c&select=_id+user+school+emergency+relatives+addresses+phone

        //$query_regla = '&select=_id+user+school+emergency+relatives+addresses+phone';
       //select={"user": 1,  "relatives":1, "relational_data":1, "years":{"$elemMatch":{"year":"6308dedb64d9466850b563d9"}}}

        $query_regla = 'select={"user": 1,  "relatives":1, "relational_data":1, "years":{"$elemMatch":{"year":"6308dedb64d9466850b563d9", "registered": true}}}';
        $query = '/students?' . $query_regla;
        $body = array();

        return $this->Idukay_API->respuesta_Json($body, $query);
    }

    function lista_Padres()
    {
        //students?_id=5cdc79d6fcdc87a26653992c&select=_id+user+school+emergency+relatives+addresses+phone

        $query_regla = '&select=_id+user+phones';
        $query = '/parents?' . $query_regla;
        $body = array();

        return $this->Idukay_API->respuesta_Json($body, $query);
    }
}


//https://staging.idukay.net/api/students?select={"relatives": 1,  "user": 1, "years":{"$elemMatch":{"year":"6308dedb64d9466850b563d9", "registered": true}}}&_id=5cdc79d6fcdc87a26653992c