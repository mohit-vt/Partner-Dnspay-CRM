<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="col-md-10 row col-md-offset-1 mbot15">
    <div class="col-md-12 text-center">
        <h1 class="tw-font-bold register-heading">
            Agent Register
        </h1>
    </div>
</div>

<div class="col-md-10 col-md-offset-1">
    <?= form_open('authentication/agent_register', ['id' => 'agent-register-form']); ?>

    <div class="panel_s">
        <div class="panel-body">
            <div class="row">

                <div class="col-md-6">
                    <h4 class="bold">Primary Contact Information</h4>

                    <div class="form-group mtop15">
                        <label><span class="text-danger">*</span> First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= set_value('firstname'); ?>">
                        <?= form_error('firstname'); ?>
                    </div>

                    <div class="form-group">
                        <label><span class="text-danger">*</span> Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= set_value('lastname'); ?>">
                        <?= form_error('lastname'); ?>
                    </div>

                    <div class="form-group">
                        <label><span class="text-danger">*</span> Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= set_value('email'); ?>">
                        <?= form_error('email'); ?>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= set_value('phone'); ?>">
                    </div>

                    <div class="form-group">
                        <label><span class="text-danger">*</span> Password</label>
                        <input type="password" name="password" class="form-control">
                        <?= form_error('password'); ?>
                    </div>

                    <div class="form-group">
                        <label><span class="text-danger">*</span> Repeat Password</label>
                        <input type="password" name="passwordr" class="form-control">
                        <?= form_error('passwordr'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="bold">Company Information</h4>

                    <div class="form-group mtop15">
                        <label>Company</label>
                        <input type="text" name="company" class="form-control" value="<?= set_value('company'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Role Position</label>
                        <input type="text" name="title" class="form-control" value="<?= set_value('title'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" class="form-control" value="<?= set_value('website'); ?>">
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="<?= set_value('city'); ?>">
                    </div>

                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="<?= set_value('state'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= set_value('address'); ?>">
                    </div>
                </div>

            </div>
        </div>

        <div class="panel-footer text-right">
            <button type="submit" class="btn btn-primary">
                Register
            </button>
        </div>
    </div>

    <?= form_close(); ?>
</div>