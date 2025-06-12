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
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Payroll Settings"; ?></h3>
                        <div class="box-tools pull-right">
                           <a href="<?php echo base_url(); ?>admin/payroll/add_payroll_setting" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></a>
                       <br><br>
                        </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Salary Cheque List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo "Start Date"; ?></th>
                                        <th><?php echo "DA"; ?></th>
                                        <th><?php echo "Personal Pay"; ?></th>
                                        <th><?php echo "HRA"; ?></th>
                                        <th><?php echo "Traveling Allowance"; ?></th>
                                        <th><?php echo "Other Allowance"; ?></th>
                                        <th><?php echo "Employees PF"; ?></th>
                                        <th><?php echo "Employees Provident Fund"; ?></th>
                                        <th><?php echo "Employees' Pension Scheme"; ?></th>
                                        <th><?php echo "EDLI"; ?></th>
                                        <th><?php echo "Administrative Charges"; ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($Payroll_set)) {
                                        foreach ($Payroll_set as $PayrollRow) {
                                    ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo date('d-m-Y', strtotime($PayrollRow['start_date']));?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['da'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['pp'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['hra'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['ta'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['oa'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['ey_pf'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['er_epf'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['er_eps'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['er_edli'] ?></td>
                                                <td class="mailbox-name"><?php echo $PayrollRow['er_admin'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                <!-- <a data-placement="left" href="javascript:void(0);" class="btn btn-default btn-xs print_detail" id="<?php echo $PayrollRow['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>" >
                                                        <i class="fa fa-print"></i>
                                                    </a> -->
                                                   
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/payroll/add_payroll_setting/<?php echo $PayrollRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                   
                                                  
                                                    
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
   
</script>
<script>
    
</script>