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
$userid = $row_mos_usuario['usu_id'];
//ver todos registros
mysql_select_db($database_conexion, $conexion);
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro";
$mos_vertodos = mysql_query($query_mos_vertodos, $conexion) or die(mysql_error());
$row_mos_vertodos = mysql_fetch_assoc($mos_vertodos);
$totalRows_mos_vertodos = mysql_num_rows($mos_vertodos);
//estados
mysql_select_db($database_conexion, $conexion);
$query_mos_estados = "SELECT e.est_id,e.est_nombre,COUNT(r.est_id) AS cant,est_color FROM call_registro r  RIGHT JOIN call_estado e ON r.est_id = e.est_id GROUP BY  e.est_id";
$mos_estados = mysql_query($query_mos_estados, $conexion) or die(mysql_error());
$row_mos_estados = mysql_fetch_assoc($mos_estados);
$totalRows_mos_estados = mysql_num_rows($mos_estados);
//Productos
mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT * FROM call_curso where cur_activo = 1 order by cur_nombre asc";
$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);
//registros
$maxRows_mos_registro = 20;
$pageNum_mos_registro = 0;
if (isset($_GET['pageNum_mos_registro'])) {
  $pageNum_mos_registro = $_GET['pageNum_mos_registro'];
}
$startRow_mos_registro = $pageNum_mos_registro * $maxRows_mos_registro;

mysql_select_db($database_conexion, $conexion);
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre 
FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id
INNER JOIN call_curso c ON r.cur_id = c.cur_id order by reg_id desc";	
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
//} end if
//busqueda
if(isset($_POST['buscar']))
{
mysql_select_db($database_conexion, $conexion);
$query_mos_registro = "SELECT reg_id,reg_codigo,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.cur_nombre 
FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id
INNER JOIN call_curso c ON r.cur_id = c.cur_id WHERE reg_apellidos LIKE '%".$_POST['buscar']."%' order by reg_id desc";
$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
//$totalRows_mos_registro = mysql_num_rows($mos_registro);
}
//
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	mysql_select_db($database_conexion, $conexion);
	$query_nro_ped = "SELECT MAX(reg_codigo) + 1 AS id FROM call_registro";
	$nro_ped = mysql_query($query_nro_ped, $conexion) or die(mysql_error());
	$row_nro_ped = mysql_fetch_assoc($nro_ped);
	$totalRows_nro_ped = mysql_num_rows($nro_ped);

	if ($row_nro_ped['id']== "" && $row_nro_ped['id']== 0)	
	$codigo = 1; else $codigo = $row_nro_ped['id'];
	
