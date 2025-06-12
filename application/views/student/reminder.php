<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('reminder', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Reminder List "; ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>student/reminder" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
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

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('reminder')." ". $this->lang->line('type'); ?></label> <small class="req">*</small>
                                    <select name="reminder_type" class="form-control" id="reminder_type">
                                        <option <?php if (!empty($update) && $update['reminder_type'] == 1) {
                                                    echo "selected";
                                                } ?> value="1">Yearly</option>
                                        <option <?php if (!empty($update) && $update['reminder_type'] == 2) {
                                                    echo "selected";
                                                } ?> value="2">Monthly</option>
                                        <option <?php if (!empty($update) && $update['reminder_type'] == 3) {
                                                    echo "selected";
                                                } ?> value="3">Weekly</option>
                                        <option <?php if (!empty($update) && $update['reminder_type'] == 4) {
                                                    echo "selected";
                                                } ?> value="4">Daily</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('reminder_type'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('reminder')." ". $this->lang->line('category'); ?></label> <small class="req">*</small>
                                    <select name="reminder_category" class="form-control" id="reminder_category">
                                        <option <?php if (!empty($update) && $update['reminder_category'] == 1) {
                                                    echo "selected";
                                                } ?> value="1">FD</option>
                                        <option <?php if (!empty($update) && $update['reminder_category'] == 2) {
                                                    echo "selected";
                                                } ?> value="2">Fees</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('reminder_category'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Date"; ?></label> <small class="req">*</small>
                                    <input id="date" name="date" type="text" class="form-control date" value="<?php echo set_value('date', !empty($update) ? date('d-m-Y',strtotime($update['date'])) : date('d-m-Y')); ?>" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Reminder Period In Days"; ?></label> 
                                    <input id="reminder_period" name="reminder_period" type="number" class="form-control" value="<?php echo set_value('reminder_period', !empty($update) ? $update['reminder_period'] : ""); ?>" />
                                    <span class="text-danger"><?php echo form_error('reminder_period'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Subject"; ?></label> <small class="req">*</small>
                                    <input id="subject" name="subject" type="text" class="form-control" value="<?php echo set_value('subject', !empty($update) ? $update['subject'] : ""); ?>" />
                                    <span class="text-danger"><?php echo form_error('subject'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Description"; ?></label> <small class="req">*</small>
                                    <textarea name="description" id="description"  class="form-control" ><?php echo set_value('description', !empty($update) ? $update['description'] : ""); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <input type="hidden" name="id" value="<?php echo !empty($update) ? $update['id'] : ""?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('reminder', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Reminder List"; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo "Reminder List"; ?></div>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('reminder')." ".$this->lang->line('type'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('reminder')." ".$this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>



                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultlist as $result) {
                                    ?>
                                        <tr>
                                            <td class="mailbox-name"><?php if ($result['reminder_type'] == 1) {
                                                                            echo "Yearly";
                                                                        } elseif ($result['reminder_type'] == 2) {
                                                                            echo "Monthly";
                                                                        } 
                                                                        elseif ($result['reminder_type'] == 3) {
                                                                            echo "Weekly";
                                                                        } 
                                                                        elseif ($result['reminder_type'] == 4) {
                                                                            echo "Daily";
                                                                        } ?></td>
                                            <td class="mailbox-name">
                                                <?php if ($result['reminder_category'] == 1) {
                                                                            echo "FD";
                                                                        } else {
                                                                            echo "Fees";
                                                                        } ; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $result['subject']; ?>
                                            </td>

                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('reminder', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>student/reminder/<?php echo $result['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('reminder', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>student/deletereminder/<?php echo $result['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
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
            <!-- left column -->

            <!-- right column -->
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
    });
</script>