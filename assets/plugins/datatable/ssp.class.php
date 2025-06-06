<?php

/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */


// Please Remove below 4 lines as this is use in Datatatables test environment for your local or live environment please remove it or else it will not work
$file = $_SERVER['DOCUMENT_ROOT'] . '/datatables/pdo.php';
if (is_file($file)) {
	include($file);
}


class SSP
{
	/**
	 * Create the data output array for the DataTables rows
	 *
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
	static function data_output($columns, $data)
	{
		$out = array();

		for ($i = 0, $ien = count($data); $i < $ien; $i++) {
			$row = array();

			for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
				$column = $columns[$j];

				// Is there a formatter?
				if (isset($column['formatter'])) {
					if (empty($column['db'])) {
						$row[$column['dt']] = $column['formatter']($data[$i]);
					} else {
						$row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
					}
				} else {
					if (!empty($column['db'])) {
						$row[$column['dt']] = $data[$i][$columns[$j]['db']];
					} else {
						$row[$column['dt']] = "";
					}
				}
			}

			$out[] = $row;
		}

		return $out;
	}


	/**
	 * Database connection
	 *
	 * Obtain an PHP PDO connection from a connection details array
	 *
	 *  @param  array $conn SQL connection details. The array should have
	 *    the following properties
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 *  @return resource PDO connection
	 */

	static function db($conn)
	{
		if (is_array($conn)) {
			return self::sql_connect($conn);
		}

		return $conn;
	}


	/**
	 * Paging
	 *
	 * Construct the LIMIT clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL limit clause
	 */
	static function limit($request, $columns)
	{
		$limit = '';

		if (isset($request['start']) && $request['length'] != -1) {
			// Para SQL Server, usar OFFSET y FETCH
			$limit = "OFFSET " . intval($request['start']) . " ROWS FETCH NEXT " . intval($request['length']) . " ROWS ONLY";
		}

		return $limit;
	}



	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	static function order($request, $columns)
	{
		$order = '';

		if (isset($request['order']) && count($request['order'])) {
			$orderBy = array();
			$dtColumns = self::pluck($columns, 'dt');

			for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
				// Convertir el índice de la columna en la propiedad de datos de la columna
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				// Verificar si la columna es ordenable
				if ($requestColumn['orderable'] == 'true') {
					$dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC';

					// Usar corchetes en lugar de comillas invertidas para SQL Server
					$orderBy[] = '[' . $column['db'] . '] ' . $dir;
				}
			}

			if (count($orderBy)) {
				$order = 'ORDER BY ' . implode(', ', $orderBy);
			}
		}

