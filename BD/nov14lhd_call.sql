-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-06-2017 a las 17:36:57
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `nov14lhd_call`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_comentario`
--

CREATE TABLE IF NOT EXISTS `call_comentario` (
`com_id` int(11) NOT NULL,
  `emp_id` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usu_id` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `com_comentario` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `com_fechareg` date DEFAULT NULL,
  `com_fechasis` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_comentario`
--

INSERT INTO `call_comentario` (`com_id`, `emp_id`, `usu_id`, `com_comentario`, `com_fechareg`, `com_fechasis`) VALUES
(1, '12', '41', 'señora no me contesto  el señor borrar', '2015-04-16', '2015-04-16 22:30:22'),
(2, '12', '41', 'señora  voy a almorzar tengo hambre', '2015-04-16', '2015-04-16 22:30:35'),
(3, '1', '21', 'señor dayana  hasta las hora llame a 35 perosna  me retiro a almorzar', '2015-04-17', '2015-04-17 23:39:33'),
(4, '1', '21', 'yo paola   termine mis llamadas', '2015-04-17', '2015-04-17 23:40:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_config`
--

CREATE TABLE IF NOT EXISTS `call_config` (
`conf_id` int(11) NOT NULL,
  `conf_url` varchar(50) DEFAULT NULL,
  `conf_correo` varchar(50) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_config`
--

INSERT INTO `call_config` (`conf_id`, `conf_url`, `conf_correo`) VALUES
(1, 'Lheowebglobal.com', 'informes@eobs.pe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_empresa`
--

CREATE TABLE IF NOT EXISTS `call_empresa` (
`emp_id` int(11) NOT NULL,
  `emp_nombre` varchar(50) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_empresa`
--

INSERT INTO `call_empresa` (`emp_id`, `emp_nombre`) VALUES
(1, 'WEB'),
(7, 'EOBS-PERÚ'),
(14, 'PROMELSA'),
(13, 'ZAM MARKETING ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_estado`
--

CREATE TABLE IF NOT EXISTS `call_estado` (
`est_id` int(11) NOT NULL,
  `est_nombre` varchar(50) DEFAULT NULL,
  `est_color` varchar(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_estado`
--

INSERT INTO `call_estado` (`est_id`, `est_nombre`, `est_color`) VALUES
(1, '1.PENDIENTES DE CONTACTO', 'primary'),
(2, '2.PENDIENTES DE DOCUMENTOS', 'yellow'),
(3, '3.EN EVALUACIÓN', 'orange'),
(4, '4.PENDIENTES RESERVA DE MATRICULA', 'maroon'),
(5, '5.MATRICULADOS', 'maroon'),
(6, 'DADOS DE BAJA', 'red'),
(9, 'NO CONTESTADOS', 'light-blue'),
(10, 'NUMERO ERRADO', 'green'),
(12, 'CHUNGUITO', 'primary');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_obs`
--

CREATE TABLE IF NOT EXISTS `call_obs` (
`obs_id` int(11) NOT NULL,
  `obs_fechareg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `obs_descripcion` varchar(500) DEFAULT NULL,
  `reg_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_obs`
--

INSERT INTO `call_obs` (`obs_id`, `obs_fechareg`, `obs_descripcion`, `reg_id`) VALUES
(1, '2012-11-28 04:27:12', 'Hola soy chucky mi estimada Narishitassss', 2922),
(2, '2012-11-28 04:27:26', 'dlsdksldkslklds', 2922),
(3, '2012-11-28 04:32:22', 'joijijoijoio', 2922),
(4, '2012-11-28 04:32:45', 'emy', 2921);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_pedido`
--

CREATE TABLE IF NOT EXISTS `call_pedido` (
`nro_pedido` int(11) NOT NULL,
  `cli_id` int(11) NOT NULL,
  `ped_estado` int(11) NOT NULL,
  `cc_vendedor` int(11) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_ped` datetime NOT NULL,
  `requerimiento` text COLLATE utf8_spanish_ci
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_pedido`
--

INSERT INTO `call_pedido` (`nro_pedido`, `cli_id`, `ped_estado`, `cc_vendedor`, `total`, `fecha_reg`, `fecha_ped`, `requerimiento`) VALUES
(1, 1, 1, 14, '1000.00', '2017-06-21 23:06:15', '2017-06-15 00:00:00', NULL),
(2, 1, 1, 16, '50.00', '2017-06-21 23:06:18', '2017-06-08 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_pedido_det`
--

CREATE TABLE IF NOT EXISTS `call_pedido_det` (
  `nro_pedido` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `precio` decimal(18,2) DEFAULT NULL,
  `importe` decimal(18,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_pedido_det`
--

INSERT INTO `call_pedido_det` (`nro_pedido`, `pro_id`, `cant`, `precio`, `importe`) VALUES
(1, 2, 1, '800.00', '800.00'),
(1, 3, 1, '300.00', '300.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_perfil`
--

CREATE TABLE IF NOT EXISTS `call_perfil` (
`per_id` int(11) NOT NULL,
  `per_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_perfil`
--

INSERT INTO `call_perfil` (`per_id`, `per_nombre`) VALUES
(1, 'Coordinador'),
(2, 'Call Center'),
(3, 'Owner'),
(4, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_registro`
--

CREATE TABLE IF NOT EXISTS `call_registro` (
`reg_id` int(11) NOT NULL,
  `reg_codigo` int(5) unsigned zerofill DEFAULT NULL,
  `reg_fecha` date DEFAULT NULL,
  `est_id` int(11) DEFAULT NULL,
  `cur_id` int(11) DEFAULT NULL COMMENT 'Id Servicio',
  `reg_fechareg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usu_id` int(11) DEFAULT NULL,
  `reg_apellidos` varchar(50) DEFAULT NULL,
  `reg_nombres` varchar(50) DEFAULT NULL,
  `reg_formacion` varchar(100) DEFAULT NULL COMMENT 'Empresa',
  `reg_observaciones` varchar(300) DEFAULT NULL,
  `reg_ciudad` varchar(50) DEFAULT NULL,
  `reg_pais` varchar(50) DEFAULT NULL,
  `reg_email` varchar(50) DEFAULT NULL,
  `reg_telefono` varchar(30) DEFAULT NULL,
  `reg_telefono2` varchar(30) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `reg_direccion` varchar(100) DEFAULT NULL,
  `reg_rubro` varchar(100) DEFAULT NULL,
  `reg_derivado` int(11) DEFAULT NULL,
  `cli_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_registro`
--

INSERT INTO `call_registro` (`reg_id`, `reg_codigo`, `reg_fecha`, `est_id`, `cur_id`, `reg_fechareg`, `usu_id`, `reg_apellidos`, `reg_nombres`, `reg_formacion`, `reg_observaciones`, `reg_ciudad`, `reg_pais`, `reg_email`, `reg_telefono`, `reg_telefono2`, `emp_id`, `reg_direccion`, `reg_rubro`, `reg_derivado`, `cli_id`) VALUES
(1, NULL, '2016-05-13', 1, 1, '2017-06-11 17:31:47', 6, 'VALDERRAMA ALFARO', 'INGRID MARYORY', 'I&A', 'asasas', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '956986610', '982539291', 1, NULL, NULL, NULL, 0),
(2, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 5, 'ZAPATA VALDERRAMA', 'EMY FABIANA', 'ZSOLUTIONS', '2', NULL, 'SANTIAGO DE SURCO', 'arthuro_2004@hotmail.com', '22222222', '222222222222', 1, NULL, NULL, NULL, 0),
(3, NULL, '2016-05-14', 5, 1, '2017-06-11 17:31:47', 5, 'ZAVALA', 'CHUKY', 'ZSOLUTIONS', '222                                  ', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '2', '222222222222', 1, NULL, NULL, NULL, 0),
(4, NULL, '2016-05-14', 2, 1, '2017-06-11 17:31:47', 1, 'arturo', 't amo', 'ZSOLUTIONS', '33                 ', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '333', '33', 1, NULL, NULL, NULL, 0),
(5, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 7, 'MORALES', 'RENDA', 'I&A', NULL, NULL, 'SANTIAGO DE SURCO', 'arthuro_2004@hotmail.com', '9998933', '982539291', 1, NULL, NULL, NULL, 0),
(6, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 7, 'alfaro', 'roysi', 'ssdjdksjkj', NULL, NULL, 'jkjkkj', 'arthuro_2004@hotmail.com', '232323', NULL, 1, NULL, NULL, 1, 0),
(7, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 11, 'fernandez', 'luana', 'I&A', NULL, NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '956986610', '982539291', 1, NULL, NULL, 1, 0),
(8, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 5, 'MORALES RODRIGUEZ', 'BRENDA', '<#', 'hola quisiera saber precio del curso', NULL, 'ASSLASAS', 'arthuro_2004@hotmail.com', '956986610', NULL, 1, NULL, NULL, 1, 0),
(9, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 11, 'prueba1', '1', 'sss', '222', NULL, 'PERU', 'arthuro_2004@hotmail.com', '2222', '22', 1, NULL, NULL, 1, 0),
(10, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 7, 'prueba 2', '2', '2', NULL, NULL, '2', 'arthuro_2004@hotmail.com', '2', '2', 1, NULL, NULL, 1, 0),
(11, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 10, 'prueba 3', '3', 'ZSOLUTIONS', '3', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '3', '3', 1, NULL, NULL, 1, 0),
(12, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 6, 'prueba 4', '4', 'ww', '333', NULL, 'www', 'arthuro_2004@hotmail.com', '333', '333', 1, NULL, NULL, 1, 0),
(13, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 11, 'prueba 5', '5', 'aa', NULL, NULL, 'aaa', 'arthuro_2004@hotmail.com', '3', '2', 1, NULL, NULL, 1, 0),
(14, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 7, 'prueba 6', '6', 'ssdjdksjkj', NULL, NULL, 'SSs', 'arthuro_2004@hotmail.com', '3', '3', 1, NULL, NULL, 1, 0),
(15, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 10, 'prueba 7', '7', 'aaaa', '33', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '333', '3', 1, NULL, NULL, 1, 0),
(16, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 1, 'prueba 8', '8', '8', '                 ', NULL, '8', 'arthuro_2004@hotmail.com', NULL, NULL, 1, NULL, NULL, 1, 0),
(17, NULL, '2017-05-21', 1, 1, '2017-06-11 17:31:47', 5, 'TORRES GONZALES', 'BRYAN', 'Hola', 'DESEA EL DESARROLLO DE UNA WEB AUTOADMINISTRABLE, COTIZAR', NULL, 'Peru', 'chuyito@chistrese.com', '980989300', '980898944', 1, NULL, NULL, NULL, 0),
(18, NULL, '2017-05-22', 1, 1, '2017-06-11 17:31:47', 5, 'KOOPMAN', 'SIMONE ANNA', 'SAP', 'HOLA SOY CHUCKY', NULL, 'Peru', 'skoopman@promelsa.com.pe', '980989300', NULL, 1, NULL, NULL, NULL, 0),
(19, NULL, '2017-05-22', 1, 1, '2017-06-11 17:31:47', 5, 'KOOPMAN', 'SIMONE ANNA', 'SAP', 'HOLA SOY CHUCKY', NULL, 'Peru', 'skoopman@promelsa.com.pe', '980989300', NULL, 1, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_usuario`
--

CREATE TABLE IF NOT EXISTS `call_usuario` (
`usu_id` int(11) NOT NULL,
  `per_id` int(11) NOT NULL,
  `usu_nombre` varchar(50) NOT NULL,
  `usu_clave` varchar(50) NOT NULL,
  `usu_activo` int(1) DEFAULT NULL,
  `emp_id` int(11) NOT NULL,
  `cor_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_usuario`
--

INSERT INTO `call_usuario` (`usu_id`, `per_id`, `usu_nombre`, `usu_clave`, `usu_activo`, `emp_id`, `cor_id`) VALUES
(1, 3, 'super', 'arthur', 1, 1, 0),
(2, 4, 'admin', 'admin', 1, 1, 1),
(3, 1, 'maria', 'maria', 1, 1, 1),
(4, 1, 'rafa', 'rafa', 1, 1, 2),
(5, 2, 'arthur', 'arthur', 1, 1, 3),
(6, 2, 'marquio', 'marquio', 1, 1, 3),
(7, 2, 'cecilia', 'cecilia', 1, 1, 4),
(8, 4, 'admin2', 'admin2', 1, 1, 1),
(9, 2, 'prueba', 'prueba', 1, 1, 2),
(10, 1, 'prueba2', 'prueba2', 1, 1, 2),
(11, 1, 'ZAM', 'zam', 1, 1, 2),
(12, 2, 'Alexander', 'alexander', 1, 13, 1),
(13, 2, 'Wilber', 'wilber', 1, 13, 1),
(14, 2, 'Katherine', 'katherine', 1, 13, 1),
(15, 2, 'Brenda', 'brenda', 1, 13, 1),
(16, 2, 'Carlos', 'carlos', 1, 13, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_visitas`
--

CREATE TABLE IF NOT EXISTS `call_visitas` (
`vis_id` int(11) NOT NULL,
  `vis_fecha` date NOT NULL,
  `vis_lugar` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `vis_cli` int(11) NOT NULL,
  `vis_tipovisita` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `hora_ini` time NOT NULL,
  `hora_fin` time NOT NULL,
  `usu_id` int(11) NOT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `motivo` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_visitas`
--

INSERT INTO `call_visitas` (`vis_id`, `vis_fecha`, `vis_lugar`, `vis_cli`, `vis_tipovisita`, `hora_ini`, `hora_fin`, `usu_id`, `fecha_reg`, `motivo`) VALUES
(1, '2017-06-01', 'LOS OLIVOS', 1, 'OFICINA', '00:00:00', '00:00:00', 14, '2017-06-21 05:21:15', ''),
(2, '2017-06-01', 'LOS OLIVOS', 1, 'OFICINA', '00:00:00', '00:00:00', 14, '2017-06-21 05:25:25', ''),
(3, '2017-06-02', 'san isidro', 4, 'opoerador', '12:00:00', '15:00:00', 16, '2017-06-21 05:21:49', 'kkkkkkkkkkk'),
(4, '2017-06-02', 'san isidro', 2, 'opoerador', '12:00:00', '15:00:00', 14, '2017-06-21 05:22:00', ''),
(8, '2017-06-05', 'MIRAFLORES JR JOSE PARDO', 2, 'LOCAL', '15:00:00', '16:00:00', 1, '2017-06-21 19:23:53', 'ddddddddddddddd'),
(9, '2017-06-07', 'OVALO HIGUERETA - SURCO', 2, 'LOCAL', '15:00:00', '17:00:00', 15, '2017-06-21 22:06:46', 'SE LLEVARA COTIZACIONES Y MOSTRARA LOS SERVICIOS'),
(10, '2017-06-08', 'MIRAFLORES JR JOSE PARDO', 1, 'LOCAL', '15:00:00', '16:00:00', 13, '2017-06-21 22:58:55', 'www');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
`cli_id` int(11) NOT NULL,
  `nro_doc` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `razon_social` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cli_id`, `nro_doc`, `razon_social`, `direccion`, `telefono`, `email`, `fecha_reg`) VALUES
(1, '10468645381', 'ARTURO ZAPATA CARRETERO', 'JR. SAN GABINO 2323 SURCO', '943054727', 'azapata@promelsa.com.pe', '2017-06-12 04:36:46'),
(2, '78565412', 'INGRID VALDERRAMA ALFARO', 'JR SAN GABINO MIRAFLORES', '985655555', 'ingrdivalderrama@gmail.com', '2017-06-21 19:21:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE IF NOT EXISTS `documento` (
  `cn_serie` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `cn_numero` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_doc` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nro_pedido` int(11) NOT NULL,
  `cc_cliente` int(11) NOT NULL,
  `cc_vta` int(11) NOT NULL,
  `cc_moneda` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cc_vendedor` int(11) NOT NULL,
  `total` decimal(18,2) DEFAULT NULL,
  `fecha_ped` date NOT NULL,
  `igv` decimal(18,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`cn_serie`, `cn_numero`, `tipo_doc`, `nro_pedido`, `cc_cliente`, `cc_vta`, `cc_moneda`, `fecha_reg`, `cc_vendedor`, `total`, `fecha_ped`, `igv`) VALUES
('F011', '0000001', '01', 1, 10, 1, '01', '2017-06-22 00:11:26', 14, '118.00', '2017-06-01', NULL),
('F021', '0000002', '01', 22, 22, 2, '01', '2017-06-22 00:11:23', 14, '338.00', '2017-06-02', NULL),
('F021', '0000003', '01', 3232, 11, 2, '01', '2017-06-22 00:11:19', 16, '1180.00', '2017-06-02', NULL),
('F021', '0000004', '01', 354554, 1, 2, '01', '2017-06-22 00:11:17', 14, '600.00', '2017-06-01', NULL),
('F001', '0000005', '01', 4545, 1, 1, '01', '2017-06-22 00:11:12', 16, '100.00', '2017-06-03', NULL),
('F011', '0000007', '1', 1, 1, 1, '01', '2017-06-22 14:38:38', 15, '1298.00', '2017-06-21', '198.00'),
('F011', '0000006', '1', 1, 1, 1, '01', '2017-06-22 00:09:15', 14, NULL, '2017-06-14', NULL),
('F011', '0000008', '01', 1, 1, 1, '01', '2017-06-22 14:38:33', 13, '1298.00', '2017-06-20', '198.00'),
('F011', '0000009', '01', 1, 1, 1, '01', '2017-06-22 14:38:18', 12, '1298.00', '2017-06-16', '198.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_det`
--

CREATE TABLE IF NOT EXISTS `documento_det` (
  `cn_item` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `cn_serie` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `cn_numero` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `pro_id` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `precio` decimal(18,2) NOT NULL,
  `importe` decimal(18,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `documento_det`
--

INSERT INTO `documento_det` (`cn_item`, `cn_serie`, `cn_numero`, `pro_id`, `cant`, `precio`, `importe`) VALUES
('1', 'F011', '0000009', 1, 1, '850.00', '980.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ppto`
--

CREATE TABLE IF NOT EXISTS `ppto` (
  `fecha` date NOT NULL,
  `usu_id` int(11) NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ppto`
--

INSERT INTO `ppto` (`fecha`, `usu_id`, `monto`, `fecha_reg`) VALUES
('2017-06-01', 14, '500.00', '2017-06-20 23:49:34'),
('2017-06-02', 14, '1200.00', '2017-06-21 00:54:16'),
('2017-06-02', 16, '1000.00', '2017-06-21 00:43:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
`pro_id` int(11) NOT NULL,
  `pro_descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `pro_activo` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`pro_id`, `pro_descripcion`, `pro_activo`) VALUES
(1, 'APLICACIONES MOVILES', 1),
(2, 'APLICACIONES WEBs', 1),
(3, 'MARKETING SOCIAL', 1),
(4, 'PAGINA WEB', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `call_comentario`
--
ALTER TABLE `call_comentario`
 ADD PRIMARY KEY (`com_id`), ADD FULLTEXT KEY `emp_id` (`emp_id`), ADD FULLTEXT KEY `usu_id` (`usu_id`);

--
-- Indices de la tabla `call_config`
--
ALTER TABLE `call_config`
 ADD PRIMARY KEY (`conf_id`);

--
-- Indices de la tabla `call_empresa`
--
ALTER TABLE `call_empresa`
 ADD PRIMARY KEY (`emp_id`);

--
-- Indices de la tabla `call_estado`
--
ALTER TABLE `call_estado`
 ADD PRIMARY KEY (`est_id`);

--
-- Indices de la tabla `call_obs`
--
ALTER TABLE `call_obs`
 ADD PRIMARY KEY (`obs_id`);

--
-- Indices de la tabla `call_pedido`
--
ALTER TABLE `call_pedido`
 ADD PRIMARY KEY (`nro_pedido`);

--
-- Indices de la tabla `call_pedido_det`
--
ALTER TABLE `call_pedido_det`
 ADD PRIMARY KEY (`nro_pedido`,`pro_id`);

--
-- Indices de la tabla `call_perfil`
--
ALTER TABLE `call_perfil`
 ADD PRIMARY KEY (`per_id`);

--
-- Indices de la tabla `call_registro`
--
ALTER TABLE `call_registro`
 ADD PRIMARY KEY (`reg_id`);

--
-- Indices de la tabla `call_usuario`
--
ALTER TABLE `call_usuario`
 ADD PRIMARY KEY (`usu_id`,`per_id`), ADD KEY `per_id` (`per_id`);

--
-- Indices de la tabla `call_visitas`
--
ALTER TABLE `call_visitas`
 ADD PRIMARY KEY (`vis_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
 ADD PRIMARY KEY (`cli_id`), ADD UNIQUE KEY `nro_doc_3` (`nro_doc`), ADD UNIQUE KEY `nro_doc_4` (`nro_doc`), ADD KEY `nro_doc` (`nro_doc`), ADD KEY `nro_doc_2` (`nro_doc`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
 ADD PRIMARY KEY (`cn_serie`,`cn_numero`);

--
-- Indices de la tabla `documento_det`
--
ALTER TABLE `documento_det`
 ADD PRIMARY KEY (`cn_numero`);

--
-- Indices de la tabla `ppto`
--
ALTER TABLE `ppto`
 ADD PRIMARY KEY (`fecha`,`usu_id`), ADD KEY `usu_id` (`usu_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
 ADD PRIMARY KEY (`pro_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `call_comentario`
--
ALTER TABLE `call_comentario`
MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `call_config`
--
ALTER TABLE `call_config`
MODIFY `conf_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `call_empresa`
--
ALTER TABLE `call_empresa`
MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `call_estado`
--
ALTER TABLE `call_estado`
MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `call_obs`
--
ALTER TABLE `call_obs`
MODIFY `obs_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `call_pedido`
--
ALTER TABLE `call_pedido`
MODIFY `nro_pedido` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `call_perfil`
--
ALTER TABLE `call_perfil`
MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `call_registro`
--
ALTER TABLE `call_registro`
MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `call_usuario`
--
ALTER TABLE `call_usuario`
MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `call_visitas`
--
ALTER TABLE `call_visitas`
MODIFY `vis_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `call_usuario`
--
ALTER TABLE `call_usuario`
ADD CONSTRAINT `call_usuario_ibfk_2` FOREIGN KEY (`per_id`) REFERENCES `call_perfil` (`per_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ppto`
--
ALTER TABLE `ppto`
ADD CONSTRAINT `ppto_ibfk_1` FOREIGN KEY (`usu_id`) REFERENCES `call_usuario` (`usu_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
