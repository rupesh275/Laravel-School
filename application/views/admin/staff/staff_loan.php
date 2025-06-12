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
                        <?php if ($this->session->flashdata('msg')) { ?> <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/staff/staff_loan') ?>" method="post" class="">
                                <?php //echo $this->customlib->getCSRF(); 
                                ?>
                                <div class="col-md-4">
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
                                        <span class="text-danger"><?php echo form_error('staff_list'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Date</label><small class="req"> *</small>
                                        <input type="text" name="loan_date" id="loan_date" value="<?php echo set_value('loan_date', !empty($update['loan_date']) ? date('d-m-Y', strtotime($update['loan_date'])) : date('d-m-Y')) ?> " class="form-control date">
                                        <span class="text-danger"><?php echo form_error('loan_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Amount</label><small class="req"> *</small>
                                        <input type="text" name="loan_amount" id="loan_amount" value="<?php echo set_value('loan_amount', !empty($update['loan_amount']) ? $update['loan_amount'] : ""); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('loan_amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Tenure Months</label><small class="req"> *</small>
                                        <input type="text" name="loan_tenure_months" value="<?php echo set_value('loan_tenure_months', !empty($update['loan_tenure_months']) ? $update['loan_tenure_months'] : ""); ?>" id="loan_tenure_months" class="form-control">
                                        <span class="text-danger"><?php echo form_error('loan_tenure_months'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Monthly Deduction Amount</label><small class="req"> *</small>
                                        <input type="text" name="loan_emi" id="loan_emi" value="<?php echo set_value('loan_emi', !empty($update['loan_emi']) ? $update['loan_emi'] : ""); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('loan_emi'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Close Date</label><small class="req"> *</small>
                                        <input type="text" name="loan_close_date" value="<?php echo set_value('loan_close_date', !empty($update['loan_close_date']) ? date('d-m-Y', strtotime($update['loan_close_date'])) : ""); ?>" id="loan_close_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('loan_close_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loan Purpose</label><small class="req"> *</small>
                                        <input type="text" name="loan_purpose" id="loan_purpose" value="<?php echo set_value('loan_purpose', !empty($update['loan_purpose']) ? $update['loan_purpose'] : ""); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('loan_purpose'); ?></span>
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
                        <h3 class="box-title titlefix"><?php echo "Staff Loan List"; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Staff Loan List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('staff') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo "Loan Date"; ?></th>
                                        <th><?php echo "Loan " . $this->lang->line('amount'); ?></th>
                                        <th><?php echo "Loan Emi"; ?></th>
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
                                                <td class="mailbox-name"><?php echo date('d-m-Y', strtotime($staffRow['loan_date'])) ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['loan_amount'] ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['loan_emi'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('subject', 'can_edit')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/staff_loan/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('subject', 'can_delete')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/staff/delete_loan/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
        $('.select2').select2();
        $("#loan_tenure_months,#loan_date,#loan_amount").on("keyup change", function(e) {
            var loan_date = $("#loan_date").val();
            var loan_amount = $("#loan_amount").val();
            var loan_tenure_months = $("#loan_tenure_months").val();
            if (loan_amount != "" && loan_tenure_months != "") {
                var amount = Math.round(loan_amount / loan_tenure_months).toFixed(2);
            } else {
                var amount = "";
            }
            $("#loan_emi").val(amount);
            var totalDate = addMonths(loan_date, loan_tenure_months);
            $("#loan_close_date").val(totalDate);

        });

        function addMonths(date, months) {
            var date = moment(date, "DD-MM-YYYY").add(months, 'months').format('DD-MM-YYYY');
            return date;
        }
    });
</script>