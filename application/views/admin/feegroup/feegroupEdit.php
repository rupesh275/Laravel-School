<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('fees_group', 'can_add') || $this->rbac->hasPrivilege('fees_group', 'can_edit')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_fees_group'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <form action="<?php echo site_url("admin/feegroup/edit/" . $id) ?>" id="form1" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">

                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="id" type="hidden" class="form-control" value="<?php echo set_value('id', $feegroup['id']); ?>" />

                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                            <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $feegroup['name']); ?>" />
                                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo "Display " . $this->lang->line('name'); ?></label> <small class="req">*</small>
                                            <input autofocus="" id="dis_name" name="dis_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('dis_name', $feegroup['dis_name']); ?>" />
                                            <span class="text-danger"><?php echo form_error('dis_name'); ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fees') . " " . $this->lang->line('type'); ?></label> <small class="req">*</small>
                                            <select name="fees_type" id="fees_type" class="form-control">
                                        <?php  if ($userdata['role_id'] == 10 && $userdata['user_type'] == "Bus Operator" ) { ?>
                                            <option <?php echo set_select('fees_type', 'b', $feegroup['fees_type'] == 'b' ? true : false);?>value="b">Bus Fees</option>
                                        <?php }else{?>
                                                <option <?php echo set_select('fees_type', 'm', $feegroup['fees_type'] == 'm' ? true : false); ?> value="m">Main Fees</option>
                                        <option <?php echo set_select('fees_type', 'o', $feegroup['fees_type'] == 'o' ? true : false);?>value="o">Other Fees</option>
                                        <option <?php echo set_select('fees_type', 'b', $feegroup['fees_type'] == 'b' ? true : false);?>value="b">Bus Fees</option>
                                        <?php }?>
                                            </select>
                                            <!-- <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" /> -->
                                            <span class="text-danger"><?php echo form_error('fees_type'); ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                            <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?><?php echo set_value('description', $feegroup['description']) ?></textarea>
                                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('due_date'); ?></label>
                                            <input id="due_date" name="due_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('due_date', $this->customlib->dateformat($feegroup['due_date'])); ?>" />
                                            <span class="text-danger"><?php echo form_error('due_date'); ?></span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="input-type"><?php echo $this->lang->line('fine') . " " . $this->lang->line('type') . $feegroup_type->fine_type;  ?></label>
                                                <div id="input-type" class="row">
                                                    <div class="col-sm-3">
                                                        <label class="radio-inline">

                                                            <input name="account_type" class="finetype" id="input-type-student" value="none" type="radio" <?php echo set_radio('account_type', 'none', (set_value('none', $feegroup['fine_type']) == "none") ? TRUE : FALSE); ?> /><?php echo $this->lang->line('none') ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-student" value="percentage" type="radio" <?php echo set_radio('account_type', 'percentage', (set_value('percentage', $feegroup['fine_type']) == "percentage") ? TRUE : FALSE); ?> /><?php echo $this->lang->line('percentage') ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-tutor" value="fix" type="radio" <?php echo set_radio('account_type', 'fix', (set_value('fix', $feegroup['fine_type']) == "fix") ? TRUE : FALSE); ?> /><?php echo $this->lang->line('fix') . " " . $this->lang->line('amount') ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-tutor" value="perday" type="radio" <?php echo set_radio('account_type', 'perday', (set_value('perday', $feegroup['fine_type']) == "perday") ? TRUE : FALSE); ?> /><?php echo "Per " . $this->lang->line('day') ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="radio-inline">
                                                            <input name="account_type" class="finetype" id="input-type-tutor" value="permonth" type="radio" <?php echo set_radio('account_type', 'permonth', (set_value('permonth', $feegroup['fine_type']) == "permonth") ? TRUE : FALSE); ?> /><?php echo "Per month" ?>
                                                        </label>
                                                    </div>                                                    

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('percentage') ?></label><small class="req"> *</small>
                                                    <input id="fine_percentage" name="fine_percentage" placeholder="" type="text" class="form-control" value="<?php echo set_value('fine_percentage', $feegroup['fine_percentage']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('fine_percentage'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fine') . " " . $this->lang->line('amount') ?></label><small class="req"> *</small>
                                                    <input id="fine_amount" name="fine_amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('fine_amount', $feegroup['fine_amount']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('fine_amount'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('fees_group', 'can_add') || $this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_group_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('fees_group_list'); ?></div>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th><?php echo "Display Name"; ?>
                                        <th><?php echo "Fees Type"; ?>
                                        </th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($feegroupList as $feegroup) {
                                        if ($userdata['role_id'] == 10 && $userdata['user_type'] == "Bus Operator" ) {
                                            if ($feegroup['fees_type'] == "b") {
                                                ?>
                                                <tr>
                                            <td class="mailbox-name">
                                                <?php echo $feegroup['name'] ?>
                                            </td>
                                            <td> <?php echo $feegroup['dis_name'] ?></td>
                                            <td><?php if ($feegroup['fees_type'] == 'm') {
                                                    echo "Main Fees";
                                                } elseif ($feegroup['fees_type'] == 'o') {
                                                    echo "Other Fees";
                                                } elseif ($feegroup['fees_type'] == 'b') {
                                                    echo "Bus Fees";
                                                } ?></td>

                                            

                                            <td class="mailbox-name">

                                                <?php echo $feegroup['description']; ?>


                                            </td>


                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/feegroup/edit/<?php echo $feegroup['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/feegroup/delete/<?php echo $feegroup['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                                <?php
                                            }
                                        }else{
                                    ?>
                                        <tr>


                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feegroup['name'] ?></a>
                                                <div class="fee_detail_popover" style="display: none">
                                                    <?php
                                                    if ($feegroup['description'] == "") {
                                                    ?>
                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <p class="text text-info"><?php echo $feegroup['description']; ?></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?php echo $feegroup['dis_name'] ?></td>
                                            <td><?php if ($feegroup['fees_type'] == 'm') {
                                                    echo "Main Fees";
                                                } elseif($feegroup['fees_type'] == 'o') {
                                                    echo "Other Fees";
                                                }elseif($feegroup['fees_type'] == 'b') {
                                                    echo "Bus Fees";
                                                } ?></td>

                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/feegroup/edit/<?php echo $feegroup['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/feegroup/delete/<?php echo $feegroup['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $count++;
                                    }}
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        var account_type = "<?php echo set_value('account_type', $feegroup['fine_type']); ?>";
        load_disable(account_type);

    });

    $(document).on('change', '.finetype', function() {
        calculatefine();
    });


    $(document).on('keyup', '#amount,#fine_percentage', function() {
        calculatefine();
    });

    function load_disable(account_type) {
        
        if (account_type === "percentage") {
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (account_type === "fix") {
            $('#fine_amount').prop('readonly', false);
            $('#fine_percentage').prop('readonly', true);
        } else {
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', true);
        }
    }


    function calculatefine() {
        var amount = $('#amount').val() || 0;
        var fine_percentage = $('#fine_percentage').val();
        var finetype = $('input[name=account_type]:checked', '#form1').val();
        if (finetype === "percentage") {
            fine_amount = ((amount * fine_percentage) / 100).toFixed(2);
            
            $('#fine_amount').val(fine_amount).prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (finetype === "fix") {
            $('#fine_amount').val("").prop('readonly', false);
            $('#fine_percentage').val("").prop('readonly', true);
        } else if (finetype === "perday") {
            $('#due_date_error').html(' *');
            $('#fine_amount').val("").prop('readonly', false);
            $('#fine_percentage').val("").prop('readonly', true);
        } else {
            $('#fine_amount').val("");
        }
    }
</script>