<?php

include('Idukay.php');

class Querys
{

    private $url_API;
    private $bearerToken;

    private $Idukay_API;

    private $anio_lectivo;

    public function __construct($url_API, $bearerToken, $anio_lectivo)
    {
        $this->url_API = $url_API;
        $this->bearerToken = $bearerToken;
        $this->anio_lectivo = $anio_lectivo;

        $this->Idukay_API = new Idukay($this->url_API, $this->bearerToken);
    }

    //https://staging.idukay.net/api

    function lista_Estudiante()
    {
        //students?_id=5cdc79d6fcdc87a26653992c&select=_id+user+school+emergency+relatives+addresses+phone

        //$query_regla = '&select=_id+user+school+emergency+relatives+addresses+phone';
        //select={"user": 1,  "relatives":1, "relational_data":1, "years":{"$elemMatch":{"year":"6308dedb64d9466850b563d9"}}}

        $query_regla = 'select={"user": 1,  "relatives":1, "relational_data":1, "years":{"$elemMatch":{"year":"' . $this->anio_lectivo . '", "registered": true}}}';
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

    function lista_Docentes()
    {
        //students?_id=5cdc79d6fcdc87a26653992c&select=_id+user+school+emergency+relatives+addresses+phone

        $query_regla = '';
        $query = '/staff?' . $query_regla;
        $body = array();

        return $this->Idukay_API->respuesta_Json($body, $query);
    }

    function lista_HorariosDocentes()
    {
        //students?_id=5cdc79d6fcdc87a26653992c&select=_id+user+school+emergency+relatives+addresses+phone
        //teacher=61892e40c3923cc35701291b&
        $query_regla = 'year=6308dedb64d9466850b563d9';
        $query = '/teachers_schedule?' . $query_regla;
        $body = array();

        return $this->Idukay_API->respuesta_Json($body, $query);
    }

    function mostrarDatos()
    {
        //echo $this->url_API . ' ' . $this->bearerToken . ' ' . $this->anio_lectivo;
    }
}


//https://staging.idukay.net/api/students?select={"relatives": 1,  "user": 1, "years":{"$elemMatch":{"year":"6308dedb64d9466850b563d9", "registered": true}}}&_id=5cdc79d6fcdc87a26653992c