-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2017 a las 21:18:29
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
-- Estructura de tabla para la tabla `call_curso`
--

CREATE TABLE IF NOT EXISTS `call_curso` (
`cur_id` int(11) NOT NULL,
  `cur_nombre` varchar(50) DEFAULT NULL,
  `cur_activo` int(1) DEFAULT NULL,
  `cur_descripcion` longblob,
  `cur_remitente` varchar(100) DEFAULT NULL,
  `cur_asunto` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_curso`
--

INSERT INTO `call_curso` (`cur_id`, `cur_nombre`, `cur_activo`, `cur_descripcion`, `cur_remitente`, `cur_asunto`) VALUES
(29, 'MARKETING DIGITAL', 1, 0x0d0a, NULL, NULL),
(30, 'APLICACION MOVIL', 1, 0x0d0a, NULL, NULL),
(31, 'APLICACION WEB', 1, 0x0d0a, NULL, NULL),
(32, 'aaaa', 1, 0x0d0a3c7020636c6173733d224d736f4e6f726d616c223e3c7370616e206c616e673d22454e2d5553223e446574616c6c6520646520726574656e6369c3b36e3a20546f74616c657320646562656e2065737461720d0a637561647261646f73207920656e63616a6f6e61646f730d0a09093c6f3a703e3c2f6f3a703e3c2f7370616e3e3c2f703e0d0a0d0a0d0a3c7020636c6173733d224d736f4e6f726d616c223e3c7370616e206c616e673d22454e2d5553223e266e6273703b3c2f7370616e3e3c2f703e0d0a0d0a0d0a3c7020636c6173733d224d736f4e6f726d616c223e0d0a093c212d2d0d0a095b69662067746520766d6c20315d3e3c763a6f76616c2069643d22456c697073655f78303032305f3222206f3a737069643d225f78303030305f7331303236220d0a207374796c653d2727706f736974696f6e3a6162736f6c7574653b6d617267696e2d6c6566743a3233362e3770743b6d617267696e2d746f703a3233342e3470743b77696474683a3232322e373570743b0d0a206865696768743a343270743b7a2d696e6465783a3235313635393236343b7669736962696c6974793a76697369626c653b6d736f2d777261702d7374796c653a7371756172653b0d0a206d736f2d777261702d64697374616e63652d6c6566743a3970743b6d736f2d777261702d64697374616e63652d746f703a303b6d736f2d777261702d64697374616e63652d72696768743a3970743b0d0a206d736f2d777261702d64697374616e63652d626f74746f6d3a303b6d736f2d706f736974696f6e2d686f72697a6f6e74616c3a6162736f6c7574653b0d0a206d736f2d706f736974696f6e2d686f72697a6f6e74616c2d72656c61746976653a746578743b6d736f2d706f736974696f6e2d766572746963616c3a6162736f6c7574653b0d0a206d736f2d706f736974696f6e2d766572746963616c2d72656c61746976653a746578743b762d746578742d616e63686f723a6d6964646c652727206f3a676678646174613d225545734442425141426741494141414149514337355569554251454141423443414141544141414157304e76626e526c626e526656486c775a584e644c6e6874624b5352765537444d4253460d0a6479546577664b4b4571634d434b456d4866675a67614538774d572b5353776332374a76532f7632334b544a676b6f584673752b502b63374f6c3576446f4d5465307a5a426c2f4c56566c4a0d0a675634485933315879342f745333457652536277426c7a77574d736a5a726c7072712f57323250454c486a623531723252504642716178374843435849614c6e54687653414d5450314b6b490d0a2b677336564c6456646164303849536543686f315a4c4e2b77685a326a73547a6763736e4a776c646c754c784e44697961676b784f717542324b6e61652f4f4c557379456b6a656e6d647a620d0a6d472f59686c526e4357506e62384338393862524a47745176454f6956786a5968744c4f7873384179536954344a75447973746c5656345750654d36744b335661494c65445a78494f5373750d0a74692f6a69644e474e5a332f4a3038794331644e76397638414141412f2f38444146424c417751554141594143414141414345417254412f38634541414141794151414143774141414639790d0a5a57787a4c7935795a57787a68492f4e437349774549547667753851396d375465684352707232493446583041645a6b327762624a47546a3339756269364167654a746c3247396d367659780d0a6a654a476b613133437171694245464f65324e64722b423033433357494469684d7a683652777165784e413238316c396f42465466754c424268615a346c6a426b464c59534d6c366f416d350d0a38494663646a6f664a307a356a4c304d71432f596b3179573555724754775930583079784e777269336c51676a732b516b2f2b7a6664645a5456757672784f3539434e436d6f6a337643776a0d0a4d666155464f6a5268725048614e345776305656354f59676d31702b4c573165414141412f2f38444146424c417751554141594143414141414345415673333877764d4341414434426741410d0a4877414141474e7361584269623246795a43396b636d46336157356e6379396b636d46336157356e4d5335346257797356643950327a415166702b302f3848794f37514e4c5a534b674c70530d0a30435145465758692b656f3454545448396d773374507672642b656b7447507342397465456a7433392f6d373738365873347431705667746e532b4e546e6e76734d755a314d4a6b7056366d0d0a2f4e5044316347514d7839415a36434d6c696e66534d38767a742b2f4f345052306f45745373455151667352704c7749775934364853384b5759452f4e465a71744f584756524277363561640d0a7a4d4554496c65716b3353377835304b53733350643143584549437458506b58554d71497a7a4b62674b3742493651536f2f30764c55636c2f68305a5272712b646e5a755a343659693974360d0a356c695a7052795630314368524c7a54476c6f3333485a6552433133414f7663566552763870797455393776446f2b545a4d445a427466392f7242334d6d6a7735446f776751374a4d426d650d0a6b6f4e416a38485255622f6262513873376e344449597270723047515a6b4d484633735576535743757634783532536238315356316b7557504f644f767476457433472b3165772f70667a4d0d0a466b62572b5841745463566f6b584b7049703359576c44662b4e417732587246624d7856715653736c4e4c734b65576e67796771324a546e43674c7157316d737174644c7a6b417438584b490d0a34434b694e36724d4b4a70775972504c69584b734270567945454c71454756416574393530756d58344976474d5a6f61745a785a365377794b53526b55353278734c485952786f76484364710d0a6c637734557849703043703642696a566e3367694361566a2f323346442b74354c476459667a445a686c4a593442743732426c5544707659573346564974636238474547447538316673514a0d0a456537776b53754468457937347177773775747233386b66377870614d514f6345796a6b6c7855347a456439314237563776583743427669706a38345358446a3969324c6659746556524f440d0a367659697537676b2f3643327939795a367447346245796e6f676d30774c4f626b725762536341396d6e416343546b65783755776c5956776f2b635742304d7646706571394c422b424766620d0a586770343857374e76414172582b756e78726670715045716d4c78736d3631526c517a4b68336e594b426b4c463757584f694e6c373146314254526f70542b5954616d7957432f30774f65750d0a50437376352f5a6569685a33577a39506b424665333873634a78444f686d37546e38497446395352546349496a326b76364e6b636f436941496e507334546647746945554c664d63536230780d0a2f6a6b6f6e6d2f304c72347174576e76462f302f666e616c3869616d61656c474172706f4a4e714c6d523964326e38552f566a32392b6666414141412f2f38444146424c41775155414159410d0a43414141414345417a6972355455514741414454475141414767414141474e7361584269623246795a433930614756745a533930614756745a5445756547317337466c4c62787333454c34580d0a36483959374c32785a4c317349334a6753334c63786b364353456d524937564c37544c6d4c68636b5a556533496a6e315571424157765451414c33315542514e3041414e65756d504d6543670d0a545839456839794853496d4b482f41684b434944787537734e3850687a4f77334a50666d7261634a395934784634536c5862392b6f2b5a374f413159534e4b6f367a386337583232345874430d0a6f6a52456c4b573436382b7738473974662f724a54625156554a4b4e47654c684b4d594a397342514b725a5131342b6c7a4c6257316b5141596952757341796e38477a436549496b33504a6f0d0a4c65546f42415a49364e703672645a655378424a2f5732774b4a57684159562f7152524b4546412b564761776c364945527238336d5a414161327834564663494d524d397972316a524c732b0d0a32417a5a7951672f6c6235486b5a44776f4f7658394d3966323736356872594b4a53705836427036652f70583642554b3464473648704e48343272515a725056624f3955396a5741796d58630d0a6f444e6f44397156505131415151417a7a58307862625a324e33663772514a7267504a4c682b312b70392b6f57336a44666d504a353532572b7250774770546262793768392f5a364545554c0d0a7230453576725745627a59373637326d68646567484e3965776e64714f2f316d78384a725545784a65725345727258616a56343532776f7959585466436439734e666336363458784f5171710d0a6f616f754e63534570584a567253586f43654e37414642416969524a50546e4c38415146554a4d39524d6d59452b2b41524c4655773641746a497a6e75536751537949316f696343546a4c5a0d0a39622f49554f6f626b4c4d33623036667654353939736670382b656e7a33347a725674362b79694e544c313350332f37373875767648392b2f2b6e64692b2f796f5266787773532f2f6658720d0a74332f2b3954377a38444c4e4a3376322f61753372312b642f66444e33372b3863466a6634576873776b636b77634b3769302b38427979424365726f325037674d622b6378696847784e54590d0a53534f425571524763646766794e6843333530686968793458577a48385245484d6e454262302b6657413450597a36567847487854707859774550473643376a7a696a6355574d5a5952354e0d0a303867394f4a2b6175416349486276473771485579764a676d67474c4570664a586f77744e2b39546c456f553452524c547a316a527867375a7665594543757568795467544c434a394234540d0a627863525a3068475a477856303178706e7953516c356e4c51636933465a7644523934756f36355a392f47786a5952334131474838794e4d72544465526c4f4a4570664a4555716f476641440d0a4a474f586b384d5a44307a6351456a49644951703877596846734b6c63342f446649326b33774569636166396b4d345347386b6c4f584c5a5045434d6d63672b4f2b72464b4d6c633243464a0d0a5978503775546943456b586566535a6438454e6d7679487148764b4130705870666b53776c65377a32654168634b6a7030727841314a4d70642b54794e6d5a572f51356e64494b77706871670d0a6549753545354b65532b5035434e6444344543545a7a2b2b64506838506154744e6d78462f4a4a3076634f4a3833335a587944705662684661753478487049506e356e37614a7265782f41790d0a4c4c656e6a3854386b5a6a392f7a30787233716672352b4f3577774d354b775767766d5357792f416b35587237776d686443686e4642384976515158304866435052417150623350784e562b0d0a4c4976685572334a4d494346697a6a534f68356e386b73693432474d4d6c692b3133316c4a424b463655683447524f7762645269703232467039506b6b49583574724e655631764d6e4477450d0a6b6e4e357256584a59637367633353374d39394b56656131743548653870594f4b4e334c4f47454d5a6a765263446a524b595571534871444455467a4f4b466e64693165624471383246446d0d0a793151746551477556566d42685a454879366d753332714343696a4276676c52484b6f3835616b7573367554655a325a5868564d71774a7163493552564d41383035764b313558545537504c0d0a532b30436d6261634d4d724e646b4a485276637745614d51463957707042647834374b353370796e31484a5068614b49686546475a2b4e39586c773131364333794130304e5a6d437074354a0d0a31323833576c4179416371362f6753323733435a5a4641375169316f455933673443755150482f6872384973475265796a3053634231795454733447435a47596535516b5856394e76306f440d0a54545748614e2f71363041494836787a6d3041724835707a6b485137795867797759453030323549564b547a573244346e437563543758363163464b6b30306833634d3450504847644d6f660d0a49436978567165754168675341576338395479614959466a7959724935765733304a674b326a58504258554e35584a45737867564863556b3878797571627879523939564d544475696a6c440d0a5149325146493177484b6b476177625636715a5631386839574e6c317a3164536b544e496339347a4c565a525864504e597459495a527459694f58566d727a6856526c69614a646d68382b700d0a653546794e3075755731676e56463043416c37467a3946314c394151444e666d67316d754b592b5861566878646947316530633577584e63753069544d46692f585a70646946765649357a440d0a6766424b6e522f3046717357524a4e7958616b6a376672456349677962787a56757a34633838507077314f346767384650736a576c5778647965414b54762b6858655248396c322f7543676c0d0a38447958564a68474b576d556d475970615a615356696c706c5a4a324b576e376e6a376268753870366c6a623938716a612b6868785646337362617776384e732f77634141502f2f417742510d0a53774d45464141474141674141414168414a786d526b4737414141414a41454141436f414141426a62476c77596d3968636d51765a484a6864326c755a334d7658334a6c62484d765a484a680d0a64326c755a7a4575654731734c6e4a6c62484f456a38304b776a4151684f2b433778443262744a36454a456d76596a5171395148434d6b324c54592f4a4648733278766f52554877736a437a0d0a3744657a546675794d336c69544a4e33484770614155476e764a3663345844724c37736a6b4a536c30334c32446a6b736d4b41563230317a78566e6d637054474b5352534b43357847484d4f0d0a4a3861534774484b52483141567a61446a31626d49714e68516171374e4d6a3256585667385a4d42346f744a4f73306864726f4730692b684a50396e2b324759464a3639656c68302b5563450d0a793655584671434d426a4d48536c646e6e5455745859474a686e33394a7434414141442f2f774d415545734241693041464141474141674141414168414c766c534a514641514141486749410d0a41424d41414141414141414141414141414141414141414141467444623235305a573530583152356347567a5853353462577851537745434c5141554141594143414141414345417254412f0d0a38634541414141794151414143774141414141414141414141414141414141324151414158334a6c62484d764c6e4a6c62484e51537745434c514155414159414341414141434541567333380d0a77764d4341414434426741414877414141414141414141414141414141414167416741415932787063474a7659584a6b4c32527959586470626d647a4c32527959586470626d63784c6e68740d0a6246424c415149744142514142674149414141414951444f4b766c4e52415941414e4d5a4141416141414141414141414141414141414141414641464141426a62476c77596d3968636d51760d0a6447686c625755766447686c625755784c6e68746246424c41514974414251414267414941414141495143635a6b5a42757741414143514241414171414141414141414141414141414141410d0a414d774c4141426a62476c77596d3968636d51765a484a6864326c755a334d7658334a6c62484d765a484a6864326c755a7a4575654731734c6e4a6c62484e515377554741414141414155410d0a4251426e415141417a777741414141410d0a222066696c6c65643d226622207374726f6b65636f6c6f723d2223656437643331205b333230355d222f3e3c215b656e6469665d0d0a092d2d3e0d0a090d0a093c212d2d0d0a095b69662021766d6c5d0d0a092d2d3e0d0a093c7370616e207374796c653d226d736f2d69676e6f72653a76676c61796f75743b706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a3235313635393236343b6d617267696e2d6c6566743a2033313570783b6d617267696e2d746f703a33313270783b77696474683a32393970783b6865696768743a35387078223e3c696d672077696474683d2232393922206865696768743d22353822207372633d2266696c653a2f2f2f433a2f55736572732f557365722f417070446174612f4c6f63616c2f54656d702f6d736f68746d6c636c6970312f30312f636c69705f696d6167653030312e706e672220763a7368617065733d22456c697073655f78303032305f3222202f3e3c2f7370616e3e0d0a093c212d2d0d0a095b656e6469665d0d0a092d2d3e0d0a090d0a093c212d2d0d0a095b69662067746520766d6c20315d3e3c763a7368617065747970650d0a2069643d225f78303030305f7437352220636f6f726473697a653d2232313630302c323136303022206f3a7370743d22373522206f3a70726566657272656c61746976653d2274220d0a20706174683d226d403440356c40344031314039403131403940357865222066696c6c65643d226622207374726f6b65643d2266223e0d0a203c763a7374726f6b65206a6f696e7374796c653d226d69746572222f3e0d0a203c763a666f726d756c61733e0d0a20203c763a662065716e3d226966206c696e65447261776e20706978656c4c696e6557696474682030222f3e0d0a20203c763a662065716e3d2273756d20403020312030222f3e0d0a20203c763a662065716e3d2273756d20302030204031222f3e0d0a20203c763a662065716e3d2270726f6420403220312032222f3e0d0a20203c763a662065716e3d2270726f6420403320323136303020706978656c5769647468222f3e0d0a20203c763a662065716e3d2270726f6420403320323136303020706978656c486569676874222f3e0d0a20203c763a662065716e3d2273756d20403020302031222f3e0d0a20203c763a662065716e3d2270726f6420403620312032222f3e0d0a20203c763a662065716e3d2270726f6420403720323136303020706978656c5769647468222f3e0d0a20203c763a662065716e3d2273756d2040382032313630302030222f3e0d0a20203c763a662065716e3d2270726f6420403720323136303020706978656c486569676874222f3e0d0a20203c763a662065716e3d2273756d204031302032313630302030222f3e0d0a203c2f763a666f726d756c61733e0d0a203c763a70617468206f3a657874727573696f6e6f6b3d226622206772616469656e7473686170656f6b3d227422206f3a636f6e6e656374747970653d2272656374222f3e0d0a203c6f3a6c6f636b20763a6578743d22656469742220617370656374726174696f3d2274222f3e0d0a3c2f763a7368617065747970653e3c763a73686170652069643d22496d6167656e5f78303032305f3122206f3a737069643d225f78303030305f69313032352220747970653d22235f78303030305f743735220d0a207374796c653d272777696474683a3434312e373570743b6865696768743a3236342e373570743b7669736962696c6974793a76697369626c653b6d736f2d777261702d7374796c653a73717561726527273e0d0a203c763a696d61676564617461207372633d2266696c653a2f2f2f433a557365727355736572417070446174614c6f63616c54656d706d736f68746d6c636c697031, 'aaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaa'),
(28, 'DESARROLLO DE PAGINA WEB', 1, 0x0d0a, NULL, NULL);

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
  `fecha_ped` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_pedido`
--

INSERT INTO `call_pedido` (`nro_pedido`, `cli_id`, `ped_estado`, `cc_vendedor`, `total`, `fecha_reg`, `fecha_ped`) VALUES
(1, 1, 1, 1, '1000.00', '2017-06-15 14:19:27', '2017-06-15 00:00:00'),
(2, 1, 1, 1, '50.00', '2017-06-15 14:19:27', '2017-06-08 00:00:00');

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
  `reg_derivado` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `call_registro`
--

INSERT INTO `call_registro` (`reg_id`, `reg_codigo`, `reg_fecha`, `est_id`, `cur_id`, `reg_fechareg`, `usu_id`, `reg_apellidos`, `reg_nombres`, `reg_formacion`, `reg_observaciones`, `reg_ciudad`, `reg_pais`, `reg_email`, `reg_telefono`, `reg_telefono2`, `emp_id`, `reg_direccion`, `reg_rubro`, `reg_derivado`) VALUES
(1, NULL, '2016-05-13', 1, 1, '2017-06-11 17:31:47', 6, 'VALDERRAMA ALFARO', 'INGRID MARYORY', 'I&A', 'asasas', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '956986610', '982539291', 1, NULL, NULL, NULL),
(2, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 5, 'ZAPATA VALDERRAMA', 'EMY FABIANA', 'ZSOLUTIONS', '2', NULL, 'SANTIAGO DE SURCO', 'arthuro_2004@hotmail.com', '22222222', '222222222222', 1, NULL, NULL, NULL),
(3, NULL, '2016-05-14', 5, 1, '2017-06-11 17:31:47', 5, 'ZAVALA', 'CHUKY', 'ZSOLUTIONS', '222                                  ', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '2', '222222222222', 1, NULL, NULL, NULL),
(4, NULL, '2016-05-14', 2, 1, '2017-06-11 17:31:47', 1, 'arturo', 't amo', 'ZSOLUTIONS', '33                 ', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '333', '33', 1, NULL, NULL, NULL),
(5, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 7, 'MORALES', 'RENDA', 'I&A', NULL, NULL, 'SANTIAGO DE SURCO', 'arthuro_2004@hotmail.com', '9998933', '982539291', 1, NULL, NULL, NULL),
(6, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 7, 'alfaro', 'roysi', 'ssdjdksjkj', NULL, NULL, 'jkjkkj', 'arthuro_2004@hotmail.com', '232323', NULL, 1, NULL, NULL, 1),
(7, NULL, '2016-05-14', 1, 1, '2017-06-11 17:31:47', 11, 'fernandez', 'luana', 'I&A', NULL, NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '956986610', '982539291', 1, NULL, NULL, 1),
(8, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 5, 'MORALES RODRIGUEZ', 'BRENDA', '<#', 'hola quisiera saber precio del curso', NULL, 'ASSLASAS', 'arthuro_2004@hotmail.com', '956986610', NULL, 1, NULL, NULL, 1),
(9, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 11, 'prueba1', '1', 'sss', '222', NULL, 'PERU', 'arthuro_2004@hotmail.com', '2222', '22', 1, NULL, NULL, 1),
(10, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 7, 'prueba 2', '2', '2', NULL, NULL, '2', 'arthuro_2004@hotmail.com', '2', '2', 1, NULL, NULL, 1),
(11, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 10, 'prueba 3', '3', 'ZSOLUTIONS', '3', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '3', '3', 1, NULL, NULL, 1),
(12, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 6, 'prueba 4', '4', 'ww', '333', NULL, 'www', 'arthuro_2004@hotmail.com', '333', '333', 1, NULL, NULL, 1),
(13, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 11, 'prueba 5', '5', 'aa', NULL, NULL, 'aaa', 'arthuro_2004@hotmail.com', '3', '2', 1, NULL, NULL, 1),
(14, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 7, 'prueba 6', '6', 'ssdjdksjkj', NULL, NULL, 'SSs', 'arthuro_2004@hotmail.com', '3', '3', 1, NULL, NULL, 1),
(15, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 10, 'prueba 7', '7', 'aaaa', '33', NULL, 'CHIMBOTE', 'arthuro_2004@hotmail.com', '333', '3', 1, NULL, NULL, 1),
(16, NULL, '2016-05-15', 1, 1, '2017-06-11 17:31:47', 1, 'prueba 8', '8', '8', '                 ', NULL, '8', 'arthuro_2004@hotmail.com', NULL, NULL, 1, NULL, NULL, 1),
(17, NULL, '2017-05-21', 1, 1, '2017-06-11 17:31:47', 5, 'TORRES GONZALES', 'BRYAN', 'Hola', 'DESEA EL DESARROLLO DE UNA WEB AUTOADMINISTRABLE, COTIZAR', NULL, 'Peru', 'chuyito@chistrese.com', '980989300', '980898944', 1, NULL, NULL, NULL),
(18, NULL, '2017-05-22', 1, 1, '2017-06-11 17:31:47', 5, 'KOOPMAN', 'SIMONE ANNA', 'SAP', 'HOLA SOY CHUCKY', NULL, 'Peru', 'skoopman@promelsa.com.pe', '980989300', NULL, 1, NULL, NULL, NULL),
(19, NULL, '2017-05-22', 1, 1, '2017-06-11 17:31:47', 5, 'KOOPMAN', 'SIMONE ANNA', 'SAP', 'HOLA SOY CHUCKY', NULL, 'Peru', 'skoopman@promelsa.com.pe', '980989300', NULL, 1, NULL, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
(11, 1, 'ZAM', 'zam', 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `call_visitas`
--

CREATE TABLE IF NOT EXISTS `call_visitas` (
`vis_id` int(11) NOT NULL,
  `vis_fecha` datetime NOT NULL,
  `vis_lugar` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `vis_cli` int(11) NOT NULL,
  `vis_tipovisita` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `hora_ini` time NOT NULL,
  `hora_fin` time NOT NULL,
  `usu_id` int(11) NOT NULL,
  `fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `motivo` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `call_visitas`
--

INSERT INTO `call_visitas` (`vis_id`, `vis_fecha`, `vis_lugar`, `vis_cli`, `vis_tipovisita`, `hora_ini`, `hora_fin`, `usu_id`, `fecha_reg`, `motivo`) VALUES
(1, '2017-05-01 23:18:32', 'LOS OLIVOS', 0, 'OFICINA', '00:00:00', '00:00:00', 5, '0000-00-00 00:00:00', ''),
(2, '2017-05-01 23:18:32', 'LOS OLIVOS', 0, 'OFICINA', '00:00:00', '00:00:00', 5, '0000-00-00 00:00:00', ''),
(3, '0000-00-00 00:00:00', 'san isidro', 1, 'opoerador', '12:00:00', '15:00:00', 1, '2017-06-04 03:12:17', 'kkkkkkkkkkk'),
(4, '2017-06-07 00:00:00', 'san isidro', 2, 'opoerador', '12:00:00', '15:00:00', 1, '2017-06-12 00:04:56', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cli_id`, `nro_doc`, `razon_social`, `direccion`, `telefono`, `email`, `fecha_reg`) VALUES
(1, '10468645381', 'ARTURO ZAPATA CARRETERO', 'JR. SAN GABINO 2323 SURCO', '943054727', 'azapata@promelsa.com.pe', '2017-06-12 04:36:46');

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
  `total` decimal(18,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`cn_serie`, `cn_numero`, `tipo_doc`, `nro_pedido`, `cc_cliente`, `cc_vta`, `cc_moneda`, `fecha_reg`, `cc_vendedor`, `total`) VALUES
('F011', '00001245', '01', 1, 10, 1, '01', '2017-06-06 02:47:53', 1, '118.00'),
('F021', '00001245', '01', 22, 22, 2, '01', '2017-06-07 02:47:37', 2, '338.00'),
('F021', '0009898', '01', 3232, 11, 2, '01', '2017-06-08 02:48:01', 1, '1180.00'),
('F021', '00031245', '01', 354554, 1, 2, '01', '2017-06-08 02:58:58', 2, '600.00');

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
  `precio` decimal(18,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
-- Indices de la tabla `call_curso`
--
ALTER TABLE `call_curso`
 ADD PRIMARY KEY (`cur_id`);

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
-- AUTO_INCREMENT de la tabla `call_curso`
--
ALTER TABLE `call_curso`
MODIFY `cur_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
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
MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `call_visitas`
--
ALTER TABLE `call_visitas`
MODIFY `vis_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;