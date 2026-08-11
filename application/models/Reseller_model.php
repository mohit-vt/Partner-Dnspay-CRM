<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reseller_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('agent_crm', TRUE); // Connect to `agent_crm` database
    }

    /**
     * Insert pipeline report data into database
     */
    public function add_new_reseller($data)
    {
        $this->db->insert('resellers', $data);
        return $this->db->insert_id();
    }
  
  	
    public function get_last_reseller_code()
    {
        $this->db->select('reseller_code');
        $this->db->from('resellers');
        $this->db->like('reseller_code', '360', 'after');
        $this->db->order_by('reseller_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->reseller_code : '360500'; // Default starting point
    }
  
  public function add_agent_overview($agent_id, $overview, $created_by) {
    $data = [
        'agent_id' => $agent_id,
        'overview' => $overview,
        'created_by' => $created_by,
        'created_at' => date('Y-m-d H:i:s')
    ];
    return $this->db->insert('agent_overviews', $data);
}

public function get_agent_overviews($agent_id)
{
    // agent_crm DB for agent_overviews
    $agentDb = $this->db;

    // default DB for staff
    $default_db = $this->load->database('default', TRUE);

    // Get overviews from agent_crm
    $agentDb->select('*')
            ->from('agent_overviews')
            ->where('agent_id', $agent_id)
            ->order_by('created_at', 'DESC');

    $overviews = $agentDb->get()->result_array();

    if (empty($overviews)) return [];

    // Collect all unique created_by IDs
    $created_by_ids = array_column($overviews, 'created_by');

    // Fetch staff names from default DB
    $default_db->select('staffid, firstname, lastname')
               ->from('staff')
               ->where_in('staffid', $created_by_ids);

    $staff_list = $default_db->get()->result_array();

    // Build a map of staffid => fullname
    $staff_map = [];
    foreach ($staff_list as $s) {
        $staff_map[$s['staffid']] = $s['firstname'] . ' ' . $s['lastname'];
    }

    // Attach fullname to each overview
    foreach ($overviews as &$ov) {
        $ov['firstname'] = $staff_map[$ov['created_by']] ?? 'Unknown';
        $ov['lastname']  = '';
    }

    return $overviews;
}


    /**
     * Insert pipeline report data into database
     */
// public function get_all_resellers()
// {
//     // This will use agent_crm connection (already set in __construct)
//     $resellers = $this->db->get('resellers')->result_array();

//     // Set default status for resellers
//     foreach ($resellers as &$reseller) {
//         $reseller['status'] = $reseller['status'] ?? 'Pending';
//     }

//     // Use default Perfex CRM connection for staff
//     $default_db = $this->load->database('default', TRUE);
//     $default_db->select('staff.*')
//                ->from('staff')
//                ->join('roles', 'roles.roleid = staff.role')
//                ->where('roles.name', 'agent');
//     $agents = $default_db->get()->result_array();

//     return [
//         'resellers' => $resellers,
//         'agents'    => $agents
//     ];
// }
  
  public function get_all_resellers()
{
    // Fetch resellers from reseller table (your external/linked DB)
    $resellers = $this->db->get('resellers')->result_array();

    // Set default status for resellers
    foreach ($resellers as &$reseller) {
        $reseller['status'] = $reseller['status'] ?? 'Pending';
    }

    // Connect to default Perfex database for staff/agents
    $default_db = $this->load->database('default', TRUE);

    // Every role except Platform Administrator and Office Manager counts as an "agent"
    // for this listing — same exclusion rule used for Agent ID generation.
    $excluded_roles = ['platform administrator', 'office manager'];

    $default_db->select('roleid')
               ->from('roles')
               ->where_in('LOWER(name)', $excluded_roles);
    $excludedRoleRows = $default_db->get()->result();
    $excluded_roleids = array_map(fn($r) => $r->roleid, $excludedRoleRows);

    $default_db->select('staff.staffid, staff.firstname, staff.agent_id, staff.lastname, staff.email, staff.datecreated, staff.active, staff.user_status, roles.name as role_name')
               ->from('staff')
               ->join('roles', 'roles.roleid = staff.role');

    if (!empty($excluded_roleids)) {
        $default_db->where_not_in('staff.role', $excluded_roleids);
    }

    $agents = $default_db->get()->result_array();

    return [
        'resellers' => $resellers,
        'agents'    => $agents
    ];
}

  
    public function get_resellers_by_user_id($id)
    {
        $this->db->where('user_id', $id);
        return $this->db->get('resellers')->result_array(); // Fetch single record
    }
  
  	public function generate_application_id($bank_name)
    {
        $prefix = strtoupper(substr($bank_name, 0, 1)); // First letter of bank
        $baseNumber = 253660;

        // Get last application_id starting with this prefix
        $this->db->like('application_id', $prefix, 'after');
        $this->db->order_by('reseller_id', 'DESC');
        $last = $this->db->get('resellers')->row();

        if ($last && !empty($last->application_id)) {
            // Extract numeric part from application_id (e.g., W253661 -> 253661)
            $lastNumber = (int) substr($last->application_id, 1);
            $newNumber = $lastNumber + 1;
        } else {
            // Start fresh from base
            $newNumber = $baseNumber + 1;
        }

        return $prefix . $newNumber;
    }


    public function get_last_reseller_id()
    {
        $this->db->select_max('reseller_id'); // Get highest ID from `resellers` table
        $query = $this->db->get('resellers')->row();
        return $query->reseller_id ? (int) $query->reseller_id : 0; // If no records exist, return 0
    }

    public function get_resellers_by_id($id)
    {
        $this->db->where('reseller_id', $id);
        return $this->db->get('resellers')->row_array(); // Fetch single record
    }

    public function update_resellers($id, $data)
    {
        $this->db->where('reseller_id', $id);
        return $this->db->update('resellers', $data);
    }
    
    public function delete_multiple_report($id)
    {
        $this->db->where('reseller_id', $id);
        return $this->db->delete('clients');
    }

    public function delete_resellers($id)
    {
        $this->db->where('reseller_id', $id);
        return $this->db->delete('resellers');
    }

    public function delete($id)
    {
        $this->db->where('reseller_id', $id);
        return $this->db->delete('resellers');
    }


}
