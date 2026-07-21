<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pre_application extends AdminController {
    public function __construct() {
        parent::__construct();
        $this->load->model('pre_application_model');
        $this->load->model('Staff_model');
        $this->load->library('upload');
        $this->agent_crm_db = $this->load->database('agent_crm', TRUE);
                                                    
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

        
    $user_id = get_staff_user_id();
    $staff   = $this->staff_model->get($user_id); // fetch current staff details
    $role    = $this->roles_model->get($staff->role); // get role info

    // Check if Admin OR Partner Admin
    if (is_admin() || ($role && strtolower($role->name) === 'partner admin') || ($role && strtolower($role->name) === 'management')) {
        
        $applications = $this->pre_application_model->get_all_pre_application_list(); 
      
      	$staff_agents = $this->staff_model->get_agents();
        $agentsLookup = [];
        foreach ($staff_agents as $agent) {
            $agentsLookup[$agent->staffid] = $agent->firstname . ' ' . $agent->lastname;
        }
        
        $seen = [];
        foreach ($applications as &$app) {
            $key = strtolower(trim($app['personal_email_address'])) . '|' . trim($app['cell_number']) . '|' . strtolower(trim($app['bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
            }

                   $employeeData = $this->pre_application_model->get_employee_name_by_id($app['assigned_employee']);

            if ($employeeData) {
                $app['assign_employee'] = $employeeData->firstname . ' ' . $employeeData->lastname;
            } else {
                $app['assign_employee'] = 'Unassigned';
            }  
           // Agent name (from lookup)
   		 $app['assign_agent'] = !empty($app['assigned_agent']) && isset($agentsLookup[$app['assigned_agent']])
        ? $agentsLookup[$app['assigned_agent']]
        : null;
       
        }
        $data['applications'] = $applications;
        }else{

        $user_id = get_staff_user_id();

        $applications = $this->pre_application_model->get_pre_application_list_by_user_id($user_id); 
        
        $seen = [];
        foreach ($applications as &$app) {
            $key = strtolower(trim($app['personal_email_address'])) . '|' . trim($app['cell_number']) . '|' . strtolower(trim($app['bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
            }

                    $employeeData = $this->pre_application_model->get_employee_name_by_id($app['assigned_employee']);

            if ($employeeData) {
                $app['assign_employee'] = $employeeData->firstname . ' ' . $employeeData->lastname;
            } else {
                $app['assign_employee'] = 'Unassigned';
            }   
        }
        $data['applications'] = $applications;

    }

        $data['title'] = 'Merchant Pre Application';
        $data['pre_application_merchant_status'] = is_admin();
        $this->load->view('admin/pre_application/list', $data);
    }

  	 public function pre_application_detail() {
        
    if (is_admin()) {
        
        $applications = $this->pre_application_model->get_all_pre_application_list(); 
        
        $seen = [];
        foreach ($applications as &$app) {
            $key = strtolower(trim($app['personal_email_address'])) . '|' . trim($app['cell_number']) . '|' . strtolower(trim($app['bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
            }

                    $employeeData = $this->pre_application_model->get_employee_name_by_id($app['assigned_employee']);

            if ($employeeData) {
                $app['assign_employee'] = $employeeData->firstname . ' ' . $employeeData->lastname;
            } else {
                $app['assign_employee'] = 'Unassigned';
            }  
        $agent_crm_db = $this->load->database('agent_crm', TRUE); 
        $app['esign_sent'] = $agent_crm_db->where('app_id', $app['id'])
        ->where('action', 'esign_sent')
        ->limit(1)
        ->get('pre_application_log')
        ->num_rows() > 0;
       
        }
        $data['applications'] = $applications;
        }else{

        $user_id = get_staff_user_id();

        $applications = $this->pre_application_model->get_pre_application_list_by_user_id($user_id); 
        
        $seen = [];
        foreach ($applications as &$app) {
            $key = strtolower(trim($app['personal_email_address'])) . '|' . trim($app['cell_number']) . '|' . strtolower(trim($app['bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
            }

                    $employeeData = $this->pre_application_model->get_employee_name_by_id($app['assigned_employee']);

            if ($employeeData) {
                $app['assign_employee'] = $employeeData->firstname . ' ' . $employeeData->lastname;
            } else {
                $app['assign_employee'] = 'Unassigned';
            }  
        $agent_crm_db = $this->load->database('agent_crm', TRUE); 
        $app['esign_sent'] = $agent_crm_db->where('app_id', $app['id'])
        ->where('action', 'esign_sent')
        ->limit(1)
        ->get('pre_application_log')
        ->num_rows() > 0;
        }
        $data['applications'] = $applications;

    }
        $data['title'] = 'Merchant Pre Application';
        $data['pre_application_merchant_status'] = is_admin();
        $this->load->view('admin/pre_application/pre_application_detail', $data);

    }
    
    public function get_notes()
    {
    $app_id = $this->input->post('app_id');
    $this->load->model('pre_application_model');

    $notes = $this->pre_application_model->get_pre_application_notes_by_app_id($app_id);

    foreach ($notes as $note) {
        echo '
        <div class="d-flex justify-content-start mb-2">
            <div class="p-3 bg-light border rounded shadow-sm w-100" style="max-width: 90%;">
                <div class="mb-1">
                    <strong class="text-primary">' . htmlspecialchars($note['staff_name']) . '</strong>
                    <small class="text-muted float-end">' . date('M d, Y H:i', strtotime($note['created_at'])) . '</small>
                </div>
                <div class="text-dark small" style="white-space: pre-wrap;">' . htmlspecialchars($note['note']) . '</div>
            </div>
        </div>';
    }

    }

    public function add_note()
    {
        if ($this->input->post()) {
            $app_id = $this->input->post('app_id');
            $note = $this->input->post('note');
            $staff = get_staff_full_name(get_staff_user_id());

            $data = [
                'app_id' => $app_id,
                'note' => $note,
                'staff_name' => $staff,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->load->model('pre_application_model');
            $this->pre_application_model->insert_pre_application_notes($data);

            echo json_encode(['success' => true]);
            return;
        }

        echo json_encode(['success' => false]);
    }

    public function send_esign()
    {
    if (!$this->input->is_ajax_request()) {
        show_error('No direct script access allowed');
    }

    $app_id = $this->input->post('app_id');
    $staff_id = get_staff_user_id();

    if (!$app_id || !$staff_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        return;
    }

    $data = [
        'app_id' => $app_id,
        'action' => 'esign_sent',
        'staff_id' => $staff_id,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // You can use a dedicated logging method or reuse note table if suitable
    //$this->db->insert('pre_application_log', $data);
    $this->load->model('pre_application_model');
    $this->pre_application_model->insert_pre_application_log($data);

    echo json_encode(['success' => true]);
    }




    public function create()
    {
       $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
       $data['staff_agents'] = $this->staff_model->get_agents();

        if ($this->input->post()) {
            $data = $this->input->post();
            $this->pre_application_model->insert_pre_application($data);
            set_alert('success', 'Merchant Pre Application created successfully.');
            redirect(admin_url('pre_application'));
        }

        $data['title'] = 'Create Merchant Pre Application';
        $this->load->view('admin/pre_application/pre_application', $data);
    }
    public function view($id)
    {
        $this->load->model('pre_application_model'); // Load model
        $data['pre_application'] = $this->pre_application_model->get_pre_application_by_id($id); // Fetch data by ID

        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();


        if (!$data['pre_application']) {
            show_404(); // Show error if no data found
        }
        $data['pre_application_merchant_status'] = is_admin();
        $data['form_readonly'] = !is_admin();
        $this->load->view('admin/pre_application/view', $data);
    }

    public function copy($id)
    {
        $this->load->model('pre_application_model');
        $application = $this->pre_application_model->get_by_id($id);

        if (!$application) {
            set_alert('danger', 'Application not found.');
            redirect(admin_url('pre_application'));
        }
        // Remove ID to avoid confusion (we're creating a new record)
        unset($application->id);
        // Optionally clear fields that should not be copied
        // $application->merchant_status = 'Pending';

        $data['pre_application'] = (array) $application;
        $data['copy_mode'] = true;
        $data['form_readonly'] = false;
        $this->load->view('admin/pre_application/view', $data);
    }

    // 26.05.2025 
    // public function update($id)
    // {
    //     $this->load->model('pre_application_model');  
    //     $data = $this->input->post();
    //     $this->pre_application_model->update_pre_application($id, $data); 
    //     set_alert('success', 'Merchant Pre Application Updated Successfully');
    //     redirect(admin_url('pre_application/view/' . $id));
    //}

    public function update($id)
    {
        $this->load->model('pre_application_model');
		$input = $this->input->post();
        $last_id = $this->pre_application_model->get_last_pre_application_id();
        $next_id = $last_id + 1; // Increment ID
        $unique_code = str_pad($next_id, 7, '0', STR_PAD_LEFT); // Format as 7-digit
            //Sanitize and Validate Fields
            $data = [
                'status'          => $input['status'] ?? null,
                'owner_percentage'          => $input['owner_percentage'] ?? null,
                'first_name'          => $input['first_name'] ?? null,
                'last_name'           => $input['last_name'] ?? null,
                'home_address'             => $input['home_address'] ?? null,
                'city'               => $input['city'] ?? null,
                'state'               => $input['state'] ?? null,
                'country'               => $input['country'] ?? null,
                'zip_code'            => $input['zip_code'] ?? null,
                'cell_number'             => $input['cell_number'] ?? null,
                'personal_email_address'   => $input['personal_email_address'] ?? null,
                'date_received'       => $input['date_received'] ?? null,
                'date_of_birth'       => $input['date_of_birth'] ?? null,
                'ssn_passport'       => $input['ssn_passport'] ?? null,
                'business_system'       => $input['business_system'] ?? null,
                'business_name'       => $input['business_name'] ?? null,
                'business_type'       => $input['business_type'] ?? null,
                'fed_tax_id'       => $input['fed_tax_id'] ?? null,
                'merchandise_service'       => $input['merchandise_service'] ?? null,
                'merchandise_service'       => $input['merchandise_service'] ?? null,
                'high_ticket_amount'     => $input['high_ticket_amount'] ?? null,
                'average_ticket_amount'     => $input['average_ticket_amount'] ?? null,
                'average_monthly_volume'     => $input['average_monthly_volume'] ?? null,
                'business_telephone_number' => $input['business_telephone_number'] ?? null,
                'business_cs_number' => $input['business_cs_number'] ?? null,
                'business_fax_number' => $input['business_fax_number'] ?? null,
                'business_website_address'  => $input['business_website_address'] ?? null,
                'business_email_address'   => $input['business_email_address'] ?? null,
                'business_address'    => $input['business_address'] ?? null,
                'business_address_type'   => $input['business_address_type'] ?? null,
                'business_city'    => $input['business_city'] ?? null,
                'business_state'      => $input['business_state'] ?? null,
                'business_zip_code'    => $input['business_zip_code'] ?? null,
                'business_country'    => $input['business_country'] ?? null,
                'select_your_rates_fees'      => $input['select_your_rates_fees'] ?? null,
                'source_of_the_lead'    => $input['source_of_the_lead'] ?? null,
                'bank_name'      => $input['bank_name'] ?? null,
                'bank_phone_number'    => $input['bank_phone_number'] ?? null,
                'routing_number'      => $input['routing_number'] ?? null,
                'account_number'    => $input['account_number'] ?? null,
                'business_established'    => $input['business_established'] ?? null,
                'swift_number'      => $input['swift_number'] ?? null,
                'iban'    => $input['iban'] ?? null,
                'assigned_employee'      => $input['assigned_employee'] ?? null,
                'assigned_agent'      => $input['assigned_agent'] ?? null,
                'assigned_to'      => $input['assigned_to'] ?? null,
                'comments'      => $input['comments'] ?? null,
                'referred_by'      => $input['referred_by'] ?? null,
                'state_of_license'      => $input['state_of_license'] ?? null,
                'driving_license' => $input['driving_license'] ?? null,
            ];
        $errors = [];

        // Retain existing uploaded files
        $existing_record = $this->pre_application_model->get_by_id($id); // You need this method in the model

        $upload_path = 'uploads/merchant_docs/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
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
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                $config['max_size']      = 2048;
                $config['file_name']     = uniqid() . '_' . time() . '_' . $_FILES['file']['name'];

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $uploaded_files[] = $upload_data['file_name'];
                } else {
                    $errors[] = $this->upload->display_errors();
                }
            }

            // Merge old files + new ones
            if (!empty($uploaded_files)) {
                $existing_files = !empty($existing_record->upload_file)
                    ? explode(',', $existing_record->upload_file)
                    : [];

                $all_files = array_merge($existing_files, $uploaded_files);
                $data['upload_file'] = implode(',', $all_files);
            }
        } else {
            // If no files uploaded, retain existing
            $data['upload_file'] = $existing_record->upload_file;
        }

        $this->pre_application_model->update_pre_application($id, $data);

        set_alert('success', 'Merchant Pre Application Updated Successfully');
        redirect(admin_url('pre_application'));
    }

    

    public function delete($id)
    {
        $deleted = $this->pre_application_model->delete_pre_application($id);
        if ($deleted) {
            set_alert('success', 'Merchant Pre Application deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Merchant pre application.');
        }
        redirect(admin_url('pre_application'));
    }

    public function delete_multiple()
    {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');
            
            if (!empty($ids)) {
                $this->load->model('pre_application_model');
                
                $deleted_count = 0;
                foreach ($ids as $id) {
                    // Optional: Add permission checks or logging here
                    if ($this->pre_application_model->delete_pre_application($id)) {
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
            $updated = $agent_crm_db->update('pre_application', ['merchant_status' => $status]);



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


    public function delete_pre_application()
    {
        $id     = $this->input->post('id');
        $reason = $this->input->post('reason', true);

        if (!$id || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $staff_id = get_staff_user_id();
        $agent_db = $this->load->database('agent_crm', true);

        // Fetch record for snapshot
        $record = $agent_db->where('id', $id)->get('pre_application')->row_array();
        if (!$record) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }

        // Delete record
        $deleted = $agent_db->delete('pre_application', ['id' => $id]);

        if ($deleted) {
            // Save delete log
            $logData = [
                'record_id'    => $id,
                'agent_id'      => isset($record['agent_id']) ? $record['agent_id'] : null,
                'application_id'=> isset($record['application_id']) ? $record['application_id'] : null,
                'deleted_by'   => $staff_id,
                'deleted_at'   => date('Y-m-d H:i:s'),
                'reason'       => $reason,
                'data_snapshot'=> json_encode($record),
            ];
            $agent_db->insert('deleted_pre_applications', $logData);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
    }

    public function deleted_records()
    {
        $agent_db = $this->load->database('agent_crm', true);
        $data['deleted_records'] = $agent_db
            ->order_by('deleted_at', 'DESC')
            ->get('deleted_pre_applications')
            ->result_array();

        $this->load->view('admin/pre_application/deleted_records', $data);
    }





    public function submit()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $errors = [];

        $agent_db = $this->load->database('agent_crm', true);

        // ==== Agent ID (360xxx) ====
        $last_agent_code = $this->pre_application_model->get_last_agent_code();

        $last_number = 500; // start baseline
        if ($last_agent_code && strlen($last_agent_code) > 3) {
            $last_number = (int) substr($last_agent_code, 3);
        }
        $next_number = $last_number + 1;
        $unique_code_pre_app = '360' . str_pad($next_number, 3, '0', STR_PAD_LEFT);

        // ==== Application ID (S25xxxx) ====
        $last_app_code = $this->pre_application_model->get_last_application_code();
        $last_sequence = 3600; // baseline start for sequence part

        if ($last_app_code) {
            // Extract numeric sequence part (after prefix "S25")
            $last_sequence = (int) substr($last_app_code, 3);
        }
        $next_sequence = $last_sequence + 1;

        // Build new Application ID
        $yearTwo = date('y'); // "25"
        $applicationId = 'S' . $yearTwo . $next_sequence;

        $data = [
                'agent_id' => $unique_code_pre_app,
                'application_id' => $applicationId,
                'status'          => filter_var($this->input->post('status', true), FILTER_SANITIZE_STRING),
                'owner_percentage'          => filter_var($this->input->post('owner_percentage', true), FILTER_SANITIZE_NUMBER_INT),
                'first_name'          => filter_var($this->input->post('first_name', true), FILTER_SANITIZE_STRING),
                'last_name'           => filter_var($this->input->post('last_name', true), FILTER_SANITIZE_STRING),
                'home_address'             => filter_var($this->input->post('home_address', true), FILTER_SANITIZE_STRING),
                'suite_appartment'      => filter_var($this->input->post('suite_appartment', true), FILTER_SANITIZE_STRING),
                'city'               => filter_var($this->input->post('city', true), FILTER_SANITIZE_STRING),
                'state'               => filter_var($this->input->post('state', true), FILTER_SANITIZE_STRING),
                'country'               => filter_var($this->input->post('country', true), FILTER_SANITIZE_STRING),
                'zip_code'            => filter_var($this->input->post('zip_code', true), FILTER_SANITIZE_NUMBER_INT),
                'cell_number'             => filter_var($this->input->post('cell_number', true), FILTER_SANITIZE_NUMBER_INT),
                'personal_email_address'   => filter_var($this->input->post('personal_email_address', true), FILTER_SANITIZE_STRING),
                'date_received'       => filter_var($this->input->post('date_received', true), FILTER_SANITIZE_STRING),
                'date_of_birth'       => filter_var($this->input->post('date_of_birth', true), FILTER_SANITIZE_STRING),
                'ssn_passport'       => filter_var($this->input->post('ssn_passport', true), FILTER_SANITIZE_STRING),
                'business_system'       => filter_var($this->input->post('business_system', true), FILTER_SANITIZE_STRING),
                'business_name'       => filter_var($this->input->post('business_name', true), FILTER_SANITIZE_STRING),
                'business_type'       => filter_var($this->input->post('business_type', true), FILTER_SANITIZE_STRING),
                'fed_tax_id'       => filter_var($this->input->post('fed_tax_id', true), FILTER_SANITIZE_STRING),
                'merchandise_service'       => filter_var($this->input->post('merchandise_service', true), FILTER_SANITIZE_STRING),
                'merchandise_service'       => filter_var($this->input->post('merchandise_service', true), FILTER_SANITIZE_STRING),
                'high_ticket_amount'     => filter_var($this->input->post('high_ticket_amount', true), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'average_ticket_amount'     => filter_var($this->input->post('average_ticket_amount', true), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'average_monthly_volume'     => filter_var($this->input->post('average_monthly_volume', true), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'business_telephone_number' => filter_var($this->input->post('business_telephone_number', true), FILTER_SANITIZE_NUMBER_INT),
                'business_cs_number' => filter_var($this->input->post('business_cs_number', true), FILTER_SANITIZE_NUMBER_INT),
                'business_fax_number' => filter_var($this->input->post('business_fax_number', true), FILTER_SANITIZE_STRING),
                'business_website_address'  => filter_var($this->input->post('business_website_address', true), FILTER_SANITIZE_URL),
                'business_email_address'   => filter_var($this->input->post('business_email_address', true), FILTER_SANITIZE_STRING),
                'business_address'    => filter_var($this->input->post('business_address', true), FILTER_SANITIZE_STRING),
                'business_address_type'   => filter_var($this->input->post('business_address_type', true), FILTER_SANITIZE_STRING),
                'business_city'    => filter_var($this->input->post('business_city', true), FILTER_SANITIZE_STRING),
                'business_state'      => filter_var($this->input->post('business_state', true), FILTER_SANITIZE_STRING),
                'business_zip_code'    => filter_var($this->input->post('business_zip_code', true), FILTER_SANITIZE_STRING),
                'business_country'    => filter_var($this->input->post('business_country', true), FILTER_SANITIZE_STRING),
                'select_your_rates_fees'      => filter_var($this->input->post('select_your_rates_fees', true), FILTER_SANITIZE_STRING),
                'source_of_the_lead'    => filter_var($this->input->post('source_of_the_lead', true), FILTER_SANITIZE_STRING),
                'bank_name'      => filter_var($this->input->post('bank_name', true), FILTER_SANITIZE_STRING),
                'bank_phone_number'    => filter_var($this->input->post('bank_phone_number', true), FILTER_SANITIZE_STRING),
                'routing_number'      => filter_var($this->input->post('routing_number', true), FILTER_SANITIZE_STRING),
                'account_number'    => filter_var($this->input->post('account_number', true), FILTER_SANITIZE_STRING),
                'business_established'    => filter_var($this->input->post('business_established', true), FILTER_SANITIZE_STRING),
                'swift_number'      => filter_var($this->input->post('swift_number', true), FILTER_SANITIZE_STRING),
                'iban'    => filter_var($this->input->post('iban', true), FILTER_SANITIZE_STRING),
                'assigned_employee'      => filter_var($this->input->post('assigned_employee', true), FILTER_SANITIZE_STRING),
                'assigned_agent'      => filter_var($this->input->post('assigned_agent', true), FILTER_SANITIZE_STRING),
                'assigned_to'      => filter_var($this->input->post('assigned_to', true), FILTER_SANITIZE_STRING),
                'comments'      => filter_var($this->input->post('comments', true), FILTER_SANITIZE_STRING),
                'referred_by'      => filter_var($this->input->post('referred_by', true), FILTER_SANITIZE_STRING),
                'state_of_license'      => filter_var($this->input->post('state_of_license', true), FILTER_SANITIZE_STRING),
                'driving_license' => filter_var($this->input->post('driving_license', true), FILTER_SANITIZE_STRING),
                'user_id' => get_staff_user_id(),
                                
            ];

            // Check for validation errors
            // if (!$data['business_email_address']) {
            //     $errors['business_email_address'] = 'Invalid email format!';
            // }
            // if (!$data['business_website_address']) {
            //     $errors['business_website_address'] = 'Invalid website URL!';
            // }
            // if (!$data['business_website_address']) {
            //     $errors['business_website_address'] = 'Invalid contact website!';
            // }


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
                   
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    set_alert('danger', $error);
                }
                redirect(admin_url('pre_application/form'));
                return;
            }

            // Insert Data
            $insert_id = $this->pre_application_model->add_pre_application($data);

            if ($insert_id) {
                set_alert('success', 'Merchant Pre Application submitted successfully.');
                redirect(admin_url('pre_application'));
            } else {
                set_alert('danger', 'Failed to submit Merchant Pre Application.');
            }
                        
        }

        $this->load->view('admin/pre_application/form');
    }


}
