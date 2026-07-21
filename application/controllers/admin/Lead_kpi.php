<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_kpi extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lead_kpi_model');
    }

	public function index()
    {
        if (!has_permission('leads', '', 'view')) {
            access_denied('leads');
        }

        $data['from'] = $this->input->get('from') ?: date('Y-m-d', strtotime('monday this week'));
        $data['to']   = $this->input->get('to')   ?: date('Y-m-d', strtotime('sunday this week'));

        // Make date range available to widgets via global var
        $this->load->vars([
            'lead_kpi_from' => $data['from'],
            'lead_kpi_to'   => $data['to'],
        ]);

        $data['title'] = 'Lead KPI Dashboard';
        $this->load->view('admin/lead_kpi/dashboard', $data);
    }
    // Optional: JSON endpoint for external dashboards
    public function api()
    {
        if (!has_permission('leads', '', 'view')) {
            access_denied('leads');
        }

        $from = $this->input->get('from') ?: date('Y-m-d', strtotime('monday this week'));
        $to   = $this->input->get('to')   ?: date('Y-m-d', strtotime('sunday this week'));

        $report = $this->lead_kpi_model->weekly_report($from, $to);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'from' => $from,
                'to' => $to,
                'kpis' => $report['kpis'],
                'breakdowns' => $report['breakdowns'],
            ]));
    }
}
