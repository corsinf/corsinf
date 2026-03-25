<?php

function obtener_tipo_usuario()
{
    return strtoupper($_SESSION['INICIO']['TIPO'] ?? '');
}

function tiene_rol($roles = [])
{
    $tipo_usuario = obtener_tipo_usuario();
    return in_array($tipo_usuario, $roles);
}

function es_restringido()
{
    $roles_restringidos = ['EMPLEADOS', 'POSTULANTES', 'VISITANTES'];
    return tiene_rol($roles_restringidos);
}

function html_disabled()
{
    return es_restringido() ? 'disabled' : '';
}

function obtener_link_edicion()
{
    $modulo = $_SESSION['INICIO']['MODULO_SISTEMA'] ?? '';
    $tabla = $_SESSION['INICIO']['NO_CONCURENTE_TABLA'] ?? '';
    $campo_id = $_SESSION['INICIO']['NO_CONCURENTE'] ?? '';
    $id_persona = $_SESSION['INICIO']['ID_PERSONA'] ?? -1;

    if (tiene_rol(['EMPLEADOS'])) {
        return "../vista/inicio.php?mod=$modulo&acc=th_registrar_personas&id_persona=$id_persona&id_postulante=postulante&_origen=nomina&_persona_nomina=true";
    }

    if (tiene_rol(['VISITANTES'])) {
        return "../vista/inicio.php?mod=$modulo&acc=th_registrar_personas&id_persona=$id_persona";
    }

    if ($tabla == "_talentoh.th_postulantes") {
        return "../vista/inicio.php?mod=$modulo&acc=th_informacion_personal&id_postulante=$campo_id";
    }

    return "#";
}

function obtener_redireccion()
{
    $id_persona = $_GET['id_persona'] ?? '';
    $id_postulante = $_GET['id_postulante'] ?? '';
    $origen = $_GET['_origen'] ?? '';

    if (tiene_rol(['EMPLEADOS'])) {
        return "th_registrar_personas&id_persona=$id_persona&id_postulante=$id_postulante&_origen=nomina&_persona_nomina=true";
    }

    if (tiene_rol(['VISITANTES'])) {
        return "th_registrar_personas&id_persona=$id_persona";
    }

    if ($origen === 'nomina') {
        return 'th_personas_nomina';
    }

    return 'th_personas';
}

function validar_acceso_persona($id_persona)
{
    $session_id = $_SESSION['INICIO']['ID_PERSONA'] ?? 0;

    if ($session_id > 0 && $id_persona != '' && $session_id != $id_persona) {
        echo "<script>location.href = 'inicio.php?acc=pagina_error';</script>";
        exit;
    }
}
