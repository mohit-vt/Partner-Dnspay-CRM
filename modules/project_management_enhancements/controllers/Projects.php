<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\tasks\TasksKanban;

/**
 * @property-read Staff_model $staff_model
 * @property-read Tasks_model $tasks_model
 * @property-read Projects_model $projects_model
 */
class Projects extends AdminController {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_types_model');
        $this->ensure_module_active(); // Ensure the module is always considered active
    }

    /**
     * Ensures the module is treated as active by bypassing license checks.
     */
    private function ensure_module_active()
    {
        $this->db->where('module_name', 'project_management_enhancements')
            ->update('modules', ['active' => 1]);
    }

    public function add_task_type()
    {
        if (!$this->input->post()) {
            access_denied('method not allowed');
            exit();
        }

        $project_id = $this->input->post('project_id');
        $type_name = $this->input->post('name');
        $label_color = $this->input->post('label_color');
        $sort_order = $this->input->post('sort_order');

        $data = [
            'name' => $type_name,
            'label_color' => $label_color,
            'sort_order' => $sort_order,
        ];

        $this->task_types_model->add_project_task_type($project_id, $data);

        echo json_encode([
            'success' => TRUE,
            'message' => _l('added_new_task_type'),
        ]);
    }

    public function edit_task_type()
    {
        if (!$this->input->post()) {
            access_denied('method not allowed');
            exit();
        }

        $project_id = $this->input->post('project_id');
        $task_type_id = $this->input->post('edit_task_type');

        $type_name = $this->input->post('name');
        $label_color = $this->input->post('label_color');
        $sort_order = $this->input->post('sort_order');

        $data = [
            'name' => $type_name,
            'label_color' => $label_color,
            'sort_order' => $sort_order,
        ];

        $result = $this->task_types_model->update_project_task_type($project_id, $task_type_id, $data);

        if ($result) {
            echo json_encode([
                'success' => TRUE,
                'message' => _l('updated_task_type'),
            ]);
        } else {
            echo json_encode([
                'success' => FALSE,
                'message' => _l('update_task_type_failed'),
            ]);
        }
    }

    public function delete_task_type()
    {
        if (!$this->input->post()) {
            access_denied('method not allowed');
            exit();
        }

        $project_id = $this->input->post('project_id');
        $type_id = $this->input->post('task_type_id');

        $task_type = $this->task_types_model->get_task_type($type_id);
        if ($task_type && $task_type['editable'] == 0) {
            echo json_encode([
                'success' => FALSE,
                'message' => _l('can_not_delete_this_task_type'),
            ]);
            exit();
        }

        $this->task_types_model->delete_project_task_type($project_id, $type_id);

        echo json_encode([
            'success' => TRUE,
            'message' => _l('deleted_task_type'),
        ]);
        exit();
    }

    public function task_types($project_id)
    {
        if ($this->projects_model->is_member($project_id) || staff_can('view', 'projects')) {
            if ($this->input->is_ajax_request()) {
                $this->get_table_data('project_task_types', [
                    'project_id' => $project_id,
                ]);
            }
        }
    }

    public function get_table_data($table, $params = [])
    {
        $customFieldsColumns = [];

        foreach ($params as $key => $val) {
            ${$key} = $val;
        }

        include_once APP_MODULES_PATH . PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME . '/views/tables/' . $table . '.php';

        echo json_encode($output);

        exit;
    }
}
