<?php

/**
 * @deprecated Archivo dado de baja el 02/04/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_personasM extends BaseModel
{
    protected $tabla = 'th_personas';
    protected $primaryKey = 'th_per_id AS _id';

    protected $camposPermitidos = [
        'th_per_primer_nombre AS primer_nombre',
        'th_per_segundo_nombre AS segundo_nombre',
        'th_per_primer_apellido AS primer_apellido',
        'th_per_segundo_apellido AS segundo_apellido',
        'th_per_cedula AS cedula',
        'th_per_estado_civil AS estado_civil',
        'th_per_sexo AS sexo',
        'th_per_fecha_nacimiento AS fecha_nacimiento',
        'th_per_nacionalidad AS nacionalidad',
        'th_per_telefono_1 AS telefono_1',
        'th_per_telefono_2 AS telefono_2',
        'th_per_correo AS correo',
        'th_per_direccion AS direccion',
        'th_per_foto_url AS foto_url',
        'th_prov_id AS id_provincia',
        'th_ciu_id AS id_ciudad',
        'th_parr_id AS id_parroquia',
        'th_per_postal AS postal',
        'th_per_observaciones AS observaciones',
        //'th_per_tabla AS tabla',
        //'th_per_id_comunidad AS id_comunidad',
        //'th_per_tabla_union AS tabla_union',
        'th_per_estado AS estado',
        'th_per_fecha_creacion AS fecha_creacion',
        //'th_per_fecha_modificacion AS fecha_modificacion',
        'PERFIL',
        //'PASS',

        // Campos adicionales que pueden ser necesarios en el futuro
        // 'th_per_es_admin',
        // 'th_per_habiltado',
        // 'th_barr_id',
        // 'th_per_cargo',
        // 'th_per_fecha_admision',
        // 'th_per_fecha_aut_limite',
        // 'th_per_fecha_aut_inicio'
    ];

    public function listar_por_departamento($id_departamento = '')
    {
        // Normalizar entrada (puede ser número, texto o vacío)
        $valor = ($id_departamento !== '' && $id_departamento !== null) ? trim($id_departamento) : '';

        if ($valor === '') {
            // Si no se manda nada, devolvemos todas las personas activas que estén asignadas a algún departamento
            $sql = "
        SELECT DISTINCT p.th_per_id,
               p.th_per_cedula,
               p.th_per_primer_nombre,
               p.th_per_segundo_nombre,
               p.th_per_primer_apellido,
               p.th_per_segundo_apellido
        FROM th_personas p
        INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
        WHERE p.th_per_estado = 1
        ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
        ";
        } else {
            // Si el valor es numérico lo tratamos como id
            if (is_numeric($valor)) {
                $id = (int)$valor;
                $sql = "
            SELECT p.th_per_id,
                   p.th_per_cedula,
                   p.th_per_primer_nombre,
                   p.th_per_segundo_nombre,
                   p.th_per_primer_apellido,
                   p.th_per_segundo_apellido
            FROM th_personas p
            INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
            WHERE pd.th_dep_id = {$id}
              AND p.th_per_estado = 1
            ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
            ";
            } else {
                // Si no es numérico, lo tratamos como nombre (búsqueda parcial usando LIKE)
                // Escapamos comillas simples para evitar romper la query (mejor usar binding si está disponible)
                $nombre = addslashes($valor);
                $sql = "
            SELECT p.th_per_id,
                   p.th_per_cedula,
                   p.th_per_primer_nombre,
                   p.th_per_segundo_nombre,
                   p.th_per_primer_apellido,
                   p.th_per_segundo_apellido
            FROM th_personas p
            INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
            INNER JOIN th_departamentos d ON pd.th_dep_id = d.th_dep_id
            WHERE d.th_dep_nombre LIKE '%{$nombre}%'
              AND p.th_per_estado = 1
            ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
            ";
            }
        }

        return $this->db->datos($sql);
    }
}
