<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('finance') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">

                    <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/reportduefees'); ?>"><a href="<?php echo site_url('studentfee/reportduefees'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('balance_fees_statement'); ?></a></li>

                    <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/reportdailycollection'); ?>"><a href="<?php echo site_url('studentfee/reportdailycollection'); ?>"><i class="fa fa-file-text-o"></i><?php echo $this->lang->line('daily_collection_report'); ?> </a></li> -->

                    <?php
                    if ($this->rbac->hasPrivilege('fees_statement', 'can_view')) {
                    ?>
                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/reportbyname'); ?>"><a href="<?php echo base_url(); ?>studentfee/reportbyname"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('fees_statement'); ?></a></li> -->
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
                    ?>

                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/studentacademicreport'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/studentacademicreport"><i class="fa fa-file-text-o"></i>
                                <?php echo $this->lang->line('balance_fees_report'); ?></a></li> -->
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('fees_collection_report', 'can_view')) {
                    ?>

                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/collection_report'); ?>"><a href="<?php echo base_url(); ?>studentfee/collection_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report'); ?></a></li> -->
                    <?php }
                     if ($this->rbac->hasPrivilege('collection_report', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/collection_report_receipt'); ?>"><a href="<?php echo base_url(); ?>studentfee/collection_report_receipt"><i class="fa fa-file-text-o"></i> <?php echo "Collection" . " " . $this->lang->line('report')." [ Receipt Date ]"; ?></a></li>
                        <?php }
                         if ($this->rbac->hasPrivilege('collectionReportReceipt', 'can_view')) {
                            ?>
                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/collectionReportReceipt'); ?>"><a href="<?php echo base_url(); ?>studentfee/collectionReportReceipt"><i class="fa fa-file-text-o"></i> <?php echo "Collection" . " " . $this->lang->line('report')." [ Entry Date Wise ]"; ?></a></li>
                            <?php }
                         if ($this->rbac->hasPrivilege('collectionReportPassDate', 'can_view')) {
                            ?>
                                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/collectionReportPassDate'); ?>"><a href="<?php echo base_url(); ?>studentfee/collectionReportPassDate"><i class="fa fa-file-text-o"></i> <?php echo "Collection" . " " . $this->lang->line('report')." [ Pass Date Wise ]"; ?></a></li>
                            <?php }
                            
                            if ($this->rbac->hasPrivilege('collection_report', 'can_view')) {
                                ?>
                                    <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/collection_report_old'); ?>"><a href="<?php echo base_url(); ?>studentfee/collection_report_old"><i class="fa fa-file-text-o"></i> <?php echo "Collection Report Old"; ?></a></li>
                            <?php }                            
                    if ($this->rbac->hasPrivilege('income_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/income'); ?>"><a href="<?php echo base_url(); ?>report/income"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('income') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('installwise_report', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/installwise_report'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/installwise_report"><i class="fa fa-file-text-o"></i> <?php echo "Student Wise Fees " . " " . $this->lang->line('report'); ?></a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('installwise_report', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/installwise_report_all'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/installwise_report_all"><i class="fa fa-file-text-o"></i> <?php echo "Student Wise Fees " . " " . $this->lang->line('report'). "[All Class]"; ?></a></li>
                        <?php }                    
                    

                    if ($this->rbac->hasPrivilege('expense_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/expense'); ?>"><a href="<?php echo base_url(); ?>report/expense"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('expense') . ' ' . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('payroll_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/payroll'); ?>"><a href="<?php echo base_url(); ?>report/payroll"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('payroll') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('installwise_report', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/installwise_report_brief'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/installwise_report_brief"><i class="fa fa-file-text-o"></i> <?php echo "Student Wise Fees Reports(Brief)"; ?></a></li>
                        <?php }
                                         
                    if ($this->rbac->hasPrivilege('income_group_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/incomegroup'); ?>"><a href="<?php echo base_url(); ?>report/incomegroup"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('income') . " " . $this->lang->line('group') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }

                    if ($this->rbac->hasPrivilege('expense_group_report', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/expensegroup'); ?>"><a href="<?php echo base_url(); ?>report/expensegroup"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('expense') . " " . $this->lang->line('group') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('classwise_installreport', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classwise_installreport'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classwise_installreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class') . " " . $this->lang->line('wise') . " " . $this->lang->line('report'); ?></a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('classwise_installreport_detailed', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classwise_installreport_detailed'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classwise_installreport_detailed"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class') . " " . $this->lang->line('wise') . " Dues " . $this->lang->line('report'); ?> (Fees Wise)</a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('classwise_installreport_others', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classwise_installreport_others'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classwise_installreport_others"><i class="fa fa-file-text-o"></i> <?php echo " Dues " . $this->lang->line('fees'); ?> (Other Fees)</a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('classwise_installreport_others', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classwise_installreport_others_caution'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classwise_installreport_others_caution"><i class="fa fa-file-text-o"></i> <?php echo " Dues " . $this->lang->line('fees'); ?> (Caution Deposit)</a></li>
                        <?php }                        
                    if ($this->rbac->hasPrivilege('section_installreport', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/section_installreport'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/section_installreport"><i class="fa fa-file-text-o"></i> <?php echo "Section " . $this->lang->line('wise') . " " . $this->lang->line('report'); ?></a></li>
                        <?php }
                    if ($this->rbac->hasPrivilege('online_admission', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/onlineadmission'); ?>"><a href="<?php echo base_url(); ?>report/onlineadmission"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('online_admission') . " " . $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('classwise_installreport', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classwise_installreport_brief'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classwise_installreport_brief"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class') . " " . $this->lang->line('wise') . " " . $this->lang->line('report')." (Brief)"; ?></a></li>
                        <?php }  
                    
                    if ($this->rbac->hasPrivilege('classfees_report', 'can_view')) {
                    ?>
                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/classfees_report'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/classfees_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class') . " " . $this->lang->line('wise') . " " . $this->lang->line('fees') . " " . $this->lang->line('report'); ?></a></li> -->
                    <?php }
                    if ($this->rbac->hasPrivilege('sectiondue_report', 'can_view')) {
                    ?>
                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/sectiondue_report'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/sectiondue_report"><i class="fa fa-file-text-o"></i> <?php echo "Section" . " " . $this->lang->line('due') . " " . $this->lang->line('report'); ?></a></li> -->
                    <?php }
                    if ($this->rbac->hasPrivilege('chequeReport', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/chequeReport'); ?>"><a href="<?php echo base_url(); ?>admin/feemaster/chequeReport"><i class="fa fa-file-text-o"></i> <?php echo "Cheque" . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('onlinefailedreport', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/onlinefailedreport'); ?>"><a href="<?php echo base_url(); ?>admin/feemaster/onlinefailedreport"><i class="fa fa-file-text-o"></i> <?php echo "Online Failed" . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    if ($this->rbac->hasPrivilege('cancelled_reports', 'can_view')) {
                    ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/cancelled_reports'); ?>"><a href="<?php echo base_url(); ?>report/cancelled_reports"><i class="fa fa-file-text-o"></i> <?php echo "Cancelled Receipt" . " " . $this->lang->line('report'); ?></a></li>
                    <?php }
                    
                    if ($this->rbac->hasPrivilege('online_fees_collection_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/onlinefees_report'); ?>"><a href="<?php echo base_url(); ?>report/onlinefees_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('online') . " " . $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('reconsile_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/reconsile_report'); ?>"><a href="<?php echo base_url(); ?>report/reconsile_report"><i class="fa fa-file-text-o"></i> <?php echo  "Reconsile " . $this->lang->line('report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('studentWise_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/studentWise_report'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/studentWise_report"><i class="fa fa-file-text-o"></i> <?php echo  "Student Wise " . $this->lang->line('report')." [ Teacher ]"; ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('studentWise_fees_statement', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/studentWise_fees_statement'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/studentWise_fees_statement"><i class="fa fa-file-text-o"></i> <?php echo  "Student Wise Fees Statement [ CA ]"; ?></a></li>
                    <?php
                    }
                   
                    //if ($this->rbac->hasPrivilege('section_installreport', 'can_view')) {
                    ?>
                        <!-- <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/finance/section_installreport'); ?>"><a href="<?php echo base_url(); ?>admin/transaction/section_installreport"><i class="fa fa-file-text-o"></i> <?php echo "Section " . $this->lang->line('wise') . " Installment " . $this->lang->line('report'); ?></a></li> -->
                    <?php //} ?>

                </ul>
            </div>
        </div>
    </div>
</div>