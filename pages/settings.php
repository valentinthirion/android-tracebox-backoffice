
<?php
	$message = array();

	if (isset($_GET['dropDestinations']) && ($_GET['dropDestinations'] == "true"))
	{
		deleteAllDestinations();
	}

	if (isset($_GET['dropProbes']) && ($_GET['dropProbes'] == "true"))
	{
		deleteAllProbes();
	}

	if (isset($_GET['dropAll']) && ($_GET['dropAll'] == "true"))
	{
		deleteAllDestinations();
		deleteAllProbes();
	}

	if ((isset($_GET['action'])) && ($_GET['action'] == "download"))
	{
		switch ($_GET['data'])
		{
			case "destinations":
				downloadDestinations($_GET['format']);
				break;
			case "probes":
				downloadProbes();
				break;
		}
	}

	if ((isset($_POST['changePassword'])) && ($_POST['changePassword'] == "true"))
	{
		if ($_POST['newpassword1'] == "")
			$message = array("alert-error", "The password could not be null");
		else
		{
			if ($_POST['newpassword1'] != $_POST['newpassword2'])
				$message = array("alert-error", "The given passwords don't match!");
			else
			{
				if (setPassword($_POST['oldpassword'], $_POST['newpassword1']))
					$message = array("alert-success", "The password has been changed!");
				else
					$message = array("alert-error", "The old password is not correct");
			}
		}
	}

	if ((isset($_POST['changeAPIPrefix'])) && ($_POST['changeAPIPrefix'] == "true"))
	{
		setAPIPrefix($_POST['apiprefix']);
	}
	
	?>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Settings
                    <small>of the backoffice</small>
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
                                <h3 class="box-title">Actions</h3>
                            </div><!-- /.box-header -->

							<div class="box-body pad table-responsive">
	                            <button class="btn btn-primary" onclick="confirmAndGo('Do you really want to drop the complete destination list?\n(this action is irreversible)', 'index.php?page=settings&dropDestinations=true');">Drop destinations</button>
							</div>
							<div class="box-body pad table-responsive" onclick="confirmAndGo('Do you really want to drop the complete probe list?\n(this action is irreversible)', 'index.php?page=settings&dropProbes=true');">
								<button class="btn btn-primary">Drop probes</button>
							</div>
							<div class="box-body pad table-responsive" onclick="confirmAndGo('Do you really want to drop the data?\n(this action is irreversible)', 'index.php?page=settings&dropAll=true');">
								<button class="btn btn-primary">Drop all</button>
							</div>
							<hr />
							<div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=destinations&format=CSV');">Download all destinations (CSV)</button>
							</div>
							<div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=destinations&format=XML');">Download all destinations (XML)</button>
							</div>
							<div class="box-body pad table-responsive">
								<button class="btn btn-primary" onclick="window.location.assign('index.php?page=settings&action=download&data=probes');">Download all probes (XML)</button>
							</div>                        	
                        </div>	                        
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">General</h3>
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
                                        <label for="name">Password</label>
                                        <input name="oldpassword" type="password" class="form-control" id="name" placeholder="Old password" value="">
                                        <input name="newpassword1" type="password" class="form-control" id="name" placeholder="New Password" value="">
                                        <input name="newpassword2" type="password" class="form-control" id="name" placeholder="Confirm" value="">
                                    </div>
                                </div><!-- /.box-body -->
								<div class="box-footer">
									<input type="hidden" name="changePassword" value="true" />
									<button type="submit" class="btn btn-primary" onclick="this.submit()">Submit</button>
								</div>
                            </form>
                            <!-- form start -->
                            <form role="form" action="" method="POST">
                                <div class="box-body">
                                	<div class="form-group">
                                        <label for="address">URL Prefix</label>
                                        <input name="apiprefix" type="text" class="form-control" id="address" placeholder="" value="<?php echo getAPIPrefix(); ?>" />
                                    </div>
                                </div>
                                <div class="box-footer">
									<input type="hidden" name="changeAPIPrefix" value="true" />
									<button type="submit" class="btn btn-primary" onclick="this.submit()">Submit</button>
								</div>
							<form>
                        </div><!-- /.box -->
                    </div><!--/.col (right) -->
                </div>   <!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->

    <?php

	function downloadDestinations($format)
	{
		switch ($format)
		{
			case "CSV":
				createCSVWithAllDestinations();
				echo "<script type='text/javascript'>window.open('destinations.csv');</script>";
				break;
			case "XML":
				createXMLWithAllDestinations();
				echo "<script type='text/javascript'>window.open('destinations.xml');</script>";
				break;
		}
	}

	function downloadProbes()
	{
		createXMLWithProbes();
		echo "<script type='text/javascript'>window.open('probes.xml');</script>";
	}

?>