<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Funciones para carga masiva
*/

class funciones_carga_idukay
{
    function inserts($tabla, $datos)
    {
        $valores = '';
        $campos = '';
        $sql = 'INSERT INTO ' . $tabla;

        foreach ($datos as $key => $value) {
            $campos .= $value['campo'] . ',';

            if (is_string($value['dato'])) {
                $dato = "'" . str_replace("'", "''", $value['dato']) . "'";
            } else {
                $dato = str_replace(',', '', $value['dato']);
                $dato = is_numeric($dato) ? $dato : "'" . $dato . "'";
            }

            $valores .= $dato . ',';
        }

        $campos = rtrim($campos, ',');
        $valores = rtrim($valores, ',');

        $sql .= '(' . $campos . ') VALUES (' . $valores . ');';
        return $sql;
    }

    //Sirve para hacer un update a todas las contraseñas o insert en caso de que no exista 
    function upsertSQL_normal_upadte($tabla, $datos, $where)
    {
        $campos = '';
        $idValor = '';

        // Construcción del SQL INSERT
        $insertCampos = '';
        $insertValores = '';

        foreach ($datos as $key => $value) {
            $campos .= $value['campo'] . ',';

            if (is_string($value['dato'])) {
                $dato = "'" . str_replace("'", "''", $value['dato']) . "'";
            } else {
                $dato = str_replace(',', '', $value['dato']);
                $dato = is_numeric($dato) ? $dato : "'" . $dato . "'";
            }

            $insertCampos .= $value['campo'] . ',';
            $insertValores .= $dato . ',';

            // Suponiendo que el campo para el WHERE es parte de $datos
            if ($value['campo'] === $where) {
                $idValor = $dato;
            }
        }

        $campos = rtrim($campos, ',');
        $insertCampos = rtrim($insertCampos, ',');
        $insertValores = rtrim($insertValores, ',');

        // Construcción del SQL MERGE
        $mergeSQL =
            'MERGE INTO ' . $tabla . ' AS target
                    USING (
                        VALUES (' . $idValor . ')
                    ) AS source (' . $where . ')
                    ON target.' . $where . ' = source.' . $where . '
                    WHEN MATCHED THEN
                        UPDATE SET ';

        foreach ($datos as $value) {
            $mergeSQL .= $value['campo'] . ' = ';
            $mergeSQL .= is_numeric($value['dato']) ? $value['dato'] : "'" . $value['dato'] . "'";
            $mergeSQL .= ', ';
        }

        $mergeSQL = rtrim($mergeSQL, ', ');

        $mergeSQL .=
            'WHEN NOT MATCHED THEN
                    INSERT (' . $campos . ')
                    VALUES (' . $insertValores . '); ';

        return $mergeSQL;
    }

    //Sirve para hacer un update sin tomar en cuenta la PASS o insert en caso de que no exista
    function upsertSQL($tabla, $datos, $where)
    {
        $campos = '';
        $idValor = '';

        // Construcción del SQL INSERT
        $insertCampos = '';
        $insertValores = '';

        foreach ($datos as $key => $value) {
            $campos .= $value['campo'] . ',';

            if (is_string($value['dato'])) {
                $dato = "'" . str_replace("'", "''", $value['dato']) . "'";
            } else {
                $dato = str_replace(',', '', $value['dato']);
                $dato = is_numeric($dato) ? $dato : "'" . $dato . "'";
            }

            $insertCampos .= $value['campo'] . ',';
            $insertValores .= $dato . ',';

            // Suponiendo que el campo para el WHERE es parte de $datos
            if ($value['campo'] === $where) {
                $idValor = $dato;
            }
        }

        $campos = rtrim($campos, ',');
        $insertCampos = rtrim($insertCampos, ',');
        $insertValores = rtrim($insertValores, ',');

        // Construcción del SQL MERGE
        $mergeSQL =
            'MERGE INTO ' . $tabla . ' AS target
                USING (
                    VALUES (' . $idValor . ')
                ) AS source (' . $where . ')
                ON target.' . $where . ' = source.' . $where . '
                WHEN MATCHED THEN
                    UPDATE SET ';

        foreach ($datos as $value) {
            // Excluir el campo 'PASS' del UPDATE
            if ($value['campo'] !== 'PASS') {
                $mergeSQL .= $value['campo'] . ' = ';
                $mergeSQL .= is_numeric($value['dato']) ? $value['dato'] : "'" . $value['dato'] . "'";
                $mergeSQL .= ', ';
            }
        }

        $mergeSQL = rtrim($mergeSQL, ', ');

        $mergeSQL .=
            ' WHEN NOT MATCHED THEN
                INSERT (' . $campos . ')
                VALUES (' . $insertValores . '); ';

        return $mergeSQL;
    }




