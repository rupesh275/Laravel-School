<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">
<style type="text/css">
    @media print {
        td{
            font-size:9px;
        }
        th{
            font-size:10px;
        }
        .paytable2 td, .paytable2 th{
            padding: 2px 0px;
            font-size: 11px;
        }
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
        .visible-xs {
            display: none !important;
        }
        .hidden-xs {
            display: block !important;
        }
        table.hidden-xs {
            display: table;
        }
        tr.hidden-xs {
            display: table-row !important;
        }
        th.hidden-xs,
        td.hidden-xs {
            display: table-cell !important;
        }
        .hidden-xs.hidden-print {
            display: none !important;
        }
        .hidden-sm {
            display: none !important;
        }
        .visible-sm {
            display: block !important;
        }
        table.visible-sm {
            display: table;
        }
        tr.visible-sm {
            display: table-row !important;
        }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important;
        }
        h3{
            font-size: 16px;
            font-weight: 600;
        }
      
      
    }
    #html-2-pdfwrapper{
            page-break-after: always;
        }
</style>

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $this->lang->line('payslip'); ?></title>
    </head>

    <div id="html-2-pdfwrapper">

        <div class="row" >
            <!-- left column -->
            <div class="col-md-12">

                <div class="">
                    <table width="100%"> 
                        <tr>
                            <td>
                                <div>
                                    <img src="<?php echo base_url() ?>/uploads/print_headerfooter/staff_payslip/<?php $this->setting_model->get_payslipheader(); ?>" style="height:100px;width: 100%;" />
                                   
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right"> <b style="font-size:18px;"> Affiliation No. : <?php echo $affno; ?></b></td>
                        </tr>
                    </table>
                    <table width="100%"> 
                        <tr>
                            <td style="text-align:center" colspan="4"><h3 style="margin: 10px 0 20px;">Payslip For The Month of <?php echo $month; ?> <?php echo $year; ?></h3></td>
                        </tr>
                    </table>
                    <table width="100%">
                    <?php 
                        $salryRow = $this->db->where('month', $month)->where('year', $year)->where('type', 'salary')->where('status', 'active')->get('salary_cheque_trn')->row_array();
                        if ($salryRow != null) {
                            $transfer_date = date('d-m-Y', strtotime($salryRow['payment_date']));
                        } else {
                            $transfer_date = "";
                        }
                        $session_name = $this->setting_model->getCurrentSessionName();
                    ?>
                    <tr>
                            <th style="width:16%"><?php echo $this->lang->line('payslip'); ?></th>
                            <th style="width:35%"> : <?php echo $Payroll_set['payslip_prefix']."/".$session_name."/".$result["id"]; ?></th>
                            <th colspan="2">Salary Transfer Date : <?php echo $transfer_date; ?></th>
                        </tr>
                    </table>
                    <hr style="margin:7px 0;border:1px solid #ccc">
                    <div class="paytable" >
                    <!-- <div class="paytable"  style="border:0.5px solid #ccc;padding:10px 0 10px 10px"> -->

                    <table width="100%" class="paytable2">
                        
                        <tr>
                            <th style="width:16%"><?php echo $this->lang->line('staff_id'); ?></th>
                            <th style="width:35%">: <?php echo $result["employee_id"] ?></th>
                            <th ><?php echo $this->lang->line("name"); ?></th>
                            <th>: <?php echo $result["name"] . " " . $result["surname"] ?></th>
                        </tr>
                        <tr>
                            <?php if ($sch_setting->staff_department) { ?>
                                <th><?php echo $this->lang->line('department'); ?></th>
                                <th>: <?php echo $result["department"] ?></th>
                            <?php } if ($sch_setting->staff_designation) { ?>
                                <th><?php echo $this->lang->line('designation'); ?></th>
                                <th>: <?php echo $result["designation"] ?></th>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th><?php echo 'PFUAN No'; ?></th> 
                            <th>: <?php echo $result["uan_no"] ?></th>
                            <th><?php echo 'Bank A/C No'; ?></th>
                            <th>: <?php echo $result["bank_account_no"] ?></th>
                        </tr>
                    </table>
                    </div>
                    <br>
                    <table class="earntable table table-striped table-responsive" >
                        <tr style="background: #198fe9;">
                            <th width="19%"><?php echo $this->lang->line('earning'); ?></th> 
                            <th width="16%" class="pttright reborder"><?php echo $this->lang->line('amount'); ?>(<?php echo $currency_symbol; ?>)</th>
                            <th width="20%" class="pttleft"><?php echo $this->lang->line('deduction'); ?></th>
                            <th width="16%" class="text-right"><?php echo $this->lang->line('amount'); ?>(<?php echo $currency_symbol; ?>)</th>
                        </tr>
                        
                        <tr>
                            <td ><?php echo "Basic Pay";?></td>
                            <td class="pttright reborder"><?php echo $result['basic_pay'];?></td>
                            <td class="pttleft"><?php echo "LWP"; ?></td>
                            <td class="text-right"><?php echo $result['lwp'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "GP";?></td>
                            <td class="pttright reborder"><?php echo $result['gp'];?></td>
                            <td class="pttleft"><?php echo "PF"; ?></td>
                            <td class="text-right"><?php echo $result['pf'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "DA";?></td>
                            <td class="pttright reborder"><?php echo $result['da'];?></td>
                            <td class="pttleft"><?php echo "Profession Tax"; ?></td>
                            <td class="text-right"><?php echo $result['profession_tax'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "HRA";?></td>
                            <td class="pttright reborder"><?php echo $result['hra'];?></td>
                            <td class="pttleft"><?php echo "Income Tax"; ?></td>
                            <td class="text-right"><?php echo $result['income_tax'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "TA";?></td>
                            <td class="pttright reborder"><?php echo $result['ta'];?></td>
                            <td class="pttleft"><?php echo "Advance"; ?></td>
                            <td class="text-right"><?php echo $result['advance'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "Other Allowance";?></td>
                            <td class="pttright reborder"><?php echo $result['other_allowance'];?></td>
                            <td class="pttleft"><?php echo "Loan"; ?></td>
                            <td class="text-right"><?php echo $result['loan'];?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "Personal Pay";?></td>
                            <td class="pttright reborder"><?php echo $result['pp'];?></td>
                            <td class="pttleft"><?php echo "Other Deduction"; ?></td>
                            <td class="text-right"><?php echo $result['other_deduction']; ?></td>
                        </tr>
                        <tr>
                            <td ><?php echo "Addition";?></td>
                            <td class="pttright reborder"><?php echo $result['addition'];?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        

                        <tr>
                            <th><?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('earning'); ?></th>
                            <th class="pttright reborder"><?php echo number_format($result["gross_salary"],2);  ?></th>
                            <th class="pttleft"><?php echo $this->lang->line('total'); ?> <?php echo $this->lang->line('deduction'); ?></th>
                            <th class="text-right"><?php echo number_format($result["total_deduction"],2);  ?></th>
                        </tr>  
                    </table>   

                    <table class="totaltable table table-striped table-responsive">
                        <!-- <tr>
                            <th width="20%"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('mode'); ?></th> 
                            <td class="text-right"><?php //echo $payment_mode[$result["payment_mode"]]; ?></td>
                        </tr> -->
                        <!-- <tr>
                            <th width="20%"><?php echo $this->lang->line('basic_salary'); ?>(<?php echo $currency_symbol; ?>)</th> 
                            <td class="text-right"><?php echo  number_format($result["basic_pay"],2); ?></td>
                        </tr> -->

                        <tr>
                            <th width="20%"><?php echo $this->lang->line('gross_salary'); ?>(<?php echo $currency_symbol; ?>)</th> 
                            <td class="text-right"><?php echo  number_format($result["gross_salary"],2); ?></td>
                        </tr>
                        <?php //if (!empty($result["tax"])) { ?>

                            <!-- <tr>
                                <th width="20%"><?php echo $this->lang->line('tax'); ?>(<?php echo $currency_symbol; ?>)</th> 
                                <td class="text-right"><?php //echo $result["tax"] ?></td>
                            </tr> -->


                        <?php //}
                        ?>
                        <tr>
                            <th width="20%"><?php echo $this->lang->line('net_salary'); ?>(<?php echo $currency_symbol; ?>)</th> 
                            <td class="text-right"><?php echo number_format($result["nett_salary"],2);?>
                            </td>
                        </tr>
                        <tr>


                            <td align="center" colspan="2">
                                <div style="position: absolute;left:15px"><?php $this->setting_model->get_payslipfooter(); ?> <p ></p></div>

                            </td>
                        </tr>

                    </table>  

                </div>
            </div>
            <!--/.col (left) -->

        </div>
    </div>

</html>