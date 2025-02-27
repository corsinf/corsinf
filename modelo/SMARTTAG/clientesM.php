   <?php
@session_start();
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_mysql.php');
   	}
   }else
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_sql.php'); 
   	}
   }
   if(!class_exists('codigos_globales'))
   {
   	require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
   }
/**
 * 
 */
class clientesM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	function update($tabla,$datos,$where)
	{
		$datos = $this->db->update($tabla,$datos,$where);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}

	function lista_clientes($query=false,$cali=false,$proc=false,$WHERE=false)
	{
		$sql = "SELECT id_cliente as 'id',nombre_cliente as 'nombre',direccion_cliente as 'dir',nivel_cliente as 'nivel',detalle_proceso as 'proceso', color_proceso as 'color',id_usuario as 'usu',contactado_cliente as 'contactado' FROM cliente C
		   LEFT JOIN proceso P ON c.id_proceso = P.id_proceso WHERE 1=1 ";
		   if($query)
		   {
		   	$sql.=" AND  nombre_cliente LIKE '%".$query."%'";
		   }
		   if($cali)
		   {
		   	if($cali==-1)
		   	{
		   		$sql.=" AND nivel_cliente = 0";
		   	}else
		   	{
		   		$sql.=" AND nivel_cliente = ".$cali;
		   	}
		   }
		   if($proc)
		   {
		   	$sql.=" AND C.id_proceso = ".$proc;
		   }
		   $sql.=" ORDER BY contactado_cliente ASC";
		   // print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function clientes($parametros)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion,ciudad,detalle_tipo_cliente_prove FROM cliente_proveedor C
		LEFT JOIN tipo_cliente_proveedor T ON  C.id_tipo_cliente = T.id_tipo_cliente_prove WHERE  tipo = 'C' AND estado='A'";
		if($parametros['query']!='')
		{
			$sql.=" AND nombre LIKE '%".$parametros['query']."%'";
		}

		if($parametros['tipo']!='T' && $parametros['tipo']!='')
		{
			$sql.=" AND id_tipo_cliente = ".$parametros['tipo'];
		}

		$sql.= " ORDER BY id_cliente_prove DESC";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function clientes_inactivos($query=false)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion FROM cliente_proveedor WHERE  tipo = 'C' AND estado='I'";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function proveedores($parametros)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion,nombre_empresa,ciudad,detalle_tipo_cliente_prove FROM cliente_proveedor C
		LEFT JOIN tipo_cliente_proveedor T ON  C.id_tipo_cliente = T.id_tipo_cliente_prove WHERE  tipo = 'P' AND estado='A'";
		if($parametros['query']!='')
		{
			$sql.=" AND nombre LIKE '%".$parametros['query']."%'";
		}

		if($parametros['tipo']!='T' && $parametros['tipo']!='')
		{
			$sql.=" AND id_tipo_cliente = ".$parametros['tipo'];
		}

		$sql.= "ORDER BY id_cliente_prove DESC";
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function proveedores_inactivos($query=false)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion FROM cliente_proveedor WHERE  tipo = 'P' AND estado='I'";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}



	function ficha_clientes($id)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion,credito,ciudad,C.id_tipo_cliente,detalle_tipo_cliente_prove as 'tipo' FROM cliente_proveedor C
		LEFT JOIN tipo_cliente_proveedor T ON  C.id_tipo_cliente = T.id_tipo_cliente_prove
		WHERE  tipo = 'C'
		 AND id_cliente_prove = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function ficha_proveedor($id)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion,nombre_empresa,ciudad,C.id_tipo_cliente,detalle_tipo_cliente_prove as 'tipo' FROM cliente_proveedor C
		LEFT JOIN tipo_cliente_proveedor T ON  C.id_tipo_cliente = T.id_tipo_cliente_prove WHERE  tipo = 'P'
		 AND id_cliente_prove = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function delete_clientes($id)
	{
		$sql = "DELETE FROM  cliente_proveedor WHERE id_cliente_prove='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string_cod_error($sql);
		if($datos==1)
		{
			return 1;
		}else if($datos=='547')
		{
			return -2;
		}else
		{
			return -1;
		}
		return $datos;
	}
}
?>