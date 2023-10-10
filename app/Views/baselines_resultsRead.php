<?php
# Copyright © 2023 FirstWave. All Rights Reserved.
# SPDX-License-Identifier: AGPL-3.0-or-later
include 'shared/read_functions.php';
$delete = false;
if (strpos($user->permissions[$meta->collection], 'd') !== false) {
    $delete = true;
}
$extra = '';
$extra_id = 0;
foreach ($meta->filter as $filter) {
    if ($filter->name === 'policy_id') {
        $extra = 'policy';
        $extra_id = intval($filter->value);
    }
    if ($filter->name === 'device_id') {
        $extra = 'device';
        $extra_id = intval($filter->value);
    }
}
if ($extra === 'policy') {
    foreach ($resource->result->policy as $key) {
        if ($key->id !== '' and $key->id === $extra_id) {
            $policy = $key;
        }
    }
}
$style = @$user->toolbar_style;
$single_policy_button = '';
$single_device_button = '';
if ($style === 'icontext') {
    $details_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2 active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true" title="' . __('Details') . '"><span style="margin-right:6px;" class="fa fa-eye"></span>' . __('Details') . '</button></li>';

    #$details_button = '<li class="nav-item" role="presentation"><a role="button" href="#details" class="btn btn-light" data-bs-toggle="tab" title="' . __('Details') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Details') . '</a></li>';

    $policy_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="policies-tab" data-bs-toggle="tab" data-bs-target="#policies" type="button" role="tab" aria-controls="policies" aria-selected="false" title="' . __('Policy Results') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Policy Results') . '</button></li>';

    #$policy_button = '<li class="nav-item" role="presentation"><a role="button" href="#policies" class="btn btn-light nav-link" data-bs-toggle="tab" title="' . __('Policy Results') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Policy Results') . '</a></li>';

    $device_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="devices-tab" data-bs-toggle="tab" data-bs-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false" title="' . __('Device Results') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Device Results') . '</button></li>';

    #$device_button = '<li class="nav-item" role="presentation"><a role="button" href="#devices" class="btn btn-light" data-bs-toggle="tab" title="' . __('Device Results') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Device Results') . '</a></li>';

    if ($extra === 'policy') {
        $single_policy_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy" type="button" role="tab" aria-controls="policy" aria-selected="false" title="' . __('Policy Result') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Policy Result') . '</button></li>';

        #$single_policy_button = '<li class="nav-item" role="presentation"><a role="button" href="#policy" class="btn btn-light" data-bs-toggle="tab" title="' . __('Policy Result') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Policy Result') . '</a></li>';
    }

    if ($extra === 'device') {
        $single_device_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="device-tab" data-bs-toggle="tab" data-bs-target="#device" type="button" role="tab" aria-controls="device" aria-selected="false" title="' . __('Device Result') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Device Result') . '</button></li>';

        #$single_policy_button = '<li class="nav-item" role="presentation"><a role="button" href="#policy" class="btn btn-light" data-bs-toggle="tab" title="' . __('Policy Result') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span>' . __('Policy Result') . '</a></li>';
    }
} else if ($style === 'icon') {
    $details_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2 active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true" title="' . __('Details') . '"><span class="fa fa-eye"></span></button></li>';

    $policy_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab" aria-controls="results" aria-selected="false" title="' . __('Policy Results') . '"><span class="fa-solid fa-square-poll-horizontal"></span></button></li>';

    $device_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="devices-tab" data-bs-toggle="tab" data-bs-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false" title="' . __('Device Results') . '"><span style="margin-right:6px;" class="fa-solid fa-square-poll-horizontal"></span></button></li>';
} else {
    $details_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2 active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true" title="' . __('Details') . '">' . __('Details') . '</button></li>';

    $policy_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab" aria-controls="results" aria-selected="false" title="' . __('Policy Results') . '">' . __('Policy Results') . '</button></li>';

    $device_button = '<li class="nav-item" role="presentation"><button class="btn btn-light mb-2" id="devices-tab" data-bs-toggle="tab" data-bs-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false" title="' . __('Device Results') . '">' . __('Device Results') . '</button></li>';
}
?>
        <main class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <?= read_card_header($meta->collection, $meta->id, $meta->icon, $user, $data[0]->attributes->name) ?>
                </div>
                <div class="card-body">

                    <div class="row text-center">
                        <div class="col-10 offset-1">
                            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                                <?= $details_button ?>
                                <?= $policy_button ?>
                                <?= $device_button ?>
                                <?= $single_policy_button ?>
                                <?= $single_device_button ?>
                            </ul>
                        </div>
                    </div>
                    <br/>

                    <div class="tab-content">
                        <div class="tab-pane active" id="details" role="tabpanel" tabindex="0">
                            <div class="row">
                                <div class="col-6">
                                    <?= read_field('baseline', $resource->{'baselines.name'}) ?>
                                    <?= read_field('timestamp', $resource->timestamp) ?>
                                    <?= read_field('group', $resource->result->group_name) ?>
                                    <?= read_field('device_count', $resource->result->devices, '', false, 'Devices') ?>
                                    <?= read_field('pass', $resource->result->pass) ?>
                                    <?= read_field('fail', $resource->result->fail) ?>
                                </div>
                                <div class="col-6">
                                    <br>
                                    <div class="offset-2 col-8">
                                        <?php if (!empty($dictionary->about)) { ?>
                                            <h4 class="text-center"><?= __('About') ?></h4><br>
                                            <?= $dictionary->about ?>
                                        <?php } ?>
                                        <?php if (!empty($dictionary->notes)) { ?>
                                            <h4 class="text-center"><?= __('Notes') ?></h4><br>
                                            <?= $dictionary->notes ?>
                                        <?php } ?>
                                        <?php if (!empty($dictionary->columns)) { ?>
                                            <?php $fields = array('timestamp', 'group', 'devices') ?>
                                        <h4 class="text-center"><?= __('Fields') ?></h4><br>
                                            <?php foreach ($fields as $key) { ?>
                                            <code><?= $key ?>: </code><?= $dictionary->columns->{$key} ?><br><br>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="policies" role="tabpanel" tabindex="0">
                            <br>
                            <div class="row">
                                <h3 class="text-center">Policies: <?= $resource->result->policies ?>, Pass: <span class="text-success"><?= $resource->result->pass ?></span>, Fail: <span class="text-danger"><?= $resource->result->fail ?></span></h3><br />
                            </div>
                            <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[1,"asc"]]'>
                                <thead>
                                    <tr>
                                        <th class="text-center"><?= __('View') ?></th>
                                        <th><?= __('Type') ?></th>
                                        <th><?= __('Policy') ?></th>
                                        <th class="text-center"><?= __('Pass') ?></th>
                                        <th class="text-center"><?= __('Fail') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($resource->result->policy)) { ?>
                                    <?php foreach ($resource->result->policy as $row) { ?>
                                    <tr>
                                        <td class="text-center"><a href="<?= url_to('baselines_resultsRead', $meta->id) ?>?policy_id=<?= $row->id ?>#policy" role="button" class="btn btn-primary btn-sm"><span class="fa fa-eye" aria-hidden="true"></span></a></td>
                                        <td><?= $row->table ?></td>
                                        <td><?= html_entity_decode($row->name) ?></td>
                                        <td class="text-center"><a href="#" role="button" class="btn btn-success btn-sm"><strong><?= $row->pass ?></strong></a></td>
                                        <td class="text-center"><a href="#" role="button" class="btn btn-danger  btn-sm"><strong><?= $row->fail ?></strong></a></td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="devices" role="tabpanel" tabindex="0">
                            <br>
                            <div class="row">
                                <h3 class="text-center">Devices: <?= $resource->result->devices ?>, Pass: <span class="text-success"><?= $resource->result->pass ?></span>, Fail: <span class="text-danger"><?= $resource->result->fail ?></span></h3><br />
                            </div>
                            <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[1,"asc"]]'>
                                <thead>
                                    <tr>
                                        <th class="text-center"><?= __('View') ?></th>
                                        <th><?= __('Device') ?></th>
                                        <th class="text-center"><?= __('Pass') ?></th>
                                        <th class="text-center"><?= __('Fail') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($resource->result->device_list)) { ?>
                                    <?php foreach ($resource->result->device_list as $row) { ?>
                                    <tr>
                                        <td class="text-center"><a href="<?= url_to('baselines_resultsRead', $meta->id) ?>?device_id=<?= $row->id ?>" role="button" class="btn btn-primary btn-sm"><span class="fa fa-eye" aria-hidden="true"></span></a></td>
                                        <td><?= $row->name ?></td>
                                        <td class="text-center"><a href="#" role="button" class="btn btn-success btn-sm"><strong><?= $row->pass ?></strong></a></td>
                                        <td class="text-center"><a href="#" role="button" class="btn btn-danger  btn-sm"><strong><?= $row->fail ?></strong></a></td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($extra === 'policy') { ?>
                        <div class="tab-pane" id="policy" role="tabpanel" tabindex="0">
                            <br>
                            <div class="row">
                                <h3 class="text-center"><?= html_entity_decode($policy->name) ?>, Pass: <span class="text-success"><?= $policy->pass ?></span>, Fail: <span class="text-danger"><?= $policy->fail ?></span></h3><br />
                            </div>
                            <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[1,"asc"]]'>
                                <thead>
                                    <tr>
                                        <th class="text-center"><?= __('View') ?></th>
                                        <th><?= __('Device') ?></th>
                                        <?php if ($policy->table === 'software') { ?>
                                        <th><?= __('Name') ?></th>
                                        <th><?= __('Version') ?></th>
                                        <?php } else if ($policy->table === 'netstat') { ?>
                                        <th><?= __('Program') ?></th>
                                        <th><?= __('Protocol') ?></th>
                                        <th><?= __('Port') ?></th>
                                        <?php } else if ($policy->table === 'user') { ?>
                                        <th><?= __('Name') ?></th>
                                        <th><?= __('Type') ?></th>
                                        <th><?= __('Status') ?></th>
                                        <?php } ?>
                                        <th class="text-center"><?= __('Status') ?></th>
                                        <th class="text-center"><?= __('Update') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($policy->result as $row) { ?>
                                    <tr>
                                        <td class="text-center"><a href="<?= url_to('baselines_resultsRead', $meta->id) ?>?device_id=<?= $row->id ?>" role="button" class="btn btn-primary btn-sm"><span class="fa fa-eye" aria-hidden="true"></span></a></td>
                                        <td><?= $row->name ?></td>
                                        <td><?= @$row->test1 ?></td>
                                        <td><?= @$row->test2 ?></td>
                                        <?php if ($policy->table !== 'software') { ?>
                                        <td><?= @$row->test3 ?></td>
                                        <?php } ?>
                                        <td class="text-center">
                                            <span style="display:none"><?= $row->status ?></span>
                                            <?php if ($row->status === 'fail') { ?>
                                                <button class="btn btn-sm btn-danger"><span style="width:1rem;" class="fa fa-xmark" aria-hidden="true"></span></button>
                                            <?php } else { ?>
                                                <button class="btn btn-sm btn-success"><span style="width:1rem;" class="fa fa-check" aria-hidden="true"></span></button>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                        <?php if ($policy->table === 'software' and !empty($row->test1) and $row->status === 'fail') { ?>
                                            <a href="<?= url_to('baselines_policiesRead', $policy->id) ?>" role="button" class="btn btn-sm btn-warning"><span style="width:1rem;" class="fa-regular fa-pen-to-square" aria-hidden="true"></span></a>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>


                        <?php if ($extra === 'device') { ?>
                        <div class="tab-pane" id="device" role="tabpanel" tabindex="0">
                            <br>
                            <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[1,"asc"]]'>
                                <thead>
                                    <tr>
                                        <th class="text-center"><?= __('View') ?></th>
                                        <th><?= __('Type') ?></th>
                                        <th><?= __('Policy') ?></th>
                                        <th><?= __('Test 1') ?></th>
                                        <th><?= __('Test 2') ?></th>
                                        <th><?= __('Test 3') ?></th>
                                        <th class="text-center"><?= __('Status') ?></th>
                                        <th class="text-center"><?= __('Update') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($resource->result->policy as $key) {
                                    foreach ($key->result as $row) {
                                        if ($row->id === $extra_id) { ?>
                                        <tr>
                                            <td class="text-center"><a href="<?= url_to('baselines_policiesRead', $key->id) ?>" role="button" class="btn btn-primary btn-sm"><span class="fa fa-eye" aria-hidden="true"></span></a></td>
                                            <td><?= $key->table ?></td>
                                            <td><?= html_entity_decode($key->name) ?></td>
                                            <td><?= $row->test1 ?></td>
                                            <td><?= $row->test2 ?></td>
                                            <td><?= $row->test3 ?></td>
                                            <td class="text-center">
                                                <span style="display:none"><?= $row->status ?></span>
                                            <?php if ($row->status ==='fail') { ?>
                                                <button class="btn btn-sm btn-danger"><span style="width:1rem;" class="fa fa-xmark" aria-hidden="true"></span></button>
                                            <?php } else { ?>
                                                <button class="btn btn-sm btn-success"><span style="width:1rem;" class="fa fa-check" aria-hidden="true"></span></button>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center">
                                            <?php if (!empty($row->test1) and $row->status === 'fail') { ?>
                                                <a href="<?= url_to('baselines_policiesRead', $key->id) ?>" role="button" class="btn btn-sm btn-warning"><span class="fa-regular fa-pen-to-square" aria-hidden="true"></span></a>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </main>

<script {csp-script-nonce}>
window.onload = function () {
    $(document).ready(function() {
        $("#button_create").remove();
        $("#button_export_json").remove();
        <?php if (empty($user->toolbar_style) or $user->toolbar_style === 'icontext') { ?>
            $(".page-title-right").append("<a style=\"margin-right:6px;\" role=\"button\" id=\"button_export_policy\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Policy") ?>\" href=\"<?= url_to('baselines_resultsExportPolicy', $resource->id) ?>\"><span style=\"margin-right:6px;\" class=\"fa fa-arrow-up-right-from-square\"></span><?= __("Export by Policy") ?></a>");
            $(".page-title-right").append("<a role=\"button\" id=\"button_export_device\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Device") ?>\" href=\"<?= url_to('baselines_resultsExportDevice', $resource->id) ?>\"><span style=\"margin-right:6px;\" class=\"fa fa-arrow-up-right-from-square\"></span><?= __("Export by Device") ?></a>");
        <?php } else if ($user->toolbar_style === 'icon') { ?>
            $(".page-title-right").append("<a style=\"margin-right:6px;\" role=\"button\" id=\"button_export_policy\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Policy") ?>\" href=\"<?= url_to('baselines_resultsExportPolicy', $resource->id) ?>\"><span style=\"margin-right:6px;\" class=\"fa fa-arrow-up-right-from-square\"></span></a>");
            $(".page-title-right").append("<a role=\"button\" id=\"button_export_device\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Device") ?>\" href=\"<?= url_to('baselines_resultsExportDevice', $resource->id) ?>\"><span style=\"margin-right:6px;\" class=\"fa fa-arrow-up-right-from-square\"></span></a>");
        <?php } else if ($user->toolbar_style === 'text') { ?>
            $(".page-title-right").append("<a style=\"margin-right:6px;\" role=\"button\" id=\"button_export_policy\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Policy") ?>\" href=\"<?= url_to('baselines_resultsExportPolicy', $resource->id) ?>\"><?= __("Export by Policy") ?></a>");
            $(".page-title-right").append("<a role=\"button\" id=\"button_export_device\" class=\"btn btn-light mb-2\" title=\"<?= __("Export by Device") ?>\" href=\"<?= url_to('baselines_resultsExportDevice', $resource->id) ?>\"><?= __("Export by Device") ?></a>");
        <?php } ?>

    });

    $(document).ready(function() {
        $(function(){
          hash = "#details";
          if (window.location.hash !== "") {
            var hash = window.location.hash;
          }
          hash && $('ul.nav.nav-pills a[href="' + hash + '"]').tab('show');
          $('ul.nav.nav-pills a').click(function (e) {
             $(this).tab('show');
             $('body').scrollTop();
             /* window.scrollTo(0, 0); */
             window.location.hash = this.hash;
             $('body').scrollTop();
          });
        });
    });

    $(document).ready(function () {
        $(".nav-link").click(function(e) {
            console.log("Prevent Default");
            window.scrollTo(0, 0);
        });
    });

}
</script>