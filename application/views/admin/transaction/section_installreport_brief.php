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
                        if (isset($class_section_list) && !empty($class_section_list)) {
                        ?>

                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Sree Narayana Guru Central School(CBSE)<br>"; ?></h3>
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Section Wise Brief Fees Report as on ".date('d-m-Y'); ?></h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php
                                                                echo "Section Wise Brief Fees Report as on ".date('d-m-Y') . "<br>";
                                                                $this->customlib->get_postmessage();
                                                                ?></div>
                                    <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                    <table class="table table-striped table-hover" class="display" style="width:100%" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan=""></th>
                                                <th class="text-center" colspan="5">Net</th>
                                            </tr>
                                            <tr>
                                                <th class="text-left"><?php echo "Section"; ?></th>

                                                <th><?php echo $this->lang->line('pay') . " " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo "Disc " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo "Outstanding"; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($sch_section_result)) {
                                                $grandPayTotal1 = 0;
                                                $grandPayTotal2 = 0;
                                                $grandPayTotal3 = 0;
                                                $grandPaidTotal1 = 0;
                                                $grandPaidTotal2 = 0;
                                                $grandPaidTotal3 = 0;
                                                $grandDiscTotal1 = 0;
                                                $grandDiscTotal2 = 0;
                                                $grandDiscTotal3 = 0;

                                                $grandBalanceTotal1 = 0;
                                                $grandBalanceTotal2 = 0;
                                                $grandBalanceTotal3 = 0;
                                                $grandNetPayTotal1 = 0;
                                                $grandNetPaidTotal2 = 0;
                                                $grandNetDiscTotal = 0;
                                                $grandNetBalanceTotal3 = 0;

                                                foreach ($sch_section_result as  $sch_section) {
                                                    $classes = $this->student_model->getclassbysectionid($sch_section['id']);
                                                    $grandPay1 = 0;
                                                    $grandPay2 = 0;
                                                    $grandPay3 = 0;
                                                    $grandPaid1 = 0;
                                                    $grandPaid2 = 0;
                                                    $grandPaid3 = 0;
                                                    $grandDisc1 = 0;
                                                    $grandDisc2 = 0;
                                                    $grandDisc3 = 0;
                                                    $grandBalance1 = 0;
                                                    $grandBalance2 = 0;
                                                    $grandBalance3 = 0;
                                                    $grandNetPay1 = 0;
                                                    $grandNetPaid2 = 0;
                                                    $grandNetDisc = 0;
                                                    $grandNetBalance3 = 0;
                                                    foreach ($classes as $key => $class_section) {
                                                        $students = $this->student_model->getStudentByClassSectionIDforinstall($class_section->class_id, $class_section->section_id);

                                                        $netpay = 0;
                                                        $netdeposit = 0;
                                                        $netdisc = 0;
                                                        $netbalance = 0;
                                                        $total_Payinstall1 = 0;
                                                        $total_Payinstall2 = 0;
                                                        $total_Payinstall3 = 0;
                                                        $total_Paidinstall1 = 0;
                                                        $total_Paidinstall2 = 0;
                                                        $total_Paidinstall3 = 0;
                                                        $total_disc1 = 0;
                                                        $total_disc2 = 0;
                                                        $total_disc3 = 0;
            
                                                        $total_Balinstall1 = 0;
                                                        $total_Balinstall2 = 0;
                                                        $total_Balinstall3 = 0;
                                                        foreach ($students as  $student) {
                                                            $student_total_fees = array();
                                                            $student_total_fees[] = $this->studentfeemaster_model->getStudentFees_main($student['student_session_id']);
                                                            if (!empty($student_total_fees)) {
    
                                                                $deposit = 0;
                                                                $discount = 0;
                                                                $balance = 0;
                                                                $fine = 0;
    
                                                                $inst_payamount = array(0, 0, 0, 0);
                                                                $inst_paidamount = array(0, 0, 0, 0);
                                                                $inst_discamount = array(0, 0, 0, 0);
                                                                $inst_fineamount = array(0, 0, 0, 0);
                                                                $inst_balamount = array(0, 0, 0, 0);
                                                                $rw = 0;
                                                                $totalfee = 0;
                                                                $deposit = 0;
                                                                $balance = 0;
    
                                                                // $installtwo = [];
    
                                                                // echo "<pre>";
                                                                // print_r ($student_total_fees[0]);
                                                                // echo "</pre>";
                                                                //echo "<pre>";
                                                                //print_r($student_total_fees);
                                                                foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                                                                    $inst_payamount[$rw] = 0;
                                                                    $inst_paidamount[$rw] = 0;
                                                                    $inst_discamount[$rw] = 0;
                                                                    $inst_balamount[$rw] = 0;
                                                                    foreach ($student_total_fees_value as $ff) {
                                                                        $fees = $ff->fees;
                                                                        // if ($student_total_fees_value->fees_type == 'm') {
                                                                        if (!empty($fees)) {
                                                                            $late_adm_disc=0;
                                                                            foreach ($fees as $key => $each_fee_value) {
                                                                                $inst_payamount[$rw] += $each_fee_value->amount;
                                                                                $amount_detail = json_decode($each_fee_value->amount_detail);
                                                                                if (is_object($amount_detail)) {
                                                                                    $paid_amt_loop = 0;
                                                                                    $disc_loop = 0;
                                                                                    $late_adm_disc = 0;
                                                                                    foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                                                        $inst_paidamount[$rw] +=  $amount_detail_value->amount;
                                                                                        $paid_amt_loop += $amount_detail_value->amount;
                                                                                        $inst_fineamount[$rw] +=  $amount_detail_value->amount_fine;
                                                                                        if(isset($amount_detail_value->discount_id))
                                                                                        {
                                                                                            if($amount_detail_value->discount_id==7)
                                                                                            {
                                                                                                $late_adm_disc = $amount_detail_value->amount_discount;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $inst_discamount[$rw] +=  $amount_detail_value->amount_discount;
                                                                                                $disc_loop += $amount_detail_value->amount_discount;    
                                                                                            }
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $inst_discamount[$rw] +=  $amount_detail_value->amount_discount;
                                                                                            $disc_loop += $amount_detail_value->amount_discount;
                                                                                        }
    
                                                                                    }
                                                                                    $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $disc_loop + $late_adm_disc));
                                                                                    if ($student['session_is_active']  == "no") {
                                                                                        $totalfee += $paid_amt_loop;
                                                                                    }
                                                                                    else {
                                                                                    $totalfee += ($each_fee_value->amount - $late_adm_disc);
                                                                                    }
                                                                                    $deposit += ($paid_amt_loop );
                                                                                    $discount += $disc_loop;
                                                                                } else {
                                                                                    //echo "<br>".$inst_balamount[$rw]."-".$inst_payamount[$rw];
                                                                                    
                                                                                    $inst_balamount[$rw] += ($each_fee_value->amount);
                                                                                    $totalfee += $each_fee_value->amount;
                                                                                    //$deposit += 0;
                                                                                    //$balance = $totalfee - $deposit;
                                                                                }
                                                                                $inst_payamount[$rw] -= $late_adm_disc;
                                                                                
                                                                            }
                                                                        }
                                                                        if ($student['session_is_active']  == "no") {
                                                                            $inst_payamount[$rw]= $inst_paidamount[$rw];
                                                                            $inst_balamount[$rw]=0;
                                                                        }
                                                                        ++$rw;
                                                                    }
                                                                    if ($student['session_is_active']  == "no") {
                                                                        $balance=0;
                                                                        }
                                                                        else
                                                                        {$balance = $totalfee - $deposit - $discount;}
                                                                }
                                                                $total_Payinstall1  += $inst_payamount[0];
                                                                $total_Payinstall2  += $inst_payamount[1];
                                                                $total_Payinstall3  += $inst_payamount[2];
    
                                                                $total_Paidinstall1 += !empty($inst_paidamount[0]) ? $inst_paidamount[0] : "0";
                                                                $total_Paidinstall2 += !empty($inst_paidamount[1]) ? $inst_paidamount[1] : "0";
                                                                $total_Paidinstall3 += !empty($inst_paidamount[2]) ? $inst_paidamount[2] : "0";
                                                                
                                                                $total_disc1 += !empty($inst_discamount[0]) ? $inst_discamount[0] : "0";
                                                                $total_disc2 += !empty($inst_discamount[1]) ? $inst_discamount[1] : "0";
                                                                $total_disc3 += !empty($inst_discamount[2]) ? $inst_discamount[2] : "0";
        
                                                                $total_Balinstall1  += !empty($inst_balamount[0]) ? $inst_balamount[0] : "0";
                                                                $total_Balinstall2  += !empty($inst_balamount[1]) ? $inst_balamount[1] : "0";
                                                                $total_Balinstall3  += !empty($inst_balamount[2]) ? $inst_balamount[2] : "0";
    
                                                                // $netpay += $totalfee;
                                                                // $netdeposit += $deposit;
                                                                // $netdisc += $discount;
                                                                // $netbalance += $balance;
                                                                $netpay += ( $inst_payamount[0]+ $inst_payamount[1]+ $inst_payamount[2]);
                                                                $netdeposit += ($inst_paidamount[0] + $inst_paidamount[1] + $inst_paidamount[2]);
                                                                $netdisc += ($inst_discamount[0]+$inst_discamount[1]+$inst_discamount[2]);
                                                                $netbalance += ($inst_balamount[0]+$inst_balamount[1]+$inst_balamount[2]);
    
                                                ?>
    
                                                        <?php
                                                            }
                                                        } ?>


                                                    <?php
                                                        $grandPay1          += $total_Payinstall1;
                                                        $grandPay2          += $total_Payinstall2;
                                                        $grandPay3          += $total_Payinstall3;
                                                        $grandPaid1         += $total_Paidinstall1;
                                                        $grandPaid2         += $total_Paidinstall2;
                                                        $grandPaid3         += $total_Paidinstall3;
                                                        $grandDisc1         += $total_disc1;
                                                        $grandDisc2         += $total_disc2;
                                                        $grandDisc3         += $total_disc3;
                                                        $grandBalance1      += $total_Balinstall1;
                                                        $grandBalance2      += $total_Balinstall2;
                                                        $grandBalance3      += $total_Balinstall3;
                                                        $grandNetPay1       += $netpay;
                                                        $grandNetPaid2      += $netdeposit;
                                                        $grandNetDisc       += $netdisc;
                                                        $grandNetBalance3   += $netbalance;
                                                    }

                                                    ?>


                                                    <tr>
                                                        <td><?php echo $sch_section['sch_section']; ?></td>
                                                        <!-- 4th -->
                                                        <td class="text-right"><?php echo $grandNetPay1; ?></td>
                                                        <td class="text-right"><?php echo $grandNetPaid2; ?></td>
                                                        <td class="text-right"><?php echo $grandNetDisc; ?></td>
                                                        <td class="text-right"><?php echo $grandNetBalance3; ?></td>
                                                        <td class="text-right"><?php
                                                                                if ($grandNetPay1 == 0) {
                                                                                    echo 0;
                                                                                } else {
                                                                                    echo round(($grandNetBalance3 / $grandNetPay1) * 100, 2);
                                                                                }
                                                                                ?>%</td>
                                                    </tr>

                                                <?php
                                                    $grandPayTotal1                 += $grandPay1;
                                                    $grandPayTotal2                 += $grandPay2;
                                                    $grandPayTotal3                 += $grandPay3;
                                                    $grandPaidTotal1                += $grandPaid1;
                                                    $grandPaidTotal2                += $grandPaid2;
                                                    $grandPaidTotal3                += $grandPaid3;
                                                    $grandBalanceTotal1             += $grandBalance1;
                                                    $grandBalanceTotal2             += $grandBalance2;
                                                    $grandBalanceTotal3             += $grandBalance3;
                                                    $grandDiscTotal1                += $grandDisc1;
                                                    $grandDiscTotal2                += $grandDisc2;
                                                    $grandDiscTotal3                += $grandDisc3;
                                                    $grandNetPayTotal1              += $grandNetPay1;
                                                    $grandNetPaidTotal2             += $grandNetPaid2;
                                                    $grandNetDiscTotal              += $grandNetDisc;
                                                    $grandNetBalanceTotal3          += $grandNetBalance3;
                                                } ?>
                                                <tr>
                                                    <td><b>Total</b></td>
                                                    <!-- 4th -->
                                                    <td class="text-right"><?php echo $grandNetPayTotal1; ?></td>
                                                    <td class="text-right"><?php echo $grandNetPaidTotal2; ?></td>
                                                    <td class="text-right"><?php echo $grandNetDiscTotal; ?></td>
                                                    <td class="text-right"><?php echo $grandNetBalanceTotal3; ?></td>
                                                    <td class="text-right"><?php
                                                                            if ($grandBalanceTotal3 <= 0) {
                                                                                echo 0;
                                                                            } else {
                                                                                echo round(($grandNetBalanceTotal3 / $grandNetPayTotal1) * 100, 2);
                                                                            }
                                                                            ?>%</td>
                                                </tr>
                                        <?
                                            }
                                        } ?>

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

    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            ordering: false,
            paging: false,
            bSort: false,
            info: false
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