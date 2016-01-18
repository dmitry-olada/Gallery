<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Auth</title>
	<link rel="stylesheet" href='bootstrap.min.css' type='text/css' media='all'>
	<link rel="stylesheet" href='../../Views/css/style.css' type='text/css' media='all'>
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

 				<form role="form">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control" id="email" placeholder="Enter an email">
				 </div>
				 <div class="form-group">
				  <label for="pass">Password</label>
				  <input type="password" class="form-control" id="pass" placeholder="Enter a password">
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


 				<form role="form">
				 <div class="form-group">
				  <label for="email">Email</label>
				  <input type="email" class="form-control test" name="" ="email" placeholder="Enter an email">
				  <label for="pass">Password</label>
				  <input type="password" class="form-control test" name="pass" placeholder="Make up your password">
				  <label for="pass">Nick</label>
				  <input type="text" class="form-control" name="nick" placeholder="Select a nick">
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