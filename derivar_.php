<?php require_once('Connections/conexion.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2,3,4";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
$msj ='';
date_default_timezone_set('America/Lima');
$fechaactual = date('Y-m-d');
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
//lista users drow
mysql_select_db($database_conexion, $conexion);
if ($perid == 1)//admin
$query_lista_user = "SELECT * FROM call_usuario where emp_id=".$empid." and per_id in (2)";
else 
$query_lista_user = "SELECT * FROM call_usuario where usu_id=".$usuid."";
$lista_user = mysql_query($query_lista_user, $conexion) or die(mysql_error());
$row_lista_user = mysql_fetch_assoc($lista_user);
$totalRows_lista_user = mysql_num_rows($lista_user);
//ver todos registros
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super admin
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro";
elseif ($perid == 1 or $perid == 4)//admin
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro where emp_id = ".$empid."";
else
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro where usu_id = ".$usuid."";
$mos_vertodos = mysql_query($query_mos_vertodos, $conexion) or die(mysql_error());
$row_mos_vertodos = mysql_fetch_assoc($mos_vertodos);
$totalRows_mos_vertodos = mysql_num_rows($mos_vertodos);
//estados
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre,e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id GROUP BY  e.est_nombre";
elseif ($perid == 1 or $perid == 4)//admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.emp_id = ".$empid." GROUP BY e.est_nombre";
else
$query_mos_estado = "SELECT e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.usu_id = ".$usuid." GROUP BY e.est_nombre";
$mos_estado = mysql_query($query_mos_estado, $conexion) or die(mysql_error());
$row_mos_estado = mysql_fetch_assoc($mos_estado);

//registros
$maxRows_mos_registro = 20;
$pageNum_mos_registro = 0;
if (isset($_GET['pageNum_mos_registro'])) {
  $pageNum_mos_registro = $_GET['pageNum_mos_registro'];
}
$startRow_mos_registro = $pageNum_mos_registro * $maxRows_mos_registro;

mysql_select_db($database_conexion, $conexion);
if ($perid == 1 or $perid == 4)//super admin
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre,u.usu_nombre,em.emp_nombre FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id and u.per_id in (1,4) inner join call_empresa em on r.emp_id = em.emp_id 
  where r.usu_id=".$usuid." and r.est_id=1 order by reg_id desc";
$query_limit_mos_registro = sprintf("%s LIMIT %d, %d", $query_mos_registro, $startRow_mos_registro, $maxRows_mos_registro);
$mos_registro = mysql_query($query_limit_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
	
	if (isset($_GET['totalRows_mos_registro'])) {
	  $totalRows_mos_registro = $_GET['totalRows_mos_registro'];
	} else {
	  $all_mos_registro = mysql_query($query_mos_registro);
	  $totalRows_mos_registro = mysql_num_rows($all_mos_registro);
	}
	$totalPages_mos_registro = ceil($totalRows_mos_registro/$maxRows_mos_registro)-1;

//busqueda
if(isset($_POST['buscar']))
{
	$maxRows_mos_registro = 20;
$pageNum_mos_registro = 0;
if (isset($_GET['pageNum_mos_registro'])) {
  $pageNum_mos_registro = $_GET['pageNum_mos_registro'];
}
$startRow_mos_registro = $pageNum_mos_registro * $maxRows_mos_registro;
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super admin
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre,u.usu_nombre,em.emp_nombre FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id inner join call_empresa em on r.emp_id = em.emp_id WHERE reg_apellidos LIKE '%".$_POST['buscar']."%' order by reg_id desc";
elseif ($perid == 1 or $perid == 4)//admin
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre,u.usu_nombre,em.emp_nombre FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id inner join call_empresa em on r.emp_id = em.emp_id WHERE reg_apellidos LIKE '%".$_POST['buscar']."%' and r.emp_id=".$empid." order by reg_id desc";
else
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre,u.usu_nombre,em.emp_nombre FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id inner join call_empresa em on r.emp_id = em.emp_id WHERE reg_apellidos LIKE '%".$_POST['buscar']."%' and r.usu_id=".$usuid." order by reg_id desc";  
$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
	
	if (isset($_GET['totalRows_mos_registro'])) {
	  $totalRows_mos_registro = $_GET['totalRows_mos_registro'];
	} else {
	  $all_mos_registro = mysql_query($query_mos_registro);
	  $totalRows_mos_registro = mysql_num_rows($all_mos_registro);
	}
	$totalPages_mos_registro = ceil($totalRows_mos_registro/$maxRows_mos_registro)-1;

}
//nuevop empresa o user
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super admin
	{
	$query_mos_empresa = "SELECT * FROM call_empresa order by emp_nombre asc";
	$prefijo = 'emp';
	}
else
	{
	$query_mos_empresa = "SELECT * FROM call_usuario where emp_id=".$empid." and per_id in (2) order by usu_nombre asc";
	$prefijo = 'usu';
	}
	$mos_empresa = mysql_query($query_mos_empresa, $conexion) or die(mysql_error());
	$row_mos_empresa = mysql_fetch_assoc($mos_empresa);
//busqueda
if(isset($_POST['curso']))
{	
	$Productos = $_POST["curso"];
	$QueryCurso  = '';
	$SqlCurso = '';
	
   	for ($i=0;$i<count($Productos);$i++) 
      	{//echo "<br> " . $i . ": " . $Productos[$i]; 
		$QueryCurso = $QueryCurso . ("r.cur_id = " . "'" . $Productos[$i] . "'" . " OR ");
      	} 
		$length = strlen($QueryCurso);
		if ($length > 0)
            {
                $QueryCurso = substr($QueryCurso, 0, $length - 4);
                $SqlCurso= "(". $QueryCurso .")";
            } 	
		//
	$empresas = $_POST["empresa"];
	$QueryEmpresa  = '';
	$SqlEmpresa = '';
	
   	for ($i=0;$i<count($empresas);$i++) 
	{
		$QueryEmpresa = $QueryEmpresa . ("r.".$prefijo."_id = " . "'" . $empresas[$i] . "'" . " OR ");//o user o empresa
    } 
		$length = strlen($QueryEmpresa);
		if ($length > 0)
            {
                $QueryEmpresa = substr($QueryEmpresa, 0, $length - 4);
                $SqlEmpresa= "(". $QueryEmpresa .")";
            } 	
		//
		$Condiciones = '';
		$Condiciones = " where ". $SqlCurso ." AND ". $SqlEmpresa ." order by reg_id desc";
		
		$maxRows_mos_registro = 20;
$pageNum_mos_registro = 0;
if (isset($_GET['pageNum_mos_registro'])) {
  $pageNum_mos_registro = $_GET['pageNum_mos_registro'];
}
$startRow_mos_registro = $pageNum_mos_registro * $maxRows_mos_registro;
		
	$consulta1 = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, r.usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre,u.usu_nombre,em.emp_nombre FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id INNER JOIN call_curso c ON r.cur_id = c.cur_id inner join call_usuario u on r.usu_id = u.usu_id inner join call_empresa em on r.emp_id = em.emp_id";
	$query_mos_registro = $consulta1.$Condiciones;
	$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
	$row_mos_registro = mysql_fetch_assoc($mos_registro);

	if (isset($_GET['totalRows_mos_registro'])) {
	  $totalRows_mos_registro = $_GET['totalRows_mos_registro'];
	} else {
	  $all_mos_registro = mysql_query($query_mos_registro);
	  $totalRows_mos_registro = mysql_num_rows($all_mos_registro);
	}
	$totalPages_mos_registro = ceil($totalRows_mos_registro/$maxRows_mos_registro)-1;
}
//Productos
mysql_select_db($database_conexion, $conexion);
$query_mos_Productos = "SELECT * FROM call_curso where cur_activo = 1 order by cur_nombre asc";
$mos_Productos = mysql_query($query_mos_Productos, $conexion) or die(mysql_error());
$row_mos_Productos = mysql_fetch_assoc($mos_Productos);

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT * FROM call_curso where cur_activo = 1 order by cur_nombre asc";
$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);
//
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $insertSQL = sprintf("INSERT INTO call_registro (reg_fecha, est_id, usu_id, reg_apellidos, reg_nombres, cur_id, reg_formacion, reg_observaciones, reg_pais, reg_email, reg_telefono,reg_telefono2,emp_id) VALUES ( %s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
                       GetSQLValueString($fechaactual, "date"),
                       GetSQLValueString(1, "int"),
                       GetSQLValueString($_POST['usu_id'], "int"),
                       GetSQLValueString($_POST['reg_apellidos'], "text"),
                       GetSQLValueString($_POST['reg_nombres'], "text"),
                       GetSQLValueString($_POST['cur_id'], "int"),
                       GetSQLValueString($_POST['reg_formacion'], "text"),
                       GetSQLValueString($_POST['reg_observaciones'], "text"),
                       //GetSQLValueString($_POST['reg_ciudad'], "text"),
                       GetSQLValueString($_POST['reg_pais'], "text"),
                       GetSQLValueString($_POST['reg_email'], "text"),
                       GetSQLValueString($_POST['reg_telefono'], "text"),
                       GetSQLValueString($_POST['reg_telefono2'], "text"),
                       GetSQLValueString($empid, "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  //mensje x curso
	$colname_mos_msjexcurso = "-1";
	if (isset($_POST['cur_id'])) {
	  $colname_mos_msjexcurso = $_POST['cur_id'];
	}
	mysql_select_db($database_conexion, $conexion);
	$query_mos_msjexcurso = sprintf("SELECT * FROM call_curso WHERE cur_id = %s", GetSQLValueString($colname_mos_msjexcurso, "int"));
	$mos_msjexcurso = mysql_query($query_mos_msjexcurso, $conexion) or die(mysql_error());
	$row_mos_msjexcurso = mysql_fetch_assoc($mos_msjexcurso);
	$totalRows_mos_msjexcurso = mysql_num_rows($mos_msjexcurso);
  //
  //config
mysql_select_db($database_conexion, $conexion);
$query_mos_config = "SELECT * FROM call_config ORDER BY conf_id desc limit 1";
$mos_config = mysql_query($query_mos_config, $conexion) or die(mysql_error());
$row_mos_config = mysql_fetch_assoc($mos_config);
$totalRows_mos_config = mysql_num_rows($mos_config);
  //mail
$url = $row_mos_config['conf_url']; //http://reinademipromo.com'
$correo = $row_mos_config['conf_correo'];//'informes@reinademipromo.com'

require('class.phpmailer.php');

$mail = new PHPMailer();
$mail->Host = "localhost";
$mail->From = $correo;
$mail->FromName = $row_mos_msjexcurso['cur_remitente'];// remitente"Reina de mi Promo"background=".$url."/images/confirmavoto.jpg
$mail->Subject = $row_mos_msjexcurso['cur_asunto'];//asunto "Gracias por Inscribirte en nuestro concurso"

$mail->AddAddress($_POST['reg_email']);
$content="<table width=614 height=584 border=0 align=center cellpadding=0 cellspacing=0>
  
  <tr>
    <td>
  ".$row_mos_msjexcurso['cur_descripcion']."
  </td>
    
  </tr>
  
   <tr>
   <td height=35><div align=center class=home_masvistos_campos>Para fijar este email correctamente, a&ntilde;ade <a href=mailto:".$correo." class=monthlink>".$correo."</a> a tus contactos</div></td>
 </tr>
  
    <td>
            
  </td>
  
  </td>
  </tr>
</table>";

			$mail->MsgHTML($content);
			
			if(!$mail->Send()) {
			
			} else {
			
			}
  //
  $m = 'Derivado Correctamente';
  //
  $insertGoTo = "derivar.php?n=$m";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
//
// obtenemos msje resultado insertado
if (isset($_GET['n'])) 
{
  $var = $_GET['n'];
  $msj = '<div class="alert alert-info alert-dismissable">
         <i class="fa fa-info"></i>
		 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <b>Alerta !</b>'.$var.'!!  </div>';
}
//eliminar y asignar
/*
$tipo = '';
$tipo = isset($_POST['tipo']);*/
if(isset($_POST['casilla']))
{
$checkbox2 = isset($_POST['casilla']) ? $_POST['casilla'] : NULL;
$user = $_POST['usu_id'];
$emp = $_POST['emp_id'];
for ($i=0;$i<sizeof($checkbox2);$i++)
{
		//update
		$query2="update call_registro set emp_id=". $emp .", usu_id = ".$user." WHERE reg_id = '".$checkbox2[$i]."'";
  		$Result1 = mysql_query($query2, $conexion) or die(mysql_error());
}
	//
  $m = 'Cliente Cambiado Correctamente';
  //
  $insertGoTo = "derivar.php?n=$m";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));	
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert11"])) && ($_POST["MM_insert11"] == "form11")) {
	
  $insertSQL = sprintf("INSERT INTO call_comentario(emp_id,usu_id, com_comentario,com_fechareg) VALUES ( %s,%s, %s,%s)", 
                       GetSQLValueString($empid, "int"),
                       GetSQLValueString($usuid, "int"),
                       GetSQLValueString($_POST['com_comentario'], "text"),
					   GetSQLValueString($fechaactual, "date"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  //
  $m = ' Comentrario Registrado Correctamente !!';
  //
  $insertGoTo = "index.php?n=$m";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/jQueryUI/jquery-ui-1.10.3.custom.css">
        <link rel="stylesheet" type="text/css" href="css/jQueryUI/jquery-ui-1.10.3.custom.min.css">
        <link  type="text/css" rel="stylesheet" href="css/timepicker/bootstrap-timepicker.min.css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <!-- BOOTSTRAP-FULLSCREEN-SELECT files -->
        <script src="combo/demo/libs/jquery.min.js"></script>
        <script src="combo/demo/libs/bootstrap.min.js"></script>
        <script src="combo/demo/libs/pretty.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="combo/css/bootstrap-fullscreen-select.css" />
        <script type="text/javascript" src="combo/js/bootstrap-fullscreen-select-arthur.js"></script>
      
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                WEB            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                       <li><a href="importar.php" class="hide-option"><i class="fa fa-upload"></i></a></li>
                        <!-- User Account: style can be found in dropdown.less -->
                       <li class="dropdown notifications-menu">
                            <?php if ($perid != 2) { ?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gear"></i>
                                <!--<span class="label label-warning">10</span>-->
                            </a>
<?php } ?>
                            <ul class="dropdown-menu">
                                <!--<li class="header">You have 10 notifications</li>-->
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;"><ul class="menu" style="overflow: hidden; width: 100%;">
                                        <?php if ($perid == 3) { ?>
                                        <li>
                                            <a href="curso.php">
                                                <i class="fa fa-briefcase"></i> Productos
                                            </a>
                                        </li>
                                         <li>
                                            <a href="config.php">
                                                <i class="fa fa-gears success"></i> Configuración
                                            </a>
                                        </li>
                                        <li>
                                            <a href="empresa.php">
                                                <i class="fa fa-align-center mailbox bg-black"></i> Empresas
                                            </a>
                                        </li>
                                      <li>
                                            <a href="estado.php">
                                                <i class="fa fa-retweet bg-orange"></i> Estados
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="usuario.php">
                                                <i class="fa fa-users danger"></i> Usuarios
                                            </a>
                                        </li>
                                        <li>
                                             <a href="coments.php">
                                                <i class="fa fa-comment bg-blue"></i> Comentarios
                                            </a>
                                        </li>
                                    </ul><div class="slimScrollBar" style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; z-index: 99; right: 1px; height: 156.86274509803923px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
                                </li>
                                <li class="footer"><!--<a href="#">View all</a>--></li>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span> <i class="caret"></i></span>
                            <ul class="dropdown-menu">
                            
                                <!-- User image -->
                              
                   <li class="user-header bg-light-blue">
         				<div class="text-center" >
            			<img src="imagenes/anonimo90.png" class="img-circle" style="margin:10px 0px 10px 0px;" alt="User Image" />
                        <p style="margin:10px 0px 10px 0px;">
						<?php echo $row_mos_usuario['usu_nombre'];?>
                        </p>
                        </div>
                   </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                   <!-- <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>-->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Mi Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo $logoutAction ?>" class="btn btn-default btn-flat">Salir</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
            <!-- div principal -->
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
<img src="imagenes/anonimo.png"  class="img-circle">

                      </div>
                        <div class="pull-left info">
                            <p><?php echo $row_mos_usuario['usu_nombre'];?></p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Conectado</a>
                        </div>
                    </div>
                    
                    <!-- search form 
                    <form name="form1" id="form1" action="" method="post" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" id="q" name="q" class="form-control" placeholder="Buscar por DNI ..." maxlength="8"/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button><input type="hidden" name="MM_insert" value="form1">
                            </span>
                        </div>
                    </form>-->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less 
                    <ul class="sidebar-menu">
                  
                      <?php do { ?>
                        <li> 
                          <a href="categoria.php?es=<?php echo $row_mos_estado['est_id'];?>"><span><?php echo $row_mos_estado['est_nombre'];?></span> 
                            <small class="badge pull-right bg-<?php echo $row_mos_estado['est_color'];?>">
                            <?php echo $row_mos_estado['cant'];?> 
                            </small>
                          </a>
                        </li>
                        <?php } while ($row_mos_estado = mysql_fetch_assoc($mos_estado)); ?>
                        

                       	<li>
                        <a href="index.php">
                        <div class="row">
                        	<div class="col-md-6"><strong>Todos</strong></div>
                        	<div class="col-md-6 text-right">
                            	<strong><?php echo $row_mos_vertodos['cant'];?></strong>
                            </div>
                        </div>
                        </a>
                        </li>-->
                        <li>
                        <div class="row" style="margin:10px 0px 10px 0px;">
                            <div class="col-md-12 text-center">
<button class="btn btn-success btn-sm" onclick="window.location='excel.php'">
                            	<i class="fa fa-cloud-download"></i> Descargar EXCEL</button>
                            </div>
                        </div>
                        <div class="row" style="margin:0px 0px 10px 0px;">   
                            <div class="col-md-12 text-center">
                            	<button class="btn btn-danger btn-sm" onclick="window.location='pdf.php'">
                            	<i class="fa fa-cloud-download"></i> Descargar PDF</button>
                            </div>                        
                        </div>
                        </li>
                  </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) 
                <section class="content-header">
                    <h1>
                        </h3><small> <a href="index.php">Inicio</a></small>
                    </h1>
                    <ol class="breadcrumb">
                    </ol>
                </section>-->

                <!-- Main content -->
                <section class="content">
                <?php echo $msj;?>
                
<div class="row">
                
                <div class="col-md-12">
                
                <div class="box box-primary">
                                
                  <div class="box-header"> <h3 class="box-title">Derivar</h3></div>
                   
                  <div class="box-body">
     
                		<div class="box-body table-responsive no-padding">
  <?php if ($totalRows_mos_registro !== 0) { 
						  
 // Show if recordset emptyxx ?><?php //$tipo = isset($_POST['tipo']); if($tipo == 1) echo 'eliminar.php'; elseif($tipo == 2) echo 'asignar.php';?>
 <form name="frm" action="" method="post">
  <table class="table table-bordered table-striped table-hover table-condensed tablesorter">
  		<tr>
        <td></td>
        <td><strong>Fecha</strong></td>
        <td align="center"><strong>E</strong></td> 
        <!--<td><strong>Código</strong></td>-->
        <td><strong>Nombre del Postulante</strong></td>
        <td><strong>Curso</strong></td>
        <!--<td>Email</td>-->
        <td><strong>Teléfono</strong></td>
        <td><strong>Nombre del Asesor</strong></td>
        <td><strong>Empresa</strong></td>
        <td><strong>Observaciones</strong></td>
        <td></td>
      </tr>
      <?php do { ?>
        <tr>
          <td>	<?php if ($perid != 2) { ?>
         	 <input name="casilla[]" type="checkbox" id="casilla[]" value="<?php echo $row_mos_registro['reg_id']; ?>">
          		<?php } ?>
          </td>
          <td><?php echo $row_mos_registro['reg_fecha']; ?></td>
          <td align="center"><small class="badge pull-right bg-<?php echo $row_mos_registro['est_color'];?>">
			 	<br>
              </small>
          </td>
          <!--<td>
		  <a href="detalle_reg.php?id=echo $row_mos_registro['reg_id']; ">
		  $row_mos_registro['reg_id']; 
          </a>
          </td>-->
          <td><a href="detalle_reg.php?id=<?php echo $row_mos_registro['reg_id']; ?>">
		  	  <?php echo $row_mos_registro['cliente']; ?>
              </a>
          </td>
          <td><?php echo $row_mos_registro['cur_nombre']; ?></td>
          <td><?php echo $row_mos_registro['reg_telefono']; ?></td>
          <td><?php echo $row_mos_registro['usu_nombre']; ?></td>
                    <td><?php echo $row_mos_registro['emp_nombre']; ?></td>
          <td><?php echo $row_mos_registro['reg_observaciones']; ?></td>
          <td><?php if ($perid != 2) { // Show if recordset empty ?>
              <div align="center"><a onclick="return confirm('¿Seguro que desea eliminar?')" href="delete_reg.php?id=<?php echo $row_mos_registro['reg_id']; ?>" title="Eliminar" class="hide-option">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Eliminar"><i class="fa fa-trash-o"></i></button>
              </a></div>
              <?php } // Show if recordset empty ?></td>
        </tr>
        <?php } while ($row_mos_registro = mysql_fetch_assoc($mos_registro)); ?>
    <tr>
    <tr>
    	<td colspan="10">
        <div class="row">
        		<div class="col-md-8">
        <table>
        	<tr>
            <td>
<?php if ($pageNum_mos_registro > 0) { // Show if not first page ?>
        <a title="Primero" href="<?php printf("%s?pageNum_mos_registro=%d%s", $currentPage, 0, $queryString_mos_registro); ?>"> <button class="btn btn-default btn-sm" type="button"><i class="fa fa-step-backward"></i></button></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mos_registro > 0) { // Show if not first page ?>
        <a title="Anterior" href="<?php printf("%s?pageNum_mos_registro=%d%s", $currentPage, max(0, $pageNum_mos_registro - 1), $queryString_mos_registro); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-backward"></i></button></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mos_registro < $totalPages_mos_registro) { // Show if not last page ?>
        <a title="Siguiente" href="<?php printf("%s?pageNum_mos_registro=%d%s", $currentPage, min($totalPages_mos_registro, $pageNum_mos_registro + 1), $queryString_mos_registro); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-forward"></i></button></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_mos_registro < $totalPages_mos_registro) { // Show if not last page ?>
        <a title="Ultimo" href="<?php printf("%s?pageNum_mos_registro=%d%s", $currentPage, $totalPages_mos_registro, $queryString_mos_registro); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-fast-forward"></i></button></a>
        <?php } // Show if not last page ?></td>
      <!-- prueba-->
      
      			</tr>
			</table>
            </div>
            
            <div class="col-md-4 text-right">
      Registros <?php echo ($startRow_mos_registro + 1) ?> a <?php echo min($startRow_mos_registro + $maxRows_mos_registro, $totalRows_mos_registro) ?> de <?php echo $totalRows_mos_registro ?>
      		</div>
       </div>
            
        </td>
      </tr>
  </table>
  
  <div class="row" style="margin-top:10px;">
  	  <?php if ($perid != 2) { ?>
      
            
            <div class="col-md-3">
           <a href="javascript:seleccionar_todo()">Marcar Todos</a> / <a href="javascript:deseleccionar_todo()">Desmarcar Todos</a>
            </div>
           <!-- <input type="button" id="btn-elim" value="eliminar campos">-->
            <div class="col-md-3 text-right">
         <select name="emp_id" id="emp_id" class="form-control">
			<?php
            mysql_select_db($database_conexion, $conexion);
			if ($perid == 3)//super admin
            	$query_mos_emp = "SELECT emp_id,emp_nombre FROM call_empresa";
			else
				$query_mos_emp = "SELECT emp_id,emp_nombre FROM call_empresa where emp_id=".$empid."";
            $mos_emp = mysql_query($query_mos_emp, $conexion) or die(mysql_error());
            $row_mos_emp = mysql_fetch_assoc($mos_emp);
 			echo '<option value="0"> -- Seleccione --</option>';           
			do
            { 
                echo '<option value="'.$row_mos_emp['emp_id'].'">'.$row_mos_emp['emp_nombre'].'</option>';
            } while ($row_mos_emp = mysql_fetch_assoc($mos_emp));              
            ?>
		</select>
        </div>
        <div class="col-md-3 text-right">
           <select id="usu_id" name="usu_id" class="form-control">
          	<option value="0"> -- Seleccione --</option>
          </select>	
        </div>
        <div class="col-md-2">
        <button type="submit" class="btn btn-warning"><i class="fa fa-circle-o"></i> Asignar</button>
        </div>
	  <?php } ?>
      <div class="col-md-1 text-<?php if ($perid != 2) echo 'right'; else 'left';?>">
        <a class="btn btn-bitbucket" data-toggle="modal" data-target="#comentario-modal" title="Comentarios"><i class="fa fa-comment"></i> </a>
      </div>
	  
      
  </form>
  <?php 
  } // Show if recordset empty
  else 
  { 
  echo '<br>';
  echo '<div class="alert alert-info alert-dismissable">
         <i class="fa fa-info"></i>
		 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <b>Alerta !</b> Ningun registro encontrado !!
        </div>';
	}
   ?>
                        </div>
                </div><!-- body -->
    </div><!-- /.primary-->
          </div><!-- /.col-->
    </div> <!-- /.row -->
    <!--- -->
    <!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
<div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title"><i class="fa fa-pencil"></i> Agregar</h4>
                  </div>
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
          <div class="modal-body">
          
                <div class="row">
                  <div class="col-md-6">
                    
                      <div class="form-group">Apellidos Postulante:
                         <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>
                            <input type="text" name="reg_apellidos" value="" class="form-control" required maxlength="50">          </div>
                      </div>
                      
                    </div>
                    <div class="col-md-6">
                    
                      <div class="form-group">Nombres Postulante:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>
                        <input type="text" name="reg_nombres" value="" class="form-control" maxlength="50">
                          </div>
                      </div>
                    </div>
               </div><!--end row-->
                   
               <div class="row">
                  <div class="col-md-12"> 
                      
                      <div class="form-group">Curso:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-briefcase"></i>
                                  </div>
                        <select name="cur_id" class="form-control">
                          <?php 
do {  
?>
                          <option value="<?php echo $row_mos_curso['cur_id']?>" <?php if (!(strcmp($row_mos_curso['cur_id'], $row_mos_curso['cur_id']))) {echo "SELECTED";} ?>><?php echo $row_mos_curso['cur_nombre']?></option>
                          <?php
} while ($row_mos_curso = mysql_fetch_assoc($mos_curso));
?>
                        </select></div>
                      </div>
                    </div>
                    
                </div> 
                
                <div class="row">

                  <div class="col-md-6"> 
                      <div class="form-group">Formacion:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-archive"></i>
                                  </div>
                        <input type="text" name="reg_formacion" value="" class="form-control">
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6"> 
                    
                      <div class="form-group">Nombre del Asesor:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-home"></i>
                                  </div>
                          <select name="usu_id" class="form-control">
                            <?php
do {  
?>
                            <option value="<?php echo $row_lista_user['usu_id']?>"<?php if (!(strcmp($row_lista_user['usu_id'], $row_lista_user['usu_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_user['usu_nombre']?></option>
                            <?php
} while ($row_lista_user = mysql_fetch_assoc($lista_user));
  $rows = mysql_num_rows($lista_user);
  if($rows > 0) {
      mysql_data_seek($lista_user, 0);
	  $row_lista_user = mysql_fetch_assoc($lista_user);
  }
?>
                          </select>
                          </div>
                      </div>
                     </div>
                     
                 </div>
                 
                 <div class="row">
                  <div class="col-md-6"> 
                      <div class="form-group">Pais:
                       <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-flag"></i>
                                  </div>
                        <input type="text" name="reg_pais" value="" class="form-control" maxlength="50">
                        </div>
                      </div>
                     </div>
                  <div class="col-md-6">      
                      <div class="form-group">Email:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                        <input type="email" name="reg_email" value="" class="form-control" maxlength="50">
                          </div>
                      </div>
                   </div>

                 </div>
                 <div class="row">
                  <div class="col-md-6">   
                      <div class="form-group">Teléfono:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-phone-square"></i>
                                  </div>
                        <input type="text" name="reg_telefono" value="" class="form-control" maxlength="30">
                          </div>
                      </div>
                   </div>
                   <div class="col-md-6">   
                      <div class="form-group">Teléfono 2:
                          <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-phone-square"></i>
                                  </div>
                        <input type="text" name="reg_telefono2" value="" class="form-control" maxlength="30">
                          </div>
                      </div>
                   </div>
                 </div>
                   <div class="row">
                 	<div class="col-md-12"> 
                      <div class="form-group">Observaciones:  	
                       <textarea class="form-control" name="reg_observaciones" cols="50" rows="2"></textarea> 	
           			  </div>
                	</div>
                  </div>
                    
              </div><!-- end body-->
                        <div class="modal-footer clearfix">

              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
 			  <input type="hidden" name="MM_insert" value="form1">
              <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                  </form>
                </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

<!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="comentario-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
<div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title"><i class="fa fa-comment"></i> Comentario</h4>
                  </div>
    <form method="post" name="form11" action="<?php echo $editFormAction; ?>">
          <div class="modal-body">
          
                <div class="row">
                  <div class="col-md-12">
                    
                      <div class="form-group"><!--Comentario :
                         <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>-->
                            <textarea class="form-control" name="com_comentario" cols="50" rows="5" placeholder="Escribe tu comentario ..." required></textarea> 	    
                      </div>
                      
                    </div>
 				</div>
                
                <div class="row">
                  <div class="col-md-12">
                  	<a href="coments.php">Ver Comentarios</a>
                  </div>
                </div>

		</div><!-- end body-->
        <div class="modal-footer clearfix">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
 			  <input type="hidden" name="MM_insert11" value="form11">
              <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
         </div>
                  </form>
                </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




  				</section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- add new calendar event modal -->
        <!-- jQuery 2.0.2 -->       
          <!--combo-->
     <script type="text/javascript">
            $(function () {
                $('.mobileSelect').mobileSelect();
            });
        </script>
        <script type="text/javascript">
        	function seleccionar_todo(){ 
  		 		for (i=0;i<document.frm.elements.length;i++) 
      			if(document.frm.elements[i].type == "checkbox")	
         		document.frm.elements[i].checked=1 
				} 
				function deseleccionar_todo(){ 
				   for (i=0;i<document.frm.elements.length;i++) 
					  if(document.frm.elements[i].type == "checkbox")	
						 document.frm.elements[i].checked=0 
					}
        </script>
   <script language="javascript">
    $(document).ready(function(){
       $("#emp_id").change(function () {
               $("#emp_id option:selected").each(function () {
                emp_id = $(this).val();
                $.post("users.php", { emp_id: emp_id }, function(data){
                    $("#usu_id").html(data);
                });            
            });
       })
    });
	</script>
    	<!--<script type="text/javascript">
        $(document.frm).ready(function() {
			$("#btn-elim").click(function() {
				$casilla = $('#casilla').val(); // Nos devuelve el valor
    			$params = { 'casilla' : $casilla };
				 $.ajax({
					url: "eliminador.php",
				 	type: "POST",
				 	data: $params,
				 success: function(datos) {
					alert("eliminados");
				 }
			  });
			});
		});
        </script>-->
</body>
</html>

