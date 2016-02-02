<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<h3 class="text-center">My Bookmarks</h3>

<br>

<div class="row bookmarks_content">
    <div class="col-lg-1"></div>
    <div class="col-lg-9">

        <table class="table bookmarks_table">
        <?php

        for($i = 0; $i < count($bookmarks)-1; $i+=3) { ?>
            <tr>
                <td>
                    <div class="xx_table">
                        <a href="/profile/<?=$bookmarks[$i]['id']?>"><h4><?=$bookmarks[$i]['nick'] ?></h4>
                        <img src="<?=$bookmarks[$i]['avatar'] ?>" height="200"></a>
                        <br>
                    </div>
                </td>
                <td>
                    <div class="xx_table">
                    <?php
                    if(!empty($bookmarks[$i + 1]['nick'])){ ?>
                        <a href="/profile/<?=$bookmarks[$i + 1]['id']?>"><h4><?=$bookmarks[$i + 1]['nick'] ?></h4>
                        <img src="<?=$bookmarks[$i + 1]['avatar'] ?>" height="200"></a>
                        <br>
                    <?php } ?>
                    </div>
                </td>
                <td>
                    <div class="xx_table">
                    <?php
                    if(!empty($bookmarks[$i + 2]['nick'])){ ?>
                        <a href="/profile/<?=$bookmarks[$i + 2]['id']?>"><h4><?=$bookmarks[$i + 2]['nick'] ?></h4>
                        <img src="<?=$bookmarks[$i + 2]['avatar'] ?>" height="200"></a>
                        <br>
                    <?php } ?>
                    </div>
                </td>
            </tr>
        <?php

        } ?>
        </table>

    </div>

</div>


    <div style="height: 500px"></div>


<?php $this->endblock() ?>