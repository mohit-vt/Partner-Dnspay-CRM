<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pre_application_model extends App_Model
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
    public function add_pre_application($data)
    {
        $this->db->insert('pre_application', $data);
        return $this->db->insert_id();
    }
  
      public function insert_pre_application_notes($data)
    {
        $this->db->insert('pre_application_notes', $data);
        return $this->db->insert_id();
    }
    public function insert_pre_application_log($data)
    {
        $this->db->insert('pre_application_log', $data);
        return $this->db->insert_id();
    }
  
  	public function get_employee_name_by_id($staffid)
    {
        $this->crm_db->select('t.firstname, t.lastname');
        $this->crm_db->from(db_prefix() . 'staff t');
        $this->crm_db->join(db_prefix() . 'roles r', 't.role = r.roleid');
        $this->crm_db->where_in('r.name', ['Employee', 'Management']);
        $this->crm_db->where('t.staffid', $staffid);

        $query = $this->crm_db->get();

        if ($query->num_rows() > 0) {
            return $query->row(); // return object with firstname, lastname
        }

        return null;
    }


    public function get_last_pre_application_id()
    {
        $this->db->select_max('id'); // Get highest ID from `resellers` table
        $query = $this->db->get('pre_application')->row();
        return $query->id ? (int) $query->id : 0; // If no records exist, return 0
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('pre_application')->row();
    }
  
  	// Get last agent_id like 360501
    public function get_last_agent_code()
    {
        $this->db->select('agent_id');
        $this->db->from('pre_application');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row();
        return $row ? $row->agent_id : null;
    }

    // Get last application_id like S253601
    public function get_last_application_code()
    {
        $this->db->select('application_id');
        $this->db->from('pre_application');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row();
        return $row ? $row->application_id : null;
    }

  
    // in Pre_application_model.php

    public function get_by_assign_employee_id($id)
    {
        // Step 1: Get from agent_crm.pre_application
        $this->db->where('assigned_employee', $id);
        $preapps = $this->db->get('pre_application')->result();

        // Step 2: Enrich with staff data from royalsec_crm
        $this->crm_db = $this->load->database('default', TRUE);

        foreach ($preapps as &$app) {
            $staff = $this->crm_db
                ->select('firstname, lastname, role')
                ->from('tblstaff')
                ->where('staffid', $id)
                ->get()
                ->row();

            if ($staff) {
                $app->firstname = $staff->firstname;
                $app->lastname = $staff->lastname;

                $role = $this->crm_db
                    ->select('name')
                    ->from('tblroles')
                    ->where('roleid', $staff->role)
                    ->get()
                    ->row();

                $app->role_name = $role->name ?? null;
            }
        }

        return $preapps;
    }


    /**
     * Insert pipeline report data into database
     */
    public function get_all_pre_application_list()
    {
        return $this->db->get('pre_application')->result_array();
    }
  
    public function get_pre_application_list_by_user_id($id)
    {
        $this->db->where('user_id', $id);
        return $this->db->get('pre_application')->result_array(); // Fetch single record
    }
       public function get_all_pre_application($limit = 50, $offset = 0)
{
    $limit  = (int) $limit;
    $offset = (int) $offset;

    if ($limit <= 0) $limit = 50;
    if ($offset < 0) $offset = 0;
    // ✅ Use agent_crm for pipeline_report + pre_application
    $agent_db = $this->load->database('agent_crm', TRUE);

    $agent_db->select('
        pipeline_report.id AS pipeline_report_id,
        pipeline_report.user_id AS pipeline_user_id,
        pre_application.id AS app_id,
        pre_application.user_id AS app_user_id,
        pipeline_report.assigned_to_agent,
        pipeline_report.lead_source,
        pipeline_report.social_media,
        pipeline_report.dba,
        pipeline_report.ip_address,
        pipeline_report.browser_name,
        pipeline_report.browser_version,
        pipeline_report.platform,
        pipeline_report.device_type,
        pipeline_report.user_agent,
        COALESCE(pipeline_report.created_at, pre_application.created_at) AS final_created_at,
        COALESCE(pipeline_report.bank_name, pre_application.bank_name) AS final_bank_name,
        COALESCE(pipeline_report.first_name, pre_application.first_name) AS final_first_name,
        COALESCE(pipeline_report.last_name, pre_application.last_name) AS final_last_name,
        COALESCE(pipeline_report.email_address, pre_application.personal_email_address) AS final_email,
        COALESCE(pipeline_report.company_name, pre_application.business_name) AS final_company,
        COALESCE(pipeline_report.business_phone, pre_application.cell_number) AS final_phone,
        pipeline_report.merchant_status AS pipeline_merchant_status,
        pipeline_report.status AS pipeline_cancel_status,
        pre_application.merchant_status AS preapp_merchant_status,
        pipeline_report.business_country AS pipeline_report_country,
        pipeline_report.driving_license AS pipeline_driving_license,
        pipeline_report.state_of_license AS pipeline_state_of_license,
        pipeline_report.business_address AS pipeline_business_address,
        pipeline_report.bank_name AS pipeline_report_bank_name,
        "pipeline" AS source_type
    ');
    $agent_db->from('pipeline_report');
    $agent_db->join('pre_application', 'pre_application.id = pipeline_report.pre_application_id', 'left');
    $agent_db->where('pipeline_report.is_archived', 0);
    $result1 = $agent_db->get_compiled_select();

    $agent_db->select('
        pipeline_report.id AS pipeline_report_id,
        pipeline_report.user_id AS pipeline_user_id,
        pre_application.id AS app_id,
        pre_application.user_id AS app_user_id,
        pipeline_report.assigned_to_agent,
        pipeline_report.lead_source,
        pipeline_report.dba,
        pipeline_report.social_media,
        pipeline_report.ip_address,
        pipeline_report.browser_name,
        pipeline_report.browser_version,
        pipeline_report.platform,
        pipeline_report.device_type,
        pipeline_report.user_agent,
        COALESCE(pipeline_report.created_at, pre_application.created_at) AS final_created_at,
        COALESCE(pipeline_report.bank_name, pre_application.bank_name) AS final_bank_name,
        COALESCE(pipeline_report.first_name, pre_application.first_name) AS final_first_name,
        COALESCE(pipeline_report.last_name, pre_application.last_name) AS final_last_name,
        COALESCE(pipeline_report.email_address, pre_application.personal_email_address) AS final_email,
        COALESCE(pipeline_report.company_name, pre_application.business_name) AS final_company,
        COALESCE(pipeline_report.business_phone, pre_application.cell_number) AS final_phone,
        pipeline_report.merchant_status AS pipeline_merchant_status,
        pipeline_report.status AS pipeline_cancel_status,
        pre_application.merchant_status AS preapp_merchant_status,
        pipeline_report.business_country AS pipeline_report_country,
        pipeline_report.driving_license AS pipeline_driving_license,
        pipeline_report.state_of_license AS pipeline_state_of_license,
        pipeline_report.business_address AS pipeline_business_address,
        pipeline_report.bank_name AS pipeline_report_bank_name,
        "preapp" AS source_type
    ');
    $agent_db->from('pre_application');
    $agent_db->join('pipeline_report', 'pre_application.id = pipeline_report.pre_application_id', 'left');
    $agent_db->where('pipeline_report.id IS NULL', null, false);
    $agent_db->where('(pipeline_report.is_archived = 0 OR pipeline_report.is_archived IS NULL)', null, false);
    $result2 = $agent_db->get_compiled_select();

    $query = $agent_db->query("
        ($result1)
        UNION
        ($result2)
        ORDER BY pipeline_report_id DESC
        LIMIT {$limit} OFFSET {$offset}
    ");
        $combined = $query->result_array();

        // Load staff info from royalsec_crm
        $this->crm_db = $this->load->database('default', TRUE);

        $userIds = [];

        foreach ($combined as $row) {
            $uid = $row['pipeline_user_id'] ?? $row['app_user_id'] ?? null;
            if ($uid) $userIds[$uid] = $uid;
        }

        $staffMap = [];

        if (!empty($userIds)) {
            $staffRows = $this->crm_db
                ->select('staffid, firstname, lastname')
                ->where_in('staffid', $userIds)
                ->get(db_prefix() . 'staff')
                ->result_array();

            foreach ($staffRows as $s) {
                $staffMap[$s['staffid']] = $s;
            }
        }

        // now assign without querying per row
        foreach ($combined as &$item) {
            $uid = $item['pipeline_user_id'] ?? $item['app_user_id'] ?? null;

            if ($uid && isset($staffMap[$uid])) {
                $item['staff_firstname'] = $staffMap[$uid]['firstname'];
                $item['staff_lastname']  = $staffMap[$uid]['lastname'];
            } else {
                $item['staff_firstname'] = null;
                $item['staff_lastname']  = null;
            }
        }


                return $combined;
            }





    public function get_pre_application($id)
    {
        return $this->db->get_where('pre_application', ['id' => $id])->row_array();
    }

    public function get_pre_application_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('pre_application')->row_array(); // Fetch single record
    }
  
  
  	public function get_pre_application_notes_by_app_id($app_id)
	{
    $this->db->where('app_id', $app_id);
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get('pre_application_notes')->result_array();
	}

    public function update_pre_application($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('pre_application', $data);
    }
    
    public function delete_multiple_pre_applicationt($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('clients');
    }

    public function delete_pre_application($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('pre_application');
    }


}
