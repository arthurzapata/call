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
if ($perid == 3)//super admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre,e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id GROUP BY  e.est_nombre";
elseif ($perid == 1)//admin
$query_mos_estado = "SELECT  e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.emp_id = ".$empid." GROUP BY e.est_nombre";
else
$query_mos_estado = "SELECT e.est_id,  r.emp_id,  COUNT(r.est_id) AS cant,  e.est_nombre, e.est_color FROM  call_registro r RIGHT JOIN call_estado e ON r.est_id = e.est_id WHERE  r.usu_id = ".$usuid." GROUP BY e.est_nombre";
$mos_estado = mysql_query($query_mos_estado, $conexion) or die(mysql_error());
$row_mos_estado = mysql_fetch_assoc($mos_estado);
//timezone
date_default_timezone_set('America/Lima');
$fechaactual = date('Y-m-d');
//
mysql_select_db($database_conexion, $conexion);
//super
if ($perid == 3)//super admin
{$query_mos_user = "select com_id,date_format(com_fechareg, '%d-%m-%Y') as com_fechareg,u.usu_nombre,com_comentario,e.emp_nombre, date_format(com_fechasis, '%H:%i:%s') as com_fechasis, c.usu_id from call_comentario c INNER JOIN call_usuario u on c.usu_id = u.usu_id INNER JOIN call_empresa e on c.emp_id = e.emp_id where com_fechareg between '".$fechaactual."' and '".$fechaactual."' order by com_id desc";}
else //admin
{$query_mos_user = "select com_id,date_format(com_fechareg, '%d-%m-%Y') as com_fechareg,u.usu_nombre,com_comentario,e.emp_nombre, date_format(com_fechasis, '%H:%i:%s') as com_fechasis, c.usu_id from call_comentario c INNER JOIN call_usuario u on c.usu_id = u.usu_id INNER JOIN call_empresa e on c.emp_id = e.emp_id where com_fechareg between '".$fechaactual."' and '".$fechaactual."' and c.emp_id=".$empid." order by com_id desc";}
$mos_user = mysql_query($query_mos_user, $conexion) or die(mysql_error());
$row_mos_user = mysql_fetch_assoc($mos_user);
$totalRows_mos_user = mysql_num_rows($mos_user);
   
