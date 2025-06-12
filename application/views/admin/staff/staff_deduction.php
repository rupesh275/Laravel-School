<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <small class="pull-right"></small>
                        </div>
                    </div>
                    <?php
                    // echo "<pre>";
                    // print_r ($update);
                    // echo "</pre>";
                    ?>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <?php echo $this->session->flashdata('msg');unset($_SESSION['msg']); ?> <?php } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/staff/staff_deduction_validation') ?>" method="post" class="">
                                <?php //echo $this->customlib->getCSRF(); 
                                ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Staff List </label><small class="req"> *</small>
                                        <select name="staff_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($resultlist as  $staff) {
                                            ?>
                                                <option value="<?php echo $staff['id']; ?>" <?php echo set_select('staff_id', $staff['id'], (!empty($update['staff_id']) && $update['staff_id'] == $staff['id'] ? TRUE : FALSE)) ?>><?php echo $staff['name'] . " " . $staff['middle_name']." ".$staff['surname']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('staff_list'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Date</label><small class="req"> *</small>
                                        <input type="text" name="ded_date" id="ded_date" value="<?php echo set_value('ded_date', !empty($update['ded_date']) ? date('d-m-Y', strtotime($update['ded_date'])) : date('d-m-Y')) ?> " class="form-control date">
                                        <span class="text-danger"><?php echo form_error('ded_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Amount</label><small class="req"> *</small>
                                        <input type="text" name="ded_amount" id="ded_amount" value="<?php echo set_value('ded_amount', !empty($update['ded_amount']) ? $update['ded_amount'] : ""); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('ded_amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type</label><small class="req"> *</small>
                                        <select name="ded_type" id="ded_type" class="form-control">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php if (!empty($deductionArr)) {
                                                foreach ($deductionArr as  $deductRow) {
                                            ?>
                                                    <option value="<?php echo $deductRow['id']; ?>" <?php echo set_select('ded_type', $deductRow['id'], (!empty($update['ded_type']) && $update['ded_type'] == $deductRow['id'] ? TRUE : FALSE)) ?>> <?php echo $deductRow['name']; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('ded_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label><small class="req"> *</small>
                                        <textarea name="remarks" id="remarks" class="form-control"> <?php echo set_value('remarks', !empty($update['remarks']) ? $update['remarks'] : ""); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('remarks'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="id" value="<?php echo !empty($update['id']) ? $update['id'] : "" ?>">
                                    <button type="submit" class="btn btn-info" autocomplete="off">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Staff Deduction List"; ?></h3>
                    </div>
                    <div class="box-body">
                    <form id="form1" action="<?php echo base_url(); ?>admin/staff/staff_deduction" method="post" accept-charset="utf-8">
                        <div class="box-body">
                                                            <div class="row">


                                <input type="hidden" name="ci_csrf_token" value="">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Month</label>

                                        <select autofocus="" id="class_id" name="month" class="form-control">
                                            <option value="select">Select</option>
                                            <?php
                                            if (isset($month)) {
                                                $month_selected = date("F", strtotime($month));
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
                                            }
                                            ?>
                                            
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Year</label>

                                        <select autofocus="" id="class_id" name="year" class="form-control">
                                            <option value="select">Select</option>
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
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </form>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Staff Loan List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('staff') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo "Date"; ?></th>
                                        <th><?php echo  $this->lang->line('amount'); ?></th>
                                        <th><?php echo "Type"; ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($stafflist)) {
                                        foreach ($stafflist as $staffRow) {
                                    ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $staffRow['name'] . " " . $staffRow['surname'] ?></td>
                                                <td class="mailbox-name"><?php echo date('d-m-Y', strtotime($staffRow['ded_date'])) ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['ded_amount'] ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['deduction_name'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('staff_deduction', 'can_edit')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/staff_deduction/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('staff_deduction', 'can_delete')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/delete_deduction/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(e) {
        $(".select2").select2();
    });
</script>