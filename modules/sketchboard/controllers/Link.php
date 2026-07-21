<?php

defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

class Link  extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sketchboard_model');
    }


    public function view($id){


        $CI = &get_instance();
        $CI->db->where('hash', $id);
        $data['board'] = $CI->db->get(db_prefix() . 'sketchboard')->row();
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['id'] = $data['board']->id;
            unset($post_data['editor-current-shape']);
            $this->sketchboard_model->update($post_data); 
            redirect($this->uri->uri_string());
        }

        if(!empty($data['board'])){
            $data['is_editable'] = $data['board']->is_public_edit;
            $this->load->view('site/view', $data);
        }else{
            show_404();
        }

    }
   
}
