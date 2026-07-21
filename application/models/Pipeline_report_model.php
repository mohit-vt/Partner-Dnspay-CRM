<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_report_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('agent_crm', TRUE); // Connect to `agent_crm` database
        $this->crm_db = $this->load->database('default', TRUE); // royalsec_crm
    }

    /**
     * Insert pipeline report data into database
     */
    public function add_pipeline_report($data)
    {
        $this->db->insert('pipeline_report', $data);
        return $this->db->insert_id();
    }

  	public function log_pipeline_activity($log_data)
    {

        $this->db->insert('pipeline_activity_log', $log_data);
    }
  
    public function get_all_mcc_codes()
    {
     
    $db_codes = $this->db->select('code, description')
                         ->from('mcc_codes')
                         ->get()
                         ->result_array();

    // From Stripe table
    $stripe_codes = $this->db->select('code, description')
                             ->from('mcc_codes_stripe')
                             ->get()
                             ->result_array();

    // Merge both arrays
    $merged = array_merge($db_codes, $stripe_codes);

    // Deduplicate by code
    $unique = [];
    foreach ($merged as $item) {
        $unique[$item['code']] = $item; // overwrite duplicate codes
    }

    // Sort by code
    ksort($unique);

    return array_values($unique);
    }
  
    public function get_combined_list($limit, $offset)
    {
        $this->db->select('*');
        $this->db->from('pipeline_report'); // Adjust table name if needed
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        return $query->result_array();
    }

    // Total number of rows
    public function count_all_reports()
    {
        return $this->db->count_all('pipeline_report'); // Adjust table name if needed
    }

  
    public function get_pre_applications_id_max()
    {
            $this->db->select_max('id');
            $query = $this->db->get('pipeline_report');
            $result = $query->row();
            return $result->id ?? 0;
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('pre_application')->row();
    }

      public function get_pipeline_report_count()
    {
        return $this->db->count_all('pipeline_report');
    }

    
    public function get_all_pippeline_report()
    {
        return $this->db->get('pipeline_report')->result_array();
    }

    public function get_pre_application($id)
    {
        return $this->db->get_where('pipeline_report', ['id' => $id])->row_array();
    }

    public function get_pre_application_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('pipeline_report')->row_array(); // Fetch single record
    }

    public function update_pre_application($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('pipeline_report', $data);
    }
  
    // public function get_pipeline_report_by_user($user_id) {
       
    //     $this->db->select('pipeline_report.*, pre_application.*, pipeline_report.id AS pipeline_report_id,pipeline_report.bank_name as pipeline_bank,pipeline_report.business_country as user_business_country');
    //     $this->db->from('pipeline_report');
    //     $this->db->join('pre_application', 'pre_application.id = pipeline_report.pre_application_id', 'left');
    //     $this->db->where('pipeline_report.user_id', $user_id);
    //     $query = $this->db->get();
    //    return $query->result_array(); // returns an array of arrays



    // } 
  
//     Commented on 03.10.2025
  
//     public function get_pipeline_report_by_user($user_id) {
//     $this->db->select('
//         pipeline_report.*, 
//         pre_application.*, 
//         pipeline_report.id AS pipeline_report_id, 
//         pipeline_report.bank_name AS pipeline_bank, 
//         pipeline_report.business_country AS user_business_country,
//         pipeline_report.merchant_status AS pipeline_merchant_status
//     ');
//     $this->db->from('pipeline_report');
//     $this->db->join('pre_application', 'pre_application.id = pipeline_report.pre_application_id', 'left');
//     $this->db->where('pipeline_report.user_id', $user_id);
//     $this->db->order_by('pipeline_report.id', 'DESC');

//     $query = $this->db->get();
//     return $query->result_array();
// }
  
