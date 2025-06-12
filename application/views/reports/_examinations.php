<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>  <?php echo $this->lang->line('examinations') . " " . $this->lang->line('report') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">



                    <?php
                    if ($this->rbac->hasPrivilege('rank_report', 'can_view')) {
                        ?> 
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/rankreport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/rankreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('rank') . " " . $this->lang->line('report'); ?></a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('classwisereport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/classwisereport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/classwisereport"><i class="fa fa-file-text-o"></i> <?php echo "Class Wise Reports"; ?></a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('classwisegraphreport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/classwisegraphreport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/classwisegraphreport"><i class="fa fa-file-text-o"></i> <?php echo "Class Wise Graph Reports"; ?></a></li>
                            <?php
                        }
                        ?>
                    <?php
                     if ($this->rbac->hasPrivilege('markreport', 'can_view')) {
                        ?> 
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/markreport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/markreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('marks') ?> Entries</a></li>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/subjectwisereport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/subjectwisereport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('subject') ?> Wise Exam Report (Graph)</a></li>
                            <?php }
                     if ($this->rbac->hasPrivilege('teacherReport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/teacherReport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/teacherReport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('teachers') ?> Report</a></li>
                            <?php
                         }
                     if ($this->rbac->hasPrivilege('marksReport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/marksReport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/marksReport"><i class="fa fa-file-text-o"></i> <?php echo "Exam Analysis" ?> Report</a></li>
                            <?php
                         }
                     if ($this->rbac->hasPrivilege('sectionMarksReport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/sectionMarksReport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/sectionMarksReport"><i class="fa fa-file-text-o"></i> <?php echo "Exam Analysis" ?> Report (Section Wise)</a></li>
                            <?php
                         }
                        if ($this->rbac->hasPrivilege('subjectWiseMarksReport', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/examinations/subjectWiseMarksReport'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/subjectWiseMarksReport"><i class="fa fa-file-text-o"></i> <?php echo "Subject Wise Exam"; ?> Report </a></li>
                            <?php
                         }
                        ?>


                </ul>
            </div>
        </div>
    </div>
</div>