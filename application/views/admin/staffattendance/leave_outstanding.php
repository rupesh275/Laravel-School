<style type="text/css">
    .radio {
        padding-left: 20px;
    }

    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px;
    }

    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid #cccccc;
        border-radius: 50%;
        background-color: #fff;
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out;
    }

    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
    }

    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1;
    }

    .radio input[type="radio"]:focus+label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }

    .radio input[type="radio"]:checked+label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    .radio input[type="radio"]:disabled+label {
        opacity: 0.65;
    }

    .radio input[type="radio"]:disabled+label::before {
        cursor: not-allowed;
    }

    .radio.radio-inline {
        margin-top: 0;
    }

    .radio-primary input[type="radio"]+label::after {
        background-color: #337ab7;
    }

    .radio-primary input[type="radio"]:checked+label::before {
        border-color: #337ab7;
    }

    .radio-primary input[type="radio"]:checked+label::after {
        background-color: #337ab7;
    }

    .radio-danger input[type="radio"]+label::after {
        background-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::before {
        border-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::after {
        background-color: #d9534f;
    }

    .radio-info input[type="radio"]+label::after {
        background-color: #5bc0de;
    }

    .radio-info input[type="radio"]:checked+label::before {
        border-color: #5bc0de;
    }

    .radio-info input[type="radio"]:checked+label::after {
        background-color: #5bc0de;
    }

    @media (max-width:767px) {
        .radio.radio-inline {
            display: inherit;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/staffattendance/leave_outstanding') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
                            if ($this->session->flashdata('msg')) {

                                echo $this->session->flashdata('msg');
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line("department"); ?>
                                        </label>
                                        <select autofocus=""  id="role" name="role" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($department as $key => $class) {

                                                if (isset($_POST["role"])) {
                                                    $role_selected = $_POST["role"];
                                                } else {
                                                    $role_selected = '';
                                                }
                                            ?>
                                                <option value="<?php echo $class["id"] ?>" <?php
                                                                                                if ($class["id"] == $role_selected) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?php print_r($class["department_name"]) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (!empty($resultlist)) {
                    ?>
                        <form action="<?php echo site_url('admin/staffattendance/leave_outstanding') ?>" id="save_attendance" method="post">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('list'); ?></h3>
                                <div class="box-tools pull-right">
                                    <div class="pull-right">
                                        <?php if ($this->rbac->hasPrivilege('leave_outstanding', 'can_view')) { ?>
                                            <!-- <button type="submit" name="search" value="saveattendence" class="btn btn-primary btn-sm pull-right checkbox-toggle" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save_attendance'); ?>"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button> -->
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">

                                <?php echo $this->customlib->getCSRF(); ?>
                                <div>

                                </div>
                                <div class="mailbox-controls">
                                    <span class="button-checkbox">
                                        <!-- <button type="button" class="btn btn-sm btn-primary" data-color="primary"><?php //echo $this->lang->line('mark_as_holiday'); 
                                                                                                                        ?></button>
                                            <input type="checkbox" id="checkbox1" class="hidden" name="holiday" value="checked" <?php //echo $checked; 
                                                                                                                                ?>/> -->
                                    </span>
                                </div>
                                <br>
                                <input type="hidden" name="role" value="<?php echo $user_type_id; ?>">
                                <input type="hidden" name="section_id" value="">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo "CL"; ?></th>
                                                <th><?php echo "EL"; ?></th>
                                                <th><?php echo "ML"; ?></th>
                                                <th><?php echo "SL"; ?></th>
                                                <th><?php echo "Com-Off"; ?></th>
                                                <th><?php echo "LWP"; ?></th>
                                                <!-- <th><?php //echo "Balance"; ?></th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $row_count = 1;
                                            foreach ($resultlist as $key => $value) {
                                                $update = $this->staffattendancemodel->getMonthAttendenceByStaffId($value['staff_id']);
                                                $cl_leave = $this->leavetypes_model->getleaveTypeByType('CL');
                                                $el_leave = $this->leavetypes_model->getleaveTypeByType('EL');
                                                $ml_leave = $this->leavetypes_model->getleaveTypeByType('ML');
                                                $sl_leave = $this->leavetypes_model->getleaveTypeByType('SL');
                                                $lwp_leave = $this->leavetypes_model->getleaveTypeByType('LWP');
                                                $comp_off = $this->leavetypes_model->getleaveTypeByType('C/Off');
                                                $cl = $cl_leave['id']; // casual leave
                                                $el = $el_leave['id']; // earned leave
                                                $lwp = $lwp_leave['id']; // work from home
                                                $ml = $ml_leave['id']; // medical leave
                                                $sl = $sl_leave['id']; // sick leave
                                                $comp_off = $comp_off['id']; // sick leave
                                                $cl_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$cl);
                                                $el_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$el);
                                                $lwp_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$lwp);
                                                $ml_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$ml);
                                                $sl_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$sl);
                                                $comp_off_balance = $this->staffattendancemodel->getStaffLeavebalanceByLeave($value['staff_id'],$comp_off);

                                                // $leave_balance = array_column($leave_balance,'total');
                                                // $bal = array_sum($leave_balance);
                                                // $total = $bal - $update['lwp'] - $update['comp_off']-$update['ml']-$update['sl']-$update['el']-$update['cl'];
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="staff_id[]" value="<?php echo $value['staff_id']; ?>">
                                                        <?php echo $row_count; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $value['employee_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $value['name'] . " " . $value['surname']; ?>
                                                    </td>
                                                    <td><?php echo $cl_balance - $update['cl'] > 0 ? $cl_balance - $update['cl']:0;?></td>
                                                    <td><?php echo $el_balance - $update['el'] > 0 ? $el_balance - $update['el']:0;?></td>
                                                    <td><?php echo $ml_balance - $update['ml'] > 0 ? $ml_balance - $update['ml']:0;?></td>
                                                    <td><?php echo $sl_balance - $update['sl'] > 0 ? $sl_balance - $update['sl']:0;?></td>
                                                    <td><?php echo $comp_off_balance - $update['comp_off']  > 0 ? $comp_off_balance - $update['comp_off']:0;?></td>
                                                    <td><?php echo $lwp_balance - $update['lwp'] > 0 ? $lwp_balance - $update['lwp']:0;?></td>
                                                    <!-- <td><?php //echo $total;?></td> -->
                                                </tr>
                                            <?php
                                                $row_count++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                        </form>
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php

            ?>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {
       
    });
</script>