    function generarUpdate($cursos_modificar)
    {
        $updates = [];
        foreach ($cursos_modificar as $curso) {
            $est_id = 0;
            $curso_texto = '';

            foreach ($curso as $item) {
                if ($item['campo'] == 'sa_id_est_idukay') {
                    $est_id = $item['dato'];
                } elseif ($item['campo'] == 'curso_est') {
                    $curso_texto = $item['dato'];
                }
            }

            // Validar y descomponer la cadena del curso
            if (!empty($curso_texto)) {
                $curso_array = array_map('trim', explode(',', $curso_texto));

                if (count($curso_array) == 3) {
                    list($grado, $seccion, $paralelo) = $curso_array;
                } else {
                    // Manejo de error o asignación de valores predeterminados
                    $grado = $seccion = $paralelo = '-1';
                }
            } else {
                // Asignación de valores predeterminados si curso_texto está vacío
                $grado = $seccion = $paralelo = '-1';
            }

            $update = "UPDATE estudiantes
                           SET sa_id_paralelo = (
                                SELECT cp.sa_par_id
                                FROM cat_paralelo cp
                                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                                WHERE cp.sa_par_estado = 1
                                  AND cg.sa_gra_nombre = '$grado'
                                  AND cs.sa_sec_nombre = '$seccion'
                                  AND cp.sa_par_nombre = '$paralelo'
                            ),
                               sa_id_seccion = (
                                SELECT cs.sa_sec_id
                                FROM cat_paralelo cp
                                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                                WHERE cp.sa_par_estado = 1
                                  AND cg.sa_gra_nombre = '$grado'
                                  AND cs.sa_sec_nombre = '$seccion'
                                  AND cp.sa_par_nombre = '$paralelo'
                            ),
                               sa_id_grado = (
                                SELECT cg.sa_gra_id
                                FROM cat_paralelo cp
                                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                                WHERE cp.sa_par_estado = 1
                                  AND cg.sa_gra_nombre = '$grado'
                                  AND cs.sa_sec_nombre = '$seccion'
                                  AND cp.sa_par_nombre = '$paralelo'
                            )
                           WHERE sa_id_est_idukay = '$est_id';";

            $updates[] = $update;
        }

        return $updates;
    }

    //borrador - Sirve para hacer un update o insert en caso de que no exista - (no se utiliza) 
    function upsertSQL_1($tabla, $datos, $where)
    {
        $valores = '';
        $campos = '';
        $updateSet = '';
        $idValor = '';

        // Construcción del SQL INSERT
        $insertSQL = 'INSERT INTO ' . $tabla . ' (';

        foreach ($datos as $key => $value) {
            $campos .= $value['campo'] . ',';

            if (is_string($value['dato'])) {
                $dato = "'" . str_replace("'", "''", $value['dato']) . "'";
            } else {
                $dato = str_replace(',', '', $value['dato']);
                $dato = is_numeric($dato) ? $dato : "'" . $dato . "'";
            }

            $valores .= $dato . ',';
            $updateSet .= $value['campo'] . ' = ' . $dato . ',';

            // Suponiendo que el campo para el WHERE es parte de $datos
            if ($value['campo'] === $where) {
                $idValor = $dato;
            }
        }

        $campos = rtrim($campos, ',');
        $valores = rtrim($valores, ',');
        $updateSet = rtrim($updateSet, ',');

        $insertSQL .= $campos . ') VALUES (' . $valores . ')';

        // Construcción del SQL UPDATE
        $updateSQL = 'UPDATE ' . $tabla . ' SET ' . $updateSet . ' WHERE ' . $where . ' = ' . $idValor;

        // Construcción de la consulta final con IF
        $finalSQL =
            'IF EXISTS (SELECT 1 FROM ' . $tabla . ' WHERE ' . $where . ' = ' . $idValor . ')
                             BEGIN
                                 ' . $updateSQL . ';
                             END
                             ELSE
                             BEGIN
                                 ' . $insertSQL . ';
                         END ';

        return $finalSQL;
    }

    //Para detectar errores en el query generado 
    /*$grupos = array_chunk($datos, 500);
    
    // Insertar el primer grupo en la base de datos
    $contador = 0;
    
    // Obtener el primer grupo
    $primer_grupo = $grupos[1];
    
    // Inicializar un array para las consultas SQL
    $sql = array();
    
    // Generar las consultas SQL para el primer grupo
    foreach ($primer_grupo as $dato) {
        $sql[] = $this->inserts('representantes', $dato);
    }
    
    // Construir la sentencia SQL para el primer grupo y imprimirla
    $sentenciaSql = '';
    foreach ($sql as $consulta) {
        echo $consulta . "-- " . $contador . "<br><br>";
        $contador++;
    }*/
}
