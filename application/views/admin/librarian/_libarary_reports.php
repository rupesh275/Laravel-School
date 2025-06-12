<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('library') . " " . $this->lang->line('report') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('issue_reports', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Library/library_reports/issue_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/issue_reports"><i class="fa fa-file-text-o"></i> <?php echo "Issue " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('return_pending_reports', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Library/library_reports/return_pending_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/return_pending_reports"><i class="fa fa-file-text-o"></i> <?php echo "Return Pending " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('damage_reports', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Library/library_reports/damage_reports'); ?>"><a href="<?php echo site_url('admin/library_reports/damage_reports'); ?>"><i class="fa fa-file-text-o"></i> <?php echo "Damage " . $this->lang->line('report'); ?></a></li>

                    <?php
                    }
                    if ($this->rbac->hasPrivilege('lost_reports', 'can_view')) {
                    ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Library/library_reports/lost_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/lost_reports"><i class="fa fa-file-text-o"></i>
                                <?php echo "Lost " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('staff_booking_report', 'can_view')) {
                    ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/staff_booking_report'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/staff_booking_report"><i class="fa fa-file-text-o"></i>
                                <?php echo "Staff Booking " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('student_booking_report', 'can_view')) {
                    ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/student_booking_report'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/student_booking_report"><i class="fa fa-file-text-o"></i>
                                <?php echo "Student Booking " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>