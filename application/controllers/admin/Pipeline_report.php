<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_report extends AdminController {
    public function __construct() {
        parent::__construct();
        $this->load->model('pre_application_model');
        $this->load->model('pipeline_report_model');
        $this->load->model('leads_model');
        $this->load->library('upload');
      
        $staff_id = get_staff_user_id();
        $staff = $this->staff_model->get($staff_id);
        $role = $this->roles_model->get($staff->role);

        $role_name = strtolower($role->name ?? '');

        // Hard block: Agents cannot access any admin pipeline_report page
        if ($role_name === 'agent') {
            show_error('❌ Access Denied: Agents cannot access admin pipeline report.', 403, 'Forbidden');
            exit;
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
    $this->load->model('pipeline_report_model');

    $data['title'] = 'Pipeline Report';

      	$this->load->model('staff_model');
        $this->load->model('roles_model');

        $staff_id = get_staff_user_id();
        $staff = $this->staff_model->get($staff_id);

        $role = $this->roles_model->get($staff->role); // $staff->role contains role ID
        $role_name = $role->name; // now you have the role name

        if (is_admin() || $role_name === 'partner admin' || $role_name === 'Management') {

        $pre_application = array_map(function($item) {
            $item['record_type'] = 'Merchant';
            return $item;
        }, $this->pre_application_model->get_all_pre_application());

                   $seen = [];
        foreach ($pre_application as &$app) {
            $key = strtolower(trim($app['email_address'])) . '|' . trim($app['contact_phone']) . '|' . strtolower(trim($app['pipeline_report_bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
                $app['duplicate_key'] = $key;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
                $app['duplicate_key'] = null;
            }
        }
          
                    $staff_all = $this->db->select('staffid, firstname, lastname')
    ->from(db_prefix().'staff')
    ->get()->result_array();

$data['agent_map'] = array_column(
    array_map(fn($s)=>[$s['staffid'],$s['firstname'].' '.$s['lastname']], $staff_all),
    1, 0
);
//       $staff_all = $this->db->select('staffid, firstname, lastname')
//                       ->from(db_prefix() . 'staff')
//                       ->get()->result_array();

// $agent_map = [];
// foreach ($staff_all as $s) {
//     $agent_map[$s['staffid']] = $s['firstname'] . ' ' . $s['lastname'];
// }

$data['agent_map'] = $agent_map;

        $data['combined_list'] = array_merge($pre_application);
        $data['pre_application_merchant_status'] = true;
        $data['pipeline_user_status'] = true;

    } else {
            $user_id = get_staff_user_id();

            // Step 1: Get logged-in user's pipeline report entries
            $users = $this->pipeline_report_model->get_pipeline_report_by_user($user_id);

            // Step 2: Check for duplicates **only in user's own data**
          	$seen = [];
            foreach ($users as &$app) {
             $key = strtolower(trim($app['final_email'])) . '|' . trim($app['final_phone']) . '|' . strtolower(trim($app['final_bank_name']));
            if (isset($seen[$key])) {
                $app['is_duplicate'] = true;
                $app['duplicate_key'] = $key;
            } else {
                $seen[$key] = true;
                $app['is_duplicate'] = false;
                $app['duplicate_key'] = null;
            }
        
            }

            // Step 3: Add record type (optional, for UI differentiation)
            $users = array_map(function($item) {
                $item['record_type'] = 'Merchant';
                return $item;
            }, $users);

            // Step 5: Merge
            $data['combined_list'] = $users;
            $data['pre_application_merchant_status'] = false;
            $data['pipeline_user_status'] = false;
       
        
        }
  
  		$staff_agents = $this->staff_model->get_agents();
        $agent_map = [];
            foreach ($staff_agents as $agent) {
                $agent_map[$agent->staffid] = $agent->firstname . ' ' . $agent->lastname;
            }

        $data['agent_map'] = $agent_map;
  		
  		    foreach ($data['combined_list'] as &$item) {
        if (isset($item['pipeline_report_id'])) {
            $item['application_source'] = 'Application';
        } elseif (isset($item['merchant_status_final']) && strcasecmp($item['merchant_status_final'], 'PreAPP') === 0) {
            $item['application_source'] = 'Pre - Application';
        } else {
            $item['application_source'] = 'Other'; // Placeholder for future sources
        }
    }
      // Ensure the social_media field exists
    if (!isset($item['social_media'])) {
        $item['social_media'] = '';
    }

     // ✅ Social media (ensure fields exist even if not present)
        $social_fields = [
            'social_x', 'social_instagram', 'social_tiktok',
            'social_youtube', 'social_linkedin', 'social_facebook', 'social_other'
        ];
        foreach ($social_fields as $sf) {
            if (!isset($item[$sf])) {
                $item[$sf] = ''; // default empty
            }
        }
        $this->load->view('admin/pipeline_report/list', $data);
    }


    public function activity_log($id)
    {
        $this->load->model('pipeline_report_model');
        $agent_db = $this->load->database('agent_crm', true);
        $agent_db->where('pipeline_report_id', $id);
        $agent_db->order_by('updated_at', 'DESC');
        $data['logs'] = $agent_db->get('pipeline_activity_log')->result_array();
        $data['title'] = 'Activity Log';
        
        $this->load->view('admin/pipeline_report/activity_log', $data);
    }
  
  
  public function update_cancel_status()
{
    if ($this->input->is_ajax_request()) {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        if (!$id || !$status) {
            echo json_encode(['success' => false]);
            return;
        }
       $agent_crm_db = $this->load->database('agent_crm', TRUE);


      $agent_crm_db->where('id', $id);
	  $updated = $agent_crm_db->update('pipeline_report', ['status' => $status]);


        echo json_encode(['success' => $updated]);
        return;
    }
    show_404();
}

  

  
  	public function get_cancel_details() 
{
    if ($this->input->is_ajax_request()) {
        $id = $this->input->post('id');

        // Load the agent CRM DB
        $agent_crm_db = $this->load->database('agent_crm', TRUE);

        // Get the cancel record from agent_crm
        $record = $agent_crm_db
            ->select('cancel_reason, cancelled_at, cancelled_by')
            ->from('pipeline_report')
            ->where('id', $id)
            ->get()
            ->row();

        if ($record) {
            // Now get staff info from default DB
            $this->db->select('firstname, lastname')
                     ->from(db_prefix() . 'staff')
                     ->where('staffid', $record->cancelled_by);
            $staff = $this->db->get()->row();

            $cancelled_by_name = $staff ? trim($staff->firstname . ' ' . $staff->lastname) : 'N/A';

            echo json_encode([
                'success' => true,
                'data' => [
                    'cancel_reason'      => $record->cancel_reason,
                    'cancelled_at'       => $record->cancelled_at,
                    'cancelled_by_name'  => $cancelled_by_name
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No record found']);
        }
    }
}

public function cancel_record()
{
    if ($this->input->is_ajax_request()) {
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');

        if (empty($id) || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        try {
            $agent_db = $this->load->database('agent_crm', TRUE);

            $update_data = [
                'cancelled_by'  => get_staff_user_id(),
                'cancelled_at'  => date('Y-m-d H:i:s'),
                'status'        => 'Cancelled',
                'cancel_reason' => $reason
            ];

            $agent_db->where('id', $id);
            $updated = $agent_db->update('pipeline_report', $update_data);

            if ($updated) {
                echo json_encode(['success' => true, 'message' => 'Record cancelled successfully']);
            } else {
                throw new Exception('Database update failed.');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

public function copy($id)
{
    $this->load->model('pipeline_report_model');

    // 1. Fetch record to copy
    $old = $this->pipeline_report_model->get_pre_application_by_id($id);

    if (!$old) {
        show_404();
    }

    // 2. Remove primary key / auto increment fields
    unset($old['pipeline_report_id']); // adjust based on your DB PK
    unset($old['id']);                 // if any other primary key exists

    // 3. Generate NEW application_id (same as submit logic)
    $region = strtoupper(trim($old['region'] ?? '')); // old region
    $base_number = 3650;
    $year_suffix = date('y');

    $total_existing = $this->pipeline_report_model->get_pipeline_report_count();

    $serial = $base_number + $total_existing + 1;
    $application_id = $region . $year_suffix . $serial;

    $old['application_id'] = $application_id;
  
  	$this->load->library('user_agent');

    $old['application_ip'] = $this->input->ip_address();
    $old['ip_address']     = $this->input->ip_address();
    $old['user_agent']     = substr((string)$this->input->user_agent(), 0, 200);
    $old['browser_name']   = $this->agent->browser() ?: null;
    $old['browser_version']= $this->agent->version() ?: null;
    $old['platform']       = $this->agent->platform() ?: null;

    $old['device_type'] = $this->agent->is_mobile() ? 'mobile' : ($this->agent->is_robot() ? 'bot' : 'desktop');

    // 4. Update created metadata if needed
    $old['user_id'] = get_staff_user_id();
    $old['application_date'] = date('Y-m-d');
    $old['application_ip'] = $this->input->ip_address();

    // ✅ OPTIONAL: reset statuses if needed
    // $old['merchant_status'] = 'Pending';

    // 5. Insert copied row
    $new_id = $this->pipeline_report_model->add_pipeline_report($old);

    if ($new_id) {
        set_alert('success', 'Application copied successfully (New ID: ' . $application_id . ')');
        redirect(admin_url('pipeline_report'));
    } else {
        set_alert('danger', 'Failed to copy application.');
        redirect(admin_url('pipeline_report'));
    }
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
            redirect(admin_url('pipeline_report'));
        }

        $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
        $data['staff_agents'] = $this->staff_model->get_agents();
		$data['countries'] = $this->db->order_by('short_name', 'ASC')->get('tblcountries')->result_array();
        $data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();

        $data['title'] = 'Create Pipeline Report';
        $this->load->view('admin/pipeline_report/pipeline_report', $data);
    }


    public function view($id = null)
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
    
        $this->load->view('admin/pipeline_report/view', $data);
    }
  
public function download_pdf($id)
{
    $this->load->model('pipeline_report_model');
    
    $data['pipeline_report'] = $this->pipeline_report_model->get_pre_application_by_id($id);
    if (!$data['pipeline_report']) show_404();

    $data['mcc_codes'] = $this->pipeline_report_model->get_all_mcc_codes();
    $data['staff_inhouse'] = $this->staff_model->get_employees_and_management();
    $data['staff_agents'] = $this->staff_model->get_agents();
    $data['countries'] = $this->db->order_by('short_name','ASC')->get('tblcountries')->result_array();

    //$this->load->library('pdf');

    //$html = $this->load->view('admin/pipeline_report/view_pdf', $data, TRUE);

    //$this->pdf->createPDF($html, 'application_'.$id, true); // force download
  
 	$this->load->library('pdf');
	$html = $this->load->view('admin/pipeline_report/view_pdf', $data, TRUE);
	$this->pdf->createPDF($html, 'application_'.$id, true);
  
  	

}
  
// public function archived()
// {
//     $this->load->model('pipeline_report_model');
//     $data['title'] = 'Archived Pipeline Reports';

//     // 🟢 Load the correct database connection for pipeline_report
//     $agent_db = $this->load->database('agent_crm', TRUE); // use 'agent' as configured in application/config/database.php

//     // Fetch archived records only
//     $agent_db->where('is_archived', 1);
//     $data['archived_list'] = $agent_db->get('pipeline_report')->result_array();

//     // Load view
//     $this->load->view('admin/pipeline_report/archived', $data);
// }

  
public function archived() 
{
    $this->load->model('pipeline_report_model');
    $data['title'] = 'Archived Applications';

    // 🟢 Load both databases
    $agent_db = $this->load->database('agent_crm', TRUE); // for pipeline_report
    $main_db  = $this->db; // for pre_application

    // 🟢 1. Fetch archived pipeline_report records
    $agent_db->select('
        id,
        user_id,
        application_id,
        first_name,
        last_name,
        email_address,
        company_name,
        business_phone,
        bank_name,
        created_at,
        "Pipeline" AS source_type
    ');
    $agent_db->where('is_archived', 1);
    $pipeline_archived = $agent_db->get('pipeline_report')->result_array();

    // 🟢 2. Fetch archived pre_application records (FIXED: use $main_db)
    $agent_db->select('
        id,
        user_id,
        first_name,
        last_name,
        personal_email_address AS email_address,
        business_name AS company_name,
        cell_number AS business_phone,
        bank_name,
        created_at,
        "PreApp" AS source_type
    ');
    $agent_db->where('is_archived', 1);
    $preapp_archived = $agent_db->get('pre_application')->result_array(); // ✅ FIXED HERE

    // 🟢 3. Merge both into one array
    $data['archived_list'] = array_merge($pipeline_archived, $preapp_archived);

    // 🟢 4. Sort newest first by created_at (optional)
    usort($data['archived_list'], function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    // 🟢 5. Load view
    $this->load->view('admin/pipeline_report/archived', $data);
}




    public function update($id)
    {
    $this->load->model('pipeline_report_model');
    $this->load->library('upload');

    $existing = $this->pipeline_report_model->get_pre_application_by_id($id);

    if ($this->input->post()) {
        $post_data = $this->input->post(null, true);
        // Normalize commission split fields during update
        $post_data['commission_is_split'] =
            $this->input->post('commission_is_split') == '1' ? 1 : 0;

        $post_data['commission_split_with_anybody'] =
            $this->input->post('commission_split_with_anybody', true) === 'Yes'
                ? 'Yes'
                : 'No';

        $post_data['commission_split_person_name'] = trim(
            (string) $this->input->post(
                'commission_split_person_name',
                true
            )
        );

        if ($post_data['commission_split_with_anybody'] === 'Yes') {
            $post_data['commission_is_split'] = 1;

            if ($post_data['commission_split_person_name'] === '') {
                set_alert(
                    'danger',
                    'Please enter the name of the person receiving the commission split.'
                );

                redirect(admin_url('pipeline_report/update/' . $id));
                return;
            }
        } else {
            $post_data['commission_split_person_name'] = null;
        }
      	//Ends the Normalize commission
        $upload_path = 'uploads/pipeline_docs/';
        $unique_code = str_pad($id, 7, '0', STR_PAD_LEFT);

        // Ensure upload directory exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $single_file_fields = [
            'kyc_kyb_docs', 'business_license',
            'articles_incorporation', 'utility_bill',
            'refund_policy', 'dropbox_link', 'voided_check', 'aml'
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
      $data['rdr'] = $this->input->post('rdr') ?? null;

      // Normalize commission fields during update
      $data['commission_is_split'] =
          $this->input->post('commission_is_split') == '1' ? 1 : 0;

      $data['commission_split_with_anybody'] =
          $this->input->post('commission_split_with_anybody', true) === 'Yes'
              ? 'Yes'
              : 'No';

      $data['commission_split_person_name'] = trim(
          (string) $this->input->post(
              'commission_split_person_name',
              true
          )
      );

      if ($data['commission_split_with_anybody'] === 'Yes') {
          $data['commission_is_split'] = 1;

          if ($data['commission_split_person_name'] === '') {
              set_alert(
                  'danger',
                  'Please enter the name of the person receiving the commission split.'
              );

              redirect(admin_url('pipeline_report/update/' . $id));
              return;
          }
      } else {
          $data['commission_split_person_name'] = null;
      }
      
      
      // Final cleanup before database update
      unset(
          $data[$this->security->get_csrf_token_name()],
          $data['ci_csrf_token'],
          $data['csrf_token'],
          $data['submit_button'],
          $data['remove_files']
      );
      
      $agent_db = $this->load->database('agent_crm', true);
      $allowed_fields = $agent_db->list_fields('pipeline_report');

      $data = array_intersect_key(
          $data,
          array_flip($allowed_fields)
      );

      $updated = $this->pipeline_report_model
          ->update_pre_application($id, $data);

      if ($updated) {
          set_alert(
              'success',
              'Application updated successfully.'
          );
      } else {
          set_alert(
              'danger',
              'No changes made or update failed.'
          );
      }

      redirect(admin_url('pipeline_report'));
      return;
      }

      // GET request: display edit form
      if (empty($existing)) {
          show_404();
          return;
      }

      $data = [];

      $data['pipeline_report'] = $existing;

      $data['staff_inhouse'] =
          $this->staff_model->get_employees_and_management();

      $data['staff_agents'] =
          $this->staff_model->get_agents();

      $data['countries'] = $this->db
          ->order_by('short_name', 'ASC')
          ->get('tblcountries')
          ->result_array();

      $data['mcc_codes'] =
          $this->pipeline_report_model->get_all_mcc_codes();

      $data['title'] = 'Edit Pipeline Report';

      $this->load->view(
          'admin/pipeline_report/view',
          $data
      );
      }

  
  public function update_social_media()
{
    if (!$this->input->is_ajax_request()) {
        show_error('No direct script access allowed');
    }

    $id = $this->input->post('id');
    $platform = $this->input->post('platform');

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Missing record ID']);
        return;
    }
     $agent_db = $this->load->database('agent_crm', TRUE);


     $agent_db->where('id', $id);
    $success = $agent_db->update('pipeline_report', [
        'social_media' => $platform,
    ]);

    echo json_encode(['success' => $success]);
}

// public function archive_record()
// {
//     $id     = $this->input->post('id');
//     $status = $this->input->post('status'); // 🔹 Add this in your JS call

//     if (!$id) {
//         echo json_encode(['success' => false, 'message' => 'Invalid request']);
//         return;
//     }

//     // 🔹 Agent database
//     $agent_db = $this->load->database('agent_crm', TRUE);

//     // 🔹 Handle PreApp separately
//     if ($status == 'PreApp') {
//         $updated = $this->db->where('id', $id)->update('pre_application', ['is_archived' => 1]);
//     } else {
//         $updated = $agent_db->where('id', $id)->update('pipeline_report', ['is_archived' => 1]);
//     }

//     if ($updated) {
//         echo json_encode(['success' => true, 'message' => 'Record archived successfully']);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Failed to archive record']);
//     }
// }

  public function archive_record()
{
    $id     = $this->input->post('id');
    $status = $this->input->post('status');

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }

    $agent_db = $this->load->database('agent_crm', TRUE);

    try {
        if (strcasecmp($status, 'PreApp') === 0) {
            // ✅ Check if column exists in pre_application
            if (!$agent_db->field_exists('is_archived', 'pre_application')) {
                // add the column dynamically (optional safeguard)
                $this->load->dbforge();
                $fields = [
                    'is_archived' => [
                        'type'       => 'TINYINT',
                        'constraint' => 1,
                        'default'    => 0,
                        'null'       => FALSE,
                    ],
                ];
                $this->dbforge->add_column('pre_application', $fields);
            }

            // ✅ Update PreApp record
            $updated = $agent_db->where('id', $id)->update('pre_application', ['is_archived' => 1]);

        } else {
            // ✅ Update pipeline_report record
            $updated = $agent_db->where('id', $id)->update('pipeline_report', ['is_archived' => 1]);
        }

        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Record archived successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to archive record']);
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}


public function unarchive_record()
{
    $id = $this->input->post('id');
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }

    // 🔹 Use the agent database connection
    $agent_db = $this->load->database('agent_crm', TRUE);

    $agent_db->where('id', $id);
    $updated = $agent_db->update('pipeline_report', ['is_archived' => 0]);

    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Record restored to pipeline']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to restore record']);
    }
}


public function update_status()
{
    header('Content-Type: application/json');

    $id     = $this->input->post('id');
    $type   = $this->input->post('type'); // pipeline / preapp
    $status = trim($this->input->post('status'));

    if (!$id || !$type || !$status) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing ID/type/status']);
        return;
    }

    try {
        $agent_db = $this->load->database('agent_crm', TRUE);

        if ($type === 'pipeline') {
            $agent_db->where('id', $id);
            $updated = $agent_db->update('pipeline_report', ['merchant_status' => $status]);
        } elseif ($type === 'preapp') {
            $agent_db->where('id', $id);
            $updated = $agent_db->update('pre_application', ['merchant_status' => $status]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid type']);
            return;
        }

        echo json_encode(['success' => (bool)$updated]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}


  
  public function delete_record()
{
    if ($this->input->is_ajax_request()) {
        $id     = $this->input->post('id');
        $type   = $this->input->post('type');
        $reason = $this->input->post('reason');

        $current_user = get_staff(get_staff_user_id());
        $this->load->model('roles_model');
        $agent_db = $this->load->database('agent_crm', TRUE);

        if (empty($id) || empty($type)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        try {
            if ($type == 'pipeline') {
                // ✅ Delete from pipeline_report
                $record = $agent_db->where('id', $id)->get('pipeline_report')->row_array();

                if ($record) {
                    $logData = [
                        'pipeline_report_id' => $id,
                        'application_id'     => $record['application_id'] ?? null,
                        'reason'             => $reason,
                        'deleted_at'         => date('Y-m-d H:i:s'),
                        'ip_address'         => $this->input->ip_address(),
                        'deleted_by'         => $current_user->staffid,
                        'deleted_by_name'    => $current_user->firstname . ' ' . $current_user->lastname,
                        'deleted_by_role'    => $this->roles_model->get($current_user->role)->name ?? ''
                    ];

                    $agent_db->insert('pipeline_report_deletions', $logData);
                    $agent_db->where('id', $id)->delete('pipeline_report');
                    echo json_encode(['success' => true, 'message' => 'Pipeline record deleted and logged successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Pipeline record not found.']);
                }

            } elseif ($type == 'PreApp') {
    // Fetch from pre_application table
    $record = $agent_db->where('id', $id)->get('pre_application')->row_array();

    if ($record) {
        // Log it before delete
        $logData = [
            'pipeline_report_id' => 0, // ✅ use 0 (not NULL) to satisfy NOT NULL column
            'application_id'     => $id,
            'reason'             => $reason,
            'deleted_at'         => date('Y-m-d H:i:s'),
            'ip_address'         => $this->input->ip_address(),
            'deleted_by'         => $current_user->staffid,
            'deleted_by_name'    => $current_user->firstname . ' ' . $current_user->lastname,
            'deleted_by_role'    => $this->roles_model->get($current_user->role)->name ?? ''
        ];

        // Insert log into agent DB
        $agent_db->insert('pipeline_report_deletions', $logData);

        // Delete the PreApp record
        $agent_db->where('id', $id)->delete('pre_application');

                    echo json_encode(['success' => true, 'message' => 'Pre-Application deleted and logged successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Pre-Application record not found.']);
                }

            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid record type.']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        show_404();
    }
}

public function deleted_records() 
{
    if (!has_permission('pipeline_report', '', 'view')) {
        access_denied('pipeline_report');
    }

    $agent_db = $this->load->database('agent_crm', true);
    $main_db  = $this->db;

    $per_page = 25;
    $page     = (int)($this->input->get('page') ?? 1);
    $offset   = ($page - 1) * $per_page;

    // ✅ Base query
    $agent_db->from('pipeline_report_deletions');

    // ✅ Search filter
    if ($this->input->get('search')) {
        $search = $this->input->get('search');
        $agent_db->group_start()
                 ->like('reason', $search)
                 ->or_like('pipeline_report_id', $search)
                 ->group_end();
    }

    // ✅ Count before limit
    $count_query = clone $agent_db;
    $total_rows  = $count_query->count_all_results('', false); // don’t reset query

    // ✅ Apply ordering, limit, offset
    $deletions = $agent_db->order_by('deleted_at', 'DESC')
                          ->limit($per_page, $offset)
                          ->get()
                          ->result_array();

    // ✅ Get staff IDs from result set
    $staff_ids = array_unique(array_column($deletions, 'deleted_by'));
    $staff_members = [];
    if (!empty($staff_ids)) {
        $staff_rows = $main_db->where_in('staffid', $staff_ids)
                              ->get('tblstaff')
                              ->result_array();
        $staff_members = array_column($staff_rows, null, 'staffid'); // map by staffid
    }

    // ✅ Attach staff info
    foreach ($deletions as &$deletion) {
        $staff_id = $deletion['deleted_by'];
        $deletion['staff_firstname'] = $staff_members[$staff_id]['firstname'] ?? 'Unknown';
        $deletion['staff_lastname']  = $staff_members[$staff_id]['lastname'] ?? 'User';
        $deletion['staff_email']     = $staff_members[$staff_id]['email'] ?? '';
    }

    // ✅ Pagination array
    $data['pagination'] = [
        'total_rows' => $total_rows,
        'per_page'   => $per_page,
        'base_url'   => admin_url('pipeline_report/deleted_records')
    ];

    $data['deletions'] = $deletions;
    $data['title']     = 'Deleted Pipeline Records';
    $this->load->view('admin/pipeline_report/deleted_records', $data);
}

  
  public function get_pipeline_data()
{
    $this->load->model('pipeline_report_model');
    $agent_db = $this->load->database('agent_crm', true);

    // Only non-deleted
    $agent_db->where('deleted_at IS NULL', null, false);

    if (is_admin()) {
        $result = $agent_db->get('pipeline_report')->result_array();
    } else {
        $user_id = get_staff_user_id();
        $agent_db->where('user_id', $user_id);
        $result = $agent_db->get('pipeline_report')->result_array();
    }

    $data = [];
    foreach ($result as $row) {
        $data[] = array_values($row); // convert assoc → numeric array
    }

    echo json_encode([
        "data" => $data
    ]);
}



    public function view_deleted($id)
    {
        if (!has_permission('pipeline_report', '', 'view')) {
            access_denied('pipeline_report');
        }

        $agent_db = $this->load->database('agent_crm', true);

        // Get deletion log
        $agent_db->where('pipeline_report_id', $id);
        $data['deletion'] = $agent_db->get('pipeline_report_deletions')->row_array();

        // Get the original record (from archive if you have one, or from pipeline_report if soft deleted)
        $agent_db->where('id', $id);
        $data['original_record'] = $agent_db->get('pipeline_report')->row_array();

        $data['title'] = 'Deleted Record Details';
        $this->load->view('admin/pipeline_report/view_deleted', $data);
    }

    public function delete($id)
    {
        $deleted = $this->Pipeline_report_model->delete_pre_application($id);
        if ($deleted) {
            set_alert('success', 'Pipeline Report deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete Pipeline Report.');
        }
        redirect(admin_url('pipeline_report'));
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
            $this->load->library('user_agent');

            $ip = $this->input->ip_address();
            $ua = substr((string)$this->input->user_agent(), 0, 200);

            $browserName    = $this->agent->browser() ?: null;
            $browserVersion = $this->agent->version() ?: null;
            $platform       = $this->agent->platform() ?: null;

            $deviceType = 'desktop';
            if ($this->agent->is_mobile()) $deviceType = 'mobile';
            elseif ($this->agent->is_robot()) $deviceType = 'bot';
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
                'application_ip' => $ip,
                'ip_address'     => $ip,
                'user_agent'     => $ua,
                'browser_name'   => $browserName,
                'browser_version'=> $browserVersion,
                'platform'       => $platform,
                'device_type'    => $deviceType,
                'assigned_to_mst' => filter_var($this->input->post('assigned_to_mst', true), FILTER_SANITIZE_STRING),
                'assigned_to_agent' => filter_var($this->input->post('assigned_to_agent', true), FILTER_SANITIZE_STRING),
                'assigned_to_merchant' => filter_var($this->input->post('assigned_to_merchant', true), FILTER_SANITIZE_STRING),
                'payment_type' => filter_var($this->input->post('payment_type', true), FILTER_SANITIZE_STRING),
                'application_type' => filter_var($this->input->post('application_type', true), FILTER_SANITIZE_STRING),
                'legal_name' => filter_var($this->input->post('legal_name', true), FILTER_SANITIZE_STRING),
                'dba' => filter_var($this->input->post('dba', true), FILTER_SANITIZE_STRING),
                'business_address'=> filter_var($this->input->post('business_address', true), FILTER_SANITIZE_STRING),
                'website_url' => filter_var($this->input->post('website_url', true), FILTER_SANITIZE_URL),
                'business_type' => filter_var($this->input->post('business_type', true), FILTER_SANITIZE_STRING),
                'country_code' => filter_var($this->input->post('country_code', true), FILTER_SANITIZE_STRING),
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
              	'commission_is_split' => $this->input->post(
                'commission_is_split'
            ) == '1' ? 1 : 0,

            'commission_split_with_anybody' => filter_var(
                $this->input->post('commission_split_with_anybody', true),
                FILTER_SANITIZE_STRING
            ),

            'commission_split_person_name' => filter_var(
                $this->input->post('commission_split_person_name', true),
                FILTER_SANITIZE_STRING
            ),
                'risk_category' => filter_var($this->input->post('risk_category', true), FILTER_SANITIZE_STRING),
                'chargeback_percent'   => filter_var($this->input->post('chargeback_percent', true), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'mcc_code' => filter_var($this->input->post('mcc_code', true), FILTER_SANITIZE_STRING),
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
          		'third_party_verification' => filter_var($this->input->post('third_party_verification', true), FILTER_SANITIZE_STRING),
                'third_party_other' => filter_var($this->input->post('third_party_other', true), FILTER_SANITIZE_STRING),
				'bank_address' => filter_var($this->input->post('bank_address', true), FILTER_SANITIZE_STRING),
                'dropbox_link' => filter_var($this->input->post('dropbox_link', true),FILTER_SANITIZE_URL),
            ];
          
 	// Normalize commission fields
$data['commission_is_split'] =
    !empty($data['commission_is_split']) ? 1 : 0;

$data['commission_split_with_anybody'] =
    $data['commission_split_with_anybody'] === 'Yes'
        ? 'Yes'
        : 'No';

if ($data['commission_split_with_anybody'] === 'Yes') {
    $data['commission_is_split'] = 1;

    if (empty(trim($data['commission_split_person_name']))) {
        set_alert(
            'danger',
            'Please enter the name of the person receiving the commission split.'
        );

        redirect(
            $this->input->server('HTTP_REFERER')
                ?: admin_url('pipeline_report/create')
        );

        return;
    }
} else {
    $data['commission_split_person_name'] = null;
}
                        
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
        $files = ['kyc_kyb_docs','aml', 'business_license', 'articles_incorporation', 'utility_bill', 'refund_policy','voided_check'];
        $upload_path = 'uploads/pipeline_docs/';

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

        $config['upload_path']   = 'uploads/pipeline_docs/';
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

        $config['upload_path']   = 'uploads/pipeline_docs/';
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
   
        if (!empty($errors)) {
    foreach ($errors as $error) {
        set_alert(
            'danger',
            is_array($error)
                ? implode('<br>', $error)
                : strip_tags($error)
        );
    }

    redirect(
        $this->input->server('HTTP_REFERER')
            ?: admin_url('pipeline_report/create')
    );

    return;
}

            // Insert Data
            $insert_id = $this->pipeline_report_model->add_pipeline_report($data);

            if ($insert_id) {
   

                set_alert('success', 'Pipeline Report submitted successfully.');
                redirect(admin_url('pipeline_report'));
            } else {
                set_alert('danger', 'Failed to submit Pipeline Report.');
            }

                        
        }

        //$this->load->view('pipeline_report/form', ['pre_application_id' => $data['pre_application_id']]);
      
    }



}
