<?php

	function showLogMenu($message)
	{
		?>
			<!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Log in to Android Tracebox Backoffice</h3>                                    
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form name="loginform" role="form" action="index.php" method="POST">
                                <div class="box-body">
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
                                    ?>
                                    <div class="form-group">
                                        <label for="name">Password</label>
                                        <input name="password" type="password" class="form-control" id="name" placeholder="Enter the password" autofocus>
                                    </div>
                                </div><!-- /.box-body -->

                                <div class="box-footer">
                                    <input type="hidden" name="logIn" value="true" />
                                    <button type="submit" class="btn btn-primary" onclick="this.submit()">Log</button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div>
                </div>
        
            </section><!-- /.content -->
		<?php
	}

?>