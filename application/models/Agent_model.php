<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent_model extends CI_Model
{
    public function create_agent($data)
    {
        return $this->db->insert(db_prefix().'staff', $data);
    }

    public function get_agent_role_id()
    {
        // Perfex usually uses tblroles
        $role = $this->db->where('name', 'agent')->get(db_prefix().'roles')->row();
        return $role ? (int)$role->roleid : null;
    }

    public function generate_agent_id()
    {
        $last = $this->db->select('agent_id')
                         ->like('agent_id', 'AG', 'after')
                         ->order_by('staffid','DESC')
                         ->limit(1)
                         ->get(db_prefix().'staff')
                         ->row();

        $num = 0;
        if ($last && preg_match('/AG(\d+)/', $last->agent_id, $m)) {
            $num = (int)$m[1];
        }

        $next = $num + 1;
        return 'AG' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function generate_password($len = 10)
    {
        return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%'), 0, $len);
    }
}
