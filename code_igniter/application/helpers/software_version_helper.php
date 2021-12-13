<?php
if (!defined('BASEPATH')) {
     exit('No direct script access allowed');
}
#
#  Copyright 2003-2015 Opmantek Limited (www.opmantek.com)
#
#  ALL CODE MODIFICATIONS MUST BE SENT TO CODE@OPMANTEK.COM
#
#  This file is part of Open-AudIT.
#
#  Open-AudIT is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as published
#  by the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  Open-AudIT is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
#
#  You should have received a copy of the GNU Affero General Public License
#  along with Open-AudIT (most likely in a file named LICENSE).
#  If not, see <http://www.gnu.org/licenses/>
#
#  For further information on Open-AudIT or for a license other than AGPL please see
#  www.opmantek.com or email contact@opmantek.com
#
# *****************************************************************************

/*
* @category  Helper
* @package   Open-AudIT
* @author    Mark Unwin <marku@opmantek.com>
* @copyright 2014 Opmantek
* @license   http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
* @version   GIT: Open-AudIT_4.3.1
* @link      http://www.open-audit.org
 */

if (! function_exists('version_padded')) {
    function version_padded($version)
    {
        if (empty($version)) {
            return '';
        }
        $version_padded = '';
        $pieces = array();
        $pieces = preg_split("/[\s,\+\-\_\.\\\+\~]+/", $version);
        foreach ($pieces as $piece) {
            if (strlen($piece) > 10) {
                $version_padded .= $piece;
            } else {
                $version_padded .= mb_substr("00000000000000000000".$piece, -10);
            }
        }
        return $version_padded;
    }
}

/* End of file software_version_helper.php */
/* Location: ./system/application/helpers/software_version_helper.php */
