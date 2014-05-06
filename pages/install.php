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

	function showInstallationPage($message)
	{
		?>
			<!DOCTYPE html>
			<html class="bg-black">
			    <head>
			        <meta charset="UTF-8">
			        <title>Android Backoffice | Installation</title>
			        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
			        <!-- bootstrap 3.0.2 -->
			        <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
			        <!-- font Awesome -->
			        <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css" />
			        <!-- Theme style -->
			        <link href="./css/AdminLTE.css" rel="stylesheet" type="text/css" />
			
			        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
			        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
			        <!--[if lt IE 9]>
			          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
			        <![endif]-->
			    </head>
			    <body class="bg-black">
			
			        <div class="form-box" id="login-box">
			            <div class="header">Welcome to the installation page of Tracebox for Android Backoffice.</div>
			            <form action="index.php" method="post">
			                <div class="body bg-gray">
			                	<?php
	                                if (isset($message) && $message[1] != "")
	                                {
	                                     ?>
	                                        <div class="alert <?php echo $message[0]; ?> ">
	                                            <i class="fa fa-ban "></i>
	                                            <?php echo $message[1]; ?>
	                                        </div>
	                                    <?php
	                                }
									$databaseSettings = getDatabaseSettings();
		                         ?>
		                         Please provide the following information.
								 <div class="form-group">
								 	MySQL server address: 
			                        <input type="text" name="server" class="form-control" placeholder="URL" value="<?php if (isset($_POST['server'])) echo $_POST['server'];  else echo $databaseSettings['sql_server']; ?>" />
			                    </div>
			                    <div class="form-group">
			                    	MySQL database name:
			                        <input type="text" name="database" class="form-control" placeholder="Database name" value="<?php if (isset($_POST['database'])) echo $_POST['database']; else echo $databaseSettings['sql_database']; ?>" />
			                    </div>
			                    <div class="form-group">
			                    	MySQL user name:
			                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; else echo $databaseSettings['sql_username']; ?>" />
			                    </div>
			                    <div class="form-group">
			                    	MySQL password:
									<input type="password" name="password" class="form-control" placeholder="Password" />
								</div>       
								<div class="form-group">
			                    	Table prefix:
									<input type="text" name="tableprefix" class="form-control" placeholder="Table Prefix" value="<?php if (isset($_POST['tableprefix'])) echo $_POST['tableprefix']; else echo "android_tracebox_"; ?>" />
								</div>
								<div class="form-group">
			                    	Connection password:
									<input type="password" name="connection_password" class="form-control" placeholder="Password" value="" />
								</div> 
			                </div>
			                <div class="footer">  
			                	<input type="hidden" name="installBackoffice" value="true" />                                                     
			                    <button type="submit" class="btn bg-olive btn-block">Install backoffice</button>  			                    
			                </div>
			            </form>
			        </div>
			
			
			        <!-- jQuery 2.0.2 -->
			        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
			        <!-- Bootstrap -->
			        <script src="./js/bootstrap.min.js" type="text/javascript"></script>        
			
			    </body>
			</html>
		<?php
	}
?>