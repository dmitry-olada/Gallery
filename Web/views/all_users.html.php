<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>


<h3 class="text-center">All Users</h3>

<div class="allusers_table">
    <table class="table table-striped table-hover" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Nick</th>
                <th>Register</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($info as $item){ ?>
                <tr>
                    <td><?=$item['id'] ?></td>
                    <td><a href="/profile/<?=$item['id']?>"><?=$item['nick']?></a></td>
                    <td><?=$item['reg_date'] ?></td>
                </tr>
            <?php }?>
        <tbody>
    </table>
</div>

<div style="height: 400px">

</div>

<?php $this->endblock() ?>
