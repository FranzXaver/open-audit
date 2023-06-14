<?php
# Copyright © 2023 FirstWave. All Rights Reserved.
# SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace App\Controllers;

/**
 * PHP version 7.4
 *
 * @category  Controller
 * @package   Open-AudIT\Controller
 * @author    Mark Unwin <mark.unwin@firstwave.com>
 * @copyright 2023 FirstWave
 * @license   http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
 * @version   GIT: Open-AudIT_5.0.0
 * @link      http://www.open-audit.org
 */

/**
 * Base Object Scripts
 *
 * @access   public
 * @category Object
 * @package  Open-AudIT\Controller\Scripts
 * @author   Mark Unwin <mark.unwin@firstwave.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
 * @link     http://www.open-audit.org
 */
class Scripts extends BaseController
{
    public function download($id)
    {
        if (empty($this->scriptsModel)) {
            $this->scriptsModel = new \App\Models\scriptsModel();
        }
        if (!is_numeric($id)) {
            $id = $this->scriptsModel->getByOs($id);
        }
        $scriptContents = $this->scriptsModel->download($id);
        if ($scriptContents === false) {
            \Config\Services::session()->setFlashdata('danger', 'Cannot download script, please contact your Open-AudIT administrator.');
            return redirect()->route($this->config->homepage);
        }
        $script = $this->scriptsModel->read($id);
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $script[0]->attributes->name);
        if ($script[0]->attributes->based_on == 'audit_windows.vbs') {
            header("Content-Type: text/vbscript");
        } else {
            header("Content-Type: application/x-sh");
        }
        header('Content-Transfer-Encoding: binary');
        echo $scriptContents;
        return;
    }
}