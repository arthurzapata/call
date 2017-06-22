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

if (isset($_GET['nro'])) {
  $ser = $_GET['ser'];
  $nro = $_GET['nro'];
}

mysql_select_db($database_conexion, $conexion);
$query_mos_curso = "SELECT cn_serie,cn_numero,tipo_doc,nro_pedido,c.nro_doc,c.razon_social,cc_vta,cc_moneda,d.fecha_reg,u.usu_nombre, total,fecha_ped FROM documento d left join cliente c on d.cc_cliente =c.cli_id 
left join call_usuario u on d.cc_vendedor = u.usu_id where cn_serie='".$ser."' and cn_numero='".$nro."'";
$mos_curso = mysql_query($query_mos_curso, $conexion) or die(mysql_error());
$row_mos_curso = mysql_fetch_assoc($mos_curso);
?>
<!DOCTYPE html>
<html class="bg-black">
<head>
    <meta charset="UTF-8">

    <title></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
        name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery.min.js"></script>

</head>
<body class="bg-black">
    <form id="form1" runat="server">
    
    <div class="form-documento" id="login-box">
       
     <div class="header">
            <a href="javascript:window.history.back();" class="btn btn-danger"> Regresar</a>
     </div>
     <div class="body bg-white">

        <div class="row">
           <div class="col-lg-6 col-md-6">
                <div class="form-group">
                   <!-- <img src="img/comacsa.png" height="130" width="360" />-->
                    <asp:Image ID="Image1" runat="server" height="130" width="360"/>
                </div>
                <div class="form-group">
                   <strong>ZANMARTIN SAC</strong>
                </div>
                <div class="form-group">
                    Av. Manuel Villaran N° 1201 - 302
                </div>
                <div class="form-group">
                    LIMA - LIMA - LIMA
                </div>
                <br />
            </div>
            <div class="col-lg-1 col-md-1">
            </div>
            <div class="col-lg-5 col-md-5">
                <div class="comprobante">
                    <div class="form-group">RUC N° 20468645381</div>
                    <div class="form-group"><?php echo $row_mos_curso['tipo_doc']=="01" ? 'FACTURA DE VENTA': 'BOLETA DE VENTA';?></div>
                    <div class="form-group">ELECTRÓNICA</div>
                    <div class="form-group"><asp:Label ID="lblSerieNum" runat="server" Text=""></asp:Label></div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2">
                <div class="form-group">
                    <strong>Nombre/Razón Social:</strong>
                </div>
               <!--<div class="form-group">
                    <strong>Dirección:</strong>
                </div>
       -->
            </div>
            <div class="col-lg-10 col-md-10">
                <div class="form-group">
                    <?php echo $row_mos_curso['razon_social'];?>
                </div>
             

            </div>
         </div>
         <div class="row">
            <div class="col-lg-2 col-md-2">
                  <div class="form-group">
                        <strong>RUC:</strong>
                  </div>
            </div>
            <div class="col-lg-5 col-md-5">
                <div class="form-group">
             <?php echo $row_mos_curso['nro_doc'];?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group">
                    <strong>Fecha Emisión:</strong>  <?php echo $row_mos_curso['fecha_ped'];?>
                </div>
             </div>
             <div class="col-lg-2 col-md-2">
                <div class="form-group">
                    <strong>Moneda:</strong>  <?php echo $row_mos_curso['cc_moneda']=="01"? "01 - PEN":"02 - USD";?>
                </div>
            </div>
        </div>
        <div class ="row">
            <div class="col-lg-12 col-md-12">
                <asp:GridView ID="GridView1" runat="server" AutoGenerateColumns="False"  CssClass="table table-striped table-hover table-condensed tablesorter" >
                <Columns>
                     <asp:BoundField DataField="numeroOrdenItem"  HeaderText="Item" >
                         <ItemStyle Wrap="False" HorizontalAlign="Center" />
                     </asp:BoundField>
                     <asp:BoundField DataField="codigoproducto"  HeaderText="Código" >
                         <ItemStyle Wrap="False" HorizontalAlign="Center" />
                     </asp:BoundField>
                     <asp:BoundField DataField="descripcion"  HeaderText="Descripción" >
                         <ItemStyle Wrap="False" />
                     </asp:BoundField>
                     <asp:BoundField DataField="unidadMedida"  HeaderText="Und." >
                         <ItemStyle Wrap="False" HorizontalAlign="Center" />
                     </asp:BoundField>
                     <asp:BoundField DataField="cantidad"  HeaderText="Cant." >
                         <ItemStyle Wrap="False" HorizontalAlign="Right" />
                     </asp:BoundField>
                       <asp:BoundField DataField="importeUnitarioSinImpuesto"  HeaderText="V. Uni" DataFormatString="{0:N}">
                         <ItemStyle Wrap="False" HorizontalAlign="Right" />
                     </asp:BoundField>
                   <asp:BoundField DataField="importeUnitarioConImpuesto"  HeaderText="P. Uni" DataFormatString="{0:N}" >
                         <ItemStyle Wrap="False" HorizontalAlign="Right" />
                     </asp:BoundField>
                     <asp:BoundField DataField="importedescuento"  HeaderText="Desc." DataFormatString="{0:N}">
                         <ItemStyle Wrap="False" HorizontalAlign="Right" />
                     </asp:BoundField>
                     <asp:BoundField DataField="importeTotalSinImpuesto"  HeaderText="Valor Venta"  DataFormatString="{0:N}" >
                         <ItemStyle Wrap="False" HorizontalAlign="Right" />
                     </asp:BoundField>
                </Columns>
           
            </asp:GridView>
            </div>
        </div> 

        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="form-form-group">
                    <asp:Label ID="lbl1000" runat="server" Text="Label"></asp:Label>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="form-group"><strong>Op. Gravada</strong>
                </div>
                <div class="form-group"><strong>I.G.V. (<asp:Label ID="lbl9002" runat="server" Text="Label"></asp:Label>)</strong>
                </div>
                <div class="form-group"><strong>Op.Inafecta</strong>
                </div>
                <div class="form-group"><strong>Op.Exonerada</strong>
                </div>
                <div class="form-group"><hr /></div>
                <div class="form-group"><strong>IMPORTE TOTAL</strong>
                </div>
                <div class="form-group"><strong>Total Descuentos</strong>
                </div>
            </div>
             <div class="col-lg-2 col-md-2 text-right">
                 <div class="form-group"><asp:Label ID="lblOpGravada" runat="server" Text="Label"></asp:Label>
                </div>
                <div class="form-group"><asp:Label ID="lblIgv" runat="server" Text="Label"></asp:Label>
                </div>
                <div class="form-group"><asp:Label ID="lblOpInafecta" runat="server" Text="Label"></asp:Label>
                </div>
                <div class="form-group"><asp:Label ID="lblOpExon" runat="server" Text="Label"></asp:Label>
                </div>
                <div class="form-group"><hr /></div>
                <div class="form-group"><asp:Label ID="lblImporTot" runat="server" Text="Label"></asp:Label>
                </div>
                <div class="form-group"><asp:Label ID="lblTotDesc" runat="server" Text="Label"></asp:Label>
                </div>
             </div>
        </div>
      
        
     </div>
      
    </div>
    </form>
</body>
</html>
