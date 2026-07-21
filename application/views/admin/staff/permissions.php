<div class="table-responsive">
    <table class="table table-bordered roles no-margin">
        <thead>
            <tr>
                <th><?= _l('features') ?></th>
                <th><?= _l('capabilities') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($member)) {
                $is_admin = is_admin($member->staffid);
            }

            // Define role name function once
            if (!function_exists('get_role_name_by_id')) {
                function get_role_name_by_id($roleid) {
                    $CI =& get_instance();
                    $CI->db->select('name');
                    $CI->db->where('roleid', $roleid);
                    $query = $CI->db->get(db_prefix() . 'roles');
                    return $query->num_rows() > 0 ? $query->row()->name : '';
                }
            }

            foreach (get_available_staff_permissions(null) as $feature => $permission) { ?>
                <tr data-name="<?= e($feature); ?>">
                    <td><b><?= e($permission['name']); ?></b></td>
                    <td>
                        <?php if (isset($permission['before'])) echo $permission['before']; ?>

                        <?php foreach ($permission['capabilities'] as $capability => $name) { 
                            $checked  = '';
                            $disabled = ''; // always enabled

                            // Determine if the permission should be pre-checked
                            if (
                                (isset($member) && staff_can($capability, $feature, $member->staffid)) ||
                                (isset($role) && has_role_permission($role->roleid, $capability, $feature))
                            ) {
                                $checked = ' checked ';
                            }

                            // Tooltip & other attributes
                            $is_view = $capability == 'view' ? 'data-can-view' : '';
                            $is_view_own = $capability == 'view_own' ? 'data-can-view-own' : '';
                            $not_applicable = (is_array($name) && isset($name['not_applicable']) && $name['not_applicable']) ? 'data-not-applicable="true"' : '';
                        ?>
                            <div class="tw-ml-2">
                                <div class="checkbox last:tw-mb-0">
                                    <input
                                        type="checkbox"
                                        class="capability"
                                        id="<?= $feature . '_' . $capability; ?>"
                                        name="permissions[<?= e($feature); ?>][]"
                                        value="<?= e($capability); ?>"
                                        <?= $is_view; ?>
                                        <?= $is_view_own; ?>
                                        <?= $not_applicable; ?>
                                        <?= $checked; ?>
                                        <?= $disabled; ?>>
                                    <label for="<?= $feature . '_' . $capability; ?>">
                                        <?= !is_array($name) ? $name : $name['name']; ?>
                                    </label>
                                    <?php
                                    if (isset($permission['help']) && array_key_exists($capability, $permission['help'])) {
                                        echo '<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="' . e($permission['help'][$capability]) . '"></i>';
                                    } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isset($permission['after'])) echo $permission['after']; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
