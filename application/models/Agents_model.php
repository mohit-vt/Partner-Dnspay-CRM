<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agents_model extends App_Model
{
    protected $agent_db;

    public function __construct()
    {
        parent::__construct();
        // Use dedicated connection to agent_crm database
        $this->agent_db = $this->load->database('agent_crm', TRUE);
    }

    /**
     * Insert agent data into agent_crm database
     */
    public function add_agents($data)
    {

        $this->agent_db->insert('agents', $data); 
        return $this->agent_db->insert_id();
    }

    /**
     * Generate next application ID
     */
    public function get_next_application_id()
    {
        $row = $this->agent_db->select_max('id')->get('agents')->row();
        $nextId = ($row && $row->id) ? $row->id + 1 : 1;

        $offset   = 3660;
        $sequence = $nextId + $offset;

        $monthLetter = strtoupper(substr(date('F'), 0, 1));
        $yearTwo     = date('y');

        return $monthLetter . $yearTwo . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get last agents_code (defaults to 360500 if none exist)
     */
    public function get_last_agents_code()
    {
        $this->agent_db->select('agents_code');
        $this->agent_db->from('agents');
        $this->agent_db->like('agents_code', '360', 'after');
        $this->agent_db->order_by('id', 'DESC');
        $this->agent_db->limit(1);
        $query = $this->agent_db->get();
        $result = $query->row();

        return $result ? $result->agents_code : '360500';
    }

    public function get_all_agents()
    {

         $this->agent_db->order_by('date_created', 'DESC'); // sort by latest
    	 $agents = $this->agent_db->get('agents')->result_array();

        $CI =& get_instance();

        foreach ($agents as &$agent) {
            if (!empty($agent['user_id'])) {
                $CI->db->select('firstname, lastname');
                $CI->db->from('staff');
                $CI->db->where('staffid', $agent['user_id']);
                $staff = $CI->db->get()->row_array();

                if ($staff) {
                    $agent['firstname'] = $staff['firstname'];
                    $agent['lastname']  = $staff['lastname'];
                } else {
                    $agent['firstname'] = '';
                    $agent['lastname']  = '';
                }
            } else {
                $agent['firstname'] = '';
                $agent['lastname']  = '';
            }
        }

        return $agents;
    }
  
  	public function token_exists($token)
    {
        $this->agent_db->where('submit_token', $token);
        return $this->agent_db->count_all_results('agents') > 0;
    }

    public function get_agents_by_user_id($id) 
    {
        $this->agent_db->where('user_id', $id);
        $this->agent_db->order_by('date_created', 'DESC'); // sort by latest
        return $this->agent_db->get('agents')->result_array();
    }

    public function get_pipeline_report($id)
    {
        return $this->agent_db->get_where('agents', ['id' => $id])->row_array();
    }

    public function get_agents_by_id($id)
    {
        $this->agent_db->where('id', $id);
        return $this->agent_db->get('agents')->row_array();
    }

    public function update_agents($id, $data)
    {
        $this->agent_db->where('id', $id);
        return $this->agent_db->update('agents', $data);
    }
    
    public function delete_multiple_agents($id)
    {
        $this->agent_db->where('id', $id);
        return $this->agent_db->delete('clients'); 
    }

    public function delete_agents($id)
    {
        $this->agent_db->where('id', $id);
        return $this->agent_db->delete('agents');
    }
}
