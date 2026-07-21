<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Include the App_pdf base class
require_once APPPATH . 'libraries/pdf/App_pdf.php';

class Pipeline_pdf extends App_pdf
{
    protected $reseller;

    public function __construct($reseller)
    {
        $this->reseller = $reseller;
        parent::__construct();
    }

    protected function file_path()
    {
        return APPPATH . 'views/admin/pipeline_report/view_pdf.php';
    }

    public function prepare()
    {
        $this->set_view_vars([
            'reseller' => $this->reseller
        ]);
    }

    protected function type()
    {
        return 'pipeline_report';
    }
}
