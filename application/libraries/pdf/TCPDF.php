<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once __DIR__ . '/tcpdf/tcpdf.php'; // Path to the TCPDF main file

class TCPDF_LIB extends TCPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
