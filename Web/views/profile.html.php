<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

	<div id="templatemo" class="hidden-sm hidden-xs">
		<div class="main-slider">
			<div class="flexslider">
				<ul class="slides">

					<li>
						<div class="slider-caption">
							<h2>Responsive Layout</h2>
							<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames.</p>
							<a href="#" class="largeButton homeBgColor">Read More</a>
						</div>
						<img src="../images/slide1.jpg">
					</li>

					<li>
						<div class="slider-caption">
							<h2>Portfolio Website</h2>
							<p>Fusce convallis enim vitae sagittis mollis. Sed bibendum ultricies dignissim.</p>
							<a href="#" class="largeButton homeBgColor">Details</a>
						</div>
						<img src="../images/slide2.jpg">
					</li>

					<li>
						<div class="slider-caption">
							<h2>Free Templates</h2>
							<p>All templates are free to download and use for your personal or commercial websites.</p>
							<a href="#" class="largeButton homeBgColor">Downloads</a>
						</div>
						<img src="../images/slide3.jpg">
					</li>

				</ul>
			</div>
		</div>

	</div> <!-- /#sTop -->

	<p></p>

	<div class="container-fluid">

		<?php for($i = 0; $i<count($user_albums); $i+=2){ ?>

		<div class="row">
			<div class="col-lg-6 col-xs-12">
				<div class="row">
					<div class="col-lg-2 col-xs-1"></div>
					<div class="col-lg-9 col-xs-10 profile_albums">
						<a class="text-center " href="/photos/<?= $id.'.'.$user_albums[$i]['id']?>"><h2><?=$user_albums[$i]['name']?></h2></a>
						<div class="row">
							<div class="col-lg-9 hidden-xs hidden-sm text-center">
								<a class="profile_folder" href="/photos/<?= $id.'.'.$user_albums[$i]['id']?>"><img src="../images/folder.png" height="230" width="280"></a>
							</div>
							<div class="col-xs-7 hidden-lg">
								<a class="profile_folder" href="/photos/<?= $id.'.'.$user_albums[$i+1]['id']?>"><img src="../images/folder.png" height="115" width="140"></a>
							</div>
							<div class="col-lg-3 col-xs-5">
								<span class="profile_date"><p><?=$user_albums[$i]['date']?></p></span>
								<h3 class="text-center">
									<div class="hidden-xs hidden-sm" style="height: 120px"></div>
									<?php if($user_albums[$i]['isliked']) { ?>
										<div class="profile_buhlikes">
											<a href="/albums/buhlike/<?=$user_albums[$i]['id']?>" class="ajax_buhlikes" id="buh_link_<?=$user_albums[$i]['id']?>">
												<?=$user_albums[$i]['buhlikes']?>&nbsp<img src="../images/vine2.png" height="50px" width="20px">
											</a>
										</div>
									<?php } else {?>
										<div class="profile_buhlikes">
											<a href="/albums/buhlike/<?=$user_albums[$i]['id']?>" class="ajax_buhlikes" id="buh_link_<?=$user_albums[$i]['id']?>">
												<?=$user_albums[$i]['buhlikes']?>&nbsp<img src="../images/vine3.png" height="50px" width="20px">
											</a>
										</div>
									<?php } ?>
								</h3>
							</div>
						</div>
						<div class="col-lg-1 col-xs-1"></div>
					</div>
				</div>
			</div>


			<?php if($i+1 < count($user_albums)){?>
			<div class="col-lg-6 col-xs-12">
				<div class="row">
					<div class="col-lg-1 col-xs-1"></div>
					<div class="col-lg-9 col-xs-10 profile_albums">
						<a class="text-center " href="/photos/<?= $id.'.'.$user_albums[$i+1]['id']?>"><h2><?=$user_albums[$i+1]['name']?></h2></a>
						<div class="row">
							<div class="col-lg-9 hidden-sm hidden-xs text-center">
								<a class="profile_folder" href="/photos/<?= $id.'.'.$user_albums[$i+1]['id']?>"><img src="../images/folder.png" height="230" width="280"></a>
							</div>
							<div class="col-xs-7 hidden-lg">
								<a class="profile_folder" href="/photos/<?= $id.'.'.$user_albums[$i+1]['id']?>"><img src="../images/folder.png" height="115" width="140"></a>
							</div>
							<div class="col-lg-3 col-xs-5">
								<span class="profile_date"><p><?=$user_albums[$i+1]['date']?></p></span>
								<h3 class="text-center">
									<div class="hidden-xs hidden-sm" style="height: 120px"></div>
									<?php if($user_albums[$i+1]['isliked']) { ?>
										<div class="profile_buhlikes">
											<a href="/albums/buhlike/<?=$user_albums[$i+1]['id']?>" class="ajax_buhlikes" id="buh_link_<?=$user_albums[$i+1]['id']?>">
												<?=$user_albums[$i+1]['buhlikes']?>&nbsp<img src="../images/vine2.png" height="50px" width="20px">
											</a>
										</div>
									<?php } else {?>
										<div class="profile_buhlikes">
											<a href="/albums/buhlike/<?=$user_albums[$i+1]['id']?>" class="ajax_buhlikes" id="buh_link_<?=$user_albums[$i+1]['id']?>">
												<?=$user_albums[$i+1]['buhlikes']?>&nbsp<img src="../images/vine3.png" height="50px" width="20px">
											</a>
										</div>
									<?php } ?>
								</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-2 col-xs-1"></div>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php } ?>

	</div> <!-- /.container-fluid -->

	<script src="../js/jquery.singlePageNav.js"></script>
	<script src="../js/jquery.flexslider.js"></script>
	<script src="../js/jquery.prettyPhoto.js"></script>
	<script type="text/javascript">
	$('.flexslider').flexslider({
		animation: "fade",
		directionNav: false
	});

	empty_glass = new Image();
	empty_glass.src = "../images/vine2.png";
	full_glass = new Image();
	full_glass.src = "../images/vine3.png";

	</script>

<?php $this->endblock() ?>