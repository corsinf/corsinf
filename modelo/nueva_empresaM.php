<?php 
if(!class_exists('db'))
{
	include('../db/db.php');
}
/**
 * 
 */
class nueva_empresaM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function add($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}

	function buscar_empresa($ruc)
	{
		$sql = "SELECT * FROM EMPRESAS WHERE Ruc = '".$ruc."'";
		return $this->db->datos($sql);
	} 


	function generar_tablas_modulos($basedatos,$modulo)
	{
		$contenido = file_get_contents($modulo);
		$contenido = explode(';',$contenido);
		$contenido = array_filter($contenido);
		foreach ($contenido as $key => $value) {

			//buscamos el nombre de la tabla
			$tabla = explode('(',$value);
			$tabla = $tabla[0];
			$tabla = str_replace('CREATE TABLE', '', $tabla);
			$tabla = trim($tabla);
			//validamo si existe
			$existe = $this->db->existe_tabla($basedatos,$tabla);
			if($existe[0]['existe']==0)
			{				//creamos si no existe;
				$this->db->sql_string($value);
			}else
			{
				//editamos tabla en caso de cambiarla
				$campos = str_replace('CREATE TABLE '.$tabla,'',$value);
				$campos = trim($campos);
				$campos = substr($campos,0,-1);
				$campos = substr($campos,1);
				$campos = explode(',',$campos);
				foreach ($campos as $key => $value) {
					$campo = trim($value);
					$campo = explode(" ", $campo);
					$campo = $campo[0];
					$existe = $this->db->existe_campo_tabla($basedatos,$tabla,$campo);
					if($existe[0]['existe']==0)
					{						
						$this->db->alter_db($basedatos,$tabla,$value);
					}else{
						// siempre y cuando el campo existe entra aqui
						if(strpos($value,'PRIMARY')===false)
						{
							if(strpos($value,'DEFAULT')!==false)
							{
								$existe = $this->db->existe_dato_default($basedatos,$tabla,$campo);
								if($existe[0]['existe']==0)
								{

									$modificado = explode('DEFAULT', $value);
									$this->db->alter_db($basedatos,$tabla,$modificado,0,1);
								}else
								{
									$modificado = explode('DEFAULT', $value);
									$modificado = trim($modificado[0]);									
									$this->db->alter_db($basedatos,$tabla,$modificado,1);
								}
							}else
							{
								$this->db->alter_db($basedatos,$tabla,$value,1);
							}
						}
						
					}
				}
			}
		}

		// print_r($contenido);die();

	}


	function cargar_datos_default($basedatos,$datos_default)
	{
		$contenido = file_get_contents($datos_default);
		$contenido = explode(';',$contenido);
		$contenido = array_filter($contenido);
		foreach ($contenido as $key => $value) {
			$value = trim($value);
			print_r($value);die();
			$this->db-> sql_string_terceros($basedatos,$value);
		}
	}

	

	
}

?>