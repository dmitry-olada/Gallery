<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<button data-toggle="modal" data-target="#addIssue" class="btn btn-default" style="margin: 5px 0 -15px 20px; background-color: #f7f7f7">&nbsp;+&nbsp;</button>

<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10 issues_container">

        <div class="row">
        <?php for($i=0; $i<count($issues); $i+=3){ ?>
            <div class="col-lg-4 text-center">
                <div class="panel panel-<?=$issues[$i]['type']?> ss">
                    <div class="panel-heading issues_panel_head">
                        <a href="/profile/<?=$issues[$i]['id']?>"><?=$issues[$i]['nick']?></a>
                        <?php if($main_id === $issues[$i]['users_id']){?>
                            <a class="issue_deleter" href="/settings/deleteIssue/<?=$issues[$i]['id']?>" style="float: right">×&nbsp;</a>
                        <?php } ?>
                    </div>
                    <div class="panel-body" style="height: 100px; overflow-y: auto;"><?=$issues[$i]['text']?></div>
                </div>
            </div>

        <?php if(isset($issues[$i+1])){ ?>
            <div class="col-lg-4 text-center">
                <div class="panel panel-<?=$issues[$i+1]['type']?> ss">
                    <div class="panel-heading">
                        <a href="/profile/<?=$issues[$i+1]['id']?>"><?=$issues[$i+1]['nick']?></a>
                        <?php if($main_id === $issues[$i+1]['users_id']){?>
                            <a class="issue_deleter" href="/settings/deleteIssue/<?=$issues[$i+1]['id']?>" style="float: right">×&nbsp;</a>
                        <?php } ?>
                    </div>

                <div class="panel-body" style="height: 100px; overflow-y: auto;"><?=$issues[$i+1]['text']?></div>
            </div>
        </div>
        <?php }
        if(isset($issues[$i+2])){ ?>
            <div class="col-lg-4 text-center">
                <div class="panel panel-<?=$issues[$i+2]['type']?> ss">
                    <div class="panel-heading">
                        <a href="/profile/<?=$issues[$i+2]['id']?>"><?=$issues[$i+2]['nick']?></a>
                        <?php if($main_id === $issues[$i+2]['users_id']){?>
                            <a class="issue_deleter" href="/settings/deleteIssue/<?=$issues[$i+2]['id']?>" style="float: right">×&nbsp;</a>
                        <?php } ?>
                    </div>
                    <div class="panel-body" style="height: 100px; overflow-y: auto;"><?=$issues[$i+2]['text']?></div>
                </div>
            </div>
        <?php }
        } ?>
        </div>

    </div>
    <div class="col-lg-1"></div>
</div>

<div id="addIssue" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Add issue</h4>
            </div>
            <div class="modal-body text-center">
                <form role="form" action="/settings/addIssues" method="post">
                    <textarea name="comment" cols="40" rows="3" style="resize: none" required></textarea>
                    <div style="float: left" class="text-left">
                        <input type="radio" checked name="rad" value="info">&nbsp;Info<Br>
                        <input type="radio" name="rad" value="warning">&nbsp;Warning<Br>
                        <input type="radio" name="rad" value="danger">&nbsp;Danger<Br>
                    </div>
                    <Br>
                    <input class="text-right" type="submit" value="Submit">
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $('.issue_deleter').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'));
        $(this).closest('.ss').remove();
    });
</script>


<?php $this->endblock() ?>
