<div class="row">

    <div class="col-md-10 col-md-offset-1">

        <?=$this->draw('admin/menu');?>

        <h1>LTI Connections</h1>
        <p class="explanation">
            LTI connections allow you to create a single sign on connection between your Learning
            Management System and Known. Users who click through from a Learning Management System will be logged on or, if
            they have no existing account on this Known site, a new user will be created for them.
        </p>

    </div>

</div>
<?php

    if (!empty($vars['consumers'])) {
        foreach($vars['consumers'] as $consumer) {

            /* @var \LTI_Tool_Consumer $consumer */

?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <h3><?=$consumer->name?>
            </h3>
    </div>
    <div class="col-md-4 col-md-offset-1">
        <p>
            Key<br>
            <input type="text" style="width: 90%" value="<?=htmlspecialchars($consumer->getKey())?>">
        </p>
    </div>
    <div class="col-md-4">
        <p>
            Secret<br>
            <input type="text" style="width: 90%" value="<?=htmlspecialchars($consumer->secret)?>">
        </p>
    </div>
    <div class="col-md-8 col-md-offset-1">
        <p>
            Launch URL<br>
            <input type="text" style="width: 90%" value="<?=htmlspecialchars(\Idno\Core\site()->config()->getDisplayURL() . 'lti/callback/')?>">
        </p>
    </div>
    <div class="col-md-2">
        <p>
            &nbsp;
        </p>
    </div>
    <div class="col-md-4 col-md-offset-1">
        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>admin/lti/" method="post">
            <input type="submit" class="btn" value="Delete" onclick="confirm('Are you sure you want to remove this LTI connection?');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="key" value="<?=$consumer->getKey()?>">
            <?= \Idno\Core\site()->actions()->signForm('/admin/lti/') ?>
        </form>
    </div>
</div>
<?php

        }
    }

?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">

        <h2>Create a new LTI connection</h2>
        <p>
            To establish an LTI connection, you need to create access credentials. Once these have been created, you
            can enter them into your Learning Management System to generate a Single Sign On link.
        </p>
        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>admin/lti/" method="post">
            <p>
                Connection name: <input type="text" name="name" value="" placeholder="e.g., My College LMS" class="">
                <input type="submit" class="btn btn-primary" value="Create">
                <input type="hidden" name="action" value="create">
                <?= \Idno\Core\site()->actions()->signForm('/admin/lti/') ?>
            </p>
        </form>

    </div>
</div>