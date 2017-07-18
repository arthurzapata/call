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
$from = ''; //desd
$to = ''; //hasta

$maxRows_mos_curso = 15;
$pageNum_mos_curso = 0;
if (isset($_GET['pageNum_mos_curso'])) {
  $pageNum_mos_curso = $_GET['pageNum_mos_curso'];
}
$startRow_mos_curso = $pageNum_mos_curso * $maxRows_mos_curso;

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT cn_serie,cn_numero,tipo_doc,nro_pedido,c.nro_doc,c.razon_social,cc_vta,cc_moneda,d.fecha_reg,u.usu_nombre, total,fecha_ped,estado FROM documento d left join cliente c on d.cc_cliente =c.cli_id 
left join call_usuario u on d.cc_vendedor = u.usu_id order by cn_numero desc";
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

if (isset($_POST['from'])) {

      $from = $_POST['from']; //desd
    $to = $_POST['to']; //hasta
    $fromconver = implode('-',array_reverse(explode('-', $from)));
    $toconver = implode('-',array_reverse(explode('-', $to)));

    mysql_select_db($database_conexion, $conexion);
  $query_mos_curso = "SELECT cn_serie,cn_numero,tipo_doc,nro_pedido,c.nro_doc,c.razon_social,cc_vta,cc_moneda,d.fecha_reg,u.usu_nombre, total,fecha_ped,estado FROM documento d left join cliente c on d.cc_cliente =c.cli_id 
left join call_usuario u on d.cc_vendedor = u.usu_id where fecha_ped between '". $fromconver."' and '". $toconver ."' order by d.fecha_reg desc";
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
                            <input type="text" id="q" name="q" class="form-control" placeholder="Buscar por DNI ..." maxlength="8"/>
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
                                
                  <div class="box-header"> <h3 class="box-title">Comprobantes </h3></div>
                   
                  <div class="box-body">
                    <div class="row" style="margin-bottom:10px;">
                      <div class="col-sm-6">
                          <a class="btn btn-primary" href="documento_new.php"><i class="fa fa-pencil"></i> Agregar</a>
            </div>
                    <div class="col-sm-6 search-form">
                            <form id="formb" name="formb" action="" method="post">          
                                   <div class="row"> 
                                    <div class="col-md-4">
                                          <div class="form-group">
                                            <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input type="text" class="form-control" value="<?php echo $from; ?>" name="from" id="from" placeholder="dd-mm-yyyy">
                                            </div><!-- /.input group -->
                                          </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                          <div class="form-group">
                                            <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input type="text" class="form-control" value="<?php echo $to; ?>" name="to" id="to" placeholder="dd-mm-yyyy">
                                            </div><!-- /.input group -->
                                          </div>
                                    </div>

                                    <div class="col-md-3">
                                    
                                    <div class="form-group">
                                
                                
                                      <div class="input-group-btn">
                                           <button type="submit" name="bu" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                      </div></div>
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
      <td><strong>Documento</strong></td>
      <td><strong>Tipo Doc</strong></td>
      <td><strong>Pedido</strong></td>
      <td><strong>DNI / RUC</strong></td>
      <td><strong>Nombre / Razon Social</strong></td>
      <td><strong>Vendedor</strong></td>
      <td><strong>Fecha</strong></td>
      <td align="center"><strong >Total</strong></td>
      <td align="center"><strong >Estado</strong></td>
     <td colspan="2" align="center"><strong>Acciones</strong></td>
     <td align="center"><strong> <element>Documento</element></strong></td>
    
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_mos_curso['cn_serie'].' - '.$row_mos_curso['cn_numero']; ?></td>
        <td><?php echo $row_mos_curso['tipo_doc'] == '01'?  'FACTURA' : 'BOLETA'; ?></td>
        <td><?php echo $row_mos_curso['nro_pedido'];?></td>
        <td><?php echo $row_mos_curso['nro_doc'];?>
        <td><?php echo $row_mos_curso['razon_social']; ?></td>
        <td><?php echo $row_mos_curso['usu_nombre'];?></td>
        <td><?php echo $row_mos_curso['fecha_ped'];?></td>
        <td align="right"><?php echo $row_mos_curso['total']; ?></td>
        <td align="right"><?php echo $row_mos_curso['estado']== 1 ? 'EMITIDO' : 'ANULADO'; ?></td>
           <td><div align="center"><a href="documento_edit.php?ser=<?php echo $row_mos_curso['cn_serie'].'&nro='.$row_mos_curso['cn_numero']; ?>" title="Editar" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Editar"><i class="fa fa-edit"></i></button></a></div></td>
        <td><div align="center"><a onclick="return confirm('¿Seguro que desea anular?')" href="doc_delete.php?ser=<?php echo $row_mos_curso['cn_serie'].'&nro='.$row_mos_curso['cn_numero']; ?>" title="Anular" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Anular"><i class="fa fa-trash-o"></i></button></a></div></td>

     <td><div align="center"><a href="documento.php?ser=<?php echo $row_mos_curso['cn_serie'].'&nro='.$row_mos_curso['cn_numero']; ?>" title="Ver Documento" class="hide-option"><button class="btn btn-success btn-xs" type="button" data-toggle="tooltip" data-title="Ver Documento"><i class="fa fa-file"></i></button></a></div></td>
     <!--<td><div align="center"><a onclick="return confirm('¿Seguro que desea eliminar?')" href="producto_delete.php?id=<?php echo $row_mos_curso['pro_id']; ?>" title="Eliminar" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Eliminar"><i class="fa fa-trash-o"></i></button></a></div></td>-->
      </tr>
      <?php } while ($row_mos_curso = mysql_fetch_assoc($mos_curso)); ?>

    <tr>
      <td colspan="12">
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
                  </div>
                </div>
<!---->

 </div><!-- /.primary-->
          </div><!-- /.col-->
    </div> <!-- /.row -->
   <script src="js/jquery.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.10.3 -->
   
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
       
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>        

    <link href="css/flick/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
  <script src="js/jquery-ui-1.10.4.custom.js"></script>
           
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
</body>
</html>

<!--
<script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Reclamos', 'Reclamos'],
                                  <%= ports %>
                             /* ['2013',  1000],
                              ['2014',  1170],
                              ['2015',  660],
                              ['2016',  1030]*/
                            ]);

                            var options = {
                               // title: 'Company Performance',
                                hAxis: {title: 'Sector',  titleTextStyle: {color: '#333'}},
                                vAxis: {minValue: 0}
                            };

                            var chart = new google.visualization.AreaChart(document.getElementById('chart_divv'));
                            chart.draw(data, options);
                      }
                    </script>

                   <div id="chart_divv" style="height: 500px;"></div>-->