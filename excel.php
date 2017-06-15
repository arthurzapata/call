<?php require_once('Connections/conexion.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
//usuario logueado
$colname_mos_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mos_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion, $conexion);
$query_mos_usuario = sprintf("SELECT * FROM call_usuario WHERE usu_nombre = %s", GetSQLValueString($colname_mos_usuario, "text"));
$mos_usuario = mysql_query($query_mos_usuario, $conexion) or die(mysql_error());
$row_mos_usuario = mysql_fetch_assoc($mos_usuario);
$totalRows_mos_usuario = mysql_num_rows($mos_usuario);
//variables
$usuid = $row_mos_usuario['usu_id'];
$perid =$row_mos_usuario['per_id'];
$empid = $row_mos_usuario['emp_id'];

if(isset($_GET['es']))// por estados
{
  $idestado = $_GET['es'];
  mysql_select_db($database_conexion, $conexion);
  if ($perid == 3)//owner
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
    FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.est_id = ". $idestado." ORDER BY reg_id desc"; 
  elseif ($perid == 4)//admin
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
    FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.emp_id = ".$empid." and r.est_id = ". $idestado." ORDER BY reg_id desc"; 
  elseif($perid == 1)
       $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
    FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.emp_id = ".$empid." and r.est_id = ". $idestado." and u.cor_id = ". $usuid." or u.usu_id=".$usuid." ORDER BY reg_id desc"; 
  else
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono,reg_telefono2,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
    FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.usu_id = ".$usuid." and r.est_id = ". $idestado." ORDER BY reg_id desc";
  $mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
  $row_mos_registro = mysql_fetch_assoc($mos_registro);
  $totalRows_mos_registro = mysql_num_rows($mos_registro);
}
else
{
  mysql_select_db($database_conexion, $conexion);
  if ($perid == 3)//owner
      $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono2,reg_telefono,reg_telefono2,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
      FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id ORDER BY reg_id desc"; 
  elseif ($perid == 4)//admin
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono2,reg_telefono,reg_telefono2,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
      FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.emp_id = ".$empid." ORDER BY reg_id desc"; 
  elseif($perid == 1)
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono2,reg_telefono,reg_telefono2,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
      FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.emp_id = ".$empid." and u.cor_id = ". $usuid." or u.usu_id=".$usuid." ORDER BY reg_id desc"; 
  else
    $query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') AS reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_pais,reg_email,reg_telefono,reg_telefono2,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  
    FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id INNER JOIN call_usuario u ON r.usu_id = u.usu_id  WHERE r.usu_id = ".$usuid." ORDER BY reg_id desc";
  //$query_mos_registro = "SELECT reg_id,reg_codigo,reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id, reg_apellidos,reg_nombres,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_nombre,e.est_color,c.cur_nombre,u.usu_nombre  FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id order by reg_id desc";
  $mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
  $row_mos_registro = mysql_fetch_assoc($mos_registro);
  $totalRows_mos_registro = mysql_num_rows($mos_registro);
}
//todos

?>
<?php 
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename= reporte.xls");
?>
<html>
<head>
   <meta charset="UTF-8">
	 <title>Reporte en excel</title>
</head>
<body>
<table border="1">
      <tr>
        <td><strong>Código</strong></td>
        <td><strong>Fecha</strong></td> 
        <td><strong>Estado</strong></td>
        <td><strong>Curso</strong></td>
        <!--<td><strong>Fecha</strong></td> -->
        <td><strong>Nombre del Asesor</strong></td>
        <td><strong>Apellidos</strong></td> 
        <td><strong>Nombre</strong></td>
        <td><strong>Formacion</strong></td>
        <td><strong>Observaciones</strong></td>
        
        <td><strong>Pais</strong></td>
        <td>Email</td>
        <td><strong>Telefono</strong></td>
        <td><strong>Telefono 2</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_mos_registro['reg_id']; ?></td>
          <td><?php echo $row_mos_registro['reg_fechareg']; ?></td>
          <td><?php echo $row_mos_registro['est_nombre'];?></td>
          <td><?php echo $row_mos_registro['cur_nombre']; ?></td>
          
          <td><?php echo $row_mos_registro['usu_nombre']; ?></td>
          <td><?php echo $row_mos_registro['reg_apellidos']; ?></td>
          <td><?php echo $row_mos_registro['reg_nombres']; ?></td>
          <td><?php echo $row_mos_registro['reg_formacion']; ?></td>
          <td><?php echo $row_mos_registro['reg_observaciones']; ?></td>
          
          <td><?php echo $row_mos_registro['reg_pais']; ?></td>
          <td><?php echo $row_mos_registro['reg_email']; ?></td>
          <td><?php echo $row_mos_registro['reg_telefono']; ?></td>
          <td><?php echo $row_mos_registro['reg_telefono2']; ?></td>
        </tr>
      <?php } while ($row_mos_registro = mysql_fetch_assoc($mos_registro)); ?>
</table>
</body>
</html>