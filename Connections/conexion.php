<?php

# FileName="Connection_php_mysql.htm"

# Type="MYSQL"

# HTTP="true"

$hostname_conexion = "localhost";
/*$database_conexion = "ingticse_call";
$username_conexion = "ingticse_cursoi";
$password_conexion = "S0p0rt3";*/
$database_conexion = "nov14lhd_call";
$username_conexion = "root";
$password_conexion = "";

$conexion = mysql_connect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_query("SET NAMES 'UTF8'");

?>