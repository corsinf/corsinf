<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_espacios_mediaM extends BaseModel
{
    protected $tabla        = 'hub_espacios_media';
    protected $primaryKey   = 'id_espacio_media AS _id';

    protected $camposPermitidos = [
        'id_espacio',
        'tipo',
        'url_archivo',
        'nombre_archivo',
        'formato',
        'tamanio_bytes',
        'orden',
        'es_principal',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion'
    ];

    /* ------------------------------------------------------------------ */
    /*  LISTAR                                                              */
    /* ------------------------------------------------------------------ */
    public function listar_media($id_espacio = '')
    {
        $sql = "
            SELECT
                id_espacio_media  AS _id,
                id_espacio,
                tipo,
                url_archivo,
                nombre_archivo,
                formato,
                tamanio_bytes,
                orden,
                es_principal,
                fecha_creacion
            FROM hub_espacios_media
            WHERE is_deleted = 0
        ";

        if ($id_espacio !== '') {
            $id   = (int) $id_espacio;
            $sql .= " AND id_espacio = {$id}";
        }

        $sql .= " ORDER BY es_principal DESC, orden ASC, fecha_creacion ASC";

        return $this->db->datos($sql);
    }

    
}
