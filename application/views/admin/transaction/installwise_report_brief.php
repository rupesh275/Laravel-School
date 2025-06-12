<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    table, th, td {
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
                    <form action="<?php echo site_url('admin/transaction/installwise_report_brief') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                // $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($section_list as $value) {
                                            ?>
                                                <option <?php
                                                        if ($value['section_id'] == $section_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo "Section"; ?></label>
                                        <select autofocus="" id="sch_section_id" name="sch_section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sch_section_result as $sch_section) {
                                            ?>
                                                <option value="<?php echo $sch_section['id'] ?>" <?php if (set_value('sch_section_id') == $sch_section['id']) {
                                                                                                        echo "selected=selected";
                                                                                                    } ?>><?php echo $sch_section['sch_section'] ?></option>
                                            <?php

                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('sch_section_id'); ?></span>
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

                        // echo "<pre>";
                        // print_r ($result);
                        // echo "</pre>";

                        if (isset($students) && !empty($students)) {
                        ?>

                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Brief fees report of the class [".$class_name."-".$division . "] as on ".date('d-m-Y'); ?></h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php
                                                                echo "Brief fees report of the class [".$class_name."-".$division . "]  as on ".date('d-m-Y')."<br>";
                                                                $this->customlib->get_postmessage();
                                                                ?></div>
                                    <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                    <table class="table table-striped table-bordered table-hover" class="display" style="width:100%" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="2">Students</th>
                                                <th class="text-center" colspan="5">Net</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center"><?php echo $this->lang->line('roll_no'); ?></th>
                                                <th class="text-center"><?php echo $this->lang->line('student') . " " . $this->lang->line('name'); ?></th>

                                                <th class="text-center"><?php echo $this->lang->line('pay') . " " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Disc " . $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-center"><?php echo "Outstanding (%)"; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
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
                                            $student_cnt=0;
                                            if (!empty($students)) {
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
                                                        $discount = 0;
                                                        //echo "<pre>";
                                                        //print_r($student_total_fees);

                                                        foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                                                            $inst_payamount[$rw] = 0;
                                                            $inst_paidamount[$rw] = 0;
                                                            $inst_balamount[$rw] = 0;
                                                            $inst_discamount[$rw] = 0;

                                                            foreach ($student_total_fees_value as $ff) {
                                                                $fees = $ff->fees;

                                                                //print_r($fees);

                                                                if (!empty($fees)) {
                                                                    $late_adm_disc = 0;
                                                                    foreach ($fees as $key => $each_fee_value) {
                                                                        //print_r($each_fee_value);
                                                                        //echo $each_fee_value->amount."--";
                                                                        $inst_payamount[$rw] += $each_fee_value->amount;
                                                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                                                        if (is_object($amount_detail)) {
                                                                            $paid_amt_loop = 0;
                                                                            $paid_disc=0;
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
                                                                                        $paid_disc += $amount_detail_value->amount_discount;    
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    $inst_discamount[$rw] +=  $amount_detail_value->amount_discount;
                                                                                    $paid_disc += $amount_detail_value->amount_discount;
                                                                                }
                                                                            }

                                                                            $inst_balamount[$rw] += ($each_fee_value->amount - ($paid_amt_loop + $paid_disc + $late_adm_disc));
                                                                            //$inst_discamount[$rw] += $paid_disc;
                                                                            $totalfee += ($each_fee_value->amount-$late_adm_disc);
                                                                            $deposit += ($paid_amt_loop + $paid_disc );
                                                                            $discount += $paid_disc;
                                                                        } else {
                                                                            //echo "<br>".$inst_balamount[$rw]."-".$inst_payamount[$rw];
                                                                            $inst_balamount[$rw] += ($each_fee_value->amount);
                                                                            $totalfee += $each_fee_value->amount;
                                                                            //$deposit += 0;
                                                                            //$balance = $totalfee - $deposit;

                                                                        }
                                                                        $inst_payamount[$rw] -= $late_adm_disc;
                                                                        
                                                                        //echo $inst_balamount[$rw] . "-";



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
                                                            {$balance = $totalfee - $deposit;}
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

                                                        $totalfee   =  ( $inst_payamount[0]+ $inst_payamount[1]+ $inst_payamount[2]);
                                                        $deposit    =  ($inst_paidamount[0] + $inst_paidamount[1] + $inst_paidamount[2]);
                                                        $discount   =   ($inst_discamount[0]+$inst_discamount[1]+$inst_discamount[2]);
                                                        $balance    =   ($inst_balamount[0]+$inst_balamount[1]+$inst_balamount[2]);

                                                        $netpay += ( $inst_payamount[0]+ $inst_payamount[1]+ $inst_payamount[2]);
                                                        $netdeposit += ($inst_paidamount[0] + $inst_paidamount[1] + $inst_paidamount[2]);
                                                        $netdisc += ($inst_discamount[0]+$inst_discamount[1]+$inst_discamount[2]);
                                                        $netbalance += ($inst_balamount[0]+$inst_balamount[1]+$inst_balamount[2]);

                                                        
                                                        if(($deposit>0) || ($student['session_is_active']  == "yes" && $deposit<=0)) {
                                                            ++$student_cnt;
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $student['roll_no']; ?></td>
                                                            <td <?php if ($student['session_is_active']  == "no"){ ?> style="color:red;" <?php } ?> ><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>


                                                            <?php
                                                            if ($balance <= 0) {
                                                                $osamt = 0;
                                                            } else {
                                                                $osamt = round(($balance / $totalfee)  * 100, 2);
                                                            }
                                                            ?>
                                                            <td class="text-right"><?php echo $totalfee;
                                                                ?></td>
                                                            <td class="text-right"><?php echo $deposit;
                                                                ?></td>
                                                            <td class="text-right"><?php echo $discount;
                                                                ?></td>
                                                            <td class="text-right"><?php echo $balance;
                                                                ?></td>
                                                            <td class="text-right"><?php echo $osamt; ?></td>

                                                        </tr>
                                                <?php } }
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $student_cnt; ?></td>
                                                    <td><b>Total</b></td>
                                                    <!-- 4th -->
                                                    <td class="text-right"><?php echo $netpay; ?></td>
                                                    <td class="text-right"><?php echo $netdeposit; ?></td>
                                                    <td class="text-right"><?php echo $netdisc; ?></td>
                                                    <td class="text-right"><?php echo $netbalance; ?></td>
                                                    <!-- outstanding -->
                                                    <?php 
                                                    if ($netbalance <= 0) {
                                                       $os = 0;
                                                    } else {
                                                        $os = round(($netbalance / $netpay) * 100, 2);
                                                    }
                                                    
                                                     ?>
                                                    <td class="text-right"><?php echo number_format($os, 2); ?></td>

                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><b>Outstanding %</b></td>
                                                    <!-- 4st -->
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right"><?php echo number_format($os, 2); ?>%</td>
                                                    <!-- outstanding -->
                                                    <td></td>

                                                </tr>

                                        <?php
                                            }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

                    <?php
                    ?>



                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    function removeElement() {
        document.getElementById("imgbox1").style.display = "block";
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

    $(document).ready(function() {
        $("ul.type_dropdown input[type=checkbox]").each(function() {
            $(this).change(function() {
                var line = "";
                $("ul.type_dropdown input[type=checkbox]").each(function() {
                    if ($(this).is(":checked")) {
                        line += $("+ span", this).text() + ";";
                    }
                });
                $("input.form-control").val(line);
            });
        });
    });
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