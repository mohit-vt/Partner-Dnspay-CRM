<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agents extends AdminController {
    public function __construct() {
        parent::__construct();
      	    // 🔒 Hard block: If URL contains /admin/pipeline_report, deny access immediately
    if (stripos(uri_string(), 'admin/agents') !== false) {
        show_error('❌ Access Denied: Direct access via /admin/agents is not allowed. Please use /reseller/pipeline_report.', 403, 'Forbidden');
        exit;
    }
  
  	   // 🔒 Hard block: If URL contains /admin/pipeline_report, deny access immediately
    if (stripos(uri_string(), 'admin/agents/create') !== false) {
        show_error('❌ Access Denied: Direct access via /admin/agents/create is not allowed. Please use /reseller/pipeline_report.', 403, 'Forbidden');
        exit;
    }
        $this->load->model('agents_model');
        $this->load->model('leads_model');
      
        // ✅ Restrict role to Agent only
        $current_user = $this->staff_model->get(get_staff_user_id());
        $rolevalue = $this->roles_model->get($current_user->role);


        // Block agent from all admin pipeline_report pages
        if ($role && strtolower($role->role) === 'agent') {
            show_error('Access Denied: You are not authorized to access this page.', 403, 'Forbidden');
        }
    }

    public function index() {
      
      	 $data['title'] = 'Sub Resellers List'; 
      
         if (is_admin()) {

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
         $this->load->view('reseller/agents/agent_list', $data);
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
            $this->Agents_model->insert_pipeline_report($data);
            set_alert('success', 'Agent created successfully.');
             redirect(site_url('reseller/agents'));
        }

        $data['title'] = 'Create Agent';
        $this->load->view('reseller/agents/agents', $data);
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

   // public function view($id)
    //{
     //   $this->load->model('agents_model'); 
      //  $data['agents'] = $this->agents_model->get_agents_by_id($id);
     //   $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
      //  $data['staff_agents'] = $this->staff_model->get_agents();
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

        //$this->load->view('reseller/agents/view', $data);
   // }
    public function view($id)
{
    $this->load->model('agents_model'); 

    $current_user = get_staff(get_staff_user_id()); // get logged in staff

    if ($current_user->role == 'agent' && $current_user->id != $id) {
        show_error('You are not allowed to view other agents.', 403);
    }

    $data['agents'] = $this->agents_model->get_agents_by_id($id);
    $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
    $data['staff_agents'] = $this->staff_model->get_agents();

    $this->load->view('reseller/agents/view', $data);
}

    
    public function update($id)
    {
        //$this->load->model('agents_model');
        //$data = $this->input->post();
        //$this->agents_model->update_agents($id, $data);
      
      
      	$this->load->model('agents_model');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $formData = $this->input->post();
            $updated  = $this->agents_model->update_agents($id, $formData);

     			if ($updated) {
    					set_alert('success', 'Aent updated successfully.');
                        redirect(site_url('reseller/agents/view/' . $id)); // Stay on same page
                    } else {
                        set_alert('danger', 'No changes made or update failed.');
                        redirect(site_url('reseller/agents/view/' . $id)); // Still stay on same page
                    }
        }
    
        //set_alert('success', 'Agents Updated Successfully');
        //redirect(site_url('reseller/agents'));
        
    }
    

    public function delete($id)
    {
        $deleted = $this->Agents_model->delete_agents($id);
        if ($deleted) {
            set_alert('success', 'Agents deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Agents.');
        }
        redirect(site_url('reseller/agents'));
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


 public function submit()
    {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $errors = [];

        // Load agent_crm DB
        $agent_db = $this->load->database('agent_crm', true);

                // 1. Generate agents_code
        $last_code   = (string) $this->agents_model->get_last_agents_code();
        $last_number = (strlen($last_code) > 3) ? (int) substr($last_code, 3) : 500;
        $next_number = ($last_number < 500) ? 500 : $last_number + 1;
        $unique_code = '360' . str_pad((string) $next_number, 3, '0', STR_PAD_LEFT);

        // 2. Generate application_id via model helper
        $applicationId = $this->agents_model->get_next_application_id();
      
      	$primary_interests     = $this->input->post('primary_interest');
        $primary_interests_str = is_array($primary_interests) ? implode(', ', $primary_interests) : '';

        // Fallback in case of failure
        if (empty($applicationId)) {
            $applicationId = 'FALLBACK-' . uniqid();
		}


        $data = [
            'agents_code' => $unique_code,
            'application_id' => $applicationId,
            'business_nature' => filter_var($this->input->post('business_nature', true), FILTER_SANITIZE_STRING),
            'years_in_business' => filter_var($this->input->post('years_in_business', true), FILTER_SANITIZE_STRING),
          	'assigned_to_agent'  => filter_var($this->input->post('assigned_to_agent', true), FILTER_SANITIZE_STRING),
            'current_relationships' => filter_var($this->input->post('current_relationships', true), FILTER_SANITIZE_STRING),
            'has_sales_reps' => filter_var($this->input->post('has_sales_reps', true), FILTER_SANITIZE_STRING),
            'custom_interest' => filter_var($this->input->post('custom_interest', true), FILTER_SANITIZE_STRING),
           	'primary_interest' => $primary_interests_str,
            'brick_mortar_percentage' => filter_var($this->input->post('brick_mortar_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'retail_high_risk_percentage' => filter_var($this->input->post('retail_high_risk_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'card_not_present_percentage' => filter_var($this->input->post('card_not_present_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'international_percentage' => filter_var($this->input->post('international_percentage', true), FILTER_SANITIZE_NUMBER_INT),
            'first_name' => filter_var($this->input->post('first_name', true), FILTER_SANITIZE_STRING),
            'last_name' => filter_var($this->input->post('last_name', true), FILTER_SANITIZE_STRING),
            'email' => filter_var($this->input->post('email', true), FILTER_VALIDATE_EMAIL),
            'phone' => filter_var($this->input->post('phone', true), FILTER_SANITIZE_NUMBER_INT),
            'legal_name' => filter_var($this->input->post('legal_name', true), FILTER_SANITIZE_STRING),
            'office_number' => filter_var($this->input->post('office_number', true), FILTER_SANITIZE_NUMBER_INT),
            'comments' => filter_var($this->input->post('comments', true), FILTER_SANITIZE_STRING),
            'user_id' => get_staff_user_id(),
            'date_created' => date('Y-m-d H:i:s'),
            'status' => 'Pending' // Default status
        ];
      
      if (empty($data['application_id'])) {
            $errors[] = 'Application ID generation failed!';
        } 

        if (empty($data['business_nature'])) {
            $errors[] = 'Business nature is required';
        }
        if (empty($data['years_in_business'])) {
            $errors[] = 'Years in business is required';
        }
        if (empty($data['has_sales_reps'])) {
            $errors[] = 'Sales reps information is required';
        }
        if (empty($primary_interests)) {
            $errors[] = 'At least one primary interest must be selected';
        }
        if (empty($data['first_name'])) {
            $errors[] = 'First name is required';
        }
        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        if (empty($data['phone'])) {
            $errors[] = 'Phone number is required';
        }
        if (empty($data['legal_name'])) {
            $errors[] = 'Legal name is required';
        }



		  	     if (!empty($errors)) {
                foreach ($errors as $error) {
                    set_alert('danger', $error);
                }
            		} else {
                $insert_id = $this->agents_model->add_agents($data);
                   
                if ($insert_id) {
                    set_alert('success', 'Application submitted successfully.');
                    redirect(base_url('reseller/agents/create')); 
                } else {
                    set_alert('danger', 'Failed to submit Agent Application.');
                    redirect(base_url('reseller/agents/create')); 
                }
                 }
  

    }
  
	$this->load->view('admin/agents/form');

}


}