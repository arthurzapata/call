<?php require_once('Connections/conexion.php'); 

$checkbox2 = $_POST['casilla'];//isset($_POST['casilla']) ? $_POST['casilla'] : NULL;
for ($i=0;$i<sizeof($checkbox2);$i++)
{
		mysql_select_db($database_conexion, $conexion);
		$query2="DELETE FROM call_registro WHERE reg_id = '".$checkbox2[$i]."'";
  		$Result1 = mysql_query($query2, $conexion) or die(mysql_error());
		//mysql_free_result($Result1);
}

?>