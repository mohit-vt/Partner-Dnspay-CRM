<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Remove this line (causing the error)
// require_once APPPATH.'../vendor/autoload.php';

// Include TCPDF directly
require_once(APPPATH.'third_party/tcpdf/tcpdf.php'); // Adjust path if TCPDF is in a different folder

class Pdf extends TCPDF
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createPDF($html, $filename = 'document', $download = false)
    {
        $this->SetTitle($filename);
        $this->SetMargins(10, 10, 10);
        $this->AddPage();
        $this->writeHTML($html, true, false, true, false, '');
        if ($download) {
            $this->Output($filename.'.pdf', 'D'); // force download
        } else {
            $this->Output($filename.'.pdf', 'I'); // inline view
        }
    }
}
