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
/*if (isset($_GET['pageNum_mos_curso'])) {
  $pageNum_mos_curso = $_GET['pageNum_mos_curso'];
}
$startRow_mos_curso = $pageNum_mos_curso * $maxRows_mos_curso;

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT cn_serie,cn_numero,tipo_doc,nro_pedido,c.nro_doc,c.razon_social,cc_vta,cc_moneda,d.fecha_reg,u.usu_nombre, total FROM documento d left join cliente c on d.cc_cliente =c.cli_id 
left join call_usuario u on d.cc_vendedor = u.usu_id order by d.fecha_reg desc";
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
*/
$item =0;
$total=0;
/*
  $query_mos_curso = "SELECT nro_pedido,p.cli_id,c.nro_doc,c.razon_social,ped_estado,cc_vendedor,u.usu_nombre,total FROM call_pedido p inner join cliente c on p.cli_id = c.cli_id
	inner join call_usuario u on p.cc_vendedor = u.usu_id where nro_pedido = 9999";
  $mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
  */
$query_mos_pd = "SELECT nro_pedido, d.pro_id, p.pro_descripcion, cant, precio, importe FROM call_pedido_det d LEFT JOIN producto p on d.pro_id = p.pro_id
  where nro_pedido = 9999";
  $mos_pd = mysql_query($query_mos_pd, $conexion) or die(mysql_error());
  $row_mos_pd = mysql_fetch_assoc($mos_pd);
  $totalRows_mos_pd = mysql_num_rows($mos_pd);

if (isset($_POST['buscar'])) {
  mysql_select_db($database_conexion, $conexion);
  $query_mos_curso = "SELECT nro_pedido,p.cli_id,c.nro_doc,c.razon_social,ped_estado,cc_vendedor,u.usu_nombre,total FROM call_pedido p inner join cliente c on p.cli_id = c.cli_id
	inner join call_usuario u on p.cc_vendedor = u.usu_id where nro_pedido = ".$_POST['buscar']." and ped_estado=1";
  $mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
  $row_mos_curso = mysql_fetch_assoc($mos_curso);
  $totalRows_mos_curso = mysql_num_rows($mos_curso);

  if ($totalRows_mos_curso > 0) {
      $query_mos_pd = "SELECT nro_pedido, d.pro_id, p.pro_descripcion, cant, precio, importe 
      FROM call_pedido_det d LEFT JOIN producto p on d.pro_id = p.pro_id  where nro_pedido =" .$_POST['buscar']."";
      $mos_pd = mysql_query($query_mos_pd, $conexion) or die(mysql_error());
      $row_mos_pd = mysql_fetch_assoc($mos_pd);
      $totalRows_mos_pd = mysql_num_rows($mos_pd);  
  }
  
}

//vendedores
mysql_select_db($database_conexion, $conexion);
if ($perid == 2)//admin
  $query_lista_user = "SELECT usu_id, usu_nombre FROM call_usuario where usu_id=".$usuid."  and usu_activo=1";
else 
  $query_lista_user = "SELECT usu_id, usu_nombre FROM call_usuario where per_id in (2) and usu_activo=1";
$lista_user = mysql_query($query_lista_user, $conexion) or die(mysql_error());
$row_lista_user = mysql_fetch_assoc($lista_user);
$totalRows_lista_user = mysql_num_rows($lista_user);

$fechaactual = date("Y-m-d");

////// EDITAR chunguito
$colname_mos_registro = "-1";
if (isset($_GET['ser'])) {
  $ser = $_GET['ser'];
  $num = @$_GET['nro'];
}
mysql_select_db($database_conexion, $conexion);
$query_mos_registro = "select cn_serie,cn_numero,tipo_doc, nro_pedido,p.cc_cliente,cc_moneda,DATE_FORMAT(fecha_ped,'%e-%m-%Y') as fecha_ped,
c.nro_doc,c.razon_social,p.cc_vendedor,estado from documento p left join cliente c on p.cc_cliente=c.cli_id where cn_serie ='". $ser."' and cn_numero='".$num."'";
$mos_registro = mysql_query($query_mos_registro, $conexion) or die(mysql_error());
$row_mos_registro = mysql_fetch_assoc($mos_registro);
$totalRows_mos_registro = mysql_num_rows($mos_registro);

 $query_mos_pd = "SELECT nro_pedido, d.pro_id, p.pro_descripcion, cant, precio, importe FROM call_pedido_det d LEFT JOIN producto p on d.pro_id = p.pro_id
  where nro_pedido =".$row_mos_registro['nro_pedido']."";
  $mos_pd = mysql_query($query_mos_pd, $conexion) or die(mysql_error());
  $row_mos_pd = mysql_fetch_assoc($mos_pd);
  $totalRows_mos_pd = mysql_num_rows($mos_pd);

