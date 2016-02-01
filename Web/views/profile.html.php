<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

	<div id="templatemo">
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

		<?php foreach($user_albums as $item){ ?>

		<div id="about" class="section-content">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="section-title">
							<div class="col-md-12 text-center">
								<a href="/photos/<?= $id.'.'.$item['id']?>"><h2><?=$item['name']?></h2></a>
								<p class="text-right"><?=$item['date']?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="row our-story">
					<div class="col-md-5 dir">
						<a href="/photos/<?= $id.'.'.$item['id']?>"><h3>Album</h3></a>
						<a href="#"><img src="../images/1.jpg" height="186" width="315"></a>
						<!--
						<div class="comments panel-group">
							<div class="panel panel-default">
								<p>Dimonchik : Random text</p>
							</div>
							<div class="panel panel-default">
								<p>Vitek : Random text</p>
							</div>
							<div class="panel panel-default">
								<p>Roman : Random text</p>
							</div>
						</div>
						-->
					</div>

					<div class="col-md-1"></div>
					<div class="col-md-6 descr_1">
						<div class="descr">
							<h3 class="text-center">Description</h3>
							<textarea readonly class="textarea"><?=$item['description']?></textarea>
						</div>

						<a href="#" ><h5 class="text-left">Show comments</h5></a>
						<div class="descr">
							<h3 class="text-right">10
								<img src="../images/2.jpg" height="24" width="15" >
							</h3>
						</div>
					</div>
				</div>
			</div> <!-- /.row -->
		</div> <!-- /#about -->

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
	</script>

<?php $this->endblock() ?>