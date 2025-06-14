<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('student_information') . " " . $this->lang->line('report') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('student_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_report'); ?>"><a href="<?php echo base_url(); ?>student/studentreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_report'); ?></a></li>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/classsectionreport'); ?>"><a href="<?php echo site_url('student/classsectionreport'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class_section_report'); ?></a></li>


                    <?php
                    }
                    if ($this->rbac->hasPrivilege('guardian_report', 'can_view')) {
                    ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/guardian_report'); ?>"><a href="<?php echo base_url(); ?>student/guardianreport"><i class="fa fa-file-text-o"></i>
                                <?php echo $this->lang->line('guardian_report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('student_history', 'can_view')) {
                    ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_history'); ?>"><a href="<?php echo base_url() ?>admin/users/admissionreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_history'); ?></a></li>
                    <?php } ?>

                    <?php if ($this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_login_credential'); ?>"><a href="<?php echo base_url(); ?>admin/users/logindetailreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('login_credential'); ?></a></li>
                    <?php } ?>

                    <?php if ($this->rbac->hasPrivilege('class_subject_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/class_subject_report'); ?>"><a href="<?php echo base_url(); ?>report/class_subject"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class') . " " . $this->lang->line('subject') . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    // if ($this->rbac->hasPrivilege('admission_report', 'can_view')) { 
                    ?>


                    <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php //echo set_SubSubmenu('Reports/student_information/admission_report'); 
                                                                ?>"><a href="<?php //echo base_url(); 
                                                                                ?>report/admission_report"><i class="fa fa-file-text-o"></i> <?php //echo $this->lang->line('admission') . " " . $this->lang->line('report'); 
                                                                                                                                                                                                                            ?></a></li> -->
                    <?php //}
                    if ($this->rbac->hasPrivilege('admission_report', 'can_view')) { ?>


                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/admission_report'); ?>"><a href="<?php echo base_url(); ?>report/admission_report_new"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('admission') . " " . $this->lang->line('report'); ?></a></li> -->
                    <?php }
                    // if ($this->rbac->hasPrivilege('sibling_report', 'can_view')) { 
                    ?>

                    <!-- //     <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/sibling_report'); ?>"><a href="<?php echo base_url(); ?>report/sibling_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('sibling') . " " . $this->lang->line('report'); ?></a></li> -->
                    <?php
                    // }
                    if ($this->rbac->hasPrivilege('sibling_report_new', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/sibling_report_new'); ?>"><a href="<?php echo base_url(); ?>report/sibling_report_new"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('sibling') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('student_profile', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_profile'); ?>"><a href="<?php echo base_url(); ?>report/student_profile"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('profile'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('homehork_evaluation_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/evaluation_report'); ?>"><a href="<?php echo base_url(); ?>homework/evaluation_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('homework') . " " . $this->lang->line('evaluation_report'); ?></a></li>

                    <?php }
                    if ($this->rbac->hasPrivilege('sibling_report_brief', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/sibling_report_brief'); ?>"><a href="<?php echo base_url(); ?>report/sibling_report_brief"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('sibling') . " " . $this->lang->line('report') . " Brief"; ?></a></li>

                    <?php }
                    if ($this->rbac->hasPrivilege('student_gender_ratio_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/boys_girls_ratio'); ?>"><a href="<?php echo base_url(); ?>report/boys_girls_ratio"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('gender') . " " . $this->lang->line('ratio') . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('student_teacher_ratio_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_teacher_ratio'); ?>"><a href="<?php echo base_url(); ?>report/student_teacher_ratio"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('teacher') . " " . $this->lang->line('ratio') . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('Student_discount_report', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/Student_discount_report'); ?>"><a href="<?php echo base_url(); ?>report/Student_discount_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('discount') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('departmental_report', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/departmental_report'); ?>"><a href="<?php echo base_url(); ?>report/departmental_report"><i class="fa fa-file-text-o"></i> <?php echo  "Departmental " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('student_strength_report', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_strength_report'); ?>"><a href="<?php echo base_url(); ?>report/student_strength_report"><i class="fa fa-file-text-o"></i> <?php echo  "Class Wise Student Strength " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('departmental_report_brief', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/departmental_report_brief'); ?>"><a href="<?php echo base_url(); ?>report/departmental_report_brief"><i class="fa fa-file-text-o"></i> <?php echo  "Departmental " . $this->lang->line('report')." Brief"; ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('class_wise_document_report', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/class_wise_document_report'); ?>"><a href="<?php echo base_url(); ?>report/class_wise_document_report"><i class="fa fa-file-text-o"></i> <?php echo  "Class Wise Document " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>