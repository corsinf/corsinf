<?php

class SQL_idukay
{
    /**
     * 
     * 
     * Estudiantes
     * 
     */

    //Modificar en caso sea necesario con lo del modelo estudiantes
    function ponerIdCursos()
    {
        $sql =
            "UPDATE e
            SET e.sa_id_seccion = mappings.sa_sec_id,
                e.sa_id_grado = mappings.sa_gra_id,
                e.sa_id_paralelo = mappings.sa_par_id
            FROM estudiantes e
            JOIN (
                SELECT cs.sa_sec_nombre AS seccion_nombre, cg.sa_gra_nombre AS grado_nombre, cp.sa_par_nombre AS paralelo_nombre, sa_sec_id, sa_gra_id, sa_par_id
                FROM cat_paralelo cp
                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                WHERE cp.sa_par_estado = 1
            ) AS mappings ON e.seccion_estudiante_idukay = mappings.seccion_nombre AND e.grado_estudiante_idukay = mappings.grado_nombre AND e.paralelo_estudiante_idukay = mappings.paralelo_nombre;";

        return $sql;
    }


    /**
     * 
     * 
     * Representantes
     * 
     */

    function ponerRepresentantesEstudiantes()
    {
        $sql =
            "UPDATE e

            SET e.sa_id_representante = r.sa_rep_id

            FROM estudiantes e

            INNER JOIN representantes r

            ON e.sa_id_rep_idukay = r.sa_id_rep_idukay;";


        $sql .=
            "UPDATE e

            SET e.sa_id_representante_2 = r.sa_rep_id
            
            FROM estudiantes e
            
            INNER JOIN representantes r
            
            ON e.sa_id_rep_idukay_2 = r.sa_id_rep_idukay;";

        return $sql;
    }

    /**
     * 
     * 
     * Docentes
     * 
     */

    function ponerIdCursosDocentes()
    {
        $sql =
            "UPDATE hc
             SET 
                 hc.ac_paralelo_id = mappings.sa_par_id
             FROM horario_clases hc
             JOIN (
                 SELECT cs.sa_sec_nombre AS seccion_nombre, cg.sa_gra_nombre AS grado_nombre, cp.sa_par_nombre AS paralelo_nombre, sa_sec_id, sa_gra_id, sa_par_id
                 FROM cat_paralelo cp
                 INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                 INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                 WHERE cp.sa_par_estado = 1
             ) AS mappings ON hc.seccion_idukay = mappings.seccion_nombre AND hc.grado_idukay = mappings.grado_nombre AND hc.paralelo_idukay = mappings.paralelo_nombre;";


        return $sql;
    }

    function ponerIdDocentes()
    {
        $sql =
            "UPDATE hc SET ac_docente_id = doc.sa_doc_id 
             FROM horario_clases hc
             LEFT JOIN  docentes doc ON id_docente_idukay = sa_doc_id_idukay;";


        return $sql;
    }

    function guardaDocenteParalelo()
    {
        $sql =
            "WITH CTE AS (
                 SELECT 
                     ac_docente_id AS id_doc,
                     ac_paralelo_id AS id_par,
                     ROW_NUMBER() OVER (PARTITION BY id_docente_idukay, paralelo_idukay ORDER BY id_docente_idukay) AS rn
                 FROM 
                     horario_clases
             )
             INSERT INTO docente_paralelo (
                 ac_docente_id,
                 ac_paralelo_id
             )
             SELECT 
                 id_doc,
                 id_par
             FROM 
                 CTE
             WHERE 
                 rn = 1
             ORDER BY 
                 id_doc, 
                 id_par;";

        return $sql;
    }
}
