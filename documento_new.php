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
	inner join call_usuario u on p.cc_vendedor = u.usu_id where nro_pedido = ".$_POST['buscar']."";
  $mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
  $row_mos_curso = mysql_fetch_assoc($mos_curso);

  $query_mos_pd = "SELECT nro_pedido, d.pro_id, p.pro_descripcion, cant, precio, importe FROM call_pedido_det d LEFT JOIN producto p on d.pro_id = p.pro_id
  where nro_pedido =" .$_POST['buscar']."";
  $mos_pd = mysql_query($query_mos_pd, $conexion) or die(mysql_error());
  $row_mos_pd = mysql_fetch_assoc($mos_pd);
  $totalRows_mos_pd = mysql_num_rows($mos_pd);
}


	$fechaactual = date("Y-m-d");
//// nuevo pedido
mysql_select_db($database_conexion, $conexion);
$query_nro_ped = "SELECT MAX(nro_pedido) + 1 as nroped FROM call_pedido";
$nro_ped = mysql_query($query_nro_ped, $conexion) or die(mysql_error());
$row_nro_ped = mysql_fetch_assoc($nro_ped);
$totalRows_nro_ped = mysql_num_rows($nro_ped);
	
if ($row_nro_ped['nroped']== "")	
{
$idped = 1;}
else
{
$idped = $row_nro_ped['nroped'];}
/*$sql = "SELECT MAX(id_pedido) + 1 FROM pedido";

$result = mysql_query ($sql);
if (! $result){
   echo "La consulta SQL contiene errores.".mysql_error();
   exit();
}else {
   
    while ($row = mysql_fetch_row($result))
	{
       $idped = $row[0];		
    }

 }
//*/
/*consulta obtener id _Pedidpo*/

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	//actualizamos estado  mesa
	$updateSQL = sprintf("update mesa set estado = 1 where id_meza = %s",
                       	GetSQLValueString($_SESSION['mesa'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Resultup = mysql_query($updateSQL, $conexion) or die(mysql_error());
	//
	date_default_timezone_set('America/Lima');
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	
	// obtener el cierre
  	mysql_select_db($database_conexion, $conexion);
	$query_ultima_apertura = "SELECT * FROM cierre ORDER BY id_cierre DESC LIMIT 1";
	$ultima_apertura = mysql_query($query_ultima_apertura, $conexion) or die(mysql_error());
	$row_ultima_apertura = mysql_fetch_assoc($ultima_apertura);
	$totalRows_ultima_apertura = mysql_num_rows($ultima_apertura);
  	//
  	$insertSQL = sprintf("INSERT INTO pedido (id_pedido, id_emp,fecha_ped,id_meza, estado_ped,total,hora_ped,id_cierre) 
  						VALUES (%s, %s, %s,%s, %s, %s, %s, %s)",
                       	GetSQLValueString($idped, "int"),
                       	GetSQLValueString($row_mos_usuario['id_emp'], "int"),
					   	GetSQLValueString($fecha, "date"),
						GetSQLValueString($_SESSION['mesa'], "int"),
						GetSQLValueString(0, "int"),// 0 PEDIDO EN ESPERAAA 1 PEDIDO CANCELADO
                       	GetSQLValueString($_SESSION['total'], "double"),
						GetSQLValueString($hora, "text"),
						GetSQLValueString($row_ultima_apertura['id_cierre'], "int"));

  mysql_select_db($database_conexion, $conexion);

  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

 //detalle
$producto = isset($_POST['KEY_PROD']) ? $_POST['KEY_PROD'] : NULL;
for ($i=0;$i<sizeof($producto);$i++){
/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {*/
  $query = sprintf("INSERT INTO det_pedido (id_pedido, id_plat,cantidad, importe) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($idped, "int"),
					   GetSQLValueString($producto[$i], "int"),
					   GetSQLValueString($_POST['cantidad'], "int"),
					   GetSQLValueString($_POST['importe'], "double"));
 	mysql_select_db($database_conexion, $conexion);
  	$Result2 = mysql_query($query, $conexion) or die(mysql_error());
/*		unset($_SESSION['cart']);
		unset($_SESSION['items']);
		unset($_SESSION['total']);
*/
	$mensaje = 'Pedido Registrado Correctamente.';
  	//
    $insertGoTo = "seleccionar_mesa.php?n=$mensaje";
  	if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  	}
  	header(sprintf("Location: %s", $insertGoTo));
 	}  
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
                                
                  <div class="box-header"> <h3 class="box-title">Nuevo Comprobante </h3></div>
                   
                  <div class="box-body">
                    <div class="row" style="margin-bottom:10px;">
                      <div class="col-sm-6">
                        <!--  <a class="btn btn-primary" href="documento_new.php"><i class="fa fa-pencil"></i> Agregar</a>-->
            </div>
                    <div class="col-sm-6 search-form">
                            <form name="formb" id="formb" action="" method="post" class="text-right">
                                  <div class="input-group">                                          
                                     <input type="text" name="buscar" class="form-control" placeholder="Ingresar N° Pedido ...">
                                 <div class="input-group-btn">
                                 <button type="submit" name="q" class="btn btn btn-primary"><i class="fa fa-search"></i></button>
                                 </div>
                        </div>                                                     
                        </form>
                        </div>
                   </div>     
				<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                   <div class="box-body table-responsive no-padding">
					   <div class="row">
							<div class="col-md-2">
								<div class="form-group">Tipo Comprobante
									<select name="tipo_doc" class="form-control">
                                        <option value="02" selected="">BOLETA</option>
										<option value="01" selected="">FACTURA</option>
                                    </select>
								</div>
							</div>
							<div class="col-md-1">Serie
								<div class="form-group"><input type="text" name="serie" class="form-control" required> </div>
							</div>
							<div class="col-md-2">Número
								<div class="form-group"><input type="text" name="numero" class="form-control"  required> </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">Fecha
									<div class="input-group">
										<div class="input-group-addon">
										  <i class="fa fa-calendar"></i>
										</div>
										<input type="text" id="fecha_ped" name="fecha_ped" value="<?php echo $fechaactual;?>" class="form-control" readonly="true" required>
									</div>
								</div>
							</div>

							<div class="col-md-5">
								<div class="form-group">Cliente
									<div class="input-group">
										<div class="input-group-addon">
										  <i class="fa fa-calendar"></i>
										</div>
										<input type="text" id="cliente" name="cliente" value="<?php echo htmlentities($row_mos_curso['nro_doc'] . " - " .$row_mos_curso['razon_social'] , ENT_COMPAT, 'UTF-8'); ?>" class="form-control" required>
									</div>
								</div>
							</div>
							<input type="hidden" name="cli_id" id="cli_id" value="<?php echo htmlentities($row_mos_curso['cli_id'], ENT_COMPAT, 'UTF-8'); ?>">
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
								<td><input name="KEY_PROD[]" type="hidden" id="KEY_PROD[]" value="<?php echo $prod[0]['pro_id'];?>"><?php echo $row_mos_pd['pro_id']; ?></td>
								<td><?php echo $row_mos_pd['pro_descripcion'];?></td>
								<td><?php echo $row_mos_pd['cant'];?></td>
								<td align="right"><?php echo $row_mos_pd['precio']; ?></td>
								<td align="right"><?php echo $row_mos_pd['importe'];?></td>
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
								
							  </tr>
							  <tr>
								<td colspan="5" align="right"><strong>TOTAL A PAGAR</strong></td>
								<td align="right"><strong><?php echo number_format($total + $igv,2);?></strong></td>
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
					<div class="box-footer">
							<button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
							<a href="documentos.php" style="margin-left:10px;"><button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></a>
							<input type="hidden" name="MM_insert" value="form1">
					</div>    
			   </form>
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