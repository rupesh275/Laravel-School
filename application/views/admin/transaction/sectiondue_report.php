<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

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
                    <!-- <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div> -->



                    <div class="row">

                        <?php

                        // echo "<pre>";
                        // print_r ($result);
                        // echo "</pre>";

                        if (isset($class_section_list) && !empty($class_section_list)) {
                        ?>

                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Section Due Fees Report"; ?></h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <div class="download_label"><?php
                                                                echo "Class Wise Fees Report" . "<br>";
                                                                $this->customlib->get_postmessage();
                                                                ?></div>
                                    <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                    <table class="table table-striped table-hover" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-left"><?php echo "Section"; ?></th>
                                                <!-- <th class="text-left"><?php //echo $this->lang->line('class'); 
                                                                            ?></th> -->
                                                <!-- <th class="text-left"><?php //echo $this->lang->line('section'); 
                                                                            ?></th> -->

                                                <th><?php echo $this->lang->line('total_fees'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo $this->lang->line('paid_fees'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                                <th><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>

                                                <th><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($sch_section_result)) {

                                                $subgrandTotal = 0;
                                                $subgranddepositfee = 0;
                                                $subgranddiscount = 0;
                                                $subgrandfine = 0;
                                                $subgrandbalance = 0;
                                                foreach ($sch_section_result as  $sch_section) {

                                                    $classes = $this->student_model->getclassbysectionid($sch_section['id']);

                                                    // $grand_total = array();
                                                    // $grand_depositfee = array();
                                                    // $grand_discount = array();
                                                    // $grand_fine = array();
                                                    // $grand_balance = array();
                                                    // $grand_total=array();
                                                    $totalgrand = 0;
                                                    $totaldepositfee = 0;
                                                    $totaldiscount = 0;
                                                    $totalfine = 0;
                                                    $totalbalance = 0;

                                                    foreach ($classes as $key => $class) {
                                                        $studentlist = $this->student_model->reportClassSection2($class->class_id, $class->section_id);

                                                        $grand_total = !empty($studentlist['grand_total']) ? $studentlist['grand_total'] : 0;
                                                        $grand_depositfee = !empty($studentlist['grand_depositfee']) ? $studentlist['grand_depositfee'] : 0;
                                                        $grand_discount = !empty($studentlist['grand_discount']) ? $studentlist['grand_discount'] : 0;
                                                        $grand_fine = !empty($studentlist['grand_fine']) ? $studentlist['grand_fine'] : 0;
                                                        $grand_balance = !empty($studentlist['grand_balance']) ? $studentlist['grand_balance'] : 0;

                                                        $totalgrand += $grand_total;
                                                        $totaldepositfee += $grand_depositfee;
                                                        $totaldiscount += $grand_discount;
                                                        $totalfine += $grand_fine;
                                                        $totalbalance += $grand_balance;

                                            ?>
                                                        <tr style="display:none">
                                                            <td><?php echo $sch_section['sch_section']; ?></td>
                                                            <td><?php echo $class->class; ?></td>
                                                            <td><?php echo $class->section; ?></td>

                                                            <td class="text-right">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td><?php if (!empty($studentlist['grand_total'])) {
                                                                                echo $studentlist['grand_total'];
                                                                            } else {
                                                                                echo "0";
                                                                            }; ?></td>
                                                                    <tr>
                                                                </table>
                                                            </td>
                                                            <td class="text-right">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td><?php if (!empty($studentlist['grand_depositfee'])) {
                                                                                echo $studentlist['grand_depositfee'];
                                                                            } else {
                                                                                echo "0";
                                                                            } ?></td>
                                                                    <tr>
                                                                </table>
                                                            </td>
                                                            <td class="text-right">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td><?php if (!empty($studentlist['grand_discount'])) {
                                                                                echo $studentlist['grand_discount'];
                                                                            } else {
                                                                                echo "0";
                                                                            }  ?></td>
                                                                    <tr>

                                                                </table>
                                                            </td>
                                                            <td class="text-right">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td><?php if (!empty($studentlist['grand_fine'])) {
                                                                                echo $studentlist['grand_fine'];
                                                                            } else {
                                                                                echo "0";
                                                                            } ?></td>
                                                                    <tr>
                                                                </table>
                                                            </td>
                                                            <td class="text-right">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td><?php if (!empty($studentlist['grand_balance'])) {
                                                                                echo $studentlist['grand_balance'];
                                                                            } else {
                                                                                echo "0";
                                                                            } ?></td>
                                                                    <tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <!-- <tr class="box box-solid total-bg">
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                               
                                                                <td><?php //echo $this->lang->line('total'); 
                                                                    ?></td>
                                                                <td class="text-right"><?php //echo array_sum($totalfeelabel); 
                                                                                        ?></td>
                                                                <td class="text-right"><?php //echo array_sum($depositfeelabel); 
                                                                                        ?></td>
                                                                <td class="text-right"><?php //echo array_sum($discountlabel); 
                                                                                        ?></td>
                                                                <td class="text-right"><?php //echo array_sum($finelabel); 
                                                                                        ?></td>
                                                                <td class="text-right"><?php //echo array_sum($balancelabel); 
                                                                                        ?></td>
                                                            </tr> -->
                                                    <?php
                                                        // $class = '';
                                                        // $grand_total[] = array_sum($totalfeelabel);
                                                        // $grand_depositfee[] = array_sum($depositfeelabel);
                                                        // $grand_discount[] = array_sum($discountlabel);
                                                        // $grand_fine[] = array_sum($finelabel);
                                                        // $grand_balance[] = array_sum($balancelabel);
                                                    } ?>

                                                    <tr>
                                                        <td><?php echo $sch_section['sch_section']; ?></td>
                                                        <td><?php echo $totalgrand; ?></td>
                                                        <td><?php echo $totaldepositfee; ?></td>
                                                        <td><?php echo $totaldiscount; ?></td>
                                                        <td><?php echo $totalfine; ?></td>
                                                        <td><?php echo $totalbalance; ?></td>

                                                    </tr>
                                            <?

                                                    $subgrandTotal += $totalgrand;
                                                    $subgranddepositfee += $totaldepositfee;
                                                    $subgranddiscount += $totaldiscount;
                                                    $subgrandfine += $totalfine;
                                                    $subgrandbalance += $totalbalance;
                                                }
                                            }
                                            ?>

                                            <?php ?>
                                            <tr class="box box-solid total-bg">

                                                <td><?php echo $this->lang->line('grand_total');
                                                    ?></td>
                                                <td><?php echo $subgrandTotal;
                                                    ?></td>
                                                <td><?php echo $subgranddepositfee;
                                                    ?></td>
                                                <td><?php echo $subgranddiscount;
                                                    ?></td>
                                                <td><?php echo $subgrandfine;
                                                    ?></td>
                                                <td><?php echo $subgrandbalance;
                                                    ?></td>
                                            </tr>
                                        <?php } ?>
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