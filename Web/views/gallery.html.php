<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<div class="gallery_title"><h3 class="text-center"><?=$album[0]['name']?></h3><span class="gallery_date"><?=$album[0]['date']?></span></div>

<div class="gallery_delimiter"></div>

<div class="gallery_container">
    <!-- The container for the list of example images -->

    <div class="row">

        <div class="col-lg-7 photo_container">
            <div id="links">

                <?php foreach($photo as $item){ ?>
                    <a href="<?=$item['link']?>" title="<?=$item['name']?>" data-gallery>
                        <img src="<?=$item['link']?>" height="75" alt="<?=$item['name']?>">
                    </a>
                <?php } ?>

                <!--
                <a href="http://www.cinemablend.com/images/sections/62870/_1395179465.jpg" title="Dimonchik" data-gallery>
                    <img src="http://www.cinemablend.com/images/sections/62870/_1395179465.jpg" height="75" alt="Dimonchik">
                -->

            </div>
        </div>
        <div class="col-lg-4 comment_container">
                <div class="comment_display"></div>
            <div class="comment_input">
                <input type="text" id="text_comment"/>
                <input type="submit" id="add_comment" value="Enter" href="/photos/addcomment/<?=$album[0]['id']?>">
            </div>
        </div>

    </div>

</div> <!-- /.container -->

<div class="gallery_delimiter"></div>

<div class="gallery_description">

     <div class="row">
         <div class="col-lg-8 gallery_description_block">
             <h4 class="text-center"> Description </h4>
             <?=$album[0]['description']?>
             <div style="margin-top: 20px"></div>
         </div>
         <div class="col-lg-3 gallery_buhlikes">
             <h4 class="text-center"> Likes </h4>
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

<?php $this->endblock() ?>
