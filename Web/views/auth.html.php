<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Auth</title>
	<link rel="stylesheet" href='../styles/bootstrap.min.css' type='text/css' media='all'>
	<link rel="stylesheet" href='../styles/style.css' type='text/css' media='all'>
</head>
<body>

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

 				<form role="form" action="/profile/1" method="POST">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control" name="email" placeholder="Enter an email" required>
				 </div>
				 <div class="form-group">
				  <label for="pass">Password</label>
				  <input type="password" class="form-control" name="password" placeholder="Enter a password" required>
				 </div>
				 <div class="checkbox">
				  <label><input type="checkbox" value="yes"> Remember me</label>
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
 					<h2>Sign Up</h2>
 				</div>


 				<form role="form" action="/" method="post">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control" name="email" placeholder="Enter an email" required>
				  <label for="pass">Password</label>
				  <input type="password" class="form-control" name="password" placeholder="Make up your password" required>
				  <label for="pass">Nick</label>
				  <input type="text" class="form-control" name="nick" placeholder="Select a nick" required>
				 </div>
				 <button type="submit" class="btn btn-success">Sing Up</button>
				</form>

 			</div>
			
		</div>




 	</div>
 	<div class="col-lg-3"></div>
</div>








</body>
</html>