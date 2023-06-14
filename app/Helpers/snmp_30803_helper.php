<?php
# Copyright © 2023 FirstWave. All Rights Reserved.
# SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

# Vendor Ricoh

$get_oid_details = function ($ip, $credentials, $oid) {
    $details = new \StdClass();
    $details->type = 'router';
    $details->manufacturer = 'Vyatta';
    $details->model = 'Vyatta Router';
    $details->os_group = 'Vyatta';
    $details->os_family = 'Vyatta VyOS';
    $details->os_name = my_snmp_get($ip, $credentials, "1.3.6.1.2.1.1.1.0");
    $details->os_version = str_replace('Vyatta VyOS ', '', $details->os_name);
    $details->hostname = my_snmp_get($ip, $credentials, '1.3.6.1.2.1.1.5.0');
    return($details);
};