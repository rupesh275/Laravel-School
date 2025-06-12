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




                    <div class="row">

                        <?php

                        // echo "<pre>";
                        // print_r ($result);
                        // echo "</pre>";

                        if (isset($class_section_list) && !empty($class_section_list)) {
                        ?>

                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Class Wise Installment Report As On ".date('d-m-Y'); ?></h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php
                                                                echo "Installment Wise Fees Report" . "<br>";
                                                                $this->customlib->get_postmessage();
                                                                ?></div>
                                    <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                    <table class="table table-striped table-hover table-bordered" class="display" style="width:100%" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="2"></th>
                                                <th class="text-center" colspan="3">Installment -1</th>
                                                <th class="text-center" colspan="1">Installment -2</th>
                                                <th class="text-center" colspan="3">Installment -3</th>
                                                <th class="text-center" colspan="1">Net</th>
                                            </tr>
                                            <tr>
                                                <th class="text-left"><?php echo $this->lang->line('class'); ?></th>
                                                <th class="text-left"><?php echo $this->lang->line('section'); ?></th>

                                                <th class="text-center"><?php echo "Tution Fees"; ?><span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                                <th class="text-center"><?php echo "Term Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Dues" . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                

                                                <th class="text-center"><?php echo "Activity Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                                <th class="text-center"><?php echo "Tution Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Term Fees"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Dues "; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                

                                                <th class="text-center"><?php echo "Net Dues"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            if (!empty($class_section_list)) {
                                                $class_wise_dues = array('ACT-FEES' => 0,'TRM1-FEE' => 0,'TUT-fees(a-s)' => 0,'TRM2-FEE' => 0,'TUT-FEES(O-M)' => 0,'TOTAL' => 0 );
                                                $net_fees = array('ACT-FEES' => 0,'TRM1-FEE' => 0,'TUT-fees(a-s)' => 0,'TRM2-FEE' => 0,'TUT-FEES(O-M)' => 0,'TOTAL' => 0 );
                                                foreach ($class_section_list as $key => $class_section) {
                                                    //if($class_section->class_id == 3 && $class_section->section_id == 2) {
                                                    foreach($class_wise_dues as $x => $x_value) {
                                                        $class_wise_dues[$x] = 0;
                                                      }  
                                                    
                                                    $students = $this->student_model->getStudentByClassSectionIDforinstall($class_section->class_id, $class_section->section_id);
                                                    foreach ($students as  $student) {
                                                        
                                                        $student_total_fees = array();
                                                        $student_total_fees[] = $this->studentfeemaster_model->getStudentFees_main($student['student_session_id']);
                                                        if (!empty($student_total_fees)) {


                                                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                                                                
                                                                foreach ($student_total_fees_value as $ff) {
                                                                    $fees = $ff->fees;
                                                                    // if ($student_total_fees_value->fees_type == 'm') {
                                                                    if (!empty($fees)) {
                                                                        $late_adm_disc=0;
                                                                        foreach ($fees as $key => $each_fee_value) {
                                                                            if ($student['session_is_active']  == "yes") {
                                                                            $fullfee = $each_fee_value->amount;
                                                                            }
                                                                            $inst_payamount[$rw] += $each_fee_value->amount;
                                                                            $amount_detail = json_decode($each_fee_value->amount_detail);
                                                                            if (is_object($amount_detail)) {
                                                                                foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                                                    if ($student['session_is_active']  == "yes") {
                                                                                    $fullfee = $fullfee - ($amount_detail_value->amount + $amount_detail_value->amount_discount);
                                                                                    }
                                                                                }
                                                                                $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $disc_loop + $late_adm_disc));
                                                                            } else {

                                                                            }
                                                                            $class_wise_dues[$each_fee_value->code] = $class_wise_dues[$each_fee_value->code] + $fullfee;
                                                                            //$net_fees[$each_fee_value->code]  = $net_fees[$each_fee_value->code] + $fullfee;
                                                                            $fullfee=0;
                                                                        }  
                                                                    }
                                                                }
                                                            }
                                            ?>
                                                    <?php
                                                        }
                                                    } 
                                                    //if($netpay > 0) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $class_section->class; ?></td>
                                                        <td class="text-center"><?php echo $class_section->section; ?></td>
                                                        <!-- 1st -->
                                                        <td class="text-right"><?php echo $class_wise_dues['TUT-fees(a-s)']; ?></td>
                                                        <td class="text-right"><?php echo $class_wise_dues['TRM1-FEE']; ?></td>                                                       
                                                        <td class="text-right"><?php echo $class_wise_dues['TUT-fees(a-s)']+$class_wise_dues['TRM1-FEE']; ?></td>
                                                        <!-- 2nd -->
                                                        <td class="text-right"><?php echo $class_wise_dues['ACT-FEES']; ?></td>
                                                        <!-- 3rd -->
                                                        <td class="text-right"><?php echo $class_wise_dues['TUT-FEES(O-M)']; ?></td>
                                                        <td class="text-right"><?php echo $class_wise_dues['TRM2-FEE']; ?></td>                                                       
                                                        <td class="text-right"><?php echo $class_wise_dues['TUT-FEES(O-M)']+$class_wise_dues['TRM2-FEE']; ?></td>
                                                        <!-- 4th -->
                                                        <?php 
                                                        $net_outstanding = $class_wise_dues['TUT-fees(a-s)']+$class_wise_dues['TRM1-FEE'] + $class_wise_dues['ACT-FEES'] + $class_wise_dues['TUT-FEES(O-M)']+$class_wise_dues['TRM2-FEE'];
                                                        $net_fees['TOTAL'] = $net_fees['TOTAL'] + ($class_wise_dues['TUT-fees(a-s)']+$class_wise_dues['TRM1-FEE'] + $class_wise_dues['ACT-FEES'] + $class_wise_dues['TUT-FEES(O-M)']+$class_wise_dues['TRM2-FEE']);
                                                        $net_fees['TUT-fees(a-s)'] = $net_fees['TUT-fees(a-s)'] + $class_wise_dues['TUT-fees(a-s)'];
                                                        $net_fees['TRM1-FEE'] = $net_fees['TRM1-FEE'] + $class_wise_dues['TRM1-FEE'];
                                                        $net_fees['ACT-FEES'] = $net_fees['ACT-FEES'] + $class_wise_dues['ACT-FEES'];
                                                        $net_fees['TUT-FEES(O-M)'] = $net_fees['TUT-FEES(O-M)'] + $class_wise_dues['TUT-FEES(O-M)'];
                                                        $net_fees['TRM2-FEE'] = $net_fees['TRM2-FEE'] + $class_wise_dues['TRM2-FEE'];
                                                         ?>
                                                        <td class="text-right"><?php echo $net_outstanding; ?></td>


                                                        

                                                    </tr>
                                                <?php // }
                                                 
                                                
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="text-right"><b>Total</b></td>
                                                    <!-- 1st -->
                                                        <!-- 1st -->
                                                        <td class="text-right"><?php echo $net_fees['TUT-fees(a-s)']; ?></td>
                                                        <td class="text-right"><?php echo $net_fees['TRM1-FEE']; ?></td>                                                       
                                                        <td class="text-right"><?php echo $net_fees['TUT-fees(a-s)']+$net_fees['TRM1-FEE']; ?></td>
                                                        <!-- 2nd -->
                                                        <td class="text-right"><?php echo $net_fees['ACT-FEES']; ?></td>
                                                        <!-- 3rd -->
                                                        <td class="text-right"><?php echo $net_fees['TUT-FEES(O-M)']; ?></td>
                                                        <td class="text-right"><?php echo $net_fees['TRM2-FEE']; ?></td>                                                       
                                                        <td class="text-right"><?php echo $net_fees['TUT-FEES(O-M)']+$net_fees['TRM2-FEE']; ?></td>
                                                        <!-- 4th -->
                                                        <td class="text-right"><?php echo $net_fees['TOTAL']; ?></td>
                                                    <!-- outstanding -->
                                                </tr>
                                        <?
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