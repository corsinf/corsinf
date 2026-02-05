<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tipo_licencia_transporteM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_tipo_licencia_transporte';

    // Clave primaria
    protected $primaryKey = 'id_licencia_transporte AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'codigo',
        'categoria',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];

    public function buscar_tipo_licencias_no_registradas($parametros)
    {
        $id_persona = $parametros['id_persona'];
        $query_search = $parametros['query'];
        $lista = array();

        // SQL que selecciona bancos que NO están en la tabla th_per_bancos para esa persona
        $sql = "SELECT id_licencia_transporte, descripcion 
            FROM _talentoh.th_cat_tipo_licencia_transporte 
            WHERE estado = 1
            AND descripcion LIKE '%$query_search%'
            AND id_licencia_transporte NOT IN (
                SELECT id_licencia_transporte 
                FROM _talentoh.th_per_licencias_transportes 
                WHERE th_per_id = $id_persona AND th_lic_estado = 1
            )";

        $datos = $this->db->datos($sql, false, false, true);

        foreach ($datos as $value) {
            $lista[] = array(
                'id' => $value['id_licencia_transporte'],
                'text' => $value['descripcion'],
            );
        }

        return $lista;
    }
}
