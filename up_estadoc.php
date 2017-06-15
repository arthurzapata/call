<?php require_once('Connections/conexion.php'); ?>
<?php
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
if ((isset($_GET['pk'])) && ($_GET['pk'] != "")) {

$key = $_GET['pk'];

mysql_select_db($database_conexion, $conexion);
$query_mos_doc = "SELECT * FROM call_curso where cur_id = $key";
$mos_doc = mysql_query($query_mos_doc, $conexion) or die(mysql_error());
$row_mos_doc = mysql_fetch_assoc($mos_doc);
$totalRows_mos_doc = mysql_num_rows($mos_doc);

	if ($row_mos_doc['cur_activo'] == 0) 
		$es = 1;
	elseif($row_mos_doc['cur_activo'] == 1)
		$es = 0;
	
  $deleteSQL = sprintf("update call_curso set cur_activo = $es where cur_id=%s",
                       GetSQLValueString($_GET['pk'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

  $deleteGoTo = "curso.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
mysql_free_result($mos_doc);
?>
