<?php
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

/**
 * @author Mark Unwin <marku@opmantek.com>
 *
 * @version 1.6.6
 *
 * @copyright Copyright (c) 2014, Opmantek
 * @license http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
 */
class M_oa_general extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the attribute or attributes from the system_id.
     *
     * @access	public
     *
     * @param	table & attribute & system_id
     *
     * @return string | array of objects
     */
    public function get_attribute($table = 'system', $attribute = 'hostname', $system_id = '')
    {
        if ($system_id === '') {
            return('');
        }
        if ((strpos($attribute, ",") !== false) or ($attribute == "*")) {
            $limit = "";
        } else {
            $limit = "LIMIT 1";
        }
        $sql = "SELECT $attribute FROM $table WHERE system_id = ? ".$limit;
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);

        #if ($attribute === 'man_status'){
            #echo $this->db->last_query() . "<br />\n";
            #print_r($query->row());
        #}

        if ((strpos($attribute, ",") !== false) or ($attribute == "*")) {
            $result = $query->result();

            return ($result);
        } else {
            $row = $query->row();
            if (isset($row->$attribute)) {
                return ($row->$attribute);
            } else {
                return('');
            }
        }
    }

    public function get_system_attribute_api($table = 'system', $attribute = 'hostname', $system_id = '')
    {
        if ($table == 'system') {
            $sql = "SELECT $attribute FROM $table WHERE system_id = ? ";
        } else {
            $sql = "SELECT $table.$attribute FROM $table LEFT JOIN system ON ($table.system_id = system.system_id AND $table.timestamp = system.timestamp) WHERE system.system_id = ? ";
        }
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);
        $result = $query->result();

        return ($result);
    }

    public function get_system_document_api($table = '', $system_id = '')
    {
        if ($table == '') {
            return;
        }
        if ($table == 'system') {
            $sql = 'SELECT system_id, hostname, fqdn, man_ip_address, man_type, man_class, os_version, man_function, man_environment, man_status, man_description, man_os_group, man_os_family, man_os_name, man_manufacturer, man_model, man_serial, man_form_factor, man_vm_group, uptime, location_name, last_seen, last_seen_by, icon, snmp_oid, sysDescr, sysObjectID, sysUpTime, sysContact, sysName, sysLocation FROM system LEFT JOIN oa_location ON system.man_location_id = oa_location.location_id WHERE system_id = ?';
        } elseif ($table == 'sys_hw_bios') {
            $sql = 'SELECT bios_description, bios_manufacturer, bios_serial, bios_smversion, bios_version, bios_asset_tag FROM sys_hw_bios LEFT JOIN system ON system.system_id = sys_hw_bios.system_id AND system.timestamp = sys_hw_bios.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_group') {
            $sql = 'SELECT group_name, group_description, group_sid, group_members FROM sys_sw_group LEFT JOIN system ON system.system_id = sys_sw_group.system_id AND system.timestamp = sys_sw_group.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_hard_drive') {
            $sql = 'SELECT hard_drive_caption, hard_drive_index, hard_drive_interface_type, hard_drive_manufacturer, hard_drive_model, hard_drive_serial, hard_drive_partitions, hard_drive_scsi_bus, hard_drive_scsi_logical_unit, hard_drive_scsi_port, hard_drive_size, hard_drive_status, hard_drive_firmware FROM sys_hw_hard_drive LEFT JOIN system ON system.system_id = sys_hw_hard_drive.system_id AND system.timestamp = sys_hw_hard_drive.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_software') {
            $sql = 'SELECT software_name, software_url, software_email, software_version, software_publisher, date(software_installed_on) as software_installed_on, software_installed_by FROM sys_sw_software LEFT JOIN system ON system.system_id = sys_sw_software.system_id AND system.timestamp = sys_sw_software.timestamp WHERE system.system_id = ? ';
        } elseif ($table == 'sys_hw_network_card_ip') {
            $sql = 'SELECT ip_address_v4, ip_address_v6, ip_subnet, ip_address_version, sys_hw_network_card_ip.net_mac_address, net_connection_id FROM sys_hw_network_card_ip LEFT JOIN system ON system.system_id = sys_hw_network_card_ip.system_id AND system.timestamp = sys_hw_network_card_ip.timestamp LEFT JOIN sys_hw_network_card ON sys_hw_network_card_ip.net_index = sys_hw_network_card.net_index AND sys_hw_network_card_ip.system_id = sys_hw_network_card.system_id WHERE system.system_id = ? GROUP BY sys_hw_network_card_ip.ip_id';
        } elseif ($table == 'sys_hw_memory') {
            $sql = 'SELECT memory_bank, memory_type, memory_form_factor, memory_detail, memory_capacity, memory_speed, memory_tag, memory_serial FROM sys_hw_memory LEFT JOIN system ON system.system_id = sys_hw_memory.system_id AND system.timestamp = sys_hw_memory.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_motherboard') {
            $sql = 'SELECT sys_hw_motherboard.manufacturer, sys_hw_motherboard.memory_slots, sys_hw_motherboard.model, sys_hw_motherboard.processor_slots, sys_hw_motherboard.processor_type, sys_hw_motherboard.serial FROM sys_hw_motherboard LEFT JOIN system ON system.system_id = sys_hw_motherboard.system_id AND system.timestamp = sys_hw_motherboard.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_netstat') {
            $sql = 'SELECT protocol, ip_address, port, program FROM sys_sw_netstat LEFT JOIN system ON system.system_id = sys_sw_netstat.system_id AND system.timestamp = sys_sw_netstat.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_network_card') {
            $sql = 'SELECT net_connection_id, net_mac_address, CASE WHEN net_index IS NULL OR net_index = "" THEN net_connection_id ELSE net_index END AS net_index, net_model, net_description, man_manufacturer, net_speed, net_connection_status, net_adapter_type, net_dhcp_enabled, net_dhcp_server, net_dhcp_lease_obtained, net_dhcp_lease_expires, net_dns_domain, net_dns_server, net_dns_domain_reg_enabled FROM sys_hw_network_card LEFT JOIN system ON system.system_id = sys_hw_network_card.system_id AND system.timestamp = sys_hw_network_card.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_partition') {
            $sql = 'SELECT hard_drive_index, partition_mount_type, partition_mount_point, partition_name, partition_size, partition_free_space, partition_used_space, partition_format, partition_caption, partition_disk_index, partition_bootable, partition_type, partition_quotas_supported, partition_quotas_enabled, partition_serial FROM sys_hw_partition LEFT JOIN system ON system.system_id = sys_hw_partition.system_id AND system.timestamp = sys_hw_partition.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_processor') {
            $sql = 'SELECT processor_description, processor_speed, processor_count, processor_cores, processor_logical, processor_manufacturer FROM sys_hw_processor LEFT JOIN system ON system.system_id = sys_hw_processor.system_id AND system.timestamp = sys_hw_processor.timestamp WHERE system.system_id = ?';
            #$sql = 'SELECT description AS processor_description, speed AS processor_speed, count AS processor_count, cores AS processor_cores, logical AS processor_logical, manufacturer AS processor_manufacturer FROM sys_hw_processor WHERE current = "y" AND system_id = ?';
        } elseif ($table == 'sys_sw_route') {
            $sql = 'SELECT destination, next_hop, mask, metric, protocol, sys_sw_route.type FROM sys_sw_route LEFT JOIN system ON system.system_id = sys_sw_route.system_id AND system.timestamp = sys_sw_route.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_service') {
            $sql = 'SELECT service_display_name, service_name, service_start_mode, service_start_name, service_state FROM sys_sw_service LEFT JOIN system ON system.system_id = sys_sw_service.system_id AND system.timestamp = sys_sw_service.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_share') {
            $sql = 'SELECT share_name, share_size, share_caption, share_path FROM sys_sw_share LEFT JOIN system ON system.system_id = sys_sw_share.system_id AND system.timestamp = sys_sw_share.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_user') {
            $sql = 'SELECT user_name, user_caption, user_sid, user_domain, user_disabled, user_full_name, user_password_changeable, user_password_expires, user_password_required, user_status, user_type FROM sys_sw_user LEFT JOIN system ON system.system_id = sys_sw_user.system_id AND system.timestamp = sys_sw_user.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_variable') {
            $sql = 'SELECT variable_name, variable_value FROM sys_sw_variable LEFT JOIN system ON system.system_id = sys_sw_variable.system_id AND system.timestamp = sys_sw_variable.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_windows') {
            $sql = 'SELECT windows_build_number, windows_user_name, windows_client_site_name, windows_domain_short, windows_domain_controller_address, windows_domain_controller_name, windows_domain_role, windows_part_of_domain, windows_time_caption, windows_time_daylight, windows_boot_device, windows_country_code, windows_organisation, windows_language, windows_registered_user, windows_service_pack, windows_version, windows_install_directory, windows_active_directory_ou FROM sys_sw_windows LEFT JOIN system ON system.system_id = sys_sw_windows.system_id AND system.timestamp = sys_sw_windows.timestamp WHERE system.system_id = ?';
        }
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);
        $result = $query->result();

        return ($result);
    }

    public function get_system_document_api_new($table = '', $system_id = '')
    {
        if ($table == '') {
            return;
        }
        $sql = '';
        if ($table == 'system') {
            $sql = 'SELECT system.* FROM system LEFT JOIN oa_location ON system.man_location_id = oa_location.location_id WHERE system_id = ?';
        } elseif ($table == 'sys_hw_bios') {
            $sql = 'SELECT bios_description, bios_manufacturer, bios_serial, bios_smversion, bios_version, bios_asset_tag FROM sys_hw_bios LEFT JOIN system ON system.system_id = sys_hw_bios.system_id AND system.timestamp = sys_hw_bios.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_motherboard') {
            $sql = 'SELECT sys_hw_motherboard.* FROM sys_hw_motherboard LEFT JOIN system ON system.system_id = sys_hw_motherboard.system_id AND system.timestamp = sys_hw_motherboard.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_module') {
            $sql = 'SELECT sys_hw_module.* FROM sys_hw_module WHERE system_id = ?';
        } elseif ($table == 'sys_sw_group') {
            $sql = 'SELECT group_name, group_description, group_sid, group_members FROM sys_sw_group LEFT JOIN system ON system.system_id = sys_sw_group.system_id AND system.timestamp = sys_sw_group.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_hard_drive') {
            $sql = 'SELECT hard_drive_caption, hard_drive_index, hard_drive_model_family, hard_drive_interface_type, hard_drive_manufacturer, hard_drive_model, hard_drive_serial, hard_drive_partitions, hard_drive_scsi_bus, hard_drive_scsi_logical_unit, hard_drive_scsi_port, hard_drive_size, hard_drive_status, hard_drive_firmware FROM sys_hw_hard_drive LEFT JOIN system ON system.system_id = sys_hw_hard_drive.system_id AND system.timestamp = sys_hw_hard_drive.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_software') {
            $sql = 'SELECT software_name, software_url, software_email, software_version, software_publisher, date(software_installed_on) as software_installed_on, software_installed_by FROM sys_sw_software LEFT JOIN system ON system.system_id = sys_sw_software.system_id AND system.timestamp = sys_sw_software.timestamp WHERE system.system_id = ? ';
        } elseif ($table == 'sys_sw_software_history_delta') {
            $sql = 'SELECT sys_sw_software.software_id, sys_sw_software.software_name, sys_sw_software.software_version, sys_sw_software.first_timestamp, sys_sw_software.timestamp, IF(sys_sw_software.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_software.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_software LEFT JOIN system ON (sys_sw_software.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_software_history_full') {
            $sql = 'SELECT sys_sw_software.software_id, sys_sw_software.software_name, sys_sw_software.software_version, sys_sw_software.first_timestamp, sys_sw_software.timestamp, IF(sys_sw_software.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_software.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_software LEFT JOIN system ON (sys_sw_software.system_id = system.system_id) WHERE system.system_id = ? AND (system.first_timestamp = sys_sw_software.first_timestamp OR system.timestamp = sys_sw_software.timestamp)';
        } elseif ($table == 'sys_sw_software_key') {
            $sql = 'SELECT sys_sw_software_key.* FROM sys_sw_software_key LEFT JOIN system ON system.system_id = sys_sw_software_key.system_id WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_network_card_ip') {
            $sql = 'SELECT sys_hw_network_card_ip.* FROM sys_hw_network_card_ip LEFT JOIN system ON system.system_id = sys_hw_network_card_ip.system_id AND system.timestamp = sys_hw_network_card_ip.timestamp LEFT JOIN sys_hw_network_card ON sys_hw_network_card_ip.net_index = sys_hw_network_card.net_index WHERE system.system_id = ? GROUP BY sys_hw_network_card_ip.ip_id';
        } elseif ($table == 'sys_hw_memory') {
            $sql = 'SELECT memory_bank, memory_type, memory_form_factor, memory_detail, memory_capacity, memory_speed, memory_tag, memory_serial FROM sys_hw_memory LEFT JOIN system ON system.system_id = sys_hw_memory.system_id AND system.timestamp = sys_hw_memory.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_motherboard') {
            $sql = 'SELECT sys_hw_motherboard.manufacturer, sys_hw_motherboard.memory_slots, sys_hw_motherboard.model, sys_hw_motherboard.processor_slots, sys_hw_motherboard.processor_type, sys_hw_motherboard.serial FROM sys_hw_motherboard LEFT JOIN system ON system.system_id = sys_hw_motherboard.system_id AND system.timestamp = sys_hw_motherboard.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_netstat') {
            $sql = 'SELECT protocol, ip_address, port, program FROM sys_sw_netstat LEFT JOIN system ON system.system_id = sys_sw_netstat.system_id AND system.timestamp = sys_sw_netstat.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_netstat_history_delta') {
            $sql = 'SELECT sys_sw_netstat.protocol, sys_sw_netstat.port, sys_sw_netstat.ip_address, sys_sw_netstat.program, sys_sw_netstat.first_timestamp, sys_sw_netstat.timestamp, IF(sys_sw_netstat.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_netstat.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_netstat LEFT JOIN system ON (sys_sw_netstat.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_netstat_history_full') {
            $sql = 'SELECT sys_sw_netstat.protocol, sys_sw_netstat.port, sys_sw_netstat.ip_address, sys_sw_netstat.program, sys_sw_netstat.first_timestamp, sys_sw_netstat.timestamp, IF(sys_sw_netstat.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_netstat.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_netstat LEFT JOIN system ON (sys_sw_netstat.system_id = system.system_id) WHERE system.system_id = ? AND (system.first_timestamp = sys_sw_netstat.first_timestamp OR system.timestamp = sys_sw_netstat.timestamp)';
        } elseif ($table == 'sys_hw_network_card') {
            $sql = 'SELECT sys_hw_network_card.*, CASE WHEN net_index IS NULL OR net_index = "" THEN net_connection_id ELSE net_index END AS net_index FROM sys_hw_network_card LEFT JOIN system ON system.system_id = sys_hw_network_card.system_id AND system.timestamp = sys_hw_network_card.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_optical_drive') {
            $sql = 'SELECT sys_hw_optical_drive.* FROM sys_hw_optical_drive LEFT JOIN system ON system.system_id = sys_hw_optical_drive.system_id AND system.timestamp = sys_hw_optical_drive.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_partition') {
            $sql = 'SELECT hard_drive_index, partition_device_id, partition_mount_type, partition_mount_point, partition_name, partition_size, partition_free_space, partition_used_space, partition_format, partition_caption, partition_disk_index, partition_bootable, partition_type, partition_quotas_supported, partition_quotas_enabled, partition_serial FROM sys_hw_partition LEFT JOIN system ON system.system_id = sys_hw_partition.system_id AND system.timestamp = sys_hw_partition.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_processor') {
            $sql = 'SELECT processor_description, processor_speed, processor_count, processor_cores, processor_logical, processor_manufacturer FROM sys_hw_processor LEFT JOIN system ON system.system_id = sys_hw_processor.system_id AND system.timestamp = sys_hw_processor.timestamp WHERE system.system_id = ?';
            #$sql = 'SELECT description AS processor_description, speed AS processor_speed, count AS processor_count, cores AS processor_cores,logical AS  processor_logical, manufacturer AS processor_manufacturer FROM sys_hw_processor WHERE current = "y" AND system_id = ?';
        } elseif ($table == 'sys_hw_scsi_controller') {
            $sql = 'SELECT sys_hw_scsi_controller.* FROM sys_hw_scsi_controller LEFT JOIN system ON system.system_id = sys_hw_scsi_controller.system_id AND system.timestamp = sys_hw_scsi_controller.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_sound') {
            $sql = 'SELECT sys_hw_sound.* FROM sys_hw_sound LEFT JOIN system ON system.system_id = sys_hw_sound.system_id AND system.timestamp = sys_hw_sound.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_hw_video') {
            $sql = 'SELECT sys_hw_video.* FROM sys_hw_video LEFT JOIN system ON system.system_id = sys_hw_video.system_id AND system.timestamp = sys_hw_video.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_man_audits') {
            $sql = 'SELECT sys_man_audits.* FROM sys_man_audits LEFT JOIN system ON system.system_id = sys_man_audits.system_id WHERE system.system_id = ?';
        } elseif ($table == 'oa_audit_log') {
            $sql = 'SELECT oa_audit_log.*, user_full_name FROM oa_audit_log LEFT JOIN system ON system.system_id = oa_audit_log.system_id LEFT JOIN oa_user ON oa_audit_log.user_id = oa_user.user_id WHERE system.system_id = ?';
        } elseif ($table == 'oa_alert_log') {
            $sql = 'SELECT oa_alert_log.* FROM oa_alert_log LEFT JOIN system ON system.system_id = oa_alert_log.system_id WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_route') {
            $sql = 'SELECT destination, next_hop, mask, metric, protocol, sys_sw_route.type FROM sys_sw_route LEFT JOIN system ON system.system_id = sys_sw_route.system_id AND system.timestamp = sys_sw_route.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_service') {
            $sql = 'SELECT service_display_name, service_name, service_start_mode, service_start_name, service_state FROM sys_sw_service LEFT JOIN system ON system.system_id = sys_sw_service.system_id AND system.timestamp = sys_sw_service.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_service_history_delta') {
            $sql = 'SELECT sys_sw_service.service_id, sys_sw_service.service_name, sys_sw_service.service_state, sys_sw_service.first_timestamp, sys_sw_service.timestamp, IF(sys_sw_service.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_service.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_service LEFT JOIN system ON (sys_sw_service.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_service_history_full') {
            $sql = 'SELECT sys_sw_service.service_id, sys_sw_service.service_name, sys_sw_service.service_state, sys_sw_service.first_timestamp, sys_sw_service.timestamp, IF(sys_sw_service.first_timestamp = system.first_timestamp, "y", "n") as original_install, IF(sys_sw_service.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_service LEFT JOIN system ON (sys_sw_service.system_id = system.system_id) WHERE system.system_id = ? AND (system.first_timestamp = sys_sw_service.first_timestamp OR system.timestamp = sys_sw_service.timestamp)';
        } elseif ($table == 'sys_sw_share') {
            $sql = 'SELECT share_name, share_size, share_caption, share_path FROM sys_sw_share LEFT JOIN system ON system.system_id = sys_sw_share.system_id AND system.timestamp = sys_sw_share.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_user') {
            $sql = 'SELECT user_name, user_caption, user_sid, user_domain, user_disabled, user_full_name, user_password_changeable, user_password_expires, user_password_required, user_status, user_type FROM sys_sw_user LEFT JOIN system ON system.system_id = sys_sw_user.system_id AND system.timestamp = sys_sw_user.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_variable') {
            $sql = 'SELECT variable_name, variable_value FROM sys_sw_variable LEFT JOIN system ON system.system_id = sys_sw_variable.system_id AND system.timestamp = sys_sw_variable.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_virtual_machine') {
            $sql = 'SELECT sys_sw_virtual_machine.* FROM sys_sw_virtual_machine LEFT JOIN system ON system.system_id = sys_sw_virtual_machine.system_id AND system.timestamp = sys_sw_virtual_machine.timestamp WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_windows') {
            $sql = 'SELECT windows_build_number, windows_user_name, windows_client_site_name, windows_domain_short, windows_domain_controller_address, windows_workgroup, windows_domain_controller_name, windows_domain_role, windows_part_of_domain, windows_time_caption, windows_time_daylight, windows_boot_device, windows_country_code, windows_organisation, windows_language, windows_registered_user, windows_service_pack, windows_version, windows_install_directory, windows_active_directory_ou FROM sys_sw_windows LEFT JOIN system ON system.system_id = sys_sw_windows.system_id AND system.timestamp = sys_sw_windows.timestamp WHERE system.system_id = ?';
        }
        if ($sql != '') {
            $data = array("$system_id");
            $query = $this->db->query($sql, $data);
            $result = $query->result();

            return ($result);
        }
    }

    public function get_system_document_api_history($table = '', $system_id = '')
    {
        if ($table == '') {
            return;
        }
        $sql = 'SELECT system_audits_time FROM sys_man_audits WHERE system_id = ? AND system_audits_type = "audit" ORDER BY system_audits_time LIMIT 1';
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);
        $result = $query->result();
        $first_audit_timestamp = $result[0]->system_audits_time;

        $sql = 'SELECT system_audits_time FROM sys_man_audits WHERE system_id = ? ORDER BY system_audits_time LIMIT 1';
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);
        $result = $query->result();
        $first_timestamp = $result[0]->system_audits_time;

        if ($table == 'sys_sw_software_history_delta') {
            $sql = 'SELECT sys_sw_software.software_id, sys_sw_software.software_name, sys_sw_software.software_version, sys_sw_software.first_timestamp, sys_sw_software.timestamp, IF((sys_sw_software.first_timestamp = ? OR sys_sw_software.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_software.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_software LEFT JOIN system ON (sys_sw_software.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_software_history_full') {
            $sql = 'SELECT sys_sw_software.software_id, sys_sw_software.software_name, sys_sw_software.software_version, sys_sw_software.first_timestamp, sys_sw_software.timestamp, IF((sys_sw_software.first_timestamp = ? OR sys_sw_software.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_software.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_software LEFT JOIN system ON (sys_sw_software.system_id = system.system_id) WHERE system.system_id = ? AND (sys_sw_software.first_timestamp = ? OR sys_sw_software.first_timestamp = ? OR system.timestamp = sys_sw_software.timestamp)';
        } elseif ($table == 'sys_sw_netstat_history_delta') {
            $sql = 'SELECT sys_sw_netstat.protocol, sys_sw_netstat.port, sys_sw_netstat.ip_address, sys_sw_netstat.program, sys_sw_netstat.first_timestamp, sys_sw_netstat.timestamp, IF((sys_sw_netstat.first_timestamp = ? OR sys_sw_netstat.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_netstat.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_netstat LEFT JOIN system ON (sys_sw_netstat.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_netstat_history_full') {
            $sql = 'SELECT sys_sw_netstat.protocol, sys_sw_netstat.port, sys_sw_netstat.ip_address, sys_sw_netstat.program, sys_sw_netstat.first_timestamp, sys_sw_netstat.timestamp, IF((sys_sw_netstat.first_timestamp = ? OR sys_sw_netstat.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_netstat.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_netstat LEFT JOIN system ON (sys_sw_netstat.system_id = system.system_id) WHERE system.system_id = ? AND (sys_sw_netstat.first_timestamp = ? OR sys_sw_netstat.first_timestamp = ? OR system.timestamp = sys_sw_netstat.timestamp)';
        } elseif ($table == 'sys_sw_service_history_delta') {
            $sql = 'SELECT sys_sw_service.service_id, sys_sw_service.service_name, sys_sw_service.service_state, sys_sw_service.first_timestamp, sys_sw_service.timestamp, IF((sys_sw_service.first_timestamp = ? OR sys_sw_service.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_service.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_service LEFT JOIN system ON (sys_sw_service.system_id = system.system_id) WHERE system.system_id = ?';
        } elseif ($table == 'sys_sw_service_history_full') {
            $sql = 'SELECT sys_sw_service.service_id, sys_sw_service.service_name, sys_sw_service.service_state, sys_sw_service.first_timestamp, sys_sw_service.timestamp, IF((sys_sw_service.first_timestamp = ? OR sys_sw_service.first_timestamp = ?), "y", "n") as original_install, IF(sys_sw_service.timestamp = system.timestamp, "y", "n") as current_install FROM sys_sw_service LEFT JOIN system ON (sys_sw_service.system_id = system.system_id) WHERE system.system_id = ? AND (sys_sw_service.first_timestamp = ? OR sys_sw_service.first_timestamp = ? OR system.timestamp = sys_sw_service.timestamp)';
        }
        if ($sql != '') {
            $data = array("$first_timestamp", "$first_audit_timestamp", "$system_id", "$first_timestamp", "$first_audit_timestamp");
            $query = $this->db->query($sql, $data);
            $result = $query->result();

            return ($result);
        }
    }

    public function get_system_attribute($table = 'system', $attribute = 'hostname', $system_id = '')
    {
        if ((strpos($attribute, ",") !== false) or ($attribute == "*")) {
            $limit = "";
        } else {
            $limit = "LIMIT 1";
        }
        $sql = "SELECT $table.$attribute FROM $table LEFT JOIN system ON ($table.system_id = system.system_id AND
            $table.timestamp = system.timestamp) WHERE system.system_id = ? ".$limit;
        #$sql = "SELECT $table.$attribute FROM $table WHERE system_id = ? AND current = 'y' ".$limit;
        $data = array("$system_id");
        $query = $this->db->query($sql, $data);
        if ((strpos($attribute, ",") !== false) or ($attribute == "*")) {
            $result = $query->result();

            return ($result);
        } else {
            $row = $query->row();

            return ($row->$attribute);
        }
    }

    public function count_old_attributes($days = 7)
    {
        $tables = $this->db->list_tables();
        $string = '';
        $return = array();
        $object = new stdclass();
        foreach ($tables as $table) {
            if (((strpos($table, 'sys_hw_') !== false) or (strpos($table, 'sys_sw_') !== false)) and (strpos($table, "sys_hw_warranty") === false)) {
                $object->table = '';
                $object->count = '';
                $sql = "SELECT COUNT(*) as count FROM $table LEFT JOIN system ON (system.system_id = $table.system_id)
                WHERE system.timestamp <> $table.timestamp AND DATE($table.timestamp) < DATE_SUB(curdate(), INTERVAL $days day);";
                #$sql = "SELECT COUNT(*) as count FROM $table WHERE current = 'n' AND DATE($table.last_seen) < DATE_SUB(curdate(), INTERVAL $days day);";
                $query = $this->db->query($sql);
                $row = $query->row();
                $object->count = $row->count;
                $object->table = $table;
                $return[] = clone $object;
            }
        }

        return($return);
    }

    public function count_all_hw_attributes()
    {
        $tables = $this->db->list_tables();
        $string = '';
        $return = array();
        $object = new stdclass();
        foreach ($tables as $table) {
            if (strpos($table, 'sys_hw_') !== false and strpos($table, "sys_hw_warranty") === false) {
                $object->table = '';
                $object->count = '';
                $sql = "SELECT COUNT(*) as count FROM $table";
                $query = $this->db->query($sql);
                $row = $query->row();
                $object->count = $row->count;
                $object->table = $table;
                $return[] = clone $object;
            }
        }

        return($return);
    }

    public function count_all_sw_attributes()
    {
        $tables = $this->db->list_tables();
        $string = '';
        $return = array();
        $object = new stdclass();
        foreach ($tables as $table) {
            if (strpos($table, 'sys_sw_') !== false) {
                $object->table = '';
                $object->count = '';
                $sql = "SELECT COUNT(*) as count FROM $table";
                $query = $this->db->query($sql);
                $row = $query->row();
                $object->count = $row->count;
                $object->table = $table;
                $return[] = clone $object;
            }
        }

        return($return);
    }

    public function delete_all_non_current_attributes($days = 365)
    {
        $tables = $this->db->list_tables();
        $count = 0;
        foreach ($tables as $table) {
            if (((strpos($table, 'sys_hw_') !== false) or (strpos($table, 'sys_sw_') !== false)) and (strpos($table, "sys_hw_warranty") === false)) {
                $sql = "DELETE $table FROM $table LEFT JOIN system ON (system.system_id = $table.system_id) WHERE system.timestamp <> $table.timestamp AND DATE($table.timestamp) < DATE_SUB(curdate(), INTERVAL $days day);";
                #$sql = "DELETE $table FROM $table WHERE current = 'n' AND DATE($table.last_seen) < DATE_SUB(curdate(), INTERVAL $days day)";
                $query = $this->db->query($sql);
                $count = $count + $this->db->affected_rows();
            }
        }

        return($count);
    }

    public function delete_table_non_current_attributes($table, $days = 365)
    {
        if (((strpos($table, 'sys_hw_') !== false) or (strpos($table, 'sys_sw_') !== false)) and (strpos($table, "sys_hw_warranty") === false)) {
            $sql = "DELETE $table FROM $table LEFT JOIN system ON (system.system_id = $table.system_id) WHERE system.timestamp <> $table.timestamp AND DATE($table.timestamp) < DATE_SUB(curdate(), INTERVAL $days day);";
            #$sql = "DELETE $table FROM $table WHERE current = 'n' AND DATE($table.last_seen) < DATE_SUB(curdate(), INTERVAL $days day)";
            $query = $this->db->query($sql);
            $count = $this->db->affected_rows();
        }

        return($count);
    }

    public function process_result($table = '', $match_columns = array(), $details)
    {
        // update the audit log
        $this->m_sys_man_audits->update_audit($details, "$table - start");

        if ($table == '' or count($match_columns == 0) or ! isset($details->system_id)) {
            // TODO probably should log something here
            $this->m_sys_man_audits->update_audit($details, "$table - failed 1");

            return;
        }

        // TODO - fix this. Just need to detect $table is a valid table name
        $found = 0;
        $result = $this->db->list_tables();
        foreach ($result as $key => $value) {
            if (strpos($value, $table) !== false) {
                $found = 1;
            }
        }
        if ($found == 0) {
            // TODO probably should log something here
            $this->m_sys_man_audits->update_audit($details, "$table - failed 2");

            return;
        }

        $sql = 'SELECT * FROM '.$table.' WHERE '.$table.'.current = "y" AND '.$table.'.system_id = ?';
        $data = array($details->system_id);
        $query = $this->db->query($sql, $data);
        $result = $query->result();
        foreach ($data->item as $data_xml) {
            $flag = 'insert';
            $match_count = 0;
            foreach ($result as $id => $data_db) {
                for ($i = 0; $i < count($match_columns); $i++) {
                    if ((string) $data_xml->$column[$i] == (string) $data_db->$column[$i]) {
                        $match_count ++;
                    }
                }
                if ($match_count == count($match_columns)-1) {
                    // we have a match - update
                    $flag = 'update';
                    $fields = $this->db->list_fields('$table');
                    foreach ($fields as $field) {
                        if ($data_db->$field == '' and $data_xml->$field != '') {
                            $data_db->$field = (string) $data_xml->$field;
                        }
                        $sql .= " $table.$field = ? , ";
                    }
                    $sql = substr($sql, 0, -2);
                    $data_db->timestamp = $details->timestamp;
                    $sql = "UPDATE $table SET $sql WHERE $table.id = '$data_db.id'";
                    $data = $this->db->list_fields('$table');
                    $query = $this->db->query($sql, $data);
                    unset($software_db);
                    // stop the loop
                    break;
                } else {
                    // no match - insert
                    // $flag stays unchanged
                }
            }
            if ($flag == 'insert') {
                // we did not get any matches to the array
                // insert a new row
                $data_xml->system_id = $details->system_id;
                $data_xml->first_timestamp = $details->timestamp;
                $data_xml->timestamp = $details->timestamp;
                $fields = get_object_vars($data_xml);
                $data = array();
                foreach ($field as $field) {
                    $set_fields .= " $field, ";
                    $set_values .= ' ?, ';
                    $data = $data_xml->$field;
                }
                $set_fields = substr($set_fields, 0, -2);
                $set_values = substr($set_values, 0, -2);
                $sql = "INSERT INTO $table ( $set_fields ) VALUES ( $set_values ) ";
                $query = $this->db->query($sql, $data);
            }
        }

        // get the total rows in the table that are current for the system_id
        unset($data);
        $sql = "SELECT count(*) as total FROM $table WHERE system_id = ? AND $table.current = 'y'";
        $data = array($details->system_id);
        $query = $this->db->query($sql, $data);
        $row = $query->row();
        $total_current = $row->total;

        // get the total rows for the device
        unset($data);
        $sql = "SELECT count(*) as total FROM $table WHERE system_id = ?";
        $data = array($details->system_id);
        $query = $this->db->query($sql, $data);
        $row = $query->row();
        $total_count = $row->total;

        if ($total_current = $total_count) {
            // we had no previous rows in this table - do not generate any alerts
        } else {
            // we had a count mismatch - totals not equal means not the first audit - generate alerts if required
            // test for item no longer detected
            $sql_select = "$table.id, ";
            foreach ($columns as $key => $value) {
                $sql_select .= " $table.$value, ";
            }
            $sql_select = substr($sql_select, 0, -2);

            $sql = "SELECT $sql_select FROM $table WHERE $table.system_id = ? and $table.timestamp = ?";
            $data = array("$details->system_id", "$details->original_timestamp");
            $sql = $this->clean_sql($sql);
            $query = $this->db->query($sql, $data);
            foreach ($query->result() as $myrow) {
                $alert_details =  'item removed from $table - ';
                foreach ($columns as $column) {
                    $alert_details .= $column.' = '.$myrow->$column.', ';
                }
                $alert_details = substr($alert_details, 0, -2);
                $this->m_alerts->generate_alert($details->system_id, $table, $myrow->id, $alert_details, $details->timestamp);
            }
            // test for new items
            $sql = "SELECT $sql_select FROM $table WHERE $table.first_timestamp = $table.timestamp AND $table.system_id = ? AND $table.first_timestamp = ? ";
            $data = array("$details->system_id", "$details->timestamp");
            $sql = $this->clean_sql($sql);
            $query = $this->db->query($sql, $data);
            foreach ($query->result() as $myrow) {
                $alert_details =  'item removed from $table - ';
                foreach ($columns as $column) {
                    $alert_details .= $column.' = '.$myrow->$column.', ';
                }
                $alert_details = substr($alert_details, 0, -2);
                $this->m_alerts->generate_alert($details->system_id, $table, $myrow->id, $alert_details, $details->timestamp);
            }
            // update the audit log
            $this->m_sys_man_audits->update_audit($details, "$table - end");
        }
    }
}
