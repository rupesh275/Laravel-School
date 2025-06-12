<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">   

            <?php if (($this->rbac->hasPrivilege('leave_types', 'can_add'))) { ?>
                <div class="col-md-4">    
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title." of ".$leave_types_name; ?></h3>
                        </div> 
                        <form id="form1" action="<?php echo site_url('admin/leavetypes/leavetype_category/'.$leave_type_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>        
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('category'); ?></label><small class="req"> *</small>
                                    <select name="payroll_category" id="payroll_category" class="form-control">
                                        <?php if (!empty($payroll_category)) {
                                            foreach ($payroll_category as  $value) {
                                                ?>
                                                <option value="<?php echo $value["id"] ?>" <?php
                                                if (isset($update)) {
                                                    if ($update["payroll_category"] == $value["id"]) {
                                                        echo "selected";
                                                    }
                                                }
                                                ?>><?php echo $value["category_name"] ?></option>
                                                        <?php
                                            }
                                        }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('payroll_category'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('gender'); ?></label><small class="req"> </small>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value=""></option>
                                        <option value="1" <?php if (isset($update) && $update["gender"] == "1") {  echo "selected"; }  ?>>Male</option>
                                        <option value="2" <?php if (isset($update) && $update["gender"] == "2") {  echo "selected"; }  ?>>Female</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('count'); ?></label><small class="req"> </small>
                                    <input autofocus="" id="qty"  name="qty" placeholder="" type="text" class="form-control"  value="<?php if (isset($update)) { echo $update['qty']; } ?>" />
                                    <span class="text-danger"><?php echo form_error('qty'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('period')." ".$this->lang->line('type'); ?></label><small class="req"> </small>
                                    <select name="period_type" id="period_type" class="form-control">
                                        <option value="1" <?php if (isset($update) && $update["period_type"] == "1") { echo "selected"; } ?>>Days</option>
                                        <option value="2" <?php if (isset($update) && $update["period_type"] == "2") { echo "selected"; } ?>>Month</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('period_type'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('period'); ?></label><small class="req"> </small>
                                    <input autofocus="" id="period"  name="period" placeholder="" type="text" class="form-control"  value="<?php if (isset($update)) { echo $update['period']; } ?>" />
                                    <span class="text-danger"><?php echo form_error('period'); ?></span>
                                </div>

                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="id" id="id" value="<?php if (isset($update)) { echo $update['id']; } ?>"/>
                                <input type="hidden" name="leave_type_id" id="leave_type_id" value="<?php echo $leave_type_id; ?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>   
                </div>  
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('leave_types', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('leave_type')." ".$this->lang->line('category'); ?> <?php echo $this->lang->line('list')." of ".$leave_types_name; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('leave_type') . " ".$this->lang->line('category') . $this->lang->line('list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?> </th>
                                        <th><?php echo $this->lang->line('quantity'); ?> </th>
                                        <th><?php echo $this->lang->line('period')." ".$this->lang->line('type'); ?> </th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($resultlist)) {
                                    foreach ($resultlist as $value) {
                                       $gender = "";
                                       if($value['gender'] == 1){
                                           $gender = "Male";
                                       }else if($value['gender'] == 2){
                                           $gender = "Female";
                                       }

                                       $period_type = "";
                                       if($value['period_type'] == 1){ 
                                           $period_type = "Days";
                                       }else if($value['period_type'] == 2){
                                           $period_type = "Month";
                                       }
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $value['type'] ?></td>
                                            <td class="mailbox-name"> <?php echo $value['category_name'] ?></td>
                                            <td class="mailbox-name"> <?php echo $gender ?></td>
                                            <td class="mailbox-name"> <?php echo $value['qty'] ?></td>
                                            <td class="mailbox-name"> <?php echo $period_type ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php if ($this->rbac->hasPrivilege('leave_types', 'can_edit')) { ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/leavetypes/leavetype_category/<?php echo $leave_type_id . "/" . $value['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } if ($this->rbac->hasPrivilege('leave_types', 'can_delete')) { ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/leavetypes/leavetype_category_delete/<?php echo $value['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;   
                                }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div> 

        </div>
    </section>
</div>

