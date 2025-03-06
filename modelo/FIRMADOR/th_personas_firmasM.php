<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_personas_firmasM extends BaseModel
{
    protected $tabla = 'th_personas_firmas';
    protected $primaryKey = 'th_perfir_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS id_persona',
        'th_tipfir_id AS id_tipfir',
        'th_perfir_nombre_firma AS nombre_firma',
        'th_perfir_identificacion AS identificacion',
        'th_perfir_contrasenia AS password',
        'th_perfir_fecha_creacion AS fecha_creacion',
        'th_perfir_fecha_archivo AS fecha_archivo',
        'th_perfir_fecha_expiracion AS fecha_expiracion',
        'th_perfir_documento_url AS documento_url',
        'th_perfir_politica_de_datos AS politica_de_datos',
        'th_perfir_estado AS estado'
    ];


    function lista_genero($id = '') 
    {
        // Inicializar la consulta SQL
        $sql = "SELECT th_personas_firmas.th_perfir_id, 
                       th_personas.th_per_id, 
                       th_personas.th_per_primer_nombre, 
                       th_personas.th_per_primer_apellido,  
                       th_personas_firmas.th_perfir_identificacion, 
                       th_personas_firmas.th_perfir_nombre_firma, 
                       th_cat_tipo_firma.th_tipfir_id, 
                       th_personas_firmas.th_perfir_fecha_creacion, 
                       th_personas_firmas.th_perfir_fecha_expiracion, 
                       th_personas_firmas.th_perfir_contrasenia,  
                       th_personas_firmas.th_perfir_politica_de_datos
                FROM th_personas_firmas
                INNER JOIN th_personas ON th_personas.th_per_id = th_personas_firmas.th_per_id
                INNER JOIN th_cat_tipo_firma ON th_cat_tipo_firma.th_tipfir_id = th_personas_firmas.th_tipfir_id
                WHERE th_personas.th_per_estado = 1"; // Aquí agregas la condición de estado si es necesario
        
        // Si se pasa un ID, se agrega a la consulta como filtro
        if ($id) {
            $sql .= ' AND th_personas.th_per_id = ' . $id;
        }
        
        // Ordena la consulta
        $sql .= " ORDER BY th_personas_firmas.th_perfir_id"; 
    
        // Ejecuta la consulta SQL
        $datos = $this->db->datos($sql);
        
        return $datos;
    }
    
}
