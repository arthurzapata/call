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
elseif ($perid == 1)//admin
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro where emp_id = ".$empid."";
else
$query_mos_vertodos = "SELECT COUNT(*) AS cant FROM call_registro where usu_id = ".$usuid."";
$mos_vertodos = mysql_query($query_mos_vertodos, $conexion) or die(mysql_error());
$row_mos_vertodos = mysql_fetch_assoc($mos_vertodos);
$totalRows_mos_vertodos = mysql_num_rows($mos_vertodos);
//estados
mysql_select_db($database_conexion, $conexion);
if ($perid == 3)//super adtain
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre,e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id GROUP BY  e.est_nombre";
elseif ($perid == 1)//admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.emp_id = ".$empid." GROUP BY e.est_nombre";
else
$query_mos_estado = "SELECT e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.usu_id = ".$usuid." GROUP BY e.est_nombre";
$mos_estado = mysql_query($query_mos_estado, $conexion) or die(mysql_error());
$row_mos_estado = mysql_fetch_assoc($mos_estado);
//curso
$maxRows_mos_curso = 15;
$pageNum_mos_curso = 0;
if (isset($_GET['pageNum_mos_curso'])) {
  $pageNum_mos_curso = $_GET['pageNum_mos_curso'];
}
$startRow_mos_curso = $pageNum_mos_curso * $maxRows_mos_curso;

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT * FROM cliente";
$query_limit_mos_curso = sprintf("%s LIMIT %d, %d", $query_mos_curso, $startRow_mos_curso, $maxRows_mos_curso);
$mos_curso = mysql_query($query_limit_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);

if (isset($_GET['totalRows_mos_curso'])) {
  $totalRows_mos_curso = $_GET['totalRows_mos_curso'];
} else {
  $all_mos_curso = mysql_query($query_mos_curso);
  $totalRows_mos_curso = mysql_num_rows($all_mos_curso);
}
$totalPages_mos_curso = ceil($totalRows_mos_curso/$maxRows_mos_curso)-1;

