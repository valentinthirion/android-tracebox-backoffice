<?php
    /*
		Tracebox for Android has been developed by Valentin THIRION
		in the context of his Master Thesis
		at the University of Liege (ULg) in Belgium in june 2014.
		This work has been partially funded by the
		European Commission funded mPlane ICT-318627 project
		(http://www.ict-mplane.eu).

		All information, copyrights and code about
		this project can be found at: www.androidtracebox.com
	*/    

    ?>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Download results
                    <small><?php echo "in mode " . $_GET['mode']; ?></small>
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header">
                            </div><!-- /.box-header -->
                            <div class="box-body pad table-responsive">
                            	<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=5');">Raw</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=6');">Path length</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=7');"># of mods per path</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=8');">Carriers</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=9');">Duration</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=10');">Distinct routers</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=11');">Non modified probes</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=12');">Locations</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=13');">Connection modes</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=14');">Mods location</button>
                            </div>
                            <div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=1');">Hops per probe</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=2');">Modifications</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=3');">Modifications distribution</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=export&mode=4');">Modifications distribution by network</button>
								<div class="box-body">
	                            <?php	                            	
	                                $exportText;
	                                $nb = 0;
	                            
	                                if (isset($_GET['mode']))
	                                {
	                                    switch ($_GET['mode'])
	                                    {
	                                        case 1:
	                                            $exportText = "probe_id, nbRouters, network\n";
	                                            $data = getNumberOfProbesWithSameNumberOfRouters();
	                                            $nb = mysql_num_rows($data);
	                                            while ($p = mysql_fetch_array($data))
	                                            {
	                                                $exportText .= $p['id'] . ", " . $p['nbRouters'] . ", " . $p['connectivityMode'] . "\n";
	                                            }
	                                            break;
	                                        case 2:
	                                            $exportText = "destination, connectivity, # hops, # mods, mods\n";
	                                            $data = getProbes("");
	                                            $nb = mysql_num_rows($data);
	                                            while ($p = mysql_fetch_array($data))
	                                            {
	                                                $nbOfRouters = getRoutersCountForProbeID($p['id']);
	                                                $nbOfModif = getPacketModificationsCountForProbeID($p['id']);
	                                                $exportText .= $p['destination'] . ", " . $p['connectivityMode'] . ", " . $nbOfRouters . ", " . $nbOfModif;
	                                                // Add the modifications
	                                                $modifs = getModificationsForProbe($p['id']);
	                                                while ($m = mysql_fetch_array($modifs))
	                                                {
	                                                    $exportText .= ", " . $m['layer'] . "::" . $m['field'];
	                                                }
	                                
	                                                $exportText .= "\n";
	                                            }
	                                            break;
	                                        case 3:
	                                            $exportText = "layer::field, #\n";
	                                            $data = getPacketModificationsDistribution();
	                                            $nb = mysql_num_rows($data);
	                                            while ($m = mysql_fetch_array($data))
	                                            {
	                                                $exportText .= $m['layer'] . "::" . $m['field'] . ", " . $m['count'] . "\n";
	                                            }
	                                            break;
	                                        case 4:
	                                            $exportText = "network, layer::field, #\n";
	                                            $data = getPacketModificationsDistributionByNetworkMode();
	                                            $nb = mysql_num_rows($data);
	                                            while ($m = mysql_fetch_array($data))
	                                            {
	                                                $exportText .= $m['connectivityMode'] . ", " . $m['layer'] . "::" . $m['field'] . ", " . $m['count'] . "\n";
	                                            }
	                                            break;

	                                        case 5: // RAW
	                                            $exportText = "probe_id, layer, field, ttl, address, destination, location, starttime, endtime, connectivityMode, carrierType, carrierName, batteryUsage\n";
	                                            $data = getRawProbeResult();
	                                            $nb = mysql_num_rows($data);
	                                            while ($m = mysql_fetch_array($data))
	                                            {
	                                                $exportText .= $m['pID'] . ", " . $m['layer'] . ", " . $m['field'] . ", " . $m['ttl'] . ", " . $m['address'] . ", " . $m['destination'] . ", " . $m['location'] . ", " . $m['starttime'] . ", " . $m['endtime'] . ", " . $m['connectivityMode'] . ", " . $m['carrierType'] . ", " . $m['carrierName'] . ", " . $m['batteryUsage'] . "\n";
	                                            }
	                                            break;

											case 6: // PATH LENGTHS
												$exportText = "dest, length\n";
												$data = getProbesWithNumberOfRouters("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
													$exportText .= $p['id'] . ", " . $p['destination'] . ", " . $p['rNumber'] . "\n";
												}
												break;

											case 7: // Mods per probe
												$exportText = "# of mods\n";
												$data = getProbes("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
													$exportText .= getPacketModificationsCountForProbeID($p['id']) . "\n";
												}
												break;

											case 8: // Carriers
												$exportText = "carrier name (carrier type), #\n";
												$data = getCarriersAndTypes();
												$nb = mysql_num_rows($data);
												while ($r = mysql_fetch_array($data))
												{
													$exportText .= $r['carrierName'] . " (" . $r['carrierType'] . "), " . $r['count'] . "\n";
												}
												break;

                                            case 9: // Probe duration
                                                $exportText = "duration in seconds\n";
                                                $data = getProbes("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
												    $duration = $p['endtime'] - $p['starttime'];
												    if ($duration > 0);
    													$exportText .= $duration . "\n";
												}
                                                break;

                                            case 10: // Distinct routers
                                                $exportText = "ip\n";
                                                $data = getDistinctRouters();
                                                $nb = mysql_num_rows($data);
												while ($r = mysql_fetch_array($data))
												{
												    $exportText .= $r['address'] . "\n";
												}
                                                break;

                                            case 11: // Null probes per network
                                                $exportText = "conMode, carrier name, carrier type\n";
												$data = getProbes("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
												    $n = getPacketModificationsCountForProbeID($p['id']);
												    if ($n == 0)
    													$exportText .= $p['connectivityMode'] . ", " . $p['carrierName'] . ", " . $p['carrierType'] . "\n";
												}
												break;	

                                            case 12: // Probes location
                                                $exportText = "id, #, lat, long\n";
												$data = getProbesLocation("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
												    $c = split("/", $p['location']); 
												    $exportText .= $p['id'] . ", " . $p['count'] . ", " . $c[0] . ", " . $c[1]  . "\n"; //converCoordinatesToLocale($p['location'])
                                                }
                                                break;

                                            case 13: // Connection modes
                                                $exportText = "mode, #\n";
												$data = getConnetionModes("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
												    $m = "WiFi";
												    if ($p['connectivityMode'] == 0)
												        $m = "cellular";
												    $exportText .= $m . ", " . $p['count'] . "\n";
                                                }
                                                break;

                                            case 14: // Mods location
                                                $exportText = "1st mod\n";
												$data = getProbes("");
												$nb = mysql_num_rows($data);
												while ($p = mysql_fetch_array($data))
												{
												    $routers = getRoutersForProbeID($p['id']);
                                                    $nbOfRouters = mysql_num_rows($routers);
                                                    // Get the first mod and print the ttl on 10                                              
												    while ($r = mysql_fetch_array($routers))
												    {
    												    $pmNum = getPacketModificationsCountForRouterID($r['id']);
    												    if ($pmNum > 0)
    												    {
        												    $i = round(($r['ttl'] / $nbOfRouters) * 10, 1);
        												    $exportText .= $i . "\n";
        												    break;
    												    }
												    }
                                                }
                                                break;
	                                    }
	                                }
	                                ?>
									<div class="form-group">
                                    <label>Exported data</label>
                                    <textarea class="form-control" rows="15"><?php echo $exportText; ?></textarea>
                                </div>
                                <?php echo $nb . " entries"; ?>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                            </div>
                        </div><!-- /.box -->
                    </div><!--/.col (right) -->
                </div>   <!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    <?php


?>