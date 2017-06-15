<?php  
class Registro
{

	private $registros;
	
	public function _construct(){
		$this->registros = array();
	}
	
	public function get_registros(){
		require_once('Connections/conexion.php');
		mysql_select_db($database_conexion, $conexion);
		$sql = "select * from call_registro";
		$res = mysql_query($sql,$conexion);
		while ($reg = mysql_fetch_assoc($res)){
			$this->registros[] = $reg;
		}
		return $this->registros;
	}
}
?>