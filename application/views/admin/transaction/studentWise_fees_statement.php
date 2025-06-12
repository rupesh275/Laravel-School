<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    table,
    th,
    td {
        border: 1px solid black !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/transaction/studentWise_fees_statement') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo "As On Date"; ?></label>
                                        <input type="date" name="asondate" id="asondate" value = "<?php if(isset($asondate)) { echo $asondate; } else {echo date('Y-m-d');} ?>" >
                                        <span class="text-danger"><?php echo form_error('asondate'); ?></span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><?php echo "Student Session"; ?></label>
                                        <select class="form-control" name="rep_session_id">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php
                                            foreach ($sessionlist as $key => $value) {
                                            ?>
                                                <option value="<?php echo $value['id'] ?>" <?php
                                                                                        if ((isset($rep_session_id)) && ($rep_session_id == $value['id'])) {
                                                                                            echo "selected";
                                                                                        }
                                                                                        ?>><?php echo $value['session'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('rep_session_id'); ?></span>
                                    </div>                                    
                                </div>                              
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <?php
                        if(isset($asondate)) {
                            
                        if (isset($class_section_list) && !empty($class_section_list)) {
                        ?>
                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Student Wise Fees Statement As On " . date('d-m-Y'); ?></h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php
                                                                echo "Student Wise Fees Statement Report" . "<br>";
                                                                $this->customlib->get_postmessage();
                                                                ?></div>
                                    <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                    <table class="table table-striped table-hover table-bordered" class="display" style="width:100%" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="5">Student Detail</th>
                                                <th class="text-center" colspan="5">Admission</th>
                                                <th class="text-center" colspan="4">Payable</th>
                                                <th class="text-center" colspan="6">Paid</th>
                                                <th class="text-center" colspan="4">Dues</th>
                                            </tr>
                                            <tr>
                                                <th class="text-left"><?php echo $this->lang->line('class'); ?></th>
                                                <th class="text-left"><?php echo $this->lang->line('section'); ?></th>
                                                <th class="text-left"><?php echo "Roll No"; ?></th>
                                                <th class="text-left"><?php echo $this->lang->line('student') . " " . $this->lang->line('name'); ?></th>
                                                <th class="text-left"><?php echo "Status"; ?></th>
                                                <!-- Admission -->
                                                <th class="text-center"><?php echo "Admission Fees"; ?><span><?php echo "(" . $currency_symbol . ")"; ?>Payable</span></th>
                                                <th class="text-center"><?php echo "Discount"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Paid"; ?><span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Inactive Dues"; ?><span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Dues"; ?><span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                                <!-- Payable -->
                                                <th class="text-center"><?php echo "Tution Fees"; ?><span><?php echo "(" . $currency_symbol . ")"; ?>Payable</span></th>
                                                <th class="text-center"><?php echo "Term Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Payable</span></th>
                                                <th class="text-center"><?php echo "Activity Fees"; ?><span><?php echo "(" . $currency_symbol . ")"; ?>Payable</span></th>
                                                <th class="text-center"><?php echo "Total Fees"; ?><span>Payable</span></th>

                                                <!-- Paid -->
                                                <th class="text-center"><?php echo "Tution Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Paid</span></th>
                                                <th class="text-center"><?php echo "Term Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Paid</span></th>
                                                <th class="text-center"><?php echo "Activity Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Paid</span></th>
                                                <th class="text-center"><?php echo "Discount"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Paid</span></th>
                                                <th class="text-center"><?php echo "Inactive Dues"; ?> <span></span></th>
                                                <th class="text-center"><?php echo "Total Fees"; ?> <span>Paid</span></th>

                                                <!-- Dues -->
                                                <th class="text-center"><?php echo "Tution Fees Net"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?> Dues</span></th>
                                                <th class="text-center"><?php echo "Term Fees Net"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?>Dues</span></th>
                                                <th class="text-center"><?php echo "Activity Dues Net Amount"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Total Net Dues"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            ini_set('display_errors', 1);
                                            $grandtotalDuesAmt = 0;
                                            $net_adm_payable =0;$net_adm_discount=0;$net_admpaid=0;$net_admdues=0;
                                            $net_tution_payable =0;$net_tution_discount=0;$net_tution_paid=0;$net_tution_dues=0;
                                            $net_term_payable =0;$net_term_discount=0;$net_term_paid=0;$net_term_dues=0;
                                            $net_active_payable =0;$net_active_discount=0;$net_active_paid=0;$net_active_dues=0;
                                            $net_inactive_dues=0;$net_adm_inactive_dues = 0;
                                            $net_fees_payable=0;$net_fees_paid=0;$net_fees_dues=0;
                                            $net_discount =0;
                                            


                                            if (!empty($class_section_list)) {
                                                $studentFeesPayable = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                $paidFees = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                $GrandTotalFees = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                $GrandTotalPaidFees = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                $admissionFees = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                $discountFeesArray = array('TUT-fees(a-s)' => 0, 'TUT-FEES(O-M)' => 0, 'TRM1-FEE' => 0, 'TRM2-FEE' => 0, 'ACT-FEES' => 0, 'TOTAL' => 0, "adm-fees" => 0, "APF" => 0, "DIC" => 0, "CBC" => 0, "Fine" => 0, "CAUTD" => 0);
                                                foreach ($GrandTotalFees as $z => $x_value) {
                                                    $GrandTotalFees[$z] = 0; //loop for Grand Total Payable
                                                }
                                                foreach ($GrandTotalPaidFees as $l => $x_value) {
                                                    $GrandTotalPaidFees[$l] = 0; //loop for Grand Total Paid
                                                }
                                                $totalDiscount = 0;
                                                $totalAdmDues = 0;
                                                $grandDiscountTotal = 0;
                                                $prevAmounts = 0;
                                                
                                                $grandTutionDuesTotal= 0;
                                                $grandtermFeesDues = 0;
                                                $grandactivityDues = 0;
                                                $grandtotalDuesAmt = 0;     
                                                $rrw=0; 
                                                $total_act = 0;$total_inact=0;$tot_adm=0;                                          
                                                foreach ($class_section_list as $key => $class_section) {
                                                    ++$rrw;
                                                    $resultlist = $this->student_model->getStudentByClassSectionIDforstatementwithinactive($class_section->class_id, $class_section->section_id,$rep_session_id);
                                                    foreach ($resultlist as $key => $student) {
                                                        if($student['admission_date'] <= $asondate || $student['admission_date'] = '' ) {
                                                        //echo "<pre>";print_r($student);die();
                                                        foreach ($studentFeesPayable as $x => $x_value) {
                                                            $studentFeesPayable[$x] = 0;  // Loop for Payable amount
                                                        }
                                                        foreach ($paidFees as $y => $x_value) {
                                                            $paidFees[$y] = 0; //Loop for Paid amount
                                                        }
                                                        foreach ($discountFeesArray as $y => $x_value) {
                                                            $discountFeesArray[$y] = 0; //Loop for Paid amount
                                                        }
                                                        $discount = 0;
                                                        $lateAdmDisc = 0;
                                                        $desc_codes="";
                                                        $studentTotalFeesPaying = array();
                                                        $studentTotalFeesPaying[] = $this->studentfeemaster_model->getStudentFeesAll_for_dues($student['student_session_id']);
                                                        $prev_rec = $this->studentfee_model->get_previous_student_fees($student['student_session_id']);
                                                        if (!empty($prev_rec)) {
                                                            $total_prev_dues = round($prev_rec['pay_amount'] - $prev_rec['paid_amount'], 2);
                                                        } else {
                                                            $total_prev_dues = 0;
                                                        }
                                                        $i=1;
                                                        if (!empty($studentTotalFeesPaying)) {
                                                            // echo "<pre>";
                                                            // print_r($studentTotalFeesPaying);die();
                                                            foreach ($studentTotalFeesPaying as $studentTotalFeesPaying_key => $studentTotalFeesPaying_value) {
                                                                foreach ($studentTotalFeesPaying_value as $ff) {
                                                                    $fees = $ff->fees;
                                                                    $fullfee = 0;
                                                                    if (!empty($fees)) {
                                                                        
                                                                        foreach ($fees as $key => $each_fee_value) {
                                                                            //if ($student['session_is_active']  == "yes") {
                                                                                $fullfee = $each_fee_value->amount;
                                                                            //}
                                                                            $amount_detail = json_decode($each_fee_value->amount_detail);
                                                                            
                                                                            if (is_object($amount_detail)) {
                                                                                // echo "<pre>";
                                                                                // print_r($amount_detail);
                                                                                // if($i==2)
                                                                                // {
                                                                                //     die();
                                                                                // }
                                                                                // ++$i;
                                                                                foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                                                        if($amount_detail_value->date <= $asondate ) {
                                                                                            $paidFeesff = ($amount_detail_value->amount);
                                                                                            $paidFees[$each_fee_value->code] = $paidFees[$each_fee_value->code] + $paidFeesff;
                                                                                            // if (isset($amount_detail_value->discount_id) && $amount_detail_value->discount_id != 7) {
                                                                                            $discountFeesArray[$each_fee_value->code] = $discountFeesArray[$each_fee_value->code] + $amount_detail_value->amount_discount;
                                                                                            $admissionFees[$each_fee_value->code] =  $amount_detail_value->amount_discount;
                                                                                            if ($each_fee_value->code == "adm-fees") {
                                                                                                $grandDiscountTotal += $amount_detail_value->amount_discount;
                                                                                            }
                                                                                            elseif($amount_detail_value->amount_discount > 0 && $amount_detail_value->discount_id == 7)
                                                                                            {
                                                                                                $lateAdmDisc += $amount_detail_value->amount_discount;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $discount += $amount_detail_value->amount_discount;
                                                                                            }
                                                                                            
                                                                                            $GrandTotalPaidFees[$each_fee_value->code] = $GrandTotalPaidFees[$each_fee_value->code] + $paidFeesff;
                                                                                        }
                                                                                }
                                                                            } else {
                                                                            }
                                                                            $studentFeesPayable[$each_fee_value->code] = $studentFeesPayable[$each_fee_value->code] + $fullfee;
                                                                            $GrandTotalFees[$each_fee_value->code] = $GrandTotalFees[$each_fee_value->code] + $fullfee;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        // echo $lateAdmDisc;
                                                        // Students Have To Pay as Payble
                                                        $tutionFeesTotal = $studentFeesPayable['TUT-fees(a-s)'] + $studentFeesPayable['TUT-FEES(O-M)'] - $lateAdmDisc;
                                                        $termFeesPayableTotal = $studentFeesPayable['TRM1-FEE'] + $studentFeesPayable['TRM2-FEE'];
                                                        $totalFeesPayable = $tutionFeesTotal + $termFeesPayableTotal + $studentFeesPayable['ACT-FEES']; //Total FeesPayable

                                                        // Students Have Paid 
                                                        $tutionFeesPaidTotal = $paidFees['TUT-fees(a-s)'] + $paidFees['TUT-FEES(O-M)'];
                                                        $termFeesPaidTotal = $paidFees['TRM1-FEE'] + $paidFees['TRM2-FEE'];
                                                        $totalFeesPaid = $tutionFeesPaidTotal + $termFeesPaidTotal + $paidFees['ACT-FEES'];

                                                        // Student Dues Amount 
                                                        // if ($lateAdmDisc > 0) {
                                                        //     $tutionDuesAmt = $tutionFeesTotal - $tutionFeesPaidTotal; //Tution Fees Net (Rs.) Dues
                                                        //     $termFeesDues  = $termFeesPayableTotal - $termFeesPaidTotal; //Term Fees Net (Rs.)Dues
                                                        //     $activityDues  = $studentFeesPayable['ACT-FEES'] - $paidFees['ACT-FEES']; //Activity Dues Net Amount (Rs.)
                                                        //     $totalDuesAmt  = $tutionDuesAmt + $termFeesDues + $activityDues; //Total Net Dues (Rs.)
                                                        // }else {
                                                            if ($student['session_is_active']  == "yes") {
                                                                $tutionDuesAmt = $tutionFeesTotal - $tutionFeesPaidTotal - $discountFeesArray['TUT-fees(a-s)'] - $discountFeesArray['TUT-FEES(O-M)']; //Tution Fees Net (Rs.) Dues
                                                                $termFeesDues  = $termFeesPayableTotal - $termFeesPaidTotal - $discountFeesArray['TRM1-FEE'] - $discountFeesArray['TRM2-FEE']; //Term Fees Net (Rs.)Dues
                                                                $activityDues  = $studentFeesPayable['ACT-FEES'] - $paidFees['ACT-FEES'] - $discountFeesArray['ACT-FEES']; //Activity Dues Net Amount (Rs.)
                                                                $totalDuesAmt  = $tutionDuesAmt + $termFeesDues + $activityDues; //Total Net Dues (Rs.)
                                                            }
                                                        // }
                                                        // admission
                                                        if ($student['session_is_active']  == "yes") {
                                                            $disadmamt = $studentFeesPayable['adm-fees'] > 0 ? $studentFeesPayable['adm-fees'] : 0;
                                                            $totalAdmissionDues = $disadmamt - ($admissionFees['adm-fees'] + $paidFees['adm-fees']);
                                                            $totalAdmDues += $disadmamt > 0 ? $totalAdmissionDues : 0;
                                                            //echo "<br>(".$student['roll_no'].")\ ".$studentFeesPayable['adm-fees']."/".$disadmamt."/".$totalAdmissionDues;
                                                        }
                                                        else
                                                        {
                                                            $disadmamt = $studentFeesPayable['adm-fees'] > 0 ? $studentFeesPayable['adm-fees'] : 0;
                                                            $totalAdmissionDues = $disadmamt - ($admissionFees['adm-fees'] + $paidFees['adm-fees']);
                                                            $totalAdmDues += $disadmamt > 0 ? $totalAdmissionDues : 0;
                                                            $totalAdmissionDues=0;
                                                        }
                                                        if ($student['session_is_active']  == "no") {
                                                            $grandtotalDuesAmt += 0;
                                                        } else {
                                                            $grandTutionDuesTotal += $tutionDuesAmt;
                                                            $grandtermFeesDues  += $termFeesDues;
                                                            $grandactivityDues  += $activityDues;
                                                            $grandtotalDuesAmt += $totalDuesAmt;
                                                        }


                                                        $incative_dues = $totalFeesPayable - ($tutionFeesPaidTotal+$termFeesPaidTotal+$paidFees['ACT-FEES']+$discount);
                                                        
                                                        if ($student['session_is_active'] == "no") {
                                                            $tutionDuesAmt = 0;
                                                            $termFeesDues = 0;
                                                            $activityDues = 0;
                                                            $totalDuesAmt = 0;
                                                        }
                                                        else
                                                        {
                                                            $tutionDuesAmt = $tutionFeesTotal - $tutionFeesPaidTotal;
                                                            $termFeesDues = $termFeesPayableTotal - $termFeesPaidTotal;
                                                            $activityDues = $studentFeesPayable['ACT-FEES'] - $paidFees['ACT-FEES'];
                                                            $disc = $discount;
                                                            if($disc > 0 ) {  if($tutionDuesAmt >= $disc) {$tutionDuesAmt-=$disc;$disc=0;} else {$disc-=$tutionDuesAmt;$tutionDuesAmt=0;}  }
                                                            if($disc > 0 ) {  if($termFeesDues >= $disc) {$termFeesDues-=$disc;$disc=0;} else {$disc-=$termFeesDues;$termFeesDues=0;}  }
                                                            if($disc > 0 ) {  if($activityDues >= $disc) {$activityDues-=$disc;$disc=0;} else {$disc-=$activityDues;$activityDues=0;}  }
                                                            $totalDuesAmt = $tutionDuesAmt + $termFeesDues + $activityDues;
                                                        }
                                                        //fine calculation start
                                                        //$due_date = '12-2024';
                                                        // if(!empty($fine_result)){
                                                        //     $last_month_date = $this->studentfee_model->getPreviousMonthLastDay($asondate);
                                                        //     $fine_status = $this->studentfee_model->get_current_month_fine_collection($student['student_session_id'],$asondate);
                                                        //     $diff_month = $this->studentfee_model->getMonthsDifference($due_date,date('m-Y',strtotime($asondate)));
                                                        //     $fine_amt_payable = $this->studentfee_model->get_previous_month_fine_collection($student['student_session_id'],$asondate);
                                                        //     $fine_amt_paid = $fine_amt_payable;
                                                        //     $fine_amt = 0;
                                                        //     if($diff_month > 0 && $fine_status == 0)
                                                        //     {
                                                        //         $fine_amt = ($diff_month * $fine_result['fine_amount']) ;
                                                                
                                                        //         $fine_amt_payable += $fine_amt;
                                                        //     }
                                                        //     $net_fees_payable += $fine_amt_payable;
                                                        //     $net_fees_paid += $fine_amt_paid;
                                                        //     $fine_amt_balance = $fine_amt_payable - $fine_amt_balance;
                                                        //     $totalDuesAmt = $totalDuesAmt + $fine_amt_balance;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $fine_amt_payable = 0;      $fine_amt_balance = 0;
                                                        //     $prev_fine_amt_paid = 0;    $fine_amt = 0;
                                                        // }
                                                        //fine calculation end here
                                                    ?>  
                                                        <tr>
                                                            <td><?php echo $student['class']; ?></td>
                                                            <td class="text-center"><?php echo $student['section']; ?></td>
                                                            <td class="text-center"><?php echo $student['roll_no']; ?></td>
                                                            <td <?php if ($student['session_is_active']  == "no") { ?> style="color:red;" <?php } ?> class="text-center"><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                            <td> <?php if ($student['session_is_active']  == "no") { ++$total_inact; echo "0"; } else { ++$total_act;echo "1" ;} ?></td>
                                                            <!-- Students Admission section -->
                                                            <td class="text-right"><?php if($studentFeesPayable['adm-fees']>0) { ++$tot_adm; } echo $studentFeesPayable['adm-fees']; $net_adm_payable+=$studentFeesPayable['adm-fees']; $adm_inactive_dues = $studentFeesPayable['adm-fees'] ?></td>
                                                            <td class="text-right"><?php echo $studentFeesPayable['adm-fees'] > 0 ? $admissionFees['adm-fees'] : 0; if($studentFeesPayable['adm-fees']>0) { $net_adm_discount+=$admissionFees['adm-fees'];$adm_inactive_dues -= $admissionFees['adm-fees'] ; } ?></td>
                                                            <td class="text-right"><?php echo $paidFees['adm-fees']; $net_admpaid+=$paidFees['adm-fees'];$adm_inactive_dues -= $paidFees['adm-fees'] ; ?></td>
                                                            <?php if ($student['session_is_active']  == "no") { ?>
                                                                <td class="text-right"><?php echo $adm_inactive_dues; $net_adm_inactive_dues += $adm_inactive_dues; ?></td>
                                                                <td class="text-right"><?php echo 0; ?></td>
                                                            <?php } else { ?>
                                                                <td class="text-right">0</td>
                                                                <td class="text-right"><?php echo $studentFeesPayable['adm-fees'] > 0 ? $totalAdmissionDues : 0;  if($studentFeesPayable['adm-fees']>0) { $net_admdues += $totalAdmissionDues; } ?></td>
                                                            <?php } ?>
                                                            <!-- 1st  Students Have To Pay as Payble -->
                                                            <td class="text-right"><?php echo $tutionFeesTotal; $net_tution_payable+=$tutionFeesTotal; ?></td>
                                                            <td class="text-right"><?php echo $termFeesPayableTotal; $net_term_payable+=$termFeesPayableTotal; ?></td>
                                                            <td class="text-right"><?php echo $studentFeesPayable['ACT-FEES']; $net_active_payable += $studentFeesPayable['ACT-FEES']; ?></td>
                                                            <td class="text-right"><?php echo $totalFeesPayable; $net_fees_payable+=$totalFeesPayable; ?></td>
                                                            <!-- 2nd Students Paid Amount As Current Session -->
                                                            <td class="text-right"><?php echo $tutionFeesPaidTotal; $net_tution_paid +=$tutionFeesPaidTotal; ?></td>
                                                            <td class="text-right"><?php echo $termFeesPaidTotal; $net_term_paid+=$termFeesPaidTotal; ?></td>
                                                            <td class="text-right"><?php echo $paidFees['ACT-FEES']; $net_active_paid+=$paidFees['ACT-FEES']; ?></td>
                                                            <td class="text-right"><?php echo $discount; $totalDiscount += $discount; ?></td>
                                                            <?php if ($student['session_is_active']  == "no") { ?>
                                                                <td class="text-right"><?php echo $incative_dues ; $net_inactive_dues+=$incative_dues; ?></td>
                                                            <?php } else { ?>
                                                                <td class="text-right"><?php echo 0; ?></td>
                                                            <?php } ?>
                                                            <td class="text-right"><?php echo $totalFeesPaid; $net_fees_paid  += $totalFeesPaid; ?></td>
                                                            <!-- 3rd  Student Dues Amount As Current Session -->
                                                            <td class="text-right"><?php echo $tutionDuesAmt; $net_tution_dues+=$tutionDuesAmt; ?></td>
                                                            <td class="text-right"><?php echo $termFeesDues; $net_term_dues+=$termFeesDues; ?></td>
                                                            <td class="text-right"><?php echo $activityDues; $net_active_dues+=$activityDues; ?></td>
                                                            <?php if ($student['session_is_active']  == "no") { ?>
                                                                <td class="text-right"><?php echo 0; ?></td>
                                                            <?php } else { ?>
                                                                <td class="text-right"><?php echo $totalDuesAmt; $net_fees_dues+=$totalDuesAmt; ?></td>     
                                                            <?php } ?>

                                                        </tr>
                                                <?php
                                                    }
                                                    }
                                                    ++$rrw;
                                                    // if($rrw==4)
                                                    // {break;}
                                                }

                                                $GrandTotalTutionFees = $GrandTotalFees['TUT-fees(a-s)'] + $GrandTotalFees['TUT-FEES(O-M)'];
                                                $GrandTotalTermFees = $GrandTotalFees['TRM1-FEE'] + $GrandTotalFees['TRM2-FEE'];
                                                $GrandActivityFees = $GrandTotalFees['ACT-FEES'];
                                                $GrandTotalFeesPayable =  $GrandTotalTutionFees + $GrandTotalTermFees + $GrandActivityFees;
                                                // Grand Total Paid amounts
                                                $GrandTotalPaidAmounts = $GrandTotalPaidFees['TUT-fees(a-s)'] + $GrandTotalPaidFees['TUT-FEES(O-M)'];
                                                $GrandTermPaidAmounts = $GrandTotalPaidFees['TRM1-FEE'] + $GrandTotalPaidFees['TRM2-FEE'];
                                                $GrandActivityPaidAmounts = $GrandTotalPaidFees['ACT-FEES'];
                                                $GrandFeesPaidAmounts = $GrandTotalPaidAmounts + $GrandTermPaidAmounts + $GrandTotalPaidFees['ACT-FEES'];
                                                // Dues Total Amounts
                                                // $grandTutionDuesTotal = $GrandTotalTutionFees - $GrandTotalPaidAmounts;
                                                // $grandtermFeesDues  = $GrandTotalTermFees - $GrandTermPaidAmounts;
                                                // $grandactivityDues  = $GrandTotalFees['ACT-FEES'] - $GrandTotalPaidFees['ACT-FEES'];
                                                // // $grandtotalDuesAmt  = $grandTutionDuesTotal + $grandtermFeesDues;
                                                //by manoj
                                                
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-right"><b><?php echo "Active : " . $total_act .", Inactive : " . $total_inact . ", Admisson : " . $tot_adm;  ?></b></td>
                                                    <td colspan="1" class="text-right"><b>Total</b></td>
                                                    <!-- Admission section -->
                                                    <td class="text-right"><b><?php echo $net_adm_payable; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_adm_discount ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_admpaid; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_adm_inactive_dues ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_admdues ; ?> </b></td>
                                                    <!-- 1st -->
                                                    <td class="text-right"><b><?php echo $net_tution_payable; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_term_payable ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_active_payable ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_fees_payable ; ?> </b></td>
                                                    <!-- 2nd -->
                                                    <td class="text-right"><b><?php echo $net_tution_paid; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_term_paid; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_active_paid ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $totalDiscount ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_inactive_dues ; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_fees_paid; ?> </b></td>
                                                    <!-- 3rd -->
                                                    <td class="text-right"><b><?php echo $net_tution_dues; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_term_dues; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_active_dues; ?> </b></td>
                                                    <td class="text-right"><b><?php echo $net_fees_dues; ?> </b></td>
                                                    <!-- outstanding -->
                                                </tr>
                                        <?
                                            }
                                            
                                        }
                                    }
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
                    $('#section_id').html(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
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

                    $('#section_id').html(div_data);
                }
            });
        });

        $(document).on('change', '#section_id', function(e) {
            getStudentsByClassAndSection();
        });
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);

    });

    function getStudentsByClassAndSection() {
        $('#student_id').html("");
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {
                'class_id': class_id,
                'section_id': section_id
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, obj) {
                    div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#student_id').append(div_data);
            }
        });
    }

    function removeElement() {
        document.getElementById("imgbox1").style.display = "block";
    }
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
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }

    function fnExcelReport() {
        exportToExcel();
    }

    function exportToExcel() {
        var htmls = "";
        var uri = 'data:application/vnd.ms-excel;base64,';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
        var base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        var format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };
        var tab_text = "<tr >";
        var textRange;
        var j = 0;
        var val = "";
        tab = document.getElementById('headerTable'); // id of table

        for (j = 0; j < tab.rows.length; j++) {

            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        var ctx = {
            worksheet: 'Worksheet',
            table: tab_text
        }


        var link = document.createElement("a");
        link.download = "student_wise_fees_statement.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }
</script>