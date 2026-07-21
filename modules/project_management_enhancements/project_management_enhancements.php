<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Project Management Enhancements
Description: Project Management Enhancements
Version: 1.1.0
Requires at least: 1.0.*
*/

const PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME = 'project_management_enhancements';

register_language_files('project_management_enhancements', ['project_management_enhancements']);

function pme_module_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__.'/install.php');
}

register_activation_hook(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME, 'pme_module_activation_hook');

function pme_module_register_uninstall_hook()
{
	if (get_option('pme_migrated_database'))
	{
		require_once APP_MODULES_PATH.'project_management_enhancements/migrations/100_version_100.php';

		$migration = new Migration_Version_100();
		$migration->down();
	}
}

register_uninstall_hook(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME, 'pme_module_register_uninstall_hook');

function app_init_required_services()
{
	require_once APP_MODULES_PATH.PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'/libraries/Zegaware_license.php';
}

hooks()->add_action('app_init', 'app_init_required_services');

function redirect_task_routes()
{
    // Never redirect POST (avoid breaking form submissions / losing payload)
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Only handle the core task modal route
    if (strpos($request_uri, '/admin/tasks/task') === false) {
        return;
    }

    // Preserve the full URI INCLUDING query string
    $new_uri = str_replace(
        '/admin/tasks/task',
        '/admin/project_management_enhancements/tasks/task',
        $request_uri
    );
  
  	log_message('error', 'PME redirect_task_routes redirecting to: ' . $new_uri);


    redirect($new_uri);
    exit();
}


hooks()->add_action('admin_init', 'redirect_task_routes');

function project_management_enhancements_add_custom_styles()
{ ?>
    <style>
        .task-view-collapse {
            top: 1rem;
            right: 1rem;
        }

        .task-view-collapse:not(.collapsed) i.fa-chevron-left {
            display: none;
        }

        .task-view-collapse.collapsed i.fa-chevron-right {
            display: none;
        }

        .task-single-col-right {
            display: none;
        }

        @media (max-width: 991px) {
            .task-view-collapse {
                display: none;
            }
        }

        @media (max-width: 991px) {
            .task-view-collapse {
                display: none;
            }

            .task-single-col-right {
                display: block;
            }
        }

        .tc-content.task-comment.active {
            background-color: #fffb006b;
        }
    </style>
	<?php
}

hooks()->add_action('app_admin_head', 'project_management_enhancements_add_custom_styles');

function project_management_enhancements_add_custom_scripts()
{
	?>
    <script>
        $(function () {
            $(document).ready(function () {
                $('body').on('click', '.task-view-collapse', function () {
                    const $parent = $(this).closest('.row');
                    $(this).toggleClass("collapsed");
                    if ($(this).hasClass("collapsed")) {
                        $parent.find('.task-single-col-left').removeClass('col-md-8').addClass('col-md-12');
                        $parent.find('.task-single-col-right').removeClass('col-md-4');
                        $parent.find('.task-single-col-right').hide();
                    } else {
                        $parent.find('.task-single-col-left').removeClass('col-md-12').addClass('col-md-8');
                        $parent.find('.task-single-col-right').addClass('col-md-4');
                        $parent.find('.task-single-col-right').show();
                    }
                })


                // Assign task to staff member
                $("body").on("change", 'select[name="select-assignees-modified"]', function () {
                    $("body").append('<div class="dt-loader"></div>');
                    var data = {};
                    data.assignee = $('select[name="select-assignees-modified"]').val();
                    if (data.assignee !== "") {
                        data.taskid = $(this).attr("data-task-id");
                        $.post(admin_url + "project_management_enhancements/tasks/add_task_assignees", data).done(function (
                            response
                        ) {
                            $("body").find(".dt-loader").remove();
                            response = JSON.parse(response);
                            reload_tasks_tables();
                            init_task_modal(data.taskid)
                        });
                    }
                });

                // Add follower to task
                $("body").on("change", 'select[name="select-followers-modified"]', function () {
                    var data = {};
                    data.follower = $('select[name="select-followers-modified"]').val();
                    if (data.follower !== "") {
                        data.taskid = $(this).attr("data-task-id");
                        $("body").append('<div class="dt-loader"></div>');
                        $.post(admin_url + "project_management_enhancements/tasks/add_task_followers", data).done(function (
                            response
                        ) {
                            response = JSON.parse(response);
                            $("body").find(".dt-loader").remove();
                            _task_append_html(response.taskHtml);
                            init_task_modal(data.taskid)
                        });
                    }
                });


            });
        });
    </script>
	<?php
}

hooks()->add_action('app_admin_footer', 'project_management_enhancements_add_custom_scripts');

function project_management_enhancements_licence_menu_item()
{
	if (is_admin())
	{
		$CI = &get_instance();

		$CI->app_menu->add_sidebar_menu_item('pme-license', [
			'name' => _l('pme_zegaware_license'),
			// The name if the item
			'href' => admin_url(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'/license'),
			// URL of the item
			'position' => 35,
			'icon' => 'fa fa-key',
			// Font awesome icon
		]);
	}
}

hooks()->add_action('admin_init', 'project_management_enhancements_licence_menu_item', 20);

