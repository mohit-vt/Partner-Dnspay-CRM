<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Social analytic client Controller
 */
class Social_analytic_client extends ClientsController
{  
    /**
     * [overview_analytics]
     */
    public function overview_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('overview_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/overview');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * [facebook_analytics]
     */
    public function facebook_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('facebook_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/facebook');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }
    
    /**
     * [instagram_analytics]
     */
    public function instagram_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('instagram_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/instagram');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * [tiktok_analytics]
     */
    public function tiktok_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('tiktok_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/tiktok');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * [twitter_analytics]
     */
    public function twitter_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('twitter_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/twitter');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * [youtube_analytics]
     */
    public function youtube_analytics()
    {
        if(is_client_logged_in()){
            $this->load->model('social_analytic_model');
            $data['title'] = _l('youtube_analytics');
            $data['base_workspace'] = $this->social_analytic_model->get_workspace(sa_get_contact_base_workspace_id());

            $this->data($data);

            $this->view('clients/youtube');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * [get_data_analytics ]
     */
    public function get_data_analytics(){
        $this->load->model('social_analytic_model');
        $data_filter = $this->input->post();
        $data = [];
        switch ($data_filter['type']) {
            case 'engagement_vs_total_posts_published':
                $data['engagement_vs_total_posts_published'] = $this->social_analytic_model->get_data_engagement_vs_total_posts_published($data_filter);
                echo json_encode($data);
                break;
            case 'daily_post_impression_trend':
                $data['daily_post_impression_trend'] = $this->social_analytic_model->get_data_daily_post_impression_trend_chart($data_filter);
                echo json_encode($data);
                break;
            case 'audience_growth':
                $data['audience_growth'] = $this->social_analytic_model->get_data_audience_growth_chart($data_filter);
                echo json_encode($data);
                break;
            case 'published_posts_with_engagement':
                $data['published_posts_with_engagement'] = $this->social_analytic_model->get_data_published_posts_with_engagement($data_filter);
                echo json_encode($data);
                break;
            case 'post_rate':
                $data['post_rate'] = $this->social_analytic_model->get_data_post_rate($data_filter);
                echo json_encode($data);
                break;
            case 'post_density_daily':
                $data['post_density_daily'] = $this->social_analytic_model->get_data_post_density_daily($data_filter);
                echo json_encode($data);
                break;
            case 'engagement_rate':
                $data['engagement_rate'] = $this->social_analytic_model->get_data_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'top_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $data_filter['account_ids'] = $account_ids;
                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;

                $this->load->view('analytics/'.$data_filter['social'].'/top_stats', $data_filter);
                break;
            case 'post_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] = $this->load->view('analytics/'.$data_filter['social'].'/post_stats', $data_filter, true);
                if($data_filter['social'] == 'facebook'){
                    $data['post_by_type_chart'] = $this->social_analytic_model->get_data_post_by_type_chart($data_filter);
                }
                echo json_encode($data);
                break;
            case 'engagement_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/engagement_stats', $data_filter, true);
                
                if($data_filter['social'] == 'twitter'){

                $data['engagement_rate_pie_chart'] = $this->social_analytic_model->get_data_twitter_engagement_rate_pie_chart($data_filter);
                }else{

                $data['engagement_rate_pie_chart'] = $this->social_analytic_model->get_data_engagement_rate_pie_chart($data_filter);
                }
                
                echo json_encode($data);
                break;
            case 'fan_by_age':
                $data['fan_by_age'] = $this->social_analytic_model->get_data_fan_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'fan_by_gender':
                $data['fan_by_gender'] = $this->social_analytic_model->get_data_fan_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'fan_stats':
                $data = $this->social_analytic_model->get_data_fan_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/fan_stats', $data);
                break;
            case 'reactions_overview':
                $data['reactions_overview'] = $this->social_analytic_model->get_data_reactions_overview_chart($data_filter);
                echo json_encode($data);
                break;
            case 'engagement_by_day_time':
                $data['engagement_by_day_time'] = $this->social_analytic_model->get_data_engagement_by_day_time_chart($data_filter);
                echo json_encode($data);
                break;
            case 'publishing_behavior':
                $data['publishing_behavior'] = $this->social_analytic_model->get_data_publishing_behavior_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_hours':
                $data['active_users_by_hours'] = $this->social_analytic_model->get_data_active_users_by_hours_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_days':
                $data['active_users_by_days'] = $this->social_analytic_model->get_data_active_users_by_days_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_impressions':
                $data['instagram_impressions'] = $this->social_analytic_model->get_data_instagram_impressions_chart($data_filter);
                echo json_encode($data);
                break;
            case 'impressions_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $this->load->view('analytics/'.$data_filter['social'].'/impressions_stats', $data_filter);
                break;
            case 'instagram_engagement':
                $data['instagram_engagement'] = $this->social_analytic_model->get_data_instagram_engagement_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_stories_performance':
                $data['instagram_stories_performance'] = $this->social_analytic_model->get_data_instagram_stories_performance_chart($data_filter);
                echo json_encode($data);
                break;
            case 'stories_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $this->load->view('analytics/'.$data_filter['social'].'/stories_stats', $data_filter);
                break;
            case 'stories_performance':
                $data['stories_performance'] = $this->social_analytic_model->get_data_stories_performance_chart($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_engagement_rate':
                $data['twitter_engagement_rate'] = $this->social_analytic_model->get_data_twitter_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_audience_growth':
                $data['twitter_audience_growth'] = $this->social_analytic_model->get_data_twitter_audience_growth_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follow_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $data['follow_rate_pie_chart'] = $this->social_analytic_model->get_data_follow_rate_pie_chart($data_filter);
                $data_filter['account_ids'] = $account_ids;
                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/follow_stats', $data_filter, true);
                
                
                echo json_encode($data);
                break;
            case 'awareness_through_mention':
                $data['awareness_through_mention'] = $this->social_analytic_model->get_data_awareness_through_mention_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_chart':
                $data['follower_chart'] = $this->social_analytic_model->get_data_follower_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_follow_stats':
                
                $data_stats = $this->social_analytic_model->get_data_follower_stats($data_filter);
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/follow_stats', $data_stats, true);
                
                $data['follower_gender_pie_chart'] = $this->social_analytic_model->get_data_tiktok_follower_gender_pie_chart($data_filter);
                
                echo json_encode($data);
                break;
            case 'video_views_chart':
                $data['video_views_chart'] = $this->social_analytic_model->get_data_video_views_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_engagement_rate':
                $data['tiktok_engagement_rate'] = $this->social_analytic_model->get_data_tiktok_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'posting_pattern_engagement_analysis':
                $data['posting_pattern_engagement_analysis'] = $this->social_analytic_model->get_data_posting_pattern_engagement_analysis($data_filter);
                echo json_encode($data);
                break;
            case 'gained_lost_chart':
                $data['gained_lost_chart'] = $this->social_analytic_model->get_data_gained_lost_chart($data_filter);
                echo json_encode($data);
                break;
            case 'daily_video_views_graph':
                $data['daily_video_views_graph'] = $this->social_analytic_model->get_data_daily_video_views_graph($data_filter);
                echo json_encode($data);
                break;
            case 'estimated_minutes_watched':
                $data['estimated_minutes_watched'] = $this->social_analytic_model->get_data_estimated_minutes_watched($data_filter);
                echo json_encode($data);
                break;
            case 'videos_published':
                $data['videos_published'] = $this->social_analytic_model->get_data_videos_published($data_filter);
                echo json_encode($data);
                break;
            case 'like_vs_dislike_chart':
                $data['like_vs_dislike_chart'] = $this->social_analytic_model->get_data_like_vs_dislike_chart($data_filter);
                echo json_encode($data);
                break;
            case 'comments_chart':
                $data['comments_chart'] = $this->social_analytic_model->get_data_comments_chart($data_filter);
                echo json_encode($data);
                break;
            case 'shares_chart':
                $data['shares_chart'] = $this->social_analytic_model->get_data_shares_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscribers_by_age':
                $data['subscribers_by_age'] = $this->social_analytic_model->get_data_subscriber_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscribers_by_gender':
                $data['subscribers_by_gender'] = $this->social_analytic_model->get_data_subscriber_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscriber_stats':
                $data = $this->social_analytic_model->get_data_subscriber_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/subscriber_stats', $data);
                break;
            case 'viewer_stats':
                $data = $this->social_analytic_model->get_data_view_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/viewer_stats', $data);
                break;
            case 'viewer_by_age':
                $data['viewer_by_age'] = $this->social_analytic_model->get_data_viewer_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'viewer_by_gender':
                $data['viewer_by_gender'] = $this->social_analytic_model->get_data_viewer_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'viewer_by_device':
                $data['viewer_by_device'] = $this->social_analytic_model->get_data_viewer_by_device_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_day':
                $data['active_users_by_day'] = $this->social_analytic_model->get_data_active_users_by_day($data_filter);
                echo json_encode($data);
                break;
            case 'follower_by_age':
                $data['follower_by_age'] = $this->social_analytic_model->get_data_follower_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_by_gender':
                $data['follower_by_gender'] = $this->social_analytic_model->get_data_follower_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_stats':
                $data = $this->social_analytic_model->get_data_follower_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/follower_stats', $data);
                break;
            case 'daily_video_views_chart':
                $data['daily_video_views_chart'] = $this->social_analytic_model->get_data_daily_video_views_chart($data_filter);
                echo json_encode($data);
                break;
            case 'account_performance':
                $data['account_performance'] = $this->social_analytic_model->get_data_account_performance($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_engagement':
                $data['twitter_engagement'] = $this->social_analytic_model->get_data_twitter_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'facebook_engagement':
                $data['facebook_engagement'] = $this->social_analytic_model->get_data_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_engagement':
                $data['tiktok_engagement'] = $this->social_analytic_model->get_data_tiktok_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'youtube_engagement':
                $data['youtube_engagement'] = $this->social_analytic_model->get_data_youtube_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_engagement_pie_chart':
                $data['instagram_engagement'] = $this->social_analytic_model->get_data_instagram_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            default:
                // code...
                break;
        }

    }

    /**
     * [set_contact_default_workspace]
     * @param [integer] $workspace_id workspace id
     */
    public function set_contact_default_workspace($workspace_id){
        $this->load->model('social_analytic_model');
        $message = '';
        $success = $this->social_analytic_model->set_contact_default_workspace($workspace_id);
        if ($success) {
            $message = _l('updated_successfully', _l('workspace'));
        }

        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }
}
