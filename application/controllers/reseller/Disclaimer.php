<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Disclaimer extends AdminController

{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = _l('Disclaimer Page');
        $data['disclaimer_text'] = 'This is a test disclaimer page.';

        // Load the view (must match application/views/admin/disclaimer.php)
        $this->load->view('reseller/disclaimer', $data);
    }
}
