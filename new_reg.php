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
//variables
$usuid = $row_mos_usuario['usu_id'];
$perid =$row_mos_usuario['per_id'];
$empid = $row_mos_usuario['emp_id'];
//ver todos registros
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super admin
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro";
elseif ($perid == 1)//coor
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro r inner join call_usuario u ON r.usu_id = u.usu_id where emp_id = ".$empid." and u.cor_id =".$usuid."";
elseif ($perid == 4)//admin
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
elseif ($perid == 1 )
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id inner join call_usuario u ON r.usu_id = u.usu_id WHERE  r.emp_id = ".$empid." and u.cor_id =".$usuid." GROUP BY e.est_nombre";
elseif ($perid == 4)//admin
  $query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.emp_id = ".$empid." GROUP BY e.est_nombre";
else
$query_mos_estado = "SELECT e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.usu_id = ".$usuid." GROUP BY e.est_nombre";
$mos_estado = mysql_query($query_mos_estado, $conexion) or die(mysql_error());
$row_mos_estado = mysql_fetch_assoc($mos_estado);
//Productos
mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT * FROM producto where pro_activo = 1 order by pro_descripcion asc";
$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);
$totalRows_mos_curso = mysql_num_rows($mos_curso);
//lista users drow
mysql_select_db($database_conexion, $conexion);
if ($perid == 1 )//admin
$query_lista_user = "SELECT * FROM call_usuario where emp_id=".$empid." and cor_id=".$usuid." and per_id in (2) and usu_activo= 1";
elseif($perid == 4)
$query_lista_user = "SELECT * FROM call_usuario where emp_id=".$empid." and per_id in (1,2) and usu_activo= 1";
else 
$query_lista_user = "SELECT * FROM call_usuario where usu_id=".$usuid."";
$lista_user = mysql_query($query_lista_user, $conexion) or die(mysql_error());
$row_lista_user = mysql_fetch_assoc($lista_user);
$totalRows_lista_user = mysql_num_rows($lista_user);
//estad
mysql_select_db($database_conexion, $conexion);
$query_mos_est = "SELECT * FROM call_estado order by est_nombre asc";
$mos_est = mysql_query($query_mos_est, $conexion) or die(mysql_error());
$row_mos_est = mysql_fetch_assoc($mos_est);
$totalRows_mos_est = mysql_num_rows($mos_est);
//detalle registro
/*$colname_mos_registro = "-1";
if (isset($_GET['id'])) {
  $colname_mos_registro = $_GET['id'];
}
mysql_select_db($database_conexion, $conexion);
$query_mos_registro = "SELECT reg_id,reg_codigo,reg_apellidos,reg_nombres,DATE_FORMAT(reg_fecha,'%e/%m/%Y')as reg_fecha,r.est_id,r.cur_id,DATE_FORMAT(reg_fechareg,'%e/%m/%Y') as reg_fechareg, usu_id,  CONCAT(IFNULL(reg_apellidos,''),' ',IFNULL(reg_nombres,'')) AS cliente,reg_formacion, reg_observaciones,reg_ciudad,reg_pais,reg_email,reg_telefono,e.est_color,c.pro_descripcion 
FROM call_registro r INNER JOIN call_estado e ON r.est_id = e.est_id
INNER JOIN producto c ON r.cur_id = c.pro_id WHERE r.reg_id =". $colname_mos_registro."";
$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
$totalRows_mos_registro = mysql_num_rows($mos_registro);*/
//