// function zegaware_check_license()
// {
// 	$request_uri = $_SERVER['REQUEST_URI'];

// 	if (str_contains($request_uri, '/admin/'.PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME)
// 		|| str_contains($request_uri, '/admin/tasks'))
// 	{
// 		$is_activated = Zegaware_license::is_activated(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME);

// 		if ( ! $is_activated
// 			&& ! str_contains($request_uri, '/admin/'.PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'/license'))
// 		{
// 			redirect(admin_url(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'/license'));
// 			exit();
// 		}

// 		if ($is_activated)
// 		{
// 			$last_validate = get_option(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'_last_validate');

// 			if (empty($last_validate))
// 			{
// 				validate_zegaware_license();
// 			} else
// 			{
// 				$last_validate = json_decode($last_validate);

// 				if ( ! isset($last_validate->date) || $last_validate->date !== date('Y-m-d'))
// 				{
// 					validate_zegaware_license();
// 				}
// 			}
// 		}
// 	}
// }
function zegaware_check_license()
{
    $CI = &get_instance(); // Access the CodeIgniter instance

    // Exclude AJAX requests and API calls to prevent redirection issues
    if ($CI->input->is_ajax_request()) {
        return;
    }

    $request_uri = $_SERVER['REQUEST_URI'];
  
  	// Don't redirect the task modal endpoint at all
    if (strpos($request_uri, '/admin/project_management_enhancements/tasks/task') !== false) {
        return;
    }


    // Ensure no infinite redirect loop occurs
    if (str_contains($request_uri, '/admin/' . PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME)
        && !str_contains($request_uri, '/admin/' . PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME . '/tasks')) 
    {
        // Redirect to /tasks only if not already there
        redirect(admin_url(PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME . '/tasks'));
        exit();
    }
}



hooks()->add_action('admin_init', 'zegaware_check_license');

function validate_zegaware_license(): bool
{
    // Skip actual license validation, always return true
    return true;
}

function perfex_enhance_add_project_tab()
{
	$CI = &get_instance();

	$CI->app_tabs->add_project_tab('settings', [
		'name' => _l('settings_group_project_management_enhancements_settings'),
		'icon' => 'fa-solid fa-gear',
		'position' => 70,
		'collapse' => TRUE,
	]);


	$CI->app_tabs->add_project_tab_children_item('settings', [
		'slug' => 'task_types',
		'name' => _l('settings_group_project_management_enhancements'),
		'view' => PROJECT_MANAGEMENT_ENHANCEMENTS_MODULE_NAME.'/projects/task_types_view',
		'position' => 5,
		'icon' => 'fa-solid fa-tag',
	]);
}

hooks()->add_action('admin_init', 'perfex_enhance_add_project_tab', 11);

function add_init_data_table_for_task_types()
{
	if ( ! isset($_REQUEST['group']) || $_REQUEST['group'] != 'task_types')
	{
		return;
	}
	?>
    <script>
        $(function () {
            var project_id = $('input[name="project_id"]').val();
            initDataTable(
                ".table-project-task-types",
                admin_url + "project_management_enhancements/projects/task_types/" + project_id,
                undefined,
                undefined,
                "undefined",
                [1, "desc"]
            );


            appValidateForm(
                $("#task_type_form"),
                {
                    name: "required",
                    label_color: "required",
                    sort_order: "required",
                },
                manage_task_type
            );

            function manage_task_type(form) {
                var data = $(form).serialize();
                var url = form.action;
                $.post(url, data).done(function (response) {
                    response = JSON.parse(response);
                    if (response.success == true) {
                        alert_float("success", response.message);
                    } else if (response.message) {
                        alert_float("danger", response.message);
                    }

                    $(".table-project-task-types").DataTable().ajax.reload(null, false);
                    $("#task_type").modal("hide");
                    $("#task_type_form").find('button[type="submit"]').button("reset");
                });
                return false;
            }

        })
    </script>
	<?php
}

hooks()->add_action('app_admin_footer', 'add_init_data_table_for_task_types');

function add_project_default_task_types($project_id): void
{
	$CI = &get_instance();

	$CI->db->reset_query();

	$default_types = array(
		array(
			'name' => 'Bug',
			'color' => '#FF5861',
			'sort_order' => 1,
		),
		array(
			'name' => 'Feature',
			'color' => '#00B6FF',
			'sort_order' => 2,
		),
		array(
			'name' => 'Task',
			'color' => '#00A96E',
			'sort_order' => 3,
		),
	);

	foreach ($default_types as $type)
	{
		$query = sprintf(
			"INSERT INTO %stask_types (`name`, `label_color`, `sort_order`, `editable`) VALUES ('%s','%s',%d,1)",
			db_prefix(),
			$type['name'],
			$type['color'],
			$type['sort_order'],
		);
		$CI->db->query($query);
		$insert_id = $CI->db->insert_id();
		$CI->db->reset_query();

		$query = sprintf(
			"INSERT INTO %sproject_task_types (`project_id`, `task_type_id`) VALUES (%s,%s)",
			db_prefix(),
			$project_id,
			$insert_id
		);
		$CI->db->query($query);

		$CI->db->reset_query();
	}
}

hooks()->add_action('after_add_project', 'add_project_default_task_types');