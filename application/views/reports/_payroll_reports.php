<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('payroll') . " " . $this->lang->line('report') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/payroll_reports/payroll_group_report'); ?>"><a href="<?php echo base_url(); ?>report/payroll_reports"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('payroll') . " Group Report"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/payroll_reports/payroll_daily_wages_report'); ?>"><a href="<?php echo base_url(); ?>report/payroll_daily_wages_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('payroll') . " Daily Wages Report"; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>