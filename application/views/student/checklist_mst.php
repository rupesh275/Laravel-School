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
            if ($this->rbac->hasPrivilege('checklist_mst', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Check List "; ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>student/checklist_mst" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    unset($_SESSION['msg']); ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('type'); ?></label> <small class="req">*</small>
                                    <select name="type" class="form-control" id="type">
                                        <option <?php if (!empty($update) && $update['type'] == 1) {
                                                    echo "selected";
                                                } ?> value="1">Staff</option>
                                        <option <?php if (!empty($update) && $update['type'] == 2) {
                                                    echo "selected";
                                                } ?> value="2">Student</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Item Name"; ?></label> <small class="req">*</small>
                                    <input id="item_name" name="item_name" type="text" class="form-control" value="<?php echo set_value('item_name', !empty($update) ? $update['item_name'] : ""); ?>" />
                                    <span class="text-danger"><?php echo form_error('item_name'); ?></span>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <input type="hidden" name="id" value="<?php echo !empty($update) ? $update['id'] : "" ?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('fees_type', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Check List"; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo "Check List"; ?></div>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('type'); ?>
                                        </th>
                                        <th><?php echo "Item Name"; ?></th>



                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($checklist as $check) {
                                    ?>
                                        <tr>
                                            <td class="mailbox-name"><?php if ($check['type'] == 1) {
                                                                            echo "Staff";
                                                                        } else {
                                                                            echo "Student";
                                                                        } ?></td>
                                            <td class="mailbox-name">
                                                <?php echo $check['item_name']; ?>
                                            </td>

                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('checklist_mst', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>student/checklist_mst/<?php echo $check['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('checklist_mst', 'can_delete')) {
                                                    $this->db->where('checklist_id', $check['id']);
                                                    $usedcheckcount = $this->db->get('student_checklist')->num_rows();
                                                    if ($usedcheckcount > 0) {
                                                        $delUrl = 'javascript:void(0);';
                                                        $onclick = "delalert()";
                                                    } else {
                                                        $delUrl = base_url('student/deleteChecklist_mst/' . $check['id']);
                                                        $onclick = "return confirm('" . $this->lang->line('delete_confirm') . "')";
                                                    }



                                                ?>
                                                    <a data-placement="left" href="<?php echo $delUrl; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="<?php echo $onclick; ?>;">
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

    function delalert() {
        alert("Cannot Delete It Is Used Somewhere ");
    }
</script>