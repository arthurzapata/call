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
elseif ($perid == 1)//admin
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
elseif ($perid == 1)//admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.emp_id = ".$empid." GROUP BY e.est_nombre";
else
$query_mos_estado = "SELECT e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.usu_id = ".$usuid." GROUP BY e.est_nombre";
$mos_estado = mysql_query($query_mos_estado, $conexion) or die(mysql_error());
$row_mos_estado = mysql_fetch_assoc($mos_estado);
?>
<?php ///////////////////////////////////////////////////
if (isset($_POST['tipo']))
{
	$tipo = $_POST['tipo'];
	$checkbox2 = isset($_POST['casilla']) ? $_POST['casilla'] : NULL;
	
	$_SESSION['tipo'] = $tipo;
	
	$stringofnumber=implode("|",$checkbox2); 

	$_SESSION['string']= $stringofnumber; 	
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$checkbox2 = explode("|", $_SESSION['string']); 
$tipo =	$_SESSION['tipo'];
  for ($i=0;$i<sizeof($checkbox2);$i++)
	{
	//elimina
		mysql_select_db($database_conexion, $conexion);
		$query2="DELETE FROM call_registro WHERE reg_id = '".$checkbox2[$i]."'";
  		$Result1 = mysql_query($query2, $conexion) or die(mysql_error());
 		$insertGoTo = "index.php";
  		if (isset($_SERVER['QUERY_STRING'])) 
		{
   	 	$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    	$insertGoTo .= $_SERVER['QUERY_STRING'];
		header(sprintf("Location: %s", $insertGoTo));
		}
	}
 	//
	
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form4")) {
  $updateSQL = sprintf("UPDATE call_usuario SET usu_nombre=%s WHERE usu_id=%s",
                       GetSQLValueString($_POST['usu_nombre'], "text"),
                       GetSQLValueString($_POST['usu_id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
                       <li><a href="importar.php">Importar</a></li>
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
                  
                        <li> 
                          <a href="categoria.php?es=1"><span>Pendientes de Contacto</span> 
                            <small class="badge pull-right bg-primary"><?php echo $row_mos_estado1['uno'];?> </small>
                          </a>
                        </li>
                        <li> 
                          <a href="categoria.php?es=2"><span>Pendientes envio de documentos</span> 
                            <small class="badge pull-right bg-red"><?php echo $row_mos_estado2['dos'];?> </small>
                          </a>
                        </li>
						            <li> 
                          <a href="categoria.php?es=3"><span>En evaluación</span> 
                            <small class="badge pull-right bg-yellow"><?php echo $row_mos_estado3['tres'];?> </small>
                          </a>
                        </li>
<li> 
                          <a href="categoria.php?es=4"><span>Pendientes reserva de matricula</span> 
                            <small class="badge pull-right bg-light-blue"><?php echo $row_mos_estado4['cuatro'];?> </small>
                          </a>
                        </li><li> 
                          <a href="categoria.php?es=5"><span>Matriculados</span> 
                            <small class="badge pull-right bg-green"><?php echo $row_mos_estado5['cinco'];?> </small>
                          </a>
                        </li><li> 
                          <a href="categoria.php?es=6"><span>Dados de baja</span> 
                            <small class="badge pull-right bg-maroon"><?php echo $row_mos_estado6['seis'];?> </small>
                          </a>
                        </li>

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
                
<div class="row">
<div class="col-lg-12">
<div class="box box-primary">

	
<?php if ($tipo == 1) 
{
	$title = 'Eliminar Registros';
?>

	<div class="box-header"> <h3 class="box-title"><?php echo $title;?></h3></div>
        <div class="box-body">
           <form method="post" name="form3" action="<?php echo $editFormAction; ?>">
                  
                  Desea eliminar ? <a href="index.php" class="btn btn-danger"><i class="fa fa-times"></i> No</a>
                 
                  <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Si</button> 
                  <input type="hidden" name="MM_insert" value="form3">
                  <input type="hidden" name="tipo" value="<?php echo $tipo;?>">
          </form>

	 	</div>

</form>
<?php 
}
elseif($tipo == 2) {
echo 'Asignar';?>
    <form method="post" name="form4" action="<?php echo $editFormAction; ?>">
        <select name="users" class="form-control">
          <?php
        do {  
        ?>
          <option value="<?php echo $row_lista_user['usu_id']?>"><?php echo $row_lista_user['usu_nombre']?></option>
          <?php
        } while ($row_lista_user = mysql_fetch_assoc($lista_user));
          $rows = mysql_num_rows($mos_usuario);
          if($rows > 0) {
              mysql_data_seek($mos_usuario, 0);
              $row_lista_user = mysql_fetch_assoc($lista_user);
          }
        ?>
        </select>
      	<input type="hidden" name="MM_update" value="form4">
      
    </form>
<?php
}
 else 
 echo 'Debes seleccionar una opcion valida <h1><h1><h1><h1></h1></h1></h1></h1>'
 ?>
 				<div class="box-footer">
		        </div>
         </div><!-- body -->
    </div><!-- /.primary-->
    </div><!-- /.col-->
  </div> <!-- /.row -->

</section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

    </body>
</html>
<?php
mysql_free_result($mos_usuario);
?>
