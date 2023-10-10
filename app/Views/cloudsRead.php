<?php
# Copyright © 2023 FirstWave. All Rights Reserved.
# SPDX-License-Identifier: AGPL-3.0-or-later
include 'shared/read_functions.php';
$style = @$user->toolbar_style;
if ($style === 'icontext') {
    $details_button = '<li class="nav-item" role="presentation"><a href="#details" class="nav-link" id="details-tab"><span style="margin-right:6px;" class="fa fa-eye text-success"></span>' . __('Details') . '</a></li>';
    $logs_button    = '<li class="nav-item" role="presentation"><a href="#logs"    class="nav-link" id="logs-tab"><span style="margin-right:6px;" class="fa fa-bars text-primary" ></span>' . __('Logs')    . '</a></li>';
    $devices_button = '<li class="nav-item" role="presentation"><a href="#devices" class="nav-link" id="devices-tab"><span style="margin-right:6px;" class="fa fa-desktop text-primary" ></span>' . __('Devices') . '</a></li>';
} else if ($style === 'icon') {
    $details_button = '<li class="nav-item" role="presentation"><a href="#details" class="nav-link" id="details-tab"><span style="margin-right:6px;" title="' . __('Details') . '" class="fa fa-eye text-success"></span></a></li>';
    $logs_button    = '<li class="nav-item" role="presentation"><a href="#logs"    class="nav-link" id="logs-tab"   ><span style="margin-right:6px;" title="' . __('Logs') .    '" class="fa fa-bars text-primary"></span></a></li>';
    $devices_button = '<li class="nav-item" role="presentation"><a href="#devices" class="nav-link" id="devices-tab"><span style="margin-right:6px;" title="' . __('Devices') . '" class="fa fa-desktop text-primary"></span></a></li>';
} else {
    $details_button = '<li class="nav-item" role="presentation"><a href="#details" class="nav-link" id="details-tab">' . __('Details') . '</a></li>';
    $logs_button    = '<li class="nav-item" role="presentation"><a href="#logs"    class="nav-link" id="logs-tab"   >' . __('Logs') .    '</a></li>';
    $devices_button = '<li class="nav-item" role="presentation"><a href="#devices" class="nav-link" id="devices-tab">' . __('Devices') . '</a></li>';
}
?>
        <main class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <?= read_card_header($meta->collection, $meta->id, $meta->icon, $user, $resource->name) ?>
                </div>
                <div class="card-body">

                    <div class="row text-center">
                        <div class="col-8 offset-2" style="background-color: rgba(var(--bs-body-color-rgb), 0.03);">
                            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                                <?= $details_button ?>
                                <?= $logs_button ?>
                                <?= $devices_button ?>
                            </ul>
                        </div>
                    </div>
                    <br/>

                    <div class="tab-content">
                        <div class="tab-pane" id="details" role="tabpanel" tabindex="0" aria-labelledby="details">
                            <div class="row">
                                <div class="col-6">
                                    <?= read_field('name', $resource->name, $dictionary->columns->name, $update) ?>
                                    <?= read_select('org_id', $resource->org_id, $dictionary->columns->org_id, $update, __('Organisation'), $orgs) ?>
                                    <?= read_field('description', $resource->description, $dictionary->columns->description, $update) ?>
                                    <?= read_select('options.ssh', $resource->options->ssh, $dictionary->columns->ssh, $update, __('Use SSH'), array()) ?>
                                    <?= read_select('options.wmi', $resource->options->wmi, $dictionary->columns->wmi, $update, __('Use WMI'), array()) ?>
                                    <?= read_select('options.snmp', $resource->options->snmp, $dictionary->columns->snmp, $update, __('Use SNMP'), array()) ?>
                                    <div class="row" style="padding-top:16px;">
                                        <div class="offset-2 col-8" style="position:relative;">
                                            <label for="type" class="form-label"><?= __('Type') ?></label>
                                            <div class="input-group">
                                                <select class="form-select" id="type" name="type" data-original-value="<?= $resource->type ?>" disabled>
                                                    <option value="amazon">Amazon AWS</option>
                                                    <option value="google">Google Compute</option>
                                                    <option value="microsoft">Microsoft Azure</option>
                                                </select>
                                            </div>
                                            <div class="form-text form-help pull-right" style="position: absolute; right: 0;" data-attribute="type" data-dictionary="<?= $dictionary->columns->type ?>"><span><br /></span></div>
                                        </div>
                                    </div>
                                    <?php if ($resource->type === 'amazon') {
                                        echo read_field('credentials.key', $resource->credentials->key, $dictionary->columns->key, $update, __('Credentials Key'));
                                        echo read_field('credentials.secret_key', '', $dictionary->columns->secret_key, $update, __('Credentials Secret'), '', '', 'password');
                                    } else if ($resource->type === 'microsoft') {
                                        echo read_field('credentials.subscription_id', $resource->credentials->subscription_id, $dictionary->columns->subscription_id, $update, __('Subscription ID'));
                                        echo read_field('credentials.tenant_id', $resource->credentials->tenant_id, $dictionary->columns->tenant_id, $update, __('Tenant ID'));
                                        echo read_field('credentials.client_id', $resource->credentials->client_id, $dictionary->columns->client_id, $update, __('Client ID'));
                                        echo read_field('credentials.client_secret', '', $dictionary->columns->client_secret, $update, __('Client Secret'), '', '', 'password');
                                    } else if ($resource->type === 'google') {
                                        echo read_field('credentials.json', '', $dictionary->columns->json, $update, __("Google JSON Credentials"), '', '', 'password');
                                    } ?>
                                    <?= read_field('edited_by', $resource->edited_by, $dictionary->columns->edited_by, false) ?>
                                    <?= read_field('edited_date', $resource->edited_date, $dictionary->columns->edited_date, false) ?>
                                </div>
                                <div class="col-6">
                                    <br />
                                    <div class="offset-2 col-8">
                                        <?php if (!empty($dictionary->about)) { ?>
                                            <h4 class="text-center"><?= __('About') ?></h4><br />
                                            <?= $dictionary->about ?>
                                        <?php } ?>
                                        <?php if (!empty($dictionary->notes)) { ?>
                                            <h4 class="text-center"><?= __('Notes') ?></h4><br />
                                            <?= $dictionary->notes ?>
                                        <?php } ?>
                                        <?php if (!empty($dictionary->columns)) { ?>
                                            <?php $fields = array('name', 'org_id', 'type', 'edited_by', 'edited_date') ?>
                                            <h4 class="text-center"><?= __('Fields') ?></h4><br />
                                            <?php foreach ($fields as $key) { ?>
                                            <code><?= $key ?>: </code><?= @$dictionary->columns->{$key} ?><br /><br />
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="tab-content">
                        <div class="tab-pane" id="logs" role="tabpanel" tabindex="0" aria-labelledby="logs">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[0,"asc"]]'>
                                        <thead>
                                            <tr>
                                                <th><?= __('ID') ?></th>
                                                <th><?= __('Timestamp') ?></th>
                                                <th><?= __('Severity') ?></th>
                                                <th><?= __('Message') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($included['logs'] as $log) { ?>
                                            <tr>
                                                <td><?= $log->id ?></td>
                                                <td><?= $log->timestamp ?></td>
                                                <td><?= $log->severity_text ?></td>
                                                <td><?= $log->message ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane" id="devices" role="tabpanel" tabindex="0" aria-labelledby="devices">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table <?= $GLOBALS['table'] ?> table-striped table-hover dataTable" data-order='[[2,"asc"]]'>
                                        <thead>
                                            <tr>
                                                <th style="min-width:6rem;" data-orderable="false" class="text-center"><?= __('View') ?></th>
                                                <th style="min-width:6rem;" data-orderable="false" class="text-center"></th>
                                                <th style="min-width:6rem;"><?= __('IP') ?></th>
                                                <th style="min-width:6rem;"><?= __('Name') ?></th>
                                                <th style="min-width:6rem;"><?= __('OS Group') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($included['devices'] as $device) { ?>
                                            <tr>
                                                    <td class="text-center"><a title=" <?= __('Devices') ?>" role="button" class="btn btn-sm btn-devices" href="<?= url_to('devicesRead', $device->{'devices.id'}) ?>"><span style="width:1rem;" title="<?= __('Devices') ?>" class="fa fa-desktop" aria-hidden="true"></span></a></td>
                                                <td class="text-center"><img style="width:30px;" src="/open-audit/device_images/<?= $device->{'devices.icon'} ?>.svg" alt=""/></td>
                                                <td><span style="display:none;"><?= $device->{'devices.padded_ip'} ?></span><?= $device->{'devices.ip'} ?></td>
                                                <td><?= $device->{'devices.name'} ?></td>
                                                <td><?= $device->{'devices.os_group'} ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </main>

<script {csp-script-nonce}>
window.onload = function () {
    $(document).ready(function() {
        $("#type").val("<?= $resource->type ?>");

        <?php if (isset($resource->credentials->client_secret)) {
            if ($resource->credentials->client_secret !== '') { ?>
                $("#credentials\\.client_secret").attr("placeholder", "<?= __("removed from display, but has been set") ?>");
            <?php } else { ?>
                $("#credentials\\.client_secret").attr("placeholder", "<?= __("has not been set") ?>");
            <?php }
        } ?>

        <?php if (isset($resource->credentials->json)) {
            if ($resource->credentials->json !== '') { ?>
                $("#credentials\\.json").attr("placeholder", "<?= __("removed from display, but has been set") ?>");
            <?php } else { ?>
                $("#credentials\\.json").attr("placeholder", "<?= __("has not been set") ?>");
            <?php }
        } ?>

        <?php if (isset($resource->credentials->secret_key)) {
            if ($resource->credentials->secret_key !== '') { ?>
                $("#credentials\\.secret_key").attr("placeholder", "<?= __("removed from display, but has been set") ?>");
            <?php } else { ?>
                $("#credentials\\.secret_key").attr("placeholder", "<?= __("has not been set") ?>");
            <?php }
        } ?>
    });
}
</script>

<script {csp-script-nonce}>
window.onload = function () {
    $(document).ready(function () {

        var hash = window.location.hash;
        if (hash == "") {
            hash = "#details"
        }
        hash && $('ul.nav.nav-pills a[href="' + hash + '"]').tab('show');

        $('ul.nav.nav-pills a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
            window.location.hash = this.hash;
        });

        $(".nav-link").click(function(e) {
            window.scrollTo(0, 0);
        });

    });
}
</script>