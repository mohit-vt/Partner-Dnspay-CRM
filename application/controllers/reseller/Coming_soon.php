<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Coming_soon extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($feature = 'Default')
    {
        $data['title'] = ucfirst(str_replace('_', ' ', $feature)); // Convert URL slug to title
     
        if($data['title']=="Pre application"){
            $this->load->view('reseller/pre_application/pre_application');   
        }else if($data['title']=="Pipeline"){
            $this->load->view('reseller/pipeline/pipeline');   
        }else{
            $this->load->view('reseller/coming_soon', $data);
        }
       
    }
}
