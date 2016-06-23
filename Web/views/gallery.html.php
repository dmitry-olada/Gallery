<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<div class="gallery_title"><h3 class="text-center"><?=$album['name']?></h3><span class="gallery_date hidden-sm hidden-xs"><?=$album['date']?></span></div>

<div style="margin-top: 10px" class="gallery_date hidden-lg"><?=$album['date']?></div>

<div class="gallery_delimiter"></div>

<div class="gallery_container">
    <!-- The container for the list of example images -->

    <div class="row">

        <div class="col-lg-7 col-xs-10 photo_container">
            <div id="links">

                <?php foreach($photo as $item){ ?>
                    <a href="<?=$item['link']?>" title="<?=$item['name']?>" data-gallery>
                        <img style="margin-bottom: 3px" src="<?=$item['link']?>" height="75" alt="<?=$item['name']?>">
                    </a>
                <?php } ?>

            </div>
        </div>
        <div class="col-lg-4 hidden-sm hidden-xs comment_container">
                <div class="comment_display"></div>
            <div class="comment_input">
                <input type="text" id="text_comment"/>
                <input type="submit" id="add_comment" value="Enter" href="/photos/addcomment/<?=$album['id']?>">
            </div>
        </div>

    </div>

</div> <!-- /.container -->

<div class="gallery_delimiter"></div>

<div class="gallery_description">

     <div class="row">
         <div class="col-lg-8 col-xs-6 gallery_description_block">
             <h4 class="text-center"> Description </h4>
             <?=$album['description']?>
             <div style="margin-top: 20px"></div>
         </div>
         <div class="col-lg-3 col-xs-3 gallery_buhlikes_container">
             <h4 class="text-center"> Likes </h4>
             <h3 class="text-center">
                 <?php if($album['isliked']) { ?>
                     <div class="gallery_buhlikes">
                         <a href="/albums/buhlike/<?=$album['id']?>" class="ajax_buhlikes" id="buh_link_<?=$album['id']?>">
                             <?=$album['buhlikes']?>&nbsp<img src="../images/vine2.png" height="50px" width="20px">
                         </a>
                     </div>
                 <?php } else {?>
                     <div class="gallery_buhlikes">
                         <a href="/albums/buhlike/<?=$album['id']?>" class="ajax_buhlikes" id="buh_link_<?=$album['id']?>">
                             <?=$album['buhlikes']?>&nbsp<img src="../images/vine3.png" height="50px" width="20px">
                         </a>
                     </div>
                 <?php } ?>
             </h3>

             <div class="gallery_owner text-center">
                 <h5>Owner: <a href="/profile/<?=$album['owner']?>"><?=$album['nick']?></a></h5>
             </div>
         </div>
     </div>

</div>



<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation and button states -->
<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="../js/bootstrap-image-gallery.min.js"></script>
<script src="../js/gallery_script.js"></script>
<script type="text/javascript">
    empty_glass = new Image();
    empty_glass.src = "../images/vine2.png";
    full_glass = new Image();
    full_glass.src = "../images/vine3.png";
</script>

<?php $this->endblock() ?>