		return $order;
	}



	/**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @param  array $bindings Array of values for PDO bindings, used in the
	 *    sql_exec() function
	 *  @return string SQL where clause
	 */
	static function filter($request, $columns, &$bindings)
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck($columns, 'dt');

		// Búsqueda global
		if (isset($request['search']) && $request['search']['value'] != '') {
			$str = $request['search']['value'];

			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				if ($requestColumn['searchable'] == 'true') {
					if (!empty($column['db'])) {
						$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
						// Se cambiaron las comillas invertidas por corchetes
						$globalSearch[] = "[" . $column['db'] . "] LIKE " . $binding;
					}
				}
			}
		}

		// Filtro de columnas individuales
		if (isset($request['columns'])) {
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				$str = $requestColumn['search']['value'];

				if (
					$requestColumn['searchable'] == 'true' &&
					$str != ''
				) {
					if (!empty($column['db'])) {
						$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
						// Se cambiaron las comillas invertidas por corchetes
						$columnSearch[] = "[" . $column['db'] . "] LIKE " . $binding;
					}
				}
			}
		}

		// Combina los filtros en una sola cadena
		$where = '';

		if (count($globalSearch)) {
			$where = '(' . implode(' OR ', $globalSearch) . ')';
		}

		if (count($columnSearch)) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where . ' AND ' . implode(' AND ', $columnSearch);
		}

		if ($where !== '') {
			$where = 'WHERE ' . $where;
		}

		return $where;
	}



	/**
	 * Perform the SQL queries needed for an server-side processing requested,
	 * utilising the helper functions of this class, limit(), order() and
	 * filter() among others. The returned array is ready to be encoded as JSON
	 * in response to an SSP request, or can be modified if needed before
	 * sending back to the client.
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @return array          Server-side processing response array
	 */
	static function simple($request, $conn, $table, $primaryKey, $columns)
	{
		$bindings = array();
		$db = self::db($conn);

		// Construir la cadena SQL a partir de la solicitud
		$limit = self::limit($request, $columns); // La función `limit` puede necesitar ajustes
		$order = self::order($request, $columns); // Similar a `order` en MySQL
		$where = self::filter($request, $columns, $bindings); // Lo mismo para `filter`

		// $data = "SELECT " . implode(", ", self::pluck($columns, 'db')) . "
		// 	 FROM $table
		// 	 $where
		// 	 $order
		// 	 $limit";

		// print_r($data);
		// exit;

		// Consulta principal para obtener los datos
		$data = self::sql_exec(
			$db,
			$bindings,
			"SELECT " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table
			 $where
			 $order
			 $limit"
		);

		// Longitud del conjunto de datos después de aplicar el filtro
		$resFilterLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT($primaryKey)
			 FROM $table
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Longitud total del conjunto de datos
		$resTotalLength = self::sql_exec(
			$db,
			"SELECT COUNT($primaryKey)
			 FROM $table"
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Salida
		 */
		return array(
			"draw"            => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data"            => self::data_output($columns, $data)
		);
	}



	/**
	 * The difference between this method and the `simple` one, is that you can
	 * apply additional `where` conditions to the SQL queries. These can be in
	 * one of two forms:
	 *
	 * * 'Result condition' - This is applied to the result set, but not the
	 *   overall paging information query - i.e. it will not effect the number
	 *   of records that a user sees they can have access to. This should be
	 *   used when you want apply a filtering condition that the user has sent.
	 * * 'All condition' - This is applied to all queries that are made and
	 *   reduces the number of records that the user can access. This should be
	 *   used in conditions where you don't want the user to ever have access to
	 *   particular records (for example, restricting by a login id).
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @param  string $whereResult WHERE condition to apply to the result set
	 *  @param  string $whereAll WHERE condition to apply to all queries
	 *  @return array          Server-side processing response array
	 */
	static function complex($request, $conn, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null, $columnSearch, $columnsDefault = false)
	{
		$bindings = array();
		$db = self::db($conn);

		// Evitar valores NULL en $whereResult y $whereAll
		$whereResult = !empty($whereResult) ? self::_flatten($whereResult) : '';
		$whereAll = !empty($whereAll) ? self::_flatten($whereAll) : '';

		// Construcción de SQL
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);

		if (!$columnsDefault) {
			$where = self::filter($request, $columns, $bindings);
		} else {

			// Array con los índices de las columnas que deben ser buscables
			// $columnSearch = [0, 1, 4];

			foreach ($request['columns'] as $index => &$column) {
				if (in_array($index, $columnSearch)) {
					$column['searchable'] = 'true';
				} else {
					$column['searchable'] = 'false';
				}
			}

			$where = self::filter($request, $columns, $bindings);
		}

		if ($whereResult) {
			$where = $where ? "$where AND $whereResult" : "WHERE $whereResult";
		}

		if ($whereAll) {
			$where = $where ? "$where AND $whereAll" : "WHERE $whereAll";
		}

		$whereAllSql = !empty($whereAll) ? "WHERE $whereAll" : '';

		// // Depuración: Mostrar consulta SQL generada antes de ejecutarla
		// echo "Consulta de datos: SELECT [" . implode("], [", self::pluck($columns, 'db')) . "] FROM $table $where $order $limit";
		// echo "<br><br><br><br><br>";
		// echo "Consulta COUNT filtrada: SELECT COUNT(*) FROM $table $where";
		// echo "<br><br><br><br>";
		// echo "Consulta COUNT total: SELECT COUNT(*) FROM $table $whereAllSql";
		// exit;

		// Consulta principal para obtener los datos
		$data = self::sql_exec(
			$db,
			$bindings,
			"SELECT " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table
			 $where
			 $order
			 $limit"
		);

		// Longitud del conjunto de datos después del filtrado
		$resFilterLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT({$primaryKey})
			 FROM $table
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Longitud total del conjunto de datos
		$resTotalLength = self::sql_exec(
			$db,
			// $bindings,
			"SELECT COUNT({$primaryKey})
			 FROM   $table " .
				$whereAllSql
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
    * Salida de datos
    */
		return array(
			"draw"            => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data"            => self::data_output($columns, $data)
		);
	}



	/**
	 * Connect to the database
	 *
	 * @param  array $sql_details SQL server connection details array, with the
	 *   properties:
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 * @return resource Database connection handle
	 */

	static function sql_connect($sql_details)
	{
		try {
			// Conexión con PDO para SQL Server
			$db = @new PDO(
				"sqlsrv:Server={$sql_details['host']};Database={$sql_details['db']}",
				$sql_details['user'],
				$sql_details['pass'],
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);
		} catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. " .
					"The error reported by the server was: " . $e->getMessage()
			);
		}

		return $db;
	}


	/**
	 * Execute an SQL query on the database
	 *
	 * @param  resource $db  Database handler
	 * @param  array    $bindings Array of PDO binding values from bind() to be
	 *   used for safely escaping strings. Note that this can be given as the
	 *   SQL query string if no bindings are required.
	 * @param  string   $sql SQL query to execute.
	 * @return array         Result from the query (all rows)
	 */

	static function sql_exec($db, $bindings, $sql = null)
	{
		// Si no se pasa la consulta SQL, usar el primer parámetro como consulta.
		if ($sql === null) {
			$sql = $bindings;
		}

		// Preparar la consulta
		$stmt = $db->prepare($sql);
		// echo $sql; // Descomenta esto si deseas depurar la consulta generada

		// Si los bindings son un arreglo, los enlazamos con los valores
		if (is_array($bindings)) {
			for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
				$binding = $bindings[$i];
				// Aquí estamos asociando el valor a la consulta
				$stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
			}
		}

		// Ejecutar la consulta
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			self::fatal("An SQL error occurred: " . $e->getMessage());
		}

		// Retornar los resultados en un formato asociativo (ambos índice y nombre de columna)
		return $stmt->fetchAll(PDO::FETCH_BOTH);
	}



	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */

	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal($msg)
	{
		echo json_encode(array(
			"error" => $msg
		));

		exit(0);
	}

	/**
	 * Create a PDO binding key which can be used for escaping variables safely
	 * when executing a query with sql_exec()
	 *
	 * @param  array &$a    Array of bindings
	 * @param  *      $val  Value to bind
	 * @param  int    $type PDO field type
	 * @return string       Bound key to be used in the SQL where this parameter
	 *   would be used.
	 */
	static function bind(&$a, $val, $type)
	{
		$key = ':binding_' . count($a);

		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);

		return $key;
	}


	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck($a, $prop)
	{
		$out = array();

		for ($i = 0, $len = count($a); $i < $len; $i++) {
			if (empty($a[$i][$prop])) {
				continue;
			}
			//removing the $out array index confuses the filter method in doing proper binding,
			//adding it ensures that the array data are mapped correctly
			$out[$i] = $a[$i][$prop];
		}

		return $out;
	}


	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten($a, $join = ' AND ')
	{
		if (! $a) {
			return '';
		} else if ($a && is_array($a)) {
			return implode($join, $a);
		}
		return $a;
	}
}
