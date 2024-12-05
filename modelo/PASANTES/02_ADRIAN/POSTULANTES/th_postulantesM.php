<?php

require_once(dirname(__DIR__,2).'/BaseModel.php');

class th_postulantesM extends BaseModel
{
    protected $tabla = 'th_postulantes';
    protected $primaryKey = 'th_pos_id AS _id';

    protected $camposPermitidos = [
        'th_pos_primer_nombre',
        'th_pos_segundo_nombre',
        'th_pos_primer_apellido',
        'th_pos_segundo_apellido',
        'th_pos_cedula',
        'th_pos_sexo',
        'th_pos_fecha_nacimiento',
        'th_pos_nacionalidad',
        'th_pos_estado_civil',
        'th_pos_telefono_1',
        'th_pos_telefono_2',
        'th_pos_correo',
        'th_prov_id',
        'th_ciu_id',
        'th_parr_id',
        'th_pos_direccion',
        'th_pos_postal',
        'th_pos_tabla',
        'th_pos_estado',
        'th_pos_fecha_creacion',
        'th_pos_fecha_modificacion',
        'PERFIL',
        // 'PASS',
        'th_pos_foto_url',

    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_ciudad', 'th_postulantes.th_ciu_id = th_ciudad.th_ciu_id');
        $this->join('th_provincias', 'th_ciudad.th_prov_id = th_provincias.th_prov_id');
        $this->join('th_parroquias', 'th_postulantes.th_parr_id = th_parroquias.th_parr_id');

        // Aplicar condiciones WHERE para cada tabla
        $this->where('th_provincias.th_prov_estado', '1');
        $this->where('th_ciudad.th_ciu_estado', '1');
        $this->where('th_parroquias.th_parr_estado', '1');

        // Ejecutar la consulta y obtener los datos
        $datos = $this->listar();
        
        return $datos;
    }
}