public function get_pipeline_report_by_user($user_id) {
    $user_id = (int)$user_id;

    $this->db->select('
        pipeline_report.*, 
        pre_application.*, 
        pipeline_report.id AS pipeline_report_id, 
        pipeline_report.dba,
        pipeline_report.business_phone,
        pipeline_report.company_name,
        pipeline_report.bank_name AS pipeline_bank, 
        pipeline_report.business_country AS user_business_country,
        COALESCE(pipeline_report.email_address, pre_application.personal_email_address) AS final_email,
        pipeline_report.merchant_status AS pipeline_merchant_status
    ');
    $this->db->from('pipeline_report');
    $this->db->join('pre_application', 'pre_application.id = pipeline_report.pre_application_id', 'left');

    // ✅ Include records where the user is the owner OR assigned via MST OR assigned via agent
    $this->db->group_start();
        $this->db->where('pipeline_report.user_id', $user_id);
        $this->db->or_where('pipeline_report.assigned_to_mst', $user_id);
        $this->db->or_where('pipeline_report.assigned_to_agent', $user_id);
    $this->db->group_end();

    $this->db->order_by('pipeline_report.id', 'DESC');

    $query = $this->db->get();
    return $query->result_array();
  
  
}






  	public function update_pipeline_report($id, $data)
    {
        $existing = $this->db->get_where('pipeline_report', ['id' => $id])->row_array();

        foreach ($data as $field => $newValue) {
            $oldValue = $existing[$field] ?? null;

            if ($oldValue != $newValue) {
                $this->db->insert('pipeline_activity_log', [
                    'pipeline_report_id' => $id,
                    'field_name'         => $field,
                    'old_value'          => $oldValue,
                    'new_value'          => $newValue,
                    'updated_by'         => get_staff_user_id(),
                    'updated_at'         => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $this->db->where('id', $id)->update('pipeline_report', $data);
    }

    
    public function delete_multiple_pre_applicationt($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('clients');
    }

    public function delete_pre_application($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('pipeline_report');
    }
  
  	public function count_inactive_users($user_id) {
      
    $this->db->where('status', 'inactive');
    $this->db->where('user_id', $user_id);
    return $this->db->count_all_results('pipeline_report');
      
    }

    public function get_active_agents($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->where('status', 'active')
                        ->get('pipeline_report')
                        ->result_array();
    }

    public function get_merchants($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->get('pipeline_report')
                        ->result_array();
    }

    public function get_pre_applications($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->get('pre_application')
                        ->result_array();
    }
  
  	// ✅ Count ACTIVE Agents from agents table
public function count_active_agents($user_id)
{
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 'active'); // adjust if your agents.status uses 'active'
    return $this->db->count_all_results('agents');
}


// ✅ Count Sub-Agents from agents table
public function count_sub_agents($user_id)
{
    $this->db->where('user_id', $user_id);

    // Pick the correct condition that matches your agents schema:

    // Option A (if you have a "type" column):
    //$this->db->where('status', 'Approved');

    // Option B (if sub agents are those with parent_agent_id):
    // $this->db->where('parent_agent_id IS NOT NULL', null, false);

    return $this->db->count_all_results('agents');
}


// ✅ Count Pipeline Report Applications
public function count_pipeline_report_apps($user_id)
{
    $this->db->where('user_id', $user_id);
    return $this->db->count_all_results('pipeline_report');
}

public function get_pipeline_growth_last_30_days($user_id)
{
    $this->db->select("DATE(created_at) as day, COUNT(*) as total");
    $this->db->from('pipeline_report');
    $this->db->where('user_id', $user_id);
    $this->db->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
    $this->db->group_by("DATE(created_at)");
    $this->db->order_by("DATE(created_at)", "ASC");
    $rows = $this->db->get()->result_array();

    // Fill missing days with 0
    $days = [];
    for ($i=29; $i>=0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $days[$d] = 0;
    }
    foreach ($rows as $r) {
        $days[$r['day']] = (int)$r['total'];
    }

    return [
        'labels' => array_keys($days),
        'data'   => array_values($days),
    ];
}
public function get_pipeline_status_stats($user_id)
{
    $statuses = ['Pending','In Review','Approved','Declined'];
    $out = [];
    foreach ($statuses as $s) {
        $this->db->where('user_id', $user_id);
        $this->db->where('merchant_status', $s);
        $out[$s] = $this->db->count_all_results('pipeline_report');
    }
    return $out;
}

  public function get_recent_pipeline($user_id)
{
    return $this->db->where('user_id', $user_id)
                    ->order_by('created_at', 'DESC')
                    ->limit(8)
                    ->get('pipeline_report')
                    ->result_array();
}

  

}
