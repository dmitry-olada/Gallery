<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
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

    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.maskedinput.js"></script>
    <script src="../js/bootstrap.file-input.js"></script>
    <script src="../js/custom.js"></script>

</head>
<body>

<div class="row hidden-lg">
    <div class="col-xs-5">
        <br/>
        <div style="text-align: center; margin-left: 10px"><img src="<?='../uploads/'.$avatar?>" border="1"></div>
    </div>
    <div class="col-xs-7">
        <h4 class="text-center" style="margin-left: 35px"><?=$nick?></h4>
        <ul style="list-style: none">
            <li class="phone_menu"><a href="/">Profile</a></li>
            <?php if($profile_owner) { ?>
                <li class="phone_menu"><a href='/albums'>Manage Albums</a></li>
                <li class="phone_menu"><a href='/bookmarks'>Bookmarks</a></li>
            <?php }else{ ?>
                <li class="phone_menu"><a data-toggle="modal" data-target="#infoModal" href='#'>Show info</a></li>
                <?php if(!$bm_status){ ?>
                    <li class="phone_menu"><a class="add_bookmark" href="#">Add to BM</a></li>
                <?php } else { ?>
                    <li class="phone_menu"><a class="add_bookmark" href="#">Remove from BM</a></li>
                <?php } ?>
                <input type="hidden" value="<?=$id?>" id="user_id">
            <?php } ?>
            <li class="phone_menu"><a href='/settings'>Settings</a></li>
            <li class="phone_menu"><a href='/auth/logout'>Logout</a></li>
        </ul>
    </div>

</div>

<div class="hidden-lg" style="border-top: 1px solid #f7f7f7; margin-top: 5px;"></div>

<!-- Main Menu -->
<div id="main-sidebar" class="hidden-xs hidden-sm hidden-md">
    <div class="nick">
        <a href="<?='/profile/'.$id ?>"><h2 class=" text-center"><?=$nick?></h2></a>
    </div>
    <div class="photo_profile text-center" style="height: 200px">
        <img src="<?='../uploads/'.$avatar?>" border="1">
    </div>

    <div class="navigation">
        <ul class="main-menu">
            <li><a href='/'>Profile</a></li>
            <?php if($profile_owner) { ?>
                <li><a href='/albums'>Manage Albums</a></li>
                <li><a href='/bookmarks'>Bookmarks</a></li>
            <?php }else{ ?>
                <li><a data-toggle="modal" data-target="#infoModal" href='#'>Show info</a></li>
                <?php if(!$bm_status){ ?>
                    <li><a class="add_bookmark" href="#">Add to BM</a></li>
                <?php } else { ?>
                    <li><a class="add_bookmark" href="#">Remove from BM</a></li>
                <?php } ?>
                <input type="hidden" value="<?=$id?>" id="user_id">
            <?php } ?>
            <li><a href='/settings'>Settings</a></li>
            <li><a href='/auth/logout'>Logout</a></li>
        </ul>
    </div> <!-- /.navigation -->
</div> <!-- /#main-sidebar -->

<!-- InfoModal  -->
<div id="infoModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h3 class="text-muted text-center"><span><?=$nick?></span></h3>
            </div>
            <div class="modal-body text-muted"><p>Signed by: <span><?=$date?></span></p>
                <p>Albums: <span><?=$albums?> </span></p>
                <p>Photos: <span><?=$photos?> </span></p>
                <?php if($profile_owner) {
                    echo "<p>Email: <span>" . $email . "</span></p>";
                }else{
                    echo "<p>ID: <span>" . $id . "</span></p>";
                }
                ?>
                <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>
</div><!-- /InfoModal  -->

<div id="main-content">
    <!--Flash -->
    <div class="row">
        <div class="col-lg-3 col-xs-1"></div>
        <div class="col-lg-6 col-xs-10 box">
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
        <div class="col-lg-3 col-xs-1"></div>
    </div>
<!--/Flash -->



<!-- Content -->

    <?php $this->output('content') ?>

<!-- /Content  -->

</div> <!-- /main-content -->

</body>
</html>
