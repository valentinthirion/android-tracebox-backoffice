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

    if (isset($_GET['action']))
    {
        switch ($_GET['action'])
        {
            case "list":
                showProbesList();
                break;
            case "inspect":
                showProbeInspector($_GET['id']);
                break;
            case "delete":
                showProbesList();
                break;
            case "probes_for_destination":
            	showProbesForDestination($_GET['destination_id']);
            	break;
            default:
                include("404.php");
                break;
        }
    }
    else
        showProbesList();

    function showProbesList()
    {
        $message = array();
        if (isset($_GET['action']) && ($_GET['action'] == "delete"))
            $message = showDeleteProbe($_GET['id']);

        ?>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">                
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Probes
                    <small>resulting from tracebox</small>
                </h1>
            </section>
        
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                            	<?php
                            		$count = getProbesCount();
                            		if ($count > 500)
                            			echo "<h3 class=\"box-title\">Showing the last non-null 500 probes (" . $count . " probes)</h3>";
                            		else if ($count > 0)
                            			echo "<h3 class=\"box-title\">Showing the last non-null among " . $count . " probes</h3>";
                            	?>
                            </div><!-- /.box-header -->
                            <div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=probes');">Download all probes (XML)</button>
							</div> 
                            <?php
                                if (isset($message) && $message[1] != "")
                                {
                                     ?>
                                        <div class="alert <?php echo $message[0]; ?>">
                                            <i class="fa fa-ban"></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <?php echo $message[1]; ?>
                                        </div>
                                    <?php
                                }
                            ?>
                            <div class="box-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Duration</th>
                                            <th>Destination</th>
                                            <th>Location</th>
                                            <th>Connectivity mode</th>
                                            <th># of routers</th>
                                            <th>Modifications</th>
                                            <th>Battery usage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $probes = getProbes(500);
        
                                            while ($probe = mysql_fetch_array($probes))
                                            {
                                                $d = getDestinationForIP($probe['destination']);
                                                $destination = $d['name'];
                                                if ($destination == null)
                                                    $destination = $probe['destination'];

												$nbOfRouters = getRoutersCountForProbeID($probe['id']);

												$nbOfModif = getPacketModificationsCountForProbeID($probe['id'], false);
                                                ?>
                                                    <tr>
                                                        <td><?php echo date("d/m/Y H:i", $probe['starttime']); ?></td>
                                                        <td><?php echo ($probe['endtime'] - $probe['starttime']) . "s"; ?></td>
                                                        <td><a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['address']; ?>"><?php echo $probe['destination'] . " (" . $destination . ")"; ?></a></td>
                                                        <td>
                                                        	<?php $loc = split("/", $probe['location']); echo number_format($loc[0], 4) . "/" . number_format($loc[1], 4); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                if ($probe['connectivityMode'] == 1)
                                                                    echo "WIFI";
                                                                elseif ($probe['connectivityMode'] == 0)
                                                                    echo $probe['carrierType'] . " (" . $probe['carrierName'] . ")";
                                                                elseif ($probe['connectivityMode'] == 7)
                                                                	echo "BLUETOOTH";
                                                                else
                                                                    echo $probe['connectivityMode'];
                                                            ?>
                                                        </td>
                                                        <td><?php echo $nbOfRouters ?></td>
                                                        <td><?php echo $nbOfModif ?></td>
                                                        <td><?php echo $probe['batteryUsage']; ?>%</td>
                                                        <td>
                                                            <a href="index.php?page=probes&action=inspect&id=<?php echo $probe['id']; ?>"><i class="fa fa-fw fa-eye"></i></a>
                                                            <a href="index.php?page=probes&action=delete&id=<?php echo $probe['id']; ?>"><i class="fa fa-fw fa-ban"></i></a>
                                                            <a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['address']; ?>"><i class="fa fa-fw fa-road"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Duration</th>
                                            <th>Destination</th>
                                            <th>Location</th>
                                            <th>Connectivity mode</th>
                                            <th># of routers</th>
                                            <th>Modifications</th>
                                            <th>Battery usage</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                </div>
        
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
        <?php
    }

    function showProbeInspector($id)
    {
        if (!is_numeric($id))
            showProbesList();

        $probe = getProbeForID($id);
        if ($probe == null)
            showProbesList();

        $d = getDestinationForIP($probe['destination']);
        $destination = $d['name'];
        if ($destination == null)
            $destination = $probe['destination'];

        ?>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Inspect
                        <small>a probe</small>
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
                                    <i class="fa fa-fw fa-chevron-left" onclick="window.history.back()"></i>
                                    <h3 class="box-title"><?php echo $destination . " - " . date("d/m/Y H:i", $probe['starttime']); ?></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h4>Informations</h4>
                                    <p>
                                        <ul>
                                            <li>Connection mode:
                                                <?php
                                                    if ($probe['connectivityMode'] == 1)
                                                        echo "WIFI";
                                                    elseif ($probe['connectivityMode'] == 0)
                                                        echo $probe['carrierType'] . " (" . $probe['carrierName'] . ")";
                                                ?>
                                            </li>
                                            <li>Duration: <?php echo ($probe['endtime'] - $probe['starttime']) . "s"; ?></li>
                                            <li>Battery usage: <?php echo $probe['batteryUsage']; ?>%</li>
                                            <li>Location: <?php $loc = split("/", $probe['location']); echo number_format($loc[0], 4) . "/" . number_format($loc[1], 4); ?></li>
                                        </ul>
                                    </p>
                                    <h4>Command executed on the device</h4>                             
                                    <p class="margin">
                                        <code>$ su busybox tracebox <?php echo $probe['destination']; ?></code>
                                    </p>

                                    <h4>Result</h4>
                                    <p class="margin">
                                        <code>
                                            <?php
                                                $routers = getRoutersForProbeID($probe['id']);
                                                while ($router = mysql_fetch_array($routers))
                                                {
                                                    echo $router['ttl'] . " " . $router['address'] . " ";

                                                    $packetmodifications = getPacketModificationsForRouterID($router['id']);
                                                    while ($packetmodification = mysql_fetch_array($packetmodifications))
                                                    {
                                                        echo $packetmodification['layer'] . "::" . $packetmodification['field'] . " ";
                                                    }
                                                    
                                                    echo "<br />";
                                                }
                                            ?>
                                        </code>
                                    </p>

                                    <h4>Other information</h4>

                                    <h4>Action</h4>
                                    <p class="margin">
                                        <a href="index.php?page=probes&action=delete&id=<?php echo $probe['id']; ?>"><i class="fa fa-fw fa-ban"></i></a>
                                        <a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['address']; ?>"><i class="fa fa-fw fa-road"></i></a>
                                    </p>

                                    <h4>Export</h4>
                                    <div class="form-group">
                                        <!--<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=probes');">Download all probes (XML)</button>-->
                                    </div>
                                </div><!-- /.box-body -->

                                <div class="box-footer">
                                </div>
                            </div><!-- /.box -->
                        </div><!--/.col (right) -->
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        <?php
    }

    function showDeleteProbe($probe_id)
    {
        if ($probe_id != "")
        {
            if (deleteprobe($probe_id))
                return array("alert-success", "The probe has been deleted.");
            else
                return array("alert-danger", "There was a problem deleting the probe.");
        }
            
    }

	function showProbesForDestination($destinationID)
	{
		$destination = getDestinationForID($destinationID);

		$probes = getProbesForDestination($destination['address']);

		while ($probe = mysql_fetch_array($probes))
		{
			showProbeInspector($probe['id']);
		}
	}
?>