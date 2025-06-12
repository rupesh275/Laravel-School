<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('certificate') ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('admin/certificate/tc_register'); ?>"><a href="<?php echo base_url(); ?>admin/certificate/tc_list"><i class="fa fa-file-text-o"></i> <?php echo "Transfer Certificate"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('admin/certificate/tru_tc'); ?>"><a href="<?php echo base_url(); ?>admin/certificate/tru_tc"><i class="fa fa-file-text-o"></i> <?php echo "TTC"; ?></a></li>
                <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('admin/certificate/ctc'); ?>"><a href="<?php echo base_url(); ?>admin/certificate/ctc"><i class="fa fa-file-text-o"></i> <?php echo "CTC"; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>