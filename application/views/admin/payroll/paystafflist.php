<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .table,.td,.th{
        font-size: 11px;
    }
    .ta,.other_allowance,.adv_amount,.loan_emi,.addition,.other_deduction{
        padding: 0px 2px;
        font-size: 11px;
    }
    .colorbg{
        background-color:green;
        color:white;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/payroll/payroll_group') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                                <?php
                                if ($this->session->flashdata('msg')) {


                                    echo $this->session->flashdata('msg');
                                    unset($_SESSION['msg']);
                                }
                                ?>
                            <div class="row">


                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line("department"); ?>
                                        </label>
                                        <select autofocus="" onchange="getEmployeeName(this.value)" id="role" name="role" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($department as $key => $class) {

                                                if (isset($_POST["role"])) {
                                                    $role_selected = $_POST["role"];
                                                } else {
                                                    $role_selected = '';
                                                }
                                            ?>
                                                <option value="<?php echo $class["id"] ?>" <?php
                                                                                                if ($class["id"] == $role_selected) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?php print_r($class["department_name"]) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month') ?></label>

                                        <select autofocus="" id="class_id" name="month" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            if (isset($month)) {
                                                $month_selected = date("F", strtotime($month));
                                            } else {
                                                $month_selected = date("F", strtotime("-1 month"));
                                            }
                                            foreach ($monthlist as $m_key => $month_value) {
                                            ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                                                        if ($month_selected == $m_key) {
                                                                                            echo "selected =selected";
                                                                                        }
                                                                                        ?>><?php echo $month_value; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>

                                        <select autofocus="" id="class_id" name="year" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <option <?php
                                                    if ($year == date("Y", strtotime("-1 year"))) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y", strtotime("-1 year")) ?>"><?php echo date("Y", strtotime("-1 year")) ?></option>
                                            <option <?php
                                                    if ($year == date("Y")) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y") ?>"><?php echo date("Y") ?></option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </form>

                    <?php
                    
                   
                    if (isset($resultlist)) {
                        
                    ?>
                        <form action="<?php echo site_url('admin/payroll/staffPay') ?>" id="save_Pay" method="post">
                        <div class="" id="transfee">
                            <div class="box-header ptbnull">
                            <div class="box-header ptbnull d-none text-center">
                        <h3 class="box-title titlefix dnone"><i class="fa fa-money"></i> <?php echo $sch_setting->name; ?></h3><br>
                        <p class="d-none"> <?php echo $sch_setting->address;?></p>
                        </div>
                            </div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('list'); ?>
                                    </i></h3>
                                <div class="box-tools pull-right">
                                    <?php if ($this->rbac->hasPrivilege('payroll_group', 'can_add')) { ?>
                                        <button type="button" id="generate" name="submit" value="generate" class="btn btn-primary btn-sm pull-right checkbox-toggle" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> "><i class="fa fa-save"></i> <?php echo "Generate Payroll"; ?> </button>
                                        <a href="javascript:void(0);" id="cancelpaybtn" style="margin-right: 10px;" data-month="<?php echo $month; ?>" data-year="<?php echo $year;?>" data-session_id="<?php echo $session_id;?>" data-department_id="<?php echo $department_id;?>"  class="btn btn-primary btn-sm pull-right checkbox-toggle cancelpay"> <?php echo "Cancel Payroll"; ?> </a>
                                        <button type="button" id="send_mail" style="margin-right: 10px;" name="submit" value="send_email" class="btn btn-primary btn-sm pull-right checkbox-toggle" id="load2" data-loading-text="<i class='fa fa-spinner fa-spin '></i> "><i class="fa fa-envelope"></i> <?php echo "Send Mail"; ?> </button>
                                        <button type="button" id="approvebtn" style="margin-right: 10px;" name="submit" value="approve" class="btn btn-primary btn-sm pull-right checkbox-toggle" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> "><i class="fa fa-check"></i> Approved</button>
                                        <!-- <button type="button" id="send_mail"  style="margin-right: 10px;" name="submit" value="send_email" data-loading-text="<i class='fa fa-spinner fa-spin '></i> " class="btn btn-primary btn-sm pull-right checkbox-toggle">Send Mail</button> -->
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden" name="button_type" id="button_type">
                            <div class="box-body table-responsive">
                            <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button type="button" class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                <div class="download_label"><?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('list'); ?></div>
                                <table class="table table-striped table-bordered table-hover table-responsive" id="headerTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="check-all"></th>
                                            <th>Sr.No</th>
                                            <th><?php echo $this->lang->line('staff_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('attendence'); ?></th>
                                            <th><?php echo "Basic Pay"; ?></th>
                                            <th><?php echo "GP"; ?></th>
                                            <th><?php echo "DA"; ?></th>
                                            <th><?php echo "PP"; ?></th>
                                            <th><?php echo "HRA"; ?></th>
                                            <th><?php echo "TA"; ?></th>
                                            <th><?php echo "OA"; ?></th>
                                            <th><?php echo "Gross Salary"; ?></th>
                                            <th><?php echo "Total Gross Salary"; ?></th>
                                            <th><?php echo "Other Deduction"; ?></th>
                                            <th><?php echo "PF Earning"; ?></th>
                                            <th><?php echo "PF"; ?></th>
                                            <th><?php echo "LWP"; ?></th>
                                            <th><?php echo "Profession Tax"; ?></th>
                                            <th><?php echo "Income Tax"; ?></th>
                                            <th><?php echo "Advance"; ?></th>
                                            <th><?php echo "Loan"; ?></th>
                                            <th><?php echo "Total Deduction"; ?></th>
                                            <th><?php echo "Nett Salary"; ?></th>
                                            <th><?php echo "Addition"; ?></th>
                                            <th><?php echo "Salary Hold"; ?></th>
                                            <th><?php echo "Total Salary"; ?></th>
                                            <th><?php echo "Remarks"; ?></th>
                                            <div class="hidediv">
                                                <th><?php echo "Action"; ?></th>
                                            </div>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $grossTotal = 0;
                                        $finaltotalDeduct = 0;
                                        $NettTotal = 0;
                                        $net_grosstotal_al = 0;
                                        $net_other_deduction = 0;
                                        $net_pf = 0;
                                        $pf_earning_amt = 0;
                                        $net_pf_earning = 0;
                                        $net_lwp = 0;
                                        $net_pt = 0;
                                        $net_it = 0;
                                        $net_advance = 0;
                                        $net_hold = 0;
                                        $net_loan = 0;   
                                        $net_oa=0;
                                        $net_add=0; 
                                        $totalGrandSalary = 0;
                                        //echo "<pre>";print_r($resultlist);die();
                                        foreach ($resultlist as $staff) {
                                            if($Payroll_set['attendence_by'] == 'auto'){
                                                $staffMonthAtt = $this->staffattendancemodel->getstaffleaverequest_new($staff['id'], $month, $year);
                                            }else if($Payroll_set['attendence_by'] == 'direct'){
                                                $staffMonthAtt = $this->staff_model->getStaffMonthAtt($staff['id'], $month, $year);
                                            }
                                            
                                            // echo "<pre>";
                                            // print_r ($staffMonthAtt);
                                            // echo "</pre>";
                                            
                                            // $staffMonthAtt = $this->staff_model->getStaffMonthAtt($staff['id'], $month, $year);
                                            if (($Payroll_set['attendence_by'] == 'auto') || (!empty($staffMonthAtt) && $Payroll_set['attendence_by'] == 'direct')) {
                                            $update = $this->staff_model->getSalarypayroll($staff['id'],$month,$year);
                                            $remarks ="";
                                            if(!empty($update))
                                            {
                                                $attPresent = $update['total_attendence'];
                                                $workingdays = $update['attendence'];   
                                                $basic_salary = (float)$update['basic_pay'];
                                                $gp =  (float)$update['gp'];
                                                $contact_type = $update['contract_type'];
                                                $da = (float)$update['da'];
                                                $hra = (float)$update['hra'];                                              
                                                $pp =  (float)$update['pp'];
                                                $ta = (float)$update['ta'];
                                                $other_allowance = (float)$update['other_allowance'];                                                
                                                $addition = (float)$update['addition'];
                                                $grossSalary = (float)$update['gross_salary'];
                                                $grossSalary_af_leave = (float)$update['gross_salary_al'];
                                                $lwp = (float)$update['lwp'];                                                 
                                                $pf_earning = (float)$update['pf_earning'];
                                                $pf_earning_amt = (float)$update['pf_earning'];
                                                $pf = (float)$update['pf'];
                                                $profession_tax = (float)$update['profession_tax']; 
                                                $income_tax = (float)$update['income_tax']; 
                                                $advanceAmt = (float)$update['advance']; 
                                                $loanAmt = (float)$update['loan']; 
                                                $other_deduction  =   $update['other_deduction'];
                                                $totalDeduct = (float)$update['total_deduction'];
                                                $nettSalary = (float)$update['nett_salary'];
                                                $salary_hold = (float)$update['salary_hold'];
                                                $totalsalary = (float)$update['total_salary'];
                                                $remarks = $update['remarks'];
                                                $date = $update['date'];
                                            }
                                            else
                                            {
                                                $staffAdvance = $this->staff_model->getAdvanceStaffMonth($staff['id'], $month, $year);
                                                $staffLoan = $this->staff_model->getloanByStaffId($staff['id']);
                                                // $attPresent = $staffMonthAtt['total_attendence'] ?? 0;
                                                // $workingdays = $staffMonthAtt['total_working_days'] ?? 0;
                                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), $year);
                                                $terminationLeaves = 0;
                                                if($Payroll_set['attendence_by'] == 'auto'){
                                                    if ($staff['is_active'] == 0) {
                                                        $terminationRow = $this->staff_model->getTerminationRow($staff['id']);
                                                        // echo "<pre>";
                                                        // print_r($terminationRow);die();
                                                        $terminationMonthYear = date('m-Y', strtotime($terminationRow['date_of_termination']));
                                                        $currentMonthYear = date('m', strtotime($month)).'-'.$year;
                                                        // echo $terminationMonthYear."==".$currentMonthYear;
                                                        if ($terminationMonthYear == $currentMonthYear) {
                                                            $terminationdays = date('d', strtotime($terminationRow['date_of_termination']));
                                                            $terminationLeaves = $daysInMonth - $terminationdays;
                                                        }
                                                    }

                                                    $joinMonthYear = date('m-Y', strtotime($staff['date_of_joining']));
                                                    $currentMonthYear = date('m', strtotime($month)).'-'.$year;
                                                    $joindaysleaves=0;
                                                    if ($joinMonthYear == $currentMonthYear) {
                                                        $joindays = date('d', strtotime($staff['date_of_joining']));
                                                        $joindaysleaves = $joindays - 1;
                                                    }                                                    
                                                    $leaveDays = array_sum(array_column($staffMonthAtt, 'total_days'));
                                                    if($staff['contract_type']=='dailyWages')
                                                    {
                                                        $daysInMonth = $Payroll_set['dailywages_working_days'];

                                                            $attPresentRow = $this->staffattendancemodel->getStaff_percent_days($staff['id'], $month, $year);
                                                            if(!empty($attPresentRow))
                                                            {
                                                                $attPresent = $attPresentRow['percent_days'];
                                                            }else {
                                                                
                                                                $attPresent = 0;
                                                            }
                                                            
                                                    }else {
                                                        
                                                        $attPresent = $daysInMonth - $leaveDays - $terminationLeaves - $joindaysleaves; 
                                                    }
                                                    $workingdays = $daysInMonth;
                                                }else if($Payroll_set['attendence_by'] == 'direct'){

                                                    if($staff['contract_type']=='dailyWages')
                                                    {
                                                        // $daysInMonth = $Payroll_set['dailywages_working_days'];
                                                        $attPresentRow = $this->staffattendancemodel->getStaff_percent_days($staff['id'], $month, $year);
                                                        if(!empty($attPresentRow))
                                                        {
                                                            $attPresent = $attPresentRow['percent_days'];
                                                        }else {
                                                            
                                                            $attPresent = 0;
                                                        }
                                                    }else {
                                                        
                                                        $attPresent = $staffMonthAtt['total_attendence']?? 0;
                                                    }
                                                    $workingdays = $staffMonthAtt['total_working_days']?? 0;
                                                }
                                                
                                                $salary = (float)$staff['basic_salary'];
                                                if($staff['contract_type']=='dailyWages')
                                                {
                                                    if ($staff['contract_status'] == 1) {

                                                        $dailywages =  (float)$staff['basic_pay'];
                                                        
                                                        $basic_salary = round($dailywages * $attPresent);
                                                        $gp =  0; 
                                                        // $basic_salary = 0;
                                                        $gp = 0;
                                                    }else {                                                    
                                                        $dailywages =  (float)$staff['dailywages'];
                                                        $basic_salary = (float)$staff['basic_pay'];
                                                        $gp =  (float)$staff['gp']; 
                                                        $basic_salary = round(($basic_salary/$payroll_settings['dailywages_working_days']) * $attPresent);
                                                        $gp = round(($gp/$payroll_settings['dailywages_working_days']) * $attPresent);
                                                    }
                                                }
                                                else{
                                                    $dailywages =  0;
                                                    $basic_salary =  (float)$staff['basic_pay']; 
                                                    $gp = (float)$staff['gp'];     
                                                }
                                                
                                                if($staff['contract_type']=='dailyWages')
                                                {
                                                    if ($staff['contract_status'] == 1) {
                                                        $contact_type = "dailyWages";
                                                        $da =  0;
                                                        $hra = 0;                                              
                                                        $pp =  0;
                                                        $ta = 0;
                                                        $other_allowance = 0;
                                                    }else {

                                                        $contact_type = "dailyWages";
                                                        $da =  round(($staff['da'] / $payroll_settings['dailywages_working_days'])  * $attPresent);
                                                        $hra = round(($staff['hra'] / $payroll_settings['dailywages_working_days'])  * $attPresent);                                              
                                                        $pp =  round(($staff['personal_pay'] / $payroll_settings['dailywages_working_days'])  * $attPresent);
                                                        $ta = round(($staff['ta'] / $payroll_settings['dailywages_working_days'])  * $attPresent);
                                                        $other_allowance = round(($staff['other_allowance'] / $payroll_settings['dailywages_working_days'] ) * $attPresent);
                                                    }
                                                }
                                                else
                                                {
                                                    $contact_type = "Salary";
                                                    $da = round(($basic_salary+$gp) * round(($payroll_settings['da']/100),2)); 
                                                    $hra = round(($basic_salary+$gp) * round(($payroll_settings['hra']/100),2));                                                 
                                                    $pp =  round(($basic_salary+$gp) * round(($payroll_settings['pp']/100),2));
                                                    $ta = (float)$staff['ta'];
                                                    $other_allowance = round($salary - ($basic_salary+$gp+$da+$hra+$pp+$ta));
                                                }
                                                $addition = $this->staff_model->get_other_addition($staff['id'], $month, $year);
                                                $totalGrossSalary = $basic_salary+$gp+$da+$hra+$pp+$ta+$other_allowance ;
                                                $grossSalary = $totalGrossSalary; 
                                                $oneday = round($grossSalary / $workingdays,2);
                                                $absentdays = $workingdays - $attPresent;
                                                if($staff['contract_type']=='dailyWages')
                                                {
                                                    if ($staff['contract_status'] == 1) {
                                                        $grossSalary_af_leave = $grossSalary;
                                                        $lwp = 0;                                                  
                                                        $pf_earning = 0;
                                                        $pf_earning_amt = 0;
                                                    }else {
                                                        
                                                        $grossSalary_af_leave = $grossSalary;
                                                        $lwp = round($grossSalary-$grossSalary_af_leave);                                                  
                                                        $pf_earning = round(($basic_salary + $gp + $da +  $pp + $ta));
                                                    }
                                                    $income_tax = (float)$staff['income_tax']; 
                                                }
                                                else
                                                {
                                                    $grossSalary_af_leave = round($grossSalary - ($absentdays * $oneday));
                                                    $lwp = round($grossSalary-$grossSalary_af_leave);                                                
                                                    $pf_earning = round((($basic_salary + $gp + $da +  $pp + $ta)/$workingdays) * $attPresent);
                                                }                                            
                                                //PF calculation
                                                    if($pf_earning  >= $payroll_settings['pf_earning_limit']){
                                                        $pf_earning_amt = $payroll_settings['pf_earning_limit'];
                                                        $pf = round($payroll_settings['pf_earning_limit'] * round(($payroll_settings['ey_pf']/100),2));
                                                    } else {
                                                        $pf = round(($pf_earning * round(($payroll_settings['ey_pf']/100),2)));
                                                        $pf_earning_amt = $pf_earning;
                                                    }
                                                //profession tax calculation 
                                                $gender = $staff['gender'];
                                                if($gender == "Male")
                                                {$profession_tax = $this->staff_model->get_staff_pt('M',$grossSalary_af_leave);}
                                                elseif($gender == "Female")
                                                {$profession_tax = $this->staff_model->get_staff_pt('F',$grossSalary_af_leave);}

                                                //$profession_tax = !empty($update) ? (float)$update['profession_tax'] : (float)$staff['profession_tax']; 
                                                
                                                $nmonth = date("m", strtotime($month));
                                                $advanceAmt = $this->staff_model->get_salary_advance_recovery($staff['id'],$month,$year);                                           
    
                                                $loanAmt = $this->staff_model->get_salary_recovery($staff['id']); 
                                                
                                                $other_deduction   =   $this->staff_model->get_other_deductions($staff['id'],$month,$year);
                                                $income_tax = $this->staff_model->get_other_income_tax($staff['id'], $month, $year);
                                                $salary_hold = $this->staff_model->get_other_salary_hold($staff['id'], $month, $year);                                            
                                                
                                                if ($staff['contract_status'] == 1) {
                                                    $profession_tax = 0;
                                                    $income_tax = round($basic_salary * 0.01);
                                                }
                                                $allDeduct = round($lwp + $pf + $profession_tax + $income_tax + $advanceAmt + $loanAmt + $other_deduction );
                                                $totalDeduct = number_format($allDeduct,2,".","");
                                                $nettTotSalary =  $grossSalary - $totalDeduct;
                                                $nettSalary = number_format($nettTotSalary,2,'.',"");
                                                $totalsalary = $nettTotSalary + $addition - $salary_hold;
                                                $date = date('Y-m-d');
                                                

                                            }
                                            if (!empty($update) && $update['approval_time'] !="") {
                                                $readonly = "readonly";
                                            } else {
                                                $readonly = "";
                                            }
                                        ?>
                                            <tr>
                                                <input type="hidden" name="id[]" value="<?php echo  !empty($update) ? $update['id'] : "" ;?>">
                                                <input type="hidden" name="month" id="month" value="<?php echo $month;?>">
                                                <input type="hidden" name="year" id="year" value="<?php echo $year;?>">
                                                <input type="hidden" name="department_id" id="department_id" value="<?php echo $department_id;?>">
                                                <input type="hidden" name="date" id="date" value="<?php echo $date;?>">
                                                <input type="hidden" name="staff_id[]" id="staff_id" value="<?php echo $staff['id'];?>">
                                                <input type="hidden" name="basic_salary[]" id="basic_salary<?php echo $count;?>" value="<?php echo $basic_salary; ?>">
                                                <input type="hidden" name="gp[]" id="gp<?php echo $count;?>" value="<?php echo $gp; ?>">
                                                <input type="hidden" name="da[]" id="da<?php echo $count;?>" value="<?php echo $da; ?>">
                                                <input type="hidden" name="pp[]" id="pp<?php echo $count;?>" value="<?php echo $pp; ?>">
                                                <input type="hidden" name="hra[]" id="hra<?php echo $count;?>" value="<?php echo $hra; ?>">
                                                <input type="hidden" name="ta[]" id="ta<?php echo $count;?>" value="<?php echo $ta; ?>">
                                                <input type="hidden" name="lwp[]" id="lwp<?php echo $count;?>" value="<?php echo number_format($lwp,2,'.',""); ?>">
                                                
                                                <input type="hidden" name="pf_earning[]" id="pf_earning<?php echo $count;?>" value="<?php echo $pf_earning_amt; ?>">
                                                <input type="hidden" name="pf[]" id="pf<?php echo $count;?>" value="<?php echo $pf; ?>">
                                                <input type="hidden" name="mng_pf[]" id="mng_pf<?php echo $count;?>" value="<?php echo $pf; ?>">

                                                <input type="hidden" name="profession_tax[]" id="profession_tax<?php echo $count;?>" value="<?php echo $profession_tax; ?>">
                                                <input type="hidden" name="income_tax[]" id="income_tax<?php echo $count;?>" value="<?php echo $income_tax; ?>">
                                                <input type="hidden" name="gross_salary[]" id="gross_salary<?php echo $count;?>" value="<?php echo $grossSalary; ?>">
                                                <input type="hidden" name="gross_salary_al[]" id="gross_salary_al<?php echo $count;?>" value="<?php echo $grossSalary_af_leave; ?>">
                                                <input type="hidden" name="total_attendence[]" id="total_attendence<?php echo $count;?>" value="<?php echo $workingdays; ?>">
                                                <input type="hidden" name="attendence[]" id="attendence<?php echo $count;?>" value="<?php echo $attPresent; ?>">
                                                <input type="hidden" name="contract_type[]" id="contract_type<?php echo $count;?>" value="<?php echo $contact_type; ?>">
                                                <input type="hidden" name="salary_hold[]" id="salary_hold<?php echo $count;?>" value="<?php echo $salary_hold; ?>">

                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" class="checkbox-item" name="check[]" id="check" value="<?php echo $staff['id']; ?>"></td>
                                                <td><?php echo $count;?></td>
                                                <td id="applied" <?php if(!empty($update)){ echo "style='background-color:green;color:white'";}?>><?php echo $staff['employee_id']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url('admin/staff/edit/').$staff['id']; ?> "><?php echo $staff['name'] . " " . $staff['surname']; ?></a></td>
                                                <?php if($staff['contract_type']=='dailyWages')
                                                { ?>
                                                <td><?php echo  $attPresent ?></td>
                                                <?php }else{ ?>
                                                <td><?php echo $attPresent. "/" . $workingdays ?></td>                                                    
                                                <?php } ?>
                                                <td><?php echo number_format($basic_salary,2,'.',''); ?></td>
                                                <td><?php echo number_format($gp,2,'.',''); ?></td>
                                                <td><?php echo number_format($da,2,'.',''); ?></td>
                                                <td><?php echo number_format($pp,2,'.',''); ?></td>
                                                <td><?php echo number_format($hra,2,'.',''); ?></td>
                                                <td><?php echo number_format($ta,2,'.',''); ?></td>
                                                <td>
                                                    <input style="width: 50px;" <?php echo $readonly;?> type="text" class="form-control other_allowance" data-count="<?php echo $count;?>" name="other_allowance[]" id="other_allowance<?php echo $count;?>" value="<?php echo number_format($other_allowance,2,'.',''); ?>">
                                                </td> 
                                                <td id="grossamt<?php echo $count;?>" class="grosssalary"><?php echo number_format($grossSalary,2,'.',""); ?></td>
                                                <td  class="grosssalary_al"><?php echo number_format($grossSalary_af_leave,2,'.',""); ?></td>
                                                <td>
                                                    <input style="width: 50px;" <?php echo $readonly;?> type="text" class="form-control other_deduction" name="other_deduction[]" data-count="<?php echo $count;?>" id="other_deduction<?php echo $count;?>" value="<?php echo number_format($other_deduction,2,'.',''); ?>">
                                                </td>
                                                <td><?php echo number_format($pf_earning_amt ,2,'.','');?></td>
                                                <td class="cl_other_deduction"><?php echo number_format($pf,2,'.','');?></td>
                                                <td class="cl_lwp"><?php echo number_format($lwp,2,'.',""); ?></td>
                                                <td class="cl_pt"><?php echo number_format($profession_tax,2,'.',''); ?></td>
                                                <td class="cl_it"><?php echo number_format($income_tax,2,'.','');?></td>
                                                <td>
                                                    <input style="width: 50px;" <?php echo $readonly;?> type="text" class="form-control adv_amount" name="adv_amount[]" data-count="<?php echo $count;?>" id="adv_amount<?php echo $count;?>" value="<?php echo number_format($advanceAmt,2,'.',''); ?>">
                                                </td>
                                                <td>
                                                    <input style="width: 50px;" <?php echo $readonly;?> type="text" class="form-control loan_emi" name="loan_emi[]" data-count="<?php echo $count;?>" id="loan_emi<?php echo $count;?>" value="<?php echo number_format($loanAmt,2,'.',''); ?>">
                                                </td>
                                                <?php 
                                                

                                                 ?>
                                                <input type="hidden" name="total_deduction[]" id="total_deduction<?php echo $count;?>" value="<?php echo number_format($totalDeduct,2,".",""); ?>">
                                                <input type="hidden" name="nett_salary[]" id="nett_salary<?php echo $count;?>" value="<?php echo number_format($nettSalary,2,'.',""); ?>">
                                                <input type="hidden" name="total_salary[]" id="total_salary<?php echo $count;?>" value="<?php echo number_format($totalsalary,2,'.',""); ?>">
                                                <td class="totaldeduct<?php echo $count;?> alltotaldeduct"><?php echo number_format($totalDeduct,2,".",""); ?></td>
                                                <td id="nettamt<?php echo $count;?>" class="netsalary"><?php echo number_format($nettSalary,2,".",""); ?></td>
                                                <td>
                                                    <input style="width: 50px;" <?php echo $readonly;?> type="text" class="form-control addition" data-count="<?php echo $count;?>" name="addition[]" id="addition<?php echo $count;?>" value="<?php echo number_format($addition,2,'.',''); ?>">
                                                </td> 
                                                
                                                <td><?php echo  number_format($salary_hold * -1,2,".","");?></td>
                                                <td><?php echo  number_format($totalsalary,2,".","");?></td>
                                                <td>
                                                    <input style="width: 50px;" type="text" <?php echo $readonly;?> class="form-control remarks" name="remarks[]" data-count="<?php echo $count;?>" id="remarks<?php echo $count;?>" value="<?php echo $remarks; ?>">
                                                </td>                                                
                                                <div class="hidediv">
                                                    <td class="mailbox-date pull-right">
                                                        <?php if(!empty($update)){ ?>
                                                        <button type="button" class="btn btn-default btn-xs printInv" data-staff_id="<?php echo $staff['id'];?>" data-month="<?php echo $month;?>"  data-year="<?php echo $year;?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                                                        <a data-placement="top" href="<?php echo base_url('admin/payroll/deleteSalary/'.$staff['id']."/".$month."/".$year);?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                </div>
                                            </tr>
                                            <?php 
                                            $net_oa+=$other_allowance;
                                            $net_add+=$addition;
                                            $grossTotal += $grossSalary;
                                            $net_grosstotal_al += $grossSalary_af_leave;
                                            $net_other_deduction += $other_deduction;
                                            $net_pf +=$pf;
                                            $net_pf_earning += $pf_earning_amt;
                                            $net_lwp += $lwp;
                                            $net_pt += $profession_tax;
                                            $net_it += $income_tax;
                                            $net_advance += $advanceAmt;
                                            $net_loan += $loanAmt;
                                            $finaltotalDeduct += $totalDeduct;
                                            $NettTotal += $nettSalary;
                                            $net_hold += $salary_hold;
                                            $totalGrandSalary += $totalsalary;
                                            ?>
                                        <?php
                                        $count++;
                                        } 
                                    }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b id="gross_oa"><?php echo number_format($net_oa,2,".",""); ?></b></td>
                                            <td><b id="grosstotal"><?php echo number_format($grossTotal,2,".",""); ?></b></td>
                                            <td><b id="grosstotal_al"><?php echo number_format($net_grosstotal_al,2,".",""); ?></b></td>
                                            <td><b id="gross_od"><?php echo number_format($net_other_deduction,2,".",""); ?></b></td>
                                            <td><b><?php echo number_format($net_pf_earning,2,".",""); ?></b></td>
                                            <td><b id="gross_pf"><?php echo number_format($net_pf,2,".",""); ?></b></td>
                                            <td><b id="gross_lwp"><?php echo number_format($net_lwp,2,".",""); ?></b></td>
                                            <td><b id="gross_pt"><?php echo number_format($net_pt,2,".",""); ?></b></td>
                                            <td><b id="gross_it"><?php echo number_format($net_it,2,".",""); ?></b></td>
                                            <td><b id="gross_advance"><?php echo number_format($net_advance,2,".",""); ?></b></td>
                                            <td><b id="gross_loan"><?php echo number_format($net_loan,2,".",""); ?></b></td>
                                            <td><b id="totaldeduct"><?php echo number_format($finaltotalDeduct,2,".",""); ?></b></td>
                                            <td><b id="netttotal"><?php echo number_format($NettTotal,2,".",""); ?></b></td>
                                            <td><b id="gross_add"><?php echo number_format($net_add,2,".",""); ?></b></td>
                                            <td><b id="gross_add"><?php echo number_format($net_hold,2,".",""); ?></b></td>
                                            <td><b><?php echo number_format($totalGrandSalary,2,".",""); ?></b></td>
                                            <td class="hidediv"></td>
                                            <td class="hidediv"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                </div>
                </div>
            <?php
                }
            ?>
            </div>
        </div>

    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {

        $(".other_allowance,.addition").on("keyup change", function(e) {
            var id = $(this).data('count');
            gross_salary(id);
            grand_total();
        });
        function grand_total(){    

            var gross_oa = 0;
            $(".other_allowance").each(function(){
                    gross_oa += +$(this).val();
            });               
            $("#gross_oa").text(gross_oa.toFixed(2));

            var gross_add = 0;
            $(".addition").each(function(){
                    gross_add += +$(this).val();
            });               
            $("#gross_add").text(gross_add.toFixed(2));
            
            var gross_od = 0;
            $(".other_deduction").each(function(){
                    gross_od += +$(this).val();
            });               
            $("#gross_od").text(gross_od.toFixed(2));

            var grosssalary = sum_cal('.grosssalary');
            $("#grosstotal").text(grosssalary.toFixed(2));
            
            var grosssalary_al = sum_cal('.grosssalary_al');
            $("#grosstotal_al").text(grosssalary_al.toFixed(2));

            var gross_od = 0;
            $(".other_deduction").each(function(){
                    gross_od += +$(this).val();
            });               
            $("#gross_od").text(gross_od.toFixed(2));

            var gross_pf = sum_cal('.cl_pf');
            $("#gross_pf").text(gross_pf.toFixed(2));

            var gross_lwp = sum_cal('.cl_lwp');
            $("#gross_lwp").text(gross_lwp.toFixed(2));

            var gross_pt = sum_cal('.cl_pt');
            $("#gross_pt").text(gross_pt.toFixed(2));

            var gross_it = sum_cal('.cl_it');
            $("#gross_it").text(gross_it.toFixed(2));

            var gross_adv = 0;
            $(".adv_amount").each(function(){
                    gross_adv += +$(this).val();
            });            
            $("#gross_advance").text(gross_adv.toFixed(2));
            

            var gross_loan = 0;
            $(".loan_emi").each(function(){
                    gross_loan += +$(this).val();
            });             
            $("#gross_loan").text(gross_loan.toFixed(2));

            var totaldeduct = sum_cal('.alltotaldeduct');
            $("#totaldeduct").text(totaldeduct.toFixed(2));

            var netttotal = sum_cal('.netsalary');
            $("#netttotal").text(netttotal.toFixed(2));
        }
        function sum_cal(cls) {
            var sum = 0;
            $(cls).each(function() {
                var val = parseFloat($(this).text()) || 0;
                sum += parseFloat(val);
            });
            return sum;
        }
        function gross_salary(id){
            var totaldeduct = parseFloat($(".totaldeduct"+id).text()) || 0;
            var basic_salary = parseFloat($("#basic_salary"+id).val()) || 0;
            var gp = parseFloat($("#gp"+id).val()) || 0;
            var da = parseFloat($("#da"+id).val()) || 0;
            var hra = parseFloat($("#hra"+id).val()) || 0;
            var ta = parseFloat($("#ta"+id).val()) || 0;
            var pp = parseFloat($("#pp"+id).val()) || 0;
            var other_allowance = parseFloat($("#other_allowance"+id).val()) || 0;
            var addition = parseFloat($("#addition"+id).val()) || 0;
            // var amount = Math.round(parseFloat(basic_salary) + parseFloat(gp) + parseFloat(da) + parseFloat(hra) + parseFloat(ta) + parseFloat(other_allowance)).toFixed(2);
            // var totalAll = Math.round(parseFloat(amount) - parseInt(totaldeduct)).toFixed(2);
            var amount = basic_salary + gp + da + hra + ta + other_allowance + pp + addition;
            var totalAll = amount - totaldeduct;
            
            $("#grossamt"+id).text(amount.toFixed(2));
            $("#gross_salary"+id).val(amount.toFixed(2));
            $("#nettamt"+id).text(totalAll.toFixed(2));
            $("#nett_salary"+id).val(totalAll.toFixed(2));
        }

        $(".adv_amount,.loan_emi,.other_deduction").on("keyup change", function(e) {
            var id = $(this).data('count');
            total_deduct(id);
            grand_total()
        });
        function total_deduct(id){
            var grossamt = parseFloat($("#grossamt"+id).html()) || 0;
            var lwp = parseFloat($("#lwp"+id).val()) || 0;
            var pf = parseFloat($("#pf"+id).val()) || 0;
            var profession_tax = parseFloat($("#profession_tax"+id).val())|| 0;
            var income_tax = parseFloat($("#income_tax"+id).val())|| 0;
            var adv_amount = parseFloat($("#adv_amount"+id).val())|| 0;
            var loan_emi = parseFloat($("#loan_emi"+id).val())|| 0;
            var other_deduction = parseFloat($("#other_deduction"+id).val())|| 0;
            var amount = lwp + pf + profession_tax + income_tax + adv_amount + loan_emi + other_deduction;
            var totalAll = grossamt - amount;
            // console.log(grossamt);
            // console.log(amount);
            // console.log(totalAll);
            $(".totaldeduct"+id).text(amount.toFixed(2));
            $("#total_deduction"+id).val(amount.toFixed(2));
            $("#nettamt"+id).text(totalAll.toFixed(2));
            $("#nett_salary"+id).val(totalAll.toFixed(2));
        }

        $('#check-all').on('click', function() {
            var allChecked = $('.checkbox-item').length === $('.checkbox-item:checked').length;

            if (allChecked) {
                // If all checkboxes are checked, uncheck all
                $('.checkbox-item').prop('checked', false);
            } else {
                // Otherwise, check all checkboxes
                $('.checkbox-item').prop('checked', true);
            }
        });

    });

    $(document).on('click', '#generate', function() {
        $('#generate').button('loading');
        var button = $(this).val();
        $('#button_type').val(button);
        $('#save_Pay').submit();
    });

    $(document).on('click', '#send_mail', function() {
        $('#send_email').button('loading');
        var button = $(this).val();
        $('#button_type').val(button);
        $('#save_Pay').submit();
    });
    $(document).on('click', '#approvebtn', function() {
        $('#send_email').button('loading');
        var button = $(this).val();
        $('#button_type').val(button);
        $('#save_Pay').submit();
    });

    $("#save_Pay").on("submit", function(e) {
        var submit = $("#submit").val();
        e.preventDefault();
        $.ajax({
            url: "<?php echo site_url('admin/payroll/staffPay') ?>",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function(data) {
                // $("#spinner").show();
                // $("#submit").attr("disabled",true);
                // $('#generate').button('loading');
            },
            success: function(data) {
                // $("#spinner").hide(); 
                // $("#submit").attr("disabled",false);
                // $('#generate').removeClass('dropdownloading');
                if (data.error) {
                    $.each(data, function(key, value) {
                        if (value) {
                            $('#error-' + key).html(value);
                            $('#input-' + key).addClass("border-danger");
                        } else {
                            $('#error-' + key).html(" ");
                            $('#input-' + key).removeClass("border-danger");
                        }
                    });
                }
                if (data.success) {
                    $('#generate').button('reset');
                    $('#send_mail').button('reset');
                    $('#form .form-control').removeClass("error");
                    $('#form .error').html(" ");
                    $('#applied').addClass('colorbg');
                    if (data.type == 1) {
                        Popup(data.response);
                    }else if(data.type == 2){
                        successMsg("Mail Sent Successfully");
                    }else {
                        successMsg("Approved Successfully");
                    }
                }
            }
        });
    });

    $(document).on('click', '.printInv', function() {
        var staff_id = $(this).data('staff_id');
        var payroll_id = $(this).data('payroll_id');
        var month = $(this).data('month');
        var year = $(this).data('year');
        $.ajax({
            url: '<?php echo site_url("admin/payroll/printPaySlipNew") ?>',
            type: 'post',
            data: {
                'staff_id': staff_id,
                'payroll_id': payroll_id,
                'month': month,
                'year': year,
            },
            success: function(response) {
                Popup(response);
            }
        });
    });

    $(document).on('click', '.cancelpay', function() {
        var month = $(this).data('month');
        var year = $(this).data('year');
        var session_id = $(this).data('session_id');
        var department_id = $(this).data('department_id');
        $.ajax({
            url: '<?php echo site_url("admin/payroll/cancelpay") ?>',
            type: 'post',
            data: {
                'month': month,
                'year': year,
                'session_id': session_id,
                'department_id': department_id,
            },
            success: function(data) {
                // Popup(response);
                successMsg("Pay Cancel Successfully");   
                setTimeout(function() {
                location.reload();
            }, 1000);
            }
        });
    });

    // function popup(data) {
    //     var base_url = '<?php echo base_url() ?>';
    //     var frame1 = $('<iframe />');
    //     frame1[0].name = "frame1";
    //     frame1.css({
    //         "position": "absolute",
    //         "top": "-1000000px"
    //     });
    //     $("body").append(frame1);
    //     var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
    //     frameDoc.document.open();
    //     //Create a new HTML document.
    //     frameDoc.document.write('<html>');
    //     frameDoc.document.write('<head>');
    //     frameDoc.document.write('<title></title>');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    //     frameDoc.document.write('</head>');
    //     frameDoc.document.write('<body>');
    //     frameDoc.document.write(data);
    //     frameDoc.document.write('</body>');
    //     frameDoc.document.write('</html>');
    //     frameDoc.document.close();
    //     setTimeout(function() {
    //         document.getElementById('printDiv').contentWindow.focus();
    //         document.getElementById('printDiv').contentWindow.print();
    //         $("#printDiv", top.document).remove();
    //         // frame1.remove();
    //         if (winload == false) {
    //             window.location.href='<?php echo site_url('admin/payroll/payroll_group')?>';
    //         }
    //     }, 500);


    //     return true;
    // }

    function getPayslip(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },
            success: function(result) {
                $("#print1").html("<a href='#'  class='pull-right modal-title moprintblack'  onclick='printData(" + id + ")'  title='Print' ><i class='fa fa-print'></i></a>");
                $("#testdata").html(result);
            }
        });

        $('#payslipview').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });

    };

    function printData(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },
            success: function(result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }


    


   

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>
<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }
</script>
<script>
    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

    function printDiv() {
        // document.getElementsByClassName("hidediv").style.display = "none";
        // $(".hidediv").hide();
        toggle('hidediv','none');
        // document.getElementsByClassName("cancelpay").style.display = "none";
        document.getElementById("cancelpaybtn").style.display = "none";
        document.getElementById("generate").style.display = "none";
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }

    function fnExcelReport() {
        var tab = document.getElementById('headerTable'); // ID of the table
        var tab_text = "<table border='2px'><tr>";

        for (var j = 0; j < tab.rows.length; j++) {
            tab_text += "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text += "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); // Remove links
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // Remove images
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // Remove input elements

        var blob = new Blob([tab_text], { type: 'application/vnd.ms-excel' });
        var url = URL.createObjectURL(blob);

        // Create a temporary link to download the file
        var a = document.createElement('a');
        a.href = url;
        a.download = 'report.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        // Revoke the object URL to free up resources
        URL.revokeObjectURL(url);
    }

    function toggle(className, displayState){
        var elements = document.getElementsByClassName(className)
        for (var i = 0; i < elements.length; i++){
        elements[i].style.display = displayState;
    }
}
</script>