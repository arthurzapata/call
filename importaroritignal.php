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
$userid = $row_mos_usuario['usu_id'];
$perid =$row_mos_usuario['per_id'];
$empid = $row_mos_usuario['emp_id'];
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
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Importar de Excel a la Base de Datos </title>
</head>

<body>
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
                                      <!--  <li>
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
<button class="btn btn-success btn-sm" onclick="window.location='excel.php'" disabled>
                                <i class="fa fa-cloud-download"></i> Descargar EXCEL</button>
                            </div>
                        </div>
                        <div class="row" style="margin:0px 0px 10px 0px;">   
                            <div class="col-md-12 text-center">
                                <button class="btn btn-danger btn-sm" disabled onclick="window.location='pdf.php'">
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
    <!-- FORMULARIO PARA SOICITAR LA CARGA DEL EXCEL -->
    
 <div class="row">             
    <div class="col-md-9  col-lg-9">          
      <div class="box box-primary">
        <div class="box-header"> 
            <h3 class="box-title">Selecciona el archivo a importar</h3>
        </div>   
        <div class="box-body">
            <form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
                <div class="row">  
                <div class="col-md-4">
                  <input type="file" name="excel" />
                </div>
                <div class="col-md-4">  
                  <input type='submit' name='enviar'  value="Importar" class="btn btn-primary"/>
                  <input type="hidden" value="upload" name="action" />
                </div>
                <div class="col-md-4">  <a href="index.php">Inicio</a>
                </div>  
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
   
    <!-- CARGA LA MISMA PAGINA MANDANDO LA VARIABLE upload -->
    <?php
    extract($_POST);
    if ($action == "upload") {
        //cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo bak_ 
        $archivo = $_FILES['excel']['name'];
        $tipo = $_FILES['excel']['type'];
        $destino = "bak_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)){
            echo "Archivo Cargado Con Éxito";
        }
        else{
            echo "Error Al Cargar el Archivo";
        }
        if (file_exists("bak_" . $archivo)) {
            /** Clases necesarias */
            require_once('Classes/PHPExcel.php');
            require_once('Classes/PHPExcel/Reader/Excel2007.php');
            // Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("bak_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            // Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);
            //conectamos con la base de datos 
           /* $cn = mysql_connect("localhost", "root", "") or die("ERROR EN LA CONEXION");
            $db = mysql_select_db("prueba", $cn) or die("ERROR AL CONECTAR A LA BD");*/
            mysql_select_db($database_conexion, $conexion);
            // Llenamos el arreglo con los datos  del archivo xlsx
            //arthur
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
            //
            for ($i = 2; $i <= $arrayCount; $i++) {
                //$_DATOS_EXCEL[$i]['reg_codigo'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_fecha'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['est_id'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['cur_id'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                //$_DATOS_EXCEL[$i]['reg_fechareg'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['usu_id'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_apellidos'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_nombres'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_formacion'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_observaciones'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
                //$_DATOS_EXCEL[$i]['reg_ciudad'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_pais'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_email'] = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_telefono'] = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['reg_telefono2'] = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
            }
            $total_reg = $arrayCount - 1;
        }
        //si por algo no cargo el archivo bak_ 
        else {
            echo "Necesitas primero importar el archivo";
        }
        $errores = 0;
        //recorremos el arreglo multidimensional 
        //para ir recuperando los datos obtenidos
        //del excel e ir insertandolos en la BD
        foreach ($_DATOS_EXCEL as $campo => $valor) {
            $sql = "INSERT INTO call_registro (reg_fecha,est_id,cur_id,usu_id,reg_apellidos,reg_nombres,reg_formacion,reg_observaciones,reg_pais,reg_email,reg_telefono,reg_telefono2,emp_id) VALUES ('";
            foreach ($valor as $campo2 => $valor2) {
                //echo "Clave: $valor; campo: $campo2; Valor: $valor2 <br />\n";
                if ($campo2 == "est_id"){
					mysql_select_db($database_conexion, $conexion);
                    $query_mos_est = "SELECT * FROM call_estado WHERE est_nombre ='".$valor2."'";
                    $mos_est = mysql_query($query_mos_est, $conexion) or die(mysql_error());
                    $row_mos_est = mysql_fetch_assoc($mos_est);
                    $valor2 = $row_mos_est['est_id'];
                    $sql.= $valor2 . "','"; 
				}
                elseif ($campo2 == "cur_id"){
                    mysql_select_db($database_conexion, $conexion);
                    $query_mos_cur = "SELECT * FROM call_curso WHERE cur_nombre ='".$valor2."'";
                    $mos_cur = mysql_query($query_mos_cur, $conexion) or die(mysql_error());
                    $row_mos_cur = mysql_fetch_assoc($mos_cur);
                    $valor2 = $row_mos_cur['cur_id'];
                    $sql.= $valor2 . "','";  
                }
				        elseif ($campo2 == "usu_id"){
                    mysql_select_db($database_conexion, $conexion);
                    $query_mos_usu = "SELECT * FROM call_usuario WHERE usu_nombre ='".$valor2."'";
                    $mos_usu = mysql_query($query_mos_usu, $conexion) or die(mysql_error());
                    $row_mos_usu = mysql_fetch_assoc($mos_usu);
                    $valor2 = $row_mos_usu['usu_id'];
                    $sql.= $valor2 . "','";  
                }
                elseif ($campo2 == "reg_telefono2") {
                  //$sql.= $valor2 . "');" ;
                  $sql.= $valor2 . "',".$empid.");" ;
                }
                else
                {
                    $sql.= $valor2 . "','";
                }
                //$campo2 == "reg_telefono2" ? $sql.= $valor2 . "');" : $sql.= $valor2 . "','";
            }
            //echo $sql;
            $result = mysql_query($sql);
            if (!$result) {
                echo "Error al insertar registro " . $campo;
                $errores+=1;
            }
        }
        echo '<div class="alert alert-info alert-dismissable">
         <i class="fa fa-info"></i>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <b>Alerta !</b> ARCHIVO IMPORTADO CON EXITO, EN TOTAL '.$total_reg.' REGISTROS Y '.$errores.' ERRORES !!
        </div>';
        //una vez terminado el proceso borramos el archivo que esta en el servidor el bak_
        unlink($destino);
    }
    ?>
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
</body>
</html>