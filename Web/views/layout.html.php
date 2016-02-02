<!DOCTYPE html>
<html>
<head>

    <title><?php $this->output('title', $nick.' - '.$site_title) ?></title>

    <link rel="icon" href="../images/icon.png" type="image/png" />

    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/templatemo_misc.css">
    <link rel="stylesheet" href="../styles/templatemo_style.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="../styles/bootstrap-image-gallery.min.css">

    <!-- JavaScripts -->
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.maskedinput.js" type="text/javascript"></script>
    <script src="../js/custom.js"></script>

</head>
<body>


<!-- Main Menu -->
<div id="main-sidebar" class="hidden-xs hidden-sm">
    <div class="nick">
        <a href="<?='/profile/'.$id ?>"><h2 class=" text-center text-info"><?=$nick;?></h2></a>
    </div>
    <div class="photo_profile">
        <img src="<?=$avatar?>" width="200" height="200" border="1">
    </div>


    <div class="navigation">
        <ul class="main-menu">
            <li class="home"><a href='/'>Profile</a></li>
            <?php if($profile_owner) { ?>
                <li class="about"><a href='/albums'>Manage Albums</a></li>
                <li class="services"><a href='/bookmarks'>Bookmarks</a></li>
            <?php }else{ ?>
                <li class="about"><a data-toggle="modal" data-target="#infoModal" href=''>Show info</a></li>
                <li class="services"><a href=''>Add to BM</a></li>
            <?php }
            //TODO: remove from friends;
            ?>
            <li class="portfolio"><a href='/settings'>Settings</a></li>
            <li class="contact"><a href='/auth/logout'>Logout</a></li>
        </ul>
    </div> <!-- /.navigation -->

</div> <!-- /#main-sidebar -->

<!-- InfoModal  -->
<div id="infoModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h3 class="text-muted text-center"><span><?=$nick;?></span></h3>
            </div>
            <div class="modal-body text-muted"><p>Signed by: <span><?=$date;?></span></p>
                <p>Bookmarks: <a target="_blank" href="#"><?=$bookmarks;?></a></p>
                <p>Albums: <span><?=$albums?> </p>
                <p>Photos: <span><?=$photos?> </p>
                <?php if($profile_owner) {
                    echo "<p>Email: <span>" . $email . "</span></p>";
                }else{
                    echo "<p>ID: <span>" . $id . "</span></p>";
                }
                ?>
                <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
            </div>
        </div>
    </div>
</div><!-- /InfoModal  -->


<div id="main-content">

<!--Flash -->
<div class="row">
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
</div>

<!--/Flash -->



<!-- Content -->

    <?php $this->output('content') ?>

<!-- /Content  -->

    <div class="layout_footer"
        <div class="site-footer">
            <div class="bottom-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="copyright">Buhlogram © 2016 </p>
                        </div> <!-- /.col-md-6 -->
                        <div class="col-md-6 credits">
                            <a rel="nofollow" href="#" target="_parent">Contact Us</a>
                        </div> <!-- /.col-md-6 -->
                    </div> <!-- /.row -->
                </div> <!-- /.container-fluid -->
            </div> <!-- /.bottom-footer -->
        </div> <!-- /.site-footer -->
    </div> <!-- /.layout-footer -->

</div>

</body>
</html>