$insertSQL = sprintf("INSERT INTO call_registro (reg_codigo, est_id, usu_id, reg_apellidos, reg_nombres, cur_id, reg_formacion, reg_observaciones, reg_ciudad, reg_pais, reg_email, reg_telefono) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($codigo, "int"),
                       GetSQLValueString(1, "int"),
                       GetSQLValueString($userid, "int"),
                       GetSQLValueString($_POST['reg_apellidos'], "text"),
                       GetSQLValueString($_POST['reg_nombres'], "text"),
                       GetSQLValueString($_POST['cur_id'], "int"),
                       GetSQLValueString($_POST['reg_formacion'], "text"),
                       GetSQLValueString($_POST['reg_observaciones'], "text"),
                       GetSQLValueString($_POST['reg_ciudad'], "text"),
                       GetSQLValueString($_POST['reg_pais'], "text"),
                       GetSQLValueString($_POST['reg_email'], "text"),
                       GetSQLValueString($_POST['reg_telefono'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  //mail
$url = 'http://reinademipromo.com';
$correo = 'informes@reinademipromo.com';

require('class.phpmailer.php');

$mail = new PHPMailer();
$mail->Host = "localhost";
$mail->From = $correo;
$mail->FromName = "Reina de mi Promo";//
$mail->Subject = "Gracias por Inscribirte en nuestro concurso";//asunto

$mail->AddAddress($_POST['reg_email']);
$content="<table width=614 height=584 border=0 align=center cellpadding=0 cellspacing=0>

  <tr>
    <td height=170></td>
  </tr>
  
  <tr>
    <td height=44><div align=center class=home_masvistos_campos> <font color=#597CB6 size=6>Gracias por Inscribirte</font></div></td>
  </tr>
  <tr>
    <td height=1 bgcolor=#8ec140>
	Buenas en breve un asesor se comunicara con usted !!.
	</td>
		
  </tr>
  
   <tr>
   <td height=35><div align=center class=home_masvistos_campos>Para fijar este email correctamente, a&ntilde;ade <a href=mailto:".$correo." class=monthlink>".$correo."</a> a tus contactos</div></td>
 </tr>
  
   <tr>
        <td height=68><div align=center class=home_masvistos_texto>Si ya no deseas recibir este aviso, haz clic <a class=monthlink href=".$url."/parametros.php><font color=#FFFFFF>aqu&iacute;</font></a> para modificar tus opciones.<br />
        </div></td>
      </tr>
	
	</td>
  </tr>
</table>";

			$mail->MsgHTML($content);
			
			if(!$mail->Send()) {
			
			} else {
			
			}
  //
  $m = 'Registrado Correctamente';
  //
  $insertGoTo = "index.php?n=$m";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
// obtenemos msje resultado insertado
if (isset($_GET['n'])) 
{
  $var = $_GET['n'];
  $msj = '<div class="alert alert-info alert-dismissable">
         <i class="fa fa-info"></i>
		 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <b>Alerta !</b>'.$var.'!!  </div>';
}
require_once('clases.php');
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
        <!-- Morris chart -->
        <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
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
                                    <li>
                                            <a href="curso.php">
                                                <i class="fa fa-briefcase"></i> Productos
                                            </a>
                                        </li>
                                        <li>
                                            <a href="usuario.php">
                                                <i class="fa fa-users danger"></i> Usuarios
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
                                      <!--	<li>
                                            <a href="config.php">
                                                <i class="fa fa-gear"></i> Configuración
                                            </a>
                                        </li>
  
                                        
                                        <li>
                                            <a href="#">
                                                <i class="ion ion-ios7-person danger"></i> You changed your username
                                            </a>
                                        </li>-->
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
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                  
                        <?php do { ?>   
                         <li>
                            <a href="categoria.php?es=<?php echo $row_mos_estados['est_id'];?>">
                        	<span><?php echo $row_mos_estados['est_nombre'];?></span> 
                            <small class="badge pull-right bg-<?php echo $row_mos_estados['est_color'];?>">
								<?php echo $row_mos_estados['cant'];?>
                            </small>
                            </a>
                        </li>
						<?php } while ($row_mos_estados = mysql_fetch_assoc($mos_estados)); ?>
                       	<li>
                        <a href="index.php">
                        <div class="row">
                        	<div class="col-md-6"><strong>Todos</strong></div>
                        	<div class="col-md-6 text-right">
                            	<strong><?php echo $row_mos_vertodos['cant'];?></strong>
                            </div>
                        </div>
                        </a>
                        </li>
                        <li>
                        <div class="row" style="margin:10px 0px 10px 0px;">
                            <div class="col-md-12 text-center">
<button class="btn btn-success btn-sm" onclick="window.location='excel.php?usuid=<?php echo $userid; ?>'">
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
                                
                   
                  <div class="box-header"> <!--<h3 class="box-title">Inscripción</h3>--></div>
                   
                  <div class="box-body">
                  	<div class="row" style="margin-bottom:10px;">
                   		<div class="col-sm-6">
                        	<a class="btn btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i> Agregar</a>
						</div>
                		<div class="col-sm-6 search-form">
                            <form name="formb" id="formb" action="" method="post" class="text-right">
                                  <div class="input-group">                                          
                                     <input type="text" name="buscar" class="form-control" placeholder="Buscar por Apellidos ...">
                                 <div class="input-group-btn">
                                 <button type="submit" name="q" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                 </div>
                        </div>                                                     
                        </form>
                        </div>
                   </div>     
                		<div class="box-body table-responsive no-padding">
   <?php if ($totalRows_mos_registro !== 0) { 
						  
 // Show if recordset emptyxx ?>
  <table class="table table-bordered table-striped table-hover table-condensed tablesorter">
  		<tr>
        <td><strong>Fecha</strong></td>
        <td align="center"><strong>E</strong></td>
        <td><strong>Código</strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Curso</strong></td>
        <!--<td>Email</td>-->
        <td><strong>Telefono</strong></td>
        <td><strong>Ciudad</strong></td>
        <td><strong>Observaciones</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          
          <td><?php echo $row_mos_registro['reg_fechareg']; ?></td>
          <td align="center"><small class="badge pull-right bg-<?php echo $row_mos_registro['est_color'];?>">
			 	<br>
              </small>
          </td>
          <td>
		  <a href="detalle_reg.php?id=<?php echo $row_mos_registro['reg_id']; ?>">
		  <?php echo $row_mos_registro['reg_codigo']; ?>
          </a>
          </td>
          <td><a href="detalle_reg.php?id=<?php echo $row_mos_registro['reg_id']; ?>">
		  	  <?php echo $row_mos_registro['cliente']; ?>
              </a>
          </td>
          <td><?php echo $row_mos_registro['cur_nombre']; ?></td>
          <td><?php echo $row_mos_registro['reg_telefono']; ?></td>
          <td><?php echo $row_mos_registro['reg_ciudad']; ?></td>
          <td><?php echo $row_mos_registro['reg_observaciones']; ?></td>

        </tr>
        <?php } while ($row_mos_registro = mysql_fetch_assoc($mos_registro)); ?>
    <tr>
    <tr>
    	<td colspan="8">
        <div class="row">
        		<div class="col-md-6">
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
            
            <div class="col-md-6 text-right">
      Registros <?php echo ($startRow_mos_registro + 1) ?> a <?php echo min($startRow_mos_registro + $maxRows_mos_registro, $totalRows_mos_registro) ?> de <?php echo $totalRows_mos_registro ?>
      		</div>
       </div>
            
        </td>
      </tr>
  </table>
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
                    
                      <div class="form-group">Apellidos:
                     		 <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>
                        		<input type="text" name="reg_apellidos" value="" class="form-control" required maxlength="50">					</div>
                      </div>
                      
                    </div>
                    <div class="col-md-6">
                    
                      <div class="form-group">Nombres:
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
                	<div class="col-md-6"> 
                      
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
                    
                </div> 
                
                <div class="row">
                	<div class="col-md-6"> 
                    
                      <div class="form-group">Ciudad:
                      		<div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-home"></i>
                                  </div>
                        <input type="text" name="reg_ciudad" value="" class="form-control" maxlength="50">
                      		</div>
                      </div>
                     </div>
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
                 </div>
                 
                 <div class="row">
                 	<div class="col-md-6">      
                      <div class="form-group">Email:
                      		<div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                       	<input type="email" name="reg_email" value="" class="form-control" maxlength="50" required>
                      		</div>
                      </div>
                   </div>
                   
                   <div class="col-md-6">   
                      <div class="form-group">Teléfono:
                      		<div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-phone-square"></i>
                                  </div>
                        <input type="text" id="reg_telefono" name="reg_telefono" value="" class="form-control" maxlength="12">
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


  				</section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- add new calendar event modal -->
        <!-- jQuery 2.0.2 -->
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
   <!-- InputMask -->
        <script src="js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
        <script src="js/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
        <!-- bootstrap time picker -->
        <script src="js/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>        
   <!-- Jquery UI-->
    <link href="css/flick/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
    <script type="text/javascript">
                $(document).ready(function () {
                    (function (a) {
                        a.fn.validCampo = function (b) {
                            a(this).on({ keypress: function (a) {
                                var c = a.which, d = a.keyCode, e = String.fromCharCode(c).toLowerCase(), f = b; (-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || a.preventDefault()
                            }
                            })
                        }
                    })
        (jQuery);
                });
                $(function () {
                    //Para escribir solo letras
                    $('#ContentPlaceHolder1_txtCargo').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó');

                    //Para numeros y letras
                    $('#ContentPlaceHolder1_txtDuracion').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789');
                    $('#ContentPlaceHolder1_txtDisponibilidad').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789');
                    $('#ContentPlaceHolder1_txtCicloAcademico').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789');
                    //Para escribir solo numeros    
                    $('#reg_telefono').validCampo('0123456789');
                   
                    //Para numeros letras y algunos caracteres especiales
                    $('#ContentPlaceHolder1_txtDireccion').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789.,-#');
                    //Numeros, letras y puntos
                    $('#ContentPlaceHolder1_txtNombreEmrpesa').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789.');
                    //Numeros, letras, puntos, comas y punto y coma
                    $('#ContentPlaceHolder1_txtDescripcion').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiouúó0123456789.,;');
                    //para  placas
                    $('#ContentPlaceHolder1_txtbuscar').validCampo(' abcdefghijklmnñopqrstuvwxyziou0123456789-');
                });

    </script>
</body>
</html>
<?php
mysql_free_result($mos_estados);
mysql_free_result($mos_curso);
mysql_free_result($mos_usuario);
mysql_free_result($mos_registro);
?>
