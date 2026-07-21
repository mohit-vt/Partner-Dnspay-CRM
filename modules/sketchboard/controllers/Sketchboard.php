<?php

defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

class Sketchboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sketchboard_model');
        $this->load->model('staff_model');
        $this->load->model('settings_model');
        

    }
     /**
     * Display the list of sketchboards.
     *
     * @return void
     */
    public function index(){
        $data['title'] = _l('sketchboard_list');
        $data['records'] = $this->sketchboard_model->all();
        $data['staff']   = $this->staff_model->get('', ['active' => 1]);
        $data['projects']    = $this->sketchboard_model->get_projects();
        $this->load->view('index',$data);

    }
     /**
     * Save a new sketchboard.
     *
     * Handles AJAX requests to save a new sketchboard.
     *
     * @return void
     */
    public function save_sketchboard()
    {
        if(!has_permission('sketchboard', get_staff_user_id(), 'create')){
            access_denied('sketchboard');
        }
        if ($this->input->is_ajax_request()) {


            
            $data = $this->input->post();
            if(get_option('sketchboard_purchase_is_valid') != 1){
                echo json_encode([
                    'success' => false,
                    'message' => _l('Please verify your purchase item token before proceeding.')
                ]);
                return;
            }
      

            if (empty($data['name'])) {
                echo json_encode([
                    'success' => false,
                    'message' => _l('The title field is required')
                ]);
                return;
            }

            $data['date_created'] = date('Y-m-d H:i:s');
            $data['date_updated'] = date('Y-m-d H:i:s');
            $data['hash'] = app_generate_hash();
            $data['sketch_data'] = '';
            $data['created_by'] = get_staff_user_id();
            $insert_id = $this->sketchboard_model->add($data);
            if ($insert_id) {
                echo json_encode([
                    'success' => true,
                    'insert_id'=>$insert_id,
                    'message' => _l('Sketchboard created successfully')
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => _l('An error occurred while creating the sketchboard')
                ]);
            }
        }
    }
    /**
     * View and update a specific sketchboard.
     *
     * @param int $id The ID of the sketchboard to view/update.
     * @return void
     */
    public function view($id){

        if(!has_permission('sketchboard', get_staff_user_id(), 'view')){
            access_denied('sketchboard');
        }

        if ($this->input->post()) {
            if(!has_permission('sketchboard', get_staff_user_id(), 'edit')){
                access_denied('sketchboard');
            }
    
            $post_data = $this->input->post();
            $post_data['id'] = $id;
            unset($post_data['editor-current-shape']);
            $success = $this->sketchboard_model->update($post_data);
            if ($success > 0) {
                set_alert('success', _l('Sketchboard updated successfully'));
                redirect(admin_url('sketchboard'));
            }else{
                set_alert('success', _l('An error occurred while updating the sketchboard'));
            }
            
        }
        $data['title'] = _l('sketchboard_list');
        $data['board'] = $this->sketchboard_model->get($id);
        $data['is_editable'] = has_permission('sketchboard', get_staff_user_id(), 'edit') == '' ? 0:1;
        
     
        if(!empty($data['board'])){
            $this->load->view('view',$data);
        }else{
            set_alert('danger', _l('Sketchboard not found'));
            redirect(admin_url('sketchboard'));
        }

    }
    /**
     * Update a sketchboard.
     *
     * Handles the update of a sketchboard.
     *
     * @return void
     */
    public function update_sketchboard()
    {

        if(!has_permission('sketchboard', get_staff_user_id(), 'edit')){
            access_denied('sketchboard');
        }




        $post_data = $this->input->post();

        if (empty($post_data['name'])) {
            set_alert('danger', _l('The title field is required'));
            redirect(admin_url('sketchboard'));
        }
    
        $success = $this->sketchboard_model->update($post_data);
        
        if ($success > 0) {
            set_alert('success', _l('Sketchboard updated successfully'));
            redirect(admin_url('sketchboard'));
        } else {
           
            set_alert('danger', _l('An error occurred while updating the sketchboard'));
            redirect(admin_url('sketchboard'));
        }
    }
    /**
     * Delete a sketchboard.
     *
     * @param int $id The ID of the sketchboard to delete.
     * @return void
     */
    public function delete($id)
    {
        if(!has_permission('sketchboard', get_staff_user_id(), 'delete')){
            access_denied('sketchboard');
        }
        if (isset($id) && is_numeric($id)) {
            $success = $this->sketchboard_model->delete($id);

            if ($success) {
                set_alert('success', _l('Sketchboard deleted successfully'));
            } else {
                set_alert('danger', _l('An error occurred while deleting the sketchboard'));
            }
        } else {
            set_alert('danger', _l('Invalid sketchboard ID'));
        }
        redirect(admin_url('sketchboard'));
    }  

    public function verify(){

        if ($this->input->post()) {
            $post_data = $this->input->post();
            $check = $this->verify_item($post_data['settings']['sketchboard_purchase_code']);
            if($check){
                $post_data['settings']['sketchboard_purchase_is_valid'] = 1;
                $success = $this->settings_model->update($post_data);
                if ($success > 0) {
                    set_alert('success', _l('Purchase code successfully verified.'));
                    redirect(admin_url('sketchboard/verify'));
                }
            }else{
                set_alert('danger', 'Purchase code verification failed.');
                redirect(admin_url('sketchboard/verify'));
            }           
        }
        $data['title'] = _l('sketchboard_setting');
        $this->load->view('manage',$data);
    }

    public function sketchboard_table()
    {
       
        if (!has_permission('sketchboard', '', 'view')) {
            access_denied('sketchboard');
        }

        $this->app->get_table_data(module_views_path('sketchboard', 'sketchboard_table'));
    }
    public function verify_item($p_code){
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => 'verify.hopperstack.com?purchase_code='.$p_code.'&url='.site_url().'&item='.SKETCHBOARD_ITEM_ID,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    
    }

}
