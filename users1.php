<?php require_once('Connections/conexion.php'); ?>
<?php
	$id_empresa = $_POST['emp_id'];
	$html = '';
	if($id_empresa == 0)
		{
		$html = '<option value = ""> -- Todos -- </option>';
		echo $html;
		}
		else
		{
	mysql_select_db($database_conexion, $conexion);
	$query_mos_subcat = "SELECT usu_id,usu_nombre,per_id FROM call_usuario where emp_id = ".$id_empresa." and per_id in (1,2) and usu_activo = 1 order by usu_nombre";
	$mos_subcat = mysql_query($query_mos_subcat, $conexion) or die(mysql_error());
	$row_mos_subcat = mysql_fetch_assoc($mos_subcat);
	$total_rows = mysql_num_rows($mos_subcat);
	$msje = '';
	if ($total_rows > 0)
	{
		$html .= '<option value=""> -- Todos -- </option>';
		do
		{ 
			if($row_mos_subcat['per_id']==1) $msje = '  -- Jefe --'; else  $msje = '';
			$html .= '<option value="'.$row_mos_subcat['usu_id'].'">'.$row_mos_subcat['usu_nombre'].$msje.'</option>';
		} while ($row_mos_subcat = mysql_fetch_assoc($mos_subcat));
		echo $html;              
	}
}
?>