if(isset($_POST['usu_id']))
{
	$from = $_POST['from'];	//desd
	$to = $_POST['to'];	//hasta
	$fromconver = implode('-',array_reverse(explode('-', $from)));
	$toconver = implode('-',array_reverse(explode('-', $to)));
	
	$em = $_POST['emp_id'];
	$us = $_POST['usu_id'];
	
	if($em == 0)  $busem = "like '%%'";	else $busem = "=".$em;
	if($us == 0)  $busus = "like '%%'";	else $busus = "=".$us;
	
	mysql_select_db($database_conexion, $conexion);
	$query_mos_user = "select com_id,date_format(com_fechareg, '%d-%m-%Y') as com_fechareg,u.usu_nombre,com_comentario,e.emp_nombre, date_format(com_fechasis, '%H:%i:%s') as com_fechasis, c.usu_id from call_comentario c INNER JOIN call_usuario u on c.usu_id = u.usu_id INNER JOIN call_empresa e on c.emp_id = e.emp_id where (com_fechareg between '".$fromconver."' and '".$toconver."') and c.emp_id ".$busem." and c.usu_id ".$busus." order by com_id desc";
	$mos_user = mysql_query($query_mos_user, $conexion) or die(mysql_error());
	$row_mos_user = mysql_fetch_assoc($mos_user);
	$totalRows_mos_user = mysql_num_rows($mos_user);
	/*echo $query_mos_user;*/
}
if(isset($_POST['casilla']))
{
$checkbox2 = isset($_POST['casilla']) ? $_POST['casilla'] : NULL;
for ($i=0;$i<sizeof($checkbox2);$i++)
{
		//update
		$query2="delete from call_comentario WHERE com_id = '".$checkbox2[$i]."'";
  		$Result1 = mysql_query($query2, $conexion) or die(mysql_error());
}
	//
  $m = 'Registros Eliminados Correctamente';
  //
  $insertGoTo = "coments.php?n=$m";
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
                       
                       <?php require_once('menu.php'); ?>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>  <?php echo $row_mos_usuario['usu_nombre'];?>
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
                        <!--<div class="row" style="margin:10px 0px 10px 0px;">
                            <div class="col-md-12 text-center">
                            	<button class="btn btn-success btn-sm" onclick="window.location='excoments.php'">
                            	<i class="fa fa-cloud-download"></i> Descargar EXCEL</button>
                            </div>
                        </div>-->  
                        <form action="excoments.php" method="post" target="_blank" id="FormularioExportacion">
							<div class="row" style="margin:10px 0px 10px 0px;">
                           		 <div class="col-md-12 text-center">
                            		<!--<button class="btn btn-success btn-sm">-->
                                    <button class="excelclas btn btn-success btn-sm">
                            		<i class="fa fa-cloud-download"></i> Descargar EXCEL</button>
                           		 </div>
                       		</div>
							<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
						</form>
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
                <?php echo $msj;?>
                
<div class="row">
                
                <div class="col-md-12">
                
                <div class="box box-primary">
                                
                   
                  <div class="box-header"> <h3 class="box-title"><i class="fa fa-comment"></i> Comentarios</h3></div>
                   
                  <div class="box-body">
                  	<!--<div class="row" style="margin-bottom:10px;">--> 
            <form id="formb" name="formb" action="" method="post">        	
            <?php if ($perid != 2) { ?>
            <div class="col-md-3">
            	  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control" name="from" id="from" placeholder="dd-mm-yyyy">
                    </div><!-- /.input group -->
                  </div>
            </div>
            
            <div class="col-md-3">
            	  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control" name="to" id="to" placeholder="dd-mm-yyyy">
                    </div><!-- /.input group -->
                  </div>
            </div>
            <div class="col-md-3">
            <div class="form-group">
            <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-align-center mailbox"></i>
                      </div>
         <select name="emp_id" id="emp_id" class="form-control">
			<?php
            mysql_select_db($database_conexion, $conexion);
			if ($perid == 3)//super admin
            	$query_mos_emp = "SELECT emp_id,emp_nombre FROM call_empresa";
			else
				$query_mos_emp = "SELECT emp_id,emp_nombre FROM call_empresa where emp_id=".$empid."";
            $mos_emp = mysql_query($query_mos_emp, $conexion) or die(mysql_error());
            $row_mos_emp = mysql_fetch_assoc($mos_emp);
 			echo '<option value="0"> -- Todos --</option>';           
			do
            { 
                echo '<option value="'.$row_mos_emp['emp_id'].'">'.$row_mos_emp['emp_nombre'].'</option>';
            } while ($row_mos_emp = mysql_fetch_assoc($mos_emp));              
            ?>
		</select></div><!-- /.input group -->
        </div></div>
        <div class="col-md-3">
        
        <div class="form-group">
        	<div class="input-group">
            <div class="input-group-addon">
            	<i class="fa fa-user"></i>
            </div>
           <select id="usu_id" name="usu_id" class="form-control">
          	<option value="0"> -- Todos --</option>
          </select> 	
          <div class="input-group-btn">
               <button type="submit" name="bu" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
          </div></div>
          </div>
        </form>
       <!-- <div class="col-md-3">-->
        
      <!--  </div>-->
      <?php } ?>
                   </div>   
                   <div class="row" style="margin-bottom:5px;"></div>  
               		<div class="box-body table-responsive no-padding">
                                       
   <?php if ($totalRows_mos_user !== 0) { ?>

 <form name="frm" action="" method="post">
  		<table id="Exportar_a_Excel" class="table table-bordered table-striped table-hover table-condensed tablesorter">
		<tr>
        	<td><?php if ($perid != 2) { // Show if recordset empty ?>
                <button class="btn btn-primary btn-xs" onclick="return confirm('¿Seguro que desea eliminar?')" type="submit" data-toggle="tooltip" data-title="Eliminar"><i class="fa fa-trash-o"></i></button>
              <?php } // Show if recordset empty ?></td>
    	    <td><strong><i class="fa fa-calendar"></i> Fecha</strong></td>
            <td><strong><i class="fa fa-clock-o"></i> Hora</strong></td>
            <td><strong><i class="fa fa-align-center mailbox"></i> Empresa</strong></td>
    	    <td><strong><i class="fa fa-user"></i> Asesor</strong></td>
    	    <td><strong><i class="fa fa-comment"></i> Comentario</strong></td>
            
  	    </tr>
    	  <?php do { 
		   if($row_mos_user['usu_id']==$usuid) $clase = 'alert-info'; else $clase = '';
		  ?>
    	    <tr class="<?php echo $clase;?>">
              <td><?php if ($perid != 2) { ?>
         	 <input name="casilla[]" type="checkbox" id="casilla[]" value="<?php echo $row_mos_user['com_id']; ?>">
          	<?php } ?></td>
    	      <td><?php echo $row_mos_user['com_fechareg']; ?></td>
              <td><?php echo $row_mos_user['com_fechasis']; ?></td>
              <td><?php echo $row_mos_user['emp_nombre']; ?></td>
    	      <td><?php echo $row_mos_user['usu_nombre']; ?></td>
    	      <td><?php echo $row_mos_user['com_comentario']; ?></td>
    	     
  	      </tr>
    	    <?php } while ($row_mos_user = mysql_fetch_assoc($mos_user)); ?>
  	  <tr><td colspan="8">
      <div class="row">
        
<div class="col-md-9"><?php if ($perid != 2) { ?>
           <a href="javascript:seleccionar_todo()">Marcar Todos</a> / <a href="javascript:deseleccionar_todo()">Desmarcar Todos</a><?php } ?>
            </div>

            <div class="col-md-3 text-right">
       			<?php echo $totalRows_mos_user; ?>  Registros
      		</div>
       </div>
            
        </td>
      </tr>
  </table>
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
        <!-- Morris.js charts 
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="js/plugins/morris/morris.min.js" type="text/javascript"></script>-->
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
   <!-- Jquery UI
   <link rel="stylesheet" type="text/css" href="jquery/css/jquery-ui.css">-->
    <link href="css/flick/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
   
   <script language="javascript">
	$(document).ready(function() {
	$(".excelclas").click(function(event) {
	$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
	$("#FormularioExportacion").submit();
	});
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
	<script type="text/javascript">
      $(function() {
        $( "#from" ).datepicker({
          defaultDate: "",
          changeMonth: true, 
          numberOfMonths: 1,dateFormat: "dd-mm-yy",
          dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
          }
        });
        $( "#to" ).datepicker({
          defaultDate: "",
          changeMonth: true,
          numberOfMonths: 1,dateFormat: "dd-mm-yy",
          dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
          }
        });
      });
    </script>
           
   <script language="javascript">
    $(document).ready(function(){
       $("#emp_id").change(function () {
               $("#emp_id option:selected").each(function () {
                emp_id = $(this).val();
                $.post("users1.php", { emp_id: emp_id }, function(data){
                    $("#usu_id").html(data);
                });            
            });
       })
    });
	</script>
</body>
</html>
<?php
mysql_free_result($mos_user);
?>
