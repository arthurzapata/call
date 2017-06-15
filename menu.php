                      <li class="dropdown notifications-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i> Registros</a>
                            <ul class="dropdown-menu">
                            <li>
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu" style="overflow: hidden; width: 100%;">
                                      <li>
                                         <a href="producto.php"><i class="fa fa-briefcase danger"></i> Productos</a>
                                       </li>
                                       <li>
                                            <a href="cliente.php"><i class="fa fa-exchange success"></i> Clientes</a>
                                        </li>
                                        <li>
                                            <a href="visitas.php"><i class="fa fa-briefcase"></i> Visitas</a>
                                        </li>
                                        <li>
                                            <a href="estado.php"><i class="fa fa-retweet bg-orange"></i> Estados</a>
                                        </li>
                                           <li>
                                             <a href="coments.php"><i class="fa fa-comment bg-blue"></i> Comentarios</a>
                                        </li>
                                    </ul>
                             </div>
                             </li>
                            </ul>
                       </li>
                       <!--End Menu reg-->
                          <li class="dropdown notifications-menu">
                            <?php if ($perid != 2) { ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gear"></i> Procesos
                                <!--<span class="label label-warning">10</span>-->
                            </a>
                            <?php } ?>
                            <ul class="dropdown-menu">
                                <!--<li class="header">You have 10 notifications</li>-->
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                    <ul class="menu" style="overflow: hidden; width: 100%;">

                                     <?php if ($perid == 1 or $perid == 4) { ?>
                                        <li>
                                            <a href="derivar.php">
                                                <i class="fa fa-exchange"></i> Derivar
                                            </a>
                                        </li>
                                      <?php } ?>

                                        <?php if ($perid == 3) { ?>
                                        
                                         <li>
                                            <a href="index.php">
                                                <i class="fa fa-gears success"></i> Seguimiento Llamadas
                                            </a>
                                        </li>
                                        <li>
                                            <a href="importar.php">
                                                <i class="fa fa-upload danger"></i> Importar Seguimiento
                                            </a>
                                        </li>
                                        <!--<li>
                                            <a href="pedido.php">
                                                <i class="fa fa-align-center mailbox bg-maroon"></i> Pedidos
                                            </a>
                                        </li>-->
                                        <li>
                                            <a href="documentos.php">
                                                <i class="fa fa-align-center mailbox bg-black"></i> Comprobantes
                                            </a>
                                        </li>

                                        <?php } ?>

                                     
                                    </ul><div class="slimScrollBar" style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; z-index: 99; right: 1px; height: 156.86274509803923px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
                                </li>
                                <li class="footer"><!--<a href="#">View all</a>--></li>
                            </ul>
                        </li>
                        <!-- Menu reg-->
                      <li class="dropdown notifications-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i> Reportes</a>
                            <ul class="dropdown-menu">
                            <li>
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu" style="overflow: hidden; width: 100%;">
                                        <li>
                                            <a href="rpt.php"><i class="fa fa-briefcase"></i> Seguimiento Llamadas</a>
                                        </li>
                                        <li>
                                            <a href="rpt_ventas.php"><i class="fa fa-retweet bg-orange"></i> Ventas </a>
                                        </li>
                                           <li>
                                             <a href="#.php"><i class="fa fa-comment bg-blue"></i> Ventas por Vendedor</a>
                                        </li>
                                    </ul>
                             </div>
                             </li>
                            </ul>
                       </li>
                       <!--End Menu reg-->
                       <li class="dropdown notifications-menu">
                            <?php if ($perid != 2) { ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gear"></i> Configuración
                                <!--<span class="label label-warning">10</span>-->
                            </a>
                            <?php } ?>
                            <ul class="dropdown-menu">
                                <!--<li class="header">You have 10 notifications</li>-->
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                    <ul class="menu" style="overflow: hidden; width: 100%;">

                                     <?php if ($perid == 1 or $perid == 4) { ?>
                                        <li>
                                            <a href="derivar.php">
                                                <i class="fa fa-exchange"></i> Derivar
                                            </a>
                                        </li>
                                      <?php } ?>

                                        <?php if ($perid == 3) { ?>
                                        
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

                                        <?php } ?>
                                        <li>
                                            <a href="usuario.php">
                                                <i class="fa fa-users danger"></i> Usuarios
                                            </a>
                                        </li>
                                     
                                    </ul>
                                    <div class="slimScrollBar" style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; z-index: 99; right: 1px; height: 156.86274509803923px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
                                </li>
                                <li class="footer"><!--<a href="#">View all</a>--></li>
                            </ul>
                        </li>
