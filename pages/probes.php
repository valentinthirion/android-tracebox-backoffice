<?php
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
                                <h3 class="box-title">Probes</h3>                                    
                            </div><!-- /.box-header -->
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
                                            <th>Destination</th>
                                            <th>Location</th>
                                            <th>Connectivity mode</th>
                                            <th># of routers</th>
                                            <th>Modifications</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $probes = getProbes();
        
                                            while ($probe = mysql_fetch_array($probes))
                                            {
                                                $d = getDestinationForIP($probe['destination']);
                                                $destination = $d['name'];
                                                if ($destination == null)
                                                    $destination = $probe['destination'];
                                                ?>
                                                    <tr>
                                                        <td><?php echo date("d/m/Y H:i", $probe['starttime']); ?></td>
                                                        <td><a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['address']; ?>"><?php echo $destination; ?></a></td>
                                                        <td><?php echo $probe['location']; ?></td>
                                                        <td>
                                                            <?php
                                                                if ($probe['connectivityMode'] == 1)
                                                                    echo "WIFI";
                                                                elseif ($probe['connectivityMode'] == 2)
                                                                    echo "CELLULAR";
                                                                else
                                                                    echo $probe['connectivityMode'];
                                                            ?>
                                                        </td>
                                                        <td><?php echo getRoutersCountForProbeID($probe['id']); ?></td>
                                                        <td>0</td>
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
                                            <th>Destination</th>
                                            <th>Location</th>
                                            <th>Connectivity mode</th>
                                            <th># of routers</th>
                                            <th>Modifications</th>
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

                                                    $packetmodifications = getPacketModificationsForRoiterID($router['id']);
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
                                        <select class="form-control">
                                            <option>XML</option>
                                            <option>CSV</option>
                                         </select>
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
?>