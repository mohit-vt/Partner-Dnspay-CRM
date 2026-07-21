<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'upload']);
        $this->load->model('agent_model');
    }

    public function create()
    {
        $this->load->view('agent/form');
    }

    public function submit()
    {
        $post = $this->input->post();
  


        /**
         * 1) VALIDATION
         */
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules(
            'email_address',
            'Email Address',
            'required|trim|valid_email|is_unique['.db_prefix().'staff.email]'
        );

        if (empty($post['contact_phone']) && empty($post['business_phone'])) {
            $this->form_validation->set_rules('contact_phone', 'Mobile Number', 'required');
        }

        if ($this->form_validation->run() == false) {
            // if your form needs extra data (countries/mcc etc) pass here
            $this->load->view('agent/form');
            return;
        }
      
      
      	 // ✅ Normalize primary_interest (multiple select)
        $primary_interest = $post['primary_interest'] ?? [];
        if (!is_array($primary_interest)) {
            $primary_interest = [$primary_interest];
        }
        $primary_interest_json = !empty($primary_interest)
            ? json_encode(array_values($primary_interest))
        : null;

        /**
         * helper to safely convert country selects to int/null
         */
        $to_int_or_null = function($val) {
            return ($val === '' || $val === null) ? null : (int)$val;
        };

        /**
         * 2) PREPARE STAFF DATA (tblstaff)
         */
        $role_id  = $this->agent_model->get_agent_role_id();

        if (!$role_id) {
            show_error('Role "agent" not found in roles table.');
        }

        /**
         * Generate agent_id here, 6 digits starting from 000001
         * Only for "agent" role.
         */
        $this->db->select('agent_id');
        $this->db->from(db_prefix() . 'staff');
        $this->db->where('role', $role_id);
        $this->db->where('agent_id IS NOT NULL', null, false);
        $this->db->order_by('staffid', 'DESC');
        $this->db->limit(1);
        $lastAgent = $this->db->get()->row();

        if ($lastAgent && is_numeric($lastAgent->agent_id)) {
            // e.g. "000005" -> 5
            $nextId = (int)$lastAgent->agent_id + 1;
        } else {
            // First agent
            $nextId = 1;
        }

        // ✅ 6 digits: 000001, 000002, 000003, ...
        $agent_id = str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $plain_password  = $this->agent_model->generate_password(10);
        $hashed_password = app_hash_password($plain_password);

        $staff_data = [
            'agent_id'    => $agent_id,
            'email'       => $post['email_address'],
            'firstname'   => $post['first_name'],
            'lastname'    => $post['last_name'],
            'phonenumber' => !empty($post['contact_phone']) ? $post['contact_phone'] : $post['business_phone'],
            'password'    => $hashed_password,
            'datecreated' => date('Y-m-d H:i:s'),
            'admin'       => 0,
            'role'        => $role_id,
            'active'      => 0,
            'is_not_staff'=> 0,
          	'user_status' => 'Pending',
        ];

        /**
         * 3) FILE UPLOADS
         */
        $upload_path = 'uploads/agent_docs/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $file_fields = [
            'business_license',
            'articles_incorporation',
            'corporate_documentation',
            'voided_check'
        ];

        $uploaded = [];
        foreach ($file_fields as $field) {
            $uploaded[$field] = null;

            if (!empty($_FILES[$field]['name'])) {
                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'pdf|jpg|jpeg|png',
                    'max_size'      => 4096,
                    'file_name'     => $agent_id . '_' . time() . '_' . $_FILES[$field]['name'],
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload($field)) {
                    $up = $this->upload->data();
                    $uploaded[$field] = $up['file_name'];
                } else {
                    log_message('error', 'Agent upload error ('.$field.'): '.$this->upload->display_errors());
                }
            }
        }

        /**
         * 4) START TRANSACTION
         */
        $this->db->trans_begin();

        // A) insert staff
        $ok_staff = $this->agent_model->create_agent($staff_data);
        $staffid  = $this->db->insert_id();

        if (!$ok_staff || !$staffid) {
            $this->db->trans_rollback();
            show_error('Failed to create agent in tblstaff.');
        }

        // B) insert application (tblagent_applications)
        $app_data = [
            'staffid'          => $staffid,
            'agent_id'         => $agent_id,

            // Basic
            'lead_source'      => $post['lead_source'] ?? null,
            'region'           => $post['region'] ?? null,
            'application_date' => $post['application_date'] ?? null,
            'application_ip'   => $post['application_ip'] ?? null,
            'company_name'     => $post['company_name'] ?? null,
           	'business_nature'     => $post['business_nature'] ?? null,
           	'years_in_business'     => $post['years_in_business'] ?? null,
           	'process_relationship'     => $post['process_relationship'] ?? null,
          	'sales_reps'  => $post['sales_reps'] ?? null,
           	'primary_interest'     => $primary_interest_json,  

            // Representative (KYC)
            'first_name'           => $post['first_name'] ?? null,
            'last_name'            => $post['last_name'] ?? null,
            'role_relationship'    => $post['role_relationship'] ?? null,
            'contact_phone'        => $post['contact_phone'] ?? null,
            'email_address'        => $post['email_address'] ?? null,
            'id_number'            => $post['id_number'] ?? null,
            'principal_address'    => $post['principal_address'] ?? null,
            'dob'                  => $post['dob'] ?? null,
            'home_address'         => $post['home_address'] ?? null,
            'kyc_city'             => $post['kyc_city'] ?? null,
            'kyc_state'            => $post['kyc_state'] ?? null,
            'kyc_zip'              => $post['kyc_zip'] ?? null,
            'kyc_country'          => $to_int_or_null($post['kyc_country'] ?? null),
            'ownership_percentage' => $post['ownership_percentage'] ?? null,
            'title_role'           => $post['title_role'] ?? null,

            // Banking
            'bank_name'          => $post['bank_name'] ?? null,
            'bank_account'       => $post['bank_account'] ?? null,
            'routing_swift_iban' => $post['routing_swift_iban'] ?? null,
            'account_holder'     => $post['account_holder'] ?? null,
            'account_type'       => $post['account_type'] ?? null,
            'bank_location'      => $post['bank_location'] ?? null,
            'bank_address'       => $post['bank_address'] ?? null,

            // Business (KYB)
            'legal_name'            => $post['legal_name'] ?? null,
            'dba'                   => $post['dba'] ?? null,
            'business_address'      => $post['business_address'] ?? null,
            'business_city'         => $post['business_city'] ?? null,
            'business_state'        => $post['business_state'] ?? null,
            'business_zip'          => $post['business_zip'] ?? null,
            'business_country'      => $to_int_or_null($post['business_country'] ?? null),
            'business_phone'        => $post['business_phone'] ?? null,
            'website_url'           => $post['website_url'] ?? null,
            'business_type'         => $post['business_type'] ?? null,
            'tin_ein'               => $post['tin_ein'] ?? null,
            'incorporation_country' => $to_int_or_null($post['incorporation_country'] ?? null),
            'incorporation_date'    => $post['incorporation_date'] ?? null,
            'registration_number'   => $post['registration_number'] ?? null,
            'ownership_structure'   => $post['ownership_structure'] ?? null,
            'driving_license'       => $post['driving_license'] ?? null,
            'state_of_license'      => $post['state_of_license'] ?? null,

            // Uploads
            'business_license'        => $uploaded['business_license'],
            'articles_incorporation'  => $uploaded['articles_incorporation'],
            'corporate_documentation' => $uploaded['corporate_documentation'],
            'voided_check'            => $uploaded['voided_check'],

            'status'         => 'Pending',  
            'internal_notes' => $post['internal_notes'] ?? null,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        $ok_app = $this->db->insert(db_prefix().'agent_applications', $app_data);
        $app_id = $this->db->insert_id();

        if (!$ok_app || !$app_id) {
            $this->db->trans_rollback();
            show_error('Agent created in tblstaff but failed to save application.');
        }

        /**
         * 5) COMMIT TRANSACTION
         */
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            show_error('Transaction failed. Nothing was saved.');
        }

        $this->db->trans_commit();

        /**
         * 6) THANK YOU PAGE
         */
        $data = [
            'agent_id' => $agent_id,
            'password' => $plain_password,
            'ref_url'  => site_url('pre_application/create?rid=' . $agent_id),
        ];

        $this->load->view('agent/thank_you', $data);
    }
}
