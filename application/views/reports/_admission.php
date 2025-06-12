<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('admission') . " " . $this->lang->line('report') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu(''); ?>"><a href="<?php echo base_url(); ?>report/admission_report_new"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('admission') . " " . $this->lang->line('report'); ?></a></li> -->
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/admission/admission_report'); ?>"><a href="<?php echo base_url(); ?>report/admission_report_new"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('admission') . " Register"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/admission/class_wise_admission_report'); ?>"><a href="<?php echo base_url(); ?>report/class_wise_admission_report"><i class="fa fa-file-text-o"></i> <?php echo "Class Wise Admission Report"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/admission/division_wise_adm_report'); ?>"><a href="<?php echo base_url(); ?>report/division_wise_adm_report"><i class="fa fa-file-text-o"></i> <?php echo "Division Wise Admission Report"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/admission/gender_adm_report'); ?>"><a href="<?php echo base_url(); ?>report/gender_adm_report"><i class="fa fa-file-text-o"></i> <?php echo "Gender Wise Admission Report"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/admission/adm_report_brief'); ?>"><a href="<?php echo base_url(); ?>report/adm_report_brief"><i class="fa fa-file-text-o"></i> <?php echo "Admission Report Brief"; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>