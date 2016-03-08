<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<h3 class="text-center">My Bookmarks</h3>

<br>

<div class="row bookmarks_content">
    <div class="col-lg-1 col-xs-2"></div>
    <div class="col-lg-9 col-xs-8">

        <table class="table bookmarks_table hidden-xs hidden-sm">
        <?php
        for($i = 0; $i < count($bookmarks); $i+=3) { ?>
            <tr>
                <td>
                    <div class="xx_table">
                    <?php
                    if(!empty($bookmarks[$i]['nick'])){ ?>
                        <a href="/profile/<?=$bookmarks[$i]['id']?>"><h4><?=$bookmarks[$i]['nick'] ?></h4>
                        <img src="<?=$bookmarks[$i]['avatar'] ?>" height="200"></a>
                        <br>
                    <?php } ?>
                    </div>
                </td>
                <td>
                    <div class="xx_table">
                    <?php
                    if(!empty($bookmarks[$i + 1]['nick'])){ ?>
                        <a href="/profile/<?=$bookmarks[$i + 1]['id']?>"><h4><?=$bookmarks[$i + 1]['nick'] ?></h4>
                        <img src="<?=$bookmarks[$i + 1]['avatar'] ?>" height="200" ></a>
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

        <div class="col-xs-8 hidden-lg"
            <?php
                foreach ($bookmarks as $item){ ?>
                <div class="col-xs-12 text-center hidden-lg">
                   <a href="/profile/<?=$item['id']?>"><h4 style="float: left"><?=$item['nick'] ?></h4>
                       <img src="<?=$item['avatar'] ?>" height="200"></a>
                    <br>
                </div>

            <?php } ?>
        </div>

    </div>

    <div class="hidden-lg col-xs-2"></div>

</div>


<?php $this->endblock() ?>