//detalle
/*$query_mos_detalle = "select * from documento_det where cn_serie ='". $ser."' and cn_numero='".$num."'";
$mos_detalle = mysql_query($query_mos_detalle, $conexion) or die(mysql_error());
$row_mos_detalle = mysql_fetch_assoc($mos_detalle);
$totalRows_mos_detalle = mysql_num_rows($mos_detalle);*/
//
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  $fecha_remitido = $_POST['fecha_ped'];
  $fechaconver = implode('-',array_reverse(explode('-', $fecha_remitido)));
  

  $updateSQL = sprintf("UPDATE documento SET cc_vendedor=%s, fecha_ped=%s where cn_serie =%s and cn_numero=%s",
              GetSQLValueString($_POST['cc_vendedor'], "int"),
              GetSQLValueString($fechaconver, "date"),
              GetSQLValueString($ser, "text"),
              GetSQLValueString($num, "text"));
  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $m = 'Registro Actualizado Correctamente';
  //
  $updateGoTo = "documentos.php?n=$m";
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
                                
                  <div class="box-header"> <h3 class="box-title">Editar Comprobante </h3></div>
                   
                  <div class="box-body">
                    <div class="row" style="margin-bottom:10px;">
                      <div class="col-sm-6">
                        <!--  <a class="btn btn-primary" href="documento_new.php"><i class="fa fa-pencil"></i> Agregar</a>-->
                      </div>
               <!--     <div class="col-sm-6 search-form">
                            <form name="formb" id="formb" action="" method="post" class="text-right">
                                  <div class="input-group">                                          
                                     <input type="text" name="buscar" class="form-control" placeholder="Ingresar N° Pedido ...">
                                 <div class="input-group-btn">
                                 <button type="submit" name="q" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                 </div>
                        </div>                                                     
                        </form>
                        </div>-->
                   </div>     
				<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                   <div class="box-body table-responsive no-padding">
					   <div class="row">
							<div class="col-md-2">
								<div class="form-group">Tipo Comprobante
									<select name="tipo_doc" class="form-control" readonly>
                                 <option value="<?php echo htmlentities($row_mos_registro['tipo_doc'], ENT_COMPAT, 'UTF-8'); ?>" selected="">
                                  <?php echo  $row_mos_registro['tipo_doc']=='01' ? 'FACTURA':'BOLETA';?> 
                                 </option> ?>
                                    </select>
								</div>
							</div>
							<div class="col-md-1">Serie
								<div class="form-group"><input type="text" name="serie" class="form-control" value="<?php echo htmlentities($row_mos_registro['cn_serie'], ENT_COMPAT, 'UTF-8'); ?>" readonly> </div>
							</div>
							<div class="col-md-2">Número
								<div class="form-group"><input type="text" name="numero" class="form-control" value="<?php echo htmlentities($row_mos_registro['cn_numero'], ENT_COMPAT, 'UTF-8'); ?>" readonly=""> </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">Fecha
									<div class="input-group">
										<div class="input-group-addon">
										  <i class="fa fa-calendar"></i>
										</div>
										<!--<input type="text" id="fecha_ped" name="fecha_ped" value="<?php echo $fechaactual;?>" class="form-control" required>-->
                        <input type="text" id="fecha_ped" name="fecha_ped" value="<?php echo $row_mos_registro['fecha_ped'];?>" class="form-control" required>
                  <!--  <input type="hidden" id="nro_pedido" name="nro_pedido"  value="<?php echo htmlentities(@$row_mos_curso['nro_pedido'] , ENT_COMPAT, 'UTF-8'); ?>" class="form-control" required>-->
									</div>
								</div>
							</div>

							<div class="col-md-5">
								<div class="form-group">Cliente
									<div class="input-group">
										<div class="input-group-addon">
										  <i class="fa fa-calendar"></i>
										</div>
										<input type="text" id="cliente" name="cliente" value="<?php echo htmlentities(@$row_mos_registro['nro_doc'] . " - " .@$row_mos_registro['razon_social'] , ENT_COMPAT, 'UTF-8'); ?>" class="form-control" readonly>
									</div>
								</div>
							</div>
							<input type="hidden" name="cli_id" id="cli_id" value="<?php echo htmlentities($row_mos_registro['cc_cliente'], ENT_COMPAT, 'UTF-8'); ?>">
					   </div>

             <div class="row">
					  <div class="col-md-2">Moneda
                <div class="form-group"><input type="text" name="numero" value="PEN" class="form-control"  readonly> </div>
              </div>
              <div class="col-md-1">N° Pedido
                <div class="form-group"><input type="text" name="nroped" value="<?php echo htmlentities(@$row_mos_registro['nro_pedido'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control" readonly> </div>
              </div>
               <div class="col-md-2">Estado
                <div class="form-group">
  <select name="estado" id="estado" class="form-control">
    <option value="1" <?php if ($row_mos_registro['estado'] == 1) { echo "SELECTED";} ?>>EMITIDO</option>
    <option value="0" <?php if ($row_mos_registro['estado'] == 0) { echo "SELECTED";}?>>ANULADO</option>
  </select>
                </div>
              </div>
            <div class="col-md-7">
                  <div class="form-group">Vendedor :
                      <select name="cc_vendedor" id="cc_vendedor" class="form-control">
                      <?php 
                      do{ ?>
                               <option value="<?php echo $row_lista_user['usu_id']?>" 
                                  <?php if (!(strcmp($row_lista_user['usu_id'], htmlentities($row_mos_registro['cc_vendedor'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>
                                  <?php echo $row_lista_user['usu_nombre']?></option>
                      <?php  
                      } while ($row_lista_user = mysql_fetch_assoc($lista_user));              
                      ?>
                    </select>


                  </div>
                  </div></div>
							<!--<div class="col-md-1">Cod Vend
								<div class="form-group"><input type="text" name="codven" class="form-control" value="<?php echo htmlentities(@$row_mos_curso['cc_vendedor'], ENT_COMPAT, 'UTF-8'); ?>" required> </div>
							</div>
							<div class="col-md-4">Vendedor
								<div class="form-group"><input type="text" name="vendor" class="form-control" value="<?php echo htmlentities(@$row_mos_curso['usu_nombre'], ENT_COMPAT, 'UTF-8'); ?>"  required> </div>
							</div>-->
					   </div>
					   <?php if ($totalRows_mos_pd !== 0) { ?>
              			  <table class="table table-bordered table-striped table-hover table-condensed tablesorter">
							<tr>
							  <td><strong>Item</strong></td>
							  <td><strong>Código</strong></td>
							  <td><strong>Producto o Servicio</strong></td>
							  <td><strong>Cant</strong></td>
							  <td><strong>Precio</strong></td>
							  <td><strong>Importe</strong></td>
							<!--  <td align="center"><strong>Acciones</strong></td>-->
							</tr>
							<?php 
	
							do {
							$item++;
							$total = $total + $row_mos_pd['importe'];
							?>
							  <tr>
								<td><?php echo $item; ?></td>
								<td>
                <input name="KEY_PROD[]" type="hidden" id="KEY_PROD[]" value="<?php echo $prod[0]['pro_id'];?>"><?php echo $row_mos_pd['pro_id']; ?>
                  <input name="pro_id" type="hidden" id="pro_id" value="<?php echo $row_mos_pd['pro_id']; ?>">
                </td>
								<td><?php echo $row_mos_pd['pro_descripcion'];?></td>
								<td><?php echo $row_mos_pd['cant'];?>
                   <input name="cant" type="hidden" id="cant" value="<?php echo $row_mos_pd['cant']; ?>">
                </td>
								<td align="right"><?php echo $row_mos_pd['precio']; ?>
                    <input name="precio" type="hidden" id="precio" value="<?php echo $row_mos_pd['precio']; ?>">        
                </td>
								<td align="right"><?php echo $row_mos_pd['importe'];?>
                  <input name="importe" type="hidden" id="importe" value="<?php echo $row_mos_pd['importe']; ?>">        
                </td>
								<!--<td><div align="center"><a onclick="return confirm('¿Seguro que desea eliminar?')" href="producto_delete.php?id=<?php echo $row_mos_curso['pro_id']; ?>" title="Eliminar" class="hide-option"><button class="btn btn-primary btn-xs" type="button" data-toggle="tooltip" data-title="Eliminar"><i class="fa fa-trash-o"></i></button></a></div></td>-->
							  </tr>
							  <?php } while ($row_mos_pd = mysql_fetch_assoc($mos_pd)); ?>
							  <tr>
								<td colspan="5" align="right"><strong>SUB TOTAL</strong></td>
								<td align="right"><?php echo number_format($total,2);?></td>
								
							  </tr>
							  <tr>
								<td colspan="5" align="right"><strong>IGV (18%)</strong> </td>
								<td align="right"><?php 
								$igv = $total*0.18;
								echo number_format($igv,2);?></td>
								<input type="hidden" id="igv" name="igv"  value="<?php echo @$igv;?>"> 
							  </tr>
							  <tr>
								<td colspan="5" align="right"><strong>TOTAL A PAGAR</strong></td>
								<td align="right"><strong>
                <?php
                @$totalfinal = $total + $igv;
                echo number_format($totalfinal,2);?></strong></td>
                   <input type="hidden" id="total" name="total"  value="<?php echo @$totalfinal;?>"> </tr>
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
					<div class="box-footer">
							<button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
							<a href="documentos.php" style="margin-left:10px;"><button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></a>
							   <input type="hidden" name="MM_update" value="form1">
			   </form>
            </div>
        </div>
<!---->

 </div><!-- /.primary-->
          </div><!-- /.col-->
    </div> <!-- /.row -->
   <script src="js/jquery.min.js" type="text/javascript"></script>
   <script src="js/bootstrap.min.js" type="text/javascript"></script>     
   <script src="js/AdminLTE/app.js" type="text/javascript"></script>
   <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>        

    <link href="css/flick/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
  <script src="js/jquery-ui-1.10.4.custom.js"></script>
           
    <script type="text/javascript">
      $(function() {
        $( "#fecha_ped" ).datepicker({
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
            $( "#fecha_ped" ).datepicker( "option", "maxDate", selectedDate );
          }
        });
      });
    </script>
</body>
</html>