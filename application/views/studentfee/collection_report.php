<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('studentfee/collection_report') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('duration'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $search ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_type'); ?></label>

                                    <select id="feetype_id" name="feetype_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($feetypeList as $feetype) {
                                        ?>
                                            <option value="<?php echo $feetype['id'] ?>" <?php
                                                                                            if (set_value('feetype_id') == $feetype['id']) {
                                                                                                echo "selected =selected";
                                                                                            }
                                                                                            ?>><?php echo $feetype['type'] ?></option>

                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('collect') . " " . $this->lang->line('by'); ?></label>
                                    <select class="form-control" name="collect_by">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                        foreach ($collect_by as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($received_by)) && ($received_by == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('collect_by'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('group') . " " . $this->lang->line('by'); ?></label>
                                    <select class="form-control" name="group">
                                        <?php foreach ($group_by as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($group_byid)) && ($group_byid == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (empty($results)) {
                    ?>

                        <div class="alert alert-info">
                            <?php echo $this->lang->line('no_record_found'); ?>
                        </div>
                    <?php
                    } else { ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report'); ?> From <?php echo date('d/m/Y',strtotime($start_date))." To ".date('d/m/y',strtotime($end_date)); ?></h3>
                            </div>
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead">
                                    <center>
                                        <h5><?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report') . "<br>";
                                                        $this->customlib->get_postmessage();
                                                        ?></h5>
                                    </center>
                                </div>
                                <div class="download_label"><?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report') . "<br>";
                                                                        $this->customlib->get_postmessage();
                                                                        ?></div>

                                <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>

                                <table class="table table-striped table-bordered table-hover " id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th><?php echo $this->lang->line('payment_id'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th><?php echo $this->lang->line('collect_by'); ?></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $count = 1;
                                        $grdamountLabel = array();
                                        $grddiscountLabel = array();
                                        $grdfineLabel = array();
                                        $grdTotalLabel = array();

                                        foreach ($results as $key => $value) {
                                            $payment_id = array();
                                            $date = array();
                                            $student_name = array();
                                            $student_class = array();
                                            $fees_type = array();
                                            $pay_mode = array();
                                            $collection_by = array();
                                            $amountLabel = array();
                                            $discountLabel = array();
                                            $fineLabel = array();
                                            $TotalLabel = array();
                                            $admission_no = array();
                                            foreach ($value as $collect) {
                                                $payment_id[] = $collect['id'] . "/" . $collect['inv_no'];
                                                $date[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date']));
                                                $student_name[] = $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname);

                                                $admission_no[] =   $collect['admission_no'];

                                                $student_class[] = $collect['class'] . " (" . $collect['section'] . ")";
                                                $fees_type[] = $collect['type'];
                                                $pay_mode[] = $collect['payment_mode'];
                                                if (is_array($collect['received_byname'])) {
                                                    $collection_by[] = $collect['received_byname']['name'] . " (" . $collect['received_byname']['employee_id'] . ")";
                                                }

                                                $amountLabel[] = number_format($collect['amount'], 2, '.', '');
                                                $discountLabel[] = number_format($collect['amount_discount'], 2, '.', '');
                                                $fineLabel[] = number_format($collect['amount_fine'], 2, '.', '');
                                                $t = $collect['amount'] + $collect['amount_fine'];
                                                $TotalLabel[] = number_format($t, 2, '.', '');
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <table width="100%"><?php foreach ($payment_id as $p_ides) {
                                                                        ?>
                                                            <tr>
                                                                <td style="mso-number-format:'\@'" class="text-left-md payment_id"><?php echo $p_ides; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php foreach ($date as $date_val) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $date_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php foreach ($admission_no as $admission_no) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $admission_no; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php foreach ($student_name as $student_name_val) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $student_name_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php
                                                                        foreach ($student_class as $student_class_val) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $student_class_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php foreach ($fees_type as $fees_type_val) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $fees_type_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%">

                                                        <?php foreach ($collection_by as $collection_by_val) {
                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $collection_by_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table width="100%"><?php foreach ($pay_mode as $pay_mode_val) {
                                                                        ?>
                                                            <tr>
                                                                <td class="text-left-md"><?php echo $pay_mode_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td class="text text-right">
                                                    <table width="100%"><?php foreach ($amountLabel as $amountLabel_val) {
                                                                        ?>
                                                            <tr>
                                                                <td><?php echo $amountLabel_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                                <td class="text text-right">
                                                    <table width="100%"><?php foreach ($discountLabel as $discountLabel_val) {
                                                                        ?>
                                                            <tr>
                                                                <td><?php echo $discountLabel_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>

                                                </td>
                                                <td class="text text-right">
                                                    <table width="100%"><?php foreach ($fineLabel as $fineLabel_val) {
                                                                        ?>
                                                            <tr>
                                                                <td><?php echo $fineLabel_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>

                                                </td>
                                                <td class="text text-right">
                                                    <table width="100%"><?php foreach ($TotalLabel as $TotalLabel_val) {
                                                                        ?>
                                                            <tr>
                                                                <td><?php echo $TotalLabel_val; ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </table>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                            if ($subtotal) {
                                            ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-weight:bold"><?php echo $this->lang->line('sub') . " " . $this->lang->line('total') ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php echo array_sum($amountLabel); ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php echo array_sum($discountLabel); ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php echo array_sum($fineLabel); ?></td>
                                                    <td class="text text-right " style="font-weight:bold"><?php echo array_sum($TotalLabel); ?></td>
                                                </tr>
                                        <?php
                                            }
                                            $grdamountLabel[] = array_sum($amountLabel);
                                            $grddiscountLabel[] = array_sum($discountLabel);
                                            $grdfineLabel[] = array_sum($fineLabel);
                                            $grdTotalLabel[] = array_sum($TotalLabel);
                                        }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight:bold"><?php echo $this->lang->line('grand') . " " . $this->lang->line('total'); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo array_sum($grdamountLabel); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo array_sum($grddiscountLabel); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo array_sum($grdfineLabel); ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo array_sum($grdTotalLabel); ?></td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<iframe id="txtArea1" style="display:none"></iframe>

<script>
    <?php
    if ($search_type == 'period') {
    ?>

        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>


    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";
    document.getElementById("printhead").style.display = "none";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        document.getElementById("printhead").style.display = "block";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title>Fee Collection Report</title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        document.getElementById("printhead").style.display = "none";
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
        link.download = "studentfee_collection_report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }
</script>