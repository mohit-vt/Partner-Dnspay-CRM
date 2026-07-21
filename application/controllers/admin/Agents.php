<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agents extends AdminController {
    public function __construct() {
        parent::__construct();
        $this->load->model('agents_model');
        $this->load->model('leads_model');
      
      	$staff_id = get_staff_user_id();
        $staff = $this->staff_model->get($staff_id);
        $role = $this->roles_model->get($staff->role);

        $role_name = strtolower($role->name ?? '');

        // 🔒 Hard block: Agents cannot access any admin pipeline_report page
        if ($role_name === 'agent') {
            show_error('❌ Access Denied: Agents cannot access admin pipeline report.', 403, 'Forbidden');
            exit;
        }
    }

    public function index() {
      
      	 $data['title'] = 'Sub Resellers List'; 
      
	$user_id = get_staff_user_id();
    $staff   = $this->staff_model->get($user_id); // fetch current staff details
    $role    = $this->roles_model->get($staff->role); // get role info

    // Check if Admin OR Partner Admin
    if (is_admin() || ($role && strtolower($role->name) === 'partner admin') || ($role && strtolower($role->name) === 'management')) {

        $data['leads'] = $this->agents_model->get_all_agents();

         } else {

        $user_id = get_staff_user_id();    

        $data['leads'] = $this->agents_model->get_agents_by_user_id($user_id);

         }
        // In your controller, after loading staff_model
                $staff_agents = $this->staff_model->get_agents();
                $agent_map = [];
                foreach ($staff_agents as $agent) {
                    $agent_map[$agent->staffid] = $agent->firstname . ' ' . $agent->lastname;
                }

                $data['agent_map'] = $agent_map;
         $this->load->view('admin/agents/agent_list', $data);
    }

    public function create($id = null)
    {
        $this->load->model('custom_fields_model');
        $this->load->model('leads_model');
        $this->load->model('staff_model');

       $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
       $data['staff_agents'] = $this->staff_model->get_agents();
        if ($id) {
            $lead = $this->leads_model->get($id);

            if (!$lead) {
                show_404();
            }

            $data['lead'] = (array) $lead;

            $this->load->database(); // Ensure DB loaded
            $this->db->select('f.name, v.value');
            $this->db->from('tblcustomfieldsvalues v');
            $this->db->join('tblcustomfields f', 'f.id = v.fieldid');
            $this->db->where('v.relid', $id);
            $this->db->where('v.fieldto', 'leads');
            $query = $this->db->get();
            
            $data['custom_fields'] = $query->result_array();
        } else {
            // Handle case when $id is not provided
            $data['lead'] = [];
            $data['custom_fields'] = [];
        }
      
        $data['custom_fields'] = isset($data['custom_fields']) ? $data['custom_fields'] : [];
      
        if ($this->input->post()) {
            $data = $this->input->post();

            // $this->Agents_model->insert_pipeline_report($data);

            redirect(admin_url('agents/submit')); 
        }


        $data['title'] = 'Create Agent';
        $this->load->view('admin/agents/agents', $data);
    }
    

    // public function view($id)
    // {
    //     $this->load->model('Agents_model'); // Load model
    //     $data['pipeline'] = $this->Agents_model->get_agents_by_id($id); // Fetch data by ID
    
    //     if (!$data['pipeline']) {
    //         show_404(); // Show error if no data found
    //     }
    
    //     $this->load->view('admin/agents/view', $data);
    // }

    public function view($id)
    {
        $this->load->model('agents_model'); 
        $data['agents'] = $this->agents_model->get_agents_by_id($id);
        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();
        // $lead = $this->leads_model->get($id);
        // if (!$lead) {
        //     show_404();
        // }
    
        // $data['lead'] = (array) $lead;
        // $this->load->database(); // Ensure DB loaded
        // $this->db->select('f.name, v.value');
        // $this->db->from('tblcustomfieldsvalues v');
        // $this->db->join('tblcustomfields f', 'f.id = v.fieldid');
        // $this->db->where('v.relid', $id);
        // $this->db->where('v.fieldto', 'leads');
        // $query = $this->db->get();
        
        // $data['custom_fields'] = $query->result_array();

        // $data['title'] = 'View Lead: ' . $data['lead']->name;

        $this->load->view('admin/agents/view', $data);
    }
    
    
    public function update($id)
    {
        $this->load->model('agents_model');
    
        $data = $this->input->post();
        $this->agents_model->update_agents($id, $data);
    
        set_alert('success', 'Agents Updated Successfully');
        redirect(admin_url('agents'));
        
    }
    

    public function delete($id)
    {
        $deleted = $this->Agents_model->delete_agents($id);
        if ($deleted) {
            set_alert('success', 'Agents deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Agents.');
        }
        redirect(admin_url('agents'));
    }



    public function delete_multiple()
    {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');
            
            if (!empty($ids)) {
                $this->load->model('Agents_model');
                
                $deleted_count = 0;
                foreach ($ids as $id) {
                    // Optional: Add permission checks or logging here
                    if ($this->Agents_model->delete_agents($id)) {
                        $deleted_count++;
                    }
                }

                echo json_encode([
                    'success' => true,
                    'message' => _l('deleted_successfully', $deleted_count . ' records')
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => _l('no_ids_provided')
                ]);
            }
        }
    }
  
  	  public function update_status()
    {
        header('Content-Type: application/json');

        $id = $this->input->post('id');
        $status = $this->input->post('status');

        if (!$id || !$status) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing ID or status']);
            return;
        }

        // Check if the table and column exist
        try {
            $status = ucfirst(strtolower($status)); // Normalize

            $agent_crm_db = $this->load->database('agent_crm', TRUE); // TRUE returns DB instance
            $agent_crm_db->where('id', $id);
            $updated = $agent_crm_db->update('agents', ['status' => $status]); 

            if ($updated) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Database update failed.');
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

	    public function delete_agents($id)
{
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        return;
    }

    try {
        // Load agent_crm DB
        $agent_db = $this->load->database('agent_crm', true);

        // Fetch agent from agent_crm db
        $agent = $agent_db->where('id', $id)->get('agents')->row();

        if (!$agent) {
            echo json_encode(['success' => false, 'message' => 'Agent not found']);
            return;
        }

        $current_user = $this->staff_model->get(get_staff_user_id());

        // Log deletion
        $logData = [
            'agent_id'        => $agent->id,
            'agent_code'      => $agent->agents_code,
            'application_id'  => isset($agent->application_id) ? $agent->application_id : null,
            'reason'          => $this->input->post('reason'),
            'deleted_at'      => date('Y-m-d H:i:s'),
            'ip_address'      => $this->input->ip_address(),
            'deleted_by'      => $current_user->staffid,
            'deleted_by_name' => $current_user->firstname . ' ' . $current_user->lastname,
            'deleted_by_role' => $this->roles_model->get($current_user->role)->name
        ];

        // Insert into agent_deletions (inside agent_crm DB)
        $agent_db->insert('agent_deletions', $logData);

        // Delete from agents table
        $agent_db->where('id', $id)->delete('agents');

        echo json_encode(['success' => true, 'message' => 'Agent deleted successfully.']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function agent_deletion_record()
{
    if (!has_permission('agents', '', 'view')) {
        access_denied('agents');
    }

    // Load agent_crm DB
    $agent_db = $this->load->database('agent_crm', true);

    // Get deletions from agent_crm
    $deletions = $agent_db->order_by('deleted_at', 'DESC')->get('agent_deletions')->result_array();

    // Attach staff names from royalsec_crm.tblstaff
    foreach ($deletions as &$del) {
        $staff = $this->db->select('firstname, lastname')
                          ->where('staffid', $del['deleted_by'])
                          ->get('tblstaff')
                          ->row();

        $del['deleted_by_name'] = $staff ? $staff->firstname . ' ' . $staff->lastname : 'Unknown';
    }

    $data['deletions'] = $deletions;
    $data['title'] = 'Deleted Agent Records';

    $this->load->view('admin/agents/agent_deletion_record', $data);
}

public function submit()
{
    if ($this->input->server('REQUEST_METHOD') === 'POST') {

        $errors = [];
        $agent_db = $this->load->database('agent_crm', true);

        // ✅ Strong duplicate protection (idempotency token)
        $submit_token = $this->input->post('submit_token', true);
        if (empty($submit_token)) {
            set_alert('danger', 'Invalid submission token.');
            redirect(admin_url('agents/create'));
            return;
        }

        // ✅ Prevent same token being processed twice
        if ($this->agents_model->token_exists($submit_token)) {
            set_alert('danger', 'Duplicate submission blocked.');
            redirect(admin_url('agents'));
            return;
        }

        // ✅ Generate next agents_code safely (still vulnerable without DB unique, but OK)
        $last_code = (string) $this->agents_model->get_last_agents_code();
        $last_number = (strlen($last_code) > 3) ? (int) substr($last_code, 3) : 500;
        $last_number = max(500, $last_number);
        $unique_code = '360' . str_pad((string) ($last_number + 1), 3, '0', STR_PAD_LEFT);

        // ✅ Primary interest list handling
        $primary_interests = $this->input->post('primary_interest');
        $primary_interests_str = is_array($primary_interests) ? implode(', ', $primary_interests) : '';

        // ✅ Build data array (DO NOT generate application_id BEFORE insert!)
        $data = [
            'agents_code' => $unique_code,
            'application_id' => null, // generated after insert
            'business_nature' => filter_var($this->input->post('business_nature', true), FILTER_SANITIZE_STRING),
            'years_in_business' => filter_var($this->input->post('years_in_business', true), FILTER_SANITIZE_STRING),
            'current_relationships' => filter_var($this->input->post('current_relationships', true), FILTER_SANITIZE_STRING),
            'assigned_to_agent' => filter_var($this->input->post('assigned_to_agent', true), FILTER_SANITIZE_STRING),
            'custom_interest' => filter_var($this->input->post('custom_interest', true), FILTER_SANITIZE_STRING),
            'has_sales_reps' => filter_var($this->input->post('has_sales_reps', true), FILTER_SANITIZE_STRING),
            'primary_interest' => $primary_interests_str,
            'brick_mortar_percentage' => filter_var($this->input->post('brick_mortar_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'retail_high_risk_percentage' => filter_var($this->input->post('retail_high_risk_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'card_not_present_percentage' => filter_var($this->input->post('card_not_present_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'international_percentage' => filter_var($this->input->post('international_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'first_name' => filter_var($this->input->post('first_name', true), FILTER_SANITIZE_STRING),
            'last_name' => filter_var($this->input->post('last_name', true), FILTER_SANITIZE_STRING),
            'email' => filter_var($this->input->post('email', true), FILTER_VALIDATE_EMAIL),
            'phone' => preg_replace('/\D+/', '', $this->input->post('phone', true)),
            'legal_name' => filter_var($this->input->post('legal_name', true), FILTER_SANITIZE_STRING),
            'office_number' => preg_replace('/\D+/', '', $this->input->post('office_number', true)), // ✅ FIX: was URL sanitize
            'comments' => filter_var($this->input->post('comments', true), FILTER_SANITIZE_STRING),
            'user_id' => get_staff_user_id(),
            'date_created' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
            'submit_token' => $submit_token
        ];

        // ✅ Validation
        if (empty($data['business_nature'])) $errors[] = 'Business nature is required';
        if (empty($data['years_in_business'])) $errors[] = 'Years in business is required';
        if (empty($data['has_sales_reps'])) $errors[] = 'Sales reps info is required';
        if (empty($primary_interests)) $errors[] = 'At least one primary interest must be selected';
        if (empty($data['first_name'])) $errors[] = 'First name is required';
        if (empty($data['last_name'])) $errors[] = 'Last name is required';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (empty($data['phone'])) $errors[] = 'Phone number is required';
        if (empty($data['legal_name'])) $errors[] = 'Legal name is required';

        if (!empty($errors)) {
            foreach ($errors as $error) set_alert('danger', $error);
            redirect(admin_url('agents/create'));
            return;
        }

        // ✅ Transaction protection
        $agent_db->trans_start();

        // ✅ Insert first
        $insert_id = $this->agents_model->add_agents($data);

        if ($insert_id) {
            // ✅ Generate application_id AFTER insert (concurrency safe)
            $offset = 3660;
            $sequenceNumber = $insert_id + $offset;
            $monthLetter = strtoupper(substr(date('F'), 0, 1));
            $yearTwo = date('y');
            $sequence = str_pad($sequenceNumber, 4, '0', STR_PAD_LEFT);
            $applicationId = $monthLetter . $yearTwo . $sequence;

            // ✅ Update application_id
            $this->agents_model->update_agents($insert_id, [
                'application_id' => $applicationId
            ]);
        }

        $agent_db->trans_complete();

        if ($agent_db->trans_status() === FALSE || !$insert_id) {
            set_alert('danger', 'Failed to submit application.');
            redirect(admin_url('agents/create'));
            return;
        }

        set_alert('success', 'Application submitted successfully.');
        redirect(admin_url('agents'));
        return;
    }

    // fallback view
    $this->load->view('admin/agents/form');
}
  
}
