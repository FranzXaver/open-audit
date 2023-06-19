<?php
# Copyright © 2023 FirstWave. All Rights Reserved.
# SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace App\Models;

use stdClass;

class CredentialsModel extends BaseModel
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('credentials');
        # Use the below to execute BaseModel::__construct
        # parent::__construct();
    }

    /**
     * Read the collection from the database
     *
     * @param  $resp object An object containing the properties, filter, sort and limit as passed by the user
     *
     * @return array        An array of formatted entries
     */
    public function collection(object $resp): array
    {
        $properties = $resp->meta->properties;
        $properties[] = "orgs.name as `orgs.name`";
        $properties[] = "orgs.id as `orgs.id`";
        $this->builder->select($properties, false);
        $this->builder->join('orgs', $resp->meta->collection . '.org_id = orgs.id', 'left');
        foreach ($resp->meta->filter as $filter) {
            if (in_array($filter->operator, ['!=', '>=', '<=', '=', '>', '<'])) {
                $this->builder->{$filter->function}($filter->name . ' ' . $filter->operator, $filter->value);
            } else {
                $this->builder->{$filter->function}($filter->name, $filter->value);
            }
        }
        $this->builder->orderBy($resp->meta->sort);
        $this->builder->limit($resp->meta->limit, $resp->meta->offset);
        $query = $this->builder->get();
        if ($this->sqlError($this->db->error())) {
            return array();
        }
        return format_data($query->getResult(), $resp->meta->collection);
    }

    /**
     * Create an individual item in the database
     *
     * @param  object $data The data attributes
     *
     * @return int|false    The Integer ID of the newly created item, or false
     */
    public function create($data = null): ?int
    {
        if (empty($data)) {
            return null;
        }
        if (! empty($data->credentials) && is_string($data->credentials)) {
            $data->credentials = simpleEncrypt($data->credentials, config('Encryption')->key);
        } else {
            $data->credentials = simpleEncrypt(json_encode($data->credentials), config('Encryption')->key);
        }
        $data = $this->createFieldData('credentials', $data);
        $this->builder->insert($data);
        if ($error = $this->sqlError($this->db->error())) {
            \Config\Services::session()->setFlashdata('error', json_encode($error));
            return null;
        }
        return (intval($this->db->insertID()));
    }

    /**
     * Delete an individual item from the database, by ID
     *
     * @param  int $id The ID of the requested item
     *
     * @return bool    true || false depending on success
     */
    public function delete($id = null, bool $purge = false): bool
    {
        $this->builder->delete(['id' => intval($id)]);
        if ($this->sqlError($this->db->error())) {
            return false;
        }
        if ($this->db->affectedRows() !== 1) {
            return false;
        }
        return true;
    }

    /**
     * Return an array containing arrays of related items to be stored in resp->included
     *
     * @param  int $id The ID of the requested item
     * @return array  An array of anything needed for screen output
     */
    public function includedRead(int $id = 0): array
    {
        return array();
    }

    /**
     * Return an array containing arrays of related items to be stored in resp->included
     *
     * @param  int $id The ID of the requested item
     * @return array  An array of anything needed for screen output
     */
    public function includedCreateForm(int $id = 0): array
    {
        return array();
    }


    /**
     * Read the entire collection from the database that the user is allowed to read
     *
     * @return array  An array of formatted entries
     */
    public function listUser($where = array()): array
    {
        $instance = & get_instance();
        $org_list = array_unique(array_merge($instance->user->orgs, $instance->orgsModel->getUserDescendants($instance->user->orgs, $instance->orgs)));
        $org_list[] = 1;
        $org_list = array_unique($org_list);

        $properties = array();
        $properties[] = 'credentials.*';
        $properties[] = 'orgs.name as `orgs.name`';
        $this->builder->select($properties, false);
        $this->builder->join('orgs', 'credentials.org_id = orgs.id', 'left');
        $this->builder->whereIn('orgs.id', $org_list);
        if (!empty($where[0]) and !empty($where[1])) {
            $this->builder->where($where[0], $where[1]);
        }
        if (!empty($where[2]) and !empty($where[3])) {
            $this->builder->where($where[2], $where[3]);
        }
        $query = $this->builder->get();
        if ($this->sqlError($this->db->error())) {
            return array();
        }
        return format_data($query->getResult(), 'credentials');
    }

    /**
     * Read the entire collection from the database
     *
     * @return array  An array of all entries
     */
    public function listAll($where = array()): array
    {
        if (!empty($where[0]) and !empty($where[1])) {
            $this->builder->where($where[0], $where[1]);
        }
        if (!empty($where[2]) and !empty($where[3])) {
            $this->builder->where($where[2], $where[3]);
        }
        $query = $this->builder->get();
        if ($this->sqlError($this->db->error())) {
            return array();
        }
        return $query->getResult();
    }

    /**
     * Read an individual item from the database, by ID
     *
     * @param  int $id The ID of the requested item
     *
     * @return array   The array containing the requested item
     */
    public function read(int $id = 0): array
    {
        $query = $this->builder->getWhere(['id' => intval($id)]);
        if ($this->sqlError($this->db->error())) {
            return array();
        }
        $credentials = $query->getResult();
        if (!empty($credentials[0]->credentials)) {
            $credentials[0]->credentials = simpleDecrypt($credentials[0]->credentials, config('Encryption')->key);
            $credentials[0]->credentials = json_decode($credentials[0]->credentials);
        }
        return format_data($credentials, 'credentials');
    }

    /**
     * Reset a table
     *
     * @return bool Did it work or not?
     */
    public function reset(string $table = ''): bool
    {
        if ($this->tableReset('credentials')) {
            return true;
        }
        return false;
    }

    /**
     * Update an individual item in the database
     *
     * @param  object  $data The data attributes
     *
     * @return bool    true || false depending on success
     */
    public function update($id = null, $data = null): bool
    {

        if (!empty($data->credentials)) {
            $query = $this->builder->getWhere(['id' => intval($id)]);
            $get_result = $query->getResult();
            $received_credentials = new \StdClass();
            foreach ($data->credentials as $key => $value) {
                    $received_credentials->$key = $value;
            }
            $existing_credentials = json_decode(simpleDecrypt($get_result[0]->credentials, config('Encryption')->key));
            $new_credentials = new \StdClass();
            foreach ($existing_credentials as $key => $value) {
                if (!empty($received_credentials->$key)) {
                    $new_credentials->$key = $received_credentials->$key;
                } else {
                    $new_credentials->$key = $existing_credentials->$key;
                }
            }
            $data->credentials = (string)simpleEncrypt(json_encode($new_credentials), config('Encryption')->key);
        }
        $data = $this->updateFieldData('credentials', $data);
        $this->builder->where('id', intval($id));
        $this->builder->update($data);
        if ($error = $this->sqlError($this->db->error())) {
            echo json_encode($error);
            return false;
        }
        return true;
    }

    /**
     * The dictionary item
     *
     * @return object  The stdClass object containing the dictionary
     */
    public function dictionary(): object
    {
        $instance = & get_instance();

        $collection = 'credentials';
        $dictionary = new \StdClass();
        $dictionary->table = $collection;
        $dictionary->columns = new \StdClass();

        $dictionary->attributes = new \StdClass();
        $dictionary->attributes->collection = array('id', 'name', 'type', 'description', 'orgs.name');
        $dictionary->attributes->create = array('name','org_id','type','credentials'); # We MUST have each of these present and assigned a value
        $dictionary->attributes->fields = $this->db->getFieldNames($collection); # All field names for this table
        $dictionary->attributes->fieldsMeta = $this->db->getFieldData($collection); # The meta data about all fields - name, type, max_length, primary_key, nullable, default
        $dictionary->attributes->update = $this->updateFields($collection); # We MAY update any of these listed fields

        $dictionary->about = '<p>Credentials are used to access devices.<br /><br />Configuring credentials should be one of the first things you do after installing Open-AudIT.<br /><br />' . $instance->dictionary->link . '<br /><br /></p>';

        $dictionary->notes = '<p>Credentials are encrypted when stored in the database.<br /><br />When a Discovery is run, a device has its credentials retrieved and tested for connection first (from the <code>credential</code> table). If these fail, then credentials associated with the given Org <code>credentials.org_id</code> is also tested against the device. Working credentials are stored at an individual device level in the credential table (note - no "s" in the table name).<br /><br />SSH keys are tested before SSH username / password. When testing SSH, credentials will also be marked as working with sudo or being root.<br /><br />For ease of use, Windows passwords should not contain a \' or ". This is a remote WMI limitation, not an Open-AudIT limitation.<br /><br /></p>';

        $dictionary->product = 'community';
        $dictionary->columns->id = $instance->dictionary->id;
        $dictionary->columns->name = $instance->dictionary->name;
        $dictionary->columns->org_id = $instance->dictionary->org_id;
        $dictionary->columns->description = $instance->dictionary->description;
        $dictionary->columns->type = 'Currently supported types are <code>snmp</code>, <code>snmp_v3</code>, <code>ssh</code>, <code>ssh_key</code> or <code>windows</code>.';
        $dictionary->columns->credentials = 'An encrypted JSON document containing the required attributes depending on the <code>credentials.type</code>.';
        $dictionary->columns->community = 'The SNMP community string.';
        $dictionary->columns->username = 'The credentials username.';
        $dictionary->columns->password = 'The credentials password.';
        $dictionary->columns->ssh_key = 'You SSH key.';
        $dictionary->columns->sudo_password = 'An optional password to use sudo.';
        $dictionary->columns->authentication_passphrase = 'The SNMP v3 Authentication Passphrase.';
        $dictionary->columns->authentication_protocol = 'The SNMP v3 Authentication Protocol.';
        $dictionary->columns->privacy_passphrase = 'The SNMP v3 Privacy Passphrase.';
        $dictionary->columns->privacy_protocol = 'The SNMP v3 Privacy Protocol.';
        $dictionary->columns->security_level = 'The SNMP v3 Security Level.';
        $dictionary->columns->security_name = 'The SNMP v3 Security Name.';
        $dictionary->columns->edited_by = $instance->dictionary->edited_by;
        $dictionary->columns->edited_date = $instance->dictionary->edited_date;
        return $dictionary;
    }
}
