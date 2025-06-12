<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }

    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }

    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }

    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }

    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }

    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }

    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }

    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }

    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }

    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }

    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }

    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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
                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/issue_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/issue_reports"><i class="fa fa-file-text-o"></i> <?php echo "Issue " . $this->lang->line('report'); ?></a></li>
                            <?php }
                            if ($this->rbac->hasPrivilege('return_pending_reports', 'can_view')) {
                            ?>
                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/return_pending_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/return_pending_reports"><i class="fa fa-file-text-o"></i> <?php echo "Return Pending " . $this->lang->line('report'); ?></a></li>
                            <?php }
                            if ($this->rbac->hasPrivilege('damage_reports', 'can_view')) { ?>
                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/damage_reports'); ?>"><a href="<?php echo site_url('library_reports/damage_reports'); ?>"><i class="fa fa-file-text-o"></i> <?php echo "Damage " . $this->lang->line('report'); ?></a></li>

                            <?php
                            }
                            if ($this->rbac->hasPrivilege('lost_reports', 'can_view')) {
                            ?>

                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('library_reports/lost_reports'); ?>"><a href="<?php echo base_url(); ?>admin/library_reports/lost_reports"><i class="fa fa-file-text-o"></i>
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
</div>
</div>
</section>
</div>