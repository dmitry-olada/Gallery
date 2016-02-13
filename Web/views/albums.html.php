<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

    <link rel="stylesheet" href="../styles/bootstrap-multiselect.css">
    <script src="../js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
        jQuery(function($){
            $(".change_album_date").mask("9999-99-99",{placeholder:"yyyy-mm-dd"});
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
                            <form action="/albums/create" method="post">
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
                                        <input class="change_album_date" type="text" required name="create_album_date">
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
                                <p><strong><?=$item['name']?></strong>
                                    <button class="btn btn-primary settings_button" data-toggle="collapse" data-target="#change_album_<?=$count?>">Manage</button>
                                    <button class="btn btn-default album_available" data-toggle="modal" data-target="#available_album_<?=$count?>">Permissions</button>
                                <button class="btn btn-default album_upload" data-toggle="modal" data-target="#upload_album_<?=$count?>">Upload</button></p>
                            </div>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">

                            <div id="change_album_<?=$count?>" class="collapse <?php if((integer)$collapse === $count){echo 'in'; }?>">
                             <!--<h4 class="text-center"><?=$item['name']?></h4>-->
                            <br>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <form role="form" action="/albums/change/<?=$item['id'].'.'.$count?>" method="POST">
                                            <p>Name: <input style="width:250px" type="text" name="new_name" value="<?=$item['name']?>"></p>
                                            <p>Date: &nbsp <input class="change_album_date" style="width:249px" type="text" name="new_date" value="<?=$item['date']?>"> </p>
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
                                    <div style="overflow: auto; height: 370px;" class="col-lg-6">
                                        <table class="table table-hover">
                                            <?php foreach($all_photos[(integer)$count-1] as $new_item) { ?>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <img src="<?=$new_item['link']?>" height="52px">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="photo_change_name" value="<?php echo (null !== $new_item['name'])?$new_item['name']:''?>"/>
                                                        <a class="btn btn-default ajax_btn1" href="/albums/photo_change/<?=$new_item['id']?>">Change Name </a>
                                                        <a class="btn btn-default ajax_btn" href="<?=$new_item['id'].'.'.$count?>" >Del</a>
                                                    </td>
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

                    <div id="upload_album_<?=$count?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                                    <h4 class="modal-title text-center">Upload photo</h4>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="active"><a href="#link" aria-controls="link" role="tab" data-toggle="tab">Link</a></li>
                                            <li><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">From PC</a></li>
                                            </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="link">
                                                <br/>
                                                <form role="form" action="/albums/addPhoto/<?=$item['id'].'.'.$count?>" method="post">
                                                    <div class="inputs_container text-center">
                                                        <a href="#" class=" btn btn-default minus_button" style="float: right; margin-left: 5px">&nbsp;-&nbsp;</a>
                                                        <a href="#" class=" btn btn-default plus_button" style="float: right">&nbsp;+&nbsp;</a>
                                                        <br/><br>
                                                        <div class="inputs notd">
                                                            <label> Name: </label> <input type="text" name="name_add_photo[]" class="field">
                                                            <label> &nbsp; &nbsp; Link: </label> <input type="text" name="link_add_photo[]" class="field">
                                                        </div>
                                                    </div><br/>
                                                    <div class="text-center"><input name="submit" type="submit"></div>
                                                </form>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="profile">
                                                <h4 class="text-center">This functional isn't available now =( </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button></div>
                            </div>
                        </div>
                    </div>

                    <div id="available_album_<?=$count?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                                    <h4 class="modal-title text-center">Manage '<?=$item['name']?>' permissions</h4>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="active"><a href=".open_for" aria-controls="open_for" role="tab" data-toggle="tab">Open for</a></li>
                                            <li><a href=".share" aria-controls="share" role="tab" data-toggle="tab">Share</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active open_for">
                                                <div class="row">
                                                    <div class="col-lg-2"></div>
                                                    <div class="col-lg-8">
                                                        <h6 class="text-center">If not selected anything, the album is available for all</h6>
                                                        <form role="form" action="/albums/available/<?=$item['id']?>" method="post">
                                                            <select class="example-getting-started" multiple="multiple" name="selects[]">
                                                                <?php foreach($all_users as $user_item){
                                                                    if(array_key_exists($user_item['id'], $item['share'])){
                                                                        continue;
                                                                    }?>
                                                                    <option
                                                                    <?php
                                                                    if($item['available']){
                                                                        if(array_key_exists($user_item['id'], $item['available'])) {
                                                                            echo 'selected';
                                                                        }
                                                                    }?>
                                                                    value="<?=$user_item['id']?>"><?=$user_item['nick']?></option>
                                                                <?php }?>
                                                            </select>
                                                            <input style="float: right" class="btn btn-default" type="submit" value="Update permissions">
                                                        </form>
                                                    </div>
                                                    <div class="col-lg-2"></div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade share">
                                                <div class="row">
                                                    <div class="col-lg-7">
                                                        <h6 class="text-center">Select friends which seems in the album</h6>
                                                            <label>Enter ID: &nbsp;</label><input type="text" class="share_input" height="100">
                                                            <button href="/albums/share/<?=$item['id']?>" style="float: right" class="btn btn-default share_button">Share</button>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-8">
                                                                <div class="text-center share_container">
                                                                <h6 class="text-center">Shared</h6>
                                                                <?php foreach($all_users as $user_item){
                                                                    if(!empty($item['share'])) {
                                                                        foreach ($item['share'] as $share_item) {
                                                                            if($share_item === $item['owner']){
                                                                                continue;
                                                                            }
                                                                            if ($share_item === $user_item['id']) { ?>
                                                                                    <div>
                                                                                        <a href="/profile/<?= $user_item['id'] ?>"><?= $user_item['nick'] ?></a>
                                                                                        <a href="/albums/deleteShare/<?= $user_item['id'] . '.' . $item['id'] ?>" class="share_deleter">×&nbsp</a>
                                                                                        <div class="albums_ph_delimiter"></div>
                                                                                    </div>

                                                                            <?php }
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ;
            } ?>
        </div>
    </div><!--/row-->

<div style="height: 500px"></div>

    <div class="inputs" id="link_sample">
        <label> Name: </label> <input type="text" name="name_add_photo[]" class="field">
        <label> &nbsp; &nbsp; Link: </label> <input type="text" name="link_add_photo[]" class="field">
    </div>



<script>
    $(document).ready(function() {
        $('.example-getting-started').multiselect({
            nonSelectedText: 'All users',
            enableFiltering: true,
            maxHeight: 600,
            dropUp: true
        });

        $('.example-getting').multiselect({
            enableFiltering: true,
            maxHeight: 600,
            dropUp: true
        });
    });

    $('.plus_button').click(function(e){
        e.preventDefault();
        $(this).parent().append($('#link_sample').clone().removeAttr('id'));
    });

    $('.minus_button').click(function(e){
        e.preventDefault();
        $('.inputs:last:not(.notd)', $(this).parent()).remove();
    });

    $('.share_deleter').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'), 'json');
        $(this).parent().remove();
    });

</script>

<?php $this->endblock() ?>

