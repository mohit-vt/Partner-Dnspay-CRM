<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pre_application extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('pre_application_model'); // Load model
        $this->load->model('staff_model'); // Load model
        $this->load->model('pipeline_report_model');
        $this->agent_db = $this->load->database('agent_crm', TRUE);
        $this->load->helper(['url', 'form']);
    }

    public function create()
    {
        $rid = $this->input->get('rid');
        $exists = $this->db->where('agent_id', $rid)->get(db_prefix().'staff')->row();
        if (!$exists) {
            show_error('Invalid or missing referral ID.');
        }

        $data['rid'] = $rid;
        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();
        $data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();
        // Load a public-friendly form (not admin theme)
        $this->load->view('pre_application/form', $data);
    }

  
  public function submit()
{
    $post = $this->input->post();
    
    
        $this->load->model('pipeline_report_model');
                    
        // Get last reseller ID from the database and generate next code
        $last_id = $this->pipeline_report_model->get_pre_applications_id_max();
        $next_id = $last_id + 1; // Increment ID
        $unique_code = str_pad($next_id, 7, '0', STR_PAD_LEFT); 

    if (empty($post['rid'])) {
        show_error('Referral ID missing.');
    }

    // Lookup staff by agent_id
    $agent = $this->db
        ->where('agent_id', $post['rid'])
        ->get(db_prefix().'staff')
        ->row();

    if (!$agent) {
        show_error('Invalid referral ID.');
    }

    // Detect browser / device
    if ($this->agent->is_browser()) {
        $ua = $this->agent->browser().' '.$this->agent->version();
    } elseif ($this->agent->is_mobile()) {
        $ua = $this->agent->mobile();
    } else {
        $ua = 'Unknown';
    }

   $data = [
        'lead_source'          => $post['lead_source'],
        'region'               => $post['region'],
        'application_date'     => $post['application_date'],
        'application_ip'       => $post['application_ip'],
        'company_name'         => $post['company_name'],
        'payment_type'         => $post['payment_type'],
        'application_type'     => $post['application_type'],
        'legal_name'           => $post['legal_name'],
        'dba'                  => $post['dba'],
        'business_address'     => $post['business_address'],
        'business_city'        => $post['business_city'],
        'business_state'       => $post['business_state'],
        'business_zip'         => $post['business_zip'],
        'business_country'     => $post['business_country'],
        'business_phone'       => $post['business_phone'],
        'website_url'          => $post['website_url'],
        'business_type'        => $post['business_type'],
        'tin_ein'              => $post['tin_ein'],
        'incorporation_country'=> $post['incorporation_country'],
        'incorporation_date'   => $post['incorporation_date'],
        'registration_number'  => $post['registration_number'],
        'ownership_structure'  => $post['ownership_structure'],
        'driving_license'      => $post['driving_license'],
        'first_name'           => $post['first_name'],
        'last_name'            => $post['last_name'],
        'role_relationship'    => $post['role_relationship'],
        'contact_phone'        => $post['contact_phone'],
        'email_address'        => $post['email_address'],
        'id_number'            => $post['id_number'],
        'principal_address'    => $post['principal_address'],
        'dob'                  => $post['dob'],
        'home_address'         => $post['home_address'],
        'kyc_city'             => $post['kyc_city'],
        'kyc_state'            => $post['kyc_state'],
        'kyc_zip'              => $post['kyc_zip'],
        'kyc_country'          => $post['kyc_country'],
        'ownership_percentage' => $post['ownership_percentage'],
        'title_role'           => $post['title_role'],
        'bank_name'            => $post['bank_name'],
        'bank_account'         => $post['bank_account'],
        'routing_swift_iban'   => $post['routing_swift_iban'],
        'account_holder'       => $post['account_holder'],
        'account_type'         => $post['account_type'],
        'projected_volume'     => $post['projected_volume'],
        'current_volume'       => $post['current_volume'],
        'currency'             => $post['currency'],
        'processing_type'      => $post['processing_type'],
     	'sales_type'	 	   => $post['sales_type'],
        'risk_category'	 	   => $post['risk_category'],
        'chargeback_percent'   => $post['chargeback_percent'],
        'rdr'	 	   	   	   => $post['rdr'],
     	'mcc_code'			   => $post['mcc_code'],
     	'state_of_license'	   => $post['state_of_license'],
        'boarding_platform'	   => $post['boarding_platform'],
        'back_processor'	   => $post['back_processor'],
        'mid_assigned'	 	   => $post['mid_assigned'],
        'country'	 	   	   => $post['country'],
     	'internal_notes'	   => $post['internal_notes'],
        'boarded_urls'	 	   => $post['boarded_urls'],
        'boarding_date'	 	   => $post['boarding_date'],
        'other_merchant_account' => $post['other_merchant_account'] ?? null,
        'matt_blacklist'         => $post['matt_blacklist'] ?? null,
        'bankruptcy_history'     => $post['bankruptcy_history'] ?? null,
        'dropbox_link'           => $post['dropbox_link'] ?? null,
        'go_live_date'	 	   => $post['go_live_date'],
        'pre_application_id'   => $post['rid'],
        'user_id'               => get_staff_user_id(),
     	'assigned_to_agent'    => $agent->staffid, 
        'ip_address'           => $this->input->ip_address(),
        'user_agent'           => $ua, // ✅ now correctly stored
        'created_at'           => date('Y-m-d H:i:s'),
    ];
    
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
   

    $this->agent_db->insert('pipeline_report', $data);

    $this->load->view('pre_application/thank_you');
}


}
