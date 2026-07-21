<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Task_types_model extends App_Model {
	public function get_project_task_types($project_id)
	{
		$this->db->select('task_types.*, project_task_types.id as project_task_type_id, project_task_types.project_id');
		$this->db->join(db_prefix().'task_types', db_prefix().'task_types.id='.db_prefix().'project_task_types.task_type_id');
		$this->db->where('project_task_types.project_id', $project_id);

		$this->db->order_by('sort_order', 'desc');

		return $this->db->get(db_prefix().'project_task_types')->result_array();
	}

	public function get_task_type($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);

		return $this->db->get(db_prefix().'task_types')->row_array();
	}

	public function get_task_type_by_task_type_id($project_id, $task_type_id)
	{
		$this->db->select('task_types.*');
		$this->db->where('project_id', $project_id);
		$this->db->where('task_type_id', $task_type_id);
		$this->db->join(db_prefix() . 'task_types', db_prefix() . 'task_types.id = ' . db_prefix() . 'project_task_types.task_type_id');

		return $this->db->get(db_prefix().'project_task_types')->row_array();
	}


	public function add_project_task_type($project_id, $data)
	{
		$this->db->insert(db_prefix().'task_types', $data);
		$task_type_id = $this->db->insert_id();

		$data = [
			'project_id' => $project_id,
			'task_type_id' => $task_type_id,
		];
		$this->db->insert(db_prefix().'project_task_types', $data);

		return $this->db->insert_id();
	}

	/**
	 * @param $project_id
	 * @param $task_type_id
	 * @param $data
	 * @return bool
	 */
	public function update_project_task_type($project_id, $task_type_id, $data)
	{
		$this->db->select('task_type_id');
		$this->db->where('project_id', $project_id);
		$this->db->where('task_type_id', $task_type_id);

		$result = $this->db->get(db_prefix().'project_task_types')->row_array();

		if ( ! empty($result) && $result['task_type_id'] == $task_type_id)
		{
			$this->db->where('id', $task_type_id);
			$this->db->update(db_prefix().'task_types', $data);

			return TRUE;
		}

		return FALSE;

	}

	public function delete_project_task_type($project_id, $project_task_type_id)
	{
		$this->db->where('project_id', $project_id);
		$this->db->where('task_type_id', $project_task_type_id);

		return $this->db->delete(db_prefix().'project_task_types');
	}
}