if (isset($_POST['buscar'])) {
 
  mysql_select_db($database_conexion, $conexion);
  $query_mos_pd = "select cli_id,nro_doc,razon_social from cliente where nro_doc =" .$_POST['buscar']."";
  $mos_pd = mysql_query($query_mos_pd, $conexion) or die(mysql_error());
  $row_mos_pd = mysql_fetch_assoc($mos_pd);
  $totalRows_mos_pd = mysql_num_rows($mos_pd);
/*
mysql_select_db($database_conexion, $conexion);
$query_mos_registro = "select * from cliente where nro_doc =" .$_POST['buscar']."";
$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
$totalRows_mos_registro = mysql_num_rows($mos_registro);*/
}
//

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$est = $_POST['est_id'];
	if ($est == "")
	$est = 1;
	else
	$est = $est;
  $updateSQL = sprintf("UPDATE call_registro SET est_id=%s, cur_id=%s, reg_apellidos=%s, reg_nombres=%s, reg_formacion=%s, reg_observaciones=%s,usu_id =%s, reg_pais=%s, reg_email=%s, reg_telefono=%s WHERE reg_id=%s",
                       GetSQLValueString($est, "int"),
                       GetSQLValueString($_POST['cur_id'], "int"),
                       GetSQLValueString($_POST['reg_apellidos'], "text"),
                       GetSQLValueString($_POST['reg_nombres'], "text"),
                       GetSQLValueString($_POST['reg_formacion'], "text"),
                       GetSQLValueString($_POST['reg_observaciones'], "text"),
                       GetSQLValueString($_POST['usu_id'], "int"),
                       GetSQLValueString($_POST['reg_pais'], "text"),
                       GetSQLValueString($_POST['reg_email'], "text"),
                       GetSQLValueString($_POST['reg_telefono'], "text"),
                       GetSQLValueString($_POST['reg_id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
 //
  $m = 'Registro Actualizado Correctamente';
  //
  $updateGoTo = "index.php?n=$m";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
if(@$_POST['imbox'])
{
  //config
mysql_select_db($database_conexion, $conexion);
$query_mos_config = "SELECT * FROM call_config ORDER BY conf_id desc limit 1";
$mos_config = mysql_query($query_mos_config, $conexion) or die(mysql_error());
$row_mos_config = mysql_fetch_assoc($mos_config);
$totalRows_mos_config = mysql_num_rows($mos_config);
		  //mail
/*$url = $row_mos_config['conf_url']; //http://reinademipromo.com'
$correo = $row_mos_config['conf_correo'];//'informes@reinademipromo.com'
	
	require('class.phpmailer.php');
	
	$mail = new PHPMailer();
	$mail->Host = "localhost";
	$mail->From = $correo;
	$mail->FromName = $_POST['remitente'];//
	$mail->Subject = $_POST['asunto'];//asunto
	
	$mail->AddAddress($_POST['re_email']);
	$content="<table width=614 height=584 border=0 align=center cellpadding=0 cellspacing=0>
	     <tr>
		      <td>".$_POST['imbox']."</td>
	     </tr>
	  
	     <tr>
	       <td height=35><div align=center class=home_masvistos_campos>Para fijar este email correctamente, a&ntilde;ade <a href=mailto:".$correo." class=monthlink>".$correo."</a> a tus contactos</div></td>
	     </tr>
		</td>
	  </tr>
	</table>";

			$mail->MsgHTML($content);
			
			if(!$mail->Send()) {
			
			} else {
			
			}*/
      ////Buscar cliente
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
                       
                       <?php require('menu.php'); ?>

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i> <?php echo $row_mos_usuario['usu_nombre'];?>
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
                        </li>
                        <li>
                        <div class="row" style="margin:10px 0px 10px 0px;">
                            <div class="col-md-12 text-center">
                            	<button class="btn btn-success btn-sm" disabled>
                            	<i class="fa fa-cloud-download"></i> Descargar EXCEL</button>
                            </div>
                        </div>
                        <div class="row" style="margin:0px 0px 10px 0px;">   
                            <div class="col-md-12 text-center">
                            	<button class="btn btn-danger btn-sm" disabled>
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
                
<div class="row">
                
                <div class="col-md-12">
                
                <div class="box box-primary">
                                
                   
                  <div class="box-header"> 
                  <h3 class="box-title"><?php echo $row_mos_registro['cliente'].' ! ' .$row_mos_registro['reg_codigo'];?></h3>
                  </div>
                   
                  <div class="box-body">
                  		
                   
                               <div class="row" style="margin-bottom:10px;">
                    <div class="col-sm-3 search-form">
                    <legend>
                            <form name="formb" id="formb" action="" method="post" class="text-right">
                                  <div class="input-group">                            
                                     <input type="text" name="buscar" class="form-control" placeholder="Buscar Cliente por DNI / RUC ...">
                                 <div class="input-group-btn">
                                 <button type="submit" name="q" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                 </div>
                        </div>                                                     
                        </form>
                        </legend>
                        </div>
</div>

      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                    
        <div class="row">        
                             
          <div class="col-md-9 col-sm-9">
              <!--Id cliente-->
               <input type="hidden" name="cli_id" value="<?php echo htmlentities(@$row_mos_registro['cli_id'] , ENT_COMPAT, 'UTF-8'); ?>" class="form-control">

                 <div class="row">	
                    <div class="col-md-3">
                     <small class="badge pull-left bg-<?php echo $row_mos_registro['est_color'];?>"> 
                        <?php echo $row_mos_registro['reg_fecha']; ?>
                     </small>
                    </div>
                    <div class="col-md-3">
                            <strong><?php echo $row_mos_registro['reg_telefono'];?></strong>
                    </div>
                    <div class="col-md-3">
                     <a href="mailto:<?php echo $row_mos_registro['reg_email'];?>">
                        <?php echo $row_mos_registro['reg_email'];?>
                     </a>
                    </div>
                 </div>  <!-- -->
                 
             <div class="row" style="margin-top:10px;">	
                 <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                                      <div class="input-group-addon">
                                         <i class="fa fa-briefcase"></i>
                                      </div>
    <input type="text" name="curso" class="form-control" value="<?php echo $row_mos_registro['pro_descripcion'];?>" disabled>
                        </div>
                     </div>   
                 </div> 
                 <div class="col-md-12">
                 <div class="form-group">Observaciones:
                 <textarea class="form-control" name="reg_observaciones" cols="50" rows="4">
                 
                 </textarea> 
                             </div>
                 </div>
             </div>
             
          </div><!--end colum-->
          
          <div class="col-md-3 col-sm-3">
          		 <div class="form-group">
                 <a class="btn btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-envelope"></i> Correo</a>
                 </div>
                 <!--Estado:-->
                                <label>
                                
                                <?php do { ?>
                                 <div class="radio">
                                <label>  
                                <input type="radio" name="est_id" value="<?php echo $row_mos_est['est_id'];?>" <?php if (!(strcmp(htmlentities($row_mos_registro['est_id'], ENT_COMPAT, 'UTF-8'),$row_mos_est['est_id']))) {echo "checked=\"checked\"";} ?>>
                                 <?php echo $row_mos_est['est_nombre'];?>
                                 </label>
                                 </div>
                                <?php } while ($row_mos_est = mysql_fetch_assoc($mos_est)); ?>                              
                   </div>  <!--end colum3-->       
        </div><!--end row-->
      <div class="row">
      	<div class="col-lg-9 col-md-9">
        		<div class="row">
                	<div class="col-md-6">
                            <div class="form-group">Apellidos:
                            <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>
                               <input type="text" name="reg_apellidos" value="<?php echo htmlentities($row_mos_registro['reg_apellidos'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             	</div>
                             </div>
                    </div>

                	<div class="col-md-6">          
                              <div class="form-group">Nombres:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-user"></i>
                                  </div>
                               <input type="text" name="reg_nombres" value="<?php echo htmlentities($row_mos_registro['reg_nombres'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             	</div>
                             </div>
                    </div>
                 </div><!--edn ro -->   
                 <div class="row">
                	<div class="col-md-6"> 
                            
                             <div class="form-group">Producto:
                             <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-briefcase"></i>
                                  </div>
                                <select name="cur_id" class="form-control">
                                  <?php 
do {  
?>
                                  <option value="<?php echo $row_mos_curso['pro_id']?>" <?php if (!(strcmp($row_mos_curso['pro_id'], htmlentities($row_mos_registro['cur_id'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_mos_curso['pro_descripcion']?></option>
                                  <?php
} while ($row_mos_curso = mysql_fetch_assoc($mos_curso));
?>
                                </select>
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
                                  <option value="<?php echo $row_lista_user['usu_id']?>" <?php if (!(strcmp($row_lista_user['usu_id'], htmlentities($row_mos_registro['usu_id'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_lista_user['usu_nombre']?></option>
                                  <?php
} while ($row_lista_user = mysql_fetch_assoc($lista_user));
?>
                                </select>
                                </div>
                             </div>
                     </div>
                     
                  </div>
                	<div class="row">
                    <div class="col-md-6">        
                              <div class="form-group">DNI / RUC:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-archive"></i>
                                  </div>
                               <input type="text" name="reg_formacion" value="<?php echo htmlentities($row_mos_registro['reg_formacion'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             </div></div>
                    </div>
                
                
                
                	<div class="col-md-6">         
                              <div class="form-group">Pais:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-flag"></i>
                                  </div>
                               <input type="text" name="reg_pais" value="<?php echo htmlentities($row_mos_registro['reg_pais'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             </div></div>
                    </div>
                 </div>
                

                 <div class="row">
                	   <div class="col-md-6">
                              <div class="form-group">Email:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                               <input type="email" name="reg_email" value="<?php echo htmlentities($row_mos_registro['reg_email'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             </div>
                             </div>
                     </div>

                	<div class="col-md-6">
                              <div class="form-group">Teléfono:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-phone-square"></i>
                                  </div>
                               <input type="text" id="reg_telefono" name="reg_telefono" value="<?php echo htmlentities($row_mos_registro['reg_telefono'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control">
                             </div>
                             </div>
                     </div>
                </div>             
	</div>
</div>
                </div><!-- body -->
                <div class="box-footer">
          
              <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
              <a href="index.php" style="margin-left:10px;">
                 <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></a>
				 <input type="hidden" name="MM_update" value="form1">
                 <input type="hidden" name="reg_id" value="<?php echo $row_mos_registro['reg_id']; ?>">
                          </form>
                </div>
    </div><!-- /.primary-->
          </div><!-- /.col-->
    </div> <!-- /.row -->
    <!--- MODAL-->
    <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
<div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="modal-title"><i class="fa fa-envelope"></i> Enviar Correo</h4>
                  </div>
    <form method="post" name="formenviar" action="">
          <div class="modal-body">
          		 <div class="row">
                <div class="col-md-12">
                              <div class="form-group">Remite:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                               <input type="text" name="remitente" value="" class="form-control" >
                             </div>
                             </div>                            


                             <div class="form-group">Asunto:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                               <input type="text" name="asunto" value="" class="form-control" >
                             </div>
                             </div>

                              <div class="form-group">Email:
                              <div class="input-group">
                                  <div class="input-group-addon">
                                     <i class="fa fa-envelope"></i>
                                  </div>
                               <input type="email" name="re_email" value="<?php echo htmlentities($row_mos_registro['reg_email'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control" >
                             </div>
                             </div>
                </div></div>
                <div class="row">
                 	<div class="col-md-12"> 
                      <div class="form-group">  	
                       <textarea class="form-control" name="imbox" cols="50" rows="7" required placeholder="Mensaje ..."></textarea> 	
           			  </div>
                	</div>
                </div>
           </div><!-- end body-->
           <div class="modal-footer clearfix">

              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
 			  
              <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-envelope-o"></i> Enviar</button>
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
                    //$('#reg_telefono').validCampo('0123456789');
                   
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
mysql_free_result($mos_usuario);
mysql_free_result($mos_registro);
mysql_free_result($mos_curso);
mysql_free_result($mos_est);
?>
