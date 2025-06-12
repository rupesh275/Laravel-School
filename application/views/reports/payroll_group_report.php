<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
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

    .table-container {
        max-height: 400px; /* Set a fixed height for vertical scrolling */
        overflow: auto; /* Enable both horizontal and vertical scrolling */
        border: 1px solid #ddd; /* Optional: border around the container */
    }

    /* Hide scrollbars in print */
    @media print {

        .table-responsive {
            overflow-x: visible !important; /* Ensure no horizontal scrollbars */
            width: 100% !important; /* Ensure the table width fits the page */
            /* Optional: Ensure the table is not cut off */
            display: none !important; /* Prevent cutting off content */
        }

        /* You may also want to ensure that elements are properly visible */

    }

    @media print {
        body {
            font-size: 10px;
        }
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
        <?php $this->load->view('reports/_payroll_reports'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <form action="<?php echo site_url('report/payroll_reports') ?>" method="post" class="" id="s">
                        <div class="box-body row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('month') ?></label>
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
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>

                                    <select autofocus="" id="year" name="year" class="form-control">
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
                            <?php /*  ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff')." ".$this->lang->line('category'); ?></label>

                                    <select autofocus="" id="payroll_category" name="payroll_category" class="form-control">
                                        <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($payroll_category as $key => $value) {
                                        ?>
                                            <option value="<?php echo $value['id'] ?>" <?php if(isset($payroll_category_id) && $payroll_category_id == $value['id']){ echo "selected";} ?>><?php echo $value['category_name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                        <option value="teaching-non-teaching" <?php if(isset($payroll_category_id) && $payroll_category_id == "teaching-non-teaching"){ echo "selected";} ?>>Teaching & Non Teaching</option>
                                    </select>
                                </div>
                            </div>
                            <?php */ ?>

                            <div id='date_result'>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm  pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
                    
                   
                    if (isset($resultlist)) {
                    ?>
                    <div class="" id="transfee">
                        <div class="box-body table-container">
                            <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                             <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                            <table class="table table-striped table-bordered  table-hover " id="headerTable" data-export-title="<?php echo $this->lang->line('admission') . " " . $this->lang->line('report'); ?>">
                                <thead>
                                    <tr>
                                        <th colspan="36" style="text-align: center;font-size: 20px;"><?php echo $setting->name; ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="36" style="text-align: center;"><?php echo $setting->address; ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="36" style="text-align: center;">Original Bill/Supplementary Bill/Advance Salary Grants Bill for the Month of : <span style="padding-left: 180px;"><?php echo $month . " " . $year; ?></span> <span style="padding-left: 150px;"> </span></th>
                                    </tr>
                                    <tr>
                                        <th colspan="13" style="text-align: center;"></th>
                                        <th colspan="10" style="text-align: center;"><?php echo "Details of the Admissing"; ?></th>
                                        <th colspan="9" style="text-align: center;">Deduction</th>
                                        <th colspan="4" style="text-align: center;"></th>
                                    </tr>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th><?php echo $this->lang->line('name'); ?> of the Employee</th>
                                        <th><?php echo "DOJ"; ?></th>
                                        <th><?php echo "Birth Date"; ?></th>
                                        <th><?php echo "Retirement Date / Year"; ?></th>
                                        <th><?php echo "Bank A/C No"; ?></th>
                                        <th><?php echo "MALE / FEMALE"; ?></th>
                                        <th><?php echo "Biometric ID"; ?></th>
                                        <th><?php echo "Days for the Month"; ?></th>
                                        <th><?php echo "Leave Ded"; ?></th>
                                        <th><?php echo "Present Days"; ?></th>
                                        <th><?php echo "SECTION"; ?></th>
                                        <th><?php echo "Designation"; //"Date Of Joining"; ?></th>
                                        <!-- <th>Attendance</th> -->
                                        <th><?php echo "Scale Of Pay"; ?></th>
                                        <th><?php echo "Basic Pay"; ?></th>
                                        <th><?php echo "GP"; ?></th>
                                        <th><?php echo "DA (".$payroll_settings['da']." %)"; ?></th>
                                        <th><?php echo "PP (".$payroll_settings['pp']." %)"; ?></th>
                                        <th><?php echo "H.R.A (".$payroll_settings['hra']." %)"; ?></th>
                                        <th><?php echo "T.A"; ?></th>
                                        <th><?php echo "OA"; ?></th>
                                        <th><?php echo "Gross Salary"; ?></th>
                                        <th><?php echo "Gross Salary After Leave"; ?></th>
                                        <th><?php echo "Other Deduction"; ?></th>
                                        <th><?php echo "PF Earning"; ?></th>
                                        <th><?php echo "PF"; ?></th>
                                        <th><?php echo "LWP"; ?></th>
                                        <th><?php echo "Profession Tax"; ?></th>
                                        <th><?php echo "Income Tax"; ?></th>
                                        <th><?php echo "Advance"; ?></th>
                                        <th><?php echo "Loan"; ?></th>
                                        <th><?php echo "Deduction Total "; ?></th>
                                        <th><?php echo "Net Salary"; ?></th>
                                        <th><?php echo "Addition"; ?></th>
                                        <th><?php echo "Salary Hold"; ?></th>
                                        <th><?php echo "Total Salary"; ?></th>
                                        <th>Signature of the Employee</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (!empty($resultlist)) {
                                        
                                    ?>
                                            <?php
                                                $emp_name = "";
                                            ?>
                                                <?php
                                                $net_oa = 0;
                                                $net_add = 0;
                                                $grossTotal = 0;
                                                $net_grosstotal_al = 0;
                                                $net_other_deduction = 0;
                                                $net_pf = 0;
                                                $net_pf_earning = 0;
                                                $net_lwp = 0;
                                                $net_pt = 0;
                                                $net_it = 0;
                                                $net_advance = 0;
                                                $net_loan = 0;
                                                $finaltotalDeduct = 0;
                                                $net_hold = 0;
                                                $NettTotal = 0;
                                                $grandTotal = 0;
                                                if (!empty($resultlist)) {
                                                    $count = 1;
                                                    $i = 0;
                                                    foreach ($resultlist as $key => $value) {
                                                            
                                                        $update = $this->staff_model->getSalarypayroll($value['id'], $month, $year);

                                                        if (!empty($update) && $update['payroll_category_id'] != 6) { //&& not daily wages
                                                            $leavedays = $update['total_attendence']-$update['attendence'];
                                                ?>
                                                            <tr>
                                                                <td><?php echo $count; ?></td>
                                                                <td><?php echo $value['name'] . " " . $value['surname']; ?></td>
                                                                <td><?php echo $value['date_of_joining'] !="" ? date('d-m-Y', strtotime($value['date_of_joining'])) : ""; ?></td>
                                                                <td><?php echo $value['dob'] !="" ? date('d-m-Y', strtotime($value['dob'])) : ""; ?></td>
                                                                <td><?php echo $update['date_of_retirement'] !="" ? date('d-m-Y', strtotime($update['date_of_retirement'])) : ""; ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo $value['bank_account_no']; ?></td>
                                                                <td><?php echo $value['gender']; ?></td>
                                                                <td><?php echo $value['biometric_id']; ?></td>
                                                                <td><?php echo $update['total_attendence']; ?></td>
                                                                <td><?php echo $leavedays; ?></td>
                                                                <td><?php echo $update['attendence']; ?></td>
                                                                <td><?php echo $value['department']; ?></td>
                                                                <td><?php echo $value['designation']; ?></td>
                                                                <!-- <td><?php echo $update['total_attendence'] . "/" . $update['attendence']; ?></td> -->
                                                                <td><?php echo $value['scale_of_pay']; ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['basic_pay'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['da'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['hra'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['ta'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['other_allowance'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gross_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gross_salary_al'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['other_deduction'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pf_earning'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pf'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['lwp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['profession_tax'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['income_tax'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['advance'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['loan'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['total_deduction'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['nett_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['addition'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['salary_hold'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['total_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"></td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                            $net_oa += $update['other_allowance'];
                                                            $net_add += $update['addition'];
                                                            $grossTotal += $update['gross_salary'];
                                                            $net_grosstotal_al += $update['gross_salary_al'];
                                                            $net_other_deduction += $update['other_deduction'];
                                                            $net_pf += $update['pf'];
                                                            $net_pf_earning += $update['pf_earning'];
                                                            $net_lwp += $update['lwp'];
                                                            $net_pt += $update['profession_tax'];
                                                            $net_it += $update['income_tax'];
                                                            $net_advance += $update['advance'];
                                                            $net_loan += $update['loan'];
                                                            $finaltotalDeduct += $update['total_deduction'];
                                                            $NettTotal += $update['nett_salary'];
                                                            $net_hold += $update['salary_hold'];
                                                            $grandTotal += $update['total_salary'];
                                                            ?>
                                        <?php
                                                        }
                                                        ?>
                                                        
                                                        <?php
                                                        /*
                                                        if (!empty($update) && $update['payroll_category_id'] == 6) { // && daily wages
                                                            $i++;
                                                            if ($i == 1) {
                                                            ?>
                                                            <tr>
                                                                <td> <b>Daily Wages</b></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <!-- <td></td> -->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $count; ?></td>
                                                                <td><?php echo $value['name'] . " " . $value['surname']; ?></td>
                                                                <td><?php echo $value['date_of_joining'] !="" ? date('d-m-Y', strtotime($value['date_of_joining'])) : ""; ?></td>
                                                                <td><?php echo $value['dob'] !="" ? date('d-m-Y', strtotime($value['dob'])) : ""; ?></td>
                                                                <td><?php echo $update['date_of_retirement'] !="" ? date('d-m-Y', strtotime($update['date_of_retirement'])) : ""; ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo $value['bank_account_no']; ?></td>
                                                                <td><?php echo $value['gender']; ?></td>
                                                                <td><?php echo $value['biometric_id']; ?></td>
                                                                <td><?php echo $update['total_attendence']; ?></td>
                                                                <td><?php echo $leavedays; ?></td>
                                                                <td><?php echo $update['attendence']; ?></td>
                                                                <td><?php echo $value['department']; ?></td>
                                                                <td><?php echo $value['designation']; ?></td>
                                                                <!-- <td><?php echo $update['total_attendence'] . "/" . $update['attendence']; ?></td> -->
                                                                <td><?php echo $value['scale_of_pay']; ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['basic_pay'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['da'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['hra'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['ta'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['other_allowance'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gross_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['gross_salary_al'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['other_deduction'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pf_earning'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['pf'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['lwp'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['profession_tax'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['income_tax'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['advance'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['loan'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['total_deduction'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['nett_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['addition'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"><?php echo number_format($update['total_salary'], 2, '.', ''); ?></td>
                                                                <td style="mso-number-format:General;text-align:right;"></td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                            $net_oa += $update['other_allowance'];
                                                            $net_add += $update['addition'];
                                                            $grossTotal += $update['gross_salary'];
                                                            $net_grosstotal_al += $update['gross_salary_al'];
                                                            $net_other_deduction += $update['other_deduction'];
                                                            $net_pf += $update['pf'];
                                                            $net_pf_earning += $update['pf_earning'];
                                                            $net_lwp += $update['lwp'];
                                                            $net_pt += $update['profession_tax'];
                                                            $net_it += $update['income_tax'];
                                                            $net_advance += $update['advance'];
                                                            $net_loan += $update['loan'];
                                                            $finaltotalDeduct += $update['total_deduction'];
                                                            $NettTotal += $update['nett_salary'];
                                                            $grandTotal += $update['total_salary'];
                                                            ?>
                                                            <?php
                                                        }
                                                        */
                                                }
                                        ?>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_oa"><?php echo number_format($net_oa, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_basic_pay"><?php echo number_format($grossTotal, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><?php echo number_format($net_grosstotal_al, 2, ".", ""); ?></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_other_deduction"><?php echo number_format($net_other_deduction, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b><?php echo number_format($net_pf_earning,2,".",""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_pf"><?php echo number_format($net_pf, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_lwp"><?php echo number_format($net_lwp, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_pt"><?php echo number_format($net_pt, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_it"><?php echo number_format($net_it, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_advance"><?php echo number_format($net_advance, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_loan"><?php echo number_format($net_loan, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="totaldeduct"><?php echo number_format($finaltotalDeduct, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="netttotal"><?php echo number_format($NettTotal, 2, ".", ""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_add"><?php echo number_format($net_add,2,".",""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="gross_add"><?php echo number_format($net_hold,2,".",""); ?></b></td>
                                            <td style="mso-number-format:General;text-align:right;"><b id="grandtotal"><?php echo number_format($grandTotal,2,".",""); ?></b></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                }}
                ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

</script>
<script>
    $(document).ready(function() {
        // emptyDatatable('record-list', 'data');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#reportform', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        initDatatable('record-list', 'report/dtadmissionreport', response.params, [], 100);
                    }
                },
                error: function() { // your error handler
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });

    });
</script>
<script>
    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body><h3 style='text-align:center;'></h3>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }
//     function fnExcelReport() {
//     var tab = document.getElementById('headerTable'); // Get the table by its ID
//     var tab_text = "<table border='2px' style='border-collapse: collapse;'>";

//     // Loop through rows of the table
//     for (var j = 0; j < tab.rows.length; j++) {
//         var rowText = "<tr>";  // Start a new row
//         var row = tab.rows[j];

//         // Loop through each cell in the row
//         for (var i = 0; i < row.cells.length; i++) {
//             var cell = row.cells[i];
//             var cellText = cell.innerText.trim();

//             // Get the 'colspan' attribute, if present (default is 1 if no colspan)
//             var colspan = cell.getAttribute('colspan') || 1;
//             colspan = parseInt(colspan);  // Convert to integer

//             // Check if the cell content is a number
//             if (!isNaN(cellText) && cellText !== "") {
//                 // Apply number format if it's a number (mso-number-format)
//                 rowText += "<td  >" + cellText + "</td>";
//             } else {
//                 // Otherwise, just add the text as is and center-align it
//                 rowText += "<td colspan='" + colspan + "''>" + cellText + "</td>";
//             }

//             // Skip over the spanned columns
//             i += (colspan - 1); // This ensures we don't process the same columns for colspanned cells
//         }

//         rowText += "</tr>";  // End the row
//         tab_text += rowText;  // Add row to the table HTML
//     }

//     tab_text += "</table>";

//     // Remove unnecessary HTML elements (like links, images, etc.)
//     tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); // Remove links
//     tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // Remove images
//     tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // Remove input elements

//     // Add inline CSS for Excel cell styling (like border, number formatting)
//     tab_text = "<style>table, td { border: 1px solid black; }</style>" + tab_text;

//     var blob = new Blob([tab_text], { type: 'application/vnd.ms-excel' });
//     var url = URL.createObjectURL(blob);

//     // Create a temporary link to trigger the download
//     var a = document.createElement('a');
//     a.href = url;
//     a.download = 'report.xls';
//     document.body.appendChild(a);
//     a.click();
//     document.body.removeChild(a);

//     // Revoke the object URL to free up memory
//     URL.revokeObjectURL(url);
// }



    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr >";
        var textRange;
        var j = 0;
        tab = document.getElementById('headerTable'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
        } else //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return (sa);
    }
</script>