<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_report extends AdminController {
  
public function __construct() {
    parent::__construct();

    // 🔒 Hard block: If URL contains /admin/pipeline_report, deny access immediately
    if (stripos(uri_string(), 'admin/pipeline_report') !== false) {
        show_error('❌ Access Denied: Direct access via /admin/pipeline_report is not allowed. Please use /reseller/pipeline_report.', 403, 'Forbidden');
        exit;
    }
  
  	   // 🔒 Hard block: If URL contains /admin/pipeline_report, deny access immediately
    if (stripos(uri_string(), 'admin/pipeline_report/create') !== false) {
        show_error('❌ Access Denied: Direct access via /admin/pipeline_report/create is not allowed. Please use /reseller/pipeline_report.', 403, 'Forbidden');
        exit;
    }

    $this->load->model('pre_application_model');
    $this->load->model('pipeline_report_model');
    $this->load->model('staff_model');
    $this->load->model('roles_model');
    $this->load->model('leads_model');
    $this->load->library('upload');

    // ✅ Restrict role to Agent only
    $current_user = $this->staff_model->get(get_staff_user_id());
    $rolevalue = $this->roles_model->get($current_user->role);

  
    // Block agent from all admin pipeline_report pages
        if ($role && strtolower($role->role) === 'agent') {
            show_error('Access Denied: You are not authorized to access this page.', 403, 'Forbidden');
        }
}

  
    private function normalize_for_compare($value) {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') return null;
        }

        // Convert default date to null
        if (in_array($value, ['0000-00-00', '0000-00-00 00:00:00'], true)) {
            return null;
        }

        // If it's numeric (even string), normalize as float string
        if (is_numeric($value)) {
            return (string)(float)$value;
        }

        // Normalize arrays
        if (is_array($value)) {
            sort($value);
            return json_encode(array_values($value));
        }

        return $value;
    }


public function index() 
{
    $current_user = $this->staff_model->get(get_staff_user_id());
    $rolevalue = $this->roles_model->get($current_user->role);

    // Only allow agents
    if (!$rolevalue || strtolower($rolevalue->name) !== 'agent') {
        show_error('Access Denied: You are not authorized to access this page.', 403, 'Forbidden');
    }

    $this->load->model('pipeline_report_model');

    $data['title'] = 'Pipeline Report';

    // Get current user's assigned applications
    $user_id = get_staff_user_id();
    $applications = $this->pipeline_report_model->get_pipeline_report_by_user($user_id);

    // Debug: uncomment if needed
    // echo '<pre>'; print_r($applications); die();

    // Process applications
    $seen = [];
    foreach ($applications as &$app) {
        $key = strtolower(trim($app['personal_email_address'] ?? '')) 
             . '|' . trim($app['cell_number'] ?? '') 
             . '|' . strtolower(trim($app['bank_name'] ?? ''));
        if (isset($seen[$key])) {
            $app['is_duplicate'] = true;
            $app['duplicate_key'] = $key;
        } else {
            $seen[$key] = true;
            $app['is_duplicate'] = false;
            $app['duplicate_key'] = null;
        }

        // Ensure assignment_type and record_type exist
        $app['assignment_type'] = $app['assignment_type'] ?? 'N/A';
        $app['record_type'] = 'Merchant';
    }
    unset($app);

    $data['combined_list'] = $applications;
    $data['pre_application_merchant_status'] = false;
    $data['pipeline_user_status'] = false;

    $this->load->view('reseller/pipeline_report/list', $data);
}


    public function activity_log($id)
    {
        $this->load->model('pipeline_report_model');
        $agent_db = $this->load->database('agent_crm', true);
        $agent_db->where('pipeline_report_id', $id);
        $agent_db->order_by('updated_at', 'DESC');
        $data['logs'] = $agent_db->get('pipeline_activity_log')->result_array();
        $data['title'] = 'Activity Log';
        
        $this->load->view('reseller/pipeline_report/activity_log', $data);
    }



    public function copy($id)
    {

        $this->load->model('pipeline_report_model'); // Load model
        $data['pipeline_report'] = $this->pipeline_report_model->get_pre_application_by_id($id); // Fetch data by ID
    
        if (!$data['pipeline_report']) {
            show_404(); // Show error if no data found
        }
        
      	$data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();
        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();


        $this->load->view('reseller/pipeline_report/copy_view', $data);
    }

    public function create($id = null)
    {
        $this->load->model('pre_application_model'); // Load model
        $this->load->model('pipeline_report_model'); 

        $data['pre_application'] = $this->pre_application_model->get_pre_application_by_id($id);

        if ($this->input->post()) {
            $data = $this->input->post();
            $this->pipeline_report_model->add_pipeline_report($data);
            set_alert('success', 'Pipeline Report created successfully.');
             redirect(site_url('reseller/pipeline_report'));
        }

        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();
		$data['countries'] = $this->db->order_by('short_name', 'ASC')->get('tblcountries')->result_array();
        $data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();

        $data['title'] = 'Create Pipeline Report';
        $this->load->view('reseller/pipeline_report/pipeline_report', $data);
    }


    public function view($id)
    {
        $this->load->model('pipeline_report_model'); // Load model
        $data['pipeline_report'] = $this->pipeline_report_model->get_pre_application_by_id($id); // Fetch data by ID
    
        if (!$data['pipeline_report']) {
            show_404(); // Show error if no data found
        }
     	$data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();
        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();
		$data['countries'] = $this->db->order_by('short_name', 'ASC')->get('tblcountries')->result_array();
    
        $this->load->view('reseller/pipeline_report/view', $data);
    }

    public function update($id)
    {
    $this->load->model('pipeline_report_model');
    $this->load->library('upload');

    $existing = $this->pipeline_report_model->get_pre_application_by_id($id);

    if ($this->input->post()) {
        $post_data = $this->input->post(null, true);
        $upload_path = 'uploads/pipeline_docs/';
        $unique_code = str_pad($id, 7, '0', STR_PAD_LEFT);

        // Ensure upload directory exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $single_file_fields = [
            'kyc_kyb_docs', 'business_license',
            'articles_incorporation', 'utility_bill',
            'refund_policy', 'dropbox_link', 'voided_check'
        ];

        $file_data = [];

        foreach ($single_file_fields as $field) {
            // Check for file removal first
            if (!empty($post_data['remove_'.$field])) {
                // Delete the physical file
                if (!empty($existing[$field])) {
                    $file_path = FCPATH . $upload_path . $existing[$field];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                $file_data[$field] = null;
            }
            // Check for new file upload
            elseif (!empty($_FILES[$field]['name'])) {
                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'pdf|jpg|jpeg|png',
                    'max_size'      => 2048,
                    'file_name'     => $unique_code.'_'.time().'_'.$_FILES[$field]['name'],
                    'overwrite'     => false
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload($field)) {
                    // Delete old file if exists
                    if (!empty($existing[$field])) {
                        $old_file_path = FCPATH . $upload_path . $existing[$field];
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                    
                    $upload_data = $this->upload->data();
                    $file_data[$field] = $upload_data['file_name'];
                } else {
                    // On upload error, keep existing file
                    $file_data[$field] = $existing[$field] ?? null;
                    set_alert('danger', 'Upload failed for ' . $field . ': ' . strip_tags($this->upload->display_errors()));
                }
            }
            // If no changes, use the current file from hidden field or existing data
            else {
                $file_data[$field] = $post_data['current_'.$field] ?? $existing[$field] ?? null;
            }
        }

        // Merge all data
        $data = array_merge($post_data, $file_data);

        // Remove temporary fields before saving
        foreach ($single_file_fields as $field) {
            unset($data['current_'.$field]);
            unset($data['remove_'.$field]);
        }

        // Handle multiple file uploads (upload_file[])
        if (!empty($_FILES['upload_file']['name'][0])) {
            $uploaded_files = [];
            
            foreach ($_FILES['upload_file']['name'] as $key => $name) {
                if (!empty($name)) {
                    $_FILES['file']['name'] = $name;
                    $_FILES['file']['type'] = $_FILES['upload_file']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['upload_file']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['upload_file']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['upload_file']['size'][$key];

                    $config = [
                        'upload_path' => $upload_path,
                        'allowed_types' => 'pdf|jpg|jpeg|png',
                        'max_size' => 2048,
                        'file_name' => $unique_code.'_'.time().'_'.$name,
                        'overwrite' => false
                    ];

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $uploaded_files[] = $this->upload->data('file_name');
                    }
                }
            }

            if (!empty($uploaded_files)) {
                $existing_files = !empty($existing['upload_file']) ? explode(',', $existing['upload_file']) : [];
                $data['upload_file'] = implode(',', array_merge($existing_files, $uploaded_files));
            }
        }


               // Handle multiple file uploads (dropbox_files[])
        if (!empty($_FILES['dropbox_files']['name'][0])) {
            $uploaded_files = [];
            
            foreach ($_FILES['dropbox_files']['name'] as $key => $name) {
                if (!empty($name)) {
                    $_FILES['file']['name'] = $name;
                    $_FILES['file']['type'] = $_FILES['dropbox_files']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['dropbox_files']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['dropbox_files']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['dropbox_files']['size'][$key];

                    $config = [
                        'upload_path' => $upload_path,
                        'allowed_types' => 'pdf|jpg|jpeg|png',
                        'max_size' => 2048,
                        'file_name' => $unique_code.'_'.time().'_'.$name,
                        'overwrite' => false
                    ];

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $uploaded_files[] = $this->upload->data('file_name');
                    }
                }
            }

            if (!empty($uploaded_files)) {
                $existing_files = !empty($existing['dropbox_files']) ? explode(',', $existing['dropbox_files']) : [];
                $data['dropbox_files'] = implode(',', array_merge($existing_files, $uploaded_files));
            }
        }


        // Handle multiple file deletions
        if (!empty($post_data['remove_files'])) {
            $current_files = !empty($existing['upload_file']) ? explode(',', $existing['upload_file']) : [];
            $remaining_files = array_diff($current_files, $post_data['remove_files']);
            
            foreach ($post_data['remove_files'] as $file_to_delete) {
                $file_path = FCPATH.$upload_path.$file_to_delete;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $data['upload_file'] = !empty($remaining_files) ? implode(',', $remaining_files) : '';
        }

                // Handle multiple file deletions
        if (!empty($post_data['remove_files'])) {
            $current_files = !empty($existing['dropbox_files']) ? explode(',', $existing['dropbox_files']) : [];
            $remaining_files = array_diff($current_files, $post_data['remove_files']);
            
            foreach ($post_data['remove_files'] as $file_to_delete) {
                $file_path = FCPATH.$upload_path.$file_to_delete;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $data['dropbox_files'] = !empty($remaining_files) ? implode(',', $remaining_files) : '';
        }
      
       $skip_fields = ['csrf_token', 'submit_button', 'merchant_status'];
        $changed_fields = [];

        foreach ($post_data as $key => $value) {
            if (in_array($key, $skip_fields)) {
                continue;
            }

            if (!array_key_exists($key, $existing)) {
                continue;
            }

            $old = $existing[$key];

            $old_normalized = $this->normalize_for_compare($old);
            $new_normalized = $this->normalize_for_compare($value);

            if ($old_normalized !== $new_normalized) {
                $changed_fields[$key] = [
                    'old' => $old,
                    'new' => $value,
                ];
            }
        }


        if (!empty($changed_fields)) {
            $this->load->model('pipeline_report_model');
            foreach ($changed_fields as $field => $values) {
                $log_data = [
                    'pipeline_report_id' => $id,
                    'field_name' => $field,
                    'old_value' => $values['old'],
                    'new_value' => $values['new'],
                    'updated_by' => get_staff_user_id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->pipeline_report_model->log_pipeline_activity($log_data);
            }
        }



        
        // Handle RDR field
        $post_data['rdr'] = $this->input->post('rdr') ?? null;

        $updated = $this->pipeline_report_model->update_pre_application($id, $data);

     if ($updated) {
    set_alert('success', 'Application updated successfully.');
      redirect(site_url('reseller/pipeline_report/view/' . $id)); // Stay on same page
  } else {
      set_alert('danger', 'No changes made or update failed.');
      redirect(site_url('reseller/pipeline_report/view/' . $id)); // Still stay on same page
  }

         //redirect(site_url('reseller/pipeline_report'));
    }

    // Load view data
    $data['pipeline_report'] = $existing;
    $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
    $data['staff_agents'] = $this->staff_model->get_agents();
    $data['title'] = 'Edit Pipeline Report';

    $this->load->view('reseller/pipeline_report/form', $data);
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
            $updated = $agent_crm_db->update('pipeline_report', ['merchant_status' => $status]); 

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
  


    public function delete($id)
    {
        $deleted = $this->Pipeline_report_model->delete_pre_application($id);
        if ($deleted) {
            set_alert('success', 'Pipeline Report deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Pipeline Report.');
        }
        redirect(site_url('reseller/pipeline_report'));
    }

    public function delete_multiple()
    {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');
            
            if (!empty($ids)) {
                $this->load->model('Pipeline_report_model');
                
                $deleted_count = 0;
                foreach ($ids as $id) {
                    // Optional: Add permission checks or logging here
                    if ($this->Pipeline_report_model->delete_pre_application($id)) {
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

    public function update_toggle()
    {
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        // Only allow specific fields
        if (!in_array($field, ['match_tmf', 'ubo'])) {
            show_error('Invalid field');
        }

        $this->load->model('pre_application_model');
        $this->pipeline_report_model->update_pre_application($id, [$field => $value]);

        echo json_encode(['status' => 'success']);
    }



    public function submit()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $errors = [];
            //Sanitize and Validate Fields

        $this->load->model('pipeline_report_model');
                    
        // Get last reseller ID from the database and generate next code
        $last_id = $this->pipeline_report_model->get_pre_applications_id_max();
        $next_id = $last_id + 1; // Increment ID
        $unique_code = str_pad($next_id, 7, '0', STR_PAD_LEFT); // Format as 7-digit
            $data = [
                'lead_source' => filter_var($this->input->post('lead_source', true), FILTER_SANITIZE_STRING),
                'application_date'  => filter_var($this->input->post('application_date', true), FILTER_SANITIZE_STRING),
                'application_ip'  => filter_var($this->input->post('application_ip', true), FILTER_SANITIZE_STRING),
                'assigned_to_mst' => filter_var($this->input->post('assigned_to_mst', true), FILTER_SANITIZE_STRING),
                'payment_type' => filter_var($this->input->post('payment_type', true), FILTER_SANITIZE_STRING),
                'application_type' => filter_var($this->input->post('application_type', true), FILTER_SANITIZE_STRING),
                'legal_name' => filter_var($this->input->post('legal_name', true), FILTER_SANITIZE_STRING),
                'dba' => filter_var($this->input->post('dba', true), FILTER_SANITIZE_STRING),
                'business_address'=> filter_var($this->input->post('business_address', true), FILTER_SANITIZE_STRING),
                'website_url' => filter_var($this->input->post('website_url', true), FILTER_SANITIZE_URL),
                'business_type' => filter_var($this->input->post('business_type', true), FILTER_SANITIZE_STRING),
                'tin_ein' => filter_var($this->input->post('tin_ein', true), FILTER_SANITIZE_STRING),
                'incorporation_country' => filter_var($this->input->post('incorporation_country', true), FILTER_SANITIZE_STRING),
                'incorporation_date' => filter_var($this->input->post('incorporation_date', true), FILTER_SANITIZE_STRING),
                'registration_number' => filter_var($this->input->post('registration_number', true), FILTER_SANITIZE_STRING),
                'ownership_structure' => filter_var($this->input->post('ownership_structure', true), FILTER_SANITIZE_STRING),
                'first_name' => filter_var($this->input->post('first_name', true), FILTER_SANITIZE_STRING),
                'last_name' => filter_var($this->input->post('last_name', true), FILTER_SANITIZE_STRING),
                'role_relationship' => filter_var($this->input->post('role_relationship', true), FILTER_SANITIZE_STRING),
                'contact_phone'  => filter_var($this->input->post('contact_phone', true), FILTER_SANITIZE_NUMBER_INT),
                'email_address' => filter_var($this->input->post('email_address', true), FILTER_VALIDATE_EMAIL),
                'id_number' => filter_var($this->input->post('id_number', true), FILTER_SANITIZE_NUMBER_INT),
                'company_name' => filter_var($this->input->post('company_name', true), FILTER_SANITIZE_STRING),
                'principal_address' => filter_var($this->input->post('principal_address', true), FILTER_SANITIZE_STRING),
                'dob' => filter_var($this->input->post('dob', true), FILTER_SANITIZE_STRING),
                'home_address' => filter_var($this->input->post('home_address', true), FILTER_SANITIZE_STRING),
                'ownership_percentage'  => filter_var($this->input->post('ownership_percentage', true), FILTER_SANITIZE_NUMBER_INT),
                'title_role' => filter_var($this->input->post('title_role', true), FILTER_SANITIZE_STRING),
                'first_name_2' => $this->input->post('first_name_2'),
    			'last_name_2' => $this->input->post('last_name_2'),
                'role_relationship_2' => filter_var($this->input->post('role_relationship_2', true), FILTER_SANITIZE_STRING),
                'contact_phone_2'  => filter_var($this->input->post('contact_phone_2', true), FILTER_SANITIZE_NUMBER_INT),
                'email_address_2' => filter_var($this->input->post('email_address_2', true), FILTER_VALIDATE_EMAIL),
                'id_number_2' => filter_var($this->input->post('id_number_2', true), FILTER_SANITIZE_NUMBER_INT),
                'company_name' => filter_var($this->input->post('company_name_2', true), FILTER_SANITIZE_STRING),
                'principal_address_2' => filter_var($this->input->post('principal_address_2', true), FILTER_SANITIZE_STRING),
                'dob_2' => filter_var($this->input->post('dob_2', true), FILTER_SANITIZE_STRING),
                'home_address_2' => filter_var($this->input->post('home_address_2', true), FILTER_SANITIZE_STRING),
                'ownership_percentage_2'  => filter_var($this->input->post('ownership_percentage_2', true), FILTER_SANITIZE_NUMBER_INT),
                'title_role_2' => filter_var($this->input->post('title_role_2', true), FILTER_SANITIZE_STRING),
                'bank_name' => filter_var($this->input->post('bank_name', true), FILTER_SANITIZE_STRING),
                'bank_account' => filter_var($this->input->post('bank_account', true), FILTER_SANITIZE_STRING),
                'routing_swift_iban' => filter_var($this->input->post('routing_swift_iban', true), FILTER_SANITIZE_STRING),
                'account_holder' => filter_var($this->input->post('account_holder', true), FILTER_SANITIZE_STRING),
                'account_type'=> filter_var($this->input->post('account_type', true), FILTER_SANITIZE_STRING),
                'projected_volume' => filter_var($this->input->post('projected_volume', true), FILTER_SANITIZE_STRING),
                'current_volume' => filter_var($this->input->post('current_volume', true), FILTER_SANITIZE_STRING),
                'currency'  => filter_var($this->input->post('currency', true), FILTER_SANITIZE_STRING),
                'processing_type'   => filter_var($this->input->post('processing_type', true), FILTER_SANITIZE_STRING),
                'sales_type'    => filter_var($this->input->post('sales_type', true), FILTER_SANITIZE_STRING),
                'risk_category' => filter_var($this->input->post('risk_category', true), FILTER_SANITIZE_STRING),
                'chargeback_percent'   => filter_var($this->input->post('chargeback_percent', true), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'mcc_code' => filter_var($this->input->post('mcc_code', true), FILTER_SANITIZE_STRING),
                'custom_mcc_code' => filter_var($this->input->post('custom_mcc_code', true), FILTER_SANITIZE_STRING),            
                'boarding_platform' => filter_var($this->input->post('boarding_platform', true), FILTER_SANITIZE_STRING),
                'back_processor'=> filter_var($this->input->post('back_processor', true), FILTER_SANITIZE_STRING),
                'mid_assigned' => filter_var($this->input->post('mid_assigned', true), FILTER_SANITIZE_STRING),
                'boarded_urls' => filter_var($this->input->post('boarded_urls', true), FILTER_SANITIZE_URL),
                'boarding_date' => filter_var($this->input->post('boarding_date', true), FILTER_SANITIZE_STRING),
                'go_live_date' => filter_var($this->input->post('go_live_date', true), FILTER_SANITIZE_STRING),
                'next_followup' => filter_var($this->input->post('next_followup', true), FILTER_SANITIZE_STRING),
                'last_contacted' => filter_var($this->input->post('last_contacted', true), FILTER_SANITIZE_STRING),
                'internal_notes' => filter_var($this->input->post('internal_notes', true), FILTER_SANITIZE_STRING),
                'business_city' => filter_var($this->input->post('business_city', true), FILTER_SANITIZE_STRING),
                'business_state' => filter_var($this->input->post('business_state', true), FILTER_SANITIZE_STRING),
                'business_zip' => filter_var($this->input->post('business_zip', true), FILTER_SANITIZE_STRING),
                'business_country' => filter_var($this->input->post('business_country', true), FILTER_SANITIZE_STRING),
                'pre_application_id' => filter_var($this->input->post('pre_application_id', true), FILTER_SANITIZE_NUMBER_INT),
                'kyc_city' => filter_var($this->input->post('kyc_city', true), FILTER_SANITIZE_STRING),
                'kyc_state' => filter_var($this->input->post('kyc_state', true), FILTER_SANITIZE_STRING),
                'kyc_zip' => filter_var($this->input->post('kyc_zip', true), FILTER_SANITIZE_STRING),
                'kyc_country' => filter_var($this->input->post('kyc_country', true), FILTER_SANITIZE_STRING),
                'country' => filter_var($this->input->post('country', true), FILTER_SANITIZE_STRING),
                'referred_by' => filter_var($this->input->post('referred_by', true), FILTER_SANITIZE_STRING),
                'rdr' => filter_var($this->input->post('rdr', true), FILTER_SANITIZE_STRING),
                'region' => filter_var($this->input->post('region', true), FILTER_SANITIZE_STRING),
                'user_id' => get_staff_user_id(),
                'merchant_status' => filter_var($this->input->post('merchant_status', true), FILTER_SANITIZE_STRING),
                'other_merchant_account' => filter_var($this->input->post('other_merchant_account', true), FILTER_SANITIZE_STRING),
                'matt_blacklist' => filter_var($this->input->post('matt_blacklist', true), FILTER_SANITIZE_STRING),
                'bankruptcy_history' => filter_var($this->input->post('bankruptcy_history', true), FILTER_SANITIZE_STRING),
              	'underwriting' => filter_var($this->input->post('underwriting', true), FILTER_SANITIZE_STRING),
                'management' => filter_var($this->input->post('management', true), FILTER_SANITIZE_STRING),
                'notes' => filter_var($this->input->post('notes', true), FILTER_SANITIZE_STRING),
              	'driving_license' => filter_var($this->input->post('driving_license', true), FILTER_SANITIZE_STRING),
                'state_of_license' => filter_var($this->input->post('state_of_license', true), FILTER_SANITIZE_STRING),
				'bank_location' => filter_var($this->input->post('bank_location', true), FILTER_SANITIZE_STRING),

                
            ];
			
          	if ($this->input->post('business_phone')) {
                $clean_phone = preg_replace('/\D+/', '', $this->input->post('business_phone'));
                $data['business_phone'] = $clean_phone;
            }


            $agent_db = $this->load->database('agent_crm', true);
            $agent_db->where('id', $data['merchant_status']);
            $agent_db->update('pre_application', ['merchant_pipeline_status' => 'Completed']);

            // 1. Get region and sanitize
            $region = strtoupper(trim($this->input->post('region', true)));

            // 2. Define base and year suffix
            $base_number = 3650;
            $year_suffix = date('y'); // e.g., 25 for 2025

            // 3. Get the current count of pipeline reports
            $total_existing = $this->pipeline_report_model->get_pipeline_report_count();

            // 4. Generate application_id
            $serial = $base_number + $total_existing + 1;
            $application_id = $region . $year_suffix . $serial;

            // 5. Add it to your $data array
            $data['application_id'] = $application_id;



        // Handle File Uploads
        $files = ['kyc_kyb_docs', 'business_license', 'articles_incorporation', 'utility_bill', 'refund_policy', 'dropbox_link','voided_check'];
        $upload_path = 'uploads/business_merchant_docs/';

        // Ensure upload directory exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $this->load->library('upload');

        foreach ($files as $file) {
            if (!empty($_FILES[$file]['name'])) {
                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'pdf|jpg|jpeg|png',
                    'max_size'      => 2048, // 2MB
                    'file_name'     => $unique_code . '_' . time() . '_' . $_FILES[$file]['name'],
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload($file)) {
                    $upload_data = $this->upload->data();
                    $data[$file] = $upload_data['file_name'];
                } else {
                    $errors[$file] = $this->upload->display_errors();
                    $data[$file] = null;
                }
            } else {
                $data[$file] = null;
            }
        }

        
        $uploaded_files = [];

if (!empty($_FILES['upload_file']['name'][0])) {
    $file_count = count($_FILES['upload_file']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        $_FILES['file']['name']     = $_FILES['upload_file']['name'][$i];
        $_FILES['file']['type']     = $_FILES['upload_file']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['upload_file']['tmp_name'][$i];
        $_FILES['file']['error']    = $_FILES['upload_file']['error'][$i];
        $_FILES['file']['size']     = $_FILES['upload_file']['size'][$i];

        $config['upload_path']   = 'uploads/merchant_docs/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = $unique_code . '_' . time() . '_' . $_FILES['file']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $upload_data = $this->upload->data();
            $uploaded_files[] = $upload_data['file_name'];
        } else {
            $errors[] = $this->upload->display_errors();
        }
        }

        // Only set upload_file if there are any successful uploads
        if (!empty($uploaded_files)) {
            $data['upload_file'] = implode(',', $uploaded_files); // or json_encode($uploaded_files)
        }
    }

    if (!empty($_FILES['dropbox_files']['name'][0])) {
    $file_count = count($_FILES['dropbox_files']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        $_FILES['file']['name']     = $_FILES['dropbox_files']['name'][$i];
        $_FILES['file']['type']     = $_FILES['dropbox_files']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['dropbox_files']['tmp_name'][$i];
        $_FILES['file']['error']    = $_FILES['dropbox_files']['error'][$i];
        $_FILES['file']['size']     = $_FILES['dropbox_files']['size'][$i];

        $config['upload_path']   = 'uploads/merchant_docs/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = $unique_code . '_' . time() . '_' . $_FILES['file']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $upload_data = $this->upload->data();
            $uploaded_files[] = $upload_data['file_name'];
        } else {
            $errors[] = $this->upload->display_errors();
        }
        }

        // Only set upload_file if there are any successful uploads
        if (!empty($uploaded_files)) {
            $data['dropbox_files'] = implode(',', $uploaded_files); // or json_encode($uploaded_files)
        }
    }
   
            // If there are validation errors, show messages and stop submission
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    set_alert('danger', $error);
                }
                 redirect(site_url('reseller/pipeline_report/form'));
               // var_dump($error);
                return;
            }

            // Insert Data
            $insert_id = $this->pipeline_report_model->add_pipeline_report($data);

 			if ($insert_id) {
                set_alert('success', 'Pipeline Report submitted successfully.');
                redirect(base_url('reseller/pipeline_report/create')); 
            } else {
                set_alert('danger', 'Failed to submit Pipeline Report.');
                redirect(base_url('reseller/pipeline_report/create')); 
            }


                        
        }

        $this->load->view('pipeline_report/form', ['pre_application_id' => $data['pre_application_id']]);
      
    }



}
