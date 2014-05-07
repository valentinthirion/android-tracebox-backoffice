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

	session_start();

	error_reporting(E_ERROR | E_PARSE);

    include("system/config.php");
    $message = array();

	// If just tried to install
	if ((isset($_POST['installBackoffice'])) && ($_POST['installBackoffice'] == "true"))
	{
		if (($_POST['server'] != "") && ($_POST['database'] != "") && ($_POST['username'] != "") && ($_POST['password'] != "") && ($_POST['tableprefix'] != "") && ($_POST['connection_password'] != ""))
		{
			if (setDatabaseSettings($_POST['server'], $_POST['database'], $_POST['username'], $_POST['password'], $_POST['tableprefix']))
			{
				if (connectMysql())
				{
					if (createTablesForSystem($_POST['tableprefix'], $_POST['connection_password'])) // CREATE TABLES
						$message = array("alert-success", "The database settings have been updated");
					else
						$message = array("alert-danger", "There was an error while creating the database and saving the settings");
				}
				else
				$message = array("alert-danger", "The system could not connect to the database.");
			}
		}
		else
			$message = array("alert-danger", "The given database settings are not valid.");
	}

	// Test if system installed
	if (!connectMysql())
	{
		include("pages/install.php");
		showInstallationPage($message);
		return;
	}

	
	if (isset($_POST['logIn']))
	{
		if ($_POST['logIn'] != "true")
			$message = array("alert-danger", "Error while logging in.");
		else
		{
			if ($_POST['password'] == "")
				$message = array("alert-danger", "You have to enter a password.");
			else
	        {
	        	if (!connectUser($_POST['password']))
	        		$message = array("alert-danger", "Wrong password.");
	        }
		}
	} 

	// Test if user connected
	if (!isAUserConnected())
	{
		include("pages/log.php");
		showLogMenu($message);
		return;
	}
	// USER DECONECTION
	if (isset($_GET['disconnect']) && isAUserConnected())
	{
		disconnectUser();
		$message = array("alert-success", "You have been correctly disconnected.");
		include("pages/log.php");
		showLogMenu($message);

		return;
	}

    $page = "";
    if (isset($_GET['page']))
        $page = $_GET['page'];
    else
        $page = "dashboard";

    function showActivePageOrNot($currentPage, $menuItem)
    {
        if ($currentPage == $menuItem)
            echo "active";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Android Tracebox | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
         <!-- Ion Slider -->
        <link href="css/ionslider/ion.rangeSlider.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

		<!-- CUSTOM JS -->
		<script type="text/javascript">
			function confirmAndGo(text, destination)
			{
				var c = confirm(text);
				if (c == true)
				{
					window.location.assign(destination);
				}
			}
		</script>

		<!-- FAVICON -->
		<link rel="icon" type="image/png" href="img/favicon/favicon-32.png">
		<!-- Shortcut links -->
		<link rel="shortcut icon" href="img/favicon/favicon-128.png" />
    </head>

	
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="img/favicon/favicon-48.png" width="40px" />
                Android Tracebox
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>Connected <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">About</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="index.php?disconnect=true" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="<?php showActivePageOrNot($page, "dashboard"); ?>" >
                            <a href="index.php">
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="<?php showActivePageOrNot($page, "probes"); ?>">
                            <a href="index.php?page=probes">
                                <span>Probes</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                        </li>
                        <li class="treeview <?php showActivePageOrNot($page, "destinations"); ?>">
                            <a href="#">
                                <span>Destinations</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="index.php?page=destinations"><i class="fa fa-angle-double-right"></i> List</a></li>
                                <li><a href="index.php?page=destinations&action=add"><i class="fa fa-angle-double-right"></i> Add new</a></li>
                            </ul>
                        </li>
                        <li class="<?php showActivePageOrNot($page, "settings"); ?>" >
                            <a href="index.php?page=settings">
                                <span>Settings</span>
                            </a>
                        </li>
                        <li class="" >
                            <a href="http://www.androidtracebox.org" target="_blank">
                                <span>About</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <?php
                switch ($page)
                {
                    case "dashboard":
                        include("pages/dashboard.php");
                        break;
                    case "probes":
                        include("pages/probes.php");
                        break;
                    case "destinations":
                        include("pages/destinations.php");
                        break;
                    case "settings":
                        include("pages/settings.php");
                        break;
                    case "log":
                    	include("pages/log.php");
                    	break;
                    default:
                        include("pages/404.php");
                        break;
                }
            ?>

            
        </div><!-- ./wrapper -->

        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>
		<!-- Ion Slider -->
        <script src="js/plugins/ionslider/ion.rangeSlider.min.js" type="text/javascript"></script>

    </body>
</html>