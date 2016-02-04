<?php $this->layout('views::layout.html') ?>

<?php $this->block('content') ?>

<h3 class="text-center">Settings</h3>
<br>


<div class="row">
    <div class="col-lg-1"></div>

    <div class="col-lg-5">

        <div>
            <div class="settings_delimiter"></div>
            <div class="settings_changer">
                <p> Nick: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?=$nick?><button class="btn-primary settings_button" data-toggle="collapse" data-target="#change_nick">Change</button></p>
            </div>
            <div id="change_nick" class="collapse">
                <h4 class="text-center">Enter new nick</h4>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-9">
                        <form role="form" action="/settings/change/1" method="POST">
                             <input type="text" required name="new_nick">
                             <button type="submit" class="btn btn-warning">Select</button>
                        </form>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
                <br>
            </div>
            <div class="settings_delimiter"></div>

            <div class="settings_changer">
                <p> Avatar: <button class="btn-primary settings_button" data-toggle="collapse" data-target="#change_avatar">Change</button></p>
            </div>
            <div id="change_avatar" class="collapse">
                <h4 class="text-center">Enter link to new avatar</h4>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-9">
                        <form role="form" action="/settings/change/2" method="POST">
                            <input type="text" required name="new_avatar">
                            <button type="submit" class="btn btn-warning">Select</button>
                        </form>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
                <br>
            </div>
            <div class="settings_delimiter"></div>

            <div class="settings_changer">
                <p> Email: &nbsp &nbsp &nbsp &nbsp &nbsp <?=$email?> <button class="btn-primary settings_button" data-toggle="collapse" data-target="#change_email">Change</button></p>
            </div>
            <div id="change_email" class="collapse">
            <h4 class="text-center">Enter new email</h4>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-9">
                    <form role="form" action="/settings/change/3" method="POST">
                        <input type="text required name="new_email">
                        <button type="submit" class="btn btn-warning">Select</button>
                    </form>
                </div>
                <div class="col-lg-1"></div>
            </div>
            <br>
            </div>
            <div class="settings_delimiter"></div>

            <div class="settings_changer">
                <p> Password: &nbsp &nbsp &nbsp  <button class="btn-primary settings_button" data-toggle="collapse" data-target="#change_password">Change</button></p>
            </div>
            <div id="change_password" class="collapse">
                <h4 class="text-center">Change password</h4>
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        <form role="form" action="/settings/change/4" method="POST">
                            <p>Enter old password: <input style="float: right" type="password" required name="old_password"></p>
                            <p>Enter new password: <input style="float: right" type="password" required name="new_password"></p>
                            <p>Confirm new password: <input style="float: right" type="password" required name="conf_new_password"></p>
                            <p></p>
                            <div style="text-align: center"><button type="submit" class="btn btn-warning">Select</button></div>
                        </form>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
                <br>
            </div>
            <div class="settings_delimiter"></div>
        </div>
    </div>

    <div class="col-lg-1"></div>

    <div class="col-lg-4">
        <table class="table">
                <tr>
                    <td>Identifier: </td>
                    <td class="text-center"><?=$id?></td>
                </tr>
                <tr>
                    <td>Register Date: </td>
                    <td class="text-center"><?=$date?></td>
                </tr>
                <tr>
                    <td>My Albums: </td>
                    <td class="text-center"><?=$my_albums?></td>
                </tr>
                <tr>
                    <td>All Albums: </td>
                    <td class="text-center"><?=$albums?></td>
                </tr>
                <tr>
                    <td>Photos: </td>
                    <td class="text-center"><?=$photos?></td>
                </tr>
                <tr>
                    <td>Bookmarks: </td>
                    <td class="text-center"><?=$mybookmarks?></td>
                </tr>
        </table>


        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <a class="btn btn-primary div_center" href="/settings/all">Show All Users</a>
            </div>
            <div class="col-lg-3"></div>
        </div>

        <br>
    </div>


    <div style="height: 500px"></div>

</div>

<?php $this->endblock() ?>
