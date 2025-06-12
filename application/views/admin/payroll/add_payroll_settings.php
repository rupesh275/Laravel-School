<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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
                        <form role="form" action="<?php echo site_url('admin/payroll/payroll_settings_validation') ?>" method="post" class="">
                            <?php //echo $this->customlib->getCSRF(); 
                            ?>
                           

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>DA</label><small class="req"> *</small>
                                    <input type="text" name="da" id="da" value="<?php echo set_value('da', !empty($update['da']) ? $update['da'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('da'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Personal Pay</label><small class="req"> *</small>
                                    <input type="text" name="pp" id="pp" value="<?php echo set_value('pp', !empty($update['pp']) ? $update['pp'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('pp'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>HRA</label><small class="req"> *</small>
                                    <input type="text" name="hra" id="hra" value="<?php echo set_value('hra', !empty($update['hra']) ? $update['hra'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('hra'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Traveling Allowance</label>
                                    <input type="text" name="ta" id="ta" value="<?php echo set_value('ta', !empty($update['ta']) ? $update['ta'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('ta'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Other Allowance</label>
                                    <input type="text" name="oa" id="oa" value="<?php echo set_value('oa', !empty($update['oa']) ? $update['oa'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('oa'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employees PF</label><small class="req"> *</small>
                                    <input type="text" name="ey_pf" id="ey_pf" value="<?php echo set_value('ey_pf', !empty($update['ey_pf']) ? $update['ey_pf'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('ey_pf'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PF Earning Limit</label><small class="req"> *</small>
                                    <input type="text" name="pf_earning_limit" id="pf_earning_limit" value="<?php echo set_value('pf_earning_limit', !empty($update) ? $update['pf_earning_limit'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('pf_earning_limit'); ?></span>
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employees Provident Fund</label><small class="req"> *</small>
                                    <input type="text" name="er_epf" id="er_epf" value="<?php echo set_value('er_epf', !empty($update) ? $update['er_epf'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('er_epf'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employees' Pension Scheme</label><small class="req"> *</small>
                                    <input type="text" name="er_eps" id="er_eps" value="<?php echo set_value('er_eps', !empty($update['er_eps']) ? $update['er_eps'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('er_eps'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>EDLI</label><small class="req"> *</small>
                                    <input type="text" name="er_edli" id="er_edli" value="<?php echo set_value('er_edli', !empty($update['er_edli']) ? $update['er_edli'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('er_edli'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Administrative Charges</label><small class="req"> *</small>
                                    <input type="text" name="er_admin" id="er_admin" value="<?php echo set_value('er_admin', !empty($update['er_admin']) ? $update['er_admin'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('er_admin'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Daily Wages Working Days</label><small class="req"> *</small>
                                    <input type="text" name="dailywages_working_days" id="dailywages_working_days" value="<?php echo set_value('dailywages_working_days', !empty($update['dailywages_working_days']) ? $update['dailywages_working_days'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('dailywages_working_days'); ?></span>
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Payslip Prefix</label><small class="req"> *</small>
                                    <input type="text" name="payslip_prefix" id="payslip_prefix" value="<?php echo set_value('payslip_prefix', !empty($update['payslip_prefix']) ? $update['payslip_prefix'] : ""); ?>" class="form-control">
                                    <span class="text-danger"><?php echo form_error('payslip_prefix'); ?></span>
                                </div>
                            </div>     
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Attendence By</label><small class="req"> *</small>
                                    <select name="attendence_by" id="attendence_by" class="form-control">
                                        <option value="direct" <?php echo !empty($update['attendence_by']) && $update['attendence_by'] == 'direct' ? 'selected' : ''; ?>>Direct</option>
                                        <option value="auto" <?php echo !empty($update['attendence_by']) && $update['attendence_by'] == 'auto' ? 'selected' : ''; ?>>Auto</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('attendence_by'); ?></span>
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
    </section>
</div>