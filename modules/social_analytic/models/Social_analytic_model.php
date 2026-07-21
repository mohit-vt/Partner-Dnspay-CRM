<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * This class describes a Social analytic model.
 */
require 'modules/social_analytic/vendor/autoload.php';

class Social_analytic_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * update general setting
     *
     * @param      array   $data   The data
     *
     * @return     boolean
     */
    public function update_setting($data)
    {
        $affectedRows = 0;
       

        foreach ($data['settings'] as $key => $value) {
            if (update_option($key, $value)) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Add new workspace
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_workspace($data)
    {
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'sa_workspaces', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * update workspace
     * @param array $data
     * @param  integer $id 
     * @return boolean
     */
    public function update_workspace($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'sa_workspaces', $data);
       
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * delete workspace
     * @param integer $id
     * @return boolean
     */
    public function delete_workspace($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sa_workspaces');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function set_default_workspace($id){
        $staffid = get_staff_user_id();
        $this->db->where('staffid', $staffid);
        $this->db->update(db_prefix().'staff', ['base_workspace_id' => $id]);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    
    /**
     * Get workspace
     * @param  mixed $id workspace id (Optional)
     * @return mixed     object or array
     */
    public function get_workspace($id = '', $type = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'sa_workspaces')->row();
        }

        if ($type != '') {
            $this->db->where('type', $type);
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'sa_workspaces')->result_array();
    }

    /**
     * Get customers contacts
     * @param  mixed $customer_id
     * @param  array $where       perform where query
     * @param  array $whereIn     perform whereIn query
     * @return array
     */
    public function get_contacts($customer_id = '', $where = [], $whereIn = [])
    {
        $this->db->where(db_prefix() . 'contacts.active', 1);
        $this->db->where($where);
        if ($customer_id != '') {
            $this->db->where('userid', $customer_id);
        }

        foreach ($whereIn as $key => $values) {
            if (is_string($key) && is_array($values)) {
                $this->db->where_in($key, $values);
            }
        }

        $this->db->order_by('is_primary', 'DESC');
        $this->db->join(db_prefix().'clients', db_prefix().'clients.userid = '.db_prefix() . 'contacts.userid','left');

        return $this->db->get(db_prefix() . 'contacts')->result_array();
    }

    /**
     * Add workspace_member
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_workspace_member($data)
    {
        $data_insert = [];

        $this->db->where('type', $data['type']);
        $this->db->where('workspace_id', $data['workspace_id']);
        $sa_workspace_members = $this->db->get(db_prefix() . 'sa_workspace_members')->result_array();
        $sa_workspace_members_list = [];
        foreach ($sa_workspace_members as $member) {
            $sa_workspace_members_list[] = $member['member_id'];
        }

        foreach ($data['members'] as $member_id) {
            if(in_array($member_id, $sa_workspace_members_list)){
                continue;
            }

            $node = [];
            $node['addedfrom'] = get_staff_user_id();
            $node['dateadded'] = date('Y-m-d H:i:s');
            $node['workspace_id'] = $data['workspace_id'];
            $node['type'] = $data['type'];
            $node['member_id'] = $member_id;

            $data_insert[] = $node;
        }
        
        if(count($data_insert) > 0){
            $affectedRows = $this->db->insert_batch(db_prefix().'sa_workspace_members', $data_insert);
                
            if ($affectedRows > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * delete workspace member
     * @param integer $id
     * @return boolean
     */
    public function delete_workspace_member($id)
    {
        $this->db->where('id', $id);
        $member = $this->db->get(db_prefix() . 'sa_workspace_members')->row();

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sa_workspace_members');
        if ($this->db->affected_rows() > 0) {
            if($member->type == 'staff'){
                $this->db->where('staffid', $member->member_id);
                $this->db->where('base_workspace_id', $member->workspace_id);
                $this->db->update(db_prefix() . 'staff', ['base_workspace_id' => 0]);
            }

            return true;
        }
        return false;
    }

    /**
     * Add new social_accounts
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_social_account2($data)
    {
        if(isset($data['page_ids'])){
            $data_insert = [];
            $pages = json_decode($data['pages'], true);
            $account = json_decode($data['account'], true);
            $page_arr = [];
            foreach ($pages as $key => $value) {
                $page_arr[$value['id']] = $value;
            }


            foreach ($data['page_ids'] as $page_id) {
                $node = [];
                $node['type'] = $data['type'];
                $node['page_id'] = $page_id;
                $node['name'] = $page_arr[$page_id]['name'];
                $node['category'] = $page_arr[$page_id]['category'];
                $node['user_id'] = $account['id'];
                $node['access_token'] = $page_arr[$page_id]['access_token'];
                $node['workspace_id'] = sa_get_base_workspace_id();
                $node['addedfrom'] = get_staff_user_id();
                $node['dateadded'] = date('Y-m-d H:i:s');

                $data_insert[] = $node;
            }

            if (count($data_insert) > 0) {
                $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_accounts', $data_insert);

                if ($affectedRows > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * add social_accounts
     * @param array $data
     * @param  integer $id 
     * @return boolean
     */
    public function add_social_account($data){

        $data['workspace_id'] = sa_get_base_workspace_id();
        $data['active'] = 1;
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix().'sa_accounts', $data);
        
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return true;
        }

        return false;
    }

    /**
     * update social_accounts
     * @param array $data
     * @param  integer $id 
     * @return boolean
     */
    public function update_social_account($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'sa_accounts', $data);
       
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * delete social_accounts
     * @param integer $id
     * @return boolean
     */
    public function delete_social_account($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sa_accounts');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get social_accounts
     * @param  mixed $id social_accounts id (Optional)
     * @param  string $type
     * @return mixed     object or array
     */
    public function get_social_accounts($id = '', $type = '')
    {
        $workspace_id = sa_get_base_workspace_id();
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'sa_accounts')->row();
        }

        $this->db->where('workspace_id', $workspace_id);
        if ($type != '') {
            $this->db->where('type', $type);
        }

            $this->db->where('active', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'sa_accounts')->result_array();
    }


    /**
     * get_data_engagement_vs_total_posts_published
     * @param  array $data_filter
     * @return array
     */
    public function get_data_engagement_vs_total_posts_published($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $comment = [];
        $report = [];
        $like = [];
        $post = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "comment" OR type = "share" OR type = "like" OR type = "post"  OR type = "video"  OR type = "engagement")');
        $this->db->order_by('time', 'asc');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($comment[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $comment[$date] = 0;
                $report[$date] = 0;
                $like[$date] = 0;
                $post[$date] = 0;
            }

            switch ($value['type']) {
                case 'comment':
                    $comment[$date] += $value['value'];
                    break;
                case 'share':
                    $report[$date] += $value['value'];
                    break;
                case 'like':
                    $like[$date] += $value['value'];
                    break;
                case 'post':
                    $post[$date] += $value['value'];
                    break;
                case 'video':
                    $post[$date] += $value['value'];
                    break;
                case 'engagement':
                    if($value['engagement'] == 'like'){
                        $like[$date] += $value['value'];
                    }elseif($value['engagement'] == 'retweet' || $value['engagement'] == 'share'){
                        $report[$date] += $value['value'];
                    }elseif($value['engagement'] == 'comment'){
                        $comment[$date] += $value['value'];
                    }
                    break;
                case 'reaction':
                    $like[$date] += $value['value'];
                    break;
                    
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'comment' => array_values($comment),
            'report' => array_values($report),
            'like' => array_values($like),
            'post' => array_values($post),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * Gets the where report period.
     *
     * @param      string  $field  The field
     *
     * @return     string  The where report period.
     */
    public function get_where_report_period($field = 'date_format(time, \'%Y-%m-%d\')')
    {
        $months_report      = $this->input->post('date_filter');
        
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = '(' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'last_30_days') {
                $custom_date_select = '(' . $field . ' BETWEEN "' . date('Y-m-d', strtotime('today - 30 days')) . '" AND "' . date('Y-m-d') . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = '(' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'last_month') {
                $this_month = date('m') - 1;
                $custom_date_select = '(' . $field . ' BETWEEN "' . date("Y-m-d", strtotime("first day of previous month")) . '" AND "' . date("Y-m-d", strtotime("last day of previous month")) . '")';
            }elseif ($months_report == 'this_quarter') {
                $current_month = date('m');
                  $current_year = date('Y');
                  if($current_month>=1 && $current_month<=3)
                  {
                    $start_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM means end of 31 March
                  }
                  else  if($current_month>=4 && $current_month<=6)
                  {
                    $start_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM means end of 30 June
                  }
                  else  if($current_month>=7 && $current_month<=9)
                  {
                    $start_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM means end of 30 September
                  }
                  else  if($current_month>=10 && $current_month<=12)
                  {
                    $start_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-January-'.($current_year+1)));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                  }
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                $start_date .
                '" AND "' .
                $end_date . '")';

            }elseif ($months_report == 'last_quarter') {
                $current_month = date('m');
                    $current_year = date('Y');

                  if($current_month>=1 && $current_month<=3)
                  {
                    $start_date = date('Y-m-d', strtotime('1-October-'.($current_year-1)));  // timestamp or 1-October Last Year 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // // timestamp or 1-January  12:00:00 AM means end of 31 December Last year
                  } 
                  else if($current_month>=4 && $current_month<=6)
                  {
                    $start_date = date('Y-m-d', strtotime('1-January-'.$current_year));  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM means end of 31 March
                  }
                  else  if($current_month>=7 && $current_month<=9)
                  {
                    $start_date = date('Y-m-d', strtotime('1-April-'.$current_year));  // timestamp or 1-April 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM means end of 30 June
                  }
                  else  if($current_month>=10 && $current_month<=12)
                  {
                    $start_date = date('Y-m-d', strtotime('1-July-'.$current_year));  // timestamp or 1-July 12:00:00 AM
                    $end_date = date('Y-m-d', strtotime('1-October-'.$current_year));  // timestamp or 1-October 12:00:00 AM means end of 30 September
                  }
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                $start_date .
                '" AND "' .
                $end_date . '")';

            }elseif ($months_report == 'this_year') {
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = '(' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = '' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = '(' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            } elseif(!(strpos($months_report, 'year') === false)){
                $year = explode('year_', $months_report);

                $custom_date_select = '(' . $field . ' BETWEEN "' . date($year[1].'-01-01') . '" AND "' . date(($year[1]+1).'-01-01') . '")';
            }else if($months_report == 'all_time'){
                $custom_date_select = '';
            }
        }

        return $custom_date_select;
    }

    /**
     * get_data_daily_post_impression_trend_chart
     * @param  array $data_filter
     * @return array
     */
    public function get_data_daily_post_impression_trend_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }

        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "post" OR ' . db_prefix() . 'sa_analytics.type = "video")');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [];
        $data_date = [];
        $list_invoice = '0';
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = [];
                $data_date[$date]['facebook'] = 0;
                $data_date[$date]['instagram'] = 0;
                $data_date[$date]['tiktok'] = 0;
                $data_date[$date]['twitter'] = 0;
                $data_date[$date]['youtube'] = 0;
            }
            if($value['account_type'] != ''){
                $data_date[$date][$value['account_type']] += $value['value'];
            }
        }

        $facebook = [];
        $instagram = [];
        $tiktok = [];
        $twitter = [];
        $youtube = [];

        foreach($data_date as $key => $value) {
            $facebook[] = $value['facebook'];
            $instagram[] = $value['instagram'];
            $tiktok[] = $value['tiktok'];
            $twitter[] = $value['twitter'];
            $youtube[] = $value['youtube'];
        }

        $data_return = [
            'data' => [
                ['name' => _l('facebook'), 'data' => $facebook],
                ['name' => _l('instagram'), 'data' => $instagram],
                ['name' => _l('tiktok'), 'data' => $tiktok],
                ['name' => _l('twitter'), 'data' => $twitter],
                ['name' => _l('youtube'), 'data' => $youtube],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_audience_growth_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_audience_growth_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        if($data_filter['social'] == 'facebook'){
            $this->db->where(db_prefix().'sa_analytics.type', 'fan');
            $title = _l('sa_fans');
        }elseif($data_filter['social'] == 'instagram'){
            $title = _l('sa_followers');
            $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        }else{
            $title = _l('sa_followers');
            $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        }
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = [];
                $data_date[$date]['facebook'] = 0;
                $data_date[$date]['instagram'] = 0;
                $data_date[$date]['tiktok'] = 0;
                $data_date[$date]['twitter'] = 0;
                $data_date[$date]['youtube'] = 0;
            }
            if($value['account_type'] != ''){
                $data_date[$date][$value['account_type']] += $value['value'];
            }
        }

        $facebook = [];
        $instagram = [];
        $tiktok = [];
        $twitter = [];
        $youtube = [];

        foreach($data_date as $key => $value) {
            $facebook[] = $value['facebook'];
            $instagram[] = $value['instagram'];
            $tiktok[] = $value['tiktok'];
            $twitter[] = $value['twitter'];
            $youtube[] = $value['youtube'];
        }

        $data_return = [
            'data' => [
                ['name' => $title, 'data' => ${$data_filter['social']}],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_published_posts_with_engagement]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_published_posts_with_engagement($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $engagement = [];
        $post = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }

        if($data_filter['social'] == 'twitter'){
            $this->db->where('(type = "post" or type = "engagement")');
        }else{
            $this->db->where('(type = "post" or type = "reaction" or type = "comment" or type = "share")');
        }
        $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($header[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $engagement[$date] = 0;
                $post[$date] = 0;
            }

            switch ($value['type']) {
                case 'engagement':
                    $engagement[$date] += $value['value'];
                    break;
                case 'comment':
                    $engagement[$date] += $value['value'];
                    break;
                case 'share':
                    $engagement[$date] += $value['value'];
                    break;
                case 'reaction':
                    $engagement[$date] += $value['value'];
                    break;
                case 'post':
                    $post[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        foreach ($engagement as $key => $value) {
            if($data_filter['social'] == 'twitter'){
                $engagement[$key] = round($value/2);
            }else{
                $engagement[$key] = round($value/3);
            }
        }

        $data_total = [
            'engagement' => array_values($engagement),
            'post' => array_values($post),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_post_rate]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_post_rate($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $video = [];
        $photo = [];
        $link = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
            $this->db->where('type', 'post');
            $this->db->order_by('time', 'asc');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($video[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $video[$date] = 0;
                $photo[$date] = 0;
                $link[$date] = 0;
            }

            switch ($value['post_type']) {
                case 'video':
                    $video[$date] += $value['value'];
                    break;
                case 'photo':
                    $photo[$date] += $value['value'];
                    break;
                case 'link':
                    $link[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'video' => array_values($video),
            'photo' => array_values($photo),
            'link' => array_values($link),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }


    /**
     * [get_data_post_density_daily]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_post_density_daily($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $total = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
            $this->db->where('type', 'post');
            $this->db->order_by('time', 'asc');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('l', strtotime($value['time']));

            if(!isset($header[$date])){
                $header[$date] = date('l', strtotime($value['time']));
            }

            $total[$date] += $value['value'];
        }

        return ['header' => array_values($header), 'data_total' => array_values($total)];
    }

    /**
     * [get_data_engagement_rate]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_engagement_rate($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $click = [];
        $reaction = [];
        $comment = [];
        $share = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "click" or type = "reaction" or type = "comment" or type = "share")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($click[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $click[$date] = 0;
                $reaction[$date] = 0;
                $comment[$date] = 0;
                $share[$date] = 0;
            }

            switch ($value['type']) {
                case 'click':
                    $click[$date] += $value['value'];
                    break;
                case 'reaction':
                    $reaction[$date] += $value['value'];
                    break;
                case 'comment':
                    $comment[$date] += $value['value'];
                    break;
                case 'share':
                    $share[$date] += $value['value'];
                    break;
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'click' => array_values($click),
            'reaction' => array_values($reaction),
            'comment' => array_values($comment),
            'share' => array_values($share),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_facebook_data]
     * @param  [integer] $id facebook account id
     * @return [boolean]    
     */
    public function get_facebook_data($id){
        $config = sa_get_facebook_config();
        $fb = new \Facebook\Facebook($config);
        $account = $this->social_analytic_model->get_social_accounts($id);

        try {
            $node_default = [
                'addedfrom' => get_staff_user_id(),
                'dateadded' => date('Y-m-d H:i:s'),
                'account_id' => $account->id,
            ];
            $data_insert = [];
            $response = $fb->get('/'.$account->page_id.'?fields=fan_count,engagement,followers_count', $account->access_token);
            $page_info = $response->getDecodedBody();
            $node = $node_default;
            $node['type'] = 'follower';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $page_info['followers_count'];
            $data_insert[] = $node;


            $node = $node_default;
            $node['type'] = 'fan';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $page_info['fan_count'];
            $data_insert[] = $node;

            $response = $fb->get('/'.$account->page_id.'/posts?fields=message,shares,likes.summary(true),comments.summary(true),reactions.summary(true)', $account->access_token);
            $posts = $response->getDecodedBody();
            $totalShares = 0;
            $totalComments = 0;
            $totalLikes = 0;
            $totalReactions = 0;
            $totalPosts = 0;
            foreach ($posts as $post) {
                $totalPosts++;
              if (isset($post['shares']['count'])) {
                $totalShares += $post['shares']['count'];
              }

              if (isset($post['likes']['summary']['total_count'])) {
                $totalLikes += $post['likes']['summary']['total_count'];
              }

              if (isset($post['reactions']['summary']['total_count'])) {
                $totalReactions += $post['reactions']['summary']['total_count'];
              }

              if (isset($post['comments']['summary']['total_count'])) {
                $totalComments += $post['comments']['summary']['total_count'];
              }
            }

            $node = $node_default;
            $node['type'] = 'reaction';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $totalReactions;
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'comment';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $totalComments;
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'like';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $totalLikes;
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'post';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $totalPosts;
            $data_insert[] = $node;

            $response = $fb->get('/'.$account->page_id.'/insights?metric=page_post_engagements,page_actions_post_reactions_like_total,page_fans,page_messages_total_messaging_connections&period=day', $account->access_token);
            $insights = $response->getDecodedBody();
            foreach ($insights as $insight) {
              if (isset($insight['name'])) {
                switch ($insight['name']) {
                    case 'page_post_engagements':
                        $type = 'engagement';
                        break;
                    case 'page_actions_post_reactions_like_total':
                        $type = 'like';
                        break;
                    case 'page_post_engagements':
                        $type = 'engagement';
                        break;
                    case 'page_fans':
                        $type = 'fan';
                        break;
                    case 'post_impressions':
                        $type = 'impression';
                        break;
                    case 'post_reactions_by_type_total':
                        $type = 'like';
                    case 'page_messages_total_messaging_connections':
                        $type = 'message';
                        break;
                    default:
                        // code...
                        break;
                }

                foreach ($insight['values'] as $value) {
                    if($value['value'] > 0){
                        $node = $node_default;
                        $node['type'] = $type;
                        $node['time'] = date('Y-m-d H:i:s', strtotime($value['end_time']));
                        $node['value'] = $value['value'];
                        $data_insert[] = $node;
                    }
                }
                }
            }

            $response = $fb->get('/'.$account->page_id.'/insights?metric=post_impressions,post_clicks,post_reactions_by_type_total&period=lifetime', $account->access_token);
            $insights = $response->getDecodedBody();
            foreach ($insights as $insight) {
              if (isset($insight['name'])) {
                switch ($insight['name']) {
                    case 'post_impressions':
                        $type = 'impression';
                        break;
                    case 'post_reactions_by_type_total':
                        $type = 'reaction';
                    case 'post_clicks':
                        $type = 'click';
                        break;
                    default:
                        // code...
                        break;
                }

                foreach ($insight['values'] as $value) {
                    if($value['value'] > 0){
                        $node = $node_default;
                        $node['type'] = $type;
                        $node['time'] = date('Y-m-d H:i:s');
                        $node['value'] = $value['value'];
                        $data_insert[] = $node;
                    }
                }

               
                }
            }

            $response = $fb->get('/'.$account->page_id.'/insights?metric=post_impressions,post_clicks,post_reactions_by_type_total&period=lifetime', $account->access_token);
            $insights = $response->getDecodedBody();
            foreach ($insights as $insight) {
              if (isset($insight['name'])) {
                switch ($insight['name']) {
                    case 'post_impressions':
                        $type = 'impression';
                        break;
                    case 'post_reactions_by_type_total':
                        $type = 'reaction';
                    case 'post_clicks':
                        $type = 'click';
                        break;
                    default:
                        // code...
                        break;
                }

                foreach ($insight['values'] as $value) {
                    if($value['value'] > 0){
                        $node = $node_default;
                        $node['type'] = $type;
                        $node['time'] = date('Y-m-d H:i:s');
                        $node['value'] = $value['value'];
                        $data_insert[] = $node;
                    }
                }

               
                }
            }

             if (count($data_insert) > 0) {
                $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.date('Y-m-d').'"');
                $this->db->where('account_id', $account->id);
                $this->db->delete(db_prefix() . 'sa_analytics');

                $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_analytics', $data_insert);

                if ($affectedRows > 0) {
                    return true;
                }
            }
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        return true;
    }

    /**
     * [raw_data_update]
     * @param  [array] $data
     * @return [boolean or integer]  
     */
    public function raw_data_update($data){
        if($data['edit_type'] == 'overwrite'){
            $date = date('Y-m-d', strtotime(to_sql_date($data['time'], true)));
            $this->db->where('account_id', $data['id']);
            $this->db->where('type', $data['type']);
            $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.$date.'"');
            $this->db->delete(db_prefix().'sa_analytics');
        }

        $data_insert = [
                'addedfrom' => get_staff_user_id(),
                'dateadded' => date('Y-m-d H:i:s'),
                'time' => to_sql_date($data['time'], true),
                'account_id' => $data['id'],
                'type' => $data['type'],
                'import' => 1,
                'value' => $data['value'],
            ];

        switch ($data['type']) {
            case 'fan':
                if(isset($data['gender'])){
                    $data_insert['gender'] = $data['gender'];
                }
                if(isset($data['age'])){
                    $data_insert['age'] = $data['age'];
                }
                if(isset($data['language'])){
                    $data_insert['language'] = $data['language'];
                }
                if(isset($data['country'])){
                    $data_insert['country'] = $data['country'];
                }
                break;
            case 'follower':
                if(isset($data['gender'])){
                    $data_insert['gender'] = $data['gender'];
                }
                if(isset($data['age'])){
                    $data_insert['age'] = $data['age'];
                }
                if(isset($data['language'])){
                    $data_insert['language'] = $data['language'];
                }
                if(isset($data['country'])){
                    $data_insert['country'] = $data['country'];
                }
                break;
            case 'reaction':
                if(isset($data['reaction'])){
                    $data_insert['reaction'] = $data['reaction'];
                }
                break;
            case 'post':
                if(isset($data['post_type'])){
                    $data_insert['post_type'] = $data['post_type'];
                }
                break;
            case 'stories_performance':
                if(isset($data['stories_performance'])){
                    $data_insert['stories_performance'] = $data['stories_performance'];
                }
                break;
            case 'engagement':
                if(isset($data['engagement'])){
                    $data_insert['engagement'] = $data['engagement'];
                }
                break;
            case 'subscriber':
                if(isset($data['subscriber'])){
                    $data_insert['subscriber'] = $data['subscriber'];
                }

                if(isset($data['gender'])){
                    $data_insert['gender'] = $data['gender'];
                }
                if(isset($data['age'])){
                    $data_insert['age'] = $data['age'];
                }
                if(isset($data['country'])){
                    $data_insert['country'] = $data['country'];
                }
                break;
            case 'view':
                if(isset($data['device'])){
                    $data_insert['device'] = $data['device'];
                }

                if(isset($data['gender'])){
                    $data_insert['gender'] = $data['gender'];
                }
                if(isset($data['age'])){
                    $data_insert['age'] = $data['age'];
                }
                if(isset($data['country'])){
                    $data_insert['country'] = $data['country'];
                }
                if(isset($data['is_subscriber'])){
                    $data_insert['is_subscriber'] = $data['is_subscriber'];
                }
                break;
            default:
                // code...
                break;
        }
        

        $this->db->insert(db_prefix().'sa_analytics', $data_insert);
       
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    /**
     * [update_facebook_setting]
     * @param  [array] $data
     * @return [boolean]      
     */
    public function update_facebook_setting($data){
        $affectedRows = 0;

        foreach ($data as $key => $value) {
            $this->db->where('name', $key);
            $this->db->update(db_prefix() . 'options', [
                    'value' => $value,
                ]);
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * [executeRequest]
     * @param  [string] $url        
     * @param  array  $parameters 
     * @param  string $http_header
     * @param  string $http_method
     * @return [array]             
     */
    public function executeRequest($url, $parameters = array(), $http_header = '', $http_method = '')
    {

      $curl_options = array();

      switch($http_method){
            case 'GET':
              $curl_options[CURLOPT_HTTPGET] = 'true';
              if (is_array($parameters) && count($parameters) > 0) {
                $url .= '?' . http_build_query($parameters);
              } elseif ($parameters) {
                $url .= '?' . $parameters;
              }
              break;
            case 'POST':
              $curl_options[CURLOPT_POST] = '1';
              if(is_array($parameters) && count($parameters) > 0){
                $body = http_build_query($parameters);
                $curl_options[CURLOPT_POSTFIELDS] = $body;
              }
              break;
            default:
              break;
      }
      /**
      * An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
      */
      if(is_array($http_header)){
            $header = array();
            foreach($http_header as $key => $value) {
                $header[] = "$key: $value";
            }
            $curl_options[CURLOPT_HTTPHEADER] = $header;
      }

      $curl_options[CURLOPT_URL] = $url;
      $ch = curl_init();


      curl_setopt_array($ch, $curl_options);

      //Don't display, save it on result
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      //Execute the Curl Request
      $result = curl_exec($ch);

      $headerSent = curl_getinfo($ch, CURLINFO_HEADER_OUT );

      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


      $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
       if ($curl_error = curl_error($ch)) {
           throw new Exception($curl_error);
       } else {
           $json_decode = json_decode($result, true);
       }
       curl_close($ch);

       return $json_decode;
    }

    /**
     * [callAPI]
     * @param  [string] $url    
     * @param  [array] $params 
     * @param  [array] $header 
     * @param  string $method 
     * @return [array]         
     */
    public function callAPI($url, $params, $header, $method = 'POST'){
            $data_string = json_encode($params);

            $curl = curl_init($url);
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            if($method == 'POST' || $method == 'PUT'){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            }

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 120);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            
            $result = curl_exec($curl);
            $result = json_decode($result, true);

            return $result;
    }

    /**
     * [add_tiktok_account]
     * @param [array] $data
     * @return [boolean]   
     */
    public function add_tiktok_account($data)
    {
        $data['type'] = 'tiktok';
        $data['workspace_id'] = sa_get_base_workspace_id();
        $data['addedfrom'] = get_staff_user_id();
        $data['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'sa_accounts', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * [get_tiktok_data]
     * @param  [integer] $id tiktok account id
     * @return [boolean]   
     */
    public function get_tiktok_data($id){
        $config = sa_get_tiktok_config();
        $account = $this->get_social_accounts($id);

        $expires_in = date('Y-m-d H:i:s', $account->expires_in);
        if(time() > $account->expires_in){
            $this->tiktok_refresh_token($id);
            $account = $this->get_social_accounts($id);
        }

        $node_default = [
            'addedfrom' => get_staff_user_id(),
            'dateadded' => date('Y-m-d H:i:s'),
            'account_id' => $account->id,
        ];

        $header = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '. $account->access_token);

        $user_url = $config['api_domain'].'/user/info/?fields=open_id,union_id,avatar_url_100,display_name,follower_count,following_count,likes_count,video_count';

        $user_result = $this->callAPI($user_url,  [], $header, 'GET');
        $node = $node_default;
        $node['type'] = 'follower';
        $node['time'] = date('Y-m-d H:i:s');
        $node['value'] = $user_result['data']['user']['follower_count'];
        $data_insert[] = $node;

        $node = $node_default;
        $node['type'] = 'following';
        $node['time'] = date('Y-m-d H:i:s');
        $node['value'] = $user_result['data']['user']['following_count'];
        $data_insert[] = $node;

        $node = $node_default;
        $node['type'] = 'like';
        $node['time'] = date('Y-m-d H:i:s');
        $node['value'] = $user_result['data']['user']['likes_count'];
        $data_insert[] = $node;

        $node = $node_default;
        $node['type'] = 'video';
        $node['time'] = date('Y-m-d H:i:s');
        $node['value'] = $user_result['data']['user']['video_count'];
        $data_insert[] = $node;

         if (count($data_insert) > 0) {
            $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.date('Y-m-d').'"');
            $this->db->where('account_id', $account->id);
            $this->db->delete(db_prefix() . 'sa_analytics');

            $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_analytics', $data_insert);

            if ($affectedRows > 0) {
                return true;
            }
        }

        return true;
    }

    /**
     * change account active
     * @param  [integer] $id     
     * @param  [string] $status 
     */
    public function change_account_active($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'sa_accounts', [
            'active' => $status,
        ]);
    }

    /**
     * [get_data_fan_by_age_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_fan_by_age_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'fan');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];
        $data_female = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];

        $data_male = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];
           

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                if($value['age'] != '' && isset($data_female[$value['age']])){
                    $data_female[$value['age']] += $value['value'];
                }
            }elseif(strtolower($value['gender']) == 'male'){
                if($value['age'] != '' && isset($data_male[$value['age']])){
                    $data_male[$value['age']] -= $value['value'];
                }
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('male'), 'data' => array_values($data_male)],
                ['name' => _l('female'), 'data' => array_values($data_female)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_fan_by_gender_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_fan_by_gender_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'fan');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];

        $data_female = 0;
        $data_male = 0;

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                $data_female += $value['value'];
            }elseif(strtolower($value['gender']) == 'male'){
                $data_male += $value['value'];
            }
        }

        $fan_total = $data_female + $data_male;
        if ($fan_total > 0) {
            $female_percentage = round(($data_female/$fan_total*100), 2);
            $male_percentage = 100 - $female_percentage;
        }else{
            $female_percentage = 0;
            $male_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('male'), 'y' => $male_percentage],
                ['name' => _l('female'), 'y' => $female_percentage],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_fan_stats]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_fan_stats($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'fan');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $countries = [];
        $languages = [];

        foreach ($analytics as $key => $value) {
            if($value['country'] != ''){
                if(!isset($countries[$value['country']])){
                    $countries[$value['country']] = 0;
                }
                $countries[$value['country']] += $value['value'];
            }

            if($value['language'] != ''){
                if(!isset($languages[$value['language']])){
                    $languages[$value['language']] = 0;
                }
                $languages[$value['language']] += $value['value'];
            }
        }

        arsort($countries);
        arsort($languages);

        $data_return['countries'] = $countries;
        $data_return['languages'] = $languages;
        
        return $data_return;
    }
    
    /**
     * [get_data_reactions_overview_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_reactions_overview_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'reaction');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = ['Like', 'Love', 'Wow', 'Haha', 'Sad', 'Angry'];

        $like_total = 0;
        $love_total = 0;
        $wow_total = 0;
        $haha_total = 0;
        $sad_total = 0;
        $angry_total = 0;

        foreach ($analytics as $key => $value) {

            switch ($value['reaction']) {
                case 'like':
                    $like_total += $value['value'];
                    break;
                case 'love':
                    $love_total += $value['value'];
                    break;
                case 'wow':
                    $wow_total += $value['value'];
                    break;
                case 'haha':
                    $haha_total += $value['value'];
                    break;
                case 'sad':
                    $sad_total += $value['value'];
                    break;
                case 'angry':
                    $angry_total += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }
            
        $data_return = [
            'data' => [
                [ 'y' => $like_total, 'color' => '#3b5998' ],  
                [ 'y' => $love_total, 'color' => '#e63946' ],  
                [ 'y' => $wow_total, 'color' => '#ffcc00' ],
                [ 'y' => $haha_total, 'color' => '#f7d44c' ],
                [ 'y' => $sad_total, 'color' => '#7d7d7d' ],
                [ 'y' => $angry_total, 'color' => '#d9534f' ]
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_engagement_by_day_time_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_engagement_by_day_time_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "reaction" OR type = "comment" OR type = "click" OR type = "share")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];

        $defaultData = [];
        $hours = 24;
        $days = 7;

        for ($hour=0; $hour < $hours; $hour++) { 
            for ($day=0; $day < $days; $day++) { 
                $defaultData[$hour][$day] = 0;
            }
        }

        foreach ($analytics as $key => $value) {
            $hour = date('H', strtotime($value['time'])) + 0;
            $day = date('N', strtotime($value['time'])) - 1;
            if(isset($defaultData[$hour][$day])){
                $defaultData[$hour][$day] += $value['value'];
            }
        }
            
        foreach ($defaultData as $hour => $value) {
            foreach ($value as $day => $val) {
                $data_return[] = [$hour, $day, $val];
            }    
        }    
        
        return $data_return;
    }

    /**
     * [get_data_post_by_type_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_post_by_type_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'post');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_video = 0;
        $data_photo = 0;
        $data_link = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['post_type'] == 'video'){
                $data_video += $value['value'];
            }elseif($value['post_type'] == 'photo'){
                $data_photo += $value['value'];
            }elseif($value['post_type'] == 'link'){
                $data_link += $value['value'];
            }
        }

        $post_total = $data_video + $data_photo + $data_link;
        if($post_total > 0){
            $video_percentage = round(($data_video/$post_total*100), 2);
            $photo_percentage = round(($data_photo/$post_total*100), 2);
            $link_percentage = 100 - $video_percentage - $photo_percentage;
        }else{
            $video_percentage = 0;
            $photo_percentage = 0;
            $link_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_videos'), 'y' => $video_percentage],
                ['name' => _l('sa_photos'), 'y' => $photo_percentage],
                ['name' => _l('sa_links'), 'y' => $link_percentage],
            ],
        ];
        return $data_return;
    }

    
    
    /**
     * [get_data_engagement_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_engagement_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');

        $this->db->where(db_prefix() . 'sa_accounts.type', 'facebook');

        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');

        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "click" or ' . db_prefix() . 'sa_analytics.type = "reaction" or ' . db_prefix() . 'sa_analytics.type = "comment" or ' . db_prefix() . 'sa_analytics.type = "share")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_click = 0;
        $data_reaction = 0;
        $data_comment = 0;
        $data_share = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['type'] == 'click'){
                $data_click += $value['value'];
            }elseif($value['type'] == 'reaction'){
                $data_reaction += $value['value'];
            }elseif($value['type'] == 'comment'){
                $data_comment += $value['value'];
            }elseif($value['type'] == 'share'){
                $data_share += $value['value'];
            }
        }

        $post_total = $data_click + $data_reaction + $data_comment + $data_share;
        if($post_total > 0){
            $click_percentage = round(($data_click/$post_total*100), 2);
            $reaction_percentage = round(($data_reaction/$post_total*100), 2);
            $share_percentage = round(($data_share/$post_total*100), 2);
            $comment_percentage = round(100 - $click_percentage - $reaction_percentage - $share_percentage, 2);
        }else{
            $click_percentage = 0;
            $reaction_percentage = 0;
            $share_percentage = 0;
            $comment_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_clicks'), 'y' => $click_percentage],
                ['name' => _l('sa_reactions'), 'y' => $reaction_percentage],
                ['name' => _l('sa_comments'), 'y' => $comment_percentage],
                ['name' => _l('sa_shares'), 'y' => $share_percentage],
            ],
        ];
        return $data_return;
    }


    
    /**
     * [get_data_twitter_engagement_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_twitter_engagement_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "engagement")');
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');

        $this->db->where(db_prefix() . 'sa_accounts.type', 'twitter');

        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_like = 0;
        $data_retweet = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['engagement'] == 'like'){
                $data_like += $value['value'];
            }elseif($value['engagement'] == 'retweet'){
                $data_retweet += $value['value'];
            }
        }

        $post_total = $data_like + $data_retweet;
        if($post_total > 0){
            $like_percentage = round(($data_like/$post_total*100), 2);
            $retweet_percentage = round(100 - $like_percentage, 2);
        }else{
            $like_percentage = 0;
            $retweet_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_likes'), 'y' => $like_percentage],
                ['name' => _l('sa_retweets'), 'y' => $retweet_percentage],
            ],
        ];
        return $data_return;
    }


    /**
     * [get_data_publishing_behavior_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_publishing_behavior_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $video = [];
        $photo = [];
        $carousel = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "post")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($video[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $video[$date] = 0;
                $photo[$date] = 0;
                $carousel[$date] = 0;
            }

            switch ($value['post_type']) {
                case 'video':
                    $video[$date] += $value['value'];
                    break;
                case 'photo':
                    $photo[$date] += $value['value'];
                    break;
                case 'carousel':
                    $carousel[$date] += $value['value'];
                    break;
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'video' => array_values($video),
            'photo' => array_values($photo),
            'carousel' => array_values($carousel),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }


    /**
     * [get_data_active_users_by_hours_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_active_users_by_hours_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'active_user');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
        $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        for ($i=0; $i <= 23; $i++) { 
            $categories[] = $i;
            $data_date[] = 0;
        }

        foreach ($analytics as $key => $value) {
            $date = date('H', strtotime($value['time'])) + 0;

            if(isset($data_date[$date])){
                $data_date[$date] += $value['value'];
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('sa_active_users'), 'data' => $data_date],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_active_users_by_days_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_active_users_by_days_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'active_user');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
        $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $data_date = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0];

        foreach ($analytics as $key => $value) {
            $date = date('l', strtotime($value['time']));

            if(isset($data_date[$date])){
                $data_date[$date] += $value['value'];
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('sa_active_users'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_instagram_impressions_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_instagram_impressions_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'impression');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            $data_date[$date] += $value['value'];
        }

        $data_return = [
            'data' => [
                ['name' => _l('sa_impressions'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_instagram_engagement_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_instagram_engagement_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'engagement');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            $data_date[$date] += $value['value'];
        }

        $data_return = [
            'data' => [
                ['name' => _l('sa_engagement'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_instagram_stories_performance_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_instagram_stories_performance_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $published_stories = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "published_stories")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($published_stories[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $published_stories[$date] = 0;
                $story_tap_backs[$date] = 0;
                $story_taps_forward[$date] = 0;
                $story_exits[$date] = 0;
            }

            $published_stories[$date] += $value['value'];
        }

        $data_total = [
            'published_stories' => array_values($published_stories),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_stories_performance_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_stories_performance_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $story_replies = [];
        $story_tap_backs = [];
        $story_taps_forward = [];
        $story_exits = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "stories_performance")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($story_replies[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $story_replies[$date] = 0;
                $story_tap_backs[$date] = 0;
                $story_taps_forward[$date] = 0;
                $story_exits[$date] = 0;
            }

            switch ($value['stories_performance']) {
                case 'story_replies':
                    $story_replies[$date] += $value['value'];
                    break;
                case 'story_tap_backs':
                    $story_tap_backs[$date] += $value['value'];
                    break;
                case 'story_taps_forward':
                    $story_taps_forward[$date] += $value['value'];
                    break;
                case 'story_exits':
                    $story_exits[$date] += $value['value'];
                    break;
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'story_replies' => array_values($story_replies),
            'story_tap_backs' => array_values($story_tap_backs),
            'story_taps_forward' => array_values($story_taps_forward),
            'story_exits' => array_values($story_exits),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_twitter_engagement_rate]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_twitter_engagement_rate($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $like = [];
        $retweet = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "engagement")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($like[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $like[$date] = 0;
                $retweet[$date] = 0;
            }

            switch ($value['engagement']) {
                case 'like':
                    $like[$date] += $value['value'];
                    break;
                case 'retweet':
                    $retweet[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'like' => array_values($like),
            'retweet' => array_values($retweet),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }


    
    /**
     * [get_data_twitter_audience_growth_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_twitter_audience_growth_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where('('.db_prefix().'sa_analytics.type = "follower" OR '.db_prefix().'sa_analytics.type = "following")');
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = [];
                $data_date[$date]['follower'] = 0;
                $data_date[$date]['following'] = 0;
            }
            if ($value['type'] == 'follower') {
                $data_date[$date]['follower'] += $value['value'];
            }else{
                $data_date[$date]['following'] += $value['value'];
            }
        }
        $follower = [];
        $following = [];
        foreach($data_date as $key => $value) {
            $follower[] = $value['follower'];
            $following[] = $value['following'];
        }

        $data_return = [
            'data' => [
                ['name' => _l('follower'), 'data' => $follower],
                ['name' => _l('sa_following'), 'data' => $following],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_follow_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_follow_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "follower" or type = "following")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_follower = 0;
        $data_following = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['type'] == 'follower'){
                $data_follower += $value['value'];
            }elseif($value['type'] == 'following'){
                $data_following += $value['value'];
            }
        }

        $post_total = $data_follower + $data_following ;
        if($post_total > 0){
            $follower_percentage = round(($data_follower/$post_total*100), 2);
            $following_percentage = round(100 - $follower_percentage, 2);
        }else{
            $follower_percentage = 0;
            $following_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_followers'), 'y' => $follower_percentage],
                ['name' => _l('sa_following'), 'y' => $following_percentage],
            ],
        ];
        return $data_return;
    }

    /**
     * [get_data_awareness_through_mention_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_awareness_through_mention_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'awareness_through_mention');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('total_no_of_mentions'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }


    /**
     * [get_data_follower_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_follower_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('follower'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_tiktok_follower_gender_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_tiktok_follower_gender_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "follower")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_male = 0;
        $data_female = 0;
           

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'male'){
                $data_male += $value['value'];
            }elseif(strtolower($value['gender']) == 'female'){
                $data_female += $value['value'];
            }
        }

        $post_total = $data_male + $data_female ;
        if($post_total > 0){
            $male_percentage = round(($data_male/$post_total*100), 2);
            $female_percentage = round(100 - $male_percentage, 2);
        }else{
            $male_percentage = 0;
            $female_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('male'), 'y' => $male_percentage],
                ['name' => _l('female'), 'y' => $female_percentage],
            ],
        ];
        return $data_return;
    }

    /**
     * [get_data_follower_stats]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_follower_stats($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $countries = [];
        $languages = [];

        foreach ($analytics as $key => $value) {
            if($value['country'] != ''){
                if(!isset($countries[$value['country']])){
                    $countries[$value['country']] = 0;
                }
                $countries[$value['country']] += $value['value'];
            }

            if($value['language'] != ''){
                if(!isset($languages[$value['language']])){
                    $languages[$value['language']] = 0;
                }
                $languages[$value['language']] += $value['value'];
            }
        }

        arsort($countries);
        arsort($languages);

        $data_return['countries'] = $countries;
        $data_return['languages'] = $languages;
        
        return $data_return;
    }

    /**
     * [get_data_video_views_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_video_views_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $engagement = [];
        $video_view = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }

        $this->db->where('(type = "video_view")');
        $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($header[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $video_view[$date] = 0;
            }

            $video_view[$date] += $value['value'];
        }

        $data_total = [
            'video_view' => array_values($video_view),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_tiktok_engagement_rate]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_tiktok_engagement_rate($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $like = [];
        $share = [];
        $comment = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "engagement")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($like[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $like[$date] = 0;
                $share[$date] = 0;
                $comment[$date] = 0;
            }

            switch ($value['engagement']) {
                case 'like':
                    $like[$date] += $value['value'];
                    break;
                case 'share':
                    $share[$date] += $value['value'];
                    break;
                case 'comment':
                    $comment[$date] += $value['value'];
                    break;

                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'like' => array_values($like),
            'share' => array_values($share),
            'comment' => array_values($comment),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_posting_pattern_engagement_analysis]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_posting_pattern_engagement_analysis($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $engagement = [];
        $video = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }

        $this->db->where('(type = "video" or type = "engagement")');
        $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($header[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $engagement[$date] = 0;
                $video[$date] = 0;
            }

            switch ($value['type']) {
                case 'engagement':
                    $engagement[$date] += $value['value'];
                    break;
                case 'video':
                    $video[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'engagement' => array_values($engagement),
            'video' => array_values($video),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_gained_lost_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_gained_lost_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $gained = [];
        $lost = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "subscriber")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($gained[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $gained[$date] = 0;
                $lost[$date] = 0;
            }

            switch (strtolower($value['subscriber'])) {
                case 'gained':
                    $gained[$date] += $value['value'];
                    break;
                case 'lost':
                    $lost[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'gained' => array_values($gained),
            'lost' => array_values($lost),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_daily_video_views_graph]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_daily_video_views_graph($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'view');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('sa_views'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_estimated_minutes_watched]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_estimated_minutes_watched($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'estimated_minutes_watched');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('sa_views'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_videos_published]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_videos_published($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $published_stories = [];

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "video")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($published_stories[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $published_stories[$date] = 0;
            }

            $published_stories[$date] += $value['value'];
        }

        $data_total = [
            'published_stories' => array_values($published_stories),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_like_vs_dislike_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_like_vs_dislike_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $like = [];
        $dislike = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "like" OR type = "dislike")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($like[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $like[$date] = 0;
                $dislike[$date] = 0;
            }

            switch (strtolower($value['type'])) {
                case 'like':
                    $like[$date] += $value['value'];
                    break;
                case 'dislike':
                    $dislike[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'like' => array_values($like),
            'dislike' => array_values($dislike),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_comments_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_comments_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'comment');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('sa_comments'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_shares_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_shares_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'share');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('sa_shares'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_subscriber_by_age_chart]
     * @param  [array] $data_filter 
     * @return [array]              
     */
    public function get_data_subscriber_by_age_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'subscriber');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];
        $data_female = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];

        $data_male = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];
           

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                if($value['age'] != '' && isset($data_female[$value['age']])){
                    $data_female[$value['age']] += $value['value'];
                }
            }elseif(strtolower($value['gender']) == 'male'){
                if($value['age'] != '' && isset($data_male[$value['age']])){
                    $data_male[$value['age']] -= $value['value'];
                }
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('male'), 'data' => array_values($data_male)],
                ['name' => _l('female'), 'data' => array_values($data_female)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_subscriber_by_gender_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_subscriber_by_gender_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'subscriber');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];

        $data_female = 0;
        $data_male = 0;

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                $data_female += $value['value'];
            }elseif(strtolower($value['gender']) == 'male'){
                $data_male += $value['value'];
            }
        }

        $subscriber_total = $data_female + $data_male;
        if ($subscriber_total > 0) {
            $female_percentage = round(($data_female/$subscriber_total*100), 2);
            $male_percentage = 100 - $female_percentage;
        }else{
            $female_percentage = 0;
            $male_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('male'), 'y' => $male_percentage],
                ['name' => _l('female'), 'y' => $female_percentage],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_subscriber_stats]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_subscriber_stats($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'subscriber');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $countries = [];
        $languages = [];

        foreach ($analytics as $key => $value) {
            if($value['country'] != ''){
                if(!isset($countries[$value['country']])){
                    $countries[$value['country']] = 0;
                }
                $countries[$value['country']] += $value['value'];
            }

            if($value['language'] != ''){
                if(!isset($languages[$value['language']])){
                    $languages[$value['language']] = 0;
                }
                $languages[$value['language']] += $value['value'];
            }
        }

        arsort($countries);
        arsort($languages);

        $data_return['countries'] = $countries;
        $data_return['languages'] = $languages;
        
        return $data_return;
    }

    /**
     * [get_data_view_stats]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_view_stats($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'view');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $countries = [];
        $languages = [];

        foreach ($analytics as $key => $value) {
            if($value['country'] != ''){
                if(!isset($countries[$value['country']])){
                    $countries[$value['country']] = 0;
                }
                $countries[$value['country']] += $value['value'];
            }

            if($value['language'] != ''){
                if(!isset($languages[$value['language']])){
                    $languages[$value['language']] = 0;
                }
                $languages[$value['language']] += $value['value'];
            }
        }

        arsort($countries);
        arsort($languages);

        $data_return['countries'] = $countries;
        $data_return['languages'] = $languages;
        
        return $data_return;
    }

    /**
     * [get_data_viewer_by_age_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_viewer_by_age_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'view');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];
        $data_female = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];

        $data_male = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];
           

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                if($value['age'] != '' && isset($data_female[$value['age']])){
                    $data_female[$value['age']] += $value['value'];
                }
            }elseif(strtolower($value['gender']) == 'male'){
                if($value['age'] != '' && isset($data_male[$value['age']])){
                    $data_male[$value['age']] -= $value['value'];
                }
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('male'), 'data' => array_values($data_male)],
                ['name' => _l('female'), 'data' => array_values($data_female)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_viewer_by_gender_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_viewer_by_gender_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'view');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];

        $data_female = 0;
        $data_male = 0;

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                $data_female += $value['value'];
            }elseif(strtolower($value['gender']) == 'male'){
                $data_male += $value['value'];
            }
        }

        $subscriber_total = $data_female + $data_male;
        if ($subscriber_total > 0) {
            $female_percentage = round(($data_female/$subscriber_total*100), 2);
            $male_percentage = 100 - $female_percentage;
        }else{
            $female_percentage = 0;
            $male_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('male'), 'y' => $male_percentage],
                ['name' => _l('female'), 'y' => $female_percentage],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_viewer_by_device_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_viewer_by_device_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'view');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];

        $data_mobile = 0;
        $data_tablet = 0;
        $data_desktop = 0;

        foreach ($analytics as $key => $value) {
            if(strtolower($value['device']) == 'mobile'){
                $data_mobile += $value['value'];
            }elseif(strtolower($value['device']) == 'tablet'){
                $data_tablet += $value['value'];
            }elseif(strtolower($value['device']) == 'desktop'){
                $data_desktop += $value['value'];
            }
        }

        $subscriber_total = $data_mobile + $data_tablet + $data_desktop;
        if ($subscriber_total > 0) {
            $mobile_percentage = round(($data_mobile/$subscriber_total*100), 2);
            $desktop_percentage = round(($data_desktop/$subscriber_total*100), 2);
            $tablet_percentage = round((100 - ($mobile_percentage + $desktop_percentage)), 2);
        }else{
            $mobile_percentage = 0;
            $tablet_percentage = 0;
            $desktop_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('tablet'), 'y' => $tablet_percentage],
                ['name' => _l('mobile'), 'y' => $mobile_percentage],
                ['name' => _l('desktop'), 'y' => $desktop_percentage],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_active_users_by_day]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_active_users_by_day($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);

        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->where(db_prefix().'sa_analytics.type', 'active_user');
        
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
            $this->db->order_by('time', 'asc');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        $data_return = [];
        $categories = [];
        $data_date = [];
        foreach ($analytics as $key => $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($data_date[$date])){
                $categories[] = date('d.M', strtotime($value['time']));
                $data_date[$date] = 0;
            }
            
            $data_date[$date] += $value['value'];
        }


        $data_return = [
            'data' => [
                ['name' => _l('sa_active_users'), 'data' => array_values($data_date)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_follower_by_age_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_follower_by_age_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];
        $data_female = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];

        $data_male = [
                '13_17' => 0,
                '18_24' => 0,
                '25_34' => 0,
                '35_44' => 0,
                '45_54' => 0,
                '55_64' => 0,
                '65_and_over' => 0,
            ];
           

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                if($value['age'] != '' && isset($data_female[$value['age']])){
                    $data_female[$value['age']] += $value['value'];
                }
            }elseif(strtolower($value['gender']) == 'male'){
                if($value['age'] != '' && isset($data_male[$value['age']])){
                    $data_male[$value['age']] -= $value['value'];
                }
            }
        }

        $data_return = [
            'data' => [
                ['name' => _l('male'), 'data' => array_values($data_male)],
                ['name' => _l('female'), 'data' => array_values($data_female)],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_follower_by_gender_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_follower_by_gender_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where(db_prefix().'sa_analytics.type', 'follower');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = [
            _l('13_17'),
            _l('18_24'),
            _l('25_34'),
            _l('35_44'),
            _l('45_54'),
            _l('55_64'),
            _l('65_and_over')];

        $data_female = 0;
        $data_male = 0;

        foreach ($analytics as $key => $value) {
            if(strtolower($value['gender']) == 'female'){
                $data_female += $value['value'];
            }elseif(strtolower($value['gender']) == 'male'){
                $data_male += $value['value'];
            }
        }

        $follower_total = $data_female + $data_male;
        if ($follower_total > 0) {
            $female_percentage = round(($data_female/$follower_total*100), 2);
            $male_percentage = 100 - $female_percentage;
        }else{
            $female_percentage = 0;
            $male_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('male'), 'y' => $male_percentage],
                ['name' => _l('female'), 'y' => $female_percentage],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_daily_video_views_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_daily_video_views_chart($data_filter){
        $where = $this->get_where_report_period('date_format(time, \'%Y-%m-%d\')');

        $header = [];
        $subscriber = [];
        $non_subscriber = [];
        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(type = "view")');
        $this->db->order_by('time', 'asc');
        
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();

        foreach ($analytics as $value) {
            $date = date('Y-m-d', strtotime($value['time']));

            if(!isset($subscriber[$date])){
                $header[$date] = date('d.M', strtotime($value['time']));
                $subscriber[$date] = 0;
                $non_subscriber[$date] = 0;
            }

            switch (strtolower($value['is_subscriber'])) {
                case 'subscriber':
                    $subscriber[$date] += $value['value'];
                    break;
                case 'non_subscriber':
                    $non_subscriber[$date] += $value['value'];
                    break;
                
                default:
                    // code...
                    break;
            }
        }

        $data_total = [
            'subscriber' => array_values($subscriber),
            'non_subscriber' => array_values($non_subscriber),
        ];

        return ['header' => array_values($header), 'data_total' => $data_total];
    }

    /**
     * [get_data_account_performance]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_account_performance($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');
        $this->db->where('('.db_prefix().'sa_analytics.type = "click" OR '.db_prefix().'sa_analytics.type = "engagement" OR '.db_prefix().'sa_analytics.type = "comment" OR '.db_prefix().'sa_analytics.type = "reaction" OR '.db_prefix().'sa_analytics.type = "share" OR '.db_prefix().'sa_analytics.type = "like" OR '.db_prefix().'sa_analytics.type = "dislike")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        $categories = ['Facebook', 'Instagram', 'Tiktok', 'Twitter', 'Youtube'];

        $data_total = [
            'facebook' => 0,
            'instagram' => 0,
            'tiktok' => 0,
            'twitter' => 0,
            'youtube' => 0,
        ];

        foreach ($analytics as $key => $value) {
            if($value['account_type'] != '' && isset($data_total[$value['account_type']]) && is_numeric($value['value'])){
                $data_total[$value['account_type']] += $value['value'];
            }
        }
        
        $data_return = [
            'data' => [
                [ 'y' => $data_total['facebook'], 'color' => '#1877F2' ],  
                [ 'y' => $data_total['instagram'], 'color' => '#E1306C' ],  
                [ 'y' => $data_total['tiktok'], 'color' => '#69C9D0' ],
                [ 'y' => $data_total['twitter'], 'color' => '#000000' ],
                [ 'y' => $data_total['youtube'], 'color' => '#FF0000' ],
            ],
            'categories' => $categories
        ];
        return $data_return;
    }

    /**
     * [get_data_tiktok_engagement_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_tiktok_engagement_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "engagement")');
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');

        $this->db->where(db_prefix() . 'sa_accounts.type', 'tiktok');

        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_like = 0;
        $data_share = 0;
        $data_comment = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['engagement'] == 'like'){
                $data_like += $value['value'];
            }elseif($value['engagement'] == 'share'){
                $data_share += $value['value'];
            }elseif($value['engagement'] == 'comment'){
                $data_comment += $value['value'];
            }
        }

        $post_total = $data_like + $data_share + $data_comment;
        if($post_total > 0){
            $like_percentage = round(($data_like/$post_total*100), 2);
            $comment_percentage = round(($data_comment/$post_total*100), 2);
            $share_percentage = round(100 - $like_percentage  - $comment_percentage, 2);
        }else{
            $like_percentage = 0;
            $comment_percentage = 0;
            $share_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_likes'), 'y' => $like_percentage],
                ['name' => _l('sa_shares'), 'y' => $share_percentage],
                ['name' => _l('sa_comments'), 'y' => $comment_percentage],
            ],
        ];
        return $data_return;
    }

    /**
     * [get_data_youtube_engagement_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_youtube_engagement_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');

        $this->db->where(db_prefix() . 'sa_accounts.type', 'youtube');

        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');

        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "like" OR ' . db_prefix() . 'sa_analytics.type = "dislike" OR ' . db_prefix() . 'sa_analytics.type = "comment" OR ' . db_prefix() . 'sa_analytics.type = "share")');
        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_like = 0;
        $data_dislike = 0;
        $data_comment = 0;
        $data_share = 0;
        $data_view = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['type'] == 'like'){
                $data_like += $value['value'];
            }elseif($value['type'] == 'dislike'){
                $data_dislike += $value['value'];
            }elseif($value['type'] == 'comment'){
                $data_comment += $value['value'];
            }elseif($value['type'] == 'share'){
                $data_share += $value['value'];
            }
        }

        $post_total = $data_like + $data_dislike + $data_comment + $data_share + $data_view;
        if($post_total > 0){
            $like_percentage = round(($data_like/$post_total*100), 2);
            $dislike_percentage = round(($data_dislike/$post_total*100), 2);
            $comment_percentage = round(($data_comment/$post_total*100), 2);
            $share_percentage = round(100 - $like_percentage - $comment_percentage - $dislike_percentage, 2);
        }else{
            $like_percentage = 0;
            $dislike_percentage = 0;
            $comment_percentage = 0;
            $share_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_likes'), 'y' => $like_percentage],
                ['name' => _l('sa_dislikes'), 'y' => $dislike_percentage],
                ['name' => _l('sa_comments'), 'y' => $comment_percentage],
                ['name' => _l('sa_shares'), 'y' => $share_percentage],
            ],
        ];
        return $data_return;
    }

    /**
     * [get_data_instagram_engagement_rate_pie_chart]
     * @param  [array] $data_filter
     * @return [array]             
     */
    public function get_data_instagram_engagement_rate_pie_chart($data_filter){
        $where = $this->get_where_report_period();

        $account_ids = [0];
        foreach ($data_filter as $key => $value) {
            if(!(strpos($key, 'account_id') === false)){
                $account_ids[] = $value;
            }
        }

        $account_ids = implode(',', $account_ids);
        $this->db->where('account_id in ('.$account_ids.')');
        $this->db->select('*, ' . db_prefix() . 'sa_analytics.type as type, ' . db_prefix() . 'sa_accounts.type as account_type');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('(' . db_prefix() . 'sa_analytics.type = "engagement" OR ' . db_prefix() . 'sa_analytics.type = "comment")');
        $this->db->where(db_prefix() . 'sa_accounts.type', 'instagram');

        $this->db->join(db_prefix().'sa_accounts', db_prefix() . 'sa_accounts.id = ' . db_prefix() . 'sa_analytics.account_id');

        $analytics = $this->db->get(db_prefix().'sa_analytics')->result_array();
        $data_return = [];
        
        $data_like = 0;
        $data_comment = 0;
           

        foreach ($analytics as $key => $value) {
            if($value['type'] == 'engagement'){
                $data_like += $value['value'];
            }elseif($value['type'] == 'comment'){
                $data_comment += $value['value'];
            }
        }

        $post_total = $data_like + $data_comment;
        if($post_total > 0){
            $like_percentage = round(($data_like/$post_total*100), 2);
            $comment_percentage = round(100 - $like_percentage, 2);
        }else{
            $like_percentage = 0;
            $comment_percentage = 0;
        }
        $data_return = [
            'data' => [
                ['name' => _l('sa_engagement'), 'y' => $like_percentage],
                ['name' => _l('sa_comments'), 'y' => $comment_percentage],
            ],
        ];
        return $data_return;
    }

    /**
     * [update_workspace_display_chart]
     * @param  [array] $data
     * @param  [string] $id  workspace id
     * @return [boolean]      
     */
    public function update_workspace_display_chart($data, $id){
        $display_charts = json_encode($data);
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'sa_workspaces', ['display_charts' => $display_charts]);
       
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [set_contact_default_workspace]
     * @param [type] $id workspace id
     */
    public function set_contact_default_workspace($id){
        $contact_id = get_contact_user_id();
        $this->db->where('id', $contact_id);
        $this->db->update(db_prefix().'contacts', ['base_workspace_id' => $id]);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [facebook_connect_save]
     * @param  [string] $data
     * @return [boolean] 
     */
    public function facebook_connect_save($data){
        $pages = json_decode($data['pages'], true);
        $account = json_decode($data['account'], true);
        $page_arr = [];
        foreach ($pages as $key => $value) {
            $page_arr[$value['id']] = $value;
        }
        $page_id = $data['page_id'];
        $this->db->where('id', $data['account_id']);
        $this->db->update(db_prefix().'sa_accounts', 
            [
                'user_id' => $account['id'],
                'page_id' => $page_id,
                'access_token' => $page_arr[$page_id]['access_token'],
                'expires_in' => $data['expires_in'],
                'category' => $page_arr[$page_id]['category'],
                'status' => 1,
            ]
        );
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * [account_connect_save]
     * @param  [array] $data      
     * @param  [string] $account_id 
     * @return [Boolean]      
     */
    public function account_connect_save($data, $account_id)
    {
        $this->db->where('id', $account_id);
        $this->db->update(db_prefix().'sa_accounts', 
            $data
        );

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * [get_twitter_data]
     * @param  [type] $id twitter account id
     * @return [boolean]  
     */
    public function get_twitter_data($id){
        $config = sa_get_twitter_config();
        $account = $this->get_social_accounts($id);

        $expires_in = date('Y-m-d H:i:s', $account->expires_in);
        if(time() > $account->expires_in){
            $this->twitter_refresh_token($id);
            $account = $this->get_social_accounts($id);
        }

        $node_default = [
            'addedfrom' => get_staff_user_id(),
            'dateadded' => date('Y-m-d H:i:s'),
            'account_id' => $account->id,
        ];

        $header = array(
                'Authorization: Bearer '. $account->access_token);

        $user_url = $config['api_domain'].'/2/users/'.$account->user_id.'?user.fields=public_metrics';

        $user_result = $this->callAPI($user_url,  [], $header, 'GET');
        if(isset($user_result['data'])){
            $node = $node_default;
            $node['type'] = 'follower';
            $node['engagement'] = '';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $user_result['data']['public_metrics']['followers_count'];
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'following';
            $node['engagement'] = '';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $user_result['data']['public_metrics']['following_count'];
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'engagement';
            $node['engagement'] = 'like';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $user_result['data']['public_metrics']['like_count'];
            $data_insert[] = $node;

            $node = $node_default;
            $node['type'] = 'post';
            $node['engagement'] = '';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $user_result['data']['public_metrics']['tweet_count'];
            $data_insert[] = $node;

             if (count($data_insert) > 0) {
                $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.date('Y-m-d').'"');
                $this->db->where('account_id', $account->id);
                $this->db->delete(db_prefix() . 'sa_analytics');

                $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_analytics', $data_insert);

                if ($affectedRows > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * [youtube_connect_save]
     * @param  [array] $data 
     * @return [boolean]  
     */
    public function youtube_connect_save($data){
        $page_id = $data['page_id'];
        $this->db->where('id', $data['account_id']);
        $this->db->update(db_prefix().'sa_accounts', 
            [
                'user_id' => $page_id,
                'page_id' => $page_id,
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
                'refresh_token' => $data['refresh_token'],
                'status' => 1,
            ]
        );
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * [get_youtube_data]
     * @param  [string] $id youtube account id
     * @return [boolean] 
     */
    public function get_youtube_data($id){
        $account = $this->get_social_accounts($id);

        $config = sa_get_youtube_config();
        $client = new Google_Client();
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri(admin_url('social_analytic/youtube_callback'));
        $client->setAccessToken($account->access_token);

        $expires_in = date('Y-m-d H:i:s', $account->expires_in);
        if(time() > $account->expires_in){
            $token_result = $client->fetchAccessTokenWithRefreshToken($account->refresh_token);

            $data_update = [
                'access_token' => $token_result['access_token'],
                'expires_in' => time() + $token_result['expires_in'],
            ];

            $this->account_connect_save($data_update, $account->id);
        }
        
        $youtube = new Google_Service_YouTube($client);

        $response = $youtube->channels->listChannels('snippet,statistics', ['id' => $account->page_id]);
        $channels = $response->getItems();

        $node_default = [
            'addedfrom' => get_staff_user_id(),
            'dateadded' => date('Y-m-d H:i:s'),
            'account_id' => $account->id,
        ];

        $data_insert = [];
        foreach ($channels as $channel) {
            if($channel['id'] == $account->page_id){
                if(is_numeric($channel['statistics']['videoCount'])){
                    $node = $node_default;
                    $node['type'] = 'video';
                    $node['time'] = date('Y-m-d H:i:s');
                    $node['value'] = $channel['statistics']['videoCount'];
                    $data_insert[] = $node;
                }

                if(is_numeric($channel['statistics']['viewCount'])){
                    $node = $node_default;
                    $node['type'] = 'view';
                    $node['time'] = date('Y-m-d H:i:s');
                    $node['value'] = $channel['statistics']['viewCount'];
                    $data_insert[] = $node;
                }

                if(is_numeric($channel['statistics']['subscriberCount'])){
                    $node = $node_default;
                    $node['type'] = 'subscriber';
                    $node['time'] = date('Y-m-d H:i:s');
                    $node['value'] = $channel['statistics']['subscriberCount'];
                    $data_insert[] = $node;
                }

                if(is_numeric($channel['statistics']['commentCount'])){
                    $node = $node_default;
                    $node['type'] = 'comment';
                    $node['time'] = date('Y-m-d H:i:s');
                    $node['value'] = $channel['statistics']['commentCount'];
                    $data_insert[] = $node;
                }
            }
        }

        if (count($data_insert) > 0) {
            $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.date('Y-m-d').'"');
            $this->db->where('account_id', $account->id);
            $this->db->delete(db_prefix() . 'sa_analytics');

            $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_analytics', $data_insert);

            if ($affectedRows > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * [instagram_connect_save]
     * @param  [Array] $data
     * @return [Boolean]
     */
    public function instagram_connect_save($data){
        $pages = json_decode($data['pages'], true);
        $page_arr = [];
        foreach ($pages as $key => $value) {
            $page_arr[$value['id']] = $value;
        }
        $page_id = $data['page_id'];
        $this->db->where('id', $data['account_id']);
        $this->db->update(db_prefix().'sa_accounts', 
            [
                'user_id' => $page_id,
                'page_id' => $page_id,
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
                'status' => 1,
            ]
        );
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * [get_instagram_data]
     * @param  [String] $id tiktok account id
     * @return [Boolean]    
     */
    public function get_instagram_data($id){
        $config = sa_get_instagram_config();
        $fb = new \Facebook\Facebook($config);
       
        $account = $this->social_analytic_model->get_social_accounts($id);

        try {
            $node_default = [
                'addedfrom' => get_staff_user_id(),
                'dateadded' => date('Y-m-d H:i:s'),
                'account_id' => $account->id,
            ];
            $data_insert = [];
            $response = $fb->get('/'.$account->page_id.'?fields=id,name,username,followers_count,media_count', $account->access_token);
            $page_info = $response->getDecodedBody();
            $node = $node_default;
            $node['type'] = 'follower';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $page_info['followers_count'];
            $data_insert[] = $node;
           
            $node = $node_default;
            $node['type'] = 'post';
            $node['time'] = date('Y-m-d H:i:s');
            $node['value'] = $page_info['media_count'];
            $data_insert[] = $node;

           
            if (count($data_insert) > 0) {
                $this->db->where('date_format(time, \'%Y-%m-%d\') = "'.date('Y-m-d').'"');
                $this->db->where('account_id', $account->id);
                $this->db->delete(db_prefix() . 'sa_analytics');

                $affectedRows = $this->db->insert_batch(db_prefix() . 'sa_analytics', $data_insert);

                if ($affectedRows > 0) {
                    return true;
                }
            }
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        return true;
    }

    /**
     * tiktok refresh token
     * @param  {String} $account_id tiktok account id
     * @return {Boolean}           
     */
    public function tiktok_refresh_token($account_id){
        $account = $this->get_social_accounts($account_id);
        $config = sa_get_tiktok_config();
       
        $parameters = array(
          'grant_type' => 'refresh_token',
          'client_key' => $config['client_key'],
          'client_secret' => $config['client_secret'],
          'refresh_token' => $account->refresh_token,
        );

        $http_header = array(
             'Accept' => 'application/json',
             'Content-Type' => 'application/x-www-form-urlencoded',
        );

        $url = 'https://open.tiktokapis.com/v2/oauth/token/';

        $token_result = $this->executeRequest($url,  $parameters, $http_header, 'POST');

        $data_update = [
            'access_token' => $token_result['access_token'],
            'expires_in' => time() + $token_result['expires_in'],
            'refresh_token' => $token_result['refresh_token'],
        ];

        $this->account_connect_save($data_update, $account->id);

        return true;
    }

    /**
     * twitter refresh token
     * @param  {String} $account_id twitter account id
     * @return {Boolean}           
     */
    public function twitter_refresh_token($account_id){
        $account = $this->get_social_accounts($account_id);
        $config = sa_get_twitter_config();
       
        $parameters = array(
              'grant_type' => 'refresh_token',
              'client_id' => $config['client_id'],
              'refresh_token' => $account->refresh_token,
            );

        $http_header = array(
             'Accept' => 'application/json',
             'Content-Type' => 'application/x-www-form-urlencoded',
        );

        $url = $config['api_domain'].'/2/oauth2/token';

        $data_update = [
            'access_token' => $token_result['access_token'],
            'expires_in' => time() + $token_result['expires_in'],
            'refresh_token' => $token_result['refresh_token'],
        ];

        $this->account_connect_save($data_update, $account->id);

        return true;
    }
}

