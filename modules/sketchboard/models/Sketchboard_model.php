<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sketchboard_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Retrieve sketchboard data.
     *
     * @param int|string $id Optional ID of the sketchboard.
     * @return array|object Returns all records if no ID is provided, otherwise returns a single record.
     */
    public function get($id = ''){
        if($id == ''){
            return  $this->db->get(db_prefix().'sketchboard')->result_array();
        }else{
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'sketchboard')->row();
        }
    }
     /**
     * Add a new sketchboard record.
     *
     * @param array $data Array of sketchboard data.
     * @return int The ID of the newly inserted sketchboard.
     */
    public function add($data){
        $this->db->insert(db_prefix() . 'sketchboard', $data);
        return $this->db->insert_id();
    }
    /**
     * Update an existing sketchboard record.
     *
     * @param array $data Array of sketchboard data, including ID for the record to be updated.
     * @return bool|int Returns the number of affected rows or false if no ID is provided.
     */
   
    public function update($data)
    {
        $id = isset($data['id']) ? $data['id'] : null;

        if ($id) {
            unset($data['id']);
            $data['date_updated'] = date('Y-m-d H:i:s');

            // Check if sketch_data is an array and encode it to JSON
            if (isset($data['sketch_data']) && is_array($data['sketch_data'])) {
                $data['sketch_data'] = json_encode($data['sketch_data']);
            }

            // Check if hash is empty and update it with a value if empty
            $this->db->where('id', $id);
            $record = $this->db->get(db_prefix() . 'sketchboard')->row();
            
            if (empty($record->hash)) {
                $data['hash'] = app_generate_hash(); // Generate a unique hash
            }

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'sketchboard', $data);

            return $this->db->affected_rows();
        }

        return false;
    }
     /**
     * Retrieve all sketchboards created by the current staff member.
     *
     * @return array Returns an array of sketchboards created by the current staff member.
     */
    public function all(){
        $CI = &get_instance();
        $CI->db->from(db_prefix() . 'sketchboard');
        $CI->db->where('created_by', get_staff_user_id());
        $query = $CI->db->get();
        return $query->result_array();

    }
    /**
     * Delete a sketchboard record by ID.
     *
     * @param int $id ID of the sketchboard to be deleted.
     * @return bool Returns true if the record was deleted successfully, otherwise false.
     */
    public function delete($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'sketchboard');
            return ($this->db->affected_rows() > 0);
        }
        return false;
    }

     /**
     * Get Projects
     * @param  mixed project (Optional)
     * @return mixed     object or array
     */
    public function get_projects()
    {
        return $this->db->get(db_prefix() . 'projects')->result_array();
    }
}