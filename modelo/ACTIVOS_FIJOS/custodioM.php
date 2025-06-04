<?php

if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 */

class custodioM
{
	private $db;
	private $codigos_globales;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_custodio($query = false, $ini = 0, $fin = 25)
	{
		$sql = "SELECT 
					th_per_id AS ID_PERSON,
					CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) AS PERSON_NOM,
					th_per_cedula AS PERSON_CI,
					th_per_correo AS PERSON_CORREO,
					th_per_telefono_1 AS TELEFONO,
					th_per_direccion AS DIRECCION,
					th_per_foto_url AS FOTO,
					th_per_codigo_sap AS PERSON_NO,
					th_per_unidad_org_sap AS UNIDAD_ORG
				FROM th_personas 
				WHERE th_per_estado = 1";

		if ($query) {
			$sql .= " AND (th_per_primer_nombre + ' ' + th_per_primer_apellido LIKE '%" . $query . "%')";
		}

		$sql .= " ORDER BY th_per_id DESC 
	  OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";


		$datos = $this->db->datos($sql);
		return $datos;
	}


	function lista_custodio_count($query = false)
	{
		$sql = "SELECT COUNT(th_per_id) AS cant FROM th_personas WHERE th_per_estado = 1";

		if ($query) {
			$sql .= " AND (CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) LIKE '%" . $query . "%')";
		}

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio($buscar)
	{
		$sql = "SELECT 
                th_per_id AS ID_PERSON,
                th_per_cedula AS PERSON_CI,
                CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) AS PERSON_NOM,
                th_per_correo AS PERSON_CORREO,
                th_per_telefono_1 AS TELEFONO,
                th_per_direccion AS DIRECCION,
                th_per_foto_url AS FOTO,
				th_per_codigo_sap AS PERSON_NO,
				th_per_unidad_org_sap AS UNIDAD_ORG
            FROM th_personas 
            WHERE th_per_estado = 1 
            AND th_per_id = '" . $buscar . "'";

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio_vista_publica($buscar, $id_empresa = null)
	{
		$sql = "SELECT 
                th_per_id AS ID_PERSON,
                th_per_cedula AS PERSON_CI,
                CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) AS PERSON_NOM,
                th_per_correo AS PERSON_CORREO,
                th_per_telefono_1 AS TELEFONO,
                th_per_direccion AS DIRECCION,
                th_per_foto_url AS FOTO,
				th_per_codigo_sap AS PERSON_NO,
				th_per_unidad_org_sap AS UNIDAD_ORG
            FROM th_personas 
            WHERE th_per_estado = 1 
            AND th_per_id = '" . $buscar . "'";

		if ($id_empresa) {
			$this->codigos_globales = new codigos_globales();
			$sql_publica = $this->codigos_globales->datos_empresa_publica($id_empresa, $sql);
			return isset($sql_publica['datos']) ? $sql_publica['datos'] : [];
		}

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio_todo($id = false, $person_no = false, $person_nom = false)
	{
		$sql = "SELECT 
					th_per_id AS ID_PERSON,
					th_per_codigo_sap AS PERSON_NO,
					CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) AS PERSON_NOM,
					th_per_cedula AS PERSON_CI,
					th_per_correo AS PERSON_CORREO,
					th_per_unidad_org_sap AS UNIDAD_ORG,
					th_per_estado AS ESTADO
				FROM th_personas 
				WHERE th_per_estado = 1";

		if ($id) {
			$sql .= " AND th_per_id = '" . $id . "'";
		}
		if ($person_no) {
			$sql .= " AND th_per_codigo_sap = '" . $person_no . "'";
		}
		if ($person_nom) {
			$sql .= " AND (CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) LIKE '%" . $person_nom . "%')";
		}

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio_($buscar)
	{
		$sql = "SELECT 
					th_per_id AS ID_PERSON,
					CONCAT(th_per_primer_apellido, ' ', th_per_segundo_apellido, ' ', th_per_primer_nombre, ' ', th_per_segundo_nombre) AS PERSON_NOM,
					th_per_cedula AS PERSON_CI,
					th_per_correo AS PERSON_CORREO,
					th_per_unidad_org_sap AS UNIDAD_ORG
				FROM th_personas 
				WHERE th_per_codigo_sap LIKE '" . $buscar . "'";

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('th_personas', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('th_personas', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE th_personas 
				SET th_per_estado = 0 
				WHERE " . $datos[0]['campo'] . " = '" . $datos[0]['dato'] . "';";

		$resultado = $this->db->sql_string($sql);
		return $resultado;
	}
}
