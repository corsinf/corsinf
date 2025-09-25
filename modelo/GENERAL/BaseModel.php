<?php

/**
 * Class BaseModel
 *
 * BaseModel es un modelo para generar los query de manera mas agil
 * Tiene los siguiente métodos: (cada metodo se le puede agregar la codicion where y join)
 * listar
 * insertar
 * insertar
 * editar
 * eliminar
 * like -> implentado para los select2
 */

require_once(dirname(__DIR__, 2) . '/db/db.php');

class BaseModel
{
    protected $db;
    protected $tabla;
    protected $primaryKey;
    protected $camposPermitidos = [];
    protected $condicionesWhere = [];
    protected $relaciones = [];
    protected $ordenamientos = [];
    protected $betweenWhere = [];

    function __construct($codigo_empresa_api = false)
    {
        $this->db = new db();
		$this->db->modificar_parametros_db($codigo_empresa_api);

    }

    function listar($limite = null, $error = false, $db_base = false)
    {
        // Obtener cláusulas WHERE y JOIN
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();
        $orderByClause = $this->retornaValoresOrderBy();
        $betweenClause = $this->retornaValoresBetweenBy();

        // Validar y construir la selección de campos
        if (empty($joinClause)) {
            $camposSeleccionados = implode(', ', $this->camposPermitidos);
            $camposSelect = $this->primaryKey . ', ' . $camposSeleccionados;
        } else {
            $camposSelect = '*';
        }
        // Si se define un límite, agregar TOP en la consulta
        if ($limite !== null) {
            $camposSelect = "TOP($limite) " . $camposSelect;
        }
        // Construir consulta SQL
        $sql = sprintf(
            "SELECT %s FROM %s %s %s %s;",
            $camposSelect,
            $this->tabla,
            $joinClause,
            $whereClause,
            $betweenClause,
            $orderByClause,
        );

        // Mostrar la consulta SQL para depuración y salir
        // print_r($sql); die();
        // return $sql;

        // Ejecutar consulta y devolver resultados
        $datos = $this->db->datos($sql, $db_base, $error);
        $this->reset();
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts($this->tabla, $datos);
        return $rest;
    }

    //Retorno el valor del id insertado
    function insertar_id($datos)
    {
        $rest = $this->db->inserts_id($this->tabla, $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update($this->tabla, $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $rest = $this->db->delete($this->tabla, $datos);
        return $rest;
    }

    function contar()
    {
        // Obtener cláusulas WHERE y JOIN
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        // Construir consulta SQL para contar los registros
        $sql = sprintf(
            "SELECT COUNT(*) AS total_registros FROM %s %s %s;",
            $this->tabla,  // Nombre de la tabla
            $joinClause,   // Cláusula JOIN
            $whereClause   // Cláusula WHERE
        );

        // Ejecutar consulta y devolver el resultado
        $datos = $this->db->datos($sql);

        // Devolver el total de registros
        return $datos[0]['total_registros'];
    }

    // Listar registros basado en condiciones LIKE
    function like($campos, $valor)
    {
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        //$camposSelect = implode(', ', $this->camposPermitidos);

        // Inicializar la cláusula WHERE si no hay condiciones previas
        if (empty($whereClause)) {
            $whereClause = " WHERE ";
        } else {
            $whereClause .= " AND ";
        }

        $whereClause .= " CONCAT($campos) LIKE '%" . $valor . "%'";

        $sql = sprintf(
            "SELECT * FROM %s %s %s",
            //$camposSelect,
            $this->tabla,
            $joinClause,
            $whereClause
        );

        //print_r($sql);exit();die();

        $datos = $this->db->datos($sql);
        return $datos;
    }

    //Para resetear los valores de los arrays y en un bucle no se acumulen los where o join
    function reset()
    {
        $this->condicionesWhere = [];
        $this->relaciones = [];
        $this->ordenamientos = [];
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Para generar consultas dinamicas
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Funcion para guardar todos los datos de un where

    // Para generar consultas dinámicas
    function where($atributo, $valor)
    {
        $this->condicionesWhere[] = "$atributo = '$valor'";
        return $this;
    }

    function join($tabla, $condicion, $tipo = 'INNER')
    {
        $this->relaciones[] = compact('tabla', 'condicion', 'tipo');
        return $this;
    }

    function orderBy($campo, $direccion = 'ASC')
    {
        $this->ordenamientos[] = "$campo $direccion";
        return $this;
    }

    function between($campo, $desde,$hasta)
    {
        $this->betweenWhere[] = "$campo BETWEEN '$desde' and '$hasta'";
        return $this;
    }

    // Arma la sentencia del where
    protected function retornaValoresWhere()
    {
        $whereClause = '';
        if (!empty($this->condicionesWhere)) {
            $whereClause = " WHERE " . implode(' AND ', $this->condicionesWhere);
        }

        return $whereClause;
    }

    // Arma la sentencia del join
    protected function retornaValoresJoin()
    {
        $joinClause = '';
        foreach ($this->relaciones as $relacion) {
            $joinClause .= " {$relacion['tipo']} JOIN {$relacion['tabla']} ON {$relacion['condicion']}";
        }

        return $joinClause;
    }

    protected function retornaValoresOrderBy()
    {
        $orderByClause = '';
        if (!empty($this->ordenamientos)) {
            $orderByClause = " ORDER BY " . implode(', ', $this->ordenamientos);
        }
        return $orderByClause;
    }

    protected function retornaValoresBetweenBy()
    {
        $betweenByClause ="";
        if(empty($this->condicionesWhere))
        {
            $betweenByClause = " WHERE ";
        }

        if (!empty($this->betweenWhere)) {
            foreach ($this->betweenWhere as $key => $value) {
                $betweenByClause.= $value." AND ";
            }
        }

        $betweenByClause = substr($betweenByClause, 0,-4);

        return $betweenByClause;
    }


    /*
    *
    *
    *
    */
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /*   ------------------------------------------------ */
    //Pequeñas ayudas si algo sale mal
    /*   ------------------------------------------------ */

    //Si no funciona la funcion listar()
    function listar_1()
    {
        $whereClause = $this->retornaValoresWhere();
        $joinClause = $this->retornaValoresJoin();

        // Consulta SQL
        if ($joinClause == '') {
            $camposSeleccionados = implode(', ', $this->camposPermitidos);
        } else {
            $camposSeleccionados = '*';
        }

        $sql = "SELECT $this->primaryKey, $camposSeleccionados FROM $this->tabla" . $joinClause . $whereClause;

        print_r($sql);
        exit();

        // Ejecutar consulta y devolver resultados
        $datos = $this->db->datos($sql);
        return $datos;
    }
}
