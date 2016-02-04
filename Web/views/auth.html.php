<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Welcome - <?=\Core\Controller::SITE_TITLE?></title>
	<link rel="stylesheet" href='../styles/bootstrap.min.css' type='text/css' media='all'>
	<link rel="stylesheet" href='../styles/style.css' type='text/css' media='all'>
	<script src="../js/jquery-1.10.2.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>

	<script type="text/javascript">
		setTimeout(function(){$('.box').fadeOut(1000)},3000);
	</script>
</head>


  <body bgcolor=”grey”>

<div class="row" >
	<div class="col-lg-3"></div>
	<div class="col-lg-6 box">
		<?php
		if (null !== $alert) {
			foreach($alert as $key => $value) { ?>
				<div class="alert alert-<?=$key?> alert-dismissible " role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p class="text-center"><strong><?php echo $value ?></strong></p>

				</div>
			<?php }
		}
		?>
	</div>
	<div class="col-lg-3"></div>




<br>
<br>
<br>
<br>
<br>
<br>


<div class="row" >
	<div class="col-lg-3"></div>

 	<div class="col-lg-3">

 	<div class="row" >
			<div class="col-lg-1"></div>
 			<div class="col-lg-10">
 				<div class = "text-primary text-center head_login">
 					<h2>Login</h2>
 				</div>

 				<form role="form" action="/Auth/login" method="POST" enctype="application/x-www-form-urlencoded">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control" name="email" placeholder="Enter an email" required>
				 </div>
				 <div class="form-group">
				  <label for="pass">Password</label>
				  <input type="password" class="form-control" name="password" placeholder="Enter a password" required>
				 </div>
				 <div class="checkbox">
				  <label><input type="checkbox" name="remember" value="yes"> Remember me</label>
				 </div>
				 <button type="submit" class="btn btn-primary">Login</button>
				</form>

 			</div>

		</div>

 	</div>

 	<div class="col-lg-3" id="border">
 		<div class="row" >
 			<div class="col-lg-1"></div>
 			<div class="col-lg-10">

 				<div class = "text-success text-center head_reg">
 					<h2>Register</h2>
 				</div>


 				<form role="form" action="/Auth/auth" method="post">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control" name="email" placeholder="Enter an email" required>
				  <label for="pass">Password</label>
				  <input type="password" class="form-control" name="password" placeholder="Make up your password" required>
				  <label for="pass">Nick</label>
				  <input type="text" class="form-control" name="nick" placeholder="Select a nick" required>
				 </div>
				 <button type="submit" class="btn btn-success">Register</button>
				</form>

 			</div>

		</div>




 	</div>
 	<div class="col-lg-3"></div>
</div>


	



</body>
</html>