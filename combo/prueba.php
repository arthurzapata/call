<?php require_once('../Connections/conexion.php'); ?>
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

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT * FROM call_curso";
$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);
$totalRows_mos_curso = mysql_num_rows($mos_curso);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
        <script src="demo/libs/jquery.min.js"></script>
        <script src="demo/libs/bootstrap.min.js"></script>
        <script src="demo/libs/pretty.js"></script>
        
        <!-- BOOTSTRAP-FULLSCREEN-SELECT files -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap-fullscreen-select.css" />
        <script type="text/javascript" src="js/bootstrap-fullscreen-select.js"></script>
        <!--END BOOTSTRAP-FULLSCREEN-SELECT files-->
</head>

<body>

<?php
if (!$_POST){ 
?> 
<form action="prueba.php" method="POST"> 
    Nombre: <input type="text" name="nombre"><br> 
    Apellidos: <input type="text" name="apellidos"><br> 
    Email: <input type="text" name="email"> <br> 
  <div class="form-group">
    Cerveza: 
    <select  class="form-control mobileSelect" multiple name="cerveza[]">
      <?php
do {  
?>
      <option value="<?php echo $row_mos_curso['cur_id']?>"<?php if (!(strcmp($row_mos_curso['cur_id'], $row_mos_curso['cur_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_mos_curso['cur_nombre']?></option>
      <?php
} while ($row_mos_curso = mysql_fetch_assoc($mos_curso));
  $rows = mysql_num_rows($mos_curso);
  if($rows > 0) {
      mysql_data_seek($mos_curso, 0);
	  $row_mos_curso = mysql_fetch_assoc($mos_curso);
  }
?>
    </select> 
  </div>
  <input type="submit" value="Enviar datos!" > 
</form> 
<?php
}else{ 

   	echo "Nombre: ". $_POST["nombre"]; 
   	echo "<br>Apellidos: ". $_POST["apellidos"]; 
   	echo "<br>E-mail: ". $_POST ["email"]; 
   	$cervezas=$_POST["cerveza"]; 
	echo '<br>';

   	//recorremos el array de cervezas seleccionadas. No olvidarse q la primera posición de un array es la 0 
	$Query  = '';
	$Sql = '';
   	for ($i=0;$i<count($cervezas);$i++) 
      	{ 
      	//echo "<br> Cerveza " . $i . ": " . $cervezas[$i]; 
		$Query = $Query . ("cur_id = " . "'" . $cervezas[$i] . "'" . " OR ");
      	} 
		$length = strlen($Query);
		if ($length > 0)
            {
                $Query = substr($Query, 0, $length - 4);
                $Sql= "(". $Query .")";
            } 	
		///
		$Condiciones = "";
        $Condiciones = $Sql." AND "." ( Dofe_Estado = 1 )";
		echo $Condiciones;

   } 
?>

        <script type="text/javascript">
            $(function () {
                $('.mobileSelect').mobileSelect();
            });
        </script>
               
</body>
</html>
<?php
mysql_free_result($mos_curso);
?>
