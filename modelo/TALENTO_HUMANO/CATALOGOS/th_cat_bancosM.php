<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_bancosM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_bancos';

    // Clave primaria
    protected $primaryKey = 'id_banco AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'se_lista AS se_lista',
        'fecha_creacion AS fecha_creacion'
    ];

    public function buscar_bancos_no_registrados($parametros)
    {
        $id_persona = $parametros['id_persona'];
        $query_search = $parametros['query'];
        $lista = array();

        // SQL que selecciona bancos que NO están en la tabla th_per_bancos para esa persona
        $sql = "SELECT id_banco, descripcion 
            FROM _talentoh.th_cat_bancos 
            WHERE estado = 1 AND se_lista = 1
            AND descripcion LIKE '%$query_search%'
            AND id_banco NOT IN (
                SELECT id_banco 
                FROM _talentoh.th_per_bancos 
                WHERE th_per_id = $id_persona AND th_ban_estado = 1
            )";

        $datos = $this->db->datos($sql, false, false, true);

        foreach ($datos as $value) {
            $lista[] = array(
                'id' => $value['id_banco'],
                'text' => $value['descripcion'],
            );
        }

        return $lista;
    }
}