if (isset($_POST['buscar'])) {
  	mysql_select_db($database_conexion, $conexion);
	$query_mos_curso = "SELECT * FROM cliente where razon_social like '%".$_POST['buscar']."%'";
	$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
	$row_mos_curso = mysql_fetch_assoc($mos_curso);
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
                       <!-- <li><i class="fa fa-user"></i>  </li>
                       <li><a href="importar.php" class="hide-option"><i class="fa fa-upload"></i></a></li>-->
                      <?php require('menu.php');?>

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i> <?php echo $row_mos_usuario['usu_nombre'];?>
                                <span> <i class="caret"></i> </span>
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
                            <input type="text" id="q" name="q" class="form-control" placeholder="Buscar por Razón Social o Nombre ..." maxlength="8"/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button><input type="hidden" name="MM_insert" value="form1">
                            </span>
                        </div>
                    </form>-->
                    <!-- /.search form -->
                   
                </section>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
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
                                
                  <div class="box-header"> 
                  		<h3 class="box-title"><i class="fa fa-briefcase"></i> Clientes</h3></div>
                  <div class="box-body">
                  	<div class="row" style="margin-bottom:10px;">
                   		<div class="col-sm-6">
                        	<a class="btn btn-primary" href="cliente_new.php"><i class="fa fa-pencil"></i> Agregar</a>
						</div>
                		<div class="col-sm-6 search-form">
                        <form name="formb" id="formb" action="" method="post" class="text-right">
                                  <div class="input-group">                                          
                                     <input type="text" name="buscar" class="form-control" placeholder="Buscar ...">
                                 <div class="input-group-btn">
                                 <button type="submit" name="q" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                 </div>
                        </div>                                                     
                        </form>
                        </div>
                   </div>     
                		<div class="box-body table-responsive no-padding">
   <?php if ($totalRows_mos_curso !== 0) { 
						  
 // Show if recordset emptyxx ?>
  <table class="table table-bordered table-striped table-hover table-condensed tablesorter">

    <tr>
      <td><strong>Cod</strong></td>
      <td><strong>DNI / RUC</strong></td>
      <td><strong>Razón Social</strong></td> 
      <td><strong>Dirección</strong></td>
      <td><strong>Teléfono</strong></td>
      <td><strong>Email</strong></td>
      <td><strong>Fecha Registro</strong></td>
      <!--<td align="center"><strong>Activo</strong></td>-->
      <td colspan="2" align="center"><strong>Acciones</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_mos_curso['cli_id']; ?></td>
        <td><?php echo $row_mos_curso['nro_doc']; ?></td>
        <td><?php echo $row_mos_curso['razon_social']; ?></td>
        <td><?php echo $row_mos_curso['direccion']; ?></td>
        <td><?php echo $row_mos_curso['telefono']; ?></td>
        <td><?php echo $row_mos_curso['email']; ?></td>
        <td><?php echo $row_mos_curso['fecha_reg']; ?></td>
       <!--- <td><?php echo '...'; ?></td>
        <td align="center"><?php if($row_mos_curso['cur_activo']==1)
					 $estado = 'success'; else $estado= 'danger'; ?>
			<a href="up_estadoc.php?pk=<?php echo $row_mos_curso['cur_id']; ?>" title="Cambiar Estado" class="hide-option">
            <button class="btn btn-<?php echo $estado?>" type="button" data-toggle="tooltip"></button></a>
		 </td>-->
      <td><div align="center"><a href="cliente_edit.php?id=<?php echo $row_mos_curso['cli_id']; ?>" title="Editar" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Editar"><i class="fa fa-edit"></i></button></a></div></td>
        <td><div align="center"><a onclick="return confirm('¿Seguro que desea eliminar?')" href="cliente_delete.php?id=<?php echo $row_mos_curso['cli_id']; ?>" title="Eliminar" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Eliminar"><i class="fa fa-trash-o"></i></button></a></div></td>
      </tr>
      <?php } while ($row_mos_curso = mysql_fetch_assoc($mos_curso)); ?>

    <tr>
    	<td colspan="10">
        <div class="row">
        		<div class="col-md-6">
        <table>
        	<tr>
            <td>
<?php if ($pageNum_mos_curso > 0) { // Show if not first page ?>
        <a title="Primero" href="<?php printf("%s?pageNum_mos_curso=%d%s", $currentPage, 0, $queryString_mos_curso); ?>"> <button class="btn btn-default btn-sm" type="button"><i class="fa fa-step-backward"></i></button></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mos_curso > 0) { // Show if not first page ?>
        <a title="Anterior" href="<?php printf("%s?pageNum_mos_curso=%d%s", $currentPage, max(0, $pageNum_mos_curso - 1), $queryString_mos_curso); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-backward"></i></button></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mos_curso < $totalPages_mos_curso) { // Show if not last page ?>
        <a title="Siguiente" href="<?php printf("%s?pageNum_mos_curso=%d%s", $currentPage, min($totalPages_mos_curso, $pageNum_mos_curso + 1), $queryString_mos_curso); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-forward"></i></button></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_mos_curso < $totalPages_mos_curso) { // Show if not last page ?>
        <a title="Ultimo" href="<?php printf("%s?pageNum_mos_curso=%d%s", $currentPage, $totalPages_mos_curso, $queryString_mos_curso); ?>"><button class="btn btn-default btn-sm" type="button"><i class="fa fa-fast-forward"></i></button></a>
        <?php } // Show if not last page ?></td>
      <!-- prueba-->
      
      			</tr>
			</table>
            </div>
            
            <div class="col-md-6 text-right">
      Registros <?php echo ($startRow_mos_curso + 1) ?> a <?php echo min($startRow_mos_curso + $maxRows_mos_curso, $totalRows_mos_curso) ?> de <?php echo $totalRows_mos_curso ?>
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