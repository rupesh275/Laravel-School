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
                            <a href="<?php echo base_url('admin/staffattendance/excel') ?>" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> <?php echo "Excel";?></a>
                        </div>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/staffattendance/staff_percent_days') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
                            if ($this->session->flashdata('msg')) {

                                echo $this->session->flashdata('msg');unset($_SESSION['msg']);
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
                                                    $role_selected = '5';
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
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('attendance'); ?>
                                            <?php echo $this->lang->line('month'); ?>
                                        </label>
                                        <?php
                                        /*
                                        <select id="month" name="month" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            date_default_timezone_set('America/New_York');
                                            for ($i = 1; $i <= 12; $i++) {
                                                $month = date('F', mktime(0, 0, 0, $i, 10));
                                            ?>
                                                <option value="<?php echo $month; ?>" <?php if (set_value('month') == $month) {
                                                                                            echo "selected=selected";
                                                                                        }
                                                                                        ?>><?php echo $month; ?></option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        */ ?>
                                        <input type="month" id="month" name = "month" >
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month') ?></label>

                                        <select autofocus="" id="month" name="month" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            if (isset($selectedmonth)) {
                                                $month_selected = date("F", strtotime($selectedmonth));
                                            } else {
                                                $month_selected = date("F", strtotime("-1 month"));
                                            }
                                            foreach ($monthlist as $m_key => $month_value) {
                                            ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                                                        if ($month_selected == $m_key) {
                                                                                            echo "selected =selected";
                                                                                        }
                                                                                        ?>><?php echo $month_value; ?></option>
                                            <?php
                                                // $count++;
                                            }
                                            ?>

                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>

                                        <select autofocus="" id="year" name="year" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <option <?php
                                                    if ($year == date("Y", strtotime("-1 year"))) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y", strtotime("-1 year")) ?>"><?php echo date("Y", strtotime("-1 year")) ?></option>
                                            <option <?php
                                                    if ($year == date("Y")) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y") ?>"><?php echo date("Y") ?></option>
                                        </select>

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
                    if (!empty($resultlist) && !empty($selectedmonth)) {
                    ?>
                        <form action="<?php echo site_url('admin/staffattendance/staff_percent_days') ?>" id="save_attendance" method="post">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('list'); ?></h3>
                                <div class="box-tools pull-right">
                                    <div class="pull-right">
                                        <?php if ($this->rbac->hasPrivilege('staff_percent_days', 'can_view')) { ?>
                                            <button type="submit" name="search" value="saveattendence" class="btn btn-primary btn-sm pull-right checkbox-toggle" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo "Save"; ?>"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button>
                                        <?php } ?>
                                        <!-- <?php if ($this->rbac->hasPrivilege('staff_percent_days', 'can_delete')) { ?>
                                            <button type="button" style="margin-right: 5px;" class="btn btn-primary btn-sm pull-right delete_attendance checkbox-toggle" data-month="<?php echo $selectedmonth; ?>" data-year="<?php echo $year; ?>" ><?php echo "Delete Attendence"; ?> </button>
                                        <?php } ?> -->
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
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo $this->lang->line('role'); ?></th>
                                                <th><?php echo $this->lang->line('month'); ?></th>
                                                <th><?php echo "Present Day"; ?></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $row_count = 1;
                                            $monthlyDays = $this->staffattendancemodel->getMonthlyDays($selectedmonth);
                                            foreach ($resultlist as $key => $value) {
                                                $update = $this->staffattendancemodel->getStaff_percent_days($value['staff_id'], $selectedmonth,$selectedyear);
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="staff_id[]" value="<?php echo $value['staff_id']; ?>">
                                                        <input type="hidden" name="id[]" value="<?php echo !empty($update['id']) ? $update['id'] : ""; ?>">
                                                        <input type="hidden" value="<?php echo $selectedmonth ?>" name="selectedmonth">
                                                        <input type="hidden" value="<?php echo $selectedyear ?>" name="selectedyear">
                                                        <?php echo $row_count; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $value['employee_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $value['name'] . " " . $value['surname']; ?>
                                                    </td>
                                                    <td><?php echo $value['user_type']; ?></td>
                                                    <td><?php
                                                        if (!empty($selectedmonth)) {
                                                            $selectedmonth = $selectedmonth;
                                                        } elseif (!empty($update['month'])) {
                                                            $selectedmonth = $update['month'];
                                                        } else {
                                                            $selectedmonth = "";
                                                        } ?>
                                                        <?php echo $selectedmonth; ?>
                                                    </td>
                                                    <?php
                                                    $month_de = date('m', strtotime($selectedmonth));
                                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month_de, $selectedyear);
                                                    $percent_days = 0;
                                                    ?>
                                                    <td><input type="text" style="width: 83px" data-count="<?php echo $row_count;?>" class="percent_days" id="percent_days<?php echo $row_count; ?>"  name="percent_days[]" value="<?php echo  !empty($update['percent_days']) ? $update['percent_days'] : $percent_days; ?>"></td>
                                                    
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
        $(".total_working_days,.cl,.el,.ml,.lwp,.total_attendence,.comp_off").on("keyup change", function(e) {
            var id = $(this).data('count');
            att_total(id);
        });

        function att_total(id) {
            var total_working_days = $("#total_working_days" + id).val();
            var cl = $("#cl" + id).val() || 0;
            var el = $("#el" + id).val() || 0;
            var ml = $("#ml" + id).val() || 0;
            var lwp = $("#lwp" + id).val() || 0;
            var comp_off = $("#comp_off" + id).val() || 0;
            var totaldays = parseFloat(total_working_days) - (parseFloat(lwp));

            $("#total_attendence" + id).val(totaldays);
        }

        $(document).on('click', '.delete_attendance', function () {
            var month = $(this).data('month');
            var year = $(this).data('year');
            
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('admin/staffattendance/delete_monthly_attendence'); ?>',
                data: {'month': month, 'year': year},
                success: function (data) {
                    successMsg("Deleted successfully");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            });
        });
    });
</script>