<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<link rel="stylesheet" href='../styles/bootstrap.min.css' type='text/css' media='all'>
	<link rel="stylesheet" href='../styles/style.css' type='text/css' media='all'>
</head>
<body>

<h2 class="text-center text-muted"><?=$nick;?></h2>

<div class="row" >
	<div class="col-lg-4"></div>
 	<div class="col-lg-4 line">
 		<div class="row" >
 			<div class="col-lg-5">
 			<div class="photo">
 				<img src="<?=$avatar;?>" width="133" height="133" border="1">
 			</div>

 			</div>
 			<div class="col-lg-7 ">
 				<div class="info">
					<?php if($owner) {
						echo "<h4 class='text-muted'>Email: <span>" . $email . "</span></h4>";
					}else{
						echo "<h4 class='text-muted'>ID: <span>" . $id . "</span></h4>";
					}
					?>
				<h4 class="text-muted">Signed by: <span><?=$date;?></span></h4>
				<h4 class="text-muted">Friends: <a><?=$friends;?></a></h4>
				<h4 class="text-muted">Photos: <span><?=$photos;?></span></h4>
				<h4 class="text-muted">Videos: <span><?=$videos;?></span></h4>

				</div>

			</div>


 		</div>
 	</div>
	<div class="col-lg-4"></div>
</div>
	<h2 class="text-center text-muted">Gallery</h2>
	<div class="row" >
		<div class="col-lg-2"></div>
		<div class="col-lg-8 line">
			

			
		</div>
		<div class="col-lg-2"></div>
	</div>

</body>
</html>