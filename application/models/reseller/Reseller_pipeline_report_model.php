<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reseller_pipeline_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // No session or URL helper needed here
    }

    // Get all pipeline records for a reseller
    public function get_all_pipeline($reseller_id)
    {
        return $this->db->get_where('pipeline_report', [
            'user_id' => $reseller_id
        ])->result_array();
    }

    // Get a single pipeline record
    public function get_pipeline_by_id($id, $reseller_id)
    {
        return $this->db->get_where('pipeline_report', [
            'id' => $id,
            'user_id' => $reseller_id
        ])->row_array();
    }
}
