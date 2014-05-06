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
                showDestinationsList();
                break;
            case "add":
                showDestinationForm();
                break;
            case "delete":
                showDestinationsList();
                break;
            default:
                include("404.php");
                break;
        }
    }
    else
        showDestinationsList();
?>

<?php
    function showDestinationsList()
    {
        $message = array();
        if (isset($_GET['action']) && ($_GET['action'] == "delete"))
            $message = showDeleteDestination($_GET['id']);

        ?>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">                
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Manage destinations
                    <small>used by tracebox probes</small>
                </h1>
            </section>
        
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Destinations</h3>                                    
                            </div><!-- /.box-header -->
                            <div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=destinations&format=CSV');">Download all destinations (CSV)</button>
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=destinations&format=XML');">Download all destinations (XML)</button>
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
                                            <th>Rank</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th># of probes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $destinations = getDestinations();
        
                                            while ($destination = mysql_fetch_array($destinations))
                                            {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $destination['id']; ?></td>
                                                        <td><a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['address']; ?>"><?php echo $destination['name']; ?></a></td>
                                                        <td><a href="<?php echo $destination['address']; ?>" target="_blank"><?php echo $destination['address']; ?></a></td>
                                                        <td>0</td>
                                                        <td>
                                                            <a href="index.php?page=destinations&action=delete&id=<?php echo $destination['id']; ?>"><i class="fa fa-fw fa-ban"></i></a>
                                                            <a href="index.php?page=probes&action=probes_for_destination&destination_id=<?php echo $destination['id']; ?>"><i class="fa fa-fw fa-road"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th># of probes</th>
                                            <th>Actions</th>
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

    function showDestinationForm()
    {
        $check = true;
        $message = array();

        // Check if a new destination has been enterred
        if (isset($_POST['addDestination']) && ($_POST['addDestination'] == "true"))
        {
            if ($_POST['name'] == "")
            {
                $check = false;
                $message = array("alert-danger", "You have to provide a name.");
            }
            if ($check && $_POST['address'] == "")
            {
                $check = false;
                $message = array("alert-danger", "You have to provide an IP or an URL");
            }
            $IP = $_POST['address'];
            if ($check && !ip2long($_POST['address']))
            {
                $IP_2 = gethostbyname($IP);
                if ($IP_2 == $IP)
                {
                    $check = false;
                    $message = array("alert-danger", "The given IP/URL is not valid.");
                }
                else
                    $IP = $IP_2;
            }
                

            if ($check)
            {
                if (addNewDestination($_POST['name'], $IP))
                    $message = array("alert-success", "The new destination has been added to the list.");
                else
                {
                    $message = array("alert-danger", "There was an error while saving the destination, please try again.");
                    $check = false;
                }
            }
        }
        ?>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Add a new destination
                        <small>to be probed</small>
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
                                    <h3 class="box-title">Information</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form role="form" action="" method="POST">
                                    <div class="box-body">
                                        <?php
                                            if (isset($message) && $message[1] != "")
                                            {
                                                 ?>
                                                    <div class="alert <?php echo $message[0]; ?>">
                                                        <i class="fa fa-ban"></i>
                                                        <?php echo $message[1]; ?>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                        
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="Enter the destination name" value="<?php if (!$check) echo $_POST['name']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input name="address" type="text" class="form-control" id="address" placeholder="IP / URL" value="<?php if (!$check) echo $_POST['address']; ?>">
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
                                        <input type="hidden" name="addDestination" value="true" />
                                        <button type="submit" class="btn btn-primary" onclick="this.submit()">Submit</button>
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div><!--/.col (right) -->
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        <?php
    }

    function showDeleteDestination($destination_id)
    {
        if ($destination_id != "")
        {
            if (deleteDestination($destination_id))
                return array("alert-success", "The destination has been deleted.");
            else
                return array("alert-danger", "There was a problem deleting the destination.");
        }
            
    }

?>