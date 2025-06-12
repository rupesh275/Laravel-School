<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">
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
                            <small class="pull-right"></small>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/staff/staff_advance') ?>" method="post" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Staff List </label><small class="req"> *</small>
                                        <select name="staff_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($resultlist as  $staff) {
                                            ?>
                                                <option value="<?php echo $staff['id']; ?>" <?php echo set_select('staff_id', $staff['id'], (!empty($update['staff_id']) && $update['staff_id'] == $staff['id'] ? TRUE : FALSE)) ?>><?php echo $staff['name'] . " " . $staff['surname']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('staff_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Advance Date</label><small class="req"> *</small>
                                        <input type="text" name="adv_date" value="<?php echo set_value('adv_date', !empty($update['adv_date']) ? date('d-m-Y', strtotime($update['adv_date'])) : date('d-m-Y')) ?>" id="adv_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('adv_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Advance Amount</label><small class="req"> *</small>
                                        <input type="text" name="adv_amount" value="<?php echo set_value('adv_amount', !empty($update['adv_amount']) ? $update['adv_amount'] : ""); ?>" id="adv_amount" class="form-control">
                                        <span class="text-danger"><?php echo form_error('adv_amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Advance Remarks </label><small class="req"> *</small>
                                        <input type="text" name="adv_remarks" value="<?php echo set_value('adv_remarks', !empty($update['adv_remarks']) ? $update['adv_remarks'] : ""); ?>" id="adv_remarks" class="form-control">
                                        <span class="text-danger"><?php echo form_error('adv_remarks'); ?></span>
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
                        <h3 class="box-title titlefix"><?php echo "Staff Advance List"; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Staff Advance List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('staff') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo "Advance Date"; ?></th>
                                        <th><?php echo "Advance " . $this->lang->line('amount'); ?></th>
                                        <th><?php echo "Advance Remarks"; ?></th>
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
                                                <td class="mailbox-name"> <?php echo $staffRow['name'] ?></td>
                                                <td class="mailbox-name"><?php echo date('d-m-Y', strtotime($staffRow['adv_date'])) ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['adv_amount'] ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['adv_remarks'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('subject', 'can_edit')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/staff_advance/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('subject', 'can_delete')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/delete_adv/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
$(document).ready(function() {
    $('.select2').select2();
})
</script>