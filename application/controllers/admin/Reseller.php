<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reseller extends AdminController {
  
    public function __construct() {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('reseller_model');
        $this->load->library('upload');
      
        
    }

public function index() 
{
    $data['title'] = 'ISO/Reseller Leads';
  	$this->load->model('custom_fields_model');

    // Get current user & role
    $user_id = get_staff_user_id();
    $staff   = $this->staff_model->get($user_id);
    $role    = $this->roles_model->get($staff->role); // fetch role object

    // Get the ID for 'ISO/Reseller' from tblleads_status
    $this->db->where('name', 'ISO/Reseller');
    $status = $this->db->get(db_prefix() . 'leads_status')->row();

    if (!$status) {
        $data['leads'] = [];
    } else {
        $this->db->select('id, agent_id, application_id, name, application_status, company, remote_ip, user_agent, email, phonenumber, dateadded');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('status', $status->id);

        // Check access rights
        if (!(is_admin() || 
             ($role && strtolower($role->name) === 'partner admin') ||
             ($role && strtolower($role->name) === 'relationship management team') ||
     		 ($role && strtolower($role->name) === 'management'))) {
            // Restrict to only leads created by this user
            $this->db->where('user_id', $user_id); 
            // If your schema uses a different field, adjust this (e.g., created_by, assigned)
        }

        $this->db->order_by('dateadded', 'DESC'); 
        $this->db->order_by('id', 'DESC');        

        $query = $this->db->get();
        $data['leads'] = $query->result_array();
      
      // ✅ Load database (if not already)
      $this->load->database();

      // Get all lead IDs we just fetched
      $lead_ids = array_column($data['leads'], 'id');

      $lastnames = [];
      if (!empty($lead_ids)) {
          $this->db->select('v.relid, v.value');
          $this->db->from('tblcustomfieldsvalues v');
          $this->db->join('tblcustomfields f', 'f.id = v.fieldid');
          $this->db->where('f.name', 'Last Name');
          $this->db->where('v.fieldto', 'leads');
          $this->db->where_in('v.relid', $lead_ids);
          $query = $this->db->get();

          foreach ($query->result_array() as $row) {
              $lastnames[$row['relid']] = $row['value'];
          }
      }

      // ✅ Merge last name into leads
      foreach ($data['leads'] as &$lead) {
          $lead_id = $lead['id'];
          $lead['lastname'] = $lastnames[$lead_id] ?? '';
          $lead['full_name'] = trim(($lead['name'] ?? '') . ' ' . ($lead['lastname'] ?? ''));
      }
      unset($lead);


            }

    $this->load->view('admin/reseller/list', $data);
}


public function reseller_list()
{
    $user_id = get_staff_user_id();
    $staff   = $this->staff_model->get($user_id); 
    $role    = $this->roles_model->get($staff->role); 
    $current_user_role = strtolower($role->name ?? '');

    if (is_admin() || 
        ($role && strtolower($role->name) === 'partner admin') || 
        ($role && strtolower($role->name) === 'management')) 
    {
        $data['title'] = 'ISO/Reseller List';

        $lists     = $this->reseller_model->get_all_resellers();
        $resellers = $lists['resellers'];
        $agents    = $lists['agents'];

        // Build agent map
        $agent_map = [];
        foreach ($agents as $agent) {
            $agent_map[$agent['staffid']] = $agent['firstname'] . ' ' . $agent['lastname'];
        }

        // Pass role into closure using "use"
        $merged = array_merge(
            $resellers,
            array_map(function ($a) use ($current_user_role) {
                return [
                    'agent_id'        => $a['agent_id'],
                  	'staffid'       => $a['staffid'],
                    'reseller_id'     => $a['staffid'],
                    'first_name'      => $a['firstname'],
                    'last_name'       => $a['lastname'],
                    'email'           => $a['email'],
                    'created_at'      => $a['datecreated'] ?? date('Y-m-d H:i:s'),
                    'status'          => $a['user_status'] ?? 'Pending',
                    'assigned_to_agent' => $a['staffid'],
                    'is_agent'        => true,
                ];
            }, $agents)
        );

        $data['reseller_lists'] = $merged;
        $data['agent_map']      = $agent_map;
        $data['self_agent']     = null; // Admin/Management view doesn't need the self-info card

    } else {
        $this->load->model('reseller_model');

        // Table stays scoped to only what THIS user actually submitted
        $data['reseller_lists'] = $this->reseller_model->get_resellers_by_user_id($user_id);
        $data['agent_map']      = [];

        // Separate: the logged-in agent's own identity (Agent ID, status, etc.)
        // shown as a standalone info card in the view, NOT merged into the table.
        $data['self_agent'] = $this->db
            ->select('staffid, firstname, lastname, email, agent_id, datecreated, user_status')
            ->where('staffid', $user_id)
            ->get(db_prefix().'staff')
            ->row_array();
    }

    $data['title'] = 'ISO/Reseller List';
    $this->load->view('admin/reseller/reseller_list', $data);
}
  
  public function view_agent_application()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
    }

    $staffid = (int)$this->input->post('staffid');

    if (!$staffid) {
        echo '<p class="text-danger">Invalid agent.</p>';
        return;
    }

    // Get application by staffid
    $this->db->where('staffid', $staffid);
    $app = $this->db->get(db_prefix().'agent_applications')->row();

    if (!$app) {
        echo '<p>No application found for this agent.</p>';
        return;
    }

    $data['app'] = $app;

    // Render a partial view as HTML (we'll create this next)
    $this->load->view('admin/reseller/agent_application_view', $data);
}

  
  
  
 public function login_as($staff_id)
    {
        if (!is_admin()) {
            show_error('Unauthorized', 403);
        }

        $staff = $this->staff_model->get($staff_id);
        if (!$staff) {
            show_error('User not found', 404);
        }

        // Generate secure one-time token
        $token = bin2hex(random_bytes(16));

        // Save token to DB
        $this->db->insert('tbl_impersonation_tokens', [
            'token'      => $token,
            'admin_id'   => get_staff_user_id(),
            'agent_id'   => $staff->staffid,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Redirect in this request to the impersonation entry point.
        // IMPORTANT: If this controller is requested in a new tab (target=_blank),
        // the redirect will happen in that new tab only.
        redirect(site_url('impersonate/start/' . $token));
    }

    public function add_agent_overview() {
        if (!$this->input->is_ajax_request()) show_404();

        $agent_id = $this->input->post('agent_id');
        $overview = $this->input->post('overview');
        $created_by = get_staff_user_id();

        if (!$agent_id || !$overview) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }

        $this->load->model('reseller_model');
        $saved = $this->reseller_model->add_agent_overview($agent_id, $overview, $created_by);

        if ($saved) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save overview']);
        }
    }

    public function get_agent_overviews() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $agent_id = $this->input->post('agent_id'); // use POST instead of GET
        if (!$agent_id) {
            echo json_encode([]);
            return;
        }

        $this->load->model('reseller_model');
        $overviews = $this->reseller_model->get_agent_overviews($agent_id);

        echo json_encode($overviews ?: []);
    }
  
  


    public function reseller_view($id)
    {
        $this->load->model('reseller_model'); // Load model
        $data['reseller'] = $this->reseller_model->get_resellers_by_id($id); // Fetch data by ID
    
        if (!$data['reseller']) {
            show_404(); // Show error if no data found
        }
      	$data['staff_agents'] = $this->staff_model->get_agents();
    
        $this->load->view('admin/reseller/reseller_view', $data);
    }

    public function integrate()
    {
        // if ($this->input->post()) {
        //     $data = $this->input->post();
        //     set_alert('success', 'Reseller created successfully.');
        //     redirect(admin_url('reseller'));
        // }

        $data['title'] = 'ISO/Reseller';
        $this->load->view('admin/reseller/integrate', $data);
    }

    public function create($id = null)
    
    {
        $this->load->model('custom_fields_model');

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
            set_alert('success', 'Reseller created successfully.');
            redirect(admin_url('reseller'));
        }
      
      	$data['staff_agents'] = $this->staff_model->get_agents();

        $data['title'] = $id ? 'Create Reseller ' . $data['lead']['name'] : 'Create Reseller';

        $this->load->view('admin/reseller/reseller', $data);
    }


    // public function create($id)
    // {
    //     $this->load->model('custom_fields_model');

    //     $lead = $this->leads_model->get($id);

    //     if (!$lead) {
    //         show_404();
    //     }
    
    //     $data['lead'] = (array) $lead;
    
    //     $this->load->database(); // Ensure DB loaded
    //     $this->db->select('f.name, v.value');
    //     $this->db->from('tblcustomfieldsvalues v');
    //     $this->db->join('tblcustomfields f', 'f.id = v.fieldid');
    //     $this->db->where('v.relid', $id);
    //     $this->db->where('v.fieldto', 'leads');
    //     $query = $this->db->get();
        
    //     $data['custom_fields'] = $query->result_array();

        
    //     if ($this->input->post()) {
    //         $data = $this->input->post();
    //         set_alert('success', 'Reseller created successfully.');
    //         redirect(admin_url('reseller'));
    //     }

    //     $data['title'] = 'Create Reseller ' . $data['lead']->name;

    //     $this->load->view('admin/reseller/reseller', $data);
    // }
  
  
    public function view($id)
    {
        $this->load->model('custom_fields_model');


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

        $data['title'] = 'View Lead: ' . $data['lead']->name;

        $this->load->view('admin/reseller/view', $data);
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
    
        if ($id && $status) {
            $this->db->where('id', $id);
            $this->db->update('tblleads', ['application_status' => $status]);
    
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing ID or status']);
        }
    }



//public function update_resellers_status() {
//     if (!$this->input->is_ajax_request()) {
//         show_404();
//     }

//     $id = (int)$this->input->post('id');
//     $status = $this->input->post('status');

//     $allowed_statuses = ['Pending','Approved', 'Declined','onhold'];
//     if (!$id || !in_array($status, $allowed_statuses)) {
//         echo json_encode(['success' => false, 'message' => 'Invalid input']);
//         http_response_code(400);
//         return;
//     }

//     // Load external DB
//     $agentDb = $this->load->database('agent_crm', TRUE);

//     $agentDb->where('reseller_id', $id);
//     $updated = $agentDb->update('resellers', ['status' => $status]);

//     if ($updated) {
//         echo json_encode(['success' => true]);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'DB update failed']);
//         http_response_code(500);
//     }
// }
    
public function update_resellers_status()
{
    $id     = $this->input->post('id');
    $status = $this->input->post('status');

    if (!$id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }

    // normalize casing
    $status_norm = ucfirst(strtolower($status)); // Pending / Approved / Declined
    if ($status_norm === 'Onhold') $status_norm = 'onhold';

    // 1) Check if this id is an AGENT (staff table)
    $default_db = $this->load->database('default', TRUE);

    $default_db->select('staff.staffid, staff.active, roles.name as role_name')
               ->from(db_prefix().'staff as staff')
               ->join(db_prefix().'roles as roles', 'roles.roleid = staff.role')
               ->where('staff.staffid', $id)
               ->where('roles.name', 'agent');

    $agent = $default_db->get()->row();

    if ($agent) {


      
    $default_db->where('staffid', $id)
           ->update(db_prefix().'staff', ['user_status' => $status]);
  

    // ✅ Mirror staff.active
    // Approved stays inactive so they go through reset first login
    $new_active = 1;

    $default_db->where('staffid', $id)
               ->update(db_prefix().'staff', [
                   'active' => $new_active
               ]);

    echo json_encode(['success' => true, 'message' => 'Agent status updated']);
    return;
}


    // 2) Otherwise it’s a reseller
    $this->db->where('reseller_id', $id);
    $ok = $this->db->update('resellers', ['status' => $status_norm]);

    echo json_encode([
        'success' => (bool)$ok,
        'message' => $ok ? 'Reseller status updated' : 'Failed to update reseller status'
    ]);
}
  
      

    public function update($id)
    {
    $this->load->model('reseller_model');
    $this->load->library('upload');

    $existing = $this->reseller_model->get_resellers_by_id($id);
    
    if ($this->input->post()) {
        $post_data = $this->input->post(null, true);
        $upload_path = 'uploads/reseller_docs/';
        $unique_code = str_pad($id, 7, '0', STR_PAD_LEFT);

        // Ensure upload directory exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $single_file_fields = ['nda_file', 'reseller_w9', 'iso_agreement_file', 'id_file', 'bank_file'];

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


    $updated = $this->reseller_model->update_resellers($id, $data);

        if ($updated) {
           set_alert('success', 'Reseller Updated Successfully');
        } else {
            set_alert('danger', 'No changes made or update failed.');
        }
   
        
        redirect(admin_url('reseller/reseller_list'));
    }
}
  public function delete_partner()
{
    if (!has_permission('resellers', '', 'delete')) {
        access_denied('resellers');
    }

    if ($this->input->post()) {
        $id     = $this->input->post('id');
        $reason = $this->input->post('reason');

        if (empty($id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid Reseller ID'
            ]);
            die;
        }

        try {
            // Load Agent CRM DB
            $agent_db = $this->load->database('agent_crm', true);

            // Fetch reseller before deleting
            $reseller = $agent_db->where('reseller_id', $id)->get('resellers')->row();
            if (!$reseller) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Reseller not found'
                ]);
                die;
            }

            // Current logged-in staff
            $current_user = $this->staff_model->get(get_staff_user_id());

            // Log deletion
            $logData = [
                'reseller_id'      => $reseller->reseller_id,
                'reseller_code'    => $reseller->reseller_code,
                'application_id'   => $reseller->application_id ?? null,
                'reason'           => $reason,
                'deleted_at'       => date('Y-m-d H:i:s'),
                'ip_address'       => $this->input->ip_address(),
                'deleted_by'       => $current_user->staffid,
                'deleted_by_name'  => $current_user->firstname . ' ' . $current_user->lastname,
                'deleted_by_role'  => $this->roles_model->get($current_user->role)->name
            ];
            $agent_db->insert('reseller_deletions', $logData);

            // Delete reseller
            $success = $agent_db->where('reseller_id', $id)->delete('resellers');

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Reseller deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete reseller');
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

  
public function partner_deletion_record()
{
    if (!has_permission('reseller', '', 'view')) {
        access_denied('reseller');
    }

    // Load agent_crm DB for partner_deletions
    $agent_db = $this->load->database('agent_crm', true);

    // Get deletions
    $deletions = $agent_db->order_by('deleted_at', 'DESC')->get('partner_deletions')->result_array();

    // Attach staff info from main DB (royalsec_crm/mancrm)
    foreach ($deletions as &$del) {
        if (!empty($del['deleted_by'])) {
            $staff = $this->db->select('firstname, lastname')
                              ->where('staffid', $del['deleted_by'])
                              ->get('tblstaff')
                              ->row();
            if ($staff) {
                $del['firstname'] = $staff->firstname;
                $del['lastname']  = $staff->lastname;
            }
        }
    }

    $data['deletions'] = $deletions;
    $data['title'] = 'Partner Deleted Records';
    $this->load->view('admin/reseller/partner_deletion_record', $data);
}

    

public function deleted_records()
{
    if (!has_permission('reseller', '', 'view')) {
        access_denied('reseller');
    }

    // Load agent_crm DB
    $agent_db = $this->load->database('agent_crm', true);

    // Get all deletion logs from agent_crm
    $agent_db->order_by('deleted_at', 'DESC');
    $deletions = $agent_db->get('reseller_deletions')->result_array();

    // Attach staff info from default DB
    foreach ($deletions as &$del) {
        if (!empty($del['deleted_by'])) {
            $staff = $this->db
                ->select('firstname, lastname')
                ->where('staffid', $del['deleted_by'])
                ->get(db_prefix().'staff')
                ->row();

            if ($staff) {
                $del['firstname'] = $staff->firstname;
                $del['lastname']  = $staff->lastname;
            } else {
                $del['firstname'] = 'Unknown';
                $del['lastname']  = '';
            }
        } else {
            $del['firstname'] = 'System';
            $del['lastname']  = '';
        }
    }

    $data['deletions'] = $deletions;
    $data['title']     = 'Deleted Reseller Records';
    $this->load->view('admin/reseller/deleted_records', $data);
}

public function delete()
{
    if ($this->input->method() !== 'post') {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }

    $id     = $this->input->post('id');
    $reason = $this->input->post('reason');

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        return;
    }

    try {
        $lead = $this->db->where('id', $id)->get(db_prefix() . 'leads')->row();

        if (!$lead) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }

        $current_user = $this->staff_model->get(get_staff_user_id());
        $agent_db     = $this->load->database('agent_crm', true);

        // Log deletion
        $logData = [
            'reseller_id'      => $lead->id,
            'reseller_code'    => $lead->agent_id ?? null,
            'application_id'   => $lead->application_id ?? null,
            'reason'           => $reason,
            'deleted_at'       => date('Y-m-d H:i:s'),
            'ip_address'       => $this->input->ip_address(),
            'deleted_by'       => $current_user->staffid,
            'deleted_by_name'  => $current_user->firstname . ' ' . $current_user->lastname,
            'deleted_by_role'  => $this->roles_model->get($current_user->role)->name
        ];

        $agent_db->insert('partner_deletions', $logData);

        // Delete the lead
        $deleted = $this->db->where('id', $id)->delete(db_prefix() . 'leads');

        if ($deleted) {
            echo json_encode(['success' => true, 'message' => 'Record deleted and logged successfully']);
        } else {
            throw new Exception('Failed to delete record');
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function delete_multiple()
    {
        if ($this->input->is_ajax_request()) {
            $ids = $this->input->post('ids');

            if (!empty($ids)) {
                $this->load->model('reseller_model');
                $deleted = 0;

                foreach ($ids as $id) {
                    if ($this->reseller_model->delete($id)) {
                        $deleted++;
                    }
                }

                echo json_encode(['success' => true, 'message' => "$deleted record(s) deleted."]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No IDs provided.']);
            }
        } else {
            show_404();
        }
    }



 public function submit()
    {
   		$bank_name = filter_var($this->input->post('bank_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $errors = [];
        $last_code = $this->reseller_model->get_last_reseller_code(); 
        $last_number = intval(substr($last_code, 3));
        $next_number = max(500, $last_number) + 1; 
        $unique_code = '360' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
        $application_id = $this->reseller_model->generate_application_id($bank_name);
       $data = [
            'reseller_code'    => $unique_code,
            'application_id'   => $application_id,
            'reseller_type'    => filter_var($this->input->post('reseller_type'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'name'             => filter_var($this->input->post('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          	'assigned_to_agent' => filter_var($this->input->post('assigned_to_agent'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'first_name'             => filter_var($this->input->post('first_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'last_name'             => filter_var($this->input->post('last_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'address'          => filter_var($this->input->post('address'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'street'          => filter_var($this->input->post('street'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'city'          => filter_var($this->input->post('city'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'state'          => filter_var($this->input->post('state'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'zip_code'          => filter_var($this->input->post('zip_code'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'country'          => filter_var($this->input->post('country'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'contact_person'     => filter_var($this->input->post('contact_person'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            //'email'            => filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL) ?: null,
         	'email' => filter_var(trim($this->input->post('email')), FILTER_VALIDATE_EMAIL) ?: null,
            'phone'            => filter_var($this->input->post('phone'), FILTER_SANITIZE_NUMBER_INT),
            'payout_method'    => filter_var($this->input->post('payout_method'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'bank_name'        => filter_var($this->input->post('bank_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'routing_number'   => filter_var($this->input->post('routing_number'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'account_number'   => filter_var($this->input->post('account_number'), FILTER_SANITIZE_NUMBER_INT),
            'leads_id'   => filter_var($this->input->post('leads_id'), FILTER_SANITIZE_NUMBER_INT),
            'website'          => filter_var($this->input->post('website'), FILTER_VALIDATE_URL) ?: null,
            'status'          => filter_var($this->input->post('status'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'user_id'           => get_staff_user_id(),

        ];

        // Validate required fields
        if (!$data['email']) {
            $errors['email'] = 'Invalid email format!';
        }
        if (!$data['name']) {
            $errors['name'] = 'Company/Individual Name is required!';
        }
        if (!$data['phone']) {
            $errors['phone'] = 'Cell Phone Number is required!';
        }

        // Handle File Uploads
        $files = ['nda_file', 'reseller_w9', 'iso_agreement_file', 'id_file', 'bank_file'];
        $upload_path = 'uploads/reseller_docs/';

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
      

           

            // If there are validation errors, show messages and stop submission
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    set_alert('danger', $error);
                }
                redirect(admin_url('reseller/form'));
                return;
            }

            // Insert Data
            $insert_id = $this->reseller_model->add_new_reseller($data);

            if ($insert_id) {
                set_alert('success', 'Reseller submitted successfully.');
                redirect(admin_url('reseller/reseller_list'));
            } else {
                set_alert('danger', 'Failed to submit reseller.');
            }
            
        }

        $this->load->view('admin/reseller/form');
    }



}
