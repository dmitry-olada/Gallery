<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

    <script type="text/javascript">
        jQuery(function($){
            $("#change_album_date").mask("9999-99-99",{placeholder:"yyyy-mm-dd"});
        });
    </script>

    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <h3 class="text-center">Manage Albums</h3>
                <div class="col-lg-1"></div>
                <div class="col-lg-11">
                    <button class="btn btn-default" type="button" data-toggle="modal" data-target="#create_album_modal">Create Album</button>
                </div>
            </div>

            <div id="create_album_modal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Create new album<button class="close" type="button" data-dismiss="modal">×</button></h4>
                        </div>
                        <div class="modal-body">
                            <form action="/albums/create/1" method="post">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <p>Name:</p>
                                        <div class="form_indent"></div>
                                        <p>Date:</p>
                                        <div class="form_indent"></div>
                                        <p>Description:</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" required name="create_album_name">
                                        <div class="form_indent"></div>
                                        <input id="change_album_date" type="text" required name="create_album_date">
                                        <div class="form_indent"></div>
                                        <textarea style="resize: none" cols="45" rows="6" name="change_album_description"></textarea>
                                    </div>
                                    <div class="col-lg-2"></div>
                                </div>
                                <div style="text-align: center"><input type="submit" value="Create"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <?php if(!empty($all_albums)) {
                $count = 0;
                foreach ($all_albums as $item) {
                    $count++;
                ?>
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="settings_changer albums_counter">
                                <p><strong><?=$item['name']?></strong><button class="btn-primary settings_button" data-toggle="collapse" data-target="#change_album_<?=$count?>">Manage</button><button class="btn-success album_upload">Upload</button></p>
                            </div>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">

                            <div id="change_album_<?=$count?>" class="collapse">
                             <!--<h4 class="text-center"><?=$item['name']?></h4>-->
                            <br>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <form role="form" action="/albums/change/<?=$item['id']?>" method="POST">
                                            <p>Name: <input style="width:250px" type="text" name="new_name" value="<?=$item['name']?>"></p>
                                            <p>Date: &nbsp <input id="change_album_date" style="width:249px" type="text" name="new_date" value="<?=$item['date']?>"> </p>
                                            <p>Description: </p><p><textarea id="album_description" cols="45" rows="6" name="new_description"
                                                <?php
                                                    if($item['description'] !== ''){
                                                        echo "style='border: none'";
                                                    }?>
                                                ><?=$item['description']?></textarea></p>
                                            <div style="text-align: center" ><input type="submit" value="Change" class="btn btn-warning"></div>
                                        </form>
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-6">
                                        <table class="table table-hover">
                                            <?php foreach($all_photos[(integer)$count-1] as $new_item) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="album_img">
                                                            <img src="<?=$new_item['link']?>" height="52px">
                                                            <div class="hov_block">
                                                                <form action="/albums/photo_delete/<?=$new_item['id']?>" method="post">
                                                                    <input type="submit" value="Delete">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><form role="form" action="/albums/photo_change/<?=$new_item['id']?>" method="POST">
                                                            <input type="text" name="new_photo_name" value="<?php echo (null !== $new_item['name'])?$new_item['name']:''?>">
                                                            <div><input type="submit" value="Change Name"></div>
                                                        </form></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>

                                </div><!--/row-->
                                <br>
                            </div><!--/change_album-->
                        </div><!--/col-lg-6-->
                        <div class="col-lg-1"></div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <div class="settings_delimiter"></div>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>


                <?php } ;
            } ?>
        </div>
    </div><!--/row-->

<div style="height: 500px"></div>

<?php $this->endblock